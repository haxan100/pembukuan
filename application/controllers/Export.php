<?php
require APPPATH . '/third_party/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Export extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('HargaModel', 'harga');
    }

    public function exportHargaDetailExcel($id)
	{
		// Ambil data dari database dengan join ke master_harga
		$this->load->model('HargaModel', 'harga');
		$data = $this->harga->getAllDetailsWithMasterHarga($id);

		// Inisialisasi Spreadsheet
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		// Header Kolom
		$sheet->setCellValue('A1', 'No')
			->setCellValue('B1', 'Judul Harga')
			->setCellValue('C1', 'Periode Awal')
			->setCellValue('D1', 'Periode Akhir')
			->setCellValue('E1', 'Merk')
			->setCellValue('F1', 'Model')
			->setCellValue('G1', 'Type')
			->setCellValue('H1', 'Storage')
			->setCellValue('I1', 'Ram')
			->setCellValue('J1', 'Harga A')
			->setCellValue('K1', 'Harga B')
			->setCellValue('L1', 'Harga C')
			->setCellValue('M1', 'Harga D')
			->setCellValue('N1', 'Harga E')
			->setCellValue('O1', 'Harga F')
			->setCellValue('P1', 'Harga G')
			->setCellValue('Q1', 'Harga H')
			->setCellValue('R1', 'Harga I')
			->setCellValue('S1', 'Harga J')
			->setCellValue('T1', 'Harga Fullset')
			->setCellValue('U1', 'Harga Promotion');

		// Data dari Database
		$rowNumber = 2; // Mulai dari baris kedua
		$no = 1;
		foreach ($data as $row) {
			$sheet->setCellValue('A' . $rowNumber, $no++)
				->setCellValue('B' . $rowNumber, $row->judul_harga)
				->setCellValue('C' . $rowNumber, $row->periode_awal)
				->setCellValue('D' . $rowNumber, $row->periode_akhir)
				->setCellValue('E' . $rowNumber, $row->merk)
				->setCellValue('F' . $rowNumber, $row->model)
				->setCellValue('G' . $rowNumber, $row->type)
				->setCellValue('H' . $rowNumber, $row->storage)
				->setCellValue('I' . $rowNumber, $row->ram)
				->setCellValue('J' . $rowNumber, number_format($row->harga_a, 0, ',', '.'))
				->setCellValue('K' . $rowNumber, number_format($row->harga_b, 0, ',', '.'))
				->setCellValue('L' . $rowNumber, number_format($row->harga_c, 0, ',', '.'))
				->setCellValue('M' . $rowNumber, number_format($row->harga_d, 0, ',', '.'))
				->setCellValue('N' . $rowNumber, number_format($row->harga_e, 0, ',', '.'))
				->setCellValue('O' . $rowNumber, number_format($row->harga_f, 0, ',', '.'))
				->setCellValue('P' . $rowNumber, number_format($row->harga_g, 0, ',', '.'))
				->setCellValue('Q' . $rowNumber, number_format($row->harga_h, 0, ',', '.'))
				->setCellValue('R' . $rowNumber, number_format($row->harga_i, 0, ',', '.'))
				->setCellValue('S' . $rowNumber, number_format($row->harga_j, 0, ',', '.'))
				->setCellValue('T' . $rowNumber, number_format($row->harga_fullset, 0, ',', '.'))
				->setCellValue('U' . $rowNumber, number_format($row->harga_promotion, 0, ',', '.'));
			$rowNumber++;
		}

		// Atur Header untuk Download
		$filename = 'Harga_Detail_' . date('Y-m-d_H-i-s') . '.xlsx';
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '"');
		header('Cache-Control: max-age=0');

		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output'); // Kirim file ke browser
		exit;
	}

}
