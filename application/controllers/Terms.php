<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Terms extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('TermsModel');
	}
	public function save()
	{
		$title = $this->input->post('title');
		$content = $this->input->post('content');

		if (empty($title) || empty($content)) {
			echo json_encode(['status' => 'error', 'message' => 'Judul dan konten tidak boleh kosong!']);
			return;
		}

		$data = [
			'title' => $title,
			'content' => $content,
			'updated_at' => date('Y-m-d H:i:s')
		];

		$result = $this->TermsModel->saveTerms($data);

		if ($result) {
			echo json_encode(['status' => 'success', 'message' => 'Syarat & Ketentuan berhasil disimpan.']);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data.']);
		}
	}
	public function get()
	{
		$this->load->model('TermsModel'); // Pastikan Anda memiliki model TermsModel
		$terms = $this->TermsModel->getLatestTerms(); // Ambil data terbaru

		if ($terms) {
			echo json_encode([
				'status' => 'success',
				'data' => $terms
			]);
		} else {
			echo json_encode([
				'status' => 'error',
				'message' => 'Tidak ada data syarat dan ketentuan yang ditemukan.'
			]);
		}
	}
	function index() {
		$this->load->view('Admin/term_view'); // Memuat view HTML
	}
}
