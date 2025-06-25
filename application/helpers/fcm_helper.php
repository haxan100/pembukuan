<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . '../vendor/autoload.php'; // Path ke autoload Composer
use Google\Client;

if (!function_exists('generate_firebase_token')) {
    function generate_firebase_token($jsonKeyPath)
    {
        $client = new Client();
        $client->setAuthConfig($jsonKeyPath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');

        // Mengambil Access Token
        $accessToken = $client->fetchAccessTokenWithAssertion();
        return $accessToken['access_token'];
    }
}



if (!function_exists('send_fcm_notification')) {
    function send_fcm_notification($accessToken, $to, $title, $body, $data = [])
    {
        $url = 'https://fcm.googleapis.com/v1/projects/plusminus-3d9e5/messages:send';

        $message = [
            "message" => [
                "token" => $to,
                "notification" => [
                    "title" => $title,
                    "body" => $body,
                ],
                "data" => $data, // Pastikan $data adalah array key-value
            ]
        ];

        $headers = [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'response' => json_decode($response, true),
            'http_code' => $httpCode,
        ];
    }
}


// if (!function_exists('send_fcm_notification')) {
//     function send_fcm_notification($to, $title, $body, $data = []) {
//         $url = 'https://fcm.googleapis.com/fcm/send';
//         $serverKey = 'AIzaSyBYX7Je9BK3QzDxmaFbh-jKzzIAw7h0SXs'; // Ganti dengan Server Key Anda

//         $notification = [
//             'title' => $title,
//             'body' => $body,
//             'icon' => isset($data['icon']) ? $data['icon'] : 'https://example.com/icon.png',
//             'click_action' => isset($data['click_action']) ? $data['click_action'] : 'https://example.com'
//         ];

//         $payload = [
//             'to' => $to,
//             'notification' => $notification,
//             'data' => $data // Data tambahan, jika ada
//         ];

//         $headers = [
//             'Authorization: key=' . $serverKey,
//             'Content-Type: application/json',
//         ];

//         $ch = curl_init();

//         curl_setopt($ch, CURLOPT_URL, $url);
//         curl_setopt($ch, CURLOPT_POST, true);
//         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//         curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

//         $result = curl_exec($ch);
//         curl_close($ch);

//         return $result;
//     }
// }
