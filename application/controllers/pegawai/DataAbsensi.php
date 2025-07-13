<?php

class DataAbsensi extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('hak_akses') != '2') {
      $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Anda belum login!</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
      redirect('welcome');
    }
  }
  // Guru scan QR → buka halaman form absensi
  public function index() 
  {
      $nip = $this->session->userdata('nip');
      $pegawai = $this->db->get_where('data_pegawai', ['nip' => $nip])->row();
      if (!$pegawai) {
          echo "Data pegawai tidak ditemukan!";
          return;
      }
      $data['pegawai'] = $pegawai;
      $this->load->view('templates_pegawai/header', $data);
      $this->load->view('templates_pegawai/sidebar');
      $this->load->view('pegawai/formScanQR', $data);
      $this->load->view('templates_pegawai/footer');
  }

  // Guru klik "Attend" → validasi GPS → simpan
  public function do_attend() 
  {
      $nip = $this->input->post('nip');
      $lat = $this->input->post('lat');
      $lon = $this->input->post('lon');

      // Lokasi sekolah (contoh)
    //   $lat_school = -7.693513;
    //   $lon_school = 112.900412;
      $lat_school = -6.2488578;
      $lon_school = 106.8433405;
      $radius = 100; // meter

      // Hitung jarak
      $theta = $lon - $lon_school;
      $dist = sin(deg2rad($lat)) * sin(deg2rad($lat_school)) +
              cos(deg2rad($lat)) * cos(deg2rad($lat_school)) * cos(deg2rad($theta));
      $dist = acos($dist);
      $dist = rad2deg($dist);
      $km = $dist * 60 * 1.1515 * 1.609344;
      $meters = $km * 1000;

      print($meters);
      print($radius);

      if ($meters > $radius) {
          echo $meters;

          echo "❌ Gagal: Anda di luar area sekolah.";
          echo $radius;
          //return;
          $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Anda telah berhasil absen</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
          </div>');
          redirect('pegawai/dataAbsensi');
      }

      $bulan = date('mY'); // contoh bulan + tahun jadi misalnya 072025
      $cek = $this->db->get_where('data_kehadiran', ['nip' => $nip, 'bulan' => $bulan])->row();

      if ($cek) {
          // Sudah ada record bulan ini, tambahkan hadir +1
          $this->db->set('hadir', 'hadir+1', FALSE);
          $this->db->where('id_kehadiran', $cek->id_kehadiran);
          $this->db->update('data_kehadiran');

          $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Anda telah berhasil absen</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
          </div>');
          redirect('pegawai/dataAbsensi');
      } else {
          // Belum ada record bulan ini, insert baru
          $pegawai = $this->db->get_where('data_pegawai', ['nip' => $nip])->row();
          $data = [
              'bulan'         => $bulan,
              'nip'           => $nip,
              'nama_pegawai'  => $pegawai->nama_pegawai,
              'jenis_kelamin' => $pegawai->jenis_kelamin,
              'nama_jabatan'  => $pegawai->jabatan,
              'hadir'         => 1,
              'sakit'         => 0,
              'izin'          => 0,
              'alpha'         => 0
          ];
          $this->db->insert('data_kehadiran', $data);
          echo $data;
          $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Anda telah berhasil absen!</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
          </div>');
          redirect('pegawai/dataAbsensi');
      }

  }

  // Generate QR unik per guru
  public function generate_qr($nip) {
      $filename = 'qr/' . $nip . '.png';
      if (!file_exists(FCPATH . $filename)) {
          $this->load->library('ciqrcode');
          $params['data'] = base_url('pegawai/dataAbsensi/attend/' . $nip); // url scan
          $params['level'] = 'H';
          $params['size'] = 10;
          $params['savename'] = FCPATH . $filename;
          $this->ciqrcode->generate($params);
      }
      echo "QR generated: <br><img src='" . base_url($filename) . "' width='200' />";
  }
}
