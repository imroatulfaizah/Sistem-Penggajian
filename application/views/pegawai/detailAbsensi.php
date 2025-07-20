<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
  </div>

  <!-- <a class="btn btn-sm btn-danger mb-3" target="blank" href="<? base_url('pegawai/dataPenempatan/printData/'); ?>"><i class="fas fa-print"></i> Print Data</a> -->
  <?= $this->session->flashdata('pesan'); ?>

  <table class="table table-bordered table-stiped mt-2">
    <tr>
        <th class="text-center">No</th>
        <th class="text-center">ID Penempatan</th>
        <th class="text-center">Jam Clock In</th>
        <th class="text-center">Jam Clockout</th>
        <th class="text-center">Lokasi Clock In</th>
        <th class="text-center">Lokasi Clock Out</th>
        <th class="text-center">Total Jam Mengajar</th>
    </tr>

    <?php
    $no = 1;
    foreach ($absensi as $g) : ?>
      <tr>
        <td><?= $no++; ?></td>
        <td><?= $g->id_penempatan; ?></td>
        <td><?= $g->jam_clockin; ?></td>
        <td><?= $g->jam_clockout; ?></td>
        <td><?= $g->lokasi_clockin; ?></td>
        <td><?= $g->lokasi_clockout; ?></td>
        <td><?= $g->total_jam; ?></td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>