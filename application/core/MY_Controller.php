<?php
// File: application/core/MY_Controller.php4

class MY_Controller extends CI_Controller
{
	public $data = [];

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(['url', 'form', 'global']);
		$this->load->library(['session']);
		$this->data['pageTitle'] = 'Default Title';
		$this->load->helper('button');
	}

	protected function render($view, $data)
	{
		$data['header'] = array(
			'title' => isset($data['pageTitle']) && !empty($data['pageTitle']) ? $data['pageTitle'] : 'dashboard',
			'page' => isset($data['page']) && !empty($data['page']) ? $data['page'] : 'index',
		);
		$this->load->view('Admin/Header', $data);
		$this->load->view($view, $data);
		$this->load->view('Admin/Footer', $data);
	}
	protected function isLoggedIn()
	{
		return $this->session->userdata('user_id') !== null;
	}
	protected function requiresAuthentication()
	{
		$publicRoutes = ['login', 'register', 'forgot_password'];
		return !in_array($this->router->fetch_class(), $publicRoutes);
	}
	protected function setFlashData($key, $message)
	{
		$this->session->set_flashdata($key, $message);
	}
	protected function getFlashData($key)
	{
		return $this->session->flashdata($key);
	}
	protected function jsonResponse($data, $status = 200)
	{
		$this->output
			->set_content_type('application/json')
			->set_status_header($status)
			->set_output(json_encode($data));
		exit;
		die;
	}
	protected function Res($data, $status, $pesan)
	{
		$data = array(
			'status' => $status,
			'message' => $pesan
		);
		echo json_encode($data);
		die;
	}
	protected function paginate($model, $conditions = [], $limit = 10, $offset = 0, $orderBy = null)
	{
		$this->load->model($model);
		$modelInstance = new $model;

		$results = $modelInstance->paginate($limit, $offset, $conditions, $orderBy);
		$totalRows = $modelInstance->countAll();

		return [
			'results' => $results,
			'pagination' => [
				'total_rows' => $totalRows,
				'per_page' => $limit,
				'current_page' => ceil(($offset + 1) / $limit),
				'total_pages' => ceil($totalRows / $limit),
			],
		];
	}
	protected function log($kategoriLogId, $jenisUser, $idUser, $details = [])
	{
		$logMessage = json_encode($details);
		$this->load->model('LogModel');
		$this->LogModel->logAction($kategoriLogId, $jenisUser, $idUser, $logMessage);
	}
	protected function ResAPI($dataR, $status, $pesan, $code = 501)
	{
		if ($status == true) $code = 200;

		$data = array(
			'code' => $code,
			'status' => $status,
			'message' => $pesan,
			'data' => $dataR
		);
		echo json_encode($data);
		die;
	}
	public function send_fcm($data)
	{
		$this->load->helper('fcm');
		$jsonKeyPath = FCPATH . 'application/keys/firebase-service-account.json';
		if (!$data) {
			return $this->output
				->set_content_type('application/json')
				->set_output(json_encode([
					'status' => false,
					'message' => 'Data JSON tidak valid atau kosong',
				]));
		}

		// Ambil data dari JSON
		$deviceToken = $data['token'] ?? null;
		$title = $data['title'] ?? null;
		$body = $data['body'] ?? null;
		$extraData = $data['data'] ?? [];

		if (!$deviceToken || !$title || !$body) {
			return $this->output
				->set_content_type('application/json')
				->set_output(json_encode([
					'status' => false,
					'message' => 'Token, title, dan body diperlukan',
				]));
		}

		try {
			// Generate OAuth 2.0 Token
			$accessToken = generate_firebase_token($jsonKeyPath);

			// Kirim notifikasi
			$result = send_fcm_notification($accessToken, $deviceToken, $title, $body, $extraData);

			if ($result['http_code'] === 200) {
				$response = [
					'status' => true,
					'message' => 'Notifikasi berhasil dikirim',
					'data' => $result['response'],
				];
			} else {
				$response = [
					'status' => false,
					'message' => 'Gagal mengirim notifikasi',
					'error' => $result['response'],
				];
			}
		} catch (Exception $e) {
			$response = [
				'status' => false,
				'message' => 'Terjadi kesalahan',
				'error' => $e->getMessage(),
			];
		}

		return $this->output
			->set_content_type('application/json')
			->set_output(json_encode($response));
	}
	function validate_jwt($token)
	{
		$decoded = validate_jwt($token);
		if ($decoded == false) return $this->ResAPI([], false, "Please Login", 403);
		return $decoded;
	}
	public function getTokoMitraByIdToko($id_toko)
	{
		$this->db->select('toko.id_toko, toko.nama_toko, toko.username, master_mitra.nama_mitra');
		$this->db->from('toko');
		$this->db->join('master_mitra', 'toko.id_mitra = master_mitra.id_master_mitra');
		$this->db->where('toko.id_toko', $id_toko);

		$query = $this->db->get();
		return $query->row(); // Mengembalikan satu row
	}
	function getSlidersData()
	{
		$sliders = $this->SliderModel->getAllSliders();
		return array_map(function ($slider) {
			return [
				'image' => base_url('uploads/sliders/') . $slider->image,
				'caption' => $slider->caption
			];
		}, $sliders);
	}

}
