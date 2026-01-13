<?php

class DataAbsensi extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('hak_akses') != '4') {
      $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Anda belum login!</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
      redirect('welcome');
    }
  }

      public function index() {
        $data['title'] = "Data Absensi Pegawai";

        // Filter bulan & tahun
        $bulan = $this->input->get('bulan') ?: date('m');
        $tahun = $this->input->get('tahun') ?: date('Y');
        $bulanTahun = $bulan . $tahun;

        $data['bulan_selected'] = $bulan;
        $data['tahun_selected'] = $tahun;

        // === Query utama (untuk count & data) ===
        $this->db->select('dk.*, dp.nama_pegawai, dp.jenis_kelamin, dj.nama_jabatan AS jabatan');
        $this->db->from('data_kehadiran dk');
        $this->db->join('data_pegawai dp', 'dk.nip = dp.nip');
        $this->db->join('data_jabatan dj', 'dp.jabatan = dj.id_jabatan');
        $this->db->where('dk.bulan', $bulanTahun);

        // Clone query untuk hitung total baris (pagination)
        $count_query = clone $this->db;
        $total_rows  = $count_query->count_all_results();

        // === Pagination – persis seperti DataPenempatan ===
        $this->load->library('pagination');

        $config['base_url']             = base_url('kepsek/dataAbsensi');
        $config['total_rows']           = $total_rows;
        $config['per_page']             = 15;
        $config['page_query_string']    = TRUE;
        $config['query_string_segment'] = 'page';
        $config['reuse_query_string']   = TRUE;   // penting: biar bulan & tahun tetap

        // Style persis seperti contoh DataPenempatan (Bootstrap 4)
        $config['full_tag_open']    = '<nav><ul class="pagination justify-content-center">';
        $config['full_tag_close']   = '</ul></nav>';
        $config['attributes']       = ['class' => 'page-link'];
        $config['first_link']       = 'First';
        $config['last_link']        = 'Last';
        $config['first_tag_open']   = '<li class="page-item">';
        $config['first_tag_close']  = '</li>';
        $config['prev_link']        = 'Previous';
        $config['prev_tag_open']    = '<li class="page-item">';
        $config['prev_tag_close']   = '</li>';
        $config['next_link']        = 'Next';
        $config['next_tag_open']    = '<li class="page-item">';
        $config['next_tag_close']   = '</li>';
        $config['last_tag_open']    = '<li class="page-item">';
        $config['last_tag_close']   = '</li>';
        $config['cur_tag_open']     = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close']    = '<span class="sr-only">(current)</span></span></li>';
        $config['num_tag_open']     = '<li class="page-item">';
        $config['num_tag_close']    = '</li>';

        $this->pagination->initialize($config);

        // Ambil data dengan limit
        $page = $this->input->get('page') ? $this->input->get('page') : 0;
        $this->db->order_by('dp.nama_pegawai', 'ASC');
        $this->db->limit($config['per_page'], $page);

        $data['absensi']    = $this->db->get()->result();
        $data['pagination'] = $this->pagination->create_links();

        // View kepsek
        $this->load->view('templates_bendahara/header', $data);
        $this->load->view('templates_bendahara/sidebar');
        $this->load->view('bendahara/RekapAbsen/dataAbsensi', $data);
        $this->load->view('templates_bendahara/footer');
    }

  public function inputAbsensi()
  {
    if ($this->input->post('submit', TRUE) == 'submit') {
      $post = $this->input->post();

      foreach ($post['bulan'] as $key => $value) {
        if ($post['bulan'][$key] != '' || $post['nik'][$key] != '') {
          $simpan[] = array(
            'bulan'         => $post['bulan'][$key],
            'nip'           => $post['nip'][$key],
            'nama_pegawai'  => $post['nama_pegawai'][$key],
            'jenis_kelamin' => $post['jenis_kelamin'][$key],
            'nama_jabatan'  => $post['nama_jabatan'][$key],
            'hadir'         => $post['hadir'][$key],
            'sakit'         => $post['sakit'][$key],
            'izin'         => $post['izin'][$key],
            'alpha'         => $post['alpha'][$key],
          );
        }
      }
      $this->penggajianModel->insert_batch('data_kehadiran', $simpan);
      $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Data berhasil ditambahkan</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
      redirect('bendahara/dataAbsensi');
    }

    $data['title'] = "Form Input Absensi";

    if ((isset($_GET['bulan']) && $_GET['bulan'] != '') && (isset($_GET['tahun']) && $_GET['tahun'] != '')) {
      $bulan = $_GET['bulan'];
      $tahun = $_GET['tahun'];
      $bulanTahun = $bulan . $tahun;
    } else {
      $bulan = date('m');
      $tahun = date('Y');
      $bulanTahun = $bulan . $tahun;
    }

    $data['inputAbsensi'] = $this->db->query("SELECT data_pegawai.*, data_jabatan.nama_jabatan FROM data_pegawai INNER JOIN data_jabatan ON data_pegawai.jabatan = data_jabatan.nama_jabatan WHERE NOT EXISTS(SELECT * FROM data_kehadiran WHERE bulan = '$bulanTahun' AND data_pegawai.nip = data_kehadiran.nip) ORDER BY data_pegawai.nama_pegawai ASC")->result();
    $this->load->view('templates_bendahara/header', $data);
    $this->load->view('templates_bendahara/sidebar');
    $this->load->view('bendahara/RekapAbsen/formInputAbsensi', $data);
    $this->load->view('templates_bendahara/footer');
  }
  public function updateData($id)
  {
    // $where = array('id_jabatan' => $id);
    $data['title'] = "Update Data Absensi";
    $data['absensi'] = $this->db->query("SELECT * FROM data_kehadiran WHERE id_kehadiran = '$id'")->result();
    // $data['absensi'] = $this->penggajianModel->get_data('data_kehadiran')->result();
    $this->load->view('templates_bendahara/header', $data);
    $this->load->view('templates_bendahara/sidebar');
    $this->load->view('bendahara/RekapAbsen/updateDataAbsensi', $data);
    $this->load->view('templates_bendahara/footer');
  }

  public function updateDataAksi()
  {
    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
      $id = $this->insert_batch->post('id_kehadiran');
      $this->updateData($id);
    } else {
      $id           = $this->input->post('id_pegawai');
      $nama_pegawai = $this->input->post('nama_pegawai');
      $jenis_kelamin   = $this->input->post('jenis_kelamin');
      $nama_jabatan = $this->input->post('nama_jabatan');
      $hadir   = $this->input->post('hadir');
      $izin   = $this->input->post('izin');


      $data = array(
        'nama_pegawai' => $nama_pegawai,
        'jenis_kelamin'   => $jenis_kelamin,
        'nama_jabatan' => $nama_jabatan,
        'hadir' => $hadir,
        'sakit'   => $sakit,
        'alpha' => $alpha,
        'izin'  => $izin,
      );

      $where = array(
        'id_kehadiran' => $id
      );

      $this->penggajianModel->update_data('data_kehadiran', $data, $where);
      $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Data berhasil diupdate</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
      redirect('bendahara/dataAbsensi');
    }
  }
  public function deleteData($id)
  {
    $where = array('id_kehadiran' => $id);
    $this->penggajianModel->delete_data($where, 'data_kehadiran');
    $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Data berhasil dihapus</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
    redirect('bendahara/dataAbsensi');
  }
  public function _rules()
  {
    $this->form_validation->set_rules('nama_pegawai', 'Nama Pegawai', 'required');
    $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required');
    $this->form_validation->set_rules('jabatan', 'Jabatan', 'required');
    $this->form_validation->set_rules('hadir', 'Hadir', 'required');
    $this->form_validation->set_rules('sakit', 'Sakit', 'required');
    $this->form_validation->set_rules('alpha', 'Alpha', 'required');
  }
}
