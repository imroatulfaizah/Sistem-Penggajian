<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
  </div>

  <a class="btn btn-sm btn-danger mb-3" target="blank" href="<?= base_url('pegawai/dataPenempatan/printData/'); ?>">
    <i class="fas fa-print"></i> Print Data
  </a>

  <!-- MODE TESTING RADIUS (sama seperti di halaman absensi harian) -->
  <div class="row mb-3">
    <div class="col-md-12 text-center">
      <?php if ($this->session->userdata('testing_radius')): ?>
        <div class="alert alert-warning d-inline-block">
          <strong>MODE TESTING RADIUS AKTIF!</strong> Anda bisa Clock-In/Out dari mana saja (tanpa batas radius).<br>
          <a href="<?= base_url('pegawai/DataAbsensi/set_radius_mode/normal') ?>" class="btn btn-sm btn-secondary mt-2">
            Matikan Mode Testing
          </a>
        </div>
      <?php else: ?>
        <a href="<?= base_url('pegawai/DataAbsensi/set_radius_mode/unlimited') ?>" class="btn btn-sm btn-outline-warning">
          Aktifkan Mode Testing Radius (Bypass 100m)
        </a>
      <?php endif; ?>
    </div>
  </div>

  <?= $this->session->flashdata('pesan'); ?>

  <!-- FILTER PER HARI -->
  <form method="get" action="">
    <div class="row mb-3">
      <div class="col-md-3">
        <select name="hari" class="form-control">
          <option value="">-- Filter Hari --</option>
          <option value="Senin"   <?= ($this->input->get('hari') == 'Senin') ? 'selected' : ''; ?>>Senin</option>
          <option value="Selasa"  <?= ($this->input->get('hari') == 'Selasa') ? 'selected' : ''; ?>>Selasa</option>
          <option value="Rabu"    <?= ($this->input->get('hari') == 'Rabu') ? 'selected' : ''; ?>>Rabu</option>
          <option value="Kamis"   <?= ($this->input->get('hari') == 'Kamis') ? 'selected' : ''; ?>>Kamis</option>
          <option value="Jumat"   <?= ($this->input->get('hari') == 'Jumat') ? 'selected' : ''; ?>>Jumat</option>
          <option value="Sabtu"   <?= ($this->input->get('hari') == 'Sabtu') ? 'selected' : ''; ?>>Sabtu</option>
          <option value="Minggu"  <?= ($this->input->get('hari') == 'Minggu') ? 'selected' : ''; ?>>Minggu</option>
        </select>
      </div>
      <div class="col-md-2">
        <button class="btn btn-primary" type="submit">Filter</button>
        <a href="<?= base_url('pegawai/dataPenempatan'); ?>" class="btn btn-secondary">Reset</a>
      </div>
    </div>
  </form>

  <table class="table table-bordered table-stiped mt-2">
    <tr>
      <th class="text-center">No</th>
      <th class="text-center">Pelajaran</th>
      <th class="text-center">Kelas</th>
      <th class="text-center">Akademik</th>
      <th class="text-center">NIP</th>
      <th class="text-center">Hari</th>
      <th class="text-center">Jam Mulai</th>
      <th class="text-center">Jam Akhir</th>
      <th class="text-center">Total Jam</th>
      <th class="text-center">Keterangan</th>
      <th class="text-center">Action</th>
    </tr>

    <?php
    $no = 1;
    date_default_timezone_set('Asia/Jakarta');
    $hari_ini = date('l'); // Sunday, Monday, dst
    $jam_sekarang = date('H:i:s');

    $nama_hari_map = [
      'Sunday' => 'Minggu',
      'Monday' => 'Senin',
      'Tuesday' => 'Selasa',
      'Wednesday' => 'Rabu',
      'Thursday' => 'Kamis',
      'Friday' => 'Jumat',
      'Saturday' => 'Sabtu',
    ];

    $hari_sekarang = $nama_hari_map[$hari_ini] ?? $hari_ini;

    // Ambil filter GET
    $filterHari = $this->input->get('hari') ?? '';

    foreach ($penempatan as $g) :

      // Filter hari jika dipilih
      if ($filterHari && $g->hari != $filterHari) {
        continue;
      }

      $isAktif = (
        $hari_sekarang == $g->hari &&
        $jam_sekarang >= $g->jam_mulai &&
        $jam_sekarang <= $g->jam_akhir
      );

      $disabled = $isAktif ? '' : 'disabled';
    ?>
      <tr>
        <td><?= $no++; ?></td>
        <td><?= $g->nama_pelajaran; ?></td>
        <td><?= $g->nama_kelas; ?></td>
        <td><?= $g->id_akademik; ?></td>
        <td><?= $g->nip; ?></td>
        <td><?= $g->hari; ?></td>
        <td><?= $g->jam_mulai; ?></td>
        <td><?= $g->jam_akhir; ?></td>
        <td><?= $g->total_jam; ?></td>
        <td><?= $g->keterangan; ?></td>
        <td>
          <center>
            <form style="display:inline-block;" method="post" action="<?= site_url('pegawai/dataAbsensi/clockIn/' . $g->id_penempatan); ?>" class="form-absensi">
              <input type="hidden" name="lat" class="lat">
              <input type="hidden" name="lon" class="lon">
              <button type="button" class="btn btn-sm btn-primary btn-clockin" <?= $disabled; ?>>
                Clock In <i class="fas fa-clock"></i>
              </button>
            </form>

            <form style="display:inline-block;" method="post" action="<?= site_url('pegawai/dataAbsensi/clockOut/' . $g->id_penempatan); ?>" class="form-absensi">
              <input type="hidden" name="lat" class="lat">
              <input type="hidden" name="lon" class="lon">
              <button type="button" class="btn btn-sm btn-success btn-clockout" <?= $disabled; ?>>
                Clock Out <i class="fas fa-clock"></i>
              </button>
            </form>

            <form style="display:inline-block;" method="post" action="<?= site_url('pegawai/dataAbsensi/detailAbsensi/' . $g->id_penempatan); ?>">
              <input type="hidden" name="lat" class="lat">
              <input type="hidden" name="lon" class="lon">
              <button type="submit" class="btn btn-sm btn-primary">
                Details <i class="fas fa-info-circle"></i>
              </button>
            </form>

            <?php if (!$isAktif): ?>
              <small class="text-danger d-block mt-1">Diluar jadwal</small>
            <?php endif; ?>
          </center>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const buttons = document.querySelectorAll(".btn-clockin, .btn-clockout");

    buttons.forEach(button => {
      button.addEventListener("click", function () {
        if (button.disabled) return;

        const form = button.closest("form");

        navigator.geolocation.getCurrentPosition(
          function (position) {
            const lat = form.querySelector(".lat");
            const lon = form.querySelector(".lon");

            lat.value = position.coords.latitude;
            lon.value = position.coords.longitude;

            form.submit();
          },
          function (error) {
            alert("Gagal mengambil lokasi: " + error.message);
          }
        );
      });
    });
  });
</script>
