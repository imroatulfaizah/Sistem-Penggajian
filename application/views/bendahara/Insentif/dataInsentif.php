<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
  </div>

  <a class="btn btn-sm btn-success mb-3" href="<?= base_url('bendahara/dataInsentif/tambahData/'); ?>"><i class="fas fa-plus"></i> Tambah Data</a>
  <a class="btn btn-sm btn-danger mb-3" target="blank" href="<?= base_url('bendahara/dataInsentif/printData/'); ?>"><i class="fas fa-print"></i> Print Data</a>
  <?= $this->session->flashdata('pesan'); ?>

  <table class="table table-bordered table-stiped mt-2">
    <tr>
      <th class="text-center">ID Insentif</th>
      <th class="text-center">ID Pegawai</th>
      <th class="text-center">Nama Insentif</th>
      <th class="text-center">Nominal Tunjangan</th>
      <th class="text-center">Status Pembayaran</th>
      <th class="text-center">Action</th>
    </tr>

    <?php
    $no = 1;
    foreach ($insentif as $j) : ?>
      <tr>
        <td><?= $no++; ?></td>
        <td><?= $j->id_pegawai; ?></td>
        <td><?= $j->nama_insentif; ?></td>
        <td>Rp. <?= number_format($j->nominal, 0, ',', '.'); ?>,-</td>
        <td>
            <?php if ($j->is_paid == 1): ?>
                <span style="color: green;">Sudah dibayar</span>
            <?php else: ?>
                <span style="color: red;">Belum dibayar</span>
            <?php endif; ?>
        </td>
        <!-- <td>Rp. <= number_format($j->nominal, 0, ',', '.'); ?>,-</td>
        <td>Rp. <= number_format($j->tj_transport, 0, ',', '.'); ?>,-</td>
        ?php $total = $j->gaji_pokok + $j->tj_transport + $j->uang_makan; ?>
        <td>Rp. <= number_format($j->uang_makan, 0, ',', '.'); ?>,-</td>
        <td>Rp. <= number_format($total, 0, ',', '.'); ?>,-</td> -->
        <td>
          <center>
            <a class="btn btn-sm btn-primary" href="<?= base_url('bendahara/dataInsentif/updateData/' . $j->id_insentif); ?>"><i class="fas fa-edit"></i></a>
            <a onclick="return confirm('Yakin hapus?')" class="btn btn-sm btn-danger" href="<?= base_url('bendahara/dataInsentif/deleteData/' . $j->id_insentif); ?>"><i class="fas fa-trash"></i></a>
          </center>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>




</div>