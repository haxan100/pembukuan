<?php
class HargaModel extends MY_Model
{
	private $table = 'master_harga';

	public function get_datatables($postData)
    {
		$this->_get_datatables_query($postData);

		if ($_POST['length'] != -1) {
			$this->db->limit($_POST['length'], $_POST['start']);
		}

		$query = $this->db->get();
		return $query->result();
	}

	public function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}
	public function count_filtered_detail($id)
	{
		$this->_get_datatables_query_detail($id);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		return $this->db->count_all($this->table);
	}
	public function count_all_detail($id = null)
	{
		if ($id !== null) {
			$this->db->where('master_harga_id', $id);
			$this->db->where('deleted_at', null);
			return $this->db->count_all_results('master_harga_details');
		}
		return $this->db->count_all('master_harga_details');
	}
	private function _get_datatables_query()
	{
		$columns = ['judul_harga', 'periode_awal', 'periode_akhir', 'created_at'];
		$this->db->from($this->table);

		$i = 0;
		foreach ($columns as $item) {
			if ($_POST['search']['value']) {
				if ($i === 0) {
					$this->db->group_start();
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if (count($columns) - 1 == $i) {
					$this->db->group_end();
				}
			}
			$i++;
		}
		$this->db->where('deleted_at', null);

		if (isset($_POST['order'])) {
			$this->db->order_by($columns[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
		} else {
			$this->db->order_by('created_at', 'DESC');
		}
	}

	public function get_by_id($id)
	{
		$this->db->from($this->table);
		$this->db->where('id', $id);
		$query = $this->db->get();

		return $query->row();
	}

	public function save($data)
	{
		if (isset($data['id']) && !empty($data['id'])) {
			$this->db->where('id', $data['id']);
			return $this->db->update($this->table, $data);
		} else {
			return $this->db->insert($this->table, $data);
		}
	}

	public function softDelete($id, $id_tabel, $tabel)
	{
		$data = [
			'deleted_at' => date('Y-m-d H:i:s')
		];
		return $this->db->where($id_tabel, $id)->update($tabel, $data);
	}
	public function get_datatables_detail($id)
	{
		$this->_get_datatables_query_detail($id);

		if ($_POST['length'] != -1) {
			$this->db->limit($_POST['length'], $_POST['start']);
		}

		$query = $this->db->get();
		return $query->result();
	}
	private function _get_datatables_query_detail($id)
	{
		$columns = ['merk', 'model', 'type', 'storage', 'ram', 'harga_a'];
		$this->db->from('master_harga_details'); // Nama tabel
		$this->db->where('master_harga_id', $id); // Filter berdasarkan ID master_harga

		$i = 0;
		foreach ($columns as $item) {
			if (!empty($_POST['search']['value'])) {
				if ($i === 0) {
					$this->db->group_start();
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if (count($columns) - 1 == $i) {
					$this->db->group_end();
				}
			}
			$i++;
		}
		$this->db->where('deleted_at', null); // Filter data yang belum dihapus secara soft delete

		// Order berdasarkan kolom yang dipilih di DataTables
		if (isset($_POST['order'])) {
			$this->db->order_by($columns[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
		} else {
			$this->db->order_by('merk', 'ASC'); // Order default
		}
	}
	public function save_detail($data, $table)
	{
		$this->db->insert('master_harga_details', $data);
	}

	public function update_detail($id, $data, $table, $primaryKey)
	{
		$this->db->where($primaryKey, $id);
		$this->db->update($table, $data);
	}
	public function get_detail_by_id($id, $table)
	{
		return $this->db->get_where("master_harga_details", ['id' => $id])->row_array();
	}
	public function getAllDetails()
	{
		$this->db->select('*');
		$this->db->from('master_harga_details');
		return $this->db->get()->result();
	}
	public function getAllDetailsWithMasterHarga($id)
	{
		$this->db->select('
			master_harga_details.*,
			master_harga.judul_harga,
			master_harga.periode_awal,
			master_harga.periode_akhir
		');
		$this->db->from('master_harga_details');
		$this->db->join('master_harga', 'master_harga.id = master_harga_details.master_harga_id', 'left');
		$this->db->where('master_harga_id', $id);
		
		return $this->db->get()->result();
	}

	public function get_datatables_mitra($postData)
    {
		$this->_get_datatables_query_mitra($postData);
		if ($_POST['length'] != -1) {
			$this->db->limit($_POST['length'], $_POST['start']);
		}

		$query = $this->db->get();
		return $query->result();
	}
	private function _get_datatables_query_mitra($post)
	{

		$columns = ['judul_harga', 'periode_awal', 'periode_akhir', 'created_at'];
		$this->db->from($this->table);

		$i = 0;
		foreach ($columns as $item) {
			if ($_POST['search']['value']) {
				if ($i === 0) {
					$this->db->group_start();
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if (count($columns) - 1 == $i) {
					$this->db->group_end();
				}
			}
			$i++;
		}
		$this->db->where('deleted_at', null);
		$this->db->where('id_mitra', $post['id_mitra']);

		if (isset($_POST['order'])) {
			$this->db->order_by($columns[$_POST['order'][0]['column']], $_POST['order'][0]['dir']);
		} else {
			$this->db->order_by('created_at', 'DESC');
		}
	}
	public function count_all_mitra($post)
	{
		$this->db->where('deleted_at', null);
		$this->db->where('id_mitra', $post['id_mitra']);
		return $this->db->count_all_results($this->table);
	}

	public function count_filtered_mitra($post)
	{
		$this->_get_datatables_query_mitra($post); // Panggil query filtering
		return $this->db->count_all_results();
	}
	public function get_by_id_mitra($id,$id_mitra)
	{
		$this->db->from($this->table);
		$this->db->where('id', $id);
		$this->db->where('id_mitra', $id_mitra);
		$query = $this->db->get();

		return $query->row();
	}
	public function update_detail_double_where($conditions, $data, $table)
	{
		$this->db->where($conditions);
		$this->db->update($table, $data);

		return $this->db->affected_rows(); // Mengembalikan jumlah baris yang diupdate
	}
	public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }
	public function get_price($id_mitra, $brand, $model, $ram, $storage, $tanggal_sekarang)
    {
        $this->db->select('
            md.merk as brand, 
            md.model, 
            md.ram, 
            md.storage,
            md.harga_a, 
            md.harga_b, 
            md.harga_c, 
            md.harga_d, 
            md.harga_e, 
            md.harga_fullset, 
            md.harga_promotion
        ');
        $this->db->from('master_harga_details md');
        $this->db->join('master_harga mh', 'md.master_harga_id = mh.id');
        $this->db->where('mh.id_mitra', $id_mitra);
        $this->db->where('md.deleted_at IS NULL');
        $this->db->where('mh.periode_awal <=', $tanggal_sekarang);
        $this->db->where('mh.periode_akhir >=', $tanggal_sekarang);
        $this->db->where('LOWER(md.merk)', strtolower($brand));
        $this->db->where('LOWER(md.model)', strtolower($model));
        $this->db->where('LOWER(md.ram)', strtolower($ram));
        $this->db->where('LOWER(md.storage)', strtolower($storage));
        $query = $this->db->get();
		// var_dump($this->db->last_query());die;
        return $query->row_array();
    }
	// function getPrice() : Returntype {
		
	// }

  


}
