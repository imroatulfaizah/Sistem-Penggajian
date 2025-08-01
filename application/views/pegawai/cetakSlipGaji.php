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
    <img src="<?= base_url('assets/img/mts.png'); ?>" alt="Logo MTS Nurul Mubtadiin" style="width: 100px; height: auto; margin-bottom: 15px;">
    <h4>TANDA TERIMA BISYAROH GURU </h4>			
    <h4>MTs NURUL MUBTADIIN </h4>				
    <h4>JATISARI PURWODADI PASURUAN </h4>
    <hr style="width: 50%; border-width: 5px; color: black;">
  </center>

  <?php

  ?>

  <?php foreach ($jam as $p)
    $jam = $p->total_jam;
  ?>

  <?php foreach ($kehadiran as $t)
    $total_hadir = $t->hadir;
  ?>

  <?php foreach ($insentif as $a)
    $total_insentif = $a->jumlah_insentif;
  ?>

  <?php foreach ($print_slip as $ps) : ?>

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
        <td><?= substr($ps->bulan, 0, 2); ?></td>
      </tr>
      <tr>
        <td>Tahun</td>
        <td>:</td>
        <td><?= substr($ps->bulan, 2, 4); ?></td>
      </tr>
    </table>

    <table class="table table-striped table-bordered mt-3">
      <tr>
        <th class="text-center" width="5%;">No</th>
        <th class="text-center">Keterangan</th>
        <th class="text-center">Jumlah</th>
      </tr>
      <tr>
        <td>1.</td>
        <td>Tunjangan Jabatan</td>
        <td>Rp. <?= number_format($ps->tunjangan_jabatan, 0, ',', '.'); ?>,-</td>
      </tr>
      <tr>
        <td>2.</td>
        <td>Insentif</td>
        <td>Rp. <?= number_format($total_insentif, 0, ',', '.'); ?>,-</td>
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
      <?php $total_gaji = $ps->tunjangan_jabatan + $ps->tunjangan_transport * $total_hadir + $ps->upah_mengajar * $jam + $total_insentif; ?>
      <tr>
        <th colspan="2" style="text-align: right;">Total Gaji</th>
        <th>Rp. <?= number_format($total_gaji, 0, ',', '.'); ?>,-</th>
      </tr>
    </table>

    <table width="100%">
      <tr>
        <td>
          <p>Kepala Sekolah</p>
          <br><br>
          <p>____________________</p>
          <p class="font-weight-bold"> MAHFUDZ, S,Ag.</p>
        </td>
        <td width="200px;">
          <p>Pasuruan, <?= date('d M Y'); ?> <br> Bendahara,</p>
          <br><br>
          <p>____________________</p>
          <p>NISWATUN H. </p>
        </td>
      </tr>
    </table>

  <?php endforeach; ?>

</body>

</html>


<script>
  window.print();
</script>