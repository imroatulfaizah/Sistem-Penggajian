<?php

class SlipGaji extends CI_Controller
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
    $data['title'] = "Filter Slip Gaji Pegawai";
    $data['pegawai'] = $this->penggajianModel->get_data('data_pegawai')->result();
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
    $this->load->view('kepsek/SlipGaji/filterSlipGaji', $data);
    $this->load->view('templates_kepsek/footer');
  }

  public function cetakSlipGaji()
  {
    $data['title'] = "Cetak Slip Gaji";
    $id_pegawai = $this->input->post('id_pegawai');
    //$data['jam'] = $this->db->query("SELECT * FROM data_penempatan WHERE id_guru = '$id_pegawai'")->result();
    $data['jam'] = $this->penggajianModel->get_data('data_penempatan')->result();

    $nama = $this->input->post('nama_pegawai');
    $bulan = $this->input->post('bulan');
    $tahun = $this->input->post('tahun');
    $bulanTahun = $bulan . $tahun;
    $data['print_slip'] = $this->db->query("SELECT data_pegawai.nip, data_pegawai.nama_pegawai, 
    data_jabatan.nama_jabatan, data_jabatan.tunjangan_jabatan, data_jabatan.tunjangan_transport, 
    data_jabatan.upah_mengajar, data_kehadiran.alpha, data_kehadiran.bulan FROM data_pegawai 
    INNER JOIN data_kehadiran ON data_kehadiran.nip=data_pegawai.nip 
    INNER JOIN data_jabatan ON data_jabatan.id_jabatan=data_pegawai.jabatan 
    WHERE data_kehadiran.bulan='$bulanTahun' AND data_kehadiran.nama_pegawai='$nama'")->result();

    // var_dump($slip);
    // die;

    $this->load->view('templates_kepsek/header', $data);
    $this->load->view('kepsek/SlipGaji/cetakSlipGaji', $data);
  }
}
