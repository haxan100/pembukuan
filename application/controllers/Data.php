<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Data extends MY_Controller
{
	public function __construct()
	{
		ini_set('max_execution_time', 0);
		ini_set('memory_limit', '2048M');
		parent::__construct();
		$this->load->model('RoleModel');
		$this->load->model('AdminModel');

		$this->load->helper('url');
		$this->load->helper('button');
	}
	
	public function getAdmins()
    {
        $postData = $this->input->post();
        $admins = $this->AdminModel->getFilteredAdmins($postData);

        $datatable = [
            'draw' => $postData['draw'] ?? 1,
            'recordsTotal' => $admins['totalRecords'],
            'recordsFiltered' => $admins['filteredRecords'],
            'data' => []
        ];

		$no = $this->input->post('start') + 1;
		foreach ($admins['data'] as $admin) {
			$datatable['data'][] = [
				'no' => $no++,
				'username' => htmlspecialchars($admin->username, ENT_QUOTES, 'UTF-8'),
				'role_name' => htmlspecialchars($admin->role_name, ENT_QUOTES, 'UTF-8'),
				'created_at' => htmlspecialchars($admin->created_at, ENT_QUOTES, 'UTF-8'),
				'updated_at' => htmlspecialchars($admin->updated_at ?? '-', ENT_QUOTES, 'UTF-8'),
				'actions' => '<button class="btn btn-warning btn-edit" data-id="' . $admin->id_admin . '">Edit</button>
							  <button class="btn btn-danger btn-delete" data-id="' . $admin->id_admin . '">Hapus</button>'
			];
		}

        echo json_encode($datatable);
    }
	

}
