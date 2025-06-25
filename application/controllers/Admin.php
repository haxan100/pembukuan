<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();

	}
	public function index()
	{
		check_login();
		$obj['ci'] = $this;
		$obj['page'] = 'dashboard'; // Set halaman aktif
		$obj['pageTitle'] = 'Dashboard';
		$this->load->view('Admin/Index', $obj);
	}
	public function master_role()
	{
		$obj['ci'] = $this;
		$obj['pageTitle'] = 'Manajemen Role Admin';
		check_access('master_role');
		$obj['page'] = 'master_role';
		$this->render('Admin/Role_admin', $obj); // Render ke view role_admin
	}
	function login()
	{
		$this->load->view('Admin/Login_admin');
	}
	public function process_login()
	{
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$admin = $this->AdminModel->findAdmin($username);
		if ($admin == null) {
			echo json_encode([
				'status' => 'error',
				'message' => 'Username Tidak ada!'
			]);
			return;
		}
		if ($admin && decrypt_password($admin->password) === $password) {
			// Simpan data admin ke session
			$this->session->set_userdata([
				'user_id' => $admin->id_admin,
				'username' => $admin->username,
				'id_role' => $admin->id_role,
			]);

			echo json_encode([
				'status' => 'success',
				'message' => 'Login berhasil!',
				'redirect' => base_url()."Admin"
			]);
			log_action(1, "superadmin",  $admin->id_admin,  ' Login Admin  ' .  $admin->username);

		} else {
			echo json_encode([
				'status' => 'error',
				'message' => 'Username atau password salah!'
			]);
		}
	}
	public function addAdmin()
	{
		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$id_role = $this->input->post('id_role');

		$data = [
			'username' => $username,
			'password' => encrypt_password($password),
			'id_role' => $id_role,
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => null,
		];

		if ($this->db->insert('admin', $data)) {
			$response = ['status' => 'success', 'message' => 'Admin berhasil ditambahkan.'];
			log_action(3, "superadmin",  $_SESSION['user_id'],  ' Tambah Admin  ' .  $username);

		} else {
			$response = ['status' => 'error', 'message' => 'Gagal menambahkan admin.'];
		}

		echo json_encode($response);
	}
	public function findAdmin($username)
	{
		return $this->db->get_where('admin', ['username' => $username])->row();
	}
	public function logout()
	{
		$this->session->unset_userdata(['user_id', 'username', 'id_role']);
		$this->session->sess_destroy();
	}
	function master_log()
	{
		$obj['ci'] = $this;
		$obj['pageTitle'] = 'Master Log';
		$obj['page'] = 'master_log';
		check_access('master_log');
		$this->render('Admin/Log', $obj); // Render ke view role_admin

	}
	public function master_admin()
	{
		check_access('master_admin');
		$obj['pageTitle'] = 'Manajemen Admin';
		$obj['ci'] = $this;
		$this->render('Admin/Master_admin', $obj);
	}
	public function getAdminById()
	{
		$id = $this->input->post('id');
		$admin = $this->AdminModel->findById($id,true);
		if ($admin) {
			$admin->password = decrypt_password($admin->password);
			echo json_encode(['status' => 'success', 'data' => $admin]);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Admin tidak ditemukan.']);
		}
	}
	public function updateAdmin()
	{
		$id_admin = $this->input->post('id');
		$data = [
			'username' => $this->input->post('username'),
			'id_role' => (int) $this->input->post('id_role'),
			'updated_at' => date('Y-m-d H:i:s')
		];

		if ($this->input->post('password')) {
			$data['password'] = encrypt_password($this->input->post('password'));
		}
		// var_dump($data);die;

		if ($this->AdminModel->update($id_admin, $data)) {
			$response = ['status' => 'success', 'message' => 'Admin berhasil diupdate.'];
			log_action(4, "superadmin",  $_SESSION['user_id'],  ' Update Admin  ' .  $this->input->post('username'));

		} else {
			$response = ['status' => 'error', 'message' => 'Gagal mengupdate admin.'];
		}

		echo json_encode($response);
	}
	public function deleteAdmin()
	{
		$id_admin = $this->input->post('id');

		if ($this->AdminModel->delete($id_admin)) {
			$response = ['status' => 'success', 'message' => 'Admin berhasil dihapus.'];
			log_action(5, "superadmin",  $_SESSION['user_id'],  ' Hapus Admin  ' .  $this->input->post('username'));

		} else {
			$response = ['status' => 'error', 'message' => 'Gagal menghapus admin.'];
		}

		echo json_encode($response);
	}
	public function getRoles()
	{
		$roles = $this->RoleModel->getAll();
		echo json_encode(['status' => 'success', 'data' => $roles]);
	}
	function syarat_ketentuan() {
		
		check_access('terms');
		$obj['pageTitle'] = 'terms';
		$obj['page'] = 'terms';
		$obj['ci'] = $this;
		$this->render('Admin/syarat_ketentuan', $obj);

	}
	function kebijakan() {
		
		check_access('policy');
		$obj['pageTitle'] = 'policy';
		$obj['page'] = 'policy';
		$obj['ci'] = $this;
		$this->render('Admin/kebijakan', $obj);

	}
	function master_harga() {
		
		check_access('master_harga');
		$obj['pageTitle'] = 'master_harga';
		$obj['page'] = 'master_harga';
		$obj['ci'] = $this;
		$this->render('Admin/Master_harga', $obj);

	}
	function master_harga_detail($id) {
		$data = $this->harga->get_by_id($id);
		if (!$data) {
			show_error('Data tidak ditemukan', 404);
		}
		check_access('master_harga');
		$obj['pageTitle'] = 'master_harga';
		$obj['page'] = 'master_harga';
		$obj['ci'] = $this;
		$obj['id_harga'] = $id;
		
		$this->render('Admin/Master_harga_detail', $obj);

	}
	function master_mitra() {

		check_access('master_mitra');
		$obj['pageTitle'] = 'master_mitra';
		$obj['page'] = 'master_mitra';
		$obj['ci'] = $this;		
		$this->render('Admin/Master_mitra', $obj);
	}
	function master_mitra_detail($id) {
		$data = $this->MitraModel->get_by_id($id);
		if (!$data) {
			show_error('Data tidak ditemukan', 404);
		}
		// var_dump($data);die;
		check_access('master_mitra');
		$obj['pageTitle'] = 'master_mitra_detail';
		$obj['page'] = 'master_mitra_detail';
		$obj['ci'] = $this;
		$obj['id_mitra'] = $id;
		$obj['data'] = $data;
		$this->render('Admin/Master_mitra_detail', $obj);
	}
	function slider() {
		check_login();
		$obj['pageTitle'] = 'slider';
		$obj['page'] = 'slider';
		$obj['ci'] = $this;
		$this->render('Admin/slider', $obj);
	}
	public function setting() {
        $obj['ci'] = $this;
        $obj['pageTitle'] = 'Manajemen Pengaturan';
        check_access('setting');
        $obj['settings'] = $this->SettingModel->getSettings();
        $obj['page'] = 'setting';
        $this->render('Admin/Settings_admin', $obj);
    }
}
