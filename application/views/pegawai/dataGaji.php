<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
  </div>

  <table class="table table-striped table-bordered">
    <tr>
      <th>Bulan / Tahun</th>
      <th>Tunjangan Jabatan</th>
      <th>Tunjangan Transportasi</th>
      <th>Upah Mengajar</th>
      <th>Total Gaji</th>
      <th>Cetak Slip</th>
    </tr>

    <?php foreach ($jam as $p)
      $jam = $p->total_jam;
    ?>

    <?php foreach ($kehadiran as $t)
      $total_hadir = $t->hadir;
    ?>

    <?php foreach ($gaji as $g) : ?>
      <tr>
        <td><?= $g->bulan; ?></td>
        <td>Rp. <?= number_format($g->tunjangan_jabatan, 0, ',', '.'); ?>,-</td>
        <td>Rp. <?= number_format($g->tunjangan_transport, 0, ',', '.'); ?> x <?= $total_hadir ?></td>
        <td>Rp. <?= number_format($g->upah_mengajar , 0, ',', '.'); ?> x <?= $jam ?></td>
        <?php $total_gaji = $g->tunjangan_jabatan + $g->tunjangan_transport * $total_hadir + $g->upah_mengajar * $jam; ?>
        <td>Rp. <?= number_format($total_gaji, 0, ',', '.'); ?>,-</td>
        <td>
          <center>
            <a class="btn btn-sm btn-primary" target="blank" href="<?= base_url('pegawai/dataGaji/cetakSlip/' . $g->nip); ?>"><i class="fas fa-print"></i></a>
          </center>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>



</div>