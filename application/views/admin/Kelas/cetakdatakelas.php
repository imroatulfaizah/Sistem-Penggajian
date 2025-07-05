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
        <h2>Daftar Data Kelas</h2>
    </center>


    <table class="table table-bordered table-striped">
        <tr>
            <th class="text-center">No</th>
            <th class="text-center">Nama kelas</th>
        </tr>
        <?php
        $no = 1;
        foreach ($kelas as $g) : ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= $g->nama_kelas; ?></td>
            </tr>

        <?php endforeach; ?>
    </table>
</body>

</html>

<script type="text/javascript">
    window.print();
</script>