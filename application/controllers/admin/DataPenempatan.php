<?php

class Datapenempatan extends CI_Controller
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
    $data['title'] = "Data Penempatan";
    $data['penempatan'] = $this->penggajianModel->get_data('data_penempatan')->result();
    $this->load->view('templates_admin/header', $data);
    $this->load->view('templates_admin/sidebar');
    $this->load->view('admin/Penempatan/dataPenempatan', $data);
    $this->load->view('templates_admin/footer');
  }

  public function tambahData()
  {
    $data['title'] = "Tambah Data Penempatan";

    $this->load->view('templates_admin/header', $data);
    $this->load->view('templates_admin/sidebar');
    $this->load->view('admin/Penempatan/tambahDatapenempatan', $data);
    $this->load->view('templates_admin/footer');
  }

  public function tambahDataAksi()
  {
    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
      $this->tambahData();
    } else {
      $id_pelajaran = $this->input->post('id_pelajaran');
      $id_kelas = $this->input->post('id_kelas');
      $id_akademik = $this->input->post('id_akademik');
      $nip = $this->input->post('nip');
      $jam_mulai = $this->input->post('jam_mulai');
      $jam_akhir = $this->input->post('jam_akhir');
      $keterangan = $this->input->post('keterangan');

      $mulai = DateTime::createFromFormat('H:i', $jam_mulai);
      $akhir = DateTime::createFromFormat('H:i', $jam_akhir);

        if ($mulai && $akhir) {
          if ($akhir < $mulai) {
            $akhir->modify('+1 day');
        }
            $interval = $mulai->diff($akhir);
            $total_jam = $interval->format('%H:%I:%S'); // Format HH:MM:SS
        } 

      $data = array(
        'id_pelajaran' => $id_pelajaran,
        'id_kelas' => $id_kelas,
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
      redirect('admin/datapenempatan');
    }
  }

  public function updateData($id)
  {
    // $where = array('id_penempatan' => $id);
    $data['penempatan'] = $this->db->query("SELECT * FROM data_penempatan WHERE id_penempatan = '$id'")->result();
    $data['title'] = "Update Data penempatan";

    $this->load->view('templates_admin/header', $data);
    $this->load->view('templates_admin/sidebar');
    $this->load->view('admin/penempatan/updateDatapenempatan', $data);
    $this->load->view('templates_admin/footer');
  }

  public function updateDataAksi()
  {
    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
      $id = $this->input->post('id_penempatan');
      $this->updateData($id);
    } else {
      $id           = $this->input->post('id_penempatan');
      $id_pelajaran = $this->input->post('id_pelajaran');
      $id_kelas = $this->input->post('id_kelas');
      $id_akademik = $this->input->post('id_akademik');
      $nip = $this->input->post('nip');
      $jam_mulai = $this->input->post('jam_mulai');
      $jam_akhir = $this->input->post('jam_akhir');
      $keterangan = $this->input->post('keterangan');

      $mulai = DateTime::createFromFormat('H:i', $jam_mulai);
      $akhir = DateTime::createFromFormat('H:i', $jam_akhir);

      if ($mulai && $akhir) {
        if ($akhir < $mulai) {
          $akhir->modify('+1 day');
      }
          $interval = $mulai->diff($akhir);
          $total_jam = $interval->h . " jam " . $interval->i . " menit";
        } else {
            $total_jam = "0 jam 0 menit";
        }


      $data = array(
        'id_pelajaran' => $id_pelajaran,
        'id_kelas' => $id_kelas,
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
      redirect('admin/datapenempatan');
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
    redirect('admin/datapenempatan');
  }
  public function printData()
  {
    $data['title'] = "Cetak Data Penempatan";
    $data['penempatan'] = $this->penggajianModel->get_data('data_penempatan')->result();
    $this->load->view('templates_admin/header', $data);
    $this->load->view('admin/penempatan/cetakdatapenempatan');
  }

  public function _rules()
  {
    $this->form_validation->set_rules('id_pelajaran', 'ID Pelajaran', 'required');
    $this->form_validation->set_rules('id_kelas', 'ID Kelas', 'required');
    $this->form_validation->set_rules('id_akademik', 'ID Akademic', 'required');
    $this->form_validation->set_rules('nip', 'NIP', 'required');
    $this->form_validation->set_rules('jam_mulai', 'Jam Mulai', 'required');
    $this->form_validation->set_rules('jam_akhir', 'Jam Akhir', 'required');
    $this->form_validation->set_rules('keterangan', 'Keterangan', 'required');
  }
}
