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
        <h2>Daftar Data Insentif</h2>
    </center>


    <table class="table table-bordered table-striped">
        <tr>
        <th class="text-center">No</th>
        <th class="text-center">ID Pegawai</th>
        <th class="text-center">Nama Insentif</th>
        <th class="text-center">Nominal Tunjangan</th>
        <th class="text-center">Is Paid</th>
        </tr>
        <?php
        $no = 1;
        foreach ($insentif as $j) : ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $j->id_pegawai; ?></td>
                <td><?= $j->nama_insentif; ?></td>
                <td>Rp. <?= number_format($j->nominal, 0, ',', '.'); ?>,-</td>
                <td><?= $j->is_paid; ?></td>
            </tr>

        <?php endforeach; ?>
    </table>
</body>

</html>

<script type="text/javascript">
    window.print();
</script>