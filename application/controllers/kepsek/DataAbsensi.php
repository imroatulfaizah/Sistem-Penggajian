<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DataAbsensi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->session->userdata('hak_akses') != '3') {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Anda tidak memiliki akses!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>');
            redirect('welcome');
        }
    }

    public function index() {
        $data['title'] = "Data Absensi Pegawai";

        // Filter bulan & tahun
        $bulan = $this->input->get('bulan') ?: date('m');
        $tahun = $this->input->get('tahun') ?: date('Y');
        $bulanTahun = $bulan . $tahun;

        $data['bulan_selected'] = $bulan;
        $data['tahun_selected'] = $tahun;

        // === Query utama (untuk count & data) ===
        $this->db->select('dk.*, dp.nama_pegawai, dp.jenis_kelamin, dj.nama_jabatan AS jabatan');
        $this->db->from('data_kehadiran dk');
        $this->db->join('data_pegawai dp', 'dk.nip = dp.nip');
        $this->db->join('data_jabatan dj', 'dp.jabatan = dj.id_jabatan');
        $this->db->where('dk.bulan', $bulanTahun);

        // Clone query untuk hitung total baris (pagination)
        $count_query = clone $this->db;
        $total_rows  = $count_query->count_all_results();

        // === Pagination – persis seperti DataPenempatan ===
        $this->load->library('pagination');

        $config['base_url']             = base_url('kepsek/dataAbsensi');
        $config['total_rows']           = $total_rows;
        $config['per_page']             = 15;
        $config['page_query_string']    = TRUE;
        $config['query_string_segment'] = 'page';
        $config['reuse_query_string']   = TRUE;   // penting: biar bulan & tahun tetap

        // Style persis seperti contoh DataPenempatan (Bootstrap 4)
        $config['full_tag_open']    = '<nav><ul class="pagination justify-content-center">';
        $config['full_tag_close']   = '</ul></nav>';
        $config['attributes']       = ['class' => 'page-link'];
        $config['first_link']       = 'First';
        $config['last_link']        = 'Last';
        $config['first_tag_open']   = '<li class="page-item">';
        $config['first_tag_close']  = '</li>';
        $config['prev_link']        = 'Previous';
        $config['prev_tag_open']    = '<li class="page-item">';
        $config['prev_tag_close']   = '</li>';
        $config['next_link']        = 'Next';
        $config['next_tag_open']    = '<li class="page-item">';
        $config['next_tag_close']   = '</li>';
        $config['last_tag_open']    = '<li class="page-item">';
        $config['last_tag_close']   = '</li>';
        $config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
        $config['num_tag_open']     = '<li class="page-item">';
        $config['num_tag_close']    = '</li>';

        $this->pagination->initialize($config);

        // Ambil data dengan limit
        $page = $this->input->get('page') ? $this->input->get('page') : 0;
        $this->db->order_by('dp.nama_pegawai', 'ASC');
        $this->db->limit($config['per_page'], $page);

        $data['absensi']    = $this->db->get()->result();
        $data['pagination'] = $this->pagination->create_links();

        // View kepsek
        $this->load->view('templates_kepsek/header', $data);
        $this->load->view('templates_kepsek/sidebar');
        $this->load->view('kepsek/RekapAbsen/dataAbsensi', $data);
        $this->load->view('templates_kepsek/footer');
    }
}