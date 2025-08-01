<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title><?= $title; ?></title>

  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-info">
  <div class="container">
    <!-- Outer Row -->
    <div class="row justify-content-center">
      <div class="col-xl-5 col-lg-6 col-md-7">
        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-12">
                <div class="p-5">
                  <div class="text-center">
                    <img src="<?= base_url('assets/img/mts.png'); ?>" alt="Logo MTS Nurul Mubtadiin" style="width: 100px; height: auto; margin-bottom: 15px;">
                    <h1 class="h4 text-gray-900 mb-4">Website Penggajian <br> <b>MTS Nurul Mubtadiin</b></h1>
                  </div>
                  <?= $this->session->flashdata('pesan'); ?>
                  <form class="user" method="post" action="<?= base_url('welcome'); ?>">
                    <div class="form-group">
                      <input type="text" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter Username..." name="username">
                      <?= form_error('username', '<div class="text-small text-danger ml-2">', '</div>') ?>
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password" name="password">
                      <?= form_error('password', '<div class="text-small text-danger ml-2">', '</div>') ?>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-primary btn-user btn-block">Login</button>
                  </form>
                  <!--
                  <div class="text-center mt-3">
                    <a class="small" href="">Forgot Password?</a>
                  </div>-->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>
</body>

</html>