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
        <h2>Daftar Data Penempatan</h2>
    </center>


    <table class="table table-bordered table-striped">
        <tr>
            <th class="text-center">No</th>
            <th class="text-center">Pelajaran</th>
            <th class="text-center">Kelas</th>
            <th class="text-center">Tahun Akademik</th>
            <th class="text-center">NIP</th>
            <th class="text-center">Jam Mulai</th>
            <th class="text-center">Jam Akhir</th>
            <th class="text-center">Total Jam</th>
            <th class="text-center">Keterangan</th>
        </tr>
        <?php
        $no = 1;
        foreach ($penempatan as $g) : ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $g->nama_pelajaran; ?></td>
                <td><?= $g->nama_kelas; ?></td>
                <td><?= $g->tahun_akademik; ?></td>
                <td><?= $g->nip; ?></td>
                <td><?= $g->jam_mulai; ?></td>
                <td><?= $g->jam_akhir; ?></td>
                <td><?= $g->total_jam; ?></td>
                <td><?= $g->keterangan; ?></td>
            </tr>

        <?php endforeach; ?>
    </table>
</body>

</html>

<script type="text/javascript">
    window.print();
</script>