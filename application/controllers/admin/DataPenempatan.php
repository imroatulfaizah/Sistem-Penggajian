<?php

class Datapenempatan extends CI_Controller
{
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

public function index()
{
    $data['title'] = "Data Penempatan";
    $hari = $this->input->get('hari');

    // ================================
    // HITUNG TOTAL DATA
    // ================================
    $this->db->from('data_penempatan a');
    $this->db->join('data_pelajaran b','a.id_pelajaran=b.id_pelajaran');
    $this->db->join('data_kelas c','a.id_kelas=c.id_kelas');
    $this->db->join('data_akademik d','a.id_akademik=d.id_akademik');
    $this->db->join('data_pegawai e','a.nip=e.nip','left');

    if (!empty($hari)) {
        $this->db->where('a.hari', $hari);
    }

    $total_rows = $this->db->count_all_results(); // <-- query reset otomatis

    // ================================
    // PAGINATION SETUP
    // ================================
    $this->load->library('pagination');

    $config['base_url'] = base_url('admin/dataPenempatan'); // TIDAK MENGGUNAKAN index/ agar query string aman
    $config['total_rows'] = $total_rows;
    $config['per_page'] = 10;
    $config['page_query_string'] = TRUE;
    $config['query_string_segment'] = 'page';
    $config['reuse_query_string'] = TRUE;

    // BOOTSTRAP 4
    $config['full_tag_open'] = '<nav><ul class="pagination">';
    $config['full_tag_close'] = '</ul></nav>';

    $config['first_link'] = 'First';
    $config['first_tag_open'] = '<li class="page-item"><span class="page-link">';
    $config['first_tag_close'] = '</span></li>';

    $config['last_link'] = 'Last';
    $config['last_tag_open'] = '<li class="page-item"><span class="page-link">';
    $config['last_tag_close'] = '</span></li>';

    $config['next_link'] = '&raquo;';
    $config['next_tag_open'] = '<li class="page-item"><span class="page-link">';
    $config['next_tag_close'] = '</span></li>';

    $config['prev_link'] = '&laquo;';
    $config['prev_tag_open'] = '<li class="page-item"><span class="page-link">';
    $config['prev_tag_close'] = '</span></li>';

    $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
    $config['cur_tag_close'] = '</span></li>';

    $config['num_tag_open'] = '<li class="page-item"><span class="page-link">';
    $config['num_tag_close'] = '</span></li>';

    $this->pagination->initialize($config);

    // OFFSET (Jika page kosong → 0)
    $page = $this->input->get('page');
    $offset = ($page) ? $page : 0;

    // ================================
    // QUERY AMBIL DATA PER HALAMAN
    // ================================
    $this->db->select('a.*, b.nama_pelajaran, c.nama_kelas, d.tahun_akademik, e.nama_pegawai');
    $this->db->from('data_penempatan a');
    $this->db->join('data_pelajaran b','a.id_pelajaran=b.id_pelajaran');
    $this->db->join('data_kelas c','a.id_kelas=c.id_kelas');
    $this->db->join('data_akademik d','a.id_akademik=d.id_akademik');
    $this->db->join('data_pegawai e','a.nip=e.nip','left');

    if (!empty($hari)) {
        $this->db->where('a.hari', $hari);
    }

    $this->db->limit($config['per_page'], $offset);
    $data['penempatan'] = $this->db->get()->result();

    // ================================
    // LOAD VIEW
    // ================================
    $this->load->view('templates_admin/header', $data);
    $this->load->view('templates_admin/sidebar');
    $this->load->view('admin/Penempatan/dataPenempatan', $data);
    $this->load->view('templates_admin/footer');
}


  public function tambahData()
  {
    $data['title'] = "Tambah Data Penempatan";
    $data['pelajaran'] = $this->penggajianModel->get_data('data_pelajaran')->result();
    $data['kelas'] = $this->penggajianModel->get_data('data_kelas')->result();
    $data['pegawai'] = $this->penggajianModel->get_data('data_pegawai')->result();
    $data['akademik'] = $this->penggajianModel->get_data('data_akademik')->result();
    $this->load->view('templates_admin/header', $data);
    $this->load->view('templates_admin/sidebar');
    $this->load->view('admin/Penempatan/tambahDatapenempatan', $data);
    $this->load->view('templates_admin/footer');
  }

