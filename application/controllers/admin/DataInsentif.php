<?php

class DataInsentif extends CI_Controller
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
    $data['title'] = "Data Insentif";
    $data['insentif'] = $this->penggajianModel->get_data('data_insentif')->result();
    $this->load->view('templates_admin/header', $data);
    $this->load->view('templates_admin/sidebar');
    $this->load->view('admin/Insentif/dataInsentif', $data);
    $this->load->view('templates_admin/footer');
  }

  public function tambahData()
  {
    $data['title'] = "Tambah Data Insentif";

    $this->load->view('templates_admin/header', $data);
    $this->load->view('templates_admin/sidebar');
    $this->load->view('admin/Insentif/tambahDataInsentif', $data);
    $this->load->view('templates_admin/footer');
  }

  public function tambahDataAksi()
  {
    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
      $this->tambahData();
    } else {
      $id_pegawai = $this->input->post('id_pegawai');
      $nama_insentif = $this->input->post('nama_insentif');
      $nominal = $this->input->post('nominal');
      $is_paid = $this->input->post('is_paid');

      $data = array(
        'id_pegawai' => $id_pegawai,
        'nama_insentif' => $nama_insentif,
        'nominal' => $nominal,
        'is_paid' => $is_paid,
      );

      $this->penggajianModel->insert_data($data, 'data_insentif');
      $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Data berhasil ditambahkan</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
      redirect('admin/dataInsentif');
    }
  }

  public function updateData($id)
  {
    // $where = array('id_jabatan' => $id);
    $data['insentif'] = $this->db->query("SELECT * FROM data_insentif WHERE id_insentif = '$id'")->result();
    $data['title'] = "Update Data Insentif";

    $this->load->view('templates_admin/header', $data);
    $this->load->view('templates_admin/sidebar');
    $this->load->view('admin/Insentif/updateDataInsentif', $data);
    $this->load->view('templates_admin/footer');
  }

  public function updateDataAksi()
  {
    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
      $id = $this->insert->post('id_insentif');
      $this->updateData($id);
    } else {
      $id           = $this->input->post('id_insentif');
      $id_pegawai   = $this->input->post('id_pegawai');
      $nama_insentif = $this->input->post('nama_insentif');  
      $nominal = $this->input->post('nominal');
      $is_paid   = $this->input->post('is_paid');

      $data = array(
        'id_pegawai' => $id_pegawai,
        'nama_insentif'   => $nama_insentif,
        'nominal' => $nominal,
        'is_paid'   => $is_paid,
      );

      $where = array(
        'id_insentif' => $id
      );

      $this->penggajianModel->update_data('data_insentif', $data, $where);
      $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Data berhasil diupdate</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
      redirect('admin/dataInsentif');
    }
  }

  public function deleteData($id)
  {
    $where = array('id_insentif' => $id);
    $this->penggajianModel->delete_data($where, 'data_insentif');
    $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Data berhasil dihapus</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
    redirect('admin/dataInsentif');
  }
  public function printData()
  {
    $data['title'] = "Cetak Data Jabatan";
    $data['insentif'] = $this->penggajianModel->get_data('data_insentif')->result();
    $this->load->view('templates_admin/header', $data);
    $this->load->view('admin/Insentif/cetakdatainsentif');
  }

  public function _rules()
  {
    $this->form_validation->set_rules('id_pegawai', 'ID Pegawai', 'required');
    $this->form_validation->set_rules('nama_insentif', 'Nama Insentif', 'required');
    $this->form_validation->set_rules('nominal', 'Nominal Tunjangan', 'required');
    $this->form_validation->set_rules('is_paid', 'Is Paid', 'required');
  }
}
