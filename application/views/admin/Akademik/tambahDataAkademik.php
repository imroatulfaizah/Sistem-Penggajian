<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
  </div>

  <div class="card" style="width: 60%; margin-bottom:100px;">
    <div class="card-body">
      <form action="<?= base_url('admin/dataAkademik/tambahDataAksi') ?>" method="post">

        <!-- Tahun Akademik -->
        <div class="form-group">
          <label for="">Tahun Akademik <small>(misal: 2025-2026)</small></label>
          <input type="text" name="tahun_akademik" class="form-control" placeholder="2025-2026">
          <?= form_error('tahun_akademik', '<div class="text-small text-danger">', '</div>') ?>
        </div>

        <!-- Semester -->
        <div class="form-group">
          <label for="">Semester</label>
          <select name="semester" class="form-control">
            <option value="">-- Pilih Semester --</option>
            <option value="Ganjil">Ganjil</option>
            <option value="Genap">Genap</option>
          </select>
          <?= form_error('semester', '<div class="text-small text-danger">', '</div>') ?>
        </div>

        <!-- Nama Akademik -->
        <div class="form-group">
          <label for="">Nama Akademik</label>
          <input type="text" name="nama_akademik" class="form-control" placeholder="Semester Ganjil 2025-2026">
          <?= form_error('nama_akademik', '<div class="text-small text-danger">', '</div>') ?>
        </div>

        <!-- Status Aktif -->
        <div class="form-group">
          <label for="">Status</label>
          <select name="is_aktif" class="form-control">
            <option value="0">Tidak Aktif</option>
            <option value="1">Aktif</option>
          </select>
          <?= form_error('is_aktif', '<div class="text-small text-danger">', '</div>') ?>
        </div>

        <button type="submit" class="btn btn-success">Submit</button>

      </form>
    </div>
  </div>

</div>
