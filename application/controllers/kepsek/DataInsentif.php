<?php

class DataInsentif extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('hak_akses') != '3') {
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

    // Query utama
    $this->db->from('data_insentif');

    // Clone untuk hitung total (pagination)
    $count_query = clone $this->db;
    $total_rows  = $count_query->count_all_results();

    // Pagination
    $this->load->library('pagination');
    $config['base_url']            = base_url('kepsek/dataInsentif');
    $config['total_rows']          = $total_rows;
    $config['per_page']            = 15;
    $config['page_query_string']   = TRUE;
    $config['query_string_segment']= 'page';
    $config['reuse_query_string']  = TRUE;

    // Bootstrap 4 styling
    $config['full_tag_open']    = '<nav><ul class="pagination justify-content-center">';
    $config['full_tag_close']   = '</ul></nav>';
    $config['attributes']       = ['class' => 'page-link'];
    $config['first_link']       = 'First';
    $config['last_link']        = 'Last';
    $config['first_tag_open']   = '<li class="page-item">';
    $config['first_tag_close']  = '</li>';
    $config['prev_link']        = '&laquo;';
    $config['prev_tag_open']    = '<li class="page-item">';
    $config['prev_tag_close']   = '</li>';
    $config['next_link']        = '&raquo;';
    $config['next_tag_open']    = '<li class="page-item">';
    $config['next_tag_close']   = '</li>';
    $config['last_tag_open']    = '<li class="page-item">';
    $config['last_tag_close']   = '</li>';
    $config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
    $config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
    $config['num_tag_open']     = '<li class="page-item">';
    $config['num_tag_close']    = '</li>';

    $this->pagination->initialize($config);

    $page   = $this->input->get('page') ? $this->input->get('page') : 0;
    $offset = $page;

    // Order by (misal by id_insentif DESC)
    $this->db->order_by('id_insentif', 'DESC');
    $this->db->limit($config['per_page'], $offset);

    $data['insentif'] = $this->db->get()->result();
    $data['pagination'] = $this->pagination->create_links();
    $data['offset'] = $offset; // Kirim offset ke view untuk nomor urut

    $this->load->view('templates_kepsek/header', $data);
    $this->load->view('templates_kepsek/sidebar');
    $this->load->view('kepsek/Insentif/dataInsentif', $data);
    $this->load->view('templates_kepsek/footer');
  }

  public function printData()
  {
    $data['title'] = "Cetak Data Jabatan";
    $data['insentif'] = $this->penggajianModel->get_data('data_insentif')->result();
    $this->load->view('templates_kepsek/header', $data);
    $this->load->view('kepsek/Insentif/cetakdatainsentif');
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