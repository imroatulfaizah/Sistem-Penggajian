<?php

class DataAkademik extends CI_Controller
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
    $data['title'] = "Data Akademik";
    $data['akademik'] = $this->penggajianModel->get_data('data_akademik')->result();
    $this->load->view('templates_admin/header', $data);
    $this->load->view('templates_admin/sidebar');
    $this->load->view('admin/Akademik/dataAkademik', $data);
    $this->load->view('templates_admin/footer');
  }

  public function tambahData()
  {
    $data['title'] = "Tambah Data Akademik";

    $this->load->view('templates_admin/header', $data);
    $this->load->view('templates_admin/sidebar');
    $this->load->view('admin/Akademik/tambahDataAkademik', $data);
    $this->load->view('templates_admin/footer');
  }

  public function tambahDataAksi()
  {
    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
      $this->tambahData();
    } else {
      $tahun_akademik = $this->input->post('tahun_akademik');

      $data = array(
        'tahun_akademik' => $tahun_akademik,
      );

      $this->penggajianModel->insert_data($data, 'data_akademik');
      $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Data berhasil ditambahkan</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
      redirect('admin/dataAkademik');
    }
  }

  public function updateData($id)
  {
    // $where = array('id_jabatan' => $id);
    $data['akademik'] = $this->db->query("SELECT * FROM data_akademik WHERE id_akademik = '$id'")->result();
    $data['title'] = "Update Data Akademik";

    $this->load->view('templates_admin/header', $data);
    $this->load->view('templates_admin/sidebar');
    $this->load->view('admin/Akademik/updateDataAkademik', $data);
    $this->load->view('templates_admin/footer');
  }

  public function updateDataAksi()
  {
    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
      $id = $this->insert->post('id_akademik');
      $this->updateData($id);
    } else {
      $id           = $this->input->post('id_akademik');
      $tahun_akademik = $this->input->post('tahun_akademik');

      $data = array(
        'tahun_akademik' => $tahun_akademik,
      );

      $where = array(
        'id_akademik' => $id
      );

      $this->penggajianModel->update_data('data_akademik', $data, $where);
      $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Data berhasil diupdate</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
      redirect('admin/dataAkademik');
    }
  }

  public function deleteData($id)
  {
    $where = array('id_akademik' => $id);
    $this->penggajianModel->delete_data($where, 'data_akademik');
    $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Data berhasil dihapus</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
    redirect('admin/dataAkademik');
  }
  public function printData()
  {
    $data['title'] = "Cetak Data Akademik";
    $data['akademik'] = $this->penggajianModel->get_data('data_akademik')->result();
    $this->load->view('templates_admin/header', $data);
    $this->load->view('admin/Akademik/cetakdataakademik');
  }

  public function _rules()
  {
    $this->form_validation->set_rules('tahun_akademik', 'Tahun Akademik', 'required');
  }
}
