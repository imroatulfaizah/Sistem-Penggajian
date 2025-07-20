<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title ?? 'QR Code Hari Ini'; ?></h1>
  </div>

  <?= $this->session->flashdata('pesan'); ?>

  <div class="card shadow mb-4" style="max-width: 400px; margin: auto;">
    <div class="card-body text-center">

      <h5 class="mb-3">QR Code Absensi</h5>

      <img src="<?= $qr_file ?>" width="250" alt="QR Code Absensi" class="img-fluid mb-3" />

      <p class="text-muted mb-0" style="font-size: 13px;">
        Kode QR ini aktif untuk sesi:
        <strong><?= $session_label ?></strong><br>
        Akan berubah otomatis pada pukul <strong><?= $next_shift_time ?></strong>
      </p>

    </div>
  </div>

</div>
