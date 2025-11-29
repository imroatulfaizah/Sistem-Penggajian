<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
    </div>

    <?= $this->session->flashdata('pesan') ?>

    <a href="<?= base_url('admin/dataJabatan/tambahData') ?>" class="btn btn-primary mb-3">
        <i class="fas fa-plus"></i> Tambah Data
    </a>

    <a href="<?= base_url('admin/dataJabatan/print') ?>" target="_blank" class="btn btn-secondary mb-3">
        <i class="fas fa-print"></i> Print
    </a>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="text-center">
                <tr>
                    <th>No</th>
                    <th>Nama Jabatan</th>
                    <th>Tunjangan Jabatan</th>
                    <th>Tunjangan Transport</th>
                    <th>Upah Mengajar</th>
                    <th>Tahun Akademik</th>
                    <th>Semester</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php $no = 1; foreach ($jabatan as $j) : ?>
                    <tr class="text-center">
                        <td><?= $no++ ?></td>
                        <td><?= $j->nama_jabatan ?></td>
                        <td>Rp <?= number_format($j->tunjangan_jabatan, 0, ',', '.') ?></td>
                        <td>Rp <?= number_format($j->tunjangan_transport, 0, ',', '.') ?></td>
                        <td>Rp <?= number_format($j->upah_mengajar, 0, ',', '.') ?></td>
                        <td><?= $j->tahun_akademik ?></td>
                        <td><?= ucfirst($j->semester) ?></td>

                        <td>
                            <a href="<?= base_url('admin/dataJabatan/updateData/'.$j->id_jabatan) ?>" 
                               class="btn btn-sm btn-success">Edit</a>

                            <a href="<?= base_url('admin/dataJabatan/deleteData/'.$j->id_jabatan) ?>" 
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Yakin hapus?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>

        </table>
    </div>
</div>
