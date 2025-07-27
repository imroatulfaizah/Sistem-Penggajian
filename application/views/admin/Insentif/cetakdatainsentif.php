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
        <th class="text-center">NIP</th>
        <th class="text-center">Nama Insentif</th>
        <th class="text-center">Nominal Tunjangan</th>
        <th class="text-center">Status Pembayaran</th>
        <th class="text-center">Nomor Kwitansi</th>
        </tr>
        <?php
        $no = 1;
        foreach ($insentif as $j) : ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $j->nip; ?></td>
                <td><?= $j->nama_insentif; ?></td>
                <td>Rp. <?= number_format($j->nominal, 0, ',', '.'); ?>,-</td>
                <td>
                    <?php if ($j->is_paid == 1): ?>
                        <span style="color: green;">Sudah dibayar</span>
                    <?php else: ?>
                        <span style="color: red;">Belum dibayar</span>
                    <?php endif; ?>
                </td>
                <td><?= $j->nomor_kwitansi; ?></td>
            </tr>

        <?php endforeach; ?>
    </table>
</body>

</html>

<script type="text/javascript">
    window.print();
</script>