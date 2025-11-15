<?php

function generate_daily_unique_code($datetime = null)
{
    $secret = 'qr-secret-key';
    $now = $datetime ?? new DateTime('now', new DateTimeZone('Asia/Jakarta'));

    $date = $now->format('Y-m-d');

    $raw = $date . '-' . $secret;

    $hash = hash('sha256', $raw, true);
    
    return substr(base62_encode($hash), 0, 8);
}

function base62_encode($data)
{
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
