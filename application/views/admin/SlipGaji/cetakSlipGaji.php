<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $title; ?></title>
  <style>
    body {
      font-family: Arial;
      color: black;
    }
  </style>
</head>

<body>

  <center>
    <h1>MTS Nurul Mubtadiin</h1>
    <h2>Slip Gaji Pegawai</h2>
    <hr style="width: 50%; border-width: 5px; color: black;">
  </center>

  <?php foreach ($jam as $p) {
    $jam = $p->total_jam;
  } ?>

  <!-- < foreach ($kehadiran as $t)
    $total_hadir = $t->hadir;
  ?> -->

  <?php foreach ($insentif as $a)
      $total_insentif = $a->jumlah_insentif;
    ?>


  <?php foreach ($print_slip as $ps) : ?>

    <?php $total_hadir = $ps->hadir; ?>

    <table style="width: 100%;">
      <tr>
        <td width="10%">Nama Pegawai</td>
        <td width="2%">:</td>
        <td><?= $ps->nama_pegawai; ?></td>
      </tr>
      <tr>
        <td>NIP</td>
        <td>:</td>
        <td><?= $ps->nip; ?></td>
      </tr>
      <tr>
        <td>Jabatan</td>
        <td>:</td>
        <td><?= $ps->nama_jabatan; ?></td>
      </tr>
      <tr>
        <td>Bulan</td>
        <td>:</td>
        <td><?= substr($ps->bulan, 0, 2);; ?></td>
      </tr>
      <tr>
        <td>Tahun</td>
        <td>:</td>
        <td><?= substr($ps->bulan, 2, 4);; ?></td>
      </tr>
    </table>

    <table class="table table-striped table-bordered mt-3">
      <tr>
        <th class="text-center" width="5%;">No</th>
        <th class="text-center">Keterangan</th>
        <th class="text-center">Jumlah</th>
      </tr>
      <!-- <tr>
        <td>1.</td>
        <td>Gaji Pokok</td>
        <td>Rp. <= number_format($ps->gaji_pokok, 0, ',', '.'); ?>,-</td>
      </tr> -->
      <tr>
        <td>2.</td>
        <td>Tunjangan Transportasi</td>
        <td>Rp. <?= number_format($ps->tunjangan_transport, 0, ',', '.'); ?>,-</td>
      </tr>
      <tr>
        <td>3.</td>
        <td>Tunjangan Transportasi</td>
        <td>Rp. <?= number_format($ps->tunjangan_transport, 0, ',', '.'); ?> x <?= $total_hadir ?></td>
      </tr>
      <tr>
        <td>4.</td>
        <td>Upah Mengajar</td>
        <td>Rp. <?= number_format($ps->upah_mengajar, 0, ',', '.'); ?> x <?= $jam ?></td>
      </tr>
      <tr>
        <td>4.</td>
        <td>Insentif</td>
        <td>Rp. <?= number_format($total_insentif, 0, ',', '.'); ?>,-</td>
      </tr>
       <?php $total_gaji = $ps->tunjangan_jabatan + $ps->tunjangan_transport * $total_hadir + $ps->upah_mengajar * $jam + $total_insentif; ?>
      <tr>
        <th colspan="2" style="text-align: right;">Total Gaji</th>
        <th>Rp. <?= number_format($total_gaji, 0, ',', '.'); ?>,-</th>
      </tr>
    </table>

    <table width="100%">
      <tr>
        <td>
          <p>Pegawai</p>
          <br><br>
          <p class="font-weight-bold"><?= $ps->nama_pegawai; ?></p>
        </td>
        <td width="200px;">
          <p>Pasuruan, <?= date('d M Y'); ?> <br> Bendahara,</p>
          <br><br>
          <p>____________________</p>
        </td>
      </tr>
    </table>

  <?php endforeach; ?>

</body>

</html>


<script>
  window.print();
</script>