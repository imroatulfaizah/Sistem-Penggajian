<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
  </div>

  <a class="btn btn-sm btn-success mb-3" href="<?= base_url('admin/dataAkademik/tambahData'); ?>">
      <i class="fas fa-plus"></i> Tambah Data
  </a>

  <a class="btn btn-sm btn-danger mb-3" target="_blank" href="<?= base_url('admin/dataAkademik/printData'); ?>">
      <i class="fas fa-print"></i> Print Data
  </a>

  <?= $this->session->flashdata('pesan'); ?>

  <table class="table table-bordered table-striped mt-2">
      <thead>
          <tr>
              <th class="text-center">No</th>
              <th class="text-center">Tahun Akademik</th>
              <th class="text-center">Semester</th>
              <th class="text-center">Nama Akademik</th>
              <th class="text-center">Status</th>
              <th class="text-center">Action</th>
          </tr>
      </thead>

      <tbody>
        <?php $no = 1; foreach ($akademik as $a) : ?>
          <tr>
              <td class="text-center"><?= $no++; ?></td>

              <td class="text-center">
                  <?= $a->tahun_akademik; ?>
              </td>

              <td class="text-center">
                  <?= $a->semester; ?>
              </td>

              <td class="text-center">
                  <?= $a->nama_akademik; ?>
              </td>

              <td class="text-center">
                  <?php if ($a->is_aktif == 1) : ?>
                      <span class="badge badge-success">Aktif</span>
                  <?php else : ?>
                      <span class="badge badge-secondary">Tidak Aktif</span>
                  <?php endif; ?>
              </td>

              <td class="text-center">
                  <a class="btn btn-sm btn-primary" 
                     href="<?= base_url('admin/dataAkademik/updateData/' . $a->id_akademik); ?>">
                      <i class="fas fa-edit"></i>
                  </a>

                  <a onclick="return confirm('Yakin hapus data ini?')" 
                     class="btn btn-sm btn-danger"
                     href="<?= base_url('admin/dataAkademik/deleteData/' . $a->id_akademik); ?>">
                      <i class="fas fa-trash"></i>
                  </a>
              </td>
          </tr>
        <?php endforeach; ?>
      </tbody>

  </table>

</div>
<!-- End Page Content -->
