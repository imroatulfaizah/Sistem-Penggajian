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
      $semester       = $this->input->post('semester');
      $is_aktif       = $this->input->post('is_aktif');

      // nama_akademik otomatis
      $nama_akademik = "Semester " . $semester . " " . $tahun_akademik;

      $data = array(
        'tahun_akademik' => $tahun_akademik,
        'semester'       => $semester,
        'nama_akademik'  => $nama_akademik,
        'is_aktif'       => $is_aktif
      );

      // Jika is_aktif = 1, set semua menjadi non aktif dulu
      if ($is_aktif == 1) {
        $this->db->set('is_aktif', 0);
        $this->db->update('data_akademik');
      }

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
      $id = $this->input->post('id_akademik');
      $this->updateData($id);

    } else {

      $id             = $this->input->post('id_akademik');
      $tahun_akademik = $this->input->post('tahun_akademik');
      $semester       = $this->input->post('semester');
      $nama_akademik  = $this->input->post('nama_akademik');
      $is_aktif       = $this->input->post('is_aktif');

      // jika user edit manual nama, tetap dipakai
      if (empty($nama_akademik)) {
        $nama_akademik = "Semester " . $semester . " " . $tahun_akademik;
      }

      $data = array(
        'tahun_akademik' => $tahun_akademik,
        'semester'       => $semester,
        'nama_akademik'  => $nama_akademik,
        'is_aktif'       => $is_aktif
      );

      // Jika is_aktif = 1, nonaktifkan semua dulu
      if ($is_aktif == 1) {
        $this->db->set('is_aktif', 0)->update('data_akademik');
      }

      $where = array('id_akademik' => $id);

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
    $this->form_validation->set_rules('semester', 'Semester', 'required');
    $this->form_validation->set_rules('is_aktif', 'Status Aktif', 'required');
  }
}
