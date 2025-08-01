<?php

class Dashboard extends CI_Controller
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

  public function index()
  {
    // $data['title'] = "Dashboard"; untuk membuat judul
    $data['title'] = "Dashboard";
    $pegawai = $this->db->query("SELECT * FROM data_pegawai");

    //untuk mengambil data tertentu
    $admin = $this->db->query("SELECT * FROM data_pegawai WHERE jabatan = 'Kepala Bidang'");

    $jabatan = $this->db->query("SELECT * FROM data_jabatan");
    $kehadiran = $this->db->query("SELECT * FROM data_kehadiran");
    $data['pegawai'] = $pegawai->num_rows();
    $data['admin'] = $admin->num_rows();
    $data['jabatan'] = $jabatan->num_rows();
    $data['kehadiran'] = $kehadiran->num_rows();
    //untuk menampilkan halaman
    $this->load->view('templates_bendahara/header', $data);
    $this->load->view('templates_bendahara/sidebar');
    $this->load->view('bendahara/dashboard', $data);
    $this->load->view('templates_bendahara/footer');
  }
}
