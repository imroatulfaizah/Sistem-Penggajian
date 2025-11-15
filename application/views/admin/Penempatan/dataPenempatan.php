<!-- Begin Page Content -->
<div class="container-fluid">

  <div class="d-sm-flex align-items-center justify-content-between mb-4">
      <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
  </div>

  <a class="btn btn-sm btn-success mb-3" href="<?= base_url('admin/dataPenempatan/tambahData/'); ?>">
      <i class="fas fa-plus"></i> Tambah Data
  </a>

  <a class="btn btn-sm btn-danger mb-3" target="_blank" href="<?= base_url('admin/dataPenempatan/printData/'); ?>">
      <i class="fas fa-print"></i> Print Data
  </a>

  <?= $this->session->flashdata('pesan'); ?>


  <?php
  // List kelas unik
  $kelasList = [];
  foreach ($penempatan as $p) {
      if (!in_array($p->nama_kelas, $kelasList)) {
          $kelasList[] = $p->nama_kelas;
      }
  }
  sort($kelasList);

  // List hari unik
  $hariList = [];
  foreach ($penempatan as $p) {
      if (!in_array($p->hari, $hariList)) {
          $hariList[] = $p->hari;
      }
  }
  ?>

  <?php foreach ($hariList as $hari): ?>

      <h4 class="mt-4 mb-2 font-weight-bold text-primary"><?= strtoupper($hari); ?></h4>

      <div style="overflow-x:auto; white-space: nowrap;">
      <table class="table table-bordered table-striped">
          <thead class="text-center">
              <tr>
                  <th>HARI</th>
                  <th>JAM</th>
                  <th>WAKTU</th>
                  <?php foreach ($kelasList as $k): ?>
                      <th><?= $k; ?></th>
                  <?php endforeach; ?>
              </tr>
          </thead>

          <tbody>

          <?php
          // Filter data hari tertentu
          $dataHari = array_filter($penempatan, fn($x) => $x->hari == $hari);

          // Group by jam_mulai + jam_akhir
          $grouped = [];
          foreach ($dataHari as $row) {
              $key = $row->jam_mulai . "_" . $row->jam_akhir;
              if (!isset($grouped[$key])) {
                  $grouped[$key] = [];
              }
              $grouped[$key][] = $row;
          }

          $jamKe = 0;
          ?>

          <?php foreach ($grouped as $key => $items): ?>

              <?php
              // Ambil jam dan waktu
              $first = $items[0];
              $waktu = date("H.i", strtotime($first->jam_mulai)) . " - " . date("H.i", strtotime($first->jam_akhir));

              // Siapkan mapping kelas → mapel
              $isiKelas = [];
              foreach ($kelasList as $k) $isiKelas[$k] = "";

              foreach ($items as $d) {
                  $isiKelas[$d->nama_kelas] = $d->nama_pelajaran . "<br>(" . $d->nama_pegawai . ")";
              }
              ?>

              <!-- ===== SHOLAT DHUHA ===== -->
              <?php if ($jamKe == 5): ?>
                  <tr>
                      <td class="text-center"><?= $hari ?></td>
                      <td class="text-center">5</td>
                      <td class="text-center">09.30 - 10.00</td>
                      <td colspan="<?= count($kelasList); ?>" class="text-center font-weight-bold text-success">
                          SHOLAT DHUHA / ISTIRAHAT
                      </td>
                  </tr>
              <?php endif; ?>

              <!-- ===== ROW UTAMA ===== -->
              <tr>
                  <td class="text-center"><?= $hari ?></td>
                  <td class="text-center"><?= $jamKe ?></td>
                  <td class="text-center"><?= $waktu ?></td>

                  <?php foreach ($kelasList as $k): ?>
                      <td class="text-center"><?= $isiKelas[$k] ?></td>
                  <?php endforeach; ?>
              </tr>

              <!-- ===== ISTIRAHAT DZUHUR ===== -->
              <?php if ($jamKe == 10): ?>
                  <tr>
                      <td class="text-center"><?= $hari ?></td>
                      <td class="text-center">10</td>
                      <td class="text-center">12.30 - 13.00</td>
                      <td colspan="<?= count($kelasList); ?>" class="text-center font-weight-bold text-primary">
                          SHOLAT DZUHUR BERJAMAAH
                      </td>
                  </tr>
              <?php endif; ?>

              <?php $jamKe++; ?>

          <?php endforeach; ?>

          </tbody>
      </table>
      </div>

  <?php endforeach; ?>

</div>
