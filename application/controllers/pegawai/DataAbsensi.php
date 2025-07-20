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
      $data['title'] = "Absensi";
      $data['pegawai'] = $pegawai;
      $this->load->view('templates_pegawai/header', $data);
      $this->load->view('templates_pegawai/sidebar');
      $this->load->view('pegawai/formAbsensi', $data);
      $this->load->view('templates_pegawai/footer');
  }

  // Guru klik "Attend" → validasi GPS → simpan
  public function do_attend() 
  {
      $this->load->helper('qr');
      $nip = $this->session->userdata('nip');
      $lat = $this->input->post('lat');
      $lon = $this->input->post('lon');
      $scanned_code = $this->input->post('qr_data');

      $now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
      $current_code = generate_daily_unique_code($now);

      if ($scanned_code !== $current_code) {
          $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong>Kode QR tidak valid atau sudah kadaluarsa!</strong>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
              </button>
          </div>');
          redirect('pegawai/dataAbsensi');
      }

      // Lokasi sekolah (contoh)
    //   $lat_school = -7.693513;
    //   $lon_school = 112.900412;
      $lat_school = -6.2357504;
      $lon_school = 106.8269568;
      $radius = 100; // meter
      // var_dump($lon);
      // die();
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
          // echo $meters;

          // echo "❌ Gagal: Anda di luar area sekolah.";
          // echo $radius;
          //     var_dump("tes");
          // die();
          //return;
          $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Anda berada diluar area sekolah!</strong>
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
          // var_dump($cek);
          // die();
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
          var_dump($data);
          die();
          $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Anda telah berhasil absen!</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
          </div>');
          redirect('pegawai/dataAbsensi');
      }

  }

  public function clockIn($id_penempatan) 
  {
      $nip = $this->input->post('nip');
      $lat = $this->input->post('lat');
      $lon = $this->input->post('lon');

      // Lokasi sekolah (contoh)
    //   $lat_school = -7.693513;
    //   $lon_school = 112.900412;
      $lat_school = -6.2357507;
      $lon_school = 106.840065;
      $radius = 100; // meter

      // Hitung jarak
      $theta = $lon - $lon_school;
      $dist = sin(deg2rad($lat)) * sin(deg2rad($lat_school)) +
              cos(deg2rad($lat)) * cos(deg2rad($lat_school)) * cos(deg2rad($theta));
      $dist = acos($dist);
      $dist = rad2deg($dist);
      $km = $dist * 60 * 1.1515 * 1.609344;
      $meters = $km * 1000;

      //print($meters);
      //print($radius);

      if ($meters > $radius) {
          //echo $meters;

          //echo "❌ Gagal: Anda di luar area sekolah.";
          //echo $radius;
          //var_dump($lat);
          //var_dump($lon);
          // die();
          //return;
          $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Anda berada diluar area sekolah!</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>');
          redirect('pegawai/dataPenempatan');
      }

      //$bulan = date('mY'); // contoh bulan + tahun jadi misalnya 072025

      $data = [
        'id_penempatan'    => $id_penempatan,
        'jam_clockin'      => date('Y-m-d H:i:s'),
        'jam_clockout'     => '',
        'lokasi_clockin'   => $lat . ',' . $lon,
        'lokasi_clockout'  => '',
        'total_jam'        => 0,
    ];
      $this->db->insert('absensi_mengajar', $data);
      // var_dump($data);
      // die();
      $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Anda telah berhasil absen!</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
      </div>');
      redirect('pegawai/dataPenempatan');
  }

  public function clockOut($id_penempatan) 
  {
      $nip = $this->input->post('nip');
      $lat = $this->input->post('lat');
      $lon = $this->input->post('lon');

      // Lokasi sekolah (contoh)
    //   $lat_school = -7.693513;
    //   $lon_school = 112.900412;
      $lat_school = -6.2357507;
      $lon_school = 106.840065;
      $radius = 100; // meter

      // Hitung jarak
      $theta = $lon - $lon_school;
      $dist = sin(deg2rad($lat)) * sin(deg2rad($lat_school)) +
              cos(deg2rad($lat)) * cos(deg2rad($lat_school)) * cos(deg2rad($theta));
      $dist = acos($dist);
      $dist = rad2deg($dist);
      $km = $dist * 60 * 1.1515 * 1.609344;
      $meters = $km * 1000;

      //print($meters);
      //print($radius);

      if ($meters > $radius) {
          //echo $meters;

          //echo "❌ Gagal: Anda di luar area sekolah.";
          //echo $radius;
          var_dump($lat);
          var_dump($lon);
          die();
          //return;
          $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Anda berada diluar area sekolah!</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>');
          redirect('pegawai/dataPenempatan');
      }

      $where = array(
        'id_penempatan' => $id
      );

      $absensi = $this->db->get_where('absensi_mengajar', ['id_penempatan' => $id_penempatan])->row();

      if ($absensi) {
          $data = [
              'id_penempatan'   => $id_penempatan,
              'jam_clockin'     => $absensi->jam_clockin,
              'jam_clockout'    => date('Y-m-d H:i:s'),
              'lokasi_clockin'  => $absensi->lokasi_clockin,
              'lokasi_clockout' => $lat . ',' . $lon,
              'total_jam'       => 0
          ];

          $where = ['id_penempatan' => $id_penempatan];

          $this->penggajianModel->update_data('absensi_mengajar', $data, $where);
          $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Anda telah berhasil absen!</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
          </div>');
          redirect('pegawai/dataPenempatan');
      } else {
          // Tangani kalau data tidak ditemukan
          // Misalnya redirect atau tampilkan error
          echo "Data absensi tidak ditemukan untuk id_penempatan: $id_penempatan";
      }

      // var_dump($data);
      // die();
      
  }
}
