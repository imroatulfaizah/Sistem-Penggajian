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

  <!-- ID Penempatan hidden -->
  <input type="hidden" name="id_penempatan" value="<?= $j->id_penempatan; ?>">

  <!-- Data Pelajaran -->
  <div class="form-group">
    <label for="">Data Pelajaran</label>
    <select name="id_pelajaran" class="form-control">
      <option value="">-- Pilih Pelajaran --</option>
      <?php foreach ($pelajaran as $row): ?>
        <option value="<?= $row->id_pelajaran ?>" 
          <?= ($row->id_pelajaran == $j->id_pelajaran) ? 'selected' : '' ?>>
          <?= $row->nama_pelajaran ?>
        </option>
      <?php endforeach; ?>
    </select>
    <?= form_error('id_pelajaran', '<div class="text-small text-danger">', '</div>') ?>
  </div>

  <!-- Data Kelas -->
  <div class="form-group">
    <label for="">Data Kelas</label>
    <select name="id_kelas" class="form-control">
      <option value="">-- Pilih Kelas --</option>
      <?php foreach ($kelas as $row): ?>
        <option value="<?= $row->id_kelas ?>" 
          <?= ($row->id_kelas == $j->id_kelas) ? 'selected' : '' ?>>
          <?= $row->nama_kelas ?>
        </option>
      <?php endforeach; ?>
    </select>
    <?= form_error('id_kelas', '<div class="text-small text-danger">', '</div>') ?>
  </div>

  <!-- Tahun Akademik -->
  <div class="form-group">
    <label for="">Tahun Akademik</label>
    <select name="id_akademik" class="form-control">
      <option value="">-- Pilih Tahun Akademik --</option>
      <?php foreach ($akademik as $row): ?>
        <option value="<?= $row->id_akademik ?>" 
          <?= ($row->id_akademik == $j->id_akademik) ? 'selected' : '' ?>>
          <?= $row->tahun_akademik ?>
        </option>
      <?php endforeach; ?>
    </select>
    <?= form_error('id_akademik', '<div class="text-small text-danger">', '</div>') ?>
  </div>

  <!-- Guru -->
  <div class="form-group">
    <label for="">Guru (NIP)</label>
    <select name="nip" class="form-control">
      <option value="">-- Pilih Guru --</option>
      <?php foreach ($pegawai as $row): ?>
        <option value="<?= $row->nip ?>" 
          <?= ($row->nip == $j->nip) ? 'selected' : '' ?>>
          <?= $row->nama_pegawai ?>
        </option>
      <?php endforeach; ?>
    </select>
    <?= form_error('nip', '<div class="text-small text-danger">', '</div>') ?>
  </div>

  <!-- Hari -->
  <div class="form-group">
    <label for="">Hari</label>
    <select name="hari" class="form-control">
      <option value="">-- Pilih Hari --</option>
      <?php 
        $hari_list = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
        foreach ($hari_list as $h):
      ?>
        <option value="<?= $h ?>" <?= ($h == $j->hari) ? 'selected' : '' ?>>
          <?= $h ?>
        </option>
      <?php endforeach; ?>
    </select>
    <?= form_error('hari', '<div class="text-small text-danger">', '</div>') ?>
  </div>

  <!-- Jam Mulai -->
  <div class="form-group">
    <label for="">Jam Mulai</label>
    <input type="time" name="jam_mulai" class="form-control" value="<?= $j->jam_mulai; ?>">
    <?= form_error('jam_mulai', '<div class="text-small text-danger">', '</div>') ?>
  </div>

  <!-- Jam Akhir -->
  <div class="form-group">
    <label for="">Jam Akhir</label>
    <input type="time" name="jam_akhir" class="form-control" value="<?= $j->jam_akhir; ?>">
    <?= form_error('jam_akhir', '<div class="text-small text-danger">', '</div>') ?>
  </div>

  <!-- Total Jam -->
  <div class="form-group">
      <label for="">Total Jam</label>
      <input 
          type="number" 
          name="total_jam" 
          class="form-control" 
          step="0.5" 
          min="0.5" 
          max="12"
          value="<?= $j->total_jam; ?>"
      >
      <?= form_error('total_jam', '<div class="text-small text-danger">', '</div>') ?>
  </div>

  <!-- Keterangan -->
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
