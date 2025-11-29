<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial; color: black; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: center; }
    </style>
</head>

<body>

    <center>
        <img src="<?= base_url('assets/img/mts.png'); ?>" 
             style="width: 100px; margin-bottom: 15px;">
        <h1>MTS Nurul Mubtadiin</h1>
        <h2>Daftar Data Jabatan</h2>
        <h3><?= $title_periode ?></h3>
    </center>

    <table>
        <tr>
            <th>No</th>
            <th>Nama Jabatan</th>
            <th>Tj. Jabatan</th>
            <th>Tj. Transport</th>
            <th>Upah Mengajar</th>
        </tr>

        <?php $no = 1; foreach ($jabatan as $g): ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $g->nama_jabatan; ?></td>
                <td>Rp. <?= number_format($g->tunjangan_jabatan, 0, ',', '.'); ?>,-</td>
                <td>Rp. <?= number_format($g->tunjangan_transport, 0, ',', '.'); ?>,-</td>
                <td>Rp. <?= number_format($g->upah_mengajar, 0, ',', '.'); ?>,-</td>
            </tr>
        <?php endforeach; ?>
    </table>

</body>
</html>

<script>
    window.print();
</script>
