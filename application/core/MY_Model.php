<?php
class MY_Model extends CI_Model
{

	public function getAll($limit = null, $offset = null)
	{
		if ($limit) $this->db->limit($limit, $offset);
		return $this->db->get($this->table)->result();
	}

	public function findById($tabel,$id,$row=true)
	{
		$this->db->where('id', $id);
		$query = $this->db->get($tabel);
		if ($row) {
			return $query->row();
		} else {
			return $query->result();
		}
	}

	public function create($data)
	{
		return $this->db->insert($this->table, $data);
	}

	public function update($id, $data,$tabel,$id_tabel)
	{
		return $this->db->where($id_tabel, $id)->update($tabel, $data);
	}

	public function delete($id)
	{
		return $this->db->where($this->primaryKey, $id)->delete($this->table);
	}

	public function countAll()
	{
		return $this->db->count_all($this->table);
	}

	public function paginate($limit, $offset, $conditions = [], $orderBy = null)
	{
		if (!empty($conditions)) {
			$this->db->where($conditions);
		}
		if ($orderBy) {
			$this->db->order_by($orderBy);
		}
		$this->db->limit($limit, $offset);
		return $this->db->get($this->table)->result();
	}

	public function findWhere($conditions)
	{
		return $this->db->get_where($this->table, $conditions)->result();
	}

	public function softDelete($id,$id_tabel,$tabel)
	{
		$data = [
			'deleted_at' => date('Y-m-d H:i:s')
		];
		return $this->db->where($id_tabel, $id)->update($tabel, $data);
	}
	public function logAction($idKategoriLog, $jenisUser, $idUser, $message)
	{
		$logData = [
			'id_kategori_log' => $idKategoriLog,
			'jenis_user' => $jenisUser,
			'id_user' => $idUser,
			'log_message' => $message,
			'created_at' => date('Y-m-d H:i:s')
		];
		$this->db->insert('logs', $logData);
	}
}
