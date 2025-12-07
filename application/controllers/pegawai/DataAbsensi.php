<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DataAbsensi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Hanya guru (hak_akses = 2)
        if ($this->session->userdata('hak_akses') != '2') {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Anda belum login!</strong>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>');
            redirect('welcome');
        }

        // Load helper nama pelajaran (wajib!)
        // $this->load->helper('nama_pelajaran');
    }

    // ==================================================================
    // HALAMAN UTAMA ABSENSI (QR Code Scanner)
    // ==================================================================
    public function index()
    {
        $data['title'] = "Absensi Mengajar";
        $data['pegawai'] = $this->db->get_where('data_pegawai', ['nip' => $this->session->userdata('nip')])->row();
        $data['is_testing_radius'] = $this->session->userdata('testing_radius');

        $this->load->view('templates_pegawai/header', $data);
        $this->load->view('templates_pegawai/sidebar');
        $this->load->view('pegawai/formAbsensi', $data);
        $this->load->view('templates_pegawai/footer');
    }

    // Toggle mode testing radius (untukkan development)
    public function set_radius_mode($mode = 'normal')
    {
        if ($mode === 'unlimited') {
            $this->session->set_userdata('testing_radius', true);
            $this->session->set_flashdata('pesan', '<div class="alert alert-warning"><strong>Mode Testing Aktif!</strong> Absen dari mana saja.</div>');
        } else {
            $this->session->unset_userdata('testing_radius');
            $this->session->set_flashdata('pesan', '<div class="alert alert-info">Mode radius normal aktif.</div>');
        }
        redirect('pegawai/dataAbsensi');
    }

    // ==================================================================
    // ABSENSI KEHADIRAN HARIAN (QR IN/OUT umum – bukan per jadwal)
    // ==================================================================
    public function do_attend()     { $this->proses_kehadiran('IN'); }
    public function do_attend_out() { $this->proses_kehadiran('OUT'); }

    private function proses_kehadiran($tipe)
    {
        $this->load->helper('qr');
        $nip = $this->session->userdata('nip');
        $lat = $this->input->post('lat');
        $lon = $this->input->post('lon');
        $qr  = $this->input->post('qr_data');

        // Validasi QR harian
        $now = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
        $today = $now->format('Ymd');
        $valid_code = generate_daily_unique_code($now);

        $parts = explode('-', $qr);
        if (count($parts) !== 3 || $parts[0] !== $tipe || $parts[1] !== $today || $parts[2] !== $valid_code) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">QR Code tidak valid atau sudah kadaluarsa!</div>');
            redirect('pegawai/dataAbsensi');
        }

        // Cek radius
        if (!$this->cek_radius($lat, $lon)) {
            redirect('pegawai/dataAbsensi');
        }

        $bulan = $now->format('mY');

        if ($tipe === 'IN') {
            $this->clock_in_harian($nip, $bulan, $lat, $lon);
        } else {
            $this->clock_out_harian($nip, $lat, $lon);
        }
    }

    private function clock_in_harian($nip, $bulan, $lat, $lon)
    {
        $cek = $this->db->get_where('data_kehadiran', ['nip' => $nip, 'bulan' => $bulan])->row();

        if ($cek) {
            $this->db->set('hadir', 'hadir + 1', FALSE)->where('id_kehadiran', $cek->id_kehadiran)->update('data_kehadiran');
        } else {
            $peg = $this->db->get_where('data_pegawai', ['nip' => $nip])->row();
            $this->db->insert('data_kehadiran', [
                'bulan' => $bulan, 'nip' => $nip, 'nama_pegawai' => $peg->nama_pegawai,
                'jenis_kelamin' => $peg->jenis_kelamin, 'nama_jabatan' => $peg->jabatan,
                'hadir' => 1, 'izin' => 0
            ]);
        }

        $this->db->insert('detail_kehadiran', [
            'nip' => $nip, 'bulan' => $bulan, 'jam_clockin' => date('Y-m-d H:i:s'),
            'lokasi_clockin' => "$lat,$lon"
        ]);

        $this->session->set_flashdata('pesan', '<div class="alert alert-success">Clock-in harian berhasil!</div>');
        redirect('pegawai/dataAbsensi');
    }

    private function clock_out_harian($nip, $lat, $lon)
    {
        $today = date('Y-m-d');
        $absen = $this->db->get_where('detail_kehadiran', [
            'nip' => $nip, 'DATE(jam_clockin)' => $today
        ])->row();

        if (!$absen || $absen->jam_clockout != null) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-warning">Anda belum clock-in hari ini!</div>');
            redirect('pegawai/dataAbsensi');
        }

        $in = new DateTime($absen->jam_clockin);
        $out = new DateTime();
        $jam = $in->diff($out)->h + ($in->diff($out)->i / 60);

        $this->db->where('id', $absen->id)->update('detail_kehadiran', [
            'jam_clockout' => $out->format('Y-m-d H:i:s'),
            'lokasi_clockout' => "$lat,$lon",
            'total_jam' => round($jam, 2)
        ]);

        $this->session->set_flashdata('pesan', '<div class="alert alert-success">Clock-out berhasil! Total: '.round($jam,2).' jam</div>');
        redirect('pegawai/dataAbsensi');
    }

    // ==================================================================
    // ABSENSI MENGAJAR PER JADWAL (Clock In/Out per id_penempatan)
    // ==================================================================
    public function clockIn($id_penempatan)
    {
        $this->proses_absen_mengajar($id_penempatan, 'IN');
    }

    public function clockOut($id_penempatan)
    {
        $this->proses_absen_mengajar($id_penempatan, 'OUT');
    }

    private function proses_absen_mengajar($id_penempatan, $tipe)
    {
        $lat = $this->input->post('lat');
        $lon = $this->input->post('lon');

        if (!$this->cek_radius($lat, $lon)) {
            redirect('pegawai/dataPenempatan');
        }

        if ($tipe === 'IN') {
            $this->db->insert('absensi_mengajar', [
                'id_penempatan'   => $id_penempatan,
                'jam_clockin'     => date('Y-m-d H:i:s'),
                'lokasi_clockin'  => "$lat,$lon",
                'total_jam'       => 0
            ]);
            $msg = "Clock-in mengajar berhasil!";
        } else {
            $absen = $this->db->get_where('absensi_mengajar', [
                'id_penempatan' => $id_penempatan,
                'DATE(jam_clockin)' => date('Y-m-d'),
                'jam_clockout' => null
            ])->row();

            if (!$absen) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-warning">Belum clock-in hari ini!</div>');
                redirect('pegawai/dataPenempatan');
            }

            $in = new DateTime($absen->jam_clockin);
            $out = new DateTime();
            $jam = $in->diff($out)->h + ($in->diff($out)->i / 60);

            $this->db->where('id', $absen->id)->update('absensi_mengajar', [
                'jam_clockout'    => $out->format('Y-m-d H:i:s'),
                'lokasi_clockout' => "$lat,$lon",
                'total_jam'       => round($jam, 2)
            ]);
            $msg = "Clock-out mengajar berhasil! Total: ".round($jam,2)." jam";
        }

        $this->session->set_flashdata('pesan', '<div class="alert alert-success">'.$msg.'</div>');
        redirect('pegawai/dataPenempatan');
    }

    // ==================================================================
    // DETAIL ABSENSI MENGAJAR (dengan nama pelajaran sesuai semester!)
    // ==================================================================
    public function detailAbsensi($id_penempatan)
    {
        $data['title'] = "Detail Absensi Mengajar";

        // Ambil jadwal + nama pelajaran yang benar
        $this->db->select('
            dp.*, dk.nama_kelas, da.nama_akademik, da.semester,
            COALESCE(dpp.nama_pelajaran, pl.nama_pelajaran) as nama_pelajaran
        ');
        $this->db->from('data_penempatan dp');
        $this->db->join('data_kelas dk', 'dp.id_kelas = dk.id_kelas');
        $this->db->join('data_akademik da', 'dp.id_akademik = da.id_akademik');
        $this->db->join('data_pelajaran pl', 'dp.id_pelajaran = pl.id_pelajaran');
        $this->db->join('data_pelajaran_periode dpp', 'dpp.id_pelajaran = dp.id_pelajaran AND dpp.id_akademik = dp.id_akademik', 'left');
        $this->db->where('dp.id_penempatan', $id_penempatan);
        $data['jadwal'] = $this->db->get()->row();

        if (!$data['jadwal']) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Jadwal tidak ditemukan!</div>');
            redirect('pegawai/dataPenempatan');
        }

        $data['absensi'] = $this->db->where('id_penempatan', $id_penempatan)
                                    ->order_by('jam_clockin', 'DESC')
                                    ->get('absensi_mengajar')
                                    ->result();

        $this->load->view('templates_pegawai/header', $data);
        $this->load->view('templates_pegawai/sidebar');
        $this->load->view('pegawai/detailAbsensi', $data);
        $this->load->view('templates_pegawai/footer');
    }

    // ==================================================================
    // REKAP KEHADIRAN HARIAN
    // ==================================================================
    public function detailKehadiran()
    {
        $data['title'] = "Rekap Kehadiran Harian";

        $nip = $this->session->userdata('nip');

        // Ambil filter dari GET
        $bulan = $this->input->get('bulan');   // format: 01-12
        $tahun = $this->input->get('tahun');   // format: 2025

        // Query utama
        $this->db->select('*');
        $this->db->from('detail_kehadiran');
        $this->db->where('nip', $nip);

        // Filter bulan & tahun jika ada
        if (!empty($bulan) && !empty($tahun)) {
            $this->db->where('MONTH(jam_clockin)', $bulan);
            $this->db->where('YEAR(jam_clockin)', $tahun);
        } elseif (!empty($bulan)) {
            $this->db->where('MONTH(jam_clockin)', $bulan);
        } elseif (!empty($tahun)) {
            $this->db->where('YEAR(jam_clockin)', $tahun);
        }

        // Clone untuk hitung total baris (pagination)
        $count_query = clone $this->db;
        $total_rows  = $count_query->count_all_results();

        // ------------------- PAGINATION -------------------
        $this->load->library('pagination');

        $config['base_url']            = base_url('pegawai/dataAbsensi/detailKehadiran');
        $config['total_rows']          = $total_rows;
        $config['per_page']            = 10;
        $config['page_query_string']   = TRUE;
        $config['query_string_segment']= 'page';
        $config['reuse_query_string']  = TRUE;   // penting agar filter bulan/tahun tetap ada saat pindah halaman
        $config['num_links']           = 5;

        // Bootstrap 4 style
        $config['full_tag_open']  = '<nav><ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['attributes']     = ['class' => 'page-link'];
        $config['first_link']     = 'First';
        $config['last_link']      = 'Last';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close']= '</li>';
        $config['prev_link']      = 'Previous';
        $config['prev_tag_open']  = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link']      = 'Next';
        $config['next_tag_open']  = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open']  = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open']   = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close']  = '<span class="sr-only">(current)</span></span></li>';
        $config['num_tag_open']   = '<li class="page-item">';
        $config['num_tag_close']  = '</li>';

        $this->pagination->initialize($config);

        $page = $this->input->get('page') ? $this->input->get('page') : 0;

        // Query dengan limit + order
        $this->db->order_by('jam_clockin', 'DESC');
        $this->db->limit($config['per_page'], $page);

        $data['kehadiran']   = $this->db->get()->result();
        $data['pagination']  = $this->pagination->create_links();
        $data['offset']      = $page;

        // Kirim data filter ke view agar tetap terpilih
        $data['selected_bulan'] = $bulan;
        $data['selected_tahun'] = $tahun;

        // Daftar tahun (otomatis dari data atau 5 tahun terakhir)
        $this->db->select('YEAR(jam_clockin) as tahun');
        $this->db->from('detail_kehadiran');
        $this->db->where('nip', $nip);
        $this->db->group_by('YEAR(jam_clockin)');
        $this->db->order_by('tahun', 'DESC');
        $data['daftar_tahun'] = $this->db->get()->result_array();

        $this->load->view('templates_pegawai/header', $data);
        $this->load->view('templates_pegawai/sidebar');
        $this->load->view('pegawai/detailKehadiran', $data);
        $this->load->view('templates_pegawai/footer');
    }
    // ==================================================================
    // FUNGSI BANTUAN
    // ==================================================================
    private function cek_radius($lat, $lon)
    {
        $lat_school = -8.0067597;
        $lon_school = 112.6208716;
        $radius = $this->session->userdata('testing_radius') ? 10000000 : 100;

        $meters = $this->distance_meter($lat, $lon, $lat_school, $lon_school);
        if ($meters > $radius) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">
                Anda di luar area sekolah! Jarak: '.round($meters).' meter
            </div>');
            return false;
        }
        return true;
    }

    private function distance_meter($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +
                cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        return $miles * 1609.344;
    }
}