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
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
        }
        th {
            text-align: center;
        }
    </style>
</head>

<body>

    <center>
        <img src="<?= base_url('assets/img/mts.png'); ?>" style="width: 100px; margin-bottom: 15px;">
        <h1>MTS Nurul Mubtadiin</h1>
        <h2>Daftar Data Akademik</h2>
    </center>

    <table>
        <tr>
            <th>No</th>
            <th>Tahun Akademik</th>
            <th>Semester</th>
            <th>Nama Akademik</th>
            <th>Aktif?</th>
        </tr>

        <?php $no = 1; foreach ($akademik as $g): ?>
        <tr>
            <td class="text-center"><?= $no++; ?></td>
            <td><?= $g->tahun_akademik; ?></td>
            <td><?= $g->semester; ?></td>
            <td><?= $g->nama_akademik; ?></td>
            <td class="text-center"><?= $g->is_aktif == 1 ? 'Aktif' : 'Tidak'; ?></td>
        </tr>
        <?php endforeach; ?>

    </table>

</body>

</html>

<script>
    window.print();
</script>
