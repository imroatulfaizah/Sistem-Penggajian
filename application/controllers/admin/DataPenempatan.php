<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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

        $hari     = $this->input->get('hari');
        $keyword  = $this->input->get('keyword');

        // Query utama
        $this->db->select('
            a.*,
            COALESCE(pp.nama_pelajaran, p.nama_pelajaran) as nama_pelajaran,
            c.nama_kelas,
            d.tahun_akademik,
            d.semester,
            d.nama_akademik,
            e.nama_pegawai
        ');
        $this->db->from('data_penempatan a');
        $this->db->join('data_pelajaran p', 'a.id_pelajaran = p.id_pelajaran');
        $this->db->join('data_pelajaran_periode pp', 
            'pp.id_pelajaran = a.id_pelajaran AND pp.id_akademik = a.id_akademik', 'left');
        $this->db->join('data_kelas c', 'a.id_kelas = c.id_kelas');
        $this->db->join('data_akademik d', 'a.id_akademik = d.id_akademik');
        $this->db->join('data_pegawai e', 'a.nip = e.nip', 'left');

        // Filter hari
        if (!empty($hari)) {
            $this->db->where('a.hari', $hari);
        }

        // Search keyword
        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('COALESCE(pp.nama_pelajaran, p.nama_pelajaran)', $keyword);
            $this->db->or_like('e.nama_pegawai', $keyword);
            $this->db->or_like('c.nama_kelas', $keyword);
            $this->db->group_end();
        }

        // Clone untuk hitung total (pagination)
        $count_query = clone $this->db;
        $total_rows  = $count_query->count_all_results();

        // Pagination
        $this->load->library('pagination');
        $config['base_url']            = base_url('admin/datapenempatan');
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

        // Order by hari dan jam
        $this->db->order_by("FIELD(a.hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')");
        $this->db->order_by('a.jam_mulai', 'ASC');
        $this->db->limit($config['per_page'], $offset);

        $data['penempatan'] = $this->db->get()->result();
        $data['pagination'] = $this->pagination->create_links();

        $this->load->view('templates_admin/header', $data);
        $this->load->view('templates_admin/sidebar');
        $this->load->view('admin/Penempatan/dataPenempatan', $data);
        $this->load->view('templates_admin/footer');
    }

    public function tambahData()
    {
        $data['title']     = "Tambah Data Penempatan";
        $data['pelajaran'] = $this->db->get('data_pelajaran')->result();
        $data['kelas']     = $this->db->get('data_kelas')->result();
        $data['pegawai']   = $this->db->where('hak_akses', 2)->get('data_pegawai')->result(); // hanya guru
        $data['akademik']  = $this->db->get('data_akademik')->result();

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
            $data = [
                'id_pelajaran' => $this->input->post('id_pelajaran'),
                'id_kelas'     => $this->input->post('id_kelas'),
                'id_akademik'  => $this->input->post('id_akademik'),
                'nip'          => $this->input->post('nip'),
                'hari'         => $this->input->post('hari'),
                'jam_mulai'    => $this->input->post('jam_mulai'),
                'jam_akhir'    => $this->input->post('jam_akhir'),
                'total_jam'    => $this->input->post('total_jam'),
                'keterangan'   => $this->input->post('keterangan')
            ];

            $this->db->insert('data_penempatan', $data);
            $this->session->set_flashdata('pesan', '<div class="alert alert-success">Data penempatan berhasil ditambahkan!</div>');
            redirect('admin/datapenempatan');
        }
    }

    public function updateData($id)
    {
        $data['title']     = "Update Data Penempatan";
        $data['pelajaran'] = $this->db->get('data_pelajaran')->result();
        $data['kelas']     = $this->db->get('data_kelas')->result();
        $data['pegawai']   = $this->db->where('hak_akses', 2)->get('data_pegawai')->result();
        $data['akademik']  = $this->db->get('data_akademik')->result();
        $data['penempatan']= $this->db->get_where('data_penempatan', ['id_penempatan' => $id])->row();

        $this->load->view('templates_admin/header', $data);
        $this->load->view('templates_admin/sidebar');
        $this->load->view('admin/Penempatan/updateDatapenempatan', $data);
        $this->load->view('templates_admin/footer');
    }

    public function updateDataAksi()
    {
        $this->_rules();
        if ($this->form_validation->run() == FALSE) {
            $this->updateData($this->input->post('id_penempatan'));
        } else {
            $id = $this->input->post('id_penempatan');
            $data = [
                'id_pelajaran' => $this->input->post('id_pelajaran'),
                'id_kelas'     => $this->input->post('id_kelas'),
                'id_akademik'  => $this->input->post('id_akademik'),
                'nip'          => $this->input->post('nip'),
                'hari'         => $this->input->post('hari'),
                'jam_mulai'    => $this->input->post('jam_mulai'),
                'jam_akhir'    => $this->input->post('jam_akhir'),
                'total_jam'    => $this->input->post('total_jam'),
                'keterangan'   => $this->input->post('keterangan')
            ];

            $this->db->where('id_penempatan', $id)->update('data_penempatan', $data);
            $this->session->set_flashdata('pesan', '<div class="alert alert-success">Data penempatan berhasil diupdate!</div>');
            redirect('admin/datapenempatan');
        }
    }

    public function deleteData($id)
    {
        $this->db->delete('data_penempatan', ['id_penempatan' => $id]);
        $this->session->set_flashdata('pesan', '<div class="alert alert-success">Data penempatan berhasil dihapus!</div>');
        redirect('admin/datapenempatan');
    }

    public function printData()
    {
        $data['title'] = "Cetak Data Penempatan";

        $this->db->select('
            a.*,
            COALESCE(pp.nama_pelajaran, p.nama_pelajaran) as nama_pelajaran,
            c.nama_kelas,
            d.nama_akademik,
            e.nama_pegawai
        ');
        $this->db->from('data_penempatan a');
        $this->db->join('data_pelajaran p', 'a.id_pelajaran = p.id_pelajaran');
        $this->db->join('data_pelajaran_periode pp', 
            'pp.id_pelajaran = a.id_pelajaran AND pp.id_akademik = a.id_akademik', 'left');
        $this->db->join('data_kelas c', 'a.id_kelas = c.id_kelas');
        $this->db->join('data_akademik d', 'a.id_akademik = d.id_akademik');
        $this->db->join('data_pegawai e', 'a.nip = e.nip', 'left');
        $this->db->order_by("FIELD(a.hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')");
        $this->db->order_by('a.jam_mulai', 'ASC');

        $data['penempatan'] = $this->db->get()->result();

        $this->load->view('templates_admin/header', $data);
        $this->load->view('admin/Penempatan/cetakdatapenempatan', $data);
    }

    private function _rules()
    {
        $this->form_validation->set_rules('id_pelajaran', 'Pelajaran', 'required');
        $this->form_validation->set_rules('id_kelas', 'Kelas', 'required');
        $this->form_validation->set_rules('id_akademik', 'Tahun Akademik', 'required');
        $this->form_validation->set_rules('nip', 'Guru', 'required');
        $this->form_validation->set_rules('hari', 'Hari', 'required');
        $this->form_validation->set_rules('jam_mulai', 'Jam Mulai', 'required');
        $this->form_validation->set_rules('jam_akhir', 'Jam Akhir', 'required');
        $this->form_validation->set_rules('total_jam', 'Total Jam', 'required|numeric');
    }
}