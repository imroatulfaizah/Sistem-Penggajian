<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DataJabatan extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // gunakan penggajianModel yang sudah ada di project kamu
        $this->load->model('penggajianModel');
        $this->load->library('form_validation');

        if ($this->session->userdata('hak_akses') != '1') {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Anda belum login!</div>');
            redirect('welcome');
        }
    }

    // index: menampilkan daftar jabatan + periode aktif (valid_to IS NULL)
    public function index()
    {
        $data['title'] = "Data Jabatan";

        $data['jabatan'] = $this->db->query("
            SELECT dj.id_jabatan,
                   dj.nama_jabatan,
                   COALESCE(djp.tunjangan_jabatan,0) AS tunjangan_jabatan,
                   COALESCE(djp.tunjangan_transport,0) AS tunjangan_transport,
                   COALESCE(djp.upah_mengajar,0) AS upah_mengajar,
                   da.tahun_akademik,
                   da.semester
            FROM data_jabatan dj
            LEFT JOIN data_jabatan_periode djp 
                ON dj.id_jabatan = djp.id_jabatan AND djp.valid_to IS NULL
            LEFT JOIN data_akademik da
                ON da.id_akademik = djp.id_akademik
            ORDER BY dj.id_jabatan ASC
        ")->result();

        $this->load->view('templates_admin/header', $data);
        $this->load->view('templates_admin/sidebar');
        $this->load->view('admin/jabatan/dataJabatan', $data);
        $this->load->view('templates_admin/footer');
    }

    // form tambah
    public function tambahData()
    {
        $data['title'] = "Tambah Data Jabatan";
        $data['akademik'] = $this->penggajianModel->get_data('data_akademik')->result();

        $this->load->view('templates_admin/header', $data);
        $this->load->view('templates_admin/sidebar');
        $this->load->view('admin/jabatan/tambahDataJabatan', $data);
        $this->load->view('templates_admin/footer');
    }

    // aksi tambah
    public function tambahDataAksi()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->tambahData();
            return;
        }

        // insert ke data_jabatan (nama saja)
        $jabatan = [
            'nama_jabatan' => $this->input->post('nama_jabatan'),
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->penggajianModel->insert_data($jabatan, 'data_jabatan');
        $id_jabatan = $this->db->insert_id();

        // insert ke data_jabatan_periode (tunjangan)
        $periode = [
            'id_jabatan' => $id_jabatan,
            'id_akademik' => $this->input->post('id_akademik'),
            'tunjangan_jabatan' => $this->input->post('tunjangan_jabatan') ?: 0,
            'tunjangan_transport' => $this->input->post('tunjangan_transport') ?: 0,
            'upah_mengajar' => $this->input->post('upah_mengajar') ?: 0,
            'valid_from' => $this->input->post('valid_from') ? $this->input->post('valid_from') : date('Y-m-d'),
            'valid_to' => NULL,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->penggajianModel->insert_data($periode, 'data_jabatan_periode');

        $this->session->set_flashdata('pesan', '<div class="alert alert-success">Data berhasil ditambahkan</div>');
        redirect('admin/dataJabatan');
    }

    // form update
    public function updateData($id)
    {
        // ambil data jabatan (master)
        $data['jabatan'] = $this->db->get_where('data_jabatan', ['id_jabatan' => $id])->row();

        // ambil periode aktif (jika ada)
        $data['periode'] = $this->db->get_where('data_jabatan_periode', ['id_jabatan' => $id, 'valid_to' => NULL])->row();

        $data['akademik'] = $this->penggajianModel->get_data('data_akademik')->result();
        $data['title'] = "Update Data Jabatan";

        $this->load->view('templates_admin/header', $data);
        $this->load->view('templates_admin/sidebar');
        $this->load->view('admin/jabatan/updateDataJabatan', $data);
        $this->load->view('templates_admin/footer');
    }

    // aksi update
    public function updateDataAksi()
    {
        $this->_rules();

        $id = $this->input->post('id_jabatan');

        if ($this->form_validation->run() == FALSE) {
            $this->updateData($id);
            return;
        }

        // update nama jabatan di master
        $this->penggajianModel->update_data('data_jabatan',
            ['nama_jabatan' => $this->input->post('nama_jabatan')],
            ['id_jabatan' => $id]);

        // jika ada periode aktif, tutup periode lama (set valid_to)
        $this->db->where('id_jabatan', $id);
        $this->db->where('valid_to', NULL);
        $this->db->update('data_jabatan_periode', ['valid_to' => date('Y-m-d')]);

        // insert periode baru dengan nilai yang diberikan
        $periode = [
            'id_jabatan' => $id,
            'id_akademik' => $this->input->post('id_akademik'),
            'tunjangan_jabatan' => $this->input->post('tunjangan_jabatan') ?: 0,
            'tunjangan_transport' => $this->input->post('tunjangan_transport') ?: 0,
            'upah_mengajar' => $this->input->post('upah_mengajar') ?: 0,
            'valid_from' => $this->input->post('valid_from') ? $this->input->post('valid_from') : date('Y-m-d'),
            'valid_to' => NULL,
            'created_at' => date('Y-m-d H:i:s')
        ];
        $this->penggajianModel->insert_data($periode, 'data_jabatan_periode');

        $this->session->set_flashdata('pesan', '<div class="alert alert-success">Data berhasil diupdate</div>');
        redirect('admin/dataJabatan');
    }

    // delete (hapus master => cascade ke periode)
    public function deleteData($id)
    {
        $this->penggajianModel->delete_data(['id_jabatan' => $id], 'data_jabatan');
        $this->session->set_flashdata('pesan', '<div class="alert alert-success">Data berhasil dihapus</div>');
        redirect('admin/dataJabatan');
    }

    // print (cetak) menggunakan current periode aktif
    public function printData()
    {
        $data['title'] = "Cetak Data Jabatan";
        $data['jabatan'] = $this->db->query("
            SELECT dj.id_jabatan,
                   dj.nama_jabatan,
                   COALESCE(djp.tunjangan_jabatan,0) AS tunjangan_jabatan,
                   COALESCE(djp.tunjangan_transport,0) AS tunjangan_transport,
                   COALESCE(djp.upah_mengajar,0) AS upah_mengajar,
                   da.tahun_akademik,
                   da.semester
            FROM data_jabatan dj
            LEFT JOIN data_jabatan_periode djp 
                ON dj.id_jabatan = djp.id_jabatan AND djp.valid_to IS NULL
            LEFT JOIN data_akademik da
                ON da.id_akademik = djp.id_akademik
            ORDER BY dj.id_jabatan ASC
        ")->result();

        // optional: build title_periode (ambil 1 periode aktif jika ada)
        $periode = $this->db->query("SELECT tahun_akademik, semester FROM data_akademik WHERE id_akademik = (
            SELECT id_akademik FROM data_jabatan_periode WHERE valid_to IS NULL LIMIT 1
        )")->row();
        $data['title_periode'] = $periode ? ($periode->semester.' '.$periode->tahun_akademik) : '';

        $this->load->view('admin/jabatan/printDataJabatan', $data);
    }

    // validasi form
    private function _rules()
    {
        $this->form_validation->set_rules('nama_jabatan', 'Nama Jabatan', 'required');
        $this->form_validation->set_rules('id_akademik', 'Tahun Akademik', 'required');
        $this->form_validation->set_rules('tunjangan_jabatan', 'Tunjangan Jabatan', 'required|numeric');
        $this->form_validation->set_rules('tunjangan_transport', 'Tunjangan Transport', 'required|numeric');
        $this->form_validation->set_rules('upah_mengajar', 'Upah Mengajar', 'required|numeric');
    }
}
