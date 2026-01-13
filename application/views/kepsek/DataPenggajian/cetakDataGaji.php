<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $title; ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    /* ===============================
       CSS ASLI — TIDAK DIUBAH
       =============================== */
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      margin: 0;
      padding: 20px;
      color: #2d3748;
    }
    .container {
      max-width: 900px;
      margin: 0 auto;
      background: white;
      border-radius: 20px;
      box-shadow: 0 20px 40px rgba(0,0,0,0.1);
      overflow: hidden;
    }
    .header {
      background: linear-gradient(120deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 30px 40px;
      text-align: center;
    }
    .header img {
      width: 90px;
      height: 90px;
      border-radius: 50%;
      border: 5px solid rgba(255,255,255,0.3);
      margin-bottom: 15px;
    }
    .header h1 { margin: 10px 0 5px; font-size: 28px; font-weight: 700; }
    .header h2 { margin: 0; font-size: 22px; font-weight: 500; }
    .header h3 { margin: 8px 0 0; font-size: 18px; }

    .content { padding: 40px; }

    .info-card {
      background: #f8f9ff;
      border-radius: 16px;
      padding: 25px;
      margin-bottom: 30px;
      border-left: 6px solid #667eea;
    }

    .info-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 12px;
    }

    .salary-table {
      width: 100%;
      border-collapse: collapse;
      margin: 30px 0;
    }

    .salary-table th,
    .salary-table td {
      padding: 18px 15px;
    }

    .amount {
      text-align: right;
      font-weight: 600;
    }

    .highlight {
      background: linear-gradient(120deg, #84fab0 0%, #8fd3f4 100%);
    }

    .footer {
      margin-top: 50px;
      display: flex;
      justify-content: space-between;
      padding-top: 30px;
      border-top: 2px dashed #e2e8f0;
    }

    @media print {
      body { background: white; padding: 10px; }
      .container { box-shadow: none; }
    }
  </style>
</head>

<body onload="window.print()">

<?php foreach ($slips as $s): ?>

<?php
$jam_mengajar   = $s->total_jam_mengajar;
$total_insentif = $s->total_insentif;

$total_gaji =
    $s->tunjangan_jabatan
  + ($s->tunjangan_transport * $s->hadir)
  + ($s->upah_mengajar * $jam_mengajar)
  + $total_insentif;
?>

<div class="container">

  <div class="header">
    <img src="<?= base_url('assets/img/mts.png'); ?>">
    <h1>TANDA TERIMA BISYAROH GURU</h1>
    <h2>MTs NURUL MUBTADIIN</h2>
    <h3>Jatisari Purwodadi Pasuruan</h3>
  </div>

  <div class="content">

    <div class="info-card">
      <div class="info-row"><strong>Nama Pegawai</strong><span><?= $s->nama_pegawai ?></span></div>
      <div class="info-row"><strong>NIP</strong><span><?= $s->nip ?></span></div>
      <div class="info-row"><strong>Jabatan</strong><span><?= $s->nama_jabatan ?></span></div>
      <div class="info-row"><strong>Periode Gaji</strong><span><?= nama_bulan($s->bulan) ?></span></div>
    </div>

    <table class="salary-table">
      <tr>
        <td>1</td>
        <td>Tunjangan Jabatan</td>
        <td class="amount">Rp. <?= number_format($s->tunjangan_jabatan,0,',','.') ?>,-</td>
      </tr>
      <tr>
        <td>2</td>
        <td>Tunjangan Transport</td>
        <td class="amount">Rp. <?= number_format($s->tunjangan_transport * $s->hadir,0,',','.') ?>,-</td>
      </tr>
      <tr>
        <td>3</td>
        <td>Upah Mengajar</td>
        <td class="amount">Rp. <?= number_format($s->upah_mengajar * $jam_mengajar,0,',','.') ?>,-</td>
      </tr>
      <tr>
        <td>4</td>
        <td>Insentif</td>
        <td class="amount">Rp. <?= number_format($total_insentif,0,',','.') ?>,-</td>
      </tr>
      <tr class="highlight">
        <td colspan="2"><strong>TOTAL GAJI</strong></td>
        <td class="amount"><strong>Rp. <?= number_format($total_gaji,0,',','.') ?>,-</strong></td>
      </tr>
    </table>

    <div class="footer">
      <div class="signature">
        <p>Kepala Sekolah</p>
        <div class="name">MAHFUDZ, S.Ag.</div>
      </div>
      <div class="signature">
        <p>Pasuruan, <?= date('d F Y') ?><br>Bendahara</p>
        <div class="name">NISWATUN H.</div>
      </div>
    </div>

  </div>
</div>

<div style="page-break-after: always;"></div>

<?php endforeach; ?>

</body>
</html>