  public function tambahDataAksi()
  {
      $this->_rules();

      if ($this->form_validation->run() == FALSE) {
          $this->tambahData();
      } else {
          $data = array(
              'id_pelajaran'   => $this->input->post('id_pelajaran'),
              'id_kelas'       => $this->input->post('id_kelas'),
              'id_akademik'    => $this->input->post('id_akademik'),
              'nip'            => $this->input->post('nip'),
              'hari'           => $this->input->post('hari'),
              'jam_mulai'      => $this->input->post('jam_mulai'),
              'jam_akhir'      => $this->input->post('jam_akhir'),
              'total_jam'      => $this->input->post('total_jam'),
              'keterangan'     => $this->input->post('keterangan')
          );

          $this->penggajianModel->insert_data($data, 'data_penempatan');

          $this->session->set_flashdata('pesan', 
              '<div class="alert alert-success alert-dismissible fade show" role="alert">
                  <strong>Data berhasil ditambahkan</strong>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
              </div>'
          );

          redirect('admin/dataPenempatan');
      }
  }

  public function updateData($id)
  {
    // $where = array('id_penempatan' => $id);
    $data['pelajaran'] = $this->penggajianModel->get_data('data_pelajaran')->result();
    $data['kelas'] = $this->penggajianModel->get_data('data_kelas')->result();
    $data['pegawai'] = $this->penggajianModel->get_data('data_pegawai')->result();
    $data['akademik'] = $this->penggajianModel->get_data('data_akademik')->result();
    $data['penempatan'] = $this->db->query("SELECT * FROM data_penempatan WHERE id_penempatan = '$id'")->result();
    $data['title'] = "Update Data penempatan";

    $this->load->view('templates_admin/header', $data);
    $this->load->view('templates_admin/sidebar');
    $this->load->view('admin/penempatan/updateDatapenempatan', $data);
    $this->load->view('templates_admin/footer');
  }

  public function updateDataAksi()
  {
    $this->_rules();

    if ($this->form_validation->run() == FALSE) {
      $id = $this->insert->post('id_penempatan', $data);
      $this->updateData($id);
    } else {
      $id           = $this->input->post('id_penempatan');
      $id_pelajaran = $this->input->post('id_pelajaran');
      $id_kelas = $this->input->post('id_kelas');
      $id_akademik = $this->input->post('id_akademik');
      $nip = $this->input->post('nip');
      $jam_mulai = $this->input->post('jam_mulai');
      $jam_akhir = $this->input->post('jam_akhir');
      $total_jam = $this->input->post('total_jam');
      $keterangan = $this->input->post('keterangan');

      $data = array(
        'id_pelajaran' => $id_pelajaran,
        'id_kelas' => $id_kelas,
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
      redirect('admin/datapenempatan');
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
    redirect('admin/datapenempatan');
  }
  public function printData()
  {
    $data['title'] = "Cetak Data Penempatan";
    $data['penempatan'] = $this->db->query("SELECT a.*, b.nama_pelajaran, c.nama_kelas, d.tahun_akademik FROM data_penempatan a
                    JOIN data_pelajaran b ON a.id_pelajaran = b.id_pelajaran
                    JOIN data_kelas c ON a.id_kelas = c.id_kelas
                    JOIN data_akademik d ON a.id_akademik = d.id_akademik")->result();
    $this->load->view('templates_admin/header', $data);
    $this->load->view('admin/penempatan/cetakdatapenempatan');
  }

  public function _rules()
  {
    $this->form_validation->set_rules('id_pelajaran', 'ID Pelajaran', 'required');
    $this->form_validation->set_rules('id_kelas', 'ID Kelas', 'required');
    $this->form_validation->set_rules('id_akademik', 'ID Akademic', 'required');
    $this->form_validation->set_rules('nip', 'NIP', 'required');
    $this->form_validation->set_rules('jam_mulai', 'Jam Mulai', 'required');
    $this->form_validation->set_rules('jam_akhir', 'Jam Akhir', 'required');
    $this->form_validation->set_rules('total_jam', 'Total Jam', 'required');
    $this->form_validation->set_rules('keterangan', 'Keterangan', 'required');
  }
}
