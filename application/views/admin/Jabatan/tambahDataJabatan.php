<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
  </div>

  <div class="card" style="width: 60%; margin-bottom:100px;">
    <div class="card-body">
      <form action="<?= base_url('admin/dataJabatan/tambahDataAksi') ?>" method="post">

        <div class="form-group">
          <label for="">Nama Jabatan</label>
          <input type="text" name="nama_jabatan" class="form-control">
          <?= form_error('nama_jabatan', '<div class="text-small text-danger">', '</div>') ?>
        </div>
        <div class="form-group">
          <label for="">Tunjangan Jabatan</label>
          <input type="number" name="tunjangan_jabatan" class="form-control">
          <?= form_error('tunjangan_jabatan', '<div class="text-small text-danger">', '</div>') ?>
        </div>
        <div class="form-group">
          <label for="">Tunjangan Transport</label>
          <input type="number" name="tunjangan_transport" class="form-control">
          <?= form_error('tunjangan_transport', '<div class="text-small text-danger">', '</div>') ?>
        </div>
        <div class="form-group">
          <label for="">Upah Mengajar</label>
          <input type="number" name="upah_mengajar" class="form-control">
          <?= form_error('upah_mengajar', '<div class="text-small text-danger">', '</div>') ?>
        </div>

        <button type="submit" class="btn btn-success">Submit</button>

      </form>
    </div>
  </div>


</div>
