<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (!function_exists('send_socket_notification')) {
    // function send_socket_notification($message, $transaction_code)
    // {
    //     $ci =& get_instance();
    //     $socket_url = $ci->config->item('socket_server_url'); // Ambil URL dari config
        
    //     $socket_data = [
    //         'message' => $message,
    //         'transaction_code' => $transaction_code
    //     ];

    //     $ch = curl_init($socket_url . "/send-notification");
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($socket_data));
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, [
    //         'Content-Type: application/json'
    //     ]);

    //     $response = curl_exec($ch); // Tangkap respons dari server Node.js
    //     $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    //     $curl_error = curl_error($ch);
    //     curl_close($ch);

    //     return [
    //         'http_code' => $http_code,
    //         'response' => $response,
    //         'curl_error' => $curl_error
    //     ];
    // }
	function send_socket_notification($data, $event) {
		$ci =& get_instance();
		$socket_url = $ci->config->item('socket_server_url') . "/send-notification"; // Tambahkan endpoint
	
		$ch = curl_init($socket_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
			"event" => $event,
			"data" => $data
		]));
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Content-Type: application/json'
		]);
		$response = curl_exec($ch);
		curl_close($ch);
	
		// **Tambahkan debugging**
		log_message('error', "Socket notification response: " . $response);
	}
	

}
