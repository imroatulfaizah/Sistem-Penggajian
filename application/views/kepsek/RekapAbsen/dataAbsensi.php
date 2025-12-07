<?php
$nama_bulan = ["", "Januari","Februari","Maret","April","Mei","Juni",
               "Juli","Agustus","September","Oktober","November","Desember"];
?>

<div class="container-fluid">

    <?= $this->session->flashdata('pesan'); ?>

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
    </div>

    <!-- Filter -->
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            Filter Data Absensi Pegawai
        </div>
        <div class="card-body">
            <form class="form-inline" method="get">
                <div class="form-group mb-2 mr-3">
                    <label class="mr-2">Bulan :</label>
                    <select name="bulan" class="form-control">
                        <option value="">-- Pilih Bulan --</option>
                        <?php for($i=1;$i<=12;$i++):
                            $val = sprintf("%02d",$i);
                            $selected = ($bulan_selected == $val) ? 'selected' : '';
                        ?>
                            <option value="<?= $val ?>" <?= $selected ?>><?= $nama_bulan[$i] ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="form-group mb-2 mr-3">
                    <label class="mr-2">Tahun :</label>
                    <select name="tahun" class="form-control">
                        <option value="">-- Pilih Tahun --</option>
                        <?php for($i=date('Y')-5;$i<=date('Y')+5;$i++): ?>
                            <option value="<?= $i ?>" <?= ($tahun_selected == $i) ? 'selected' : '' ?>><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary mb-2">
                    Tampilkan
                </button>
            </form>
        </div>
    </div>

    <!-- Info Periode -->
    <div class="alert alert-info">
        Menampilkan data kehadiran pegawai bulan 
        <strong><?= $nama_bulan[(int)$bulan_selected] ?></strong> 
        tahun <strong><?= $tahun_selected ?></strong>
    </div>

    <?php if (!empty($absensi)): ?>
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr class="text-center">
                                <th width="5%">No</th>
                                <th>NIP</th>
                                <th>Nama Pegawai</th>
                                <th>Jenis Kelamin</th>
                                <th>Jabatan</th>
                                <th>Hadir</th>
                                <th>Izin / Tidak Hadir</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = ($this->input->get('page') ? $this->input->get('page') : 0) + 1;
                            foreach ($absensi as $a): 
                            ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td><?= $a->nip ?></td>
                                    <td><?= $a->nama_pegawai ?></td>
                                    <td><?= $a->jenis_kelamin ?></td>
                                    <td><?= $a->jabatan ?></td>
                                    <td class="text-center text-success font-weight-bold"><?= $a->hadir ?></td>
                                    <td class="text-center text-danger font-weight-bold"><?= $a->izin ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- PAGINATION – persis seperti DataPenempatan -->
                <div class="mt-4">
                    <?= $pagination; ?>
                </div>

            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center shadow">
            <strong>Data absensi masih kosong</strong> untuk periode yang dipilih.
        </div>
    <?php endif; ?>

</div>