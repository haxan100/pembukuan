<?php
require_once APPPATH . '../vendor/autoload.php'; // Pastikan ini sesuai dengan lokasi folder vendor Anda

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

if (!function_exists('generate_jwt')) {
    function generate_jwt($payload)
    {
        $key = "jwt-plusminus"; // Ganti dengan secret key Anda
        $payload['iat'] = time();
        $payload['exp'] = time() + (60 * 60); // 1 jam
        return JWT::encode($payload, $key, 'HS256');
    }
}

if (!function_exists('validate_jwt')) {
    function validate_jwt($token)
    {
        $key = "jwt-plusminus"; // Ganti dengan secret key Anda
        try {
            return JWT::decode($token, new Key($key, 'HS256'));
        } catch (Exception $e) {
            return false;
        }
    }
}
