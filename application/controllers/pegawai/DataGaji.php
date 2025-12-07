<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DataGaji extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('hak_akses') != '2') {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Akses ditolak!</div>');
            redirect('welcome');
        }
        $this->load->helper('nama_bulan');
    }

    public function index()
    {
        $data['title'] = "Data Gaji Saya";
        $nip = $this->session->userdata('nip');

        $bulan = $this->input->get('bulan') ?: date('m');
        $tahun = $this->input->get('tahun') ?: date('Y');
        $bulanTahun = $bulan . $tahun;

        $data['selected_bulan'] = $bulan;
        $data['selected_tahun'] = $tahun;

        // Query yang AMAN: tidak pakai kolom sakit/izin/alfa yang tidak ada
        $sql = "
            SELECT 
                dk.hadir,
                dp.nama_pegawai,
                dp.jenis_kelamin,
                j.nama_jabatan,

                COALESCE(p.tunjangan_jabatan, 0) AS tunjangan_jabatan,
                COALESCE(p.tunjangan_transport, 0) AS tunjangan_transport,
                COALESCE(p.upah_mengajar, 0) AS upah_mengajar,

                COALESCE(ins.total_insentif, 0) AS total_insentif,

                (
                    SELECT COALESCE(SUM(am.total_jam), 0)
                    FROM absensi_mengajar am
                    JOIN data_penempatan pen ON am.id_penempatan = pen.id_penempatan
                    WHERE pen.nip = ? AND DATE_FORMAT(am.jam_clockin, '%m%Y') = ?
                ) AS total_jam_mengajar

            FROM data_kehadiran dk
            JOIN data_pegawai dp ON dk.nip = dp.nip
            JOIN data_jabatan j ON dp.jabatan = j.id_jabatan
            LEFT JOIN data_jabatan_periode p 
                ON p.id_jabatan = j.id_jabatan AND p.valid_to IS NULL
            LEFT JOIN (
                SELECT nip, SUM(nominal) AS total_insentif
                FROM data_insentif 
                WHERE is_paid = '0' 
                GROUP BY nip
            ) ins ON ins.nip = dk.nip
            WHERE dk.nip = ? AND dk.bulan = ?
        ";

        $data['gaji'] = $this->db->query($sql, [
            $nip, $bulanTahun,
            $nip, $bulanTahun
        ])->row();

        $this->load->view('templates_pegawai/header', $data);
        $this->load->view('templates_pegawai/sidebar');
        $this->load->view('pegawai/dataGaji', $data);
        $this->load->view('templates_pegawai/footer');
    }

    public function cetakSlip($bulanTahun = null)
    {
        $nip = $this->session->userdata('nip');
        $bulanTahun = $bulanTahun && strlen($bulanTahun) == 6 ? $bulanTahun : date('mY');

        $data['title'] = "Slip Gaji - " . nama_bulan($bulanTahun);

        $sql = "
            SELECT 
                dp.nip, 
                dp.nama_pegawai, 
                dp.jenis_kelamin,
                j.nama_jabatan,

                COALESCE(p.tunjangan_jabatan, 0) AS tunjangan_jabatan,
                COALESCE(p.tunjangan_transport, 0) AS tunjangan_transport,
                COALESCE(p.upah_mengajar, 0) AS upah_mengajar,

                dk.hadir,
                dk.bulan,  -- TAMBAHAN INI YANG PENTING!

                COALESCE(ins.total_insentif, 0) AS total_insentif,

                (
                    SELECT COALESCE(SUM(am.total_jam), 0)
                    FROM absensi_mengajar am
                    JOIN data_penempatan pen ON am.id_penempatan = pen.id_penempatan
                    WHERE pen.nip = ? AND DATE_FORMAT(am.jam_clockin, '%m%Y') = ?
                ) AS total_jam_mengajar

            FROM data_pegawai dp
            JOIN data_kehadiran dk ON dk.nip = dp.nip AND dk.bulan = ?
            JOIN data_jabatan j ON dp.jabatan = j.id_jabatan
            LEFT JOIN data_jabatan_periode p ON p.id_jabatan = j.id_jabatan AND p.valid_to IS NULL
            LEFT JOIN (
                SELECT nip, SUM(nominal) AS total_insentif
                FROM data_insentif WHERE is_paid = '0' GROUP BY nip
            ) ins ON ins.nip = dp.nip
            WHERE dp.nip = ?
        ";

        $data['slip'] = $this->db->query($sql, [
            $nip, $bulanTahun,
            $bulanTahun, $nip
        ])->row();

        if (!$data['slip']) {
            show_error('Data gaji tidak ditemukan untuk bulan ini.', 404);
        }

        $this->load->view('templates_pegawai/header', $data);
        $this->load->view('pegawai/cetakSlipGaji', $data);
    }
}