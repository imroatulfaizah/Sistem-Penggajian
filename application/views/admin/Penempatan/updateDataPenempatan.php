<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
  </div>

  <div class="card" style="width: 60%; margin-bottom:100px;">
    <div class="card-body">

      <?php foreach($jabatan as $j): ?>
      <form action="<?= base_url('admin/dataPenempatan/updateDataAksi') ?>" method="post">

        <div class="form-group">
          <label for="">Nama Jabatan</label>
          <input type="hidden" name="id_jabatan" value="<?= $j->id_jabatan; ?>">
          <input type="text" name="nama_jabatan" class="form-control" value="<?= $j->nama_jabatan; ?>">
          <?= form_error('nama_jabatan', '<div class="text-small text-danger">', '</div>') ?>
        </div>
        <div class="form-group">
          <label for="">Tunjangan Jabatan</label>
          <input type="number" name="tunjangan_jabatan" class="form-control" value="<?= $j->tunjangan_jabatan; ?>">
          <?= form_error('tunjangan_jabatan', '<div class="text-small text-danger">', '</div>') ?>
        </div>
        <div class="form-group">
          <label for="">Tunjangan Transport</label>
          <input type="number" name="tunjangan_transport" class="form-control" value="<?= $j->tunjangan_transport; ?>">
          <?= form_error('tunjangan_transport', '<div class="text-small text-danger">', '</div>') ?>
        </div>
        <div class="form-group">
          <label for="">Upah Mengajar</label>
          <input type="number" name="upah_mengajar" class="form-control" value="<?= $j->upah_mengajar; ?>">
          <?= form_error('upah_mengajar', '<div class="text-small text-danger">', '</div>') ?>
        </div>

        <button type="submit" class="btn btn-success">Update</button>

      </form>
      <?php endforeach; ?>
    </div>
  </div>


</div>
