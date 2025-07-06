        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-5">
            <h1 class="h3 mt-1 mb-0 text-gray-800"><?= $title; ?><br /><?php echo date('l, d-m-Y'); ?></h1>
          </div>
          <!-- Content Row -->
          <div class="row">
            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Data Pegawai</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $pegawai; ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Data Admin</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $admin; ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-user-cog fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Data Jabatan</div>
                      <div class="row no-gutters align-items-center">
                        <div class="col-auto">
                          <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?= $jabatan; ?></div>
                        </div>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-briefcase fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- Pending Requests Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Data Kehadiran</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $kehadiran; ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-comments fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Content Row -->
          <div class="container" align="center" style="margin-bottom: 100px;;">
            <div class="card" style="width:auto">
              <div class="card-header">
                Peta
              </div>
              <div class="card-body">
                <!-- Peta dengan https://google-map-generator.com/ -->
                <class="embed-responsive embed-responsive-16by9">
                  <iframe width="850" height="300" id="gmap_canvas" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.539111875242!2d112.6840018!3d-7.804961!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7d4f213de38a1%3A0xbc8227cfe515b9da!2sMTs%20Nurul%20Mubtadiin!5e0!3m2!1sid!2sid!4v1751786535812!5m2!1sid!2sid" frameborder=" 0" scrolling="no" marginheight="0" marginwidth="0"></iframe>

              </div>
            </div>
          </div>
        </div>
        </div>
        <!-- /.container-fluid -->
        </div>
        <!-- End of Main Content -->