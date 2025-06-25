<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RoleAdmin extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('RoleModel');
	}
	public function addRole()
	{
		// Ambil semua data dari POST
		$roleData = $this->input->post();

		// Validasi nama role
		if (empty($roleData['role_name'])) {
			$this->jsonResponse(['status' => 'error', 'message' => 'Role Name is required'], 400);
			return;
		}

		// Hapus key 'id' jika ada (karena ini untuk menambahkan data baru)
		unset($roleData['id']);

		// Simpan data ke database menggunakan model
		$isAdded = $this->RoleModel->addRole($roleData);
		if ($isAdded) {
			$this->Res("[]", "success" ,"Berhasil");
		} else {
			$this->Res("[]", false ,"Gagal");
		}
	}
	public function deleteRole()
	{
		$id = $this->input->post('id');
		$this->load->model('RoleModel');
	
		// Periksa apakah role sedang digunakan
		$count = $this->RoleModel->countAdminsByRole($id);
		if ($count > 0) {
			$this->jsonResponse(['status' => 'error', 'message' => "Role sedang digunakan oleh $count admin."], 400);
			return;
		}
	
		// Lanjutkan proses hapus jika tidak digunakan
		$hapus = $this->RoleModel->deleteRoleById($id);
		if ($hapus) {
			$this->Res("[]", "success" ,"Berhasil Menghapus Role");
		} else {
			$this->Res("[]", false ,"Gagal Menghapus Role");
		}
	}
	public function updateRole()
	{
		$id = $this->input->post('id');

		// Ambil semua kolom dari tabel `role`
		$fields = $this->db->list_fields('role');

		$data = [];
		foreach ($fields as $field) {
			if ($field !== 'id_role' && $field !== 'created_at' && $field !== 'updated_at') {
				// Ambil data dari POST, jika ada
				$data[$field] = $this->input->post($field) ? $this->input->post($field) : 0;
			}
		}

		// Update data role
		$this->RoleModel->update($id, $data,"role","id_role",);

		// Tambahkan log
		$this->log(4, 'superadmin', $this->session->userdata('user_id'), [
			'id_role' => $id,
			'updated_data' => $data
		]);

		echo json_encode(['status' => 'success', 'message' => 'Role updated successfully']);
	}

	// Get role by ID
	public function getRoleById()
	{
		$id = $this->input->post('id');

		// Ambil data role berdasarkan ID
		$role = $this->RoleModel->findByIdR('role', $id, true);
		if ($role) {
			// Ambil semua kolom dari tabel role
			$columns = $this->db->list_fields('role');

			$permissions = [];
			foreach ($columns as $column) {
				// Abaikan kolom yang bukan permission
				if (!in_array($column, ['id_role', 'role_name', 'created_at', 'updated_at'])) {
					$permissions[] = $column . ':' . $role->{$column};
				}
			}

			echo json_encode([
				'status' => 'success',
				'data' => [
					'role_name' => $role->role_name,
					'permissions' => $permissions
				]
			]);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Role tidak ditemukan.']);
		}
	}
	public function getRoles()
	{
		$this->load->model('RoleModel');
		
		$dt = $this->RoleModel->dt_roles($this->input->post());

		$datatable = [
			'draw' => $this->input->post('draw') ?? 1,
			'recordsTotal' => $dt['totalData'],
			'recordsFiltered' => $dt['totalData'],
			'data' => []
		];
		$start = $this->input->post('start') ?? 0;
		$no = $start + 1;

		foreach ($dt['data']->result() as $row) {
			// Hitung jumlah admin dengan role ini
			$countAdmins = $this->RoleModel->countAdminsByRole($row->id_role);

			$rowData = [
				$no++, // Nomor
				$row->role_name, // Nama Role
				$row->created_at, // Tanggal Dibuat
				$row->updated_at, // Tanggal Diedit
				$countAdmins, // Jumlah Admin yang menggunakan role
				// Tombol aksi
				createButton('edit', 'data-id_role="' . $row->id_role . '"', 'Ubah', 'far fa-edit', 'btn-edit') .
				createButton('delete', 'data-id_role="' . $row->id_role . '" data-role_name="' . $row->role_name . '"', 'Hapus', 'fas fa-trash', 'btn-delete')
			];

			$datatable['data'][] = $rowData;
		}

		echo json_encode($datatable);
	}
	public function getPermissions()
	{
		$fields = $this->db->list_fields('role'); // Ambil semua kolom tabel role
		$permissions = array_filter($fields, function ($field) {
			return !in_array($field, ['id_role', 'role_name', 'created_at', 'updated_at']);
		});

		if (!empty($permissions)) {
			echo json_encode(['status' => 'success', 'data' => array_values($permissions)]); // Pastikan data adalah array
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Tidak ada permissions ditemukan.']);
		}
	}
	public function checkRoleUsage()
{
    $id = $this->input->post('id');
    $this->load->model('RoleModel');

    $count = $this->RoleModel->countAdminsByRole($id);

    if ($count > 0) {
        echo json_encode(['status' => 'in_use', 'count' => $count]);
    } else {
        echo json_encode(['status' => 'available']);
    }
}
}
