<div class="container-fluid">
    <h3><i class="fas fa-edit"></i> Update Data Jabatan</h3>

    <form method="POST" action="<?php echo base_url('admin/dataJabatan/updateDataAksi') ?>">

        <input type="hidden" name="id_jabatan" value="<?php echo $jabatan->id_jabatan ?>">

        <!-- Nama Jabatan -->
        <div class="form-group">
            <label>Nama Jabatan</label>
            <input type="text" name="nama_jabatan" class="form-control"
                   value="<?php echo $jabatan->nama_jabatan ?>">
            <?php echo form_error('nama_jabatan','<div class="text-small text-danger">','</div>') ?>
        </div>

        <!-- Tahun Akademik -->
        <div class="form-group">
            <label>Tahun Akademik</label>
            <select name="id_akademik" class="form-control">
                <option value="">-- Pilih Tahun Akademik --</option>
                <?php foreach($akademik as $ak) : ?>
                    <option value="<?php echo $ak->id_akademik ?>"
                        <?php echo ($periode && $periode->id_akademik == $ak->id_akademik) ? 'selected' : '' ?>>
                        <?php echo $ak->semester.' '.$ak->tahun_akademik ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php echo form_error('id_akademik','<div class="text-small text-danger">','</div>') ?>
        </div>

        <!-- Tunjangan Jabatan -->
        <div class="form-group">
            <label>Tunjangan Jabatan</label>
            <input type="number" name="tunjangan_jabatan" class="form-control"
                   value="<?php echo $periode ? $periode->tunjangan_jabatan : 0 ?>">
            <?php echo form_error('tunjangan_jabatan','<div class="text-small text-danger">','</div>') ?>
        </div>

        <!-- Tunjangan Transport -->
        <div class="form-group">
            <label>Tunjangan Transport</label>
            <input type="number" name="tunjangan_transport" class="form-control"
                   value="<?php echo $periode ? $periode->tunjangan_transport : 0 ?>">
            <?php echo form_error('tunjangan_transport','<div class="text-small text-danger">','</div>') ?>
        </div>

        <!-- Upah Mengajar -->
        <div class="form-group">
            <label>Upah Mengajar</label>
            <input type="number" name="upah_mengajar" class="form-control"
                   value="<?php echo $periode ? $periode->upah_mengajar : 0 ?>">
            <?php echo form_error('upah_mengajar','<div class="text-small text-danger">','</div>') ?>
        </div>

        <!-- Valid From (periode baru) -->
        <div class="form-group">
            <label>Berlaku Mulai (Valid From)</label>
            <input type="date" name="valid_from" class="form-control"
                   value="<?php echo date('Y-m-d') ?>">
            <small class="text-muted">Set otomatis sebagai periode baru</small>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Update</button>
        <a href="<?php echo base_url('admin/dataJabatan') ?>" class="btn btn-secondary mt-3">Kembali</a>

    </form>

</div>
