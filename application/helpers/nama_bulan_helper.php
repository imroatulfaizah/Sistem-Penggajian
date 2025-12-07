<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('nama_bulan')) {
    function nama_bulan($bulanTahun)
    {
        $bulan = substr($bulanTahun, 0, 2);
        $tahun = substr($bulanTahun, 2, 4);

        $daftar = [
            '01' => 'Januari',   '02' => 'Februari', '03' => 'Maret',
            '04' => 'April',     '05' => 'Mei',      '06' => 'Juni',
            '07' => 'Juli',      '08' => 'Agustus',  '09' => 'September',
            '10' => 'Oktober',   '11' => 'November', '12' => 'Desember'
        ];

        return ($daftar[$bulan] ?? 'Bulan') . ' ' . $tahun;
    }
}
