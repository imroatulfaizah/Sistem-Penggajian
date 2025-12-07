<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
  </div>

  <?= $this->session->flashdata('pesan'); ?>

  <!-- FILTER BULAN & TAHUN -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Filter Data Kehadiran</h6>
    </div>
    <div class="card-body">
      <form method="get" class="form-inline">
        <div class="form-group mr-3">
          <select name="bulan" class="form-control">
            <option value="">-- Pilih Bulan --</option>
            <?php
            $nama_bulan = [
              '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
              '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
              '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
            ];
            foreach ($nama_bulan as $key => $val) {
              $selected = ($selected_bulan == $key) ? 'selected' : '';
              echo "<option value='$key' $selected>$val</option>";
            }
            ?>
          </select>
        </div>

        <div class="form-group mr-3">
          <select name="tahun" class="form-control">
            <option value="">-- Pilih Tahun --</option>
            <?php foreach ($daftar_tahun as $t): ?>
              <option value="<?= $t['tahun'] ?>" <?= ($selected_tahun == $t['tahun']) ? 'selected' : ''; ?>>
                <?= $t['tahun'] ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <button type="submit" class="btn btn-primary mr-2">Filter</button>
        <a href="<?= base_url('pegawai/dataAbsensi/detailKehadiran') ?>" class="btn btn-secondary">Reset</a>
      </form>
    </div>
  </div>

  <!-- TABEL KEHADIRAN -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Data Kehadiran</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <thead class="thead-dark">
            <tr>
              <th>No</th>
              <th>NIP</th>
              <th>Tanggal</th>
              <th>Clock In</th>
              <th>Clock Out</th>
              <th>Lokasi In</th>
              <th>Lokasi Out</th>
              <th>Total Jam</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $no = $offset + 1;
            if (!empty($kehadiran)): 
              foreach ($kehadiran as $g): ?>
                <tr>
                  <td><?= $no++; ?></td>
                  <td><?= htmlspecialchars($g->nip); ?></td>
                  <td><?= date('d/m/Y', strtotime($g->jam_clockin)); ?></td>
                  <td><?= $g->jam_clockin ? date('H:i:s', strtotime($g->jam_clockin)) : '-'; ?></td>
                  <td><?= $g->jam_clockout ? date('H:i:s', strtotime($g->jam_clockout)) : '-'; ?></td>
                  <td>
                    <?php if ($g->lokasi_clockin): ?>
                      <a href="https://maps.google.com/?q=<?= $g->lokasi_clockin; ?>" target="_blank" class="badge badge-primary">
                        Lihat Maps
                      </a>
                    <?php else: ?> - <?php endif; ?>
                  </td>
                  <td>
                    <?php if ($g->lokasi_clockout): ?>
                      <a href="https://maps.google.com/?q=<?= $g->lokasi_clockout; ?>" target="_blank" class="badge badge-success">
                        Lihat Maps
                      </a>
                    <?php else: ?> - <?php endif; ?>
                  </td>
                  <td class="text-center font-weight-bold">
                    <?= $g->total_jam ? $g->total_jam . ' jam' : '-'; ?>
                  </td>
                </tr>
              <?php endforeach; 
            else: ?>
              <tr>
                <td colspan="8" class="text-center text-muted">Tidak ada data kehadiran untuk filter ini.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- PAGINATION -->
      <div class="mt-4">
        <?= $pagination; ?>
      </div>
    </div>
  </div>

</div>
<!-- /.container-fluid -->