<!-- Begin Page Content -->
<div class="container-fluid py-4">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
  </div>

  <!-- Alert Selamat Datang -->
  <div class="alert alert-success alert-dismissible fade show rounded shadow-sm mb-5 border-0" 
       style="background: linear-gradient(135deg, #d4e4d4 0%, #a8caba 100%); color: #2d6a4f;" role="alert">
    <strong>Selamat datang kembali!</strong> Anda login sebagai <strong>guru</strong>.
    <button type="button" class="close" data-dismiss="alert">×</button>
  </div>

  <!-- Card Profil Guru — TEMA SAGE GREEN GRADIENT -->
  <?php foreach ($pegawai as $p) : ?>
  <div class="card border-0 shadow-lg mx-auto" style="max-width: 1100px; border-radius: 24px; overflow: hidden;">
    
    <!-- Header Gradasi Sage Premium -->
    <div class="card-header text-white text-center py-5 position-relative overflow-hidden"
         style="background: linear-gradient(135deg, #8FBC8F 0%, #6B8E23 50%, #556B2F 100%);">
      <div class="position-absolute opacity-10" style="top: -50px; right: -50px;">
        <i class="fas fa-leaf fa-10x"></i>
      </div>
      <h4 class="mb-0 font-weight-bold text-shadow">
        <i class="fas fa-user-graduate mr-3"></i> Profil Guru
      </h4>
    </div>

    <div class="card-body bg-gradient" style="background: linear-gradient(to bottom, #f8fcf8 0%, #e8f5e9 100%);">

      <div class="row align-items-start">

        <!-- Foto Guru dengan Border Sage -->
        <div class="col-lg-3 text-center mb-4 mb-lg-0">
          <?php 
          $photo = !empty($p->photo) && file_exists('assets/photo/'.$p->photo) 
                   ? base_url('assets/photo/'.$p->photo) 
                   : base_url('assets/photo/default-avatar.png');
          ?>
          <div class="position-relative d-inline-block">
            <img src="<?= $photo; ?>" 
                 alt="Foto <?= $p->nama_pegawai; ?>"
                 class="img-fluid rounded-circle shadow-lg border"
                 style="width: 240px; height: 240px; object-fit: cover; 
                        border: 10px solid #a8d5ba !important;
                        box-shadow: 0 10px 30px rgba(107, 142, 35, 0.3) !important;">
            
            <!-- Badge Aktif Sage -->
            <div class="position-absolute bottom-0 right-0 transform translate-x-4 translate-y-4">
              <span class="badge badge-success px-4 py-3 rounded-pill shadow" 
                    style="background: #6b8e23; font-size: 1rem;">
                <i class="fas fa-check-circle mr-2"></i>Aktif
              </span>
            </div>
          </div>
        </div>

        <!-- Data Guru -->
        <div class="col-lg-9">
          <h3 class="mb-4" style="color: #2d6a4f; font-weight: 800;">
            <?= $p->nama_pegawai; ?>
          </h3>
          
          <div class="row">
            <!-- Kolom Kiri -->
            <div class="col-md-6">
              <div class="d-flex align-items-start mb-4">
                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mr-3"
                     style="width: 42px; height: 42px; flex-shrink: 0;">
                  <i class="fas fa-id-card"></i>
                </div>
                <div>
                  <small class="text-muted font-weight-bold text-uppercase">NIP</small>
                  <p class="mb-0 font-weight-bold" style="color: #1b4332;"><?= $p->nip; ?></p>
                </div>
              </div>

              <div class="d-flex align-items-start mb-4">
                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mr-3"
                     style="width: 42px; height: 42px; background: #8FBC8F;">
                  <i class="fas fa-briefcase"></i>
                </div>
                <div>
                  <small class="text-muted font-weight-bold text-uppercase">Jabatan</small>
                  <p class="mb-0 font-weight-bold" style="color: #2d6a4f;"><?= $p->jabatan; ?></p>
                </div>
              </div>

              <div class="d-flex align-items-start mb-4">
                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mr-3"
                     style="width: 42px; height: 42px; background: #6b8e23;">
                  <i class="fas fa-calendar-alt"></i>
                </div>
                <div>
                  <small class="text-muted font-weight-bold text-uppercase">Tanggal Masuk</small>
                  <p class="mb-0 font-weight-bold" style="color: #1b4332;">
                    <?= date('d F Y', strtotime($p->tanggal_masuk)); ?>
                  </p>
                </div>
              </div>

              <div class="d-flex align-items-start mb-4">
                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mr-3"
                     style="width: 42px; height: 42px; background: #9acd32;">
                  <i class="fas fa-user-tag"></i>
                </div>
                <div>
                  <small class="text-muted font-weight-bold text-uppercase">Status</small>
                  <span class="badge badge-lg px-4 py-2" 
                        style="background: <?= $p->status == 'Tetap' ? '#6b8e23' : '#9acd32'; ?>; color: white;">
                    <?= $p->status; ?>
                  </span>
                </div>
              </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="col-md-6">
              <div class="d-flex align-items-start mb-4">
                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mr-3"
                     style="width: 42px; height: 42px; background: #8FBC8F;">
                  <i class="fas fa-phone"></i>
                </div>
                <div>
                  <small class="text-muted font-weight-bold text-uppercase">No. HP</small>
                  <p class="mb-0 font-weight-bold" style="color: #2d6a4f;"><?= $p->no_hp ?: '-'; ?></p>
                </div>
              </div>

              <div class="d-flex align-items-start mb-4">
                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mr-3"
                     style="width: 42px; height: 42px; background: #6b8e23;">
                  <i class="fas fa-envelope"></i>
                </div>
                <div>
                  <small class="text-muted font-weight-bold text-uppercase">Email</small>
                  <p class="mb-0 font-weight-bold text-break" style="color: #1b4332;"><?= $p->email ?: '-'; ?></p>
                </div>
              </div>

              <div class="d-flex align-items-start mb-4">
                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mr-3"
                     style="width: 42px; height: 42px; background: #9acd32;">
                  <i class="fas fa-graduation-cap"></i>
                </div>
                <div>
                  <small class="text-muted font-weight-bold text-uppercase">Pendidikan</small>
                  <p class="mb-0 font-weight-bold" style="color: #2d6a4f;"><?= $p->pendidikan ?: '-'; ?></p>
                </div>
              </div>

              <div class="d-flex align-items-start">
                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mr-3"
                     style="width: 42px; height: 42px; background: #8FBC8F;">
                  <i class="fas fa-home"></i>
                </div>
                <div>
                  <small class="text-muted font-weight-bold text-uppercase">Alamat</small>
                  <p class="mb-0 font-weight-bold text-break" style="color: #1b4332;"><?= $p->alamat ?: '-'; ?></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Footer Sage -->
    <div class="card-footer text-center text-white py-4" 
         style="background: linear-gradient(135deg, #8FBC8F 0%, #6B8E23 100%); font-size: 0.95rem;">
      <i class="fas fa-leaf mr-2"></i>
      Sistem Informasi Penggajian & Absensi Guru • Sekolah Alam Indonesia
    </div>
  </div>
  <?php endforeach; ?>

</div>
<!-- /.container-fluid -->