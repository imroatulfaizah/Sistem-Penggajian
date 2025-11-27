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
      
      // Kirim status testing ke view untuk mengubah warna/teks UI
      $data['is_testing_radius'] = $this->session->userdata('testing_radius');

      $this->load->view('templates_pegawai/header', $data);
      $this->load->view('templates_pegawai/sidebar');
      $this->load->view('pegawai/formAbsensi', $data);
      $this->load->view('templates_pegawai/footer');
  }

  // ===============================
  // FITUR BARU: TOGGLE RADIUS TESTING
  // ===============================
  public function set_radius_mode($mode)
  {
      if ($mode == 'unlimited') {
          $this->session->set_userdata('testing_radius', true);
          $this->session->set_flashdata('pesan', 
              '<div class="alert alert-warning"><b>Mode Testing Aktif!</b> Radius lokasi dibebaskan (Bisa absen dari mana saja).</div>'
          );
      } else {
          $this->session->unset_userdata('testing_radius');
          $this->session->set_flashdata('pesan', 
              '<div class="alert alert-info">Radius kembali normal (Wajib di area sekolah).</div>'
          );
      }
      redirect('pegawai/dataAbsensi');
  }

  private function invalid_qr($msg = "QR Code tidak valid!")
  {
      $this->session->set_flashdata('pesan', 
          '<div class="alert alert-danger">'.$msg.'</div>'
      );
      redirect('pegawai/dataAbsensi');
      exit;
  }

  // ===============================
  // PROSES CLOCK-IN (IN)
  // ===============================
  public function do_attend() 
  {
      $this->load->helper('qr');
      $this->load->library('ciqrcode');

      $nip = $this->session->userdata('nip');
      $lat = $this->input->post('lat');
      $lon = $this->input->post('lon');
      $scanned_code = $this->input->post('qr_data');

      $now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
      $today = $now->format('Ymd');
      $current_code = generate_daily_unique_code($now);

      // ==== PARSE QR ====
      $parts = explode('-', $scanned_code);
      if (count($parts) !== 3) return $this->invalid_qr();

      $type = $parts[0];   // IN
      $date = $parts[1];   // 20251115
      $code = $parts[2];   // dJbAjXsD

      // VALIDASI QR
      if ($type !== "IN") return $this->invalid_qr();
      if ($date !== $today) return $this->invalid_qr();
      if ($code !== $current_code) return $this->invalid_qr();

      // VALIDASI GPS
      $lat_school = -8.0067597;
      $lon_school = 112.6208716;
      
      // MODIFIKASI: Cek Session Testing
      if ($this->session->userdata('testing_radius') == true) {
          $radius = 10000000; // 10.000 KM (Bebas)
      } else {
          $radius = 100; // Normal
      }

      $meters = $this->distance_meter($lat, $lon, $lat_school, $lon_school);

      if ($meters > $radius) {
          $this->session->set_flashdata('pesan', 
              '<div class="alert alert-danger">Anda berada di luar area sekolah! Jarak: '.round($meters).' meter</div>'
          );
          redirect('pegawai/dataAbsensi');
      }

      // ==== PROSES ABSEN =====
      $bulan = date('mY');
      $cek = $this->db->get_where('data_kehadiran', [
          'nip' => $nip,
          'bulan' => $bulan
      ])->row();

      // Jika sudah ada data bulan ini
      if ($cek) {
          $this->db->set('hadir', 'hadir+1', FALSE);
          $this->db->where('id_kehadiran', $cek->id_kehadiran);
          $this->db->update('data_kehadiran');

          $data = [
              'nip'             => $nip,
              'bulan'           => $bulan,
              'jam_clockin'     => date('Y-m-d H:i:s'),
              'jam_clockout'    => NULL,
              'lokasi_clockin'  => $lat . ',' . $lon,
              'lokasi_clockout' => NULL,
              'total_jam'       => 0,
          ];

          $this->penggajianModel->insert_data($data, 'detail_kehadiran');

          $this->session->set_flashdata('pesan', 
              '<div class="alert alert-success">Clock-in berhasil!</div>'
          );
          redirect('pegawai/dataAbsensi');
      }

      // Jika belum ada data kehadiran bulan ini
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

      $this->session->set_flashdata('pesan', 
          '<div class="alert alert-success">Clock-in berhasil!</div>'
      );
      redirect('pegawai/dataAbsensi');
  }


  // ===============================
  // PROSES CLOCK-OUT (OUT)
  // ===============================
  public function do_attend_out() 
  {
      $this->load->helper('qr');

      $nip = $this->session->userdata('nip');
      $lat = $this->input->post('lat');
      $lon = $this->input->post('lon');
      $scanned_code = $this->input->post('qr_data');

      $now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
      $today = $now->format('Ymd');
      $current_code = generate_daily_unique_code($now);

      // PARSE QR
      $parts = explode('-', $scanned_code);
      if (count($parts) !== 3) return $this->invalid_qr();

      $type = $parts[0]; // OUT
      $date = $parts[1];
      $code = $parts[2];

      // VALIDASI
      if ($type !== "OUT") return $this->invalid_qr();
      if ($date !== $today) return $this->invalid_qr();
      if ($code !== $current_code) return $this->invalid_qr();

      // Validasi lokasi
      $lat_school = -8.0067597;
      $lon_school = 112.6208716;
      
      // MODIFIKASI: Cek Session Testing
      if ($this->session->userdata('testing_radius') == true) {
          $radius = 10000000; 
      } else {
          $radius = 100;
      }

      $meters = $this->distance_meter($lat, $lon, $lat_school, $lon_school);

      if ($meters > $radius) {
          $this->session->set_flashdata('pesan', 
              '<div class="alert alert-danger">Anda berada di luar area sekolah! Jarak: '.round($meters).' meter</div>'
          );
          redirect('pegawai/dataAbsensi');
      }

      // Validasi sudah clock-in
      $cek = $this->db
            ->where('nip', $nip)
            ->where('DATE(jam_clockin)', date('Y-m-d'))
            ->get('detail_kehadiran')
            ->row();

      if (!$cek) return $this->invalid_qr("Anda belum melakukan clock-in!");

      // Hitung total jam
      $clockin = new DateTime($cek->jam_clockin);
      $clockout = new DateTime();
      $interval = $clockin->diff($clockout);
      $total_jam = $interval->h + ($interval->i / 60);

      // Update clockout
      $this->db->set('jam_clockout', $clockout->format('Y-m-d H:i:s'));
      $this->db->set('lokasi_clockout', $lat . ',' . $lon);
      $this->db->set('total_jam', $total_jam);
      $this->db->where('id', $cek->id);
      $this->db->update('detail_kehadiran');

      $this->session->set_flashdata('pesan', 
          '<div class="alert alert-success">Clock-out berhasil!</div>'
      );
      redirect('pegawai/dataAbsensi');
  }


  // ===============================
  // HITUNG JARAK GPS
  // ===============================
  private function distance_meter($lat1, $lon1, $lat2, $lon2)
  {
      $theta = $lon1 - $lon2;
      $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +
              cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
      $dist = acos($dist);
      $dist = rad2deg($dist);
      $miles = $dist * 60 * 1.1515;
      return $miles * 1609.344;
  }


  public function clockIn($id_penempatan) 
  {
      $nip = $this->input->post('nip');
      $lat = $this->input->post('lat');
      $lon = $this->input->post('lon');

      $lat_school = -8.0067597;
      $lon_school = 112.6208716;
      
      // MODIFIKASI: Cek Session Testing
      if ($this->session->userdata('testing_radius') == true) {
          $radius = 10000000; 
      } else {
          $radius = 100;
      }

      $theta = $lon - $lon_school;
      $dist = sin(deg2rad($lat)) * sin(deg2rad($lat_school)) +
              cos(deg2rad($lat)) * cos(deg2rad($lat_school)) * cos(deg2rad($theta));
      $dist = acos($dist);
      $dist = rad2deg($dist);
      $km = $dist * 60 * 1.1515 * 1.609344;
      $meters = $km * 1000;

      if ($meters > $radius) {
          $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Anda berada diluar area sekolah! Jarak: '.round($meters).' meter</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>');
          redirect('pegawai/dataPenempatan');
      }

      $data = [
        'id_penempatan'    => $id_penempatan,
        'jam_clockin'      => date('Y-m-d H:i:s'),
        'jam_clockout'     => '',
        'lokasi_clockin'   => $lat . ',' . $lon,
        'lokasi_clockout'  => '',
        'total_jam'        => 0,
    ];
      $this->db->insert('absensi_mengajar', $data);

      $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
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

      $lat_school = -8.0067597;
      $lon_school = 112.6208716;
      
      // MODIFIKASI: Cek Session Testing
      if ($this->session->userdata('testing_radius') == true) {
          $radius = 10000000; 
      } else {
          $radius = 100;
      }

      $theta = $lon - $lon_school;
      $dist = sin(deg2rad($lat)) * sin(deg2rad($lat_school)) +
              cos(deg2rad($lat)) * cos(deg2rad($lat_school)) * cos(deg2rad($theta));
      $dist = acos($dist);
      $dist = rad2deg($dist);
      $km = $dist * 60 * 1.1515 * 1.609344;
      $meters = $km * 1000;

      if ($meters > $radius) {
          $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Anda berada diluar area sekolah! Jarak: '.round($meters).' meter</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>');
          redirect('pegawai/dataPenempatan');
      }

      // Logic clock out lanjutan...
      $where = array(
        'id_penempatan' => $id_penempatan // Typo variable $id di kode lama fixed
      );

      $absensi = $this->db->get_where('absensi_mengajar', ['id_penempatan' => $id_penempatan])->row();

      if ($absensi) {
          $clockin = new DateTime($absensi->jam_clockin);
          $clockout = new DateTime(); 
          $interval = $clockin->diff($clockout);
          $total_jam = $interval->h + ($interval->i / 60); 

          $this->db->set('jam_clockout', $clockout->format('Y-m-d H:i:s'));
          $this->db->set('lokasi_clockout', $lat . ',' . $lon);
          $this->db->set('total_jam', $total_jam);
          $this->db->where('id_penempatan', $id_penempatan);
          $this->db->where('DATE(jam_clockin)', date('Y-m-d'));
          $this->db->update('absensi_mengajar');

          $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Anda telah berhasil absen!</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
          </div>');
          redirect('pegawai/dataPenempatan');
      } else {
          echo "Data absensi tidak ditemukan untuk id_penempatan: $id_penempatan";
      }     
  }

  public function detailAbsensi($id_penempatan)
  {
      $data['absensi'] = $this->db->query("SELECT * FROM absensi_mengajar WHERE id_penempatan = '$id_penempatan'")->result();
      if (!$data['absensi']) {
          $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Belum ada data absensi!</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>');
          redirect('pegawai/dataPenempatan');
      }
      $data['title'] = "Rekap Absensi";

      $this->load->view('templates_pegawai/header', $data);
      $this->load->view('templates_pegawai/sidebar');
      $this->load->view('pegawai/detailAbsensi', $data);
      $this->load->view('templates_pegawai/footer');
  }

  public function detailKehadiran()
  {
    $data['title'] = "Rekap Absensi";
    $nip = $this->session->userdata('nip');
    $data['kehadiran'] = $this->db->query("SELECT * FROM detail_kehadiran WHERE nip = '$nip'")->result();

    $this->load->view('templates_pegawai/header', $data);
    $this->load->view('templates_pegawai/sidebar');
    $this->load->view('pegawai/detailKehadiran', $data);
    $this->load->view('templates_pegawai/footer');
  }
}