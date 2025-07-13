<?php

class DataGaji extends CI_Controller
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

  public function index()
  {
    $data['title'] = "Data Gaji";
    $nip = $this->session->userdata('nip');
    // var_dump($nik);
    // die;
    $data['insentif'] = $this->db->query("SELECT SUM(nominal) AS jumlah_insentif FROM data_insentif WHERE nip = '$nip'")->result();
    $data['kehadiran'] = $this->db->query("SELECT hadir FROM data_kehadiran WHERE nip = '$nip'")->result();
    $data['jam'] = $this->db->query("SELECT SUM(total_jam) as total_jam FROM data_penempatan WHERE nip = '$nip'")->result();
    $data['gaji'] = $this->db->query("SELECT data_pegawai.nama_pegawai, data_pegawai.nip, data_jabatan.tunjangan_jabatan, 
    data_jabatan.tunjangan_transport, data_jabatan.upah_mengajar, data_kehadiran.alpha, data_kehadiran.bulan, data_kehadiran.id_kehadiran 
    FROM data_pegawai INNER JOIN data_kehadiran ON data_kehadiran.nip=data_pegawai.nip 
    INNER JOIN data_jabatan ON data_jabatan.id_jabatan=data_pegawai.jabatan 
    WHERE data_kehadiran.nip='$nip' ORDER BY data_kehadiran.bulan DESC")->result();
    // var_dump($data);
    // die;
    $this->load->view('templates_pegawai/header', $data);
    $this->load->view('templates_pegawai/sidebar');
    $this->load->view('pegawai/dataGaji', $data);
    $this->load->view('templates_pegawai/footer');
  }

  public function cetakSlip($nip)
  {
    $data['title'] = "Cetak Slip Gaji";
    $data['insentif'] = $this->db->query("SELECT SUM(nominal) AS jumlah_insentif FROM data_insentif WHERE nip = '$nip'")->result();
    $data['kehadiran'] = $this->db->query("SELECT hadir FROM data_kehadiran WHERE nip = '$nip'")->result();
    $data['jam'] = $this->db->query("SELECT SUM(total_jam) as total_jam FROM data_penempatan WHERE nip = '$nip'")->result();

    $data['print_slip'] = $this->db->query("SELECT data_pegawai.nip, data_pegawai.nama_pegawai, data_jabatan.nama_jabatan, 
    data_jabatan.tunjangan_jabatan, data_jabatan.tunjangan_transport, data_jabatan.upah_mengajar, data_kehadiran.alpha, data_kehadiran.bulan 
    FROM data_pegawai INNER JOIN data_kehadiran ON data_kehadiran.nip=data_pegawai.nip 
    INNER JOIN data_jabatan ON data_jabatan.id_jabatan=data_pegawai.jabatan WHERE data_pegawai.nip='$nip'")->result();

    // var_dump($data);
    // die;

    $this->load->view('templates_pegawai/header', $data);
    $this->load->view('pegawai/cetakSlipGaji', $data);
  }
}
