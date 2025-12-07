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

      // Ambil semua pegawai untuk dropdown
      $data['pegawai'] = $this->db->select('nip, nama_pegawai')
                                  ->from('data_pegawai')
                                  ->order_by('nama_pegawai', 'ASC')
                                  ->get()->result();

      $this->load->view('templates_admin/header', $data);
      $this->load->view('templates_admin/sidebar');
      $this->load->view('admin/Insentif/tambahDataInsentif', $data);
      $this->load->view('templates_admin/footer');
  }

public function tambahDataAksi()
{
    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
        $this->tambahData(); // kembali ke form dengan error
    } else {
        $data = [
            'nip'            => $this->input->post('nip', true),
            'nama_insentif'  => $this->input->post('nama_insentif', true),
            'nominal'        => $this->input->post('nominal', true),
            'is_paid'        => $this->input->post('is_paid', true),
            'nomor_kwitansi' => $this->input->post('nomor_kwitansi', true) ?: null
        ];

        $this->penggajianModel->insert_data($data, 'data_insentif');

        $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Insentif berhasil ditambahkan!</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>');
        redirect('admin/dataInsentif');
    }
}

  public function updateData($id)
  {
      $data['title'] = "Update Data Insentif";

      // Ambil satu data insentif
      $data['insentif'] = $this->db->get_where('data_insentif', ['id_insentif' => $id])->result();

      if (empty($data['insentif'])) {
          $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Data tidak ditemukan!</div>');
          redirect('admin/dataInsentif');
      }

      // Ambil semua pegawai untuk dropdown
      $data['pegawai'] = $this->db->select('nip, nama_pegawai')
                                  ->from('data_pegawai')
                                  ->order_by('nama_pegawai', 'ASC')
                                  ->get()->result();

      $this->load->view('templates_admin/header', $data);
      $this->load->view('templates_admin/sidebar');
      $this->load->view('admin/Insentif/updateDataInsentif', $data);
      $this->load->view('templates_admin/footer');
  }

  public function updateDataAksi()
  {
      $this->_rules();

      $id = $this->input->post('id_insentif');

      if ($this->form_validation->run() == FALSE) {
          $this->updateData($id); // kembali ke form dengan error
      } else {
          $data = [
              'nip'            => $this->input->post('nip', true),
              'nama_insentif'  => $this->input->post('nama_insentif', true),
              'nominal'        => $this->input->post('nominal', true),
              'is_paid'        => $this->input->post('is_paid', true),
              'nomor_kwitansi' => $this->input->post('nomor_kwitansi', true) ?: null
          ];

          $where = ['id_insentif' => $id];

          $this->penggajianModel->update_data('data_insentif', $data, $where);

          $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
              <strong>Data insentif berhasil diupdate!</strong>
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
      $this->form_validation->set_rules('nip', 'Pegawai', 'required|trim');
      $this->form_validation->set_rules('nama_insentif', 'Nama Insentif', 'required|trim');
      $this->form_validation->set_rules('nominal', 'Nominal', 'required|numeric|greater_than[0]');
      $this->form_validation->set_rules('is_paid', 'Status Pembayaran', 'required|in_list[0,1]');
      // nomor_kwitansi opsional, jadi tidak wajib
  }
}
