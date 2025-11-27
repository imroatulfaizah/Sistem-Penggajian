<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
    </div>

    <div class="row mb-4 justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card shadow border-left-primary">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Mode Testing (Generate Manual)</h6>
                </div>
                <div class="card-body">
                    <p class="mb-3">Gunakan tombol ini jika jam server tidak sesuai dengan jam testing Anda.</p>
                    
                    <a href="<?= base_url('admin/DataAbsensi/generate_qr/IN') ?>" class="btn btn-success btn-icon-split mr-2 mb-2">
                        <span class="icon text-white-50">
                            <i class="fas fa-sign-in-alt"></i>
                        </span>
                        <span class="text">Force QR Masuk (IN)</span>
                    </a>

                    <a href="<?= base_url('admin/DataAbsensi/generate_qr/OUT') ?>" class="btn btn-danger btn-icon-split mb-2">
                        <span class="icon text-white-50">
                            <i class="fas fa-sign-out-alt"></i>
                        </span>
                        <span class="text">Force QR Pulang (OUT)</span>
                    </a>

                    <hr>
                    
                    <a href="<?= base_url('admin/DataAbsensi/generate_qr') ?>" class="btn btn-secondary btn-sm">
                        <i class="fas fa-sync"></i> Reset ke Mode Otomatis (Sesuai Jam)
                    </a>

                    
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary">
                    <h6 class="m-0 font-weight-bold text-white text-center">Scan QR Code Ini</h6>
                </div>
                <div class="card-body text-center">
                    
                    <div class="mb-4">
                        <img src="<?= $qr_file; ?>" alt="QR Code Absensi" style="width: 250px; height: 250px; border: 1px solid #ddd; padding: 10px;">
                    </div>

                    <h4 class="font-weight-bold text-dark">
                        Status: <span class="badge badge-warning"><?= $type; ?></span>
                    </h4>
                    <p class="text-muted">
                        Sesi: <strong><?= $session_label; ?></strong><br>
                        Berlaku sampai: <strong><?= $next_shift_time; ?></strong>
                    </p>
                    
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle"></i> Arahkan kamera HP / Scanner ke kode di atas untuk melakukan absensi.
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>