<?php
defined('BASEPATH') or exit('No direct script access allowed');
require 'vendor/autoload.php';
class Api extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('TransaksiModel');
		$this->load->helper('jwt_helper');
		$this->load->helper('nodejs_helper');
	}
	private function formatRupiah($angka)
	{
		$rupiah = number_format(abs($angka), 0, ',', '.');
		return ($angka < 0 ? '-Rp' : 'Rp') . $rupiah;
	}
	public function transaksi()
	{
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$data = $this->TransaksiModel->getHpTransaksiByBulanTahun($bulan, $tahun);

		if ($data) {
			// Tambahkan selisih
			foreach ($data as &$item) {
				if ($item->status === 'laku' && !is_null($item->harga_jual)) {
					$item->selisih = (int)$item->harga_jual - (int)$item->harga_beli;
				} else {
					$item->selisih = null;
				}
			}

			$this->ResAPI($data, true, 'Data retrieved successfully', 200);
		} else {
			$this->ResAPI([], false, 'Data not found', 404);
		}
	}
	public function jual()
	{
		$id_hp = $this->input->post('id_hp');
		$harga = $this->input->post('harga');
		$hp = $this->TransaksiModel->getHPById($id_hp);
		if (!$hp) {
			return $this->ResAPI([], false, 'Data not found', 404);
		}
		$result = $this->TransaksiModel->jualHP($id_hp, $harga);
		if ($result) {
			$this->ResAPI([
				'id_hp' => $id_hp,
				'harga_jual' => $harga,
				'status' => 'laku'
			], true, 'HP berhasil dijual', 200);
		} else {
			$this->ResAPI([], false, 'Gagal update data', 500);
		}
	}
	public function pengeluaran()
	{
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
	
		$data = $this->TransaksiModel->getPengeluaranByBulanTahun($bulan, $tahun);
	
		// Ambil total biaya admin dan ongkir dari tabel transaksi
		$transaksiSummary = $this->TransaksiModel->getTotalAdminOngkir($bulan, $tahun);
	
		$response = [
			'pengeluaran_tambahan' => $data,
			'total_admin' => (int) $transaksiSummary->total_admin,
			'total_ongkir' => (int) $transaksiSummary->total_ongkir,
			'total_admin_rupiah' => 'Rp' . number_format($transaksiSummary->total_admin, 0, ',', '.'),
			'total_ongkir_rupiah' => 'Rp' . number_format($transaksiSummary->total_ongkir, 0, ',', '.')
		];
	
		if ($data || $transaksiSummary) {
			$this->ResAPI($response, true, 'Data retrieved successfully', 200);
		} else {
			$this->ResAPI([], false, 'Data not found', 404);
		}
	}
	
	public function tambahPengeluaran()
	{
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$nominal = $this->input->post('nominal');
		$keterangan = $this->input->post('keterangan');

		// Validasi sederhana
		if (!$bulan || !$tahun || !$nominal || !$keterangan) {
			return $this->ResAPI([], false, 'Lengkapi semua field', 400);
		}

		$result = $this->TransaksiModel->insertPengeluaran($bulan, $tahun, $nominal, $keterangan);

		if ($result) {
			$this->ResAPI([
				'bulan' => $bulan,
				'tahun' => $tahun,
				'nominal' => $nominal,
				'keterangan' => $keterangan
			], true, 'Pengeluaran berhasil ditambahkan', 201);
		} else {
			$this->ResAPI([], false, 'Gagal menambahkan pengeluaran', 500);
		}
	}
	public function rekap()
	{
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');

		if (!$bulan || !$tahun) {
			return $this->ResAPI([], false, 'Bulan dan tahun harus diisi', 400);
		}

		$summary = $this->TransaksiModel->getRekapSummary($bulan, $tahun);

		// Hitung total beban dan laba bersih
		$summary['total_beban'] =
			$summary['modal_awal'] +
			$summary['total_admin'] +
			$summary['total_ongkir'] +
			$summary['pengeluaran_tambahan'];

		$summary['laba_bersih'] = $summary['total_penjualan'] - $summary['total_beban'];

		// Hitung jumlah status
		$statusCount = $this->TransaksiModel->countStatusHp($bulan, $tahun);
		$summary['jumlah_laku'] = $statusCount['jumlah_laku'];
		$summary['jumlah_belum_laku'] = $statusCount['jumlah_belum_laku'];

		// Format rupiah
		$summary['modal_awal_rupiah'] = $this->formatRupiah($summary['modal_awal']);
		$summary['total_penjualan_rupiah'] = $this->formatRupiah($summary['total_penjualan']);
		$summary['total_admin_rupiah'] = $this->formatRupiah($summary['total_admin']);
		$summary['total_ongkir_rupiah'] = $this->formatRupiah($summary['total_ongkir']);
		$summary['pengeluaran_tambahan_rupiah'] = $this->formatRupiah($summary['pengeluaran_tambahan']);
		$summary['total_beban_rupiah'] = $this->formatRupiah($summary['total_beban']);
		$summary['laba_bersih_rupiah'] = $this->formatRupiah($summary['laba_bersih']);

		// Hitung bulan lalu
		$bulanLalu = (int)$bulan - 1;
		$tahunLalu = (int)$tahun;
		if ($bulanLalu <= 0) {
			$bulanLalu = 12;
			$tahunLalu -= 1;
		}

		$prev = $this->TransaksiModel->getRekapSummary($bulanLalu, $tahunLalu);
		$statusCountprev = $this->TransaksiModel->countStatusHp($bulanLalu, $tahunLalu);

		$prev_beban =
			$prev['modal_awal'] +
			$prev['total_admin'] +
			$prev['total_ongkir'] +
			$prev['pengeluaran_tambahan'];

		$prev_laba = $prev['total_penjualan'] - $prev_beban;
		$selisih = $summary['laba_bersih'] - $prev_laba;
		$persen = $prev_laba === 0 ? 100 : ($selisih / abs($prev_laba)) * 100;

		$compare = [
			'bulanLalu' => $bulanLalu,
			'tahunLalu' => $tahunLalu,
			'prev_laba_bersih' => $prev_laba,
			'prev_laba_bersih_rupiah' => $this->formatRupiah($prev_laba),
			'selisih' => $selisih,
			'selisih_rupiah' => $this->formatRupiah($selisih),
			'persen' => round($persen, 2),
			'status_performa' => $selisih >= 0 ? 'naik' : 'turun',
			'jumlah_laku' => $statusCountprev['jumlah_laku'],
			'jumlah_belum_laku' => $statusCountprev['jumlah_belum_laku']
		];

		$this->ResAPI([
			'summary' => $summary,
			'compare' => $compare,
			'bulan' => (int)$bulan,
			'tahun' => (int)$tahun
		], true, 'Rekap berhasil diambil', 200);
	}

	public function listBelumLaku()
	{
		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');

		if (!$bulan || !$tahun) {
			return $this->ResAPI([], false, 'Bulan dan tahun harus diisi', 400);
		}

		$data = $this->TransaksiModel->getHpBelumLaku($bulan, $tahun);

		if ($data) {
			$this->ResAPI($data, true, 'Data belum laku ditemukan', 200);
		} else {
			$this->ResAPI([], false, 'Tidak ada data belum laku', 404);
		}
	}
	public function import_pdf()
	{
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'pdf';
		$config['max_size'] = 2048;
		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('pdf_file')) {
			return $this->ResAPI([], false, $this->upload->display_errors(), 400);
		}

		$uploadData = $this->upload->data();
		$filePath = $uploadData['full_path']; // ✅ Baru tersedia di sini

		$bulan = $this->input->post('bulan');
		$tahun = $this->input->post('tahun');
		$ongkir = $this->input->post('ongkir');
		$biaya_admin = $this->input->post('biaya_admin');

		// Simpan transaksi
		$this->db->insert('transaksi', [
			'bulan' => $bulan,
			'tahun' => $tahun,
			'ongkir' => $ongkir,
			'biaya_admin' => $biaya_admin,
			'created_at' => date('Y-m-d H:i:s')
		]);
		$transaksi_id = $this->db->insert_id();

		// ✅ Panggil parser setelah filePath tersedia
		$parser = new \Smalot\PdfParser\Parser();
		$pdf = $parser->parseFile($filePath);
		$text = $pdf->getText();

		$lines = array_filter(array_map('trim', explode("\n", $text)));

		$items = [];
		for ($i = 0; $i < count($lines); $i++) {
			if (stripos($lines[$i], 'IMEI') !== false) {
				$hp = $lines[$i - 1];
				$imei = trim(explode(':', $lines[$i])[1]);
				$nextLine = $lines[$i + 1];

				$grade = strtoupper(substr($nextLine, 0, 2));
				$hargaRaw = preg_replace('/\./', '', substr($nextLine, 2));
				$harga = (int)$hargaRaw;

				$items[] = [
					'hp' => $hp,
					'imei' => $imei,
					'grade' => $grade,
					'harga' => $harga
				];
			}
		}

		foreach ($items as $item) {
			var_dump($items);die;
			$this->db->insert('hp_transactions', [
				'hp' => $item['hp'],
				'imei' => $item['imei'],
				'grade' => $item['grade'],
				'harga_beli' => $item['harga'],
				'harga_jual' => null,
				'status' => 'belum_laku',
				'transaksi_id' => $transaksi_id
			]);
		}

		unlink($filePath);

		return $this->ResAPI(['inserted' => count($items)], true, 'Import berhasil', 200);
	}
	public function rekap_tahunan()
	{
		$tahun = $this->input->post('tahun');

		if (!$tahun) {
			return $this->ResAPI([], false, 'Tahun wajib diisi', 400);
		}

		$data = $this->TransaksiModel->getRekapTahunan($tahun);

		$this->ResAPI($data, true, 'Rekap tahunan berhasil diambil', 200);
	}
}
