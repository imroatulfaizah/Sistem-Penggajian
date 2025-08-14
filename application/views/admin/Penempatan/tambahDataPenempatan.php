<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
  </div>

  <div class="card" style="width: 60%; margin-bottom:100px;">
    <div class="card-body">
      <form action="<?= base_url('admin/dataPenempatan/tambahDataAksi') ?>" method="post">

        <!-- id_pelajaran -->
        <div class="form-group">
          <label for="">Data Pelajaran</label>
          <select name="id_pelajaran" class="form-control">
            <option value="">-- Pilih Pelajaran --</option>
            <?php foreach ($pelajaran as $row): ?>
              <option value="<?= $row->id_pelajaran ?>">
                <?= $row->nama_pelajaran ?>
              </option>
            <?php endforeach; ?>
          </select>
          <?= form_error('id_pelajaran', '<div class="text-small text-danger">', '</div>') ?>
        </div>

        <!-- id_kelas -->
        <div class="form-group">
          <label for="">Data Kelas</label>
          <select name="id_kelas" class="form-control">
            <option value="">-- Pilih Kelas --</option>
            <?php foreach ($kelas as $row): ?>
              <option value="<?= $row->id_kelas ?>">
                <?= $row->nama_kelas ?>
              </option>
            <?php endforeach; ?>
          </select>
          <?= form_error('id_kelas', '<div class="text-small text-danger">', '</div>') ?>
        </div>

        <!-- id_akademik -->
        <div class="form-group">
          <label for="">Tahun Akademik</label>
          <select name="id_akademik" class="form-control">
            <option value="">-- Pilih Tahun Akademik --</option>
            <?php foreach ($akademik as $row): ?>
              <option value="<?= $row->id_akademik ?>">
                <?= $row->tahun_akademik ?>
              </option>
            <?php endforeach; ?>
          </select>
          <?= form_error('id_akademik', '<div class="text-small text-danger">', '</div>') ?>
        </div>

        <!-- nip -->
        <div class="form-group">
          <label for="">Guru (NIP)</label>
          <select name="nip" class="form-control">
            <option value="">-- Pilih Guru --</option>
            <?php foreach ($pegawai as $row): ?>
              <option value="<?= $row->nip ?>">
                <?= $row->nama_pegawai ?>
              </option>
            <?php endforeach; ?>
          </select>
          <?= form_error('nip', '<div class="text-small text-danger">', '</div>') ?>
        </div>

        <!-- hari -->
        <div class="form-group">
          <label for="">Hari</label>
          <select name="hari" class="form-control">
            <option value="">-- Pilih Hari --</option>
            <option value="Senin">Senin</option>
            <option value="Selasa">Selasa</option>
            <option value="Rabu">Rabu</option>
            <option value="Kamis">Kamis</option>
            <option value="Jumat">Jumat</option>
            <option value="Sabtu">Sabtu</option>
            <option value="Minggu">Minggu</option>
          </select>
          <?= form_error('hari', '<div class="text-small text-danger">', '</div>') ?>
        </div>

        <!-- jam_mulai -->
        <div class="form-group">
          <label for="">Jam Mulai</label>
          <input type="time" name="jam_mulai" class="form-control">
          <?= form_error('jam_mulai', '<div class="text-small text-danger">', '</div>') ?>
        </div>

        <!-- jam_akhir -->
        <div class="form-group">
          <label for="">Jam Akhir</label>
          <input type="time" name="jam_akhir" class="form-control">
          <?= form_error('jam_akhir', '<div class="text-small text-danger">', '</div>') ?>
        </div>

        <!-- total_jam -->
        <div class="form-group">
          <label for="">Total Jam</label>
          <input type="number" name="total_jam" class="form-control">
          <?= form_error('total_jam', '<div class="text-small text-danger">', '</div>') ?>
        </div>

        <!-- keterangan -->
        <div class="form-group">
          <label for="">Keterangan</label>
          <input type="text" name="keterangan" class="form-control" value="Harian">
          <?= form_error('keterangan', '<div class="text-small text-danger">', '</div>') ?>
        </div>

        <button type="submit" class="btn btn-success">Submit</button>
      </form>
    </div>
  </div>
</div>
