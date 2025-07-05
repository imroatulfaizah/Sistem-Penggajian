<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
  </div>

  <a class="btn btn-sm btn-success mb-3" href="<?= base_url('admin/dataKelas/tambahData/'); ?>"><i class="fas fa-plus"></i> Tambah Data</a>
  <a class="btn btn-sm btn-danger mb-3" target="blank" href="<?= base_url('admin/dataKelas/printData/'); ?>"><i class="fas fa-print"></i> Print Data</a>
  <?= $this->session->flashdata('pesan'); ?>

  <table class="table table-bordered table-stiped mt-2">
    <tr>
      <th class="text-center">No</th>
      <th class="text-center">Nama Kelas</th>
      <th class="text-center">Action</th>
    </tr>

    <?php
    $no = 1;
    foreach ($kelas as $j) : ?>
      <tr>
        <td><?= $no++; ?></td>
        <td><?= $j->nama_kelas; ?></td>
        <td>
          <center>
            <a class="btn btn-sm btn-primary" href="<?= base_url('admin/dataKelas/updateData/' . $j->id_kelas); ?>"><i class="fas fa-edit"></i></a>
            <a onclick="return confirm('Yakin hapus?')" class="btn btn-sm btn-danger" href="<?= base_url('admin/dataKelas/deleteData/' . $j->id_kelas); ?>"><i class="fas fa-trash"></i></a>
          </center>
        </td>
      </tr>
    <?php endforeach; ?>
  </table>




</div>