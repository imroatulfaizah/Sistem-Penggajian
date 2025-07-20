<?php
function generate_daily_unique_code($datetime = null) {
    $secret = 'qr-secret-key';
    $now = $datetime ?? new DateTime('now', new DateTimeZone('Asia/Jakarta'));

    $hour = (int)$now->format('H');
    $session = ($hour >= 8 && $hour < 17) ? 'pagi' : 'sore';
    $date = $now->format('Y-m-d');

    $raw = $date . '-' . $session . '-' . $secret;

    // Hash, lalu encode ke base62
    $hash = hash('sha256', $raw, true); // raw binary
    return substr(base62_encode($hash), 0, 8); // ambil 8 karakter
}

function base62_encode($data) {
    $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $num = '0';

    foreach (unpack('C*', $data) as $byte) {
        $num = bcmul($num, '256');
        $num = bcadd($num, (string)$byte);
    }

    $base62 = '';
    while (bccomp($num, '0') > 0) {
        $rem = bcmod($num, '62');
        $base62 = $chars[(int)$rem] . $base62;
        $num = bcdiv($num, '62', 0);
    }

    return $base62;
}
