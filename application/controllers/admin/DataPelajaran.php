<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DataPelajaran extends CI_Controller {

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

    // ================== LIST MASTER PELAJARAN ==================
    public function index()
    {
        $data['title'] = "Data Master Pelajaran";
        $data['pelajaran'] = $this->db->order_by('nama_pelajaran', 'ASC')
                                      ->get('data_pelajaran')->result();

        $this->load->view('templates_admin/header', $data);
        $this->load->view('templates_admin/sidebar');
        $this->load->view('admin/Pelajaran/dataPelajaran', $data);
        $this->load->view('templates_admin/footer');
    }

    // ================== TAMBAH MASTER PELAJARAN ==================
    public function tambahData()
    {
        $data['title'] = "Tambah Master Pelajaran";
        $this->load->view('templates_admin/header', $data);
        $this->load->view('templates_admin/sidebar');
        $this->load->view('admin/Pelajaran/tambahDataPelajaran', $data);
        $this->load->view('templates_admin/footer');
    }

    public function tambahDataAksi()
    {
        $this->_rules();
        if ($this->form_validation->run() == FALSE) {
            $this->tambahData();
        } else {
            $nama = $this->input->post('nama_pelajaran');

            $this->db->insert('data_pelajaran', ['nama_pelajaran' => $nama]);
            $id_pelajaran = $this->db->insert_id();

            // Otomatis buat versi untuk semester aktif
            $id_akademik = $this->getAkademikAktif()->id_akademik;
            $this->db->insert('data_pelajaran_periode', [
                'id_pelajaran' => $id_pelajaran,
                'id_akademik'  => $id_akademik,
                'nama_pelajaran' => $nama
            ]);

            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Master pelajaran berhasil ditambahkan!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>');
            redirect('admin/dataPelajaran');
        }
    }

    // ================== UBAH NAMA PELAJARAN PER SEMESTER ==================
    public function ubahNama($id_pelajaran)
    {
        $data['pel'] = $this->db->get_where('data_pelajaran', ['id_pelajaran' => $id_pelajaran])->row();
        if (!$data['pel']) show_404();

        $data['akademik_aktif'] = $this->getAkademikAktif();
        $data['history'] = $this->db->get_where('data_pelajaran_periode', [
            'id_pelajaran' => $id_pelajaran,
            'id_akademik'  => $data['akademik_aktif']->id_akademik
        ])->row();

        $data['title'] = "Ubah Nama Pelajaran (Semester Aktif)";
        $this->load->view('templates_admin/header', $data);
        $this->load->view('templates_admin/sidebar');
        $this->load->view('admin/Pelajaran/ubahNamaPelajaran', $data);
        $this->load->view('templates_admin/footer');
    }

    public function ubahNamaAksi()
    {
        $this->_rules();
        if ($this->form_validation->run() == FALSE) {
            $id = $this->input->post('id_pelajaran');
            $this->ubahNama($id);
        } else {
            $id_pelajaran = $this->input->post('id_pelajaran');
            $nama_baru    = $this->input->post('nama_pelajaran');
            $id_akademik  = $this->getAkademikAktif()->id_akademik;

            $exist = $this->db->get_where('data_pelajaran_periode', [
                'id_pelajaran' => $id_pelajaran,
                'id_akademik'  => $id_akademik
            ])->row();

            $data = [
                'id_pelajaran'   => $id_pelajaran,
                'id_akademik'    => $id_akademik,
                'nama_pelajaran' => $nama_baru
            ];

            if ($exist) {
                $this->db->where('id', $exist->id)->update('data_pelajaran_periode', $data);
            } else {
                $this->db->insert('data_pelajaran_periode', $data);
            }

            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Nama pelajaran untuk semester aktif berhasil diubah!<br>
                Nama lama tetap tersimpan di data sebelumnya.</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>');
            redirect('admin/dataPelajaran');
        }
    }

    // ================== HAPUS MASTER (hanya jika belum dipakai) ==================
    public function deleteData($id)
    {
        // Cek apakah sudah dipakai di penempatan
        $used = $this->db->where('id_pelajaran', $id)->get('data_penempatan')->num_rows();
        if ($used > 0) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Pelajaran tidak dapat dihapus karena sudah digunakan di jadwal!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>');
        } else {
            $this->db->delete('data_pelajaran', ['id_pelajaran' => $id]);
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Master pelajaran berhasil dihapus!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>');
        }
        redirect('admin/dataPelajaran');
    }

    // ================== HELPER ==================
    private function getAkademikAktif()
    {
        return $this->db->where('is_aktif', 1)->get('data_akademik')->row();
    }

    public function _rules()
    {
        $this->form_validation->set_rules('nama_pelajaran', 'Nama Pelajaran', 'required|trim');
    }
}