<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title></title>

  <style type="text/css">
    body { font-family: Arial; color: black; }
  </style>
</head>

<body>

  <center>
    <img src="<?= base_url('assets/img/mts.png'); ?>" style="width: 100px; margin-bottom: 15px;">
    <h1>MTs NURUL MUBTADIIN</h1>
    <h2>Daftar Gaji Pegawai</h2>
  </center>

  <?php
    if(isset($_GET['bulan']) && $_GET['bulan'] != '' && isset($_GET['tahun']) && $_GET['tahun'] != ''){
      $bulan = $_GET['bulan'];
      $tahun = $_GET['tahun'];
    } else {
      $bulan = date('m');
      $tahun = date('Y');
    }
  ?>

  <table>
    <tr><td>Bulan</td><td>:</td><td><?= $bulan; ?></td></tr>
    <tr><td>Tahun</td><td>:</td><td><?= $tahun; ?></td></tr>
  </table>

  <table class="table table-bordered table-striped">
    <tr>
      <th>No</th>
      <th>NIP</th>
      <th>Nama Pegawai</th>
      <th>Jenis Kelamin</th>
      <th>Jabatan</th>
      <th>Tunjangan Jabatan</th>
      <th>Tunjangan Transport</th>
      <th>Upah Mengajar</th>
      <th>Total Gaji</th>
    </tr>

    <?php 
      $no = 1;
      foreach ($cetakGaji as $g) : 
        $hadir      = $g->hadir;          // jumlah hari hadir
        $total_jam  = $g->total_jam;      // jam mengajar real
        $tjab       = $g->tunjangan_jabatan;
        $ttrans     = $g->tunjangan_transport;
        $upah       = $g->upah_mengajar;

        // Rumus total gaji
        $total_gaji = $tjab + ($ttrans * $hadir) + ($upah * $total_jam);
    ?>

    <tr>
      <td><?= $no++; ?></td>
      <td><?= $g->nip; ?></td>
      <td><?= $g->nama_pegawai; ?></td>
      <td><?= $g->jenis_kelamin; ?></td>
      <td><?= $g->nama_jabatan; ?></td>
      <td>Rp. <?= number_format($tjab, 0, ',', '.'); ?>,-</td>
      <td>Rp. <?= number_format($ttrans, 0, ',', '.'); ?>,-</td>
      <td>Rp. <?= number_format($upah, 0, ',', '.'); ?> x <?= $total_jam ?></td>
      <td>Rp. <?= number_format($total_gaji, 0, ',', '.'); ?>,-</td>
    </tr>

    <?php endforeach; ?>
  </table>

  <table width="100%">
    <tr>
      <td></td>
      <td width="200px">
        <p>Pasuruan, <?= date("d M Y"); ?><br>Bendahara</p><br><br>
        <p>__________________</p>
        <p>NISWATUN H.</p>
      </td>
    </tr>
  </table>

</body>
</html>

<script>
  window.print();
</script>
