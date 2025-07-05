<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
  </div>

  <div class="card" style="width: 60%; margin-bottom:100px;">
    <div class="card-body">

      <?php foreach($pelajaran as $j): ?>
      <form action="<?= base_url('admin/dataPelajaran/updateDataAksi') ?>" method="post">

        <div class="form-group">
          <label for="">Nama pelajaran</label>
          <input type="hidden" name="id_pelajaran" value="<?= $j->id_pelajaran; ?>">
          <input type="text" name="nama_pelajaran" class="form-control" value="<?= $j->nama_pelajaran; ?>">
          <?= form_error('nama_pelajaran', '<div class="text-small text-danger">', '</div>') ?>
        </div>

        <button type="submit" class="btn btn-success">Update</button>

      </form>
      <?php endforeach; ?>
    </div>
  </div>


</div>
