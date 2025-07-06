<?php

class LaporanGaji extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('hak_akses') != '4') {
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
    $data['title'] = "Laporan Gaji Pegawai";
    $kalender = array(
      'start_day' => 'monday',
      // 'show_next_prev' => TRUE,
      //  'next_prev_url' => base_url() . "index.php/Dashboard/index",
      'month_type' => 'long',
      'day_type' => 'long'
    );
    $this->load->library('calendar', $kalender);
    $data['kalender'] = $this->calendar->generate($tahun, $bulan);
    $this->load->view('templates_bendahara/header', $data);
    $this->load->view('templates_bendahara/sidebar');
    $this->load->view('bendahara/LaporGaji/filterLaporanGaji', $data);
    $this->load->view('templates_bendahara/footer');
  }

  public function cetakLaporanGaji()
  {
    $data['title'] = "Cetak Laporan Gaji Pegawai";
    if ((isset($_GET['bulan']) && $_GET['bulan'] != '') && (isset($_GET['tahun']) && $_GET['tahun'] != '')) {
      $bulan = $_GET['bulan'];
      $tahun = $_GET['tahun'];
      $bulanTahun = $bulan . $tahun;
    } else {
      $bulan = date('m');
      $tahun = date('Y');
      $bulanTahun = $bulan . $tahun;
    }
    $data['kehadiran'] = $this->db->query("SELECT hadir FROM data_kehadiran")->result();
    $data['jam'] = $this->db->query("SELECT SUM(total_jam) as total_jam FROM data_penempatan")->result();
    $data['cetakGaji'] = $this->db->query("SELECT data_pegawai.nip, data_pegawai.nama_pegawai, data_pegawai.jenis_kelamin, 
    data_jabatan.nama_jabatan, data_jabatan.tunjangan_jabatan, data_jabatan.tunjangan_transport, data_jabatan.upah_mengajar, 
    data_kehadiran.hadir 
    FROM data_pegawai INNER JOIN data_kehadiran ON data_kehadiran.nip=data_pegawai.nip 
    INNER JOIN data_jabatan ON data_jabatan.nama_jabatan=data_pegawai.jabatan 
    WHERE data_kehadiran.bulan='$bulanTahun' ORDER BY data_pegawai.nama_pegawai ASC")->result();
    $this->load->view('templates_bendahara/header', $data);
    $this->load->view('bendahara/LaporGaji/cetakDataGaji', $data);
  }
}
