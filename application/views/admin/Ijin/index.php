<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

  <?= $this->session->flashdata('pesan'); ?>

  <div class="card shadow">
    <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Daftar Permohonan Ijin</h6></div>
    <div class="card-body">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>No</th>
            <th>NIP/Nama</th>
            <th>Jenis</th>
            <th>Keterangan</th>
            <th>Tanggal</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no=1; foreach ($daftar_ijin as $i): ?>
            <tr>
              <td><?= $no++; ?></td>
              <td><?= $i->nip . ' - ' . htmlspecialchars($i->nama_pegawai); ?></td>
              <td><?= ucfirst($i->jenis_ijin); ?></td>
              <td><?= htmlspecialchars($i->keterangan ?? '-'); ?></td>
              <td><?= date('d/m/Y', strtotime($i->tanggal_mulai)) . ' - ' . date('d/m/Y', strtotime($i->tanggal_selesai)); ?></td>
              <td>
                <?php if ($i->status == 'pending'): ?>
                  <span class="badge badge-warning">Pending</span>
                <?php elseif ($i->status == 'disetujui'): ?>
                  <span class="badge badge-success">Disetujui</span>
                <?php else: ?>
                  <span class="badge badge-danger">Ditolak</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if ($i->status == 'pending'): ?>
                  <a href="<?= base_url('admin/ijin/approve/' . $i->id_ijin); ?>" class="btn btn-sm btn-success">Approve</a>
                  <a href="<?= base_url('admin/ijin/tolak/' . $i->id_ijin); ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin tolak?');">Tolak</a>
                <?php else: ?>
                  Sudah diproses
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($daftar_ijin)): ?>
            <tr><td colspan="7" class="text-center">Belum ada permohonan ijin.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>