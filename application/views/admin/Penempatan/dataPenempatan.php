<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
  </div>

  <a class="btn btn-sm btn-success mb-3" href="<?= base_url('admin/dataPenempatan/tambahData/'); ?>"><i class="fas fa-plus"></i> Tambah Data</a>
  <a class="btn btn-sm btn-danger mb-3" target="blank" href="<?= base_url('admin/dataPenempatan/printData/'); ?>"><i class="fas fa-print"></i> Print Data</a>
  <?= $this->session->flashdata('pesan'); ?>

  <?php
// Ambil semua nama kelas unik
$kelasList = [];
foreach ($penempatan as $p) {
    if (!in_array($p->nama_kelas, $kelasList)) {
        $kelasList[] = $p->nama_kelas;
    }
}

// Urutkan kelas opsional (jika mau)
sort($kelasList);

$jam = 0;
?>

<div style="overflow-x:auto; white-space:nowrap;">
<table class="table table-bordered table-striped">

    <thead class="text-center">
        <tr>
            <th>HARI</th>
            <th>JAM</th>
            <th>WAKTU</th>
            <?php foreach ($kelasList as $k): ?>
                <th><?= $k; ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>

    <tbody>

    <?php foreach ($penempatan as $g): ?>

        <?php
        // Format waktu
        $waktu = date("H.i", strtotime($g->jam_mulai)) . " - " . date("H.i", strtotime($g->jam_akhir));
        $guru  = $g->nip;

        // ==== Tambahkan SHOLAT DHUHA pada jam 5 (waktu 09.30 - 10.00) ====
        if ($jam == 5): ?>
            <tr>
                <td class="text-center"><?= $g->hari; ?></td>
                <td class="text-center">5</td>
                <td class="text-center">09.30 - 10.00</td>
                <td colspan="<?= count($kelasList); ?>" class="text-center font-weight-bold">
                    SHOLAT DHUHA / ISTIRAHAT
                </td>
            </tr>
            <?php $jam++; ?>
        <?php endif; ?>

        <!-- Baris mata pelajaran -->
        <tr>
            <td class="text-center"><?= $g->hari; ?></td>
            <td class="text-center"><?= $jam; ?></td>
            <td class="text-center"><?= $waktu; ?></td>

            <!-- Isi kolom kelas -->
            <?php foreach ($kelasList as $k): ?>
                <td class="text-center">
                    <?= ($g->nama_kelas == $k) ? $g->nama_pelajaran . "<br>(".$guru.")" : "" ?>
                </td>
            <?php endforeach; ?>
        </tr>

        <?php
        // ==== Tambahkan ISTIRAHAT pada jam 10 (waktu 12.30 - 13.00) ====
        if ($jam == 10): ?>
            <tr>
                <td class="text-center"><?= $g->hari; ?></td>
                <td class="text-center">11</td>
                <td class="text-center">12.30 - 13.00</td>
                <td colspan="<?= count($kelasList); ?>" class="text-center font-weight-bold">
                    SHOLAT DZUHUR BERJAMAAH
                </td>
            </tr>
        <?php endif; ?>

        <?php $jam++; ?>

    <?php endforeach; ?>

    </tbody>
</table>
</div>

</div>