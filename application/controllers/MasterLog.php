<?php
class MasterLog extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('LogModel');
	}
	public function getLogs()
	{
		$this->load->model('LogModel');

		$startDate = $this->input->post('start_date');
		$endDate = $this->input->post('end_date');
		$category = $this->input->post('category');
		$datepicker = $this->input->post('datepicker');
		$year = $this->input->post('year');

		$logs = $this->LogModel->getFilteredLogs($startDate, $endDate,$category,$year,$datepicker);
		$datatable = [
			'draw' => $this->input->post('draw') ?? 1,
			'recordsTotal' => $logs['totalRecords'],
			'recordsFiltered' => $logs['filteredRecords'],
			'data' => []
		];

		$no = $this->input->post('start') + 1;
		foreach ($logs['data'] as $log) {
			$datatable['data'][] = [
				'no' => $no++,
				'kategori_log' => $log->nama_kategori_log,
				'admin_name' => $log->jenis_user ." <br> ". $log->admin_name, // Dari tabel admin
				'log_message' => $log->log_message,
				'created_at' => $log->created_at
			];
		}

		echo json_encode($datatable);
	}


	public function getCategories()
	{
		$categories = $this->LogModel->getCategories();
		echo json_encode(['status' => 'success', 'data' => $categories]);
	}
}
