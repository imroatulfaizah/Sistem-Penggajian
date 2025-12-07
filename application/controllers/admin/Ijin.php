<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ijin extends CI_Controller {
    public function __construct() {
        parent::__construct();
        if ($this->session->userdata('hak_akses') != '1') {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Anda belum login!</div>');
            redirect('welcome');
        }
    }

    public function index() {
        $data['title'] = "Kelola Ijin Pegawai";
        $data['daftar_ijin'] = $this->db->select('di.*, dp.nama_pegawai')
                                        ->from('data_ijin di')
                                        ->join('data_pegawai dp', 'di.nip = dp.nip')
                                        ->order_by('di.created_at', 'DESC')
                                        ->get()
                                        ->result();

        $this->load->view('templates_admin/header', $data);
        $this->load->view('templates_admin/sidebar');
        $this->load->view('admin/ijin/index', $data);  // view daftar ijin
        $this->load->view('templates_admin/footer');
    }

    public function approve($id_ijin) {
        $ijin = $this->db->get_where('data_ijin', ['id_ijin' => $id_ijin])->row();

        if ($ijin && $ijin->status == 'pending') {
            // Update status
            $this->db->update('data_ijin', [
                'status' => 'disetujui',
                'approved_by' => $this->session->userdata('nip')
            ], ['id_ijin' => $id_ijin]);

            // Hitung jumlah hari izin (exclude Sabtu/Minggu)
            $jumlah_hari = $this->_hitung_hari_kerja($ijin->tanggal_mulai, $ijin->tanggal_selesai);

            // Update data_kehadiran.izin (asumsikan 1 bulan dulu; extend jika multi bulan)
            $bulan = date('mY', strtotime($ijin->tanggal_mulai));  // mmYYYY
            $this->db->set('izin', 'izin + ' . (int)$jumlah_hari, FALSE);
            $this->db->where('nip', $ijin->nip);
            $this->db->where('bulan', $bulan);
            $this->db->update('data_kehadiran');

            $this->session->set_flashdata('pesan', '<div class="alert alert-success">Ijin disetujui! Izin ditambahkan ke kehadiran.</div>');
        } else {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Ijin tidak ditemukan atau sudah diproses.</div>');
        }
        redirect('admin/ijin');
    }

    public function tolak($id_ijin) {
        $this->db->update('data_ijin', ['status' => 'ditolak'], ['id_ijin' => $id_ijin]);
        $this->session->set_flashdata('pesan', '<div class="alert alert-warning">Ijin ditolak.</div>');
        redirect('admin/ijin');
    }

    private function _hitung_hari_kerja($start, $end) {
        $start = new DateTime($start);
        $end = new DateTime($end);
        $end->modify('+1 day');  // include end date

        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($start, $interval, $end);

        $hari_kerja = 0;
        foreach ($period as $dt) {
            $hari = $dt->format('N');  // 1=Senin, 7=Minggu
            if ($hari < 6) {  // Senin-Jumat
                $hari_kerja++;
            }
        }
        return $hari_kerja;
    }
}