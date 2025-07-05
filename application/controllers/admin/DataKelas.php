<?php

class DataKelas extends CI_Controller
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
    $data['title'] = "Data Kelas";
    $data['kelas'] = $this->penggajianModel->get_data('data_kelas')->result();
    $this->load->view('templates_admin/header', $data);
    $this->load->view('templates_admin/sidebar');
    $this->load->view('admin/Kelas/dataKelas', $data);
    $this->load->view('templates_admin/footer');
  }

  public function tambahData()
  {
    $data['title'] = "Tambah Data Kelas";

    $this->load->view('templates_admin/header', $data);
    $this->load->view('templates_admin/sidebar');
    $this->load->view('admin/Kelas/tambahDataKelas', $data);
    $this->load->view('templates_admin/footer');
  }

  public function tambahDataAksi()
  {
    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
      $this->tambahData();
    } else {
      $nama_kelas = $this->input->post('nama_kelas');

      $data = array(
        'nama_kelas' => $nama_kelas,
      );

      $this->penggajianModel->insert_data($data, 'data_kelas');
      $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Data berhasil ditambahkan</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
      redirect('admin/dataKelas');
    }
  }

  public function updateData($id)
  {
    // $where = array('id_jabatan' => $id);
    $data['kelas'] = $this->db->query("SELECT * FROM data_kelas WHERE id_kelas = '$id'")->result();
    $data['title'] = "Update Data Kelas";

    $this->load->view('templates_admin/header', $data);
    $this->load->view('templates_admin/sidebar');
    $this->load->view('admin/Kelas/updateDataKelas', $data);
    $this->load->view('templates_admin/footer');
  }

  public function updateDataAksi()
  {
    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
      $id = $this->insert->post('id_kelas');
      $this->updateData($id);
    } else {
      $id           = $this->input->post('id_kelas');
      $nama_kelas = $this->input->post('nama_kelas');

      $data = array(
        'nama_kelas' => $nama_kelas,
      );

      $where = array(
        'id_kelas' => $id
      );

      $this->penggajianModel->update_data('data_kelas', $data, $where);
      $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Data berhasil diupdate</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
      redirect('admin/dataKelas');
    }
  }

  public function deleteData($id)
  {
    $where = array('id_kelas' => $id);
    $this->penggajianModel->delete_data($where, 'data_kelas');
    $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Data berhasil dihapus</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
    redirect('admin/dataKelas');
  }
  public function printData()
  {
    $data['title'] = "Cetak Data Jabaatan";
    $data['kelas'] = $this->penggajianModel->get_data('data_kelas')->result();
    $this->load->view('templates_admin/header', $data);
    $this->load->view('admin/Kelas/cetakdatakelas');
  }

  public function _rules()
  {
    $this->form_validation->set_rules('nama_kelas', 'Nama Kelas', 'required');
  }
}
