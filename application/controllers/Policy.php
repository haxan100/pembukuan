<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Policy extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('PolicyModel'); // Load model kebijakan
    }

    // Endpoint untuk menyimpan kebijakan
    public function savePolicy()
    {
        $data = [
            'title' => $this->input->post('title'),
            'content' => $this->input->post('content'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($this->PolicyModel->savePolicy($data)) {
            echo json_encode(['status' => 'success', 'message' => 'Kebijakan berhasil disimpan']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan kebijakan']);
        }
    }

    // Endpoint untuk mendapatkan kebijakan
    public function getPolicy()
    {
        $policy = $this->PolicyModel->getPolicy();
        if ($policy) {
            echo json_encode(['status' => 'success', 'data' => $policy]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Kebijakan tidak ditemukan']);
        }
    }
}
