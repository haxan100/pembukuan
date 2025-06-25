<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Notification extends CI_Controller
{
	private $onesignal_url = 'https://onesignal.com/api/v1/notifications';
	private $app_id = '29c768d7-822e-4003-bfa1-7e8f5b623b63'; // Ganti dengan App ID Anda
	private $rest_api_key = 'os_v2_app_fhdwrv4cfzaahp5bp2hvwyr3mnetzc5bi6qeaunsdopkrznllvbp5isb5ndlmghw4wcs6deurplqlrg2iivxfgsvdfhaigwvpre6v6a'; // Ganti dengan REST API Key Anda
	private $rest_api = 'etzc5bi6qeaunsdopkrznllvb'; // Ganti dengan REST API Key Anda
	public function send_notification()
	{
		// Ambil data dari request POST
		$player_id = $this->input->post('player_id'); // Ambil OneSignal ID dari request
		$heading = $this->input->post('heading');
		$content = $this->input->post('content');

		if (!$player_id || !$heading || !$content) {
			echo json_encode(['status' => false, 'message' => 'Data tidak lengkap.']);
			return;
		}

		// Data untuk dikirim ke OneSignal
		$fields = [
			'app_id' => $this->app_id,
			'include_player_ids' => [$player_id], // Targetkan berdasarkan OneSignal ID
			'headings' => ['en' => $heading], // Judul notifikasi
			'contents' => ['en' => $content], // Isi notifikasi
		];

		$fields_json = json_encode($fields);

		// Kirim ke OneSignal API
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->onesignal_url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, [
			'Content-Type: application/json; charset=utf-8',
			'Authorization: Basic ' . $this->rest_api_key,
		]);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_json);

		$response = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		if ($http_code == 200) {
			echo json_encode(['status' => true, 'message' => 'Notifikasi berhasil dikirim.', 'response' => json_decode($response)]);
		} else {
			echo json_encode(['status' => false, 'message' => 'Gagal mengirim notifikasi.', 'response' => json_decode($response)]);
		}
	}
}
