<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style type="text/css">
        body {
            font-family: Arial;
            color: black;
        }
    </style>
</head>

<body>
    <br>
    <br>
    <br>
    <br>

    <center>
        <h1>MTS Nurul Mubtadiin</h1>
        <br>
        <h2>Daftar Data Jabatan</h2>
    </center>


    <table class="table table-bordered table-striped">
        <tr>
            <th class="text-center">No</th>
            <th class="text-center">Nama Jabatan</th>
            <th class="text-center">Tj. Jabatan</th>
            <th class="text-center">Tj. Transport</th>
            <th class="text-center">Upah Mengajar</th>
        </tr>
        <?php
        $no = 1;
        foreach ($jabatan as $g) : ?>
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

<script type="text/javascript">
    window.print();
</script>