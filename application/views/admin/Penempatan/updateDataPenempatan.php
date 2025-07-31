<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
  </div>

  <div class="card" style="width: 60%; margin-bottom:100px;">
    <div class="card-body">
      <?php foreach($penempatan as $j): ?>
      <form action="<?= base_url('admin/dataPenempatan/updateDataAksi') ?>" method="post">
      <input type="hidden" name="id_penempatan" value="<?= $j->id_penempatan; ?>">
        <div class="form-group">
          <label for="">Id Pelajaran</label>
          <input type="hidden" name="id_pelajaran" value="<?= $j->id_pelajaran; ?>">
          <input type="text" name="nama_pelajaran" class="form-control" value="<?= $j->id_pelajaran; ?>">
          <?= form_error('id_pelajaran', '<div class="text-small text-danger">', '</div>') ?>
        </div>
        <div class="form-group">
          <label for="">Id Kelas</label>
          <input type="number" name="id_kelas" class="form-control" value="<?= $j->id_kelas; ?>">
          <?= form_error('id_kelas', '<div class="text-small text-danger">', '</div>') ?>
        </div>
        <div class="form-group">
          <label for="">Id Akademik</label>
          <input type="number" name="id_akademik" class="form-control" value="<?= $j->id_akademik; ?>">
          <?= form_error('id_akademik', '<div class="text-small text-danger">', '</div>') ?>
        </div>
        <div class="form-group">
          <label for="">NIP</label>
          <input type="number" name="nip" class="form-control" value="<?= $j->nip; ?>">
          <?= form_error('nip', '<div class="text-small text-danger">', '</div>') ?>
        </div>
        <div class="form-group">
          <label for="">Jam Mulai</label>
          <input type="time" name="jam_mulai" class="form-control" value="<?= $j->jam_mulai; ?>">
          <?= form_error('jam_mulai', '<div class="text-small text-danger">', '</div>') ?>
        </div>
        <div class="form-group">
          <label for="">Jam Akhir</label>
          <input type="time" name="jam_akhir" class="form-control" value="<?= $j->jam_akhir; ?>">
          <?= form_error('jam_berakhir', '<div class="text-small text-danger">', '</div>') ?>
        </div>
        <div class="form-group">
          <label for="">Keterangan</label>
          <input type="text" name="keterangan" class="form-control" value="<?= $j->keterangan; ?>">
          <?= form_error('keterangan', '<div class="text-small text-danger">', '</div>') ?>
        </div>

        <button type="submit" class="btn btn-success">Update</button>

      </form>
      <?php endforeach; ?>
    </div>
  </div>


</div>
