<!-- Begin Page Content -->
<div class="container-fluid">

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
  </div>

  <div class="card" style="width: 60%; margin-bottom:100px;">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0">Update Data Insentif</h5>
    </div>
    <div class="card-body">

      <!-- Kita ambil data dari $insentif[0] karena result() -->
      <?php $i = $insentif[0]; ?>

      <form action="<?= base_url('admin/dataInsentif/updateDataAksi') ?>" method="post">

        <input type="hidden" name="id_insentif" value="<?= $i->id_insentif; ?>">

        <!-- Dropdown Pegawai (NIP + Nama) -->
        <div class="form-group">
          <label for="nip">Pegawai <span class="text-danger">*</span></label>
          <select name="nip" id="nip" class="form-control <?= form_error('nip') ? 'is-invalid' : '' ?>" required>
            <option value="">-- Pilih Pegawai --</option>
            <?php foreach ($pegawai as $p): ?>
              <option value="<?= $p->nip; ?>" <?= $p->nip == $i->nip ? 'selected' : ''; ?>>
                <?= $p->nip; ?> - <?= $p->nama_pegawai; ?>
              </option>
            <?php endforeach; ?>
          </select>
          <?= form_error('nip', '<div class="invalid-feedback">', '</div>'); ?>
        </div>

        <div class="form-group">
          <label for="nama_insentif">Nama Insentif <span class="text-danger">*</span></label>
          <input type="text" name="nama_insentif" id="nama_insentif" class="form-control <?= form_error('nama_insentif') ? 'is-invalid' : '' ?>"
                 value="<?= set_value('nama_insentif', $i->nama_insentif); ?>" required>
          <?= form_error('nama_insentif', '<div class="invalid-feedback">', '</div>'); ?>
        </div>

        <div class="form-group">
          <label for="nominal">Nominal (Rp) <span class="text-danger">*</span></label>
          <input type="number" name="nominal" id="nominal" class="form-control <?= form_error('nominal') ? 'is-invalid' : '' ?>"
                 value="<?= set_value('nominal', $i->nominal); ?>" min="1" required>
          <?= form_error('nominal', '<div class="invalid-feedback">', '</div>'); ?>
        </div>

        <div class="form-group">
          <label for="is_paid">Status Pembayaran <span class="text-danger">*</span></label>
          <select name="is_paid" id="is_paid" class="form-control <?= form_error('is_paid') ? 'is-invalid' : '' ?>" required>
            <option value="">-- Pilih Status --</option>
            <option value="0" <?= ($i->is_paid == '0') ? 'selected' : ''; ?>>Belum Dibayar</option>
            <option value="1" <?= ($i->is_paid == '1') ? 'selected' : ''; ?>>Sudah Dibayar</option>
          </select>
          <?= form_error('is_paid', '<div class="invalid-feedback">', '</div>'); ?>
        </div>

        <div class="form-group">
          <label for="nomor_kwitansi">Nomor Kwitansi</label>
          <input type="text" name="nomor_kwitansi" id="nomor_kwitansi" class="form-control"
                 value="<?= set_value('nomor_kwitansi', $i->nomor_kwitansi); ?>"
                 placeholder="Diisi jika sudah dibayar">
        </div>

        <div class="text-right">
          <a href="<?= base_url('admin/dataInsentif'); ?>" class="btn btn-secondary">
            Kembali
          </a>
          <button type="submit" class="btn btn-success">
            Update Insentif
          </button>
        </div>
      </form>

    </div>
  </div>

</div>
<!-- /.container-fluid -->