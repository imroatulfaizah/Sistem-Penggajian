<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DataPenggajian extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();

        if ($this->session->userdata('hak_akses') != '1') {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Anda belum login!</div>');
            redirect('welcome');
        }
    }

    private function getBulanTahunFromRequest()
    {
        // Prioritas 1: dari GET
        if (!empty($_GET['bulan']) && !empty($_GET['tahun'])) {
            $b = $_GET['bulan'];
            $t = $_GET['tahun'];
        }
        // Prioritas 2: dari URI segment (untuk printSlip)
        elseif ($this->uri->segment(5) && $this->uri->segment(6)) {
            $b = $this->uri->segment(5);
            $t = $this->uri->segment(6);
        }
        // Fallback
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
        $this->db->join('data_pegawai b', 'a.nip=b.nip');
        $this->db->where('a.bulan', $bulanTahun);

        $count_query = clone $this->db;
        $total_rows = $count_query->count_all_results();

        // Pagination
        $this->load->library('pagination');

        $config['base_url'] = base_url('admin/dataPenggajian');
        $config['total_rows'] = $total_rows;
        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['query_string_segment'] = 'page';
        $config['reuse_query_string'] = TRUE;

        // Style bootstrap
        $config['full_tag_open']   = '<div class="pagination justify-content-center"><ul class="pagination">';
        $config['full_tag_close']  = '</ul></div>';
        $config['num_tag_open']    = '<li class="page-item"><span class="page-link">';
        $config['num_tag_close']   = '</span></li>';
        $config['cur_tag_open']    = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close']   = '</span></li>';
        $config['next_tag_open']   = '<li class="page-item"><span class="page-link">';
        $config['next_tag_close']  = '</span></li>';
        $config['prev_tag_open']   = '<li class="page-item"><span class="page-link">';
        $config['prev_tag_close']  = '</span></li>';

        $this->pagination->initialize($config);
        $offset = ($this->input->get('page')) ? $this->input->get('page') : 0;

        // Query utama
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
                    SELECT COALESCE(SUM(am.total_jam),0)
                    FROM absensi_mengajar am
                    JOIN data_penempatan dp2 ON am.id_penempatan = dp2.id_penempatan
                    WHERE dp2.nip = a.nip
                    AND DATE_FORMAT(am.jam_clockin, '%m%Y') = ?
                ) AS total_jam

            FROM data_kehadiran a
            JOIN data_pegawai b ON a.nip = b.nip
            JOIN data_jabatan j ON b.jabatan = j.id_jabatan

            LEFT JOIN data_jabatan_periode p 
                ON p.id_jabatan = j.id_jabatan
                AND p.valid_to IS NULL

            WHERE a.bulan = ?
            ORDER BY b.nama_pegawai ASC
            LIMIT ? OFFSET ?
        ";

        $data['gaji'] = $this->db->query($sql, [
            $bulanTahun, $bulanTahun,
            $config['per_page'], $offset
        ])->result();

        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        $data['total_rows'] = $total_rows;

        // View
        $this->load->view('templates_admin/header', $data);
        $this->load->view('templates_admin/sidebar');
        $this->load->view('admin/DataPenggajian/dataGaji', $data);
        $this->load->view('templates_admin/footer');
    }

    // ============================================================
    // PRINT SLIP GAJI
    // ============================================================
    public function printSlip($nip, $bulan, $tahun)
    {

        $data['title'] = "Cetak Slip Gaji";
        list($bulan, $tahun, $bulanTahun) = $this->getBulanTahunFromRequest();

        // Insentif
        $data['insentif'] = $this->db->query("
            SELECT SUM(nominal) AS jumlah_insentif 
            FROM data_insentif 
            WHERE nip = '$nip'
        ")->result();

        // Kehadiran
        $data['kehadiran'] = $this->db->query("
            SELECT hadir 
            FROM data_kehadiran 
            WHERE nip = '$nip'
        ")->result();

        // Jam mengajar
        $data['jam'] = $this->db->query("
            SELECT SUM(total_jam) AS total_jam 
            FROM data_penempatan 
            WHERE nip = '$nip'
        ")->result();

        // Data slip gaji + tunjangan dari table periode
        $data['print_slip'] = $this->db->query("
            SELECT 
                data_pegawai.nip, 
                data_pegawai.nama_pegawai, 
                data_jabatan.nama_jabatan,
                djp.tunjangan_jabatan,
                djp.tunjangan_transport,
                djp.upah_mengajar,
                data_kehadiran.izin,
                data_kehadiran.bulan
            FROM data_pegawai
            INNER JOIN data_kehadiran 
                ON data_kehadiran.nip = data_pegawai.nip AND data_kehadiran.bulan = '$bulanTahun'
            INNER JOIN data_jabatan 
                ON data_jabatan.id_jabatan = data_pegawai.jabatan
            INNER JOIN data_jabatan_periode djp
                ON djp.id_jabatan = data_jabatan.id_jabatan
                AND djp.valid_to IS NULL
            WHERE data_pegawai.nip = '$nip'
        ")->result();

        $this->load->view('templates_pegawai/header', $data);
        $this->load->view('pegawai/cetakSlipGaji', $data);
    }

    // ============================================================
    // CETAK SEMUA DATA GAJI
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
                ON p.id_jabatan = j.id_jabatan
                AND p.valid_to IS NULL

            WHERE dk.bulan = ?
            ORDER BY dp.nama_pegawai ASC
        ";

        $data['cetakGaji'] = $this->db->query($sql, [
            $bulanTahun, $bulanTahun
        ])->result();

        $this->load->view('templates_admin/header', $data);
        $this->load->view('admin/DataPenggajian/cetakDataGaji', $data);
    }
}
