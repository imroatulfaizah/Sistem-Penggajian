<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
  </div>

  <a class="btn btn-sm btn-danger mb-3" target="_blank" href="<?= base_url('pegawai/dataPenempatan/printData'); ?>">
    <i class="fas fa-print"></i> Print Jadwal
  </a>

  <?= $this->session->flashdata('pesan'); ?>

  <!-- ================== MODE TESTING RADIUS (BARU) ================== -->
  <div class="row mb-4">
    <div class="col-md-12 text-center">
      <?php if ($this->session->userdata('testing_radius')): ?>
        <div class="alert alert-warning d-inline-block">
          <strong>MODE TESTING RADIUS AKTIF:</strong> Anda bisa Clock In/Out dari mana saja (radius bypass).<br>
          <a href="<?= base_url('pegawai/dataAbsensi/set_radius_mode/normal') ?>" class="btn btn-sm btn-secondary mt-2">
            <i class="fas fa-lock"></i> Matikan Mode Testing (Kembali Normal)
          </a>
        </div>
      <?php else: ?>
        <a href="<?= base_url('pegawai/dataAbsensi/set_radius_mode/unlimited') ?>" class="btn btn-sm btn-outline-warning">
          <i class="fas fa-wrench"></i> Aktifkan Mode Testing Radius (Bypass Lokasi)
        </a>
      <?php endif; ?>
    </div>
  </div>
  <!-- ============================================================== -->

  <!-- FILTER HARI -->
  <form method="get" class="mb-4">
    <div class="row">
      <div class="col-md-3">
        <select name="hari" class="form-control">
          <option value="">-- Semua Hari --</option>
          <?php 
          $daftar_hari = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
          foreach($daftar_hari as $h): ?>
            <option value="<?= $h ?>" <?= ($selected_hari == $h) ? 'selected' : '' ?>><?= $h ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="<?= base_url('pegawai/dataPenempatan') ?>" class="btn btn-secondary">Reset</a>
      </div>
    </div>
  </form>

  <table class="table table-bordered table-striped">
    <thead class="thead-dark">
      <tr>
        <th>No</th>
        <th>Pelajaran</th>
        <th>Kelas</th>
        <th>Akademik</th>
        <th>Hari</th>
        <th>Jam Mulai</th>
        <th>Jam Akhir</th>
        <th>Total Jam</th>
        <th>Keterangan</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $no = $offset + 1;
      date_default_timezone_set('Asia/Jakarta');
      $hari_ini = date('l');
      $jam_sekarang = date('H:i:s');

      $map = [
        'Sunday'    => 'Minggu',
        'Monday'    => 'Senin',
        'Tuesday'   => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday'  => 'Kamis',
        'Friday'    => 'Jumat',
        'Saturday'  => 'Sabtu',
      ];
      $hari_sekarang = $map[$hari_ini] ?? $hari_ini;

      foreach ($penempatan as $g):
        $isAktif = ($hari_sekarang == $g->hari && $jam_sekarang >= $g->jam_mulai && $jam_sekarang <= $g->jam_akhir);
        $disabled = $isAktif ? '' : 'disabled';
      ?>
        <tr>
          <td><?= $no++; ?></td>
          <td><?= htmlspecialchars($g->nama_pelajaran) ?></td>
          <td><?= htmlspecialchars($g->nama_kelas) ?></td>
          <td><?= htmlspecialchars($g->nama_akademik) ?></td>
          <td><?= $g->hari ?></td>
          <td><?= $g->jam_mulai ?></td>
          <td><?= $g->jam_akhir ?></td>
          <td><?= $g->total_jam ?></td>
          <td><?= htmlspecialchars($g->keterangan ?? '-') ?></td>
          <td class="text-center">
            <form style="display:inline-block;" method="post" action="<?= site_url('pegawai/dataAbsensi/clockIn/'.$g->id_penempatan) ?>" class="form-absensi">
              <input type="hidden" name="lat" class="lat">
              <input type="hidden" name="lon" class="lon">
              <button type="button" class="btn btn-sm btn-primary btn-clockin <?= $disabled ?>">
                <?php if ($this->session->userdata('testing_radius')): ?>
                  <i class="fas fa-wrench"></i> Clock In (Testing)
                <?php else: ?>
                  Clock In
                <?php endif; ?>
              </button>
            </form>

            <form style="display:inline-block;" method="post" action="<?= site_url('pegawai/dataAbsensi/clockOut/'.$g->id_penempatan) ?>" class="form-absensi">
              <input type="hidden" name="lat" class="lat">
              <input type="hidden" name="lon" class="lon">
              <button type="button" class="btn btn-sm btn-success btn-clockout <?= $disabled ?>">
                <?php if ($this->session->userdata('testing_radius')): ?>
                  <i class="fas fa-wrench"></i> Clock Out (Testing)
                <?php else: ?>
                  Clock Out
                <?php endif; ?>
              </button>
            </form>

            <a href="<?= site_url('pegawai/dataAbsensi/detailAbsensi/'.$g->id_penempatan) ?>" class="btn btn-sm btn-info">Detail</a>

            <?php if (!$isAktif): ?>
              <small class="text-danger d-block">Di luar jadwal</small>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <!-- PAGINATION -->
  <div class="mt-4">
    <?= $pagination; ?>
  </div>

</div>

<!-- Script geolocation -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".btn-clockin, .btn-clockout").forEach(btn => {
      btn.addEventListener("click", function () {
        if (this.hasAttribute('disabled')) return;

        const form = this.closest("form");
        navigator.geolocation.getCurrentPosition(pos => {
          form.querySelector(".lat").value = pos.coords.latitude;
          form.querySelector(".lon").value = pos.coords.longitude;
          form.submit();
        }, err => {
          alert("Gagal mengambil lokasi: " + err.message + "\nPastikan GPS aktif dan izinkan lokasi.");
        });
      });
    });
  });
</script>