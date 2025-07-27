<?php

class DataPenempatan extends CI_Controller
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
    $data['title'] = "Data Penempatan";
    //$data['penempatan'] = $this->penggajianModel->get_data('data_penempatan')->result();
    $data['penempatan'] = $this->db->query("SELECT a.*, SUM(total_jam) as total_jam, b.nama_pelajaran as nama_pelajaran, c.nama_kelas as nama_kelas FROM data_penempatan a
    INNER JOIN data_pelajaran b on a.id_pelajaran = b.id_pelajaran
    INNER JOIN data_kelas c ON a.id_kelas = c.id_kelas")->result();
    $this->load->view('templates_pegawai/header', $data);
    $this->load->view('templates_pegawai/sidebar');
    $this->load->view('pegawai/dataPenempatan', $data);
    $this->load->view('templates_pegawai/footer');
  }

  public function tambahData()
  {
    $data['title'] = "Tambah Data Penempatan";

    $this->load->view('templates_pegawai/header', $data);
    $this->load->view('templates_pegawai/sidebar');
    $this->load->view('pegawai/tambahDatapenempatan', $data);
    $this->load->view('templates_pegawai/footer');
  }

  public function tambahDataAksi()
  {
    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
      $this->tambahData();
    } else {
      $id_pelajaran = $this->input->post('id_pelajaran');
      $id_kelas = $this->input->post('id_kelas');
      $tunjangan_penempatan = $this->input->post('tunjangan_penempatan');
      $id_akademik = $this->input->post('id_akademik');
      $nip = $this->input->post('nip');
      $jam_mulai = $this->input->post('jam_mulai');
      $jam_akhir = $this->input->post('jam_akhir');
      $total_jam = $this->input->post('total_jam');
      $keterangan = $this->input->post('keterangan');

      $data = array(
        'id_pelajaran' => $id_pelajaran,
        'id_kelas' => $id_kelas,
        'tunjangan_penempatan' => $tunjangan_penempatan,
        'id_akademik' => $id_akademik,
        'nip' => $nip,
        'jam_mulai' => $jam_mulai,
        'jam_akhir' => $jam_akhir,
        'total_jam' => $total_jam,
        'keterangan' => $keterangan,
      );

      $this->penggajianModel->insert_data($data, 'data_penempatan');
      $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Data berhasil ditambahkan</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
      redirect('pegawai/datapenempatan');
    }
  }

  public function updateData($id)
  {
    // $where = array('id_penempatan' => $id);
    $data['penempatan'] = $this->db->query("SELECT * FROM data_penempatan WHERE id_penempatan = '$id'")->result();
    $data['title'] = "Update Data penempatan";

    $this->load->view('templates_pegawai/header', $data);
    $this->load->view('templates_pegawai/sidebar');
    $this->load->view('pegawai/updateDatapenempatan', $data);
    $this->load->view('templates_pegawai/footer');
  }

  public function updateDataAksi()
  {
    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
      $id = $this->insert->post('id_penempatan');
      $this->updateData($id);
    } else {
      $id           = $this->input->post('id_penempatan');
      $id_pelajaran = $this->input->post('id_pelajaran');
      $id_kelas = $this->input->post('id_kelas');
      $tunjangan_penempatan = $this->input->post('tunjangan_penempatan');
      $id_akademik = $this->input->post('id_akademik');
      $nip = $this->input->post('nip');
      $jam_mulai = $this->input->post('jam_mulai');
      $jam_akhir = $this->input->post('jam_akhir');
      $total_jam = $this->input->post('total_jam');
      $keterangan = $this->input->post('keterangan');

      $data = array(
        'id_pelajaran' => $id_pelajaran,
        'id_kelas' => $id_kelas,
        'tunjangan_penempatan' => $tunjangan_penempatan,
        'id_akademik' => $id_akademik,
        'nip' => $nip,
        'jam_mulai' => $jam_mulai,
        'jam_akhir' => $jam_akhir,
        'total_jam' => $total_jam,
        'keterangan' => $keterangan,
      );

      $where = array(
        'id_penempatan' => $id
      );

      $this->penggajianModel->update_data('data_penempatan', $data, $where);
      $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Data berhasil diupdate</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
      redirect('pegawai/datapenempatan');
    }
  }

  public function deleteData($id)
  {
    $where = array('id_penempatan' => $id);
    $this->penggajianModel->delete_data($where, 'data_penempatan');
    $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Data berhasil dihapus</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
    redirect('pegawai/datapenempatan');
  }
  public function printData()
  {
    $data['title'] = "Cetak Data Penempatan";
    $nip = $this->session->userdata('nip');
    //$data['penempatan'] = $this->penggajianModel->get_data('data_penempatan')->result();
    $data['penempatan'] = $this->db->query("SELECT * FROM data_penempatan WHERE nip = '$nip'")->result();
    $this->load->view('templates_pegawai/header', $data);
    $this->load->view('pegawai/cetakdatapenempatan');
  }

  public function _rules()
  {
    $this->form_validation->set_rules('id_pelajaran', 'ID Pelajaran', 'required');
    $this->form_validation->set_rules('id_kelas', 'ID Kelas', 'required');
    $this->form_validation->set_rules('tunjangan_penempatan', 'Tunjangan Penempatan', 'required');
    $this->form_validation->set_rules('id_akademik', 'ID Akademic', 'required');
    $this->form_validation->set_rules('nip', 'NIP', 'required');
    $this->form_validation->set_rules('jam_mulai', 'Jam Mulai', 'required');
    $this->form_validation->set_rules('jam_akhir', 'Jam Akhir', 'required');
    $this->form_validation->set_rules('total_jam', 'Total Jam', 'required');
    $this->form_validation->set_rules('keterangan', 'Keterangan', 'required');
  }
}
