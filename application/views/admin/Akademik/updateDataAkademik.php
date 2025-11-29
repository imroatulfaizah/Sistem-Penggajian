<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
  </div>

  <div class="card" style="width: 60%; margin-bottom:100px;">
    <div class="card-body">

      <?php foreach($akademik as $j): ?>
      <form action="<?= base_url('admin/dataAkademik/updateDataAksi') ?>" method="post">

        <input type="hidden" name="id_akademik" value="<?= $j->id_akademik; ?>">

        <div class="form-group">
          <label>Tahun Akademik</label>
          <input type="text" name="tahun_akademik" class="form-control" value="<?= $j->tahun_akademik; ?>">
          <?= form_error('tahun_akademik', '<div class="text-small text-danger">', '</div>') ?>
        </div>

        <div class="form-group">
          <label>Semester</label>
          <select name="semester" class="form-control">
            <option value="Ganjil" <?= $j->semester == 'Ganjil' ? 'selected' : '' ?>>Ganjil</option>
            <option value="Genap" <?= $j->semester == 'Genap' ? 'selected' : '' ?>>Genap</option>
          </select>
        </div>

        <div class="form-group">
          <label>Nama Akademik</label>
          <input type="text" name="nama_akademik" class="form-control" value="<?= $j->nama_akademik; ?>">
        </div>

        <div class="form-group">
          <label>Aktif?</label>
          <select name="is_aktif" class="form-control">
            <option value="0" <?= $j->is_aktif == 0 ? 'selected' : '' ?>>Tidak</option>
            <option value="1" <?= $j->is_aktif == 1 ? 'selected' : '' ?>>Ya</option>
          </select>
        </div>

        <button type="submit" class="btn btn-success">Update</button>

      </form>
      <?php endforeach; ?>
    </div>
  </div>

</div>
