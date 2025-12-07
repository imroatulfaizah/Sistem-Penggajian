<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
  </div>

  <a class="btn btn-sm btn-danger mb-3" target="blank" href="<?= base_url('kepsek/dataInsentif/printData/'); ?>"><i class="fas fa-print"></i> Print Data</a>
  <?= $this->session->flashdata('pesan'); ?>

  <table class="table table-bordered table-stiped mt-2">
    <tr>
      <th class="text-center">ID Insentif</th>
      <th class="text-center">NIP</th>
      <th class="text-center">Nama Insentif</th>
      <th class="text-center">Nominal Tunjangan</th>
      <th class="text-center">Status Pembayaran</th>
      <th class="text-center">Nomor Kwitansi</th>
    </tr>

    <?php
    $no = $offset + 1;
    foreach ($insentif as $j) : ?>
      <tr>
        <td><?= $no++; ?></td>
        <td><?= $j->nip; ?></td>
        <td><?= $j->nama_insentif; ?></td>
        <td>Rp. <?= number_format($j->nominal, 0, ',', '.'); ?>,-</td>
        <td>
            <?php if ($j->is_paid == 1): ?>
                <span style="color: green;">Sudah dibayar</span>
            <?php else: ?>
                <span style="color: red;">Belum dibayar</span>
            <?php endif; ?>
        </td>
        <td><?= $j->nomor_kwitansi; ?></td>
      </tr>
    <?php endforeach; ?>
  </table>

  <div class="mt-3 mb-5">
    <?= $this->pagination->create_links(); ?>
  </div>

</div>