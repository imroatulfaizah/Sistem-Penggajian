<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
  </div>

  <div class="card" style="width: 60%; margin-bottom:100px;">
    <div class="card-body">
      <form action="<?= base_url('admin/dataAkademik/tambahDataAksi') ?>" method="post">

        <div class="form-group">
          <label for="">Tahun Akademik</label>
          <input type="text" name="tahun_akademik" class="form-control">
          <?= form_error('tahun_akademik', '<div class="text-small text-danger">', '</div>') ?>
        </div>

        <button type="submit" class="btn btn-success">Submit</button>

      </form>
    </div>
  </div>


</div>
