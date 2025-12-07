<!-- application/views/pegawai/dataGaji.php -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
  </div>

  <!-- CARD FILTER -->
  <div class="card shadow mb-4">
    <div class="card-header bg-primary text-white">
      <i class="fas fa-filter"></i> Filter Data Gaji Saya
    </div>
    <div class="card-body">
      <form class="form-inline" method="get">
        <div class="form-group mb-2">
          <label class="mr-2">Bulan :</label>
          <select name="bulan" class="form-control">
            <option value="">-- Pilih Bulan --</option>
            <?php
            $nama_bulan = [
              '01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April',
              '05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus',
              '09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'
            ];
            foreach ($nama_bulan as $val => $txt):
              $selected = ($selected_bulan == $val) ? 'selected' : '';
              echo "<option value='$val' $selected>$txt</option>";
            endforeach;
            ?>
          </select>
        </div>

        <div class="form-group mb-2 ml-4">
          <label class="mr-2">Tahun :</label>
          <select name="tahun" class="form-control">
            <option value="">-- Pilih Tahun --</option>
            <?php
            $thn = date('Y');
            for ($i = $thn - 3; $i <= $thn + 1; $i++):
              $selected = ($selected_tahun == $i) ? 'selected' : '';
              echo "<option value='$i' $selected>$i</option>";
            endfor;
            ?>
          </select>
        </div>

        <button type="submit" class="btn btn-primary mb-2 ml-auto">
          <i class="fas fa-eye"></i> Tampilkan
        </button>
      </form>
    </div>
  </div>

  <!-- INFO BULAN -->
  <?php
  $nama_bulan_tampil = $nama_bulan[$selected_bulan] ?? 'Bulan';
  ?>
  <div class="alert alert-info">
    <i class="fas fa-calendar"></i>
    Menampilkan data gaji untuk <strong><?= $nama_bulan_tampil . ' ' . $selected_tahun; ?></strong>
  </div>

  <!-- TABEL GAJI -->
  <?php if ($gaji): ?>
    <div class="card shadow">
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped">
            <thead class="thead-dark">
              <tr>
                <th>Bulan / Tahun</th>
                <th>Tunjangan Jabatan</th>
                <th>Tunjangan Transport</th>
                <th>Upah Mengajar</th>
                <th>Insentif</th>
                <th>Total Gaji</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="text-center font-weight-bold">
                  <?= $nama_bulan_tampil; ?> <?= $selected_tahun; ?>
                </td>

                <!-- Tunjangan Jabatan -->
                <td>Rp. <?= number_format($gaji->tunjangan_jabatan, 0, ',', '.'); ?>,-</td>

                <!-- Tunjangan Transport -->
                <td>
                  Rp. <?= number_format($gaji->tunjangan_transport, 0, ',', '.'); ?>
                  × <?= $gaji->hadir; ?> hari<br>
                  <small class="text-success font-weight-bold">
                    = Rp. <?= number_format($gaji->tunjangan_transport * $gaji->hadir, 0, ',', '.'); ?>,-
                  </small>
                </td>

                <!-- Upah Mengajar -->
                <td>
                  Rp. <?= number_format($gaji->upah_mengajar, 0, ',', '.'); ?>
                  × <?= number_format($gaji->total_jam_mengajar, 1); ?> jam<br>
                  <small class="text-success font-weight-bold">
                    = Rp. <?= number_format($gaji->upah_mengajar * $gaji->total_jam_mengajar, 0, ',', '.'); ?>,-
                  </small>
                </td>

                <!-- Insentif -->
                <td class="text-warning font-weight-bold">
                  Rp. <?= number_format($gaji->total_insentif, 0, ',', '.'); ?>,-
                </td>

                <!-- Total Gaji -->
                <?php
                $total = $gaji->tunjangan_jabatan
                       + ($gaji->tunjangan_transport * $gaji->hadir)
                       + ($gaji->upah_mengajar * $gaji->total_jam_mengajar)
                       + $gaji->total_insentif;
                ?>
                <td class="font-weight-bold text-success">
                  Rp. <?= number_format($total, 0, ',', '.'); ?>,-
                </td>

                <!-- Aksi -->
                <td class="text-center">
                  <a href="<?= base_url('pegawai/dataGaji/cetakSlip/' . $selected_bulan . $selected_tahun); ?>"
                     target="_blank" class="btn btn-sm btn-info">
                    <i class="fas fa-print"></i> Slip Gaji
                  </a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  <?php else: ?>
    <div class="alert alert-warning text-center">
      <i class="fas fa-exclamation-triangle fa-2x"></i><br><br>
      <strong>Data gaji belum tersedia</strong> untuk bulan <strong><?= $nama_bulan_tampil . ' ' . $selected_tahun; ?></strong>.<br>
      Pastikan Anda sudah melakukan absensi kehadiran dan mengajar pada bulan tersebut.
    </div>
  <?php endif; ?>

</div>