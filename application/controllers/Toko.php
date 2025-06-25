<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Toko extends MY_Controller
{

	protected $primaryKey = 'id_toko';
	protected $table = "toko";
	public function __construct()
	{
		parent::__construct();
		$this->load->model('TokoModel');
		$this->load->helper('button');
	}

	public function getDTMasterMitra()
	{
		$postData = $this->input->post();
		$harga = $this->TokoModel->get_datatables($postData);
		$datatable = [
			'draw' => $postData['draw'] ?? 1,
			'recordsTotal' => $this->TokoModel->count_all(),
			'recordsFiltered' => $this->TokoModel->count_filtered($postData),
			'data' => []
		];

		$no = $this->input->post('start') + 1;
		foreach ($harga as $row) {
			$dataEdit = '
			data-id="' . htmlspecialchars($row->id_toko, ENT_QUOTES, 'UTF-8') . '
			"data-nama_toko="' . htmlspecialchars($row->nama_toko, ENT_QUOTES, 'UTF-8') . '
			" 
			';

			$dataHapus = '
			data-id="' . htmlspecialchars($row->id_toko, ENT_QUOTES, 'UTF-8') . '" 
			data-nama_toko="' . htmlspecialchars($row->nama_toko, ENT_QUOTES, 'UTF-8') . '"
			';

			$fields = [
				$no++, // Nomor
				htmlspecialchars($row->nama_toko, ENT_QUOTES, 'UTF-8'),
				htmlspecialchars($row->username, ENT_QUOTES, 'UTF-8'),
				htmlspecialchars($row->email, ENT_QUOTES, 'UTF-8'),
				htmlspecialchars($row->nomor_telpon, ENT_QUOTES, 'UTF-8'),
				htmlspecialchars($row->created_at, ENT_QUOTES, 'UTF-8'),
				htmlspecialchars($row->last_login, ENT_QUOTES, 'UTF-8'),
				createButton('edit', $dataEdit, 'Edit', 'far fa-edit', 'btn-edit') .
				createButton('delete', $dataHapus, 'Hapus', 'fas fa-trash', 'btn-delete')
			];

			$datatable['data'][] = $fields;
		}

		echo json_encode($datatable);
	}
	public function add()
	{

		$email = $this->input->post('email');
		$username = $this->input->post('username');
		$nomor_telpon = $this->input->post('nomor_telpon');
		$existingEmail = $this->TokoModel->get_by_field('email', $email);
		if ($existingEmail) {
			echo json_encode([
				'status' => 'error',
				'message' => 'Email sudah digunakan oleh pengguna lain.',
			]);
			return;
		}
		// Cek apakah username sudah ada
		$existingUsername = $this->TokoModel->get_by_field('username', $username);
		if ($existingUsername) {
			echo json_encode([
				'status' => 'error',
				'message' => 'Username sudah digunakan oleh pengguna lain.',
			]);
			return;
		}
		// Cek apakah nomor_telpon sudah ada
		$existingTelpon = $this->TokoModel->get_by_field('nomor_telpon', $nomor_telpon);
		if ($existingTelpon) {
			echo json_encode([
				'status' => 'error',
				'message' => 'nomor_telpon sudah digunakan oleh pengguna lain.',
			]);
			return;
		}
		$data = [
			'nama_toko' => $this->input->post('nama_toko'),
			'id_mitra' => $this->input->post('id_mitra'),
			'username' => $username,
			'nomor_telpon' => $nomor_telpon,
			'email' => $email,
			'password' => encrypt_password_toko($this->input->post('password')),
		];


		$this->TokoModel->save($data);
		$message = 'Data berhasil ditambahkan';

		echo json_encode(['status' => 'success', 'message' => $message]);
	}
	public function updateHarga()
	{
		$id = $this->input->post('detail_id');
		$username = $this->input->post('username');
		$email = $this->input->post('email');
		$nomor_telpon = $this->input->post('nomor_telpon');

		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			echo json_encode(['status' => 'error', 'message' => 'Format email tidak valid.']);
			return;
		}
		// Validasi username dan email untuk menghindari duplikasi
		$existingUser = $this->TokoModel->get_by_username_or_email($username, $email, $id); // Tambahkan pengecualian id jika update
		if ($existingUser) {
			$errorMessage = 'Username atau email sudah digunakan oleh pengguna lain.';
			if ($existingUser->username == $username) {
				$errorMessage = 'Username sudah digunakan oleh pengguna lain.';
			}
			if ($existingUser->email == $email) {
				$errorMessage = 'Email sudah digunakan oleh pengguna lain.';
			}

			echo json_encode(['status' => 'error', 'message' => $errorMessage]);
			return;
		}
		$data = [
			'nama_toko' => $this->input->post('nama_toko'),
			'id_mitra' => $this->input->post('id_mitra'),
			'nomor_telpon' => $nomor_telpon,
			'username' => $username,
			'email' => $email,
			'password' => encrypt_password_toko($this->input->post('password')),
		];

		if ($id) {
			// Update data
			$this->TokoModel->update_detail($id, $data, 'toko', 'id_toko');
			$message = 'Data berhasil diperbarui';
		} else {
			// Insert data
			$this->TokoModel->save($data);
			$message = 'Data berhasil ditambahkan';
		}

		echo json_encode(['status' => 'success', 'message' => $message]);
	}

	public function delete()
	{
		$id = $this->input->post('id');
		$this->TokoModel->softDelete($id, 'id_toko', "toko");
		echo json_encode(['status' => 'success', 'message' => 'Data berhasil dihapus']);
	}

	public function getById()
	{
		$id = $this->input->post('id');
		$data = $this->TokoModel->get_by_id($id);
		$data->password = decrypt_password_toko($data->password);
		echo json_encode(['status' => 'success', 'data' => $data]);
	}

	public function getHargaDetailById()
	{
		$id = $this->input->post('id');
		if ($id) {
			// Query untuk mendapatkan data berdasarkan ID
			$detail = $this->harga->get_detail_by_id($id, 'master_harga_details');
			if ($detail) {
				echo json_encode(['status' => 'success', 'data' => $detail]);
			} else {
				echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
			}
		} else {
			echo json_encode(['status' => 'error', 'message' => 'ID tidak valid']);
		}
	}
}
