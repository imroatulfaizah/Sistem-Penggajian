<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
  </div>

  <div class="card" style="width: 60%; margin-bottom:100px;">
    <div class="card-body">
      <form action="<?= base_url('admin/dataPenempatan/tambahDataAksi') ?>" method="post">

        <div class="form-group">
          <label for="">ID Penempatan</label>
          <input type="text" name="id_penempatan" class="form-control">
          <?= form_error('id_penempatan', '<div class="text-small text-danger">', '</div>') ?>
        </div>
        <div class="form-group">
          <label for="">Id Pelajaran</label>
          <input type="number" name="id_pelajaran" class="form-control">
          <?= form_error('id_pelajaran', '<div class="text-small text-danger">', '</div>') ?>
        </div>
        <div class="form-group">
          <label for="">Id Kelas</label>
          <input type="number" name="id_kelas" class="form-control">
          <?= form_error('id_kelas', '<div class="text-small text-danger">', '</div>') ?>
        </div>
        <div class="form-group">
          <label for="">Tunjangan Penempatan</label>
          <input type="number" name="tunjangan_penempatan" class="form-control">
          <?= form_error('tunjangan_penempatan', '<div class="text-small text-danger">', '</div>') ?>
        </div>
        <div class="form-group">
          <label for="">Id Akademik</label>
          <input type="number" name="id_akademik" class="form-control">
          <?= form_error('id_akademik', '<div class="text-small text-danger">', '</div>') ?>
        </div>
        <div class="form-group">
          <label for="">NIP</label>
          <input type="number" name="nip" class="form-control">
          <?= form_error('nip', '<div class="text-small text-danger">', '</div>') ?>
        </div>
        <div class="form-group">
          <label for="">Jam Mulai</label>
          <input type="number" name="jam_mulai" class="form-control">
          <?= form_error('jam_mulai', '<div class="text-small text-danger">', '</div>') ?>
        </div>
        <div class="form-group">
          <label for="">Jam Berakhir</label>
          <input type="number" name="jam_berakhir" class="form-control">
          <?= form_error('jam_berakhir', '<div class="text-small text-danger">', '</div>') ?>
        </div>
        <div class="form-group">
          <label for="">Total Jam</label>
          <input type="number" name="total_jam" class="form-control">
          <?= form_error('total_jam', '<div class="text-small text-danger">', '</div>') ?>
        </div>
        <div class="form-group">
          <label for="">Keterangan</label>
          <input type="number" name="keterangan" class="form-control">
          <?= form_error('keterangan', '<div class="text-small text-danger">', '</div>') ?>
        </div>

        <button type="submit" class="btn btn-success">Submit</button>

      </form>
    </div>
  </div>


</div>
