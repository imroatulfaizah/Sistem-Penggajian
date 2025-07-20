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

        <div class="form-group">
          <label for="">Tahun Akademik</label>
          <input type="hidden" name="id_akademik" value="<?= $j->id_akademik; ?>">
          <input type="text" name="tahun_akademik" class="form-control" value="<?= $j->tahun_akademik; ?>">
          <?= form_error('tahun_akademik', '<div class="text-small text-danger">', '</div>') ?>
        </div>

        <button type="submit" class="btn btn-success">Update</button>

      </form>
      <?php endforeach; ?>
    </div>
  </div>


</div>