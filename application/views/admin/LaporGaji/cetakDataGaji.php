<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $title; ?></title>

  <style type="text/css">
    body {
      font-family: Arial;
      color: black;
    }
  </style>
</head>

<body>

  <center>
    <img src="<?= base_url('assets/img/mts.png'); ?>" alt="Logo MTS Nurul Mubtadiin" style="width: 100px; height: auto; margin-bottom: 15px;">
    <h1>MTS Nurul Mubtadiin</h1>
    <h2>Daftar Gaji Pegawai</h2>
  </center>

  <?php
  if ((isset($_GET['bulan']) && $_GET['bulan'] != '') && (isset($_GET['tahun']) && $_GET['tahun'] != '')) {
    $bulan = $_GET['bulan'];
    $tahun = $_GET['tahun'];
    $bulanTahun = $bulan . $tahun;
  } else {
    $bulan = date('m');
    $tahun = date('Y');
    $bulanTahun = $bulan . $tahun;
  }
  ?>

  <table>
    <tr>
      <td>Bulan</td>
      <td>:</td>
      <td><?= $bulan; ?></td>
    </tr>
    <tr>
      <td>Tahun</td>
      <td>:</td>
      <td><?= $tahun; ?></td>
    </tr>
  </table>

  <table class="table table-bordered table-striped">
    <tr>
      <th class="text-center">No</th>
      <th class="text-center">NIP</th>
      <th class="text-center">Nama Pegawai</th>
      <th class="text-center">Jenis Kelamin</th>
      <th class="text-center">Jabatan</th>
      <th class="text-center">Tunjangan Jabatan</th>
      <th class="text-center">Tunjangan Transport</th>
      <th class="text-center">Upah Mengajar</th>
      <th class="text-center">Total Gaji</th>
    </tr>

    <?php foreach ($jam as $p) {
      $jam = $p->total_jam;
    } ?>
    <?php
    $no = 1;
    foreach ($cetakGaji as $g) : ?>
      <tr>
        <td><?= $no++; ?></td>
        <td><?= $g->nip; ?></td>
        <td><?= $g->nama_pegawai; ?></td>
        <td><?= $g->jenis_kelamin; ?></td>
        <td><?= $g->nama_jabatan; ?></td>
        <td>Rp. <?= number_format($g->tunjangan_jabatan, 0, ',', '.'); ?>,-</td>
        <td>Rp. <?= number_format($g->tunjangan_transport, 0, ',', '.'); ?>,-</td>
        <td>Rp. <?= number_format($g->upah_mengajar, 0, ',', '.'); ?>,-</td>
        <?php $total_gaji = $g->tunjangan_jabatan + $g->tunjangan_transport + $g->upah_mengajar * $jam; ?>
        <td>Rp. <?= number_format($total_gaji, 0, ',', '.'); ?>,-</td>
      </tr>

    <?php endforeach; ?>
  </table>

  <table width="100%;">
    <tr>
      <td></td>
      <td width="200px">
        <p>Pasuruan, <?= date("d M Y"); ?> <br> Bendahara</p>
        <br><br>
        <p>__________________</p>
        <p>NISWATUN H.</p>
      </td>
    </tr>
  </table>

</body>

</html>

<script type="text/javascript">
  window.print();
</script>