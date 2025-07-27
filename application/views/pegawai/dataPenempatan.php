<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
  </div>

  <a class="btn btn-sm btn-success mb-3" href="<?= base_url('pegawai/dataPenempatan/tambahData/'); ?>"><i class="fas fa-clock"></i> Tambah Data</a>
  <a class="btn btn-sm btn-danger mb-3" target="blank" href="<?= base_url('pegawai/dataPenempatan/printData/'); ?>"><i class="fas fa-print"></i> Print Data</a>
  <?= $this->session->flashdata('pesan'); ?>

  <table class="table table-bordered table-stiped mt-2">
    <tr>
        <th class="text-center">No</th>
        <th class="text-center">ID Pelajaran</th>
        <th class="text-center">ID Kelas</th>
        <th class="text-center">ID Akademik</th>
        <th class="text-center">NIP</th>
        <th class="text-center">Jam Mulai</th>
        <th class="text-center">Jam Akhir</th>
        <th class="text-center">Total Jam</th>
        <th class="text-center">Keterangan</th>
        <th class="text-center">Action</th>
    </tr>

    <?php
    $no = 1;
    foreach ($penempatan as $g) : ?>
      <tr>
        <td><?= $no++; ?></td>
        <td><?= $g->id_pelajaran; ?></td>
        <td><?= $g->id_kelas; ?></td>
        <td><?= $g->id_akademik; ?></td>
        <td><?= $g->nip; ?></td>
        <td><?= $g->jam_mulai; ?></td>
        <td><?= $g->jam_akhir; ?></td>
        <td><?= $g->total_jam; ?></td>
        <td><?= $g->keterangan; ?></td>
        <td>
          <center>
            <form style="display:inline-block;" method="post" action="<?= site_url('pegawai/dataAbsensi/clockIn/' . $g->id_penempatan); ?>" class="form-absensi">
              <input type="hidden" name="lat" class="lat">
              <input type="hidden" name="lon" class="lon">
              <button type="button" class="btn btn-sm btn-primary btn-clockin">
                Clock In <i class="fas fa-clock"></i>
              </button>
            </form>

            <form style="display:inline-block;" method="post" action="<?= site_url('pegawai/dataAbsensi/clockOut/' . $g->id_penempatan); ?>" class="form-absensi">
              <input type="hidden" name="lat" class="lat">
              <input type="hidden" name="lon" class="lon">
              <button type="button" class="btn btn-sm btn-success btn-clockout">
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
        const form = button.closest("form");

        navigator.geolocation.getCurrentPosition(
          function (position) {
            const lat = form.querySelector(".lat");
            const lon = form.querySelector(".lon");

            lat.value = position.coords.latitude;
            lon.value = position.coords.longitude;

            form.submit(); // submit hanya setelah lokasi berhasil diisi
          },
          function (error) {
            alert("Gagal mengambil lokasi: " + error.message);
          }
        );
      });
    });
  });
</script>

