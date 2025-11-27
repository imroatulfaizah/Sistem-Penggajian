<?php

class DataAbsensi extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('hak_akses') != '1') {
      $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Anda belum login!</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
      redirect('welcome');
    }
  }

  public function index()
  {
    $data['title'] = "Data Absensi Pegawai";

    if ((isset($_GET['bulan']) && $_GET['bulan'] != '') && (isset($_GET['tahun']) && $_GET['tahun'] != '')) {
      $bulan = $_GET['bulan'];
      $tahun = $_GET['tahun'];
      $bulanTahun = $bulan . $tahun;
    } else {
      $bulan = date('m');
      $tahun = date('Y');
      $bulanTahun = $bulan . $tahun;
    }

    $data['absensi'] = $this->db->query("SELECT data_kehadiran.*, data_pegawai.nama_pegawai, data_pegawai.jenis_kelamin, data_pegawai.jabatan FROM data_kehadiran INNER JOIN data_pegawai ON data_kehadiran.nip = data_pegawai.nip INNER JOIN data_jabatan ON data_pegawai.jabatan = data_jabatan.id_jabatan WHERE data_kehadiran.bulan = '$bulanTahun' ORDER BY data_pegawai.nama_pegawai ASC")->result();

    $this->load->view('templates_admin/header', $data);
    $this->load->view('templates_admin/sidebar');
    $this->load->view('admin/RekapAbsen/dataAbsensi', $data);
    $this->load->view('templates_admin/footer');
  }

  // Guru scan QR → buka halaman form absensi
  public function attend($nip) 
  {
      $pegawai = $this->db->get_where('data_pegawai', ['nip' => $nip])->row();
      if (!$pegawai) {
          echo "Data pegawai tidak ditemukan!";
          return;
      }
      $data['pegawai'] = $pegawai;
      $this->load->view('admin/RekapAbsen/formScanAbsensi', $data); // view baru, kita bikin di bawah
  }

  // Guru klik "Attend" → validasi GPS → simpan
  public function do_attend() 
  {
      $nip = $this->input->post('nip');
      $lat = $this->input->post('lat');
      $lon = $this->input->post('lon');

      // Lokasi sekolah (contoh)
      $lat_school = -6.200000;
      $lon_school = 106.816666;
      $radius = 100; // meter

      // Hitung jarak
      $theta = $lon - $lon_school;
      $dist = sin(deg2rad($lat)) * sin(deg2rad($lat_school)) +
              cos(deg2rad($lat)) * cos(deg2rad($lat_school)) * cos(deg2rad($theta));
      $dist = acos($dist);
      $dist = rad2deg($dist);
      $km = $dist * 60 * 1.1515 * 1.609344;
      $meters = $km * 1000;

      if ($meters > $radius) {
          echo "❌ Gagal: Anda di luar area sekolah.";
          return;
      }

      $bulan = date('mY'); // contoh bulan + tahun jadi misalnya 072025
      $cek = $this->db->get_where('data_kehadiran', ['nip' => $nip, 'bulan' => $bulan])->row();

      if ($cek) {
          // Sudah ada record bulan ini, tambahkan hadir +1
          $this->db->set('hadir', 'hadir+1', FALSE);
          $this->db->where('id_kehadiran', $cek->id_kehadiran);
          $this->db->update('data_kehadiran');
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
      }

      echo "✅ Berhasil absen!";
  }

  public function inputAbsensi()
  {
    if ($this->input->post('submit', TRUE) == 'submit') {
      $post = $this->input->post();

      foreach ($post['bulan'] as $key => $value) {
        if ($post['bulan'][$key] != '' || $post['nip'][$key] != '') {
          $simpan[] = array(
            'bulan'         => $post['bulan'][$key],
            'nip'           => $post['nip'][$key],
            'nama_pegawai'  => $post['nama_pegawai'][$key],
            'jenis_kelamin' => $post['jenis_kelamin'][$key],
            'nama_jabatan'  => $post['nama_jabatan'][$key],
            'hadir'         => $post['hadir'][$key],
            'sakit'         => $post['sakit'][$key],
            'izin'         => $post['izin'][$key],
            'alpha'         => $post['alpha'][$key],
          );
        }
      }
      $this->penggajianModel->insert_batch('data_kehadiran', $simpan);
      $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Data berhasil ditambahkan</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
      redirect('admin/dataAbsensi');
    }

    $data['title'] = "Form Input Absensi";

    if ((isset($_GET['bulan']) && $_GET['bulan'] != '') && (isset($_GET['tahun']) && $_GET['tahun'] != '')) {
      $bulan = $_GET['bulan'];
      $tahun = $_GET['tahun'];
      $bulanTahun = $bulan . $tahun;
    } else {
      $bulan = date('m');
      $tahun = date('Y');
      $bulanTahun = $bulan . $tahun;
    }

    $data['inputAbsensi'] = $this->db->query("SELECT data_pegawai.*, data_jabatan.nama_jabatan FROM data_pegawai INNER JOIN data_jabatan ON data_pegawai.jabatan = data_jabatan.id_jabatan WHERE NOT EXISTS(SELECT * FROM data_kehadiran WHERE bulan = '$bulanTahun' AND data_pegawai.nip = data_kehadiran.nip) ORDER BY data_pegawai.nama_pegawai ASC")->result();
    $this->load->view('templates_admin/header', $data);
    $this->load->view('templates_admin/sidebar');
    $this->load->view('admin/RekapAbsen/formInputAbsensi', $data);
    $this->load->view('templates_admin/footer');
  }
  public function updateData($id)
  {
    // $where = array('id_jabatan' => $id);
    $data['title'] = "Update Data Absensi";
    $data['absensi'] = $this->db->query("SELECT * FROM data_kehadiran WHERE id_kehadiran = '$id'")->result();
    // $data['absensi'] = $this->penggajianModel->get_data('data_kehadiran')->result();
    $this->load->view('templates_admin/header', $data);
    $this->load->view('templates_admin/sidebar');
    $this->load->view('admin/RekapAbsen/updateDataAbsensi', $data);
    $this->load->view('templates_admin/footer');
  }

  public function updateDataAksi()
  {
    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
      $id = $this->insert_batch->post('id_kehadiran');
      $this->updateData($id);
    } else {
      $id           = $this->input->post('id_pegawai');
      $nama_pegawai = $this->input->post('nama_pegawai');
      $jenis_kelamin   = $this->input->post('jenis_kelamin');
      $nama_jabatan = $this->input->post('nama_jabatan');
      $hadir   = $this->input->post('hadir');
      $sakit   = $this->input->post('sakit');
      $alpha   = $this->input->post('alpha');
      $izin   = $this->input->post('izin');


      $data = array(
        'nama_pegawai' => $nama_pegawai,
        'jenis_kelamin'   => $jenis_kelamin,
        'nama_jabatan' => $nama_jabatan,
        'hadir' => $hadir,
        'sakit'   => $sakit,
        'alpha' => $alpha,
        'izin'  => $izin,
      );

      $where = array(
        'id_kehadiran' => $id
      );

      $this->penggajianModel->update_data('data_kehadiran', $data, $where);
      $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Data berhasil diupdate</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
      redirect('admin/dataAbsensi');
    }
  }
  public function deleteData($id)
  {
    $where = array('id_kehadiran' => $id);
    $this->penggajianModel->delete_data($where, 'data_kehadiran');
    $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Data berhasil dihapus</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
    redirect('admin/dataAbsensi');
  }
  public function _rules()
  {
    $this->form_validation->set_rules('nama_pegawai', 'Nama Pegawai', 'required');
    $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required');
    $this->form_validation->set_rules('jabatan', 'Jabatan', 'required');
    $this->form_validation->set_rules('hadir', 'Hadir', 'required');
    $this->form_validation->set_rules('sakit', 'Sakit', 'required');
    $this->form_validation->set_rules('alpha', 'Alpha', 'required');
  }

  public function generate_qr($forced_type = null)
  {
      $this->load->helper('qr');
      $this->load->library('ciqrcode');
  
      // Waktu sekarang
      $now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
      $hour = (int)$now->format('H');
  
      // -----------------------------------------------------------
      // LOGIC MODIFIKASI: Cek apakah ada paksaan tipe dari URL?
      // -----------------------------------------------------------
      if ($forced_type === 'IN') {
          $type = 'IN';
          $session_label = 'Pagi (Testing Mode)';
          $next_shift_time = '17:00';
      } elseif ($forced_type === 'OUT') {
          $type = 'OUT';
          $session_label = 'Sore (Testing Mode)';
          $next_shift_time = '08:00 (besok)';
      } else {
          // --- Logic Normal (Otomatis berdasarkan jam) ---
          $type = ($hour >= 13) ? 'OUT' : 'IN';  
  
          // Label sesi normal
          if ($hour < 8) {
              $session_label = 'Pagi';
              $next_shift_time = '08:00';
          } elseif ($hour < 17) {
              $session_label = 'Pagi';
              $next_shift_time = '17:00';
          } else {
              $session_label = 'Sore';
              $next_shift_time = '08:00 (besok)';
          }
      }
      // -----------------------------------------------------------
  
      // Base code unik per hari
      $baseCode = generate_daily_unique_code($now);
  
      // Format QR final: IN-20251115-ABC123XY
      // Kita tambahkan random string dikit biar QR code berubah gambarnya meski string sama
      $code = $type . '-' . $now->format('Ymd') . '-' . $baseCode; 
  
      // File QR (Ditumpuk saja biar tidak menuh-menuhin server saat testing)
      // Atau kalau mau unik, tambahkan time() di nama file
      $filename = 'qr/' . $type . '-' . $now->format('Ymd') . '.png';
  
      // Generate QR (Selalu generate ulang untuk testing biar fresh)
      $params['data'] = $code;
      $params['level'] = 'H';
      $params['size'] = 10;
      $params['savename'] = FCPATH . $filename;
      $this->ciqrcode->generate($params);
  
      // Data ke view
      $data = [
          'qr_file'        => base_url($filename) . '?t=' . time(), // trik agar gambar tidak di-cache browser
          'session_label'  => $session_label,
          'next_shift_time'=> $next_shift_time,
          'title'          => 'QR Code Absensi - ' . $type,
          'type'           => $type
      ];
  
      $this->load->view('templates_admin/header', $data);
      $this->load->view('templates_admin/sidebar');
      $this->load->view('admin/QRCode/absensi_scan', $data);
      $this->load->view('templates_admin/footer');
  }




  // public function generate_qr()
  // {
  //     $this->load->helper('qr');
  //     $this->load->library('ciqrcode');

  //     $now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
  //     $code = generate_daily_unique_code($now);
  //     $filename = 'qr/' . $code . '.png';

  //     // Generate QR jika belum ada
  //     if (!file_exists(FCPATH . $filename)) {
  //         $params['data'] = $code;
  //         $params['level'] = 'H';
  //         $params['size'] = 10;
  //         $params['savename'] = FCPATH . $filename;
  //         $this->ciqrcode->generate($params);
  //     }

  //     // Tentukan sesi dan waktu berikutnya
  //     $hour = (int)$now->format('H');
  //     if ($hour < 8) {
  //         $session_label = 'Pagi';
  //         $next_shift_time = '08:00';
  //     } elseif ($hour < 17) {
  //         $session_label = 'Pagi';
  //         $next_shift_time = '17:00';
  //     } else {
  //         $session_label = 'Sore';
  //         $next_shift_time = '08:00 (besok)';
  //     }

  //     // Kirim ke view
  //     $data = [
  //         'qr_file' => base_url($filename),
  //         'session_label' => $session_label,
  //         'next_shift_time' => $next_shift_time,
  //         'title' => 'QR Code Absensi Aktif',
  //     ];

  //     $this->load->view('templates_admin/header', $data);
  //     $this->load->view('templates_admin/sidebar');
  //     $this->load->view('admin/QRCode/absensi_scan', $data);
  //     $this->load->view('templates_admin/footer');
  // }

}
