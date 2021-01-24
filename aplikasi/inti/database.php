<?php

class database
{
	private $nama_server = SERVER_DATABASE;
	private $nama_user =  USER_DATABASE;
	private $password_user = PASSWORD_USER;
	private $nama_database = NAMA_DATABASE;


	private $jembatan;
	private $kendaraan;

	private $tabel = '';


	public function __construct($tabel_masukan = "")
	{

		$this->tabel = $tabel_masukan;
		$identitas_koneksi = 'mysql:host=' . $this->nama_server . ';dbname=' . $this->nama_database;

		$option = [
			PDO::ATTR_PERSISTENT => true,
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		];

		try {
			$this->jembatan = new PDO($identitas_koneksi, $this->nama_user, $this->password_user, $option);
		} catch (PDOException $salahnya) {
			die($this->tampilkan_error($salahnya));
		}
	}

	public function isi_sql($perintah_sql)
	{
		$this->kendaraan = $this->jembatan->prepare($perintah_sql);
	}

	public function isi_parameter($param, $value, $type = null)
	{
		if (is_null($type)) {
			switch (true) {
				case is_int($value):
					$type = PDO::PARAM_INT;
					break;
				case is_bool($value):
					$type = PDO::PARAM_BOOL;
					break;
				case is_null($value):
					$type = PDO::PARAM_NULL;
					break;
				default:
					$type = PDO::PARAM_STR;
			}
		}

		$this->kendaraan->bindValue($param, $value, $type);
	}


	public function jalankan_sql()
	{
		try {
			$this->kendaraan->execute();
		} catch (PDOException $kesalahan) {
			//die('Ini kesalahannya: '.$kesalahan->getMessage());
			die($this->tampilkan_error($kesalahan));
		}
	}


	public function tampilkan_error($data = [])
	{
		require_once 'aplikasi/inti/page_error.php';
	}


	public function jumlah_baris_efek()
	{
		return $this->kendaraan->rowCount();
	}

	public function ambil_banyak_baris()
	{
		$this->jalankan_sql();
		return $this->kendaraan->fetchAll(PDO::FETCH_ASSOC);
	}

	public function ambil_satu_baris()
	{
		// proses ini adalah proses kendaraan melaksanakan tugasnya sesuai dengan apa yang diperintahkan
		$this->jalankan_sql();
		return $this->kendaraan->fetch(PDO::FETCH_ASSOC);
	}


	//update per tanggal 23 Januari 2021
	public function ambil_semua($order = [])
	{

		if ($this->tabel == '') {
			return [[]];
		} else {
			$sql = "SELECT * FROM " . $this->tabel . ' ';

			$count_order = count($order);

			if ($count_order > 0) {
				$sql .= "ORDER BY ";
				for ($i = 0; $i < $count_order; $i++) {
					$sql .= $order[$i];
					if ($i < ($count_order - 1)) $sql .= ', ';
				}
			}
			$this->isi_sql($sql);
			return $this->ambil_banyak_baris();
		}
	}

	public function ambil_id($id = "")
	{

		if ($this->tabel == '') {
			return [];
		} else {

			$this->isi_sql("SELECT * FROM " . $this->tabel . " WHERE id= :id");
			$this->isi_parameter("id", $id);
			return $this->ambil_satu_baris();
		}
	}

	public function ambil_satu_baris_tertentu($field, $value)
	{

		if ($this->tabel == '') {
			return [];
		} else {
			$this->isi_sql("SELECT * FROM " . $this->tabel . ' WHERE ' . $field . "= :" . $field);
			$this->isi_parameter($field, $value);
			return $this->ambil_satu_baris();
		}
	}

	public function ambil_banyak_baris_tertentu($field, $value, $order = [])
	{

		if ($this->tabel == '') {
			return [[]];
		} else {

			$sql = "SELECT * FROM " . $this->tabel . " WHERE " . $field . "= :" . $field;

			$count_order = count($order);

			if ($count_order > 0) {
				$sql .= " ORDER BY ";
				for ($i = 0; $i < $count_order; $i++) {
					$sql .= $order[$i];
					if ($i < ($count_order - 1)) $sql .= ', ';
				}
			}

			$this->isi_sql($sql);
			$this->isi_parameter($field, $value);
			return $this->ambil_banyak_baris();
		}
	}

	public function jumlah_baris()
	{

		if ($this->tabel == '') {
			return -1;
		} else {
			$this->isi_sql("SELECT COUNT(*) AS jumlah FROM " . $this->tabel);
			return $this->ambil_satu_baris()['jumlah'];
		}
	}

	public function jumlah_baris_tertentu($field = '', $value = '')
	{
		if ($this->tabel == '' or $field == '' or $value == '') {
			return -1;
		} else {

			$this->isi_sql("SELECT COUNT(*) AS jumlah FROM " . $this->tabel . " WHERE $field = :value");
			$this->isi_parameter("value", $value);
			return $this->ambil_satu_baris()['jumlah'];
		}
	}

	public function insert($fields_values = [[]])
	{
		$field_count = count($fields_values);
		$sql = "INSERT INTO " . $this->tabel . '( ';

		for ($i = 0; $i < $field_count; $i++) {
			$sql .= $fields_values[$i][0];
			if ($i < ($field_count - 1)) $sql .= ', ';
		}

		$sql .= ') VALUES (';

		for ($i = 0; $i < $field_count; $i++) {
			$sql .= ':' . $fields_values[$i][0];
			if ($i < ($field_count - 1)) $sql .= ', ';
		}

		$sql .= ')';

		$this->isi_sql($sql);

		for ($i = 0; $i < $field_count; $i++) {
			$this->isi_parameter($fields_values[$i][0], $fields_values[$i][1]);
		}
		$this->jalankan_sql();
	}

	public function update($fields_values = [[]])
	{
		$field_count = count($fields_values);
		$sql = "UPDATE " . $this->tabel . ' SET ';

		for ($i = 0; $i < ($field_count - 1); $i++) {
			$sql .= $fields_values[$i][0] . ' = :' . $fields_values[$i][0];
			if ($i < ($field_count - 2)) $sql .= ', ';
		}

		$sql .= ' WHERE ' . $fields_values[$field_count - 1][0] . ' = :' . $fields_values[$field_count - 1][0];

		$this->isi_sql($sql);

		for ($i = 0; $i < $field_count; $i++) {
			$this->isi_parameter($fields_values[$i][0], $fields_values[$i][1]);
		}
		$this->jalankan_sql();
	}

	public function delete_id($id = "")
	{
		$sql = "DELETE FROM " . $this->tabel . " WHERE id= :id";
		$this->isi_sql($sql);
		$this->isi_parameter("id", $id);
		$this->jalankan_sql();
	}

	public function delete($field = "", $value = "")
	{
		$sql = "DELETE FROM " . $this->tabel . " WHERE " . $field . "= : " . $field;
		$this->isi_sql($sql);
		$this->isi_parameter($field, $value);
		$this->jalankan_sql();
	}
}
