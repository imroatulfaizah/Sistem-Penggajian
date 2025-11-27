<div class="container-fluid">

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
  </div>

  <div class="card" style="width: 60%; margin-bottom:100px;">
    <div class="card-body">

      <?php foreach ($pegawai as $p) : ?>
        <form action="<?= base_url('admin/dataPegawai/updateDataAksi') ?>" method="post" enctype="multipart/form-data">

          <div class="form-group">
            <label for="">NIP</label>
            <input type="hidden" name="id_pegawai" value="<?= $p->id_pegawai; ?>">
            <input type="number" name="nip" class="form-control" value="<?= $p->nip; ?>">
            <?= form_error('nip', '<div class="text-small text-danger">', '</div>') ?>
          </div>

          <div class="form-group">
            <label for="">Nama Pegawai</label>
            <input type="text" name="nama_pegawai" class="form-control" value="<?= $p->nama_pegawai; ?>">
            <?= form_error('nama_pegawai', '<div class="text-small text-danger">', '</div>') ?>
          </div>

          <div class="form-group">
            <label for="">Username</label>
            <input type="text" name="username" class="form-control" value="<?= $p->username; ?>">
            <?= form_error('username', '<div class="text-small text-danger">', '</div>') ?>
          </div>

          <div class="form-group">
            <label for="">Password</label>
            <input type="password" name="password" class="form-control" value="<?= $p->password; ?>">
            <?= form_error('password', '<div class="text-small text-danger">', '</div>') ?>
          </div>

          <div class="form-group">
            <label for="">Jenis Kelamin</label>
            <select name="jenis_kelamin" id="" class="form-control">
              <option value="Laki-laki" <?= ($p->jenis_kelamin == 'Laki-laki') ? 'selected' : '' ?>>Laki-laki</option>
              <option value="Perempuan" <?= ($p->jenis_kelamin == 'Perempuan') ? 'selected' : '' ?>>Perempuan</option>
            </select>
            <?= form_error('jenis_kelamin', '<div class="text-small text-danger">', '</div>') ?>
          </div>

          <div class="form-group">
            <label for="">Jabatan</label>
            <select name="jabatan" id="" class="form-control">
              <option value="">--Pilih Jabatan--</option>
              <?php foreach ($jabatan as $j) : ?>
                <option value="<?= $j->id_jabatan; ?>" <?= ($p->jabatan == $j->id_jabatan) ? 'selected' : '' ?>>
                  <?= $j->nama_jabatan; ?>
                </option>
              <?php endforeach; ?>
            </select>
            <?= form_error('jabatan', '<div class="text-small text-danger">', '</div>') ?>
          </div>

          <div class="form-group">
            <label for="">Tanggal masuk</label>
            <input type="date" name="tanggal_masuk" class="form-control" value="<?= $p->tanggal_masuk; ?>">
            <?= form_error('tanggal_masuk', '<div class="text-small text-danger">', '</div>') ?>
          </div>

          <div class="form-group">
            <label for="">Status</label>
            <select name="status" id="" class="form-control">
              <option value="SATMINKAL" <?= ($p->status == 'SATMINKAL') ? 'selected' : '' ?>>SATMINKAL</option>
              <option value="NON SATMINKAL" <?= ($p->status == 'NON SATMINKAL') ? 'selected' : '' ?>>NON SATMINKAL</option>
            </select>
            <?= form_error('status', '<div class="text-small text-danger">', '</div>') ?>
          </div>

          <div class="form-group">
            <label for="">Photo</label>
            <input type="file" name="photo" class="form-control">
            <?php if ($p->photo) : ?>
                <img src="<?= base_url('assets/photo/' . $p->photo) ?>" width="80px" class="mt-2">
            <?php endif; ?>
            <?= form_error('photo', '<div class="text-small text-danger">', '</div>') ?>
          </div>

          <div class="form-group">
            <label for="">No HP</label>
            <input type="number" name="no_hp" class="form-control" value="<?= $p->no_hp; ?>">
            <?= form_error('no_hp', '<div class="text-small text-danger">', '</div>') ?>
          </div>

          <div class="form-group">
            <label for="">Email</label>
            <input type="text" name="email" class="form-control" value="<?= $p->email; ?>">
            <?= form_error('email', '<div class="text-small text-danger">', '</div>') ?>
          </div>

          <div class="form-group">
            <label for="">Pendidikan</label>
            <input type="text" name="pendidikan" class="form-control" value="<?= $p->pendidikan; ?>">
            <?= form_error('pendidikan', '<div class="text-small text-danger">', '</div>') ?>
          </div>

          <div class="form-group">
            <label for="">Ekstrakurikuler</label>
            <input type="text" name="ekstra" class="form-control" value="<?= $p->ekstra; ?>" placeholder="Kosongkan jika bukan guru ekstra">
          </div>

          <div class="form-group">
            <label for="">Alamat</label>
            <input type="text" name="alamat" class="form-control" value="<?= $p->alamat; ?>">
            <?= form_error('alamat', '<div class="text-small text-danger">', '</div>') ?>
          </div>

          <div class="form-group">
            <label for="">Hak Akses</label>
            <select name="hak_akses" id="" class="form-control">
              <option value="1" <?= ($p->hak_akses == '1') ? 'selected' : '' ?>>Admin</option>
              <option value="2" <?= ($p->hak_akses == '2') ? 'selected' : '' ?>>Pegawai</option>
              <option value="3" <?= ($p->hak_akses == '3') ? 'selected' : '' ?>>Kepala Sekolah</option>
              <option value="4" <?= ($p->hak_akses == '4') ? 'selected' : '' ?>>Bendahara</option>
            </select>
          </div>

          <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
      <?php endforeach; ?>
    </div>
  </div>
</div>