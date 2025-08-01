<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-info sidebar sidebar-dark accordion" id="accordionSidebar">
      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="">
        <div class="sidebar-brand-text mx-3">APK LAPORAN PENGGAJIAN</div>
      </a>
      <!-- Divider -->
      <hr class="sidebar-divider my-0">
      <!-- Nav Item - Dashboard -->
      <li class="nav-item active">
        <a class="nav-link" href="<?= base_url('admin/dashboard'); ?>">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>
      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item active">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
          <i class="fas fa-fw fa-database"></i>
          <span>Master Data</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="<?= base_url('admin/dataPegawai') ?>">Data Pegawai</a>
            <a class="collapse-item" href="<?= base_url('admin/dataJabatan'); ?>">Data Jabatan</a>
            <a class="collapse-item" href="<?= base_url('admin/dataKelas'); ?>">Data Kelas</a>
            <a class="collapse-item" href="<?= base_url('admin/dataAkademik'); ?>">Data Akademik</a>
            <a class="collapse-item" href="<?= base_url('admin/dataPelajaran'); ?>">Data Pelajaran</a>
            <a class="collapse-item" href="<?= base_url('admin/dataPenempatan'); ?>">Data Penempatan</a>
          </div>
        </div>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="<?= base_url('admin/dataAbsensi/generate_qr'); ?>">
          <i class="fas fa-fw fa-money-check-alt"></i>
          <span>Generate QR Code</span></a>
      </li>
      <!-- Nav Item - Utilities Collapse Menu -->
      <li class="nav-item active">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities" aria-expanded="true" aria-controls="collapseUtilities">
          <i class="fas fa-fw fa-money-check-alt"></i>
          <span>Rekap Data</span>
        </a>
        <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="<?= base_url('admin/dataAbsensi'); ?>">Rekap Data Absensi</a>
            <a class="collapse-item" href="<?= base_url('admin/dataInsentif'); ?>">Rekap Data Insentif</a>
            <a class="collapse-item" href="<?= base_url('admin/dataPenggajian'); ?>">Data Gaji</a>
          </div>
        </div>
      </li>
      <!-- Nav Item - Pages Collapse Menu -->
      <li class="nav-item active">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
          <i class="far fa-fw fa-copy"></i>
          <span>Laporan</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
          <div class="bg-white py-2 collapse-inner rounded">
            <a class="collapse-item" href="<?= base_url('admin/laporanGaji'); ?>">Laporan Gaji</a>
            <a class="collapse-item" href="<?= base_url('admin/laporanAbsensi'); ?>">Laporan Absensi</a>
            <a class="collapse-item" href="<?= base_url('admin/slipGaji'); ?>">Slip Gaji</a>
          </div>
        </div>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="<?= base_url('admin/GantiPassword'); ?>">
          <i class="fas fa-fw fa-lock"></i>
          <span>Ganti Password</span></a>
      </li>
      <!-- Nav Item - Tables -->
      <!-- <li class="nav-item active">
        <a class="nav-link" href="<?= base_url('welcome/logout'); ?>" onclick="return confirm('Yakin akan logout?')">
          <i class="fas fa-fw fa-sign-out-alt"></i>
          <span>Logout</span></a>
      </li>-->
      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>
    </ul>
    <!-- End of Sidebar -->
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <!-- Main Content -->
      <div id="content">
        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>
          <h4 class="font-weight-bold"></h4>
          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">
            <div class="topbar-divider d-none d-sm-block"></div>
            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Selamat Datang <?= $this->session->userdata('nama_pegawai'); ?></span>
                <img class="img-profile rounded-circle" src="<?= base_url('assets/photo/') . $this->session->userdata('photo'); ?>">
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="<?= base_url('welcome/logout'); ?>" onclick="return confirm('Yakin akan logout?')">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>
          </ul>
        </nav>
        <!-- End of Topbar -->