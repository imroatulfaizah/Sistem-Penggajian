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

  <div class="card mb-3">
      <div class="card-header bg-primary text-white">
          Filter & Pencarian Data
      </div>
      <div class="card-body">
          <form method="GET" action="">

              <div class="row align-items-end">

                  <div class="col-md-3">
                      <label><strong>Filter Hari</strong></label>
                      <select name="hari" class="form-control">
                          <option value="">-- Semua Hari --</option>
                          <?php
                          $hariList = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
                          foreach ($hariList as $h) : ?>
                              <option value="<?= $h; ?>" 
                                  <?= (isset($_GET['hari']) && $_GET['hari'] == $h) ? 'selected' : ''; ?>>
                                  <?= $h; ?>
                              </option>
                          <?php endforeach; ?>
                      </select>
                  </div>

                  <div class="col-md-4">
                      <label><strong>Cari Data</strong></label>
                      <input type="text" name="keyword" class="form-control" placeholder="Mapel / Guru / Kelas..." value="<?= isset($_GET['keyword']) ? $_GET['keyword'] : '' ?>">
                  </div>

                  <div class="col-md-5">
                      <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Tampilkan</button>
                      <a href="<?= base_url('admin/dataPenempatan'); ?>" class="btn btn-secondary"><i class="fas fa-sync"></i> Reset</a>
                  </div>

              </div>

          </form>
      </div>
  </div>
  <div class="alert alert-info">
      Menampilkan Data: <span class="font-weight-bold"><?= $total_rows ?? '-' ?></span> Data Ditemukan.
  </div>

  <div class="table-responsive">
    <table class="table table-bordered table-striped mt-2">
        <tr>
            <th class="text-center">No</th>
            <th class="text-center">Nama Pelajaran</th>
            <th class="text-center">Kelas</th>
            <th class="text-center">Tahun Akademik</th>
            <th class="text-center">Nama Guru</th>
            <th class="text-center">Hari</th>
            <th class="text-center">Jam</th>
            <th class="text-center">Total Jam</th>
            <th class="text-center">Keterangan</th>
            <th class="text-center">Action</th>
        </tr>

        <?php
        $no = $this->input->get('page') ? $this->input->get('page') + 1 : 1; // Penomoran agar lanjut di page 2
        foreach ($penempatan as $g) : ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= $g->nama_pelajaran; ?></td>
            <td><?= $g->nama_kelas; ?></td>
            <td><?= $g->tahun_akademik; ?></td>
            <td><?= $g->nama_pegawai; ?></td>
            <td>
                <?php 
                    // Pewarnaan Badge Hari Biar Cantik
                    if($g->hari == 'Senin') echo '<span class="badge badge-primary">Senin</span>';
                    elseif($g->hari == 'Selasa') echo '<span class="badge badge-success">Selasa</span>';
                    elseif($g->hari == 'Rabu') echo '<span class="badge badge-danger">Rabu</span>';
                    elseif($g->hari == 'Kamis') echo '<span class="badge badge-warning">Kamis</span>';
                    elseif($g->hari == 'Jumat') echo '<span class="badge badge-info">Jumat</span>';
                    else echo '<span class="badge badge-secondary">'.$g->hari.'</span>';
                ?>
            </td>
            <td><?= $g->jam_mulai; ?> - <?= $g->jam_akhir; ?></td>
            <td><?= $g->total_jam; ?></td>
            <td><?= $g->keterangan; ?></td>
            <td>
            <center>
                <a class="btn btn-sm btn-primary" href="<?= base_url('admin/dataPenempatan/updateData/' . $g->id_penempatan); ?>"><i class="fas fa-edit"></i></a>
                <a onclick="return confirm('Yakin hapus?')" class="btn btn-sm btn-danger" href="<?= base_url('admin/dataPenempatan/deleteData/' . $g->id_penempatan); ?>"><i class="fas fa-trash"></i></a>
            </center>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
  </div>

  <div class="mt-3 mb-5">
    <?= $this->pagination->create_links(); ?>
  </div>

</div>