<?php
class TransaksiModel extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function getHpTransaksiByBulanTahun($bulan, $tahun)
	{
		$this->db->select('hp_transactions.*');
		$this->db->from('hp_transactions');
		$this->db->join('transaksi', 'transaksi.id = hp_transactions.transaksi_id');
		$this->db->where('transaksi.bulan', $bulan);
		$this->db->where('transaksi.tahun', $tahun);
	    $this->db->order_by('hp_transactions.id', 'DESC'); // urutkan dari yang terbaru

		$query = $this->db->get();
		return $query->result();
	}
	public function getHPById($id)
    {
        return $this->db->get_where('hp_transactions', ['id' => $id])->row();
    }
    public function jualHP($id, $harga)
    {
        $this->db->where('id', $id);
        $this->db->update('hp_transactions', [
            'harga_jual' => $harga,
            'status' => 'laku'
        ]);
        return $this->db->affected_rows() > 0;
    }
	public function getPengeluaranByBulanTahun($bulan, $tahun)
	{
		return $this->db
			->get_where('pengeluaran', [
				'bulan' => $bulan,
				'tahun' => $tahun
			])
			->result();
	}
	public function insertPengeluaran($bulan, $tahun, $nominal, $keterangan)
	{
		$data = [
			'bulan' => $bulan,
			'tahun' => $tahun,
			'nominal' => $nominal,
			'keterangan' => $keterangan,
			'created_at' => date('Y-m-d H:i:s')
		];

		return $this->db->insert('pengeluaran', $data);
	}
	public function getPengeluaranById($id)
	{
		return $this->db->get_where('pengeluaran', ['id' => $id])->row();
	}
	public function updatePengeluaran($id, $bulan, $tahun, $nominal, $keterangan)
	{
		$data = [
			'bulan' => $bulan,
			'tahun' => $tahun,
			'nominal' => $nominal,
			'keterangan' => $keterangan,
			'updated_at' => date('Y-m-d H:i:s')
		];

		$this->db->where('id', $id);
		return $this->db->update('pengeluaran', $data);
	}
	public function deletePengeluaran($id)
	{
		return $this->db->delete('pengeluaran', ['id' => $id]);
	}
	public function getRekapSummary($bulan, $tahun)
	{
		// Modal awal: semua barang dibeli ke supplier (tidak peduli laku/belum)
		$this->db->select_sum('hp.harga_beli');
		$this->db->from('hp_transactions hp');
		$this->db->join('transaksi t', 'hp.transaksi_id = t.id');
		$this->db->where('t.bulan', $bulan);
		$this->db->where('t.tahun', $tahun);
		$modal = $this->db->get()->row();
	
		// Total penjualan (yang status laku saja)
		$this->db->select_sum('hp.harga_jual');
		$this->db->from('hp_transactions hp');
		$this->db->join('transaksi t', 'hp.transaksi_id = t.id');
		$this->db->where('t.bulan', $bulan);
		$this->db->where('t.tahun', $tahun);
		$this->db->where('hp.status', 'laku');
		$jual = $this->db->get()->row();
	
		// Total biaya admin + ongkir dari semua transaksi
		$this->db->select('
			SUM(biaya_admin) AS total_admin,
			SUM(ongkir) AS total_ongkir
		');
		$this->db->where('bulan', $bulan);
		$this->db->where('tahun', $tahun);
		$trx = $this->db->get('transaksi')->row();
	
		// Total pengeluaran tambahan (di luar admin/ongkir)
		$this->db->select_sum('nominal');
		$this->db->where('bulan', $bulan);
		$this->db->where('tahun', $tahun);
		$pengeluaran = $this->db->get('pengeluaran')->row();
	
		return [
			'modal_awal' => (int)($modal->harga_beli ?? 0),
			'total_penjualan' => (int)($jual->harga_jual ?? 0),
			'total_admin' => (int)($trx->total_admin ?? 0),
			'total_ongkir' => (int)($trx->total_ongkir ?? 0),
			'pengeluaran_tambahan' => (int)($pengeluaran->nominal ?? 0)
		];
	}
	
	
	public function countStatusHp($bulan, $tahun)
	{
		$this->db->select('status, COUNT(*) as jumlah');
		$this->db->from('hp_transactions');
		$this->db->join('transaksi', 'transaksi.id = hp_transactions.transaksi_id');
		$this->db->where('transaksi.bulan', $bulan);
		$this->db->where('transaksi.tahun', $tahun);
		$this->db->group_by('status');
	
		$result = $this->db->get()->result();
	
		$data = [
			'jumlah_laku' => 0,
			'jumlah_belum_laku' => 0
		];
	
		foreach ($result as $row) {
			if ($row->status === 'laku') {
				$data['jumlah_laku'] = (int)$row->jumlah;
			} elseif ($row->status === 'belum_laku') {
				$data['jumlah_belum_laku'] = (int)$row->jumlah;
			}
		}
	
		return $data;
	}
	
	public function getHpBelumLaku($bulan, $tahun)
	{
		$this->db->select('hp_transactions.*');
		$this->db->from('hp_transactions');
		$this->db->join('transaksi', 'transaksi.id = hp_transactions.transaksi_id');
		$this->db->where('hp_transactions.status', 'belum_laku');
		$this->db->where('transaksi.bulan', $bulan);
		$this->db->where('transaksi.tahun', $tahun);
		return $this->db->get()->result();
	}
	public function getRekapTahunan($tahun)
	{
		$result = [];

		for ($bulan = 1; $bulan <= 12; $bulan++) {
			$summary = $this->getRekapSummary($bulan, $tahun);

			$total_beban = $summary['modal_awal'] + $summary['total_admin'] + $summary['total_ongkir'] + $summary['pengeluaran_tambahan'];
			$laba_bersih = $summary['total_penjualan'] - $total_beban;

			$result[] = [
				'bulan' => $bulan,
				'laba_bersih' => $laba_bersih,
				'laba_bersih_rupiah' => $this->formatRupiah($laba_bersih)
			];
		}

		return $result;
	}

	private function formatRupiah($angka)
	{
		$rupiah = number_format(abs($angka), 0, ',', '.');
		return ($angka < 0 ? '-Rp' : 'Rp') . $rupiah;
	}

}
