<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Setting extends MY_Controller
{

	protected $primaryKey = 'id';
	protected $table = "Settings";
	public function __construct()
	{
		parent::__construct();
		$this->load->model('SettingModel');
		$this->load->helper('button');
	}
	public function updateSettings() {
		check_login();
        check_access('setting');

        $data = [
            'wa_admin' => $this->input->post('wa_admin', TRUE),
            'version_android' => $this->input->post('version_android', TRUE),
            'version_ios' => $this->input->post('version_ios', TRUE)
        ];
        
        $update = $this->SettingModel->updateSettings($data);
        if ($update) {
            echo json_encode(['status' => 'success', 'message' => 'Pengaturan berhasil diperbarui']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal memperbarui pengaturan']);
        }
    }
	
}
