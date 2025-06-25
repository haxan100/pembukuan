<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/third_party/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
class Import extends MY_Controller
{
	
    public function __construct()
    {
        parent::__construct();
        $this->load->model('HargaModel', 'harga');
		$this->load->helper('button');
    }
	public function importHargaDetailExcel()
	{
		if ($_FILES['file']['name']) {
			$file = $_FILES['file']['tmp_name'];
			$extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
			if ($extension == 'csv') {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
			} elseif ($extension == 'xlsx') {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			} else {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
			}
			// file path
			$spreadsheet = $reader->load($_FILES['file']['tmp_name']);

			try {
				$sheet = $spreadsheet->getActiveSheet();
				$data = $sheet->toArray(null, true, true, true);
				unset($data[1]);

				foreach ($data as $row) {
					$master_harga_id = $this->input->post('id_master_harga');
					$importData = [
						'master_harga_id' => $master_harga_id,
						'merk' => $row['A'],
						'model' => $row['B'],
						'type' => $row['C'],
						'storage' => $row['D'],
						'ram' => $row['E'],
						'harga_a' => str_replace('.', '', $row['F']),
						'harga_b' => str_replace('.', '', $row['G']),
						'harga_c' => str_replace('.', '', $row['H']),
						'harga_d' => str_replace('.', '', $row['I']),
						'harga_e' => str_replace('.', '', $row['J']),
						'harga_fullset' => str_replace('.', '', $row['K']),
						'harga_promotion' => str_replace('.', '', $row['L']),
						'created_at' => date('Y-m-d H:i:s'),
					];

					$this->harga->save_detail($importData, 'master_harga_details');
				}

				echo json_encode(['status' => 'success', 'message' => 'Data berhasil diimpor.']);
			} catch (Exception $e) {
				echo json_encode(['status' => 'error', 'message' => 'Gagal memproses file Excel.']);
			}
		} else {
			echo json_encode(['status' => 'error', 'message' => 'File tidak ditemukan.']);
		}
	}


}
