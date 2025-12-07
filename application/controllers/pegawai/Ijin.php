<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ijin extends CI_Controller {
    public function __construct() {
        parent::__construct();
        if ($this->session->userdata('hak_akses') != '2') {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Anda belum login!</div>');
            redirect('welcome');
        }
    }

    public function index() {
        $data['title'] = "Ajukan Ijin/Cuti";
        $nip = $this->session->userdata('nip');
        $data['daftar_ijin'] = $this->db->where('nip', $nip)
                                        ->order_by('created_at', 'DESC')
                                        ->get('data_ijin')
                                        ->result();

        $this->load->view('templates_pegawai/header', $data);
        $this->load->view('templates_pegawai/sidebar');
        $this->load->view('pegawai/ijin/index', $data);  // view daftar + form
        $this->load->view('templates_pegawai/footer');
    }

    public function ajukan() {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->index();
        } else {
            $data = [
                'nip' => $this->session->userdata('nip'),
                'jenis_ijin' => $this->input->post('jenis_ijin'),
                'keterangan' => $this->input->post('keterangan'),
                'tanggal_mulai' => $this->input->post('tanggal_mulai'),
                'tanggal_selesai' => $this->input->post('tanggal_selesai'),
                'status' => 'pending'
            ];

            $this->db->insert('data_ijin', $data);
            $this->session->set_flashdata('pesan', '<div class="alert alert-success">Permohonan ijin berhasil diajukan! Tunggu persetujuan.</div>');
            redirect('pegawai/ijin');
        }
    }

    private function _rules() {
        $this->form_validation->set_rules('jenis_ijin', 'Jenis Ijin', 'required');
        $this->form_validation->set_rules('tanggal_mulai', 'Tanggal Mulai', 'required');
        $this->form_validation->set_rules('tanggal_selesai', 'Tanggal Selesai', 'required');
    }
}