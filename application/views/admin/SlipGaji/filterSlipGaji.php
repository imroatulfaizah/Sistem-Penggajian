<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
    </div>

    <?= $this->session->flashdata('pesan'); ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Pilih Pegawai & Periode</h6>
        </div>
        <div class="card-body">
            <?= form_open('admin/slipGaji/cetakSlipGaji'); ?>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Pilih Pegawai</label>
                            <select name="nip" class="form-control" required>
                                <option value="">-- Pilih Pegawai --</option>
                                <?php foreach ($pegawai as $p): ?>
                                    <option value="<?= $p->nip ?>"><?= $p->nip ?> - <?= $p->nama_pegawai ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Bulan</label>
                            <select name="bulan" class="form-control" required>
                                <option value="">-- Bulan --</option>
                                <?php for ($i = 1; $i <= 12; $i++): ?>
                                    <option value="<?= sprintf('%02d', $i) ?>" <?= date('m') == sprintf('%02d', $i) ? 'selected' : '' ?>>
                                        <?= date('F', mktime(0, 0, 0, $i, 10)) ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Tahun</label>
                            <select name="tahun" class="form-control" required>
                                <option value="">-- Tahun --</option>
                                <?php for ($y = date('Y')-2; $y <= date('Y')+1; $y++): ?>
                                    <option value="<?= $y ?>" <?= date('Y') == $y ? 'selected' : '' ?>><?= $y ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-print"></i> Cetak Slip
                            </button>
                        </div>
                    </div>
                </div>
            <?= form_close(); ?>
        </div>
    </div>

</div>