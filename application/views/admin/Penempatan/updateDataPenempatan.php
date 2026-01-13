<!-- Begin Page Content -->
<div class="container-fluid">

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
  </div>

  <div class="card" style="width: 60%; margin-bottom:100px;">
    <div class="card-body">

<form action="<?= base_url('admin/datapenempatan/updateDataAksi') ?>" method="post">

  <!-- ID Penempatan -->
  <input type="hidden" name="id_penempatan" value="<?= $penempatan->id_penempatan; ?>">

  <!-- Data Pelajaran -->
  <div class="form-group">
    <label>Data Pelajaran</label>
    <select name="id_pelajaran" class="form-control">
      <option value="">-- Pilih Pelajaran --</option>
      <?php foreach ($pelajaran as $row): ?>
        <option value="<?= $row->id_pelajaran ?>"
          <?= ($row->id_pelajaran == $penempatan->id_pelajaran) ? 'selected' : '' ?>>
          <?= $row->nama_pelajaran ?>
        </option>
      <?php endforeach; ?>
    </select>
    <?= form_error('id_pelajaran','<div class="text-small text-danger">','</div>') ?>
  </div>

  <!-- Data Kelas -->
  <div class="form-group">
    <label>Data Kelas</label>
    <select name="id_kelas" class="form-control">
      <option value="">-- Pilih Kelas --</option>
      <?php foreach ($kelas as $row): ?>
        <option value="<?= $row->id_kelas ?>"
          <?= ($row->id_kelas == $penempatan->id_kelas) ? 'selected' : '' ?>>
          <?= $row->nama_kelas ?>
        </option>
      <?php endforeach; ?>
    </select>
    <?= form_error('id_kelas','<div class="text-small text-danger">','</div>') ?>
  </div>

  <!-- Tahun Akademik -->
  <div class="form-group">
    <label>Tahun Akademik</label>
    <select name="id_akademik" class="form-control">
      <option value="">-- Pilih Tahun Akademik --</option>
      <?php foreach ($akademik as $row): ?>
        <option value="<?= $row->id_akademik ?>"
          <?= ($row->id_akademik == $penempatan->id_akademik) ? 'selected' : '' ?>>
          <?= $row->tahun_akademik ?>
        </option>
      <?php endforeach; ?>
    </select>
    <?= form_error('id_akademik','<div class="text-small text-danger">','</div>') ?>
  </div>

  <!-- Guru -->
  <div class="form-group">
    <label>Guru (NIP)</label>
    <select name="nip" class="form-control">
      <option value="">-- Pilih Guru --</option>
      <?php foreach ($pegawai as $row): ?>
        <option value="<?= $row->nip ?>"
          <?= ($row->nip == $penempatan->nip) ? 'selected' : '' ?>>
          <?= $row->nama_pegawai ?>
        </option>
      <?php endforeach; ?>
    </select>
    <?= form_error('nip','<div class="text-small text-danger">','</div>') ?>
  </div>

  <!-- Hari -->
  <div class="form-group">
    <label>Hari</label>
    <select name="hari" class="form-control">
      <option value="">-- Pilih Hari --</option>
      <?php foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $h): ?>
        <option value="<?= $h ?>" <?= ($h == $penempatan->hari) ? 'selected' : '' ?>>
          <?= $h ?>
        </option>
      <?php endforeach; ?>
    </select>
    <?= form_error('hari','<div class="text-small text-danger">','</div>') ?>
  </div>

  <!-- Jam -->
  <div class="form-group">
    <label>Jam Mulai</label>
    <input type="time" name="jam_mulai" class="form-control" value="<?= $penempatan->jam_mulai ?>">
  </div>

  <div class="form-group">
    <label>Jam Akhir</label>
    <input type="time" name="jam_akhir" class="form-control" value="<?= $penempatan->jam_akhir ?>">
  </div>

  <!-- Total Jam -->
  <div class="form-group">
    <label>Total Jam</label>
    <input type="number" name="total_jam" class="form-control" value="<?= $penempatan->total_jam ?>">
  </div>

  <!-- Keterangan -->
  <div class="form-group">
    <label>Keterangan</label>
    <input type="text" name="keterangan" class="form-control" value="<?= $penempatan->keterangan ?>">
  </div>

  <button type="submit" class="btn btn-success">Update</button>
</form>

    </div>
  </div>
</div>
