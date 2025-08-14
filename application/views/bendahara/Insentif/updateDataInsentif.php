<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $title; ?></h1>
  </div>

  <div class="card" style="width: 60%; margin-bottom:100px;">
    <div class="card-body">

      <?php foreach($insentif as $j): ?>
      <form action="<?= base_url('bendahara/datainsentif/updateDataAksi') ?>" method="post">

        <div class="form-group">
          <label for="">NIP</label>
          <input type="hidden" name="id_insentif" value="<?= $j->id_insentif; ?>">
          <input type="text" name="nip" class="form-control" value="<?= $j->nip; ?>">
          <?= form_error('nip', '<div class="text-small text-danger">', '</div>') ?>
        </div>
        <div class="form-group">
          <label for="">Nama Insentif</label>
          <input type="text" name="nama_insentif" class="form-control" value="<?= $j->nama_insentif; ?>">
          <?= form_error('nama_insentif', '<div class="text-small text-danger">', '</div>') ?>
        </div>
        <div class="form-group">
          <label for="">Nominal Tunjangan</label>
          <input type="number" name="nominal" class="form-control" value="<?= $j->nominal; ?>">
          <?= form_error('nominal', '<div class="text-small text-danger">', '</div>') ?>
        </div>
        <div class="form-group">
            <label for="">Status Pembayaran</label>
            <select name="is_paid" class="form-control">
                <option value="1" <?= $j->is_paid == 1 ? 'selected' : '' ?>>Lunas</option>
                <option value="0" <?= $j->is_paid == 0 ? 'selected' : '' ?>>Belum Lunas</option>
            </select>
            <?= form_error('is_paid', '<div class="text-small text-danger">', '</div>') ?>
        </div>
        <button type="submit" class="btn btn-success">Update</button>

      </form>
      <?php endforeach; ?>
    </div>
  </div>


</div>
