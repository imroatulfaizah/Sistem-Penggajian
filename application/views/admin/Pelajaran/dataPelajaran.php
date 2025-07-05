<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
  </div>

  <a class="btn btn-sm btn-success mb-3" href="<?= base_url('admin/dataPelajaran/tambahData/'); ?>"><i class="fas fa-plus"></i> Tambah Data</a>
  <a class="btn btn-sm btn-danger mb-3" target="blank" href="<?= base_url('admin/dataPelajaran/printData/'); ?>"><i class="fas fa-print"></i> Print Data</a>
  <?= $this->session->flashdata('pesan'); ?>

  <table class="table table-bordered table-stiped mt-2">
    <tr>
      <th class="text-center">No</th>
      <th class="text-center">Nama Pelajaran</th>
      <th class="text-center">Action</th>
    </tr>

    <?php
    $no = 1;
    foreach ($pelajaran as $j) : ?>
      <tr>
        <td><?= $no++; ?></td>
        <td><?= $j->nama_pelajaran; ?></td>
        <td>
          <center>
            <a class="btn btn-sm btn-primary" href="<?= base_url('admin/dataPelajaran/updateData/' . $j->id_pelajaran); ?>"><i class="fas fa-edit"></i></a>
            <a onclick="return confirm('Yakin hapus?')" class="btn btn-sm btn-danger" href="<?= base_url('admin/dataPelajaran/deleteData/' . $j->id_pelajaran); ?>"><i class="fas fa-trash"></i></a>
          </center>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>




</div>