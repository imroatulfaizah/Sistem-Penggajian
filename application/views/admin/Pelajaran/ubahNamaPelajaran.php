<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
        <a href="<?= base_url('admin/dataPelajaran'); ?>" class="btn btn-sm btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">

            <?php 
            // Ambil data semester aktif
            $semester_aktif = $this->db->where('is_aktif', 1)->get('data_akademik')->row();
            ?>

            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-edit"></i> Ubah Nama Pelajaran untuk Semester Aktif
                    </h6>
                </div>
                <div class="card-body">

                    <!-- Info Semester Aktif -->
                    <div class="alert alert-info">
                        <strong>Semester Aktif:</strong><br>
                        <?= $semester_aktif->nama_akademik; ?> 
                        <span class="badge badge-success ml-2"><?= $semester_aktif->semester; ?></span>
                        <br><small class="text-muted">
                            Perubahan hanya berlaku untuk semester ini. Data semester sebelumnya tetap menggunakan nama lama.
                        </small>
                    </div>

                    <!-- Informasi Master Pelajaran -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <th width="40%">ID Pelajaran</th>
                                    <td>: <strong><?= $pel->id_pelajaran; ?></strong></td>
                                </tr>
                                <tr>
                                    <th>Nama di Master</th>
                                    <td>: <strong><?= htmlspecialchars($pel->nama_pelajaran); ?></strong></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <?php if (isset($history)): ?>
                                <div class="alert alert-warning small p-2">
                                    <i class="fas fa-history"></i> 
                                    <strong>Nama saat ini di semester aktif:</strong><br>
                                    <?= htmlspecialchars($history->nama_pelajaran); ?>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-secondary small p-2">
                                    <i class="fas fa-info-circle"></i> 
                                    Belum ada perubahan khusus → menggunakan nama master.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Form Ubah Nama -->
                    <?= form_open('admin/dataPelajaran/ubahNamaAksi'); ?>
                        <input type="hidden" name="id_pelajaran" value="<?= $pel->id_pelajaran; ?>">

                        <div class="form-group">
                            <label for="nama_pelajaran">
                                <strong>Nama Pelajaran Baru (untuk <?= $semester_aktif->nama_akademik; ?>)</strong>
                            </label>
                            <input type="text" name="nama_pelajaran" id="nama_pelajaran"
                                   class="form-control form-control-lg <?= form_error('nama_pelajaran') ? 'is-invalid' : '' ?>"
                                   value="<?= set_value('nama_pelajaran', $history->nama_pelajaran ?? $pel->nama_pelajaran); ?>"
                                   placeholder="Masukkan nama pelajaran untuk semester ini" required>
                            <?= form_error('nama_pelajaran', '<div class="invalid-feedback">', '</div>'); ?>
                            <small class="text-muted">
                                Kosongkan jika ingin kembali menggunakan nama master.
                            </small>
                        </div>

                        <hr>

                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                            <a href="<?= base_url('admin/dataPelajaran'); ?>" class="btn btn-secondary btn-lg">
                                <i class="fas fa-times"></i> Batal
                            </a>
                        </div>
                    <?= form_close(); ?>

                </div>
            </div>

        </div>
    </div>

</div>
<!-- /.container-fluid -->