<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
  </div>

  <div class="card" style="width: 60%; margin-bottom:100px;">
    <div class="card-body">
      <form action="<?= base_url('bendahara/dataInsentif/tambahDataAksi') ?>" method="post">

        <div class="form-group">
          <label for="">ID Pegawai</label>
          <input type="text" name="id_pegawai" class="form-control">
          <?= form_error('id_pegawai', '<div class="text-small text-danger">', '</div>') ?>
        </div>
        <div class="form-group">
          <label for="">Nama Insentif</label>
          <input type="text" name="nama_insentif" class="form-control">
          <?= form_error('nama_insentif', '<div class="text-small text-danger">', '</div>') ?>
        </div>
        <div class="form-group">
          <label for="">Nominal</label>
          <input type="text" name="nominal" class="form-control">
          <?= form_error('nominal', '<div class="text-small text-danger">', '</div>') ?>
        </div>
        <div class="form-group">
          <label for="">Status Pembayaran</label>
          <input type="text" name="is_paid" class="form-control">
          <?= form_error('is_paid', '<div class="text-small text-danger">', '</div>') ?>
        </div>

        <button type="submit" class="btn btn-success">Submit</button>

      </form>
    </div>
  </div>


</div>
