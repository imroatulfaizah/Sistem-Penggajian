<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Absensi Scan QR (HTML5 QRCode)</h1>
  </div>

  <?= $this->session->flashdata('pesan'); ?>

  <div class="card" style="width: 60%; margin-bottom:100px;">
    <div class="card-body">
    <div class="row mb-3">
    <div class="col-md-12 text-center">
        <?php if ($this->session->userdata('testing_radius')): ?>
            <div class="alert alert-warning">
                <strong>MODE TESTING AKTIF:</strong> Radius bebas (Unlimited).
                <br>
                <a href="<?= base_url('pegawai/DataAbsensi/set_radius_mode/normal') ?>" class="btn btn-sm btn-secondary mt-2">
                    Matikan Mode Testing (Kembali ke Normal)
                </a>
            </div>
        <?php else: ?>
            <a href="<?= base_url('pegawai/DataAbsensi/set_radius_mode/unlimited') ?>" class="btn btn-sm btn-outline-warning">
                <i class="fas fa-wrench"></i> Aktifkan Mode Testing (Bypass Radius)
            </a>
        <?php endif; ?>
    </div>
</div>
      <form id="attendanceForm" method="post" action="<?= site_url('pegawai/dataAbsensi/do_attend'); ?>">

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
        <input type="hidden" class="form-control" name="qr_data" id="qr_data" readonly>
        <!-- <input type="hidden" name="qr_data" id="qr_data"> -->

        <!-- Area kamera HTML5 QR Code -->
        <div id="reader" style="width: 100%; max-width: 400px; margin: 20px 0;"></div>

        <button type="button" class="btn btn-primary mt-3" onclick="startScan('in')">Scan & Clock In</button>
        <button type="button" class="btn btn-danger mt-3" onclick="startScan('out')">Scan & Clock Out</button>

      </form>
    </div>
  </div>
</div>

<!-- Load html5-qrcode dari CDN -->
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
// Ambil lokasi saat halaman dibuka
navigator.geolocation.getCurrentPosition(function (position) {
  document.getElementById('lat').value = position.coords.latitude;
  document.getElementById('lon').value = position.coords.longitude;
  document.getElementById('location').value = position.coords.latitude + ", " + position.coords.longitude;
}, function (error) {
  console.error(error);
  document.getElementById('location').value = "❌ Gagal mendeteksi lokasi!";
});

// Fungsi memulai QR scan
function startScan(mode) {

  const form = document.getElementById('attendanceForm');

  if (mode === 'in') {
    form.action = '<?= site_url('pegawai/dataAbsensi/do_attend'); ?>';
  } else {
    form.action = '<?= site_url('pegawai/dataAbsensi/do_attend_out'); ?>';
  }

  const qrReader = new Html5Qrcode("reader");

  qrReader.start(
    { facingMode: "environment" }, // Bisa ganti jadi user jika ingin kamera depan
    {
      fps: 10,
      qrbox: 250
    },
    (decodedText, decodedResult) => {
      // QR berhasil dibaca
      console.log(`QR Code detected: ${decodedText}`);
      document.getElementById('qr_data').value = decodedText;

      // Hentikan kamera
      qrReader.stop().then(ignore => {
        // Submit form setelah berhasil
        document.querySelector('form').submit();
      }).catch(err => {
        console.error('Stop failed', err);
        alert("❌ Gagal menghentikan kamera!");
      });
    },
    (errorMessage) => {
      // QR belum terdeteksi
      // console.warn(errorMessage);
    }
  ).catch((err) => {
    console.error("❌ Kamera gagal dibuka:", err);
    alert("❌ Gagal membuka kamera! Pastikan sudah diizinkan dan tidak sedang digunakan aplikasi lain.");
  });
}
</script>
