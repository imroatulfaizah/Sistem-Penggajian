<?php

class DataJabatan extends CI_Controller
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
    $data['title'] = "Data Jabatan";
    $data['jabatan'] = $this->penggajianModel->get_data('data_jabatan')->result();
    $this->load->view('templates_admin/header', $data);
    $this->load->view('templates_admin/sidebar');
    $this->load->view('admin/Jabatan/dataJabatan', $data);
    $this->load->view('templates_admin/footer');
  }

  public function tambahData()
  {
    $data['title'] = "Tambah Data Jabatan";

    $this->load->view('templates_admin/header', $data);
    $this->load->view('templates_admin/sidebar');
    $this->load->view('admin/Jabatan/tambahDataJabatan', $data);
    $this->load->view('templates_admin/footer');
  }

  public function tambahDataAksi()
  {
    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
      $this->tambahData();
    } else {
      $nama_jabatan = $this->input->post('nama_jabatan');
      $tunjangan_jabatan = $this->input->post('tunjangan_jabatan');
      $tunjangan_transport = $this->input->post('tunjangan_transport');
      $upah_mengajar = $this->input->post('upah_mengajar');

      $data = array(
        'nama_jabatan' => $nama_jabatan,
        'tunjangan_jabatan' => $tunjangan_jabatan,
        'tunjangan_transport' => $tunjangan_transport,
        'upah_mengajar' => $upah_mengajar,
      );

      $this->penggajianModel->insert_data($data, 'data_jabatan');
      $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Data berhasil ditambahkan</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
      redirect('admin/dataJabatan');
    }
  }

  public function updateData($id)
  {
    // $where = array('id_jabatan' => $id);
    $data['jabatan'] = $this->db->query("SELECT * FROM data_jabatan WHERE id_jabatan = '$id'")->result();
    $data['title'] = "Update Data Jabatan";

    $this->load->view('templates_admin/header', $data);
    $this->load->view('templates_admin/sidebar');
    $this->load->view('admin/Jabatan/updateDataJabatan', $data);
    $this->load->view('templates_admin/footer');
  }

  public function updateDataAksi()
  {
    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
      $id = $this->insert->post('id_jabatan');
      $this->updateData($id);
    } else {
      $id           = $this->input->post('id_jabatan');
      $nama_jabatan = $this->input->post('nama_jabatan');
      $tunjangan_jabatan   = $this->input->post('tunjangan_jabatan');
      $tunjangan_transport = $this->input->post('tunjangan_transport');
      $upah_mengajar   = $this->input->post('upah_mengajar');

      $data = array(
        'nama_jabatan' => $nama_jabatan,
        'tunjangan_jabatan'   => $tunjangan_jabatan,
        'tunjangan_transport' => $tunjangan_transport,
        'upah_mengajar'   => $upah_mengajar,
      );

      $where = array(
        'id_jabatan' => $id
      );

      $this->penggajianModel->update_data('data_jabatan', $data, $where);
      $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Data berhasil diupdate</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
      redirect('admin/dataJabatan');
    }
  }

  public function deleteData($id)
  {
    $where = array('id_jabatan' => $id);
    $this->penggajianModel->delete_data($where, 'data_jabatan');
    $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Data berhasil dihapus</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
    redirect('admin/dataJabatan');
  }
  public function printData()
  {
    $data['title'] = "Cetak Data Jabaatan";
    $data['jabatan'] = $this->penggajianModel->get_data('data_jabatan')->result();
    $this->load->view('templates_admin/header', $data);
    $this->load->view('admin/Jabatan/cetakdatajabatan');
  }

  public function _rules()
  {
    $this->form_validation->set_rules('nama_jabatan', 'Nama jabatan', 'required');
    $this->form_validation->set_rules('tunjangan_jabatan', 'Tunjangan Jabatan', 'required');
    $this->form_validation->set_rules('tunjangan_transport', 'Tunjangan Transport', 'required');
    $this->form_validation->set_rules('upah_mengajar', 'Upah Mengajar', 'required');
  }
}
