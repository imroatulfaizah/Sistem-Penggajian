<?php

class DataPenggajian extends CI_Controller
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
    $data['title'] = "Data Gaji Pegawai";

    if ((isset($_GET['bulan']) && $_GET['bulan'] != '') && (isset($_GET['tahun']) && $_GET['tahun'] != '')) {
      $bulan = $_GET['bulan'];
      $tahun = $_GET['tahun'];
      $bulanTahun = $bulan . $tahun;
    } else {
      $bulan = date('m');
      $tahun = date('Y');
      $bulanTahun = $bulan . $tahun;
    }

    // HAPUS QUERY $data['jam'] DISINI KARENA ITU MENJUMLAHKAN TOTAL GLOBAL
    
    $data['kehadiran'] = $this->db->query("SELECT hadir FROM data_kehadiran")->result();

    // PERBAIKAN QUERY: Menambahkan Subquery untuk menghitung total_jam per pegawai
    // Kita ambil total_jam dari tabel 'absensi_mengajar' (tempat data real time scan qr masuk)
    // Lalu di-join dengan data_penempatan untuk mencocokkan NIP
    $data['gaji'] = $this->db->query("SELECT 
        data_pegawai.nip, 
        data_pegawai.nama_pegawai, 
        data_pegawai.jenis_kelamin, 
        data_jabatan.nama_jabatan, 
        data_jabatan.tunjangan_jabatan, 
        data_jabatan.tunjangan_transport, 
        data_jabatan.upah_mengajar, 
        data_kehadiran.hadir,
        (
            SELECT COALESCE(SUM(absensi_mengajar.total_jam), 0)
            FROM absensi_mengajar
            JOIN data_penempatan ON absensi_mengajar.id_penempatan = data_penempatan.id_penempatan
            WHERE data_penempatan.nip = data_pegawai.nip
            AND DATE_FORMAT(absensi_mengajar.jam_clockin, '%m%Y') = '$bulanTahun'
        ) AS total_jam
    FROM data_pegawai 
    INNER JOIN data_kehadiran ON data_kehadiran.nip=data_pegawai.nip 
    INNER JOIN data_jabatan ON data_jabatan.id_jabatan=data_pegawai.jabatan 
    WHERE data_kehadiran.bulan='$bulanTahun' 
    ORDER BY data_pegawai.nama_pegawai ASC")->result();

    $this->load->view('templates_admin/header', $data);
    $this->load->view('templates_admin/sidebar');
    $this->load->view('admin/DataPenggajian/dataGaji', $data);
    $this->load->view('templates_admin/footer');
  }

  public function cetakGaji()
  {
    $data['title'] = "Cetak Data Gaji Pegawai";
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
    
    // PERBAIKAN QUERY UNTUK CETAK (SAMA SEPERTI DIATAS)
    $data['cetakGaji'] = $this->db->query("SELECT 
        data_pegawai.nip, 
        data_pegawai.nama_pegawai, 
        data_pegawai.jenis_kelamin, 
        data_jabatan.nama_jabatan, 
        data_jabatan.tunjangan_jabatan, 
        data_jabatan.tunjangan_transport, 
        data_jabatan.upah_mengajar, 
        data_kehadiran.hadir,
        (
            SELECT COALESCE(SUM(absensi_mengajar.total_jam), 0)
            FROM absensi_mengajar
            JOIN data_penempatan ON absensi_mengajar.id_penempatan = data_penempatan.id_penempatan
            WHERE data_penempatan.nip = data_pegawai.nip
            AND DATE_FORMAT(absensi_mengajar.jam_clockin, '%m%Y') = '$bulanTahun'
        ) AS total_jam
    FROM data_pegawai 
    INNER JOIN data_kehadiran ON data_kehadiran.nip=data_pegawai.nip 
    INNER JOIN data_jabatan ON data_jabatan.id_jabatan=data_pegawai.jabatan 
    WHERE data_kehadiran.bulan='$bulanTahun' 
    ORDER BY data_pegawai.nama_pegawai ASC")->result();

    $this->load->view('templates_admin/header', $data);
    $this->load->view('admin/DataPenggajian/cetakDataGaji', $data);
  }
}