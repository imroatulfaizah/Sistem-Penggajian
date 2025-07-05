<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
  </div>

  <div class="card" style="width: 60%; margin-bottom:100px;">
    <div class="card-body">

      <?php foreach($kelas as $j): ?>
      <form action="<?= base_url('admin/dataKelas/updateDataAksi') ?>" method="post">

        <div class="form-group">
          <label for="">Nama Kelas</label>
          <input type="hidden" name="id_kelas" value="<?= $j->id_kelas; ?>">
          <input type="text" name="nama_kelas" class="form-control" value="<?= $j->nama_kelas; ?>">
          <?= form_error('nama_kelas', '<div class="text-small text-danger">', '</div>') ?>
        </div>

        <button type="submit" class="btn btn-success">Update</button>

      </form>
      <?php endforeach; ?>
    </div>
  </div>


</div>
