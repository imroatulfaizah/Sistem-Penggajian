<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SlipGaji extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('nama_bulan');

        if ($this->session->userdata('hak_akses') != '1') {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Anda belum login!</div>');
            redirect('welcome');
        }
    }

    public function index()
    {
        $data['title']   = "Filter Slip Gaji Pegawai";
        $data['pegawai'] = $this->db->order_by('nama_pegawai', 'ASC')->get('data_pegawai')->result();

        // Tidak perlu kalender lagi
        // $this->load->library('calendar', $kalender);
        // $data['kalender'] = $this->calendar->generate($tahun, $bulan);

        $this->load->view('templates_admin/header', $data);
        $this->load->view('templates_admin/sidebar');
        $this->load->view('admin/SlipGaji/filterSlipGaji', $data);
        $this->load->view('templates_admin/footer');
    }

    public function cetakSlipGaji()
    {
        $nip   = $this->input->post('nip');
        $bulan = str_pad($this->input->post('bulan'), 2, '0', STR_PAD_LEFT);
        $tahun = $this->input->post('tahun');
        $bulanTahun = $bulan . $tahun;

        $sql = "
            SELECT 
                dp.nip, 
                dp.nama_pegawai,
                j.nama_jabatan,
                COALESCE(p.tunjangan_jabatan, 0) AS tunjangan_jabatan,
                COALESCE(p.tunjangan_transport, 0) AS tunjangan_transport,
                COALESCE(p.upah_mengajar, 0) AS upah_mengajar,
                dk.hadir,
                dk.bulan,
                COALESCE(ins.total_insentif, 0) AS total_insentif,
                (
                    SELECT COALESCE(SUM(am.total_jam), 0)
                    FROM absensi_mengajar am
                    JOIN data_penempatan pen ON am.id_penempatan = pen.id_penempatan
                    WHERE pen.nip = ? AND DATE_FORMAT(am.jam_clockin, '%m%Y') = ?
                ) AS total_jam_mengajar
            FROM data_pegawai dp
            JOIN data_kehadiran dk ON dk.nip = dp.nip AND dk.bulan = ?
            JOIN data_jabatan j ON j.id_jabatan = dp.jabatan
            LEFT JOIN data_jabatan_periode p 
                ON p.id_jabatan = j.id_jabatan AND p.valid_to IS NULL
            LEFT JOIN (
                SELECT nip, SUM(nominal) AS total_insentif
                FROM data_insentif WHERE is_paid = '0' GROUP BY nip
            ) ins ON ins.nip = dp.nip
            WHERE dp.nip = ?
        ";

        $slip = $this->db->query($sql, [$nip, $bulanTahun, $bulanTahun, $nip])->row();

        if (!$slip) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Data slip gaji tidak ditemukan!</div>');
            redirect('admin/slipGaji');
        }

        $data['title'] = "Slip Gaji - " . nama_bulan($bulanTahun);
        $data['slip']  = $slip;

        $this->load->view('templates_admin/header', $data);
        $this->load->view('pegawai/cetakSlipGaji', $data);   // desain modern
        // Jika ingin tetap pakai view admin lama (tapi tidak direkomendasikan):
        // $this->load->view('admin/SlipGaji/cetakSlipGaji', $data);
    }
}