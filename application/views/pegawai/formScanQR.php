<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Absensi Scan QR</h1>
  </div>
    <?= $this->session->flashdata('pesan'); ?>
  <div class="card" style="width: 60%; margin-bottom:100px;">
    <div class="card-body">

      <form method="post" action="<?= site_url('pegawai/dataAbsensi/do_attend'); ?>">

        <div class="form-group">
          <label for="">Nama</label>
          <input type="text" class="form-control" value="<?= $pegawai->nama_pegawai; ?>" disabled>
        </div>

        <div class="form-group">
          <label for="">NIP</label>
          <input type="text" class="form-control" value="<?= $pegawai->nip; ?>" disabled>
          <input type="hidden" name="nip" value="<?= $pegawai->nip; ?>">
        </div>

        <div class="form-group">
          <label for="">Jabatan</label>
          <input type="text" class="form-control" value="<?= $pegawai->jabatan; ?>" disabled>
        </div>

        <div class="form-group">
          <label for="">Lokasi</label>
          <input type="text" class="form-control" id="location" disabled>
        </div>

        <input type="hidden" name="lat" id="lat">
        <input type="hidden" name="lon" id="lon">

        <button type="submit" class="btn btn-success mt-3">✅ Attend</button>
      </form>
    </div>
  </div>
</div>

<!-- Script untuk deteksi lokasi -->
<script>
navigator.geolocation.getCurrentPosition(function(position) {
  document.getElementById('lat').value = position.coords.latitude;
  document.getElementById('lon').value = position.coords.longitude;
  document.getElementById('location').value = position.coords.latitude + ", " + position.coords.longitude;
}, function(error) {
  console.error(error);
  document.getElementById('location').value = "❌ Gagal mendeteksi lokasi!";
});
</script>
