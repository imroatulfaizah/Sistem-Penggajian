<!-- Begin Page Content -->
<div class="container-fluid">

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
  </div>

  <div class="card" style="width: 60%; margin-bottom:100px;">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0">Tambah Data Insentif</h5>
    </div>
    <div class="card-body">
      <form action="<?= base_url('admin/dataInsentif/tambahDataAksi') ?>" method="post">

        <!-- PILIH PEGAWAI (NIP + Nama) -->
        <div class="form-group">
          <label for="nip">Pegawai <span class="text-danger">*</span></label>
          <select name="nip" id="nip" class="form-control <?= form_error('nip') ? 'is-invalid' : '' ?>" required>
            <option value="">-- Pilih Pegawai --</option>
            <?php foreach ($pegawai as $p): ?>
              <option value="<?= $p->nip; ?>" <?= set_select('nip', $p->nip); ?>>
                <?= $p->nip; ?> - <?= $p->nama_pegawai; ?>
              </option>
            <?php endforeach; ?>
          </select>
          <?= form_error('nip', '<div class="invalid-feedback">', '</div>'); ?>
        </div>

        <div class="form-group">
          <label for="nama_insentif">Nama Insentif <span class="text-danger">*</span></label>
          <input type="text" name="nama_insentif" id="nama_insentif" class="form-control <?= form_error('nama_insentif') ? 'is-invalid' : '' ?>" 
                 value="<?= set_value('nama_insentif'); ?>" placeholder="Contoh: Insentif Ekstrakurikuler, Bonus Mengajar">
          <?= form_error('nama_insentif', '<div class="invalid-feedback">', '</div>'); ?>
        </div>

        <div class="form-group">
          <label for="nominal">Nominal (Rp) <span class="text-danger">*</span></label>
          <input type="number" name="nominal" id="nominal" class="form-control <?= form_error('nominal') ? 'is-invalid' : '' ?>" 
                 value="<?= set_value('nominal'); ?>" min="1" placeholder="500000">
          <?= form_error('nominal', '<div class="invalid-feedback">', '</div>'); ?>
        </div>

        <div class="form-group">
          <label for="is_paid">Status Pembayaran <span class="text-danger">*</span></label>
          <select name="is_paid" id="is_paid" class="form-control <?= form_error('is_paid') ? 'is-invalid' : '' ?>" required>
            <option value="">-- Pilih Status --</option>
            <option value="0" <?= set_select('is_paid', '0'); ?>>Belum Dibayar</option>
            <option value="1" <?= set_select('is_paid', '1'); ?>>Sudah Dibayar</option>
          </select>
          <?= form_error('is_paid', '<div class="invalid-feedback">', '</div>'); ?>
        </div>

        <div class="form-group">
          <label for="nomor_kwitansi">Nomor Kwitansi</label>
          <input type="text" name="nomor_kwitansi" id="nomor_kwitansi" class="form-control" 
                 value="<?= set_value('nomor_kwitansi'); ?>" placeholder="Opsional jika belum dibayar">
        </div>
        <div class="text-right">
          <a href="<?= base_url('admin/dataInsentif'); ?>" class="btn btn-secondary">
            Kembali
          </a>
          <button type="submit" class="btn btn-success">
          <i class="fas fa-save"></i> Simpan Insentif
          </button>
        </div>

      </form>
    </div>
  </div>

</div>
<!-- /.container-fluid -->