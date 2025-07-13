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

    $data['kehadiran'] = $this->db->query("SELECT hadir FROM data_kehadiran")->result();
    $data['jam'] = $this->db->query("SELECT SUM(total_jam) as total_jam FROM data_penempatan")->result();
    #data pegawai , data jabatan , dan  data kehadiran dengan sql INNER JOIN
    #sql dengan kondisi meneyesuaikan bulan dan tahun WHERE data_kehadiran.bulan='$bulanTahun' ORDER BY data_pegawai.nama_pegawai ASC")->result();
    $data['gaji'] = $this->db->query("SELECT data_pegawai.nip, data_pegawai.nama_pegawai, data_pegawai.jenis_kelamin, 
    data_jabatan.nama_jabatan, data_jabatan.tunjangan_jabatan, data_jabatan.tunjangan_transport, data_jabatan.upah_mengajar, data_kehadiran.hadir 
    FROM data_pegawai INNER JOIN data_kehadiran ON data_kehadiran.nip=data_pegawai.nip 
    INNER JOIN data_jabatan ON data_jabatan.id_jabatan=data_pegawai.jabatan 
    WHERE data_kehadiran.bulan='$bulanTahun' ORDER BY data_pegawai.nama_pegawai ASC")->result();
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
    $data['jam'] = $this->db->query("SELECT SUM(total_jam) as total_jam FROM data_penempatan")->result();
    $data['cetakGaji'] = $this->db->query("SELECT data_pegawai.nip, data_pegawai.nama_pegawai, data_pegawai.jenis_kelamin, 
    data_jabatan.nama_jabatan, data_jabatan.tunjangan_jabatan, data_jabatan.tunjangan_transport, data_jabatan.upah_mengajar, 
    data_kehadiran.hadir FROM data_pegawai INNER JOIN data_kehadiran ON data_kehadiran.nip=data_pegawai.nip 
    INNER JOIN data_jabatan ON data_jabatan.id_jabatan=data_pegawai.jabatan 
    WHERE data_kehadiran.bulan='$bulanTahun' ORDER BY data_pegawai.nama_pegawai ASC")->result();
    $this->load->view('templates_admin/header', $data);
    $this->load->view('admin/DataPenggajian/cetakDataGaji', $data);
  }
}
