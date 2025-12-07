<?php

class DataPenempatan extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    if ($this->session->userdata('hak_akses') != '2') {
      $this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Anda belum login!</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
      redirect('welcome');
    }
  }

  public function index()
  {
      $data['title'] = "Jadwal Mengajar";

      $nip = $this->session->userdata('nip');

      // ------------------- FILTER HARI -------------------
      $hari = $this->input->get('hari');

      // Query utama (hanya milik pegawai yang login)
      $this->db->select('
          a.*,
          COALESCE(pp.nama_pelajaran, p.nama_pelajaran) as nama_pelajaran,
          c.nama_kelas,
          d.tahun_akademik,
          d.semester,
          d.nama_akademik
      ');
      $this->db->from('data_penempatan a');
      $this->db->join('data_pelajaran p', 'a.id_pelajaran = p.id_pelajaran');
      $this->db->join('data_pelajaran_periode pp', 
          'pp.id_pelajaran = a.id_pelajaran AND pp.id_akademik = a.id_akademik', 'left');
      $this->db->join('data_kelas c', 'a.id_kelas = c.id_kelas');
      $this->db->join('data_akademik d', 'a.id_akademik = d.id_akademik');
      $this->db->where('a.nip', $nip);

      // Filter hari jika ada
      if (!empty($hari)) {
          $this->db->where('a.hari', $hari);
      }

      // Clone query untuk menghitung total baris (pagination)
      $count_query = clone $this->db;
      $total_rows  = $count_query->count_all_results();

      // ------------------- PAGINATION -------------------
      $this->load->library('pagination');

      $config['base_url']            = base_url('pegawai/dataPenempatan');
      $config['total_rows']          = $total_rows;
      $config['per_page']            = 10;                    // 10 baris per halaman
      $config['page_query_string']   = TRUE;
      $config['query_string_segment']= 'page';
      $config['reuse_query_string']  = TRUE;
      $config['num_links']           = 5;

      // Bootstrap 4 style
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

      $page   = $this->input->get('page') ? $this->input->get('page') : 0;

      // Order by hari & jam mulai
      $this->db->order_by("FIELD(a.hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu')");
      $this->db->order_by('a.jam_mulai', 'ASC');
      $this->db->limit($config['per_page'], $page);

      $data['penempatan'] = $this->db->get()->result();
      $data['pagination'] = $this->pagination->create_links();
      $data['offset']     = $page;   // untuk nomor urut di tabel

      // kirim filter hari agar tetap terpilih
      $data['selected_hari'] = $hari;

      $this->load->view('templates_pegawai/header', $data);
      $this->load->view('templates_pegawai/sidebar');
      $this->load->view('pegawai/dataPenempatan', $data);
      $this->load->view('templates_pegawai/footer');
  }

  public function tambahData()
  {
    $data['title'] = "Tambah Data Penempatan";

    $this->load->view('templates_pegawai/header', $data);
    $this->load->view('templates_pegawai/sidebar');
    $this->load->view('pegawai/tambahDatapenempatan', $data);
    $this->load->view('templates_pegawai/footer');
  }

  public function tambahDataAksi()
  {
    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
      $this->tambahData();
    } else {
      $id_pelajaran = $this->input->post('id_pelajaran');
      $id_kelas = $this->input->post('id_kelas');
      $tunjangan_penempatan = $this->input->post('tunjangan_penempatan');
      $id_akademik = $this->input->post('id_akademik');
      $nip = $this->input->post('nip');
      $jam_mulai = $this->input->post('jam_mulai');
      $jam_akhir = $this->input->post('jam_akhir');
      $total_jam = $this->input->post('total_jam');
      $keterangan = $this->input->post('keterangan');

      $data = array(
        'id_pelajaran' => $id_pelajaran,
        'id_kelas' => $id_kelas,
        'tunjangan_penempatan' => $tunjangan_penempatan,
        'id_akademik' => $id_akademik,
        'nip' => $nip,
        'jam_mulai' => $jam_mulai,
        'jam_akhir' => $jam_akhir,
        'total_jam' => $total_jam,
        'keterangan' => $keterangan,
      );

      $this->penggajianModel->insert_data($data, 'data_penempatan');
      $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Data berhasil ditambahkan</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
      redirect('pegawai/datapenempatan');
    }
  }

  public function updateData($id)
  {
    // $where = array('id_penempatan' => $id);
    $data['penempatan'] = $this->db->query("SELECT * FROM data_penempatan WHERE id_penempatan = '$id'")->result();
    $data['title'] = "Update Data penempatan";

    $this->load->view('templates_pegawai/header', $data);
    $this->load->view('templates_pegawai/sidebar');
    $this->load->view('pegawai/updateDatapenempatan', $data);
    $this->load->view('templates_pegawai/footer');
  }

  public function updateDataAksi()
  {
    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
      $id = $this->insert->post('id_penempatan');
      $this->updateData($id);
    } else {
      $id           = $this->input->post('id_penempatan');
      $id_pelajaran = $this->input->post('id_pelajaran');
      $id_kelas = $this->input->post('id_kelas');
      $tunjangan_penempatan = $this->input->post('tunjangan_penempatan');
      $id_akademik = $this->input->post('id_akademik');
      $nip = $this->input->post('nip');
      $jam_mulai = $this->input->post('jam_mulai');
      $jam_akhir = $this->input->post('jam_akhir');
      $total_jam = $this->input->post('total_jam');
      $keterangan = $this->input->post('keterangan');

      $data = array(
        'id_pelajaran' => $id_pelajaran,
        'id_kelas' => $id_kelas,
        'tunjangan_penempatan' => $tunjangan_penempatan,
        'id_akademik' => $id_akademik,
        'nip' => $nip,
        'jam_mulai' => $jam_mulai,
        'jam_akhir' => $jam_akhir,
        'total_jam' => $total_jam,
        'keterangan' => $keterangan,
      );

      $where = array(
        'id_penempatan' => $id
      );

      $this->penggajianModel->update_data('data_penempatan', $data, $where);
      $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Data berhasil diupdate</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
      redirect('pegawai/datapenempatan');
    }
  }

  public function deleteData($id)
  {
    $where = array('id_penempatan' => $id);
    $this->penggajianModel->delete_data($where, 'data_penempatan');
    $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Data berhasil dihapus</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>');
    redirect('pegawai/datapenempatan');
  }
  public function printData()
  {
    $data['title'] = "Cetak Data Penempatan";
    $nip = $this->session->userdata('nip');
    $data['penempatan'] = $this->db->query("SELECT a.*, b.nama_pelajaran, c.nama_kelas, d.tahun_akademik FROM data_penempatan a
                    JOIN data_pelajaran b ON a.id_pelajaran = b.id_pelajaran
                    JOIN data_kelas c ON a.id_kelas = c.id_kelas
                    JOIN data_akademik d ON a.id_akademik = d.id_akademik WHERE nip = '$nip'")->result();
    $this->load->view('templates_pegawai/header', $data);
    $this->load->view('pegawai/cetakdatapenempatan');
  }

  public function _rules()
  {
    $this->form_validation->set_rules('id_pelajaran', 'ID Pelajaran', 'required');
    $this->form_validation->set_rules('id_kelas', 'ID Kelas', 'required');
    $this->form_validation->set_rules('tunjangan_penempatan', 'Tunjangan Penempatan', 'required');
    $this->form_validation->set_rules('id_akademik', 'ID Akademic', 'required');
    $this->form_validation->set_rules('nip', 'NIP', 'required');
    $this->form_validation->set_rules('jam_mulai', 'Jam Mulai', 'required');
    $this->form_validation->set_rules('jam_akhir', 'Jam Akhir', 'required');
    $this->form_validation->set_rules('total_jam', 'Total Jam', 'required');
    $this->form_validation->set_rules('keterangan', 'Keterangan', 'required');
  }
}
