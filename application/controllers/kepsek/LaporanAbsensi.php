<?php

class LaporanAbsensi extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('hak_akses') != '3') {
      $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Anda belum login!</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
    </div>');
      redirect('welcome');
    }
  }

  public function index($tahun = NULL, $bulan = NULL)
  {
    $data['title'] = "Laporan Absensi";
    $kalender = array(
      'start_day' => 'monday',
      // 'show_next_prev' => TRUE,
      //  'next_prev_url' => base_url() . "index.php/Dashboard/index",
      'month_type' => 'long',
      'day_type' => 'long'
    );
    $this->load->library('calendar', $kalender);
    $data['kalender'] = $this->calendar->generate($tahun, $bulan);
    $this->load->view('templates_kepsek/header', $data);
    $this->load->view('templates_kepsek/sidebar');
    $this->load->view('kepsek/LaporAbsen/filterLaporanAbsensi', $data);
    $this->load->view('templates_kepsek/footer');
  }

  public function cetakLaporanAbsensi()
  {
    $data['title'] = "Cetak Laporan Absensi";

    if ((isset($_GET['bulan']) && $_GET['bulan'] != '') && (isset($_GET['tahun']) && $_GET['tahun'] != '')) {
      $bulan = $_GET['bulan'];
      $tahun = $_GET['tahun'];
      $bulanTahun = $bulan . $tahun;
    } else {
      $bulan = date('m');
      $tahun = date('Y');
      $bulanTahun = $bulan . $tahun;
    }

    $bulanTahun = $bulan . $tahun;
    $data['lap_kehadiran'] = $this->db->query("SELECT * FROM data_kehadiran WHERE bulan='$bulanTahun' ORDER BY nama_pegawai ASC")->result();
    $this->load->view('templates_kepsek/header', $data);
    $this->load->view('kepsek/LaporAbsen/cetakLaporanAbsensi');
  }
}
