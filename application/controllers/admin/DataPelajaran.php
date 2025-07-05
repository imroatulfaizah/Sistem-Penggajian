<?php

class DataPelajaran extends CI_Controller
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
    $data['title'] = "Data Pelajaran";
    $data['pelajaran'] = $this->penggajianModel->get_data('data_pelajaran')->result();
    $this->load->view('templates_admin/header', $data);
    $this->load->view('templates_admin/sidebar');
    $this->load->view('admin/Pelajaran/dataPelajaran', $data);
    $this->load->view('templates_admin/footer');
  }

  public function tambahData()
  {
    $data['title'] = "Tambah Data Pelajaran";

    $this->load->view('templates_admin/header', $data);
    $this->load->view('templates_admin/sidebar');
    $this->load->view('admin/Pelajaran/tambahDataPelajaran', $data);
    $this->load->view('templates_admin/footer');
  }

  public function tambahDataAksi()
  {
    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
      $this->tambahData();
    } else {
      $nama_pelajaran = $this->input->post('nama_pelajaran');

      $data = array(
        'nama_pelajaran' => $nama_pelajaran,
      );

      $this->penggajianModel->insert_data($data, 'data_pelajaran');
      $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Data berhasil ditambahkan</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
      redirect('admin/dataPelajaran');
    }
  }

  public function updateData($id)
  {
    // $where = array('id_jabatan' => $id);
    $data['pelajaran'] = $this->db->query("SELECT * FROM data_pelajaran WHERE id_pelajaran = '$id'")->result();
    $data['title'] = "Update Data Pelajaran";

    $this->load->view('templates_admin/header', $data);
    $this->load->view('templates_admin/sidebar');
    $this->load->view('admin/Pelajaran/updateDataPelajaran', $data);
    $this->load->view('templates_admin/footer');
  }

  public function updateDataAksi()
  {
    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
      $id = $this->insert->post('id_pelajaran');
      $this->updateData($id);
    } else {
      $id           = $this->input->post('id_pelajaran');
      $nama_pelajaran = $this->input->post('nama_pelajaran');

      $data = array(
        'nama_pelajaran' => $nama_pelajaran,
      );

      $where = array(
        'id_pelajaran' => $id
      );

      $this->penggajianModel->update_data('data_pelajaran', $data, $where);
      $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Data berhasil diupdate</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
      redirect('admin/dataPelajaran');
    }
  }

  public function deleteData($id)
  {
    $where = array('id_pelajaran' => $id);
    $this->penggajianModel->delete_data($where, 'data_pelajaran');
    $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Data berhasil dihapus</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
    redirect('admin/dataPelajaran');
  }
  public function printData()
  {
    $data['title'] = "Cetak Data Jabaatan";
    $data['pelajaran'] = $this->penggajianModel->get_data('data_pelajaran')->result();
    $this->load->view('templates_admin/header', $data);
    $this->load->view('admin/Pelajaran/cetakdataPelajaran');
  }

  public function _rules()
  {
    $this->form_validation->set_rules('nama_pelajaran', 'Nama Pelajaran', 'required');
  }
}
