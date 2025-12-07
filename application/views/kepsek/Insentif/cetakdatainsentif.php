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
        <img src="<?= base_url('assets/img/mts.png'); ?>" alt="Logo MTS Nurul Mubtadiin" style="width: 100px; height: auto; margin-bottom: 15px;">
        <h1>MTS Nurul Mubtadiin</h1>
        <h2>Daftar Data Insentif</h2>
    </center>


    <table class="table table-bordered table-striped">
        <tr>
        <th class="text-center">No</th>
        <th class="text-center">NIP</th>
        <th class="text-center">Nama Insentif</th>
        <th class="text-center">Nominal Tunjangan</th>
        <th class="text-center">Status Pembayaran</th>
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
            </tr>

        <?php endforeach; ?>
    </table>
</body>

</html>

<script type="text/javascript">
    window.print();
</script>