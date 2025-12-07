<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

  <?= $this->session->flashdata('pesan'); ?>

  <!-- FORM AJUKAN IJIN -->
  <div class="card shadow mb-4">
    <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Form Ajukan Ijin</h6></div>
    <div class="card-body">
      <form method="post" action="<?= base_url('pegawai/ijin/ajukan'); ?>">
        <div class="form-group">
          <label>Jenis Ijin</label>
          <select name="jenis_ijin" class="form-control" required>
            <option value="">-- Pilih --</option>
            <option value="sakit">Sakit</option>
            <option value="cuti">Cuti</option>
            <option value="keluarga">Acara Keluarga</option>
            <option value="lainnya">Lainnya</option>
          </select>
        </div>
        <div class="form-group">
          <label>Keterangan</label>
          <textarea name="keterangan" class="form-control" rows="3"></textarea>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label>Tanggal Mulai</label>
              <input type="date" name="tanggal_mulai" class="form-control" required>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Tanggal Selesai</label>
              <input type="date" name="tanggal_selesai" class="form-control" required>
            </div>
          </div>
        </div>
        <button type="submit" class="btn btn-primary">Ajukan</button>
      </form>
    </div>
  </div>

  <!-- DAFTAR IJIN SAYA -->
  <div class="card shadow">
    <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Riwayat Ijin Saya</h6></div>
    <div class="card-body">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>No</th>
            <th>Jenis</th>
            <th>Keterangan</th>
            <th>Tanggal</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php $no=1; foreach ($daftar_ijin as $i): ?>
            <tr>
              <td><?= $no++; ?></td>
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
            </tr>
          <?php endforeach; ?>
          <?php if (empty($daftar_ijin)): ?>
            <tr><td colspan="5" class="text-center">Belum ada permohonan ijin.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>