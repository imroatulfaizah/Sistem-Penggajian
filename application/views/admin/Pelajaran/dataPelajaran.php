<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
  </div>

  <!-- Informasi Semester Aktif -->
  <?php 
  $akademik_aktif = $this->db->where('is_aktif', 1)->get('data_akademik')->row();
  ?>
  <div class="alert alert-info mb-4">
    <i class="fas fa-info-circle"></i>
    <strong>Semester Aktif:</strong> <?= $akademik_aktif->nama_akademik ?? 'Belum ditentukan'; ?>
    <br><small class="text-muted">
      Ubah nama pelajaran hanya akan berlaku untuk semester aktif ini. Data semester sebelumnya tetap menampilkan nama lama.
    </small>
  </div>

  <a class="btn btn-sm btn-success mb-3" href="<?= base_url('admin/dataPelajaran/tambahData'); ?>">
    <i class="fas fa-plus"></i> Tambah Master Pelajaran
  </a>

  <?= $this->session->flashdata('pesan'); ?>

  <div class="table-responsive">
    <table class="table table-bordered table-striped table-hover">
      <thead class="thead-dark">
        <tr>
          <th width="5%" class="text-center">No</th>
          <th>Nama Pelajaran (Master)</th>
          <th width="20%" class="text-center">Nama di Semester Aktif</th>
          <th width="25%" class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $no = 1;
        foreach ($pelajaran as $p) : 
          // Cek apakah ada versi khusus di semester aktif
          $history = $this->db->get_where('data_pelajaran_periode', [
            'id_pelajaran' => $p->id_pelajaran,
            'id_akademik'  => $akademik_aktif->id_akademik ?? 0
          ])->row();

          $nama_aktif = $history ? $history->nama_pelajaran : $p->nama_pelajaran;
          $beda = $history && $history->nama_pelajaran !== $p->nama_pelajaran;
        ?>
          <tr>
            <td class="text-center"><?= $no++; ?></td>
            <td>
              <?= htmlspecialchars($p->nama_pelajaran); ?>
              <?php if ($beda): ?>
                <small class="text-muted d-block">
                  <i class="fas fa-history"></i> Master: <?= htmlspecialchars($p->nama_pelajaran); ?>
                </small>
              <?php endif; ?>
            </td>
            <td class="text-center font-weight-bold <?= $beda ? 'text-success' : '' ?>">
              <?= htmlspecialchars($nama_aktif); ?>
              <?php if ($beda): ?>
                <span class="badge badge-success ml-1">Diubah</span>
              <?php endif; ?>
            </td>
            <td class="text-center">
              <!-- Tombol utama: Ubah nama untuk semester aktif -->
              <a href="<?= base_url('admin/dataPelajaran/ubahNama/' . $p->id_pelajaran); ?>" 
                 class="btn btn-sm btn-warning" title="Ubah Nama untuk <?= $akademik_aktif->nama_akademik ?? 'Semester Aktif' ?>">
                <i class="fas fa-edit"></i> Ubah Nama Semester
              </a>

              <!-- Hapus master (hanya jika belum dipakai di penempatan) -->
              <?php 
              $dipakai = $this->db->where('id_pelajaran', $p->id_pelajaran)->get('data_penempatan')->num_rows();
              if ($dipakai == 0): 
              ?>
                <a href="<?= base_url('admin/dataPelajaran/deleteData/' . $p->id_pelajaran); ?>" 
                   onclick="return confirm('Yakin hapus master pelajaran ini?')" 
                   class="btn btn-sm btn-danger" title="Hapus Master">
                  <i class="fas fa-trash"></i>
                </a>
              <?php else: ?>
                <button class="btn btn-sm btn-secondary btn-disabled" disabled title="Sudah digunakan di jadwal">
                  <i class="fas fa-trash"></i>
                </button>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>

        <?php if (empty($pelajaran)): ?>
          <tr>
            <td colspan="4" class="text-center text-muted">Belum ada data pelajaran.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

</div>
<!-- /.container-fluid -->