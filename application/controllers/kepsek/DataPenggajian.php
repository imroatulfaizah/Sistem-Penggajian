<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DataPenggajian extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper('nama_bulan'); // <-- load helper di sini (atau autoload di config)

        if ($this->session->userdata('hak_akses') != '3') {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Anda belum login!</div>');
            redirect('welcome');
        }
    }

    private function getBulanTahunFromRequest()
    {
        if (!empty($_GET['bulan']) && !empty($_GET['tahun'])) {
            $b = $_GET['bulan'];
            $t = $_GET['tahun'];
        }
        elseif ($this->uri->segment(5) && $this->uri->segment(6)) {
            $b = $this->uri->segment(5);
            $t = $this->uri->segment(6);
        }
        else {
            $b = date('m');
            $t = date('Y');
        }

        return [$b, $t, $b . $t];
    }

    // ============================================================
    // INDEX — LISTING GAJI + PAGINATION
    // ============================================================
    public function index()
    {
        $data['title'] = "Data Gaji Pegawai";

        list($bulan, $tahun, $bulanTahun) = $this->getBulanTahunFromRequest();

        // Hitung total rows
        $this->db->from('data_kehadiran a');
        $this->db->join('data_pegawai b', 'a.nip = b.nip');
        $this->db->where('a.bulan', $bulanTahun);
        $total_rows = $this->db->count_all_results();

        // Pagination
        $this->load->library('pagination');
        $config['base_url']            = base_url('kepsek/dataPenggajian');
        $config['total_rows']          = $total_rows;
        $config['per_page']            = 10;
        $config['page_query_string']   = TRUE;
        $config['query_string_segment']= 'page';
        $config['reuse_query_string']  = TRUE;

        // Bootstrap 4 style
        $config['full_tag_open']  = '<div class="pagination justify-content-center"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></div>';
        $config['num_tag_open']   = '<li class="page-item"><span class="page-link">';
        $config['num_tag_close']  = '</span></li>';
        $config['cur_tag_open']   = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close']  = '</span></li>';
        $config['next_tag_open']  = '<li class="page-item"><span class="page-link">';
        $config['next_tag_close'] = '</span></li>';
        $config['prev_tag_open']  = '<li class="page-item"><span class="page-link">';
        $config['prev_tag_close'] = '</span></li>';
        $config['first_tag_open'] = '<li class="page-item"><span class="page-link">';
        $config['first_tag_close']= '</span></li>';
        $config['last_tag_open']  = '<li class="page-item"><span class="page-link">';
        $config['last_tag_close'] = '</span></li>';

        $this->pagination->initialize($config);

        $page   = $this->input->get('page');
        $offset = is_numeric($page) ? (int)$page : 0;

        $sql = "
            SELECT 
                a.*,
                b.nama_pegawai,
                b.jenis_kelamin,
                j.nama_jabatan,
                COALESCE(p.tunjangan_jabatan, 0) AS tunjangan_jabatan,
                COALESCE(p.tunjangan_transport, 0) AS tunjangan_transport,
                COALESCE(p.upah_mengajar, 0) AS upah_mengajar,
                (
                    SELECT COALESCE(SUM(am.total_jam), 0)
                    FROM absensi_mengajar am
                    JOIN data_penempatan dp2 ON am.id_penempatan = dp2.id_penempatan
                    WHERE dp2.nip = a.nip
                      AND DATE_FORMAT(am.jam_clockin, '%m%Y') = ?
                ) AS total_jam,
                (
                    SELECT COALESCE(SUM(di.nominal), 0)
                    FROM data_insentif di
                    WHERE di.nip = a.nip AND di.is_paid = '0'
                ) AS total_insentif
            FROM data_kehadiran a
            JOIN data_pegawai b ON a.nip = b.nip
            JOIN data_jabatan j ON b.jabatan = j.id_jabatan
            LEFT JOIN data_jabatan_periode p 
                ON p.id_jabatan = j.id_jabatan AND p.valid_to IS NULL
            WHERE a.bulan = ?
            ORDER BY b.nama_pegawai ASC
            LIMIT ? OFFSET ?
        ";

        $data['gaji'] = $this->db->query($sql, [
            $bulanTahun,
            $bulanTahun,
            $config['per_page'],
            $offset
        ])->result();

        $data['bulan']      = $bulan;
        $data['tahun']      = $tahun;
        $data['total_rows'] = $total_rows;

        $this->load->view('templates_kepsek/header', $data);
        $this->load->view('templates_kepsek/sidebar');
        $this->load->view('kepsek/DataPenggajian/dataGaji', $data);
        $this->load->view('templates_kepsek/footer');
    }

    // ============================================================
    // PRINT SLIP GAJI (kepsek)
    // ============================================================
    public function printSlip($nip, $bulan = null, $tahun = null)
    {
        $bulan = str_pad($bulan ?? date('m'), 2, '0', STR_PAD_LEFT);
        $tahun = $tahun ?? date('Y');
        $bulanTahun = $bulan . $tahun;

        // Gunakan helper nama_bulan()
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
            JOIN data_jabatan j ON dp.jabatan = j.id_jabatan
            LEFT JOIN data_jabatan_periode p 
                ON p.id_jabatan = j.id_jabatan AND p.valid_to IS NULL
            LEFT JOIN (
                SELECT nip, SUM(nominal) AS total_insentif
                FROM data_insentif 
                WHERE is_paid = '0' 
                GROUP BY nip
            ) ins ON ins.nip = dp.nip
            WHERE dp.nip = ?
        ";

        $data['slip'] = $this->db->query($sql, [
            $nip, $bulanTahun,
            $bulanTahun, $nip
        ])->row();

        if (!$data['slip']) {
            show_error('Data slip gaji tidak ditemukan untuk NIP ini pada bulan tersebut.', 404);
        }

        // Gunakan view slip yang sama dengan pegawai (desain modern)
        $this->load->view('templates_kepsek/header', $data);
        $this->load->view('pegawai/cetakSlipGaji', $data);
    }

    // ============================================================
    // CETAK SEMUA DATA GAJI (PDF/Print)
    // ============================================================
    public function cetakGaji()
    {
        $data['title'] = "Cetak Data Gaji Pegawai";
        list($bulan, $tahun, $bulanTahun) = $this->getBulanTahunFromRequest();

        $sql = "
            SELECT 
                dp.nip,
                dp.nama_pegawai,
                dp.jenis_kelamin,
                j.nama_jabatan,
                COALESCE(p.tunjangan_jabatan,0) AS tunjangan_jabatan,
                COALESCE(p.tunjangan_transport,0) AS tunjangan_transport,
                COALESCE(p.upah_mengajar,0) AS upah_mengajar,
                dk.hadir,
                (
                    SELECT COALESCE(SUM(am.total_jam),0)
                    FROM absensi_mengajar am
                    JOIN data_penempatan pen ON am.id_penempatan = pen.id_penempatan
                    WHERE pen.nip = dp.nip
                      AND DATE_FORMAT(am.jam_clockin, '%m%Y') = ?
                ) AS total_jam
            FROM data_pegawai dp
            JOIN data_kehadiran dk ON dk.nip = dp.nip 
            JOIN data_jabatan j ON j.id_jabatan = dp.jabatan
            LEFT JOIN data_jabatan_periode p
                ON p.id_jabatan = j.id_jabatan AND p.valid_to IS NULL
            WHERE dk.bulan = ?
            ORDER BY dp.nama_pegawai ASC
        ";

        $data['cetakGaji'] = $this->db->query($sql, [$bulanTahun, $bulanTahun])->result();
        $data['periode']   = nama_bulan($bulanTahun); // untuk ditampilkan di view

        $this->load->view('templates_kepsek/header', $data);
        $this->load->view('kepsek/DataPenggajian/cetakDataGaji', $data);
    }
}