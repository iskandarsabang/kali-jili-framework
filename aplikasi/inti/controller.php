<?php
class controller
{


	// Function "tampilkan_view" merupakan function
	// yang digunakan untuk menampilkan file view 
	// yang tersimpan di folder view
	// Penamaan function tersebut (tampilkan_view) dapat diganti
	public function tampilkan_view($file_view, $data = [])
	{
		require_once 'aplikasi/views/' . $file_view . '.php';
	}

	// Function "gunakan_model" merupakan function
	// yang digunakan untuk menggunakan file model 
	// yang tersimpan di folder model
	// Penamaan function tersebut (gunakan_model) dapat diganti
	public function gunakan_model($nama_file_model)
	{
		require_once 'aplikasi/model/' . $nama_file_model . '.php';
		$split_file = explode('/', $nama_file_model);
		$jml_split_file = count($split_file);
		$class_model = $split_file[$jml_split_file - 1];

		return new $class_model;
	}

	// Function cetak_var merupakan function yang digunakan 
	// untuk menampilkan nilai sebuah varibel
	public function cetak_var($var = [])
	{
		echo "<pre>";
		print_r($var);
		echo "</pre>";
	}

	// Function-function berikut merupakan function yang dapat dikreasikan
	// bahkan dihapus jika tidak diperlukan pada aplikasi
	public function tanggal_dmy($tanggal = '')
	{
		$tanggal_hasil = '';
		if ($tanggal != '') {
			$tanggal_hasil = substr($tanggal, -2) . '-' . substr($tanggal, 5, 2) . '-' . substr($tanggal, 0, 4);
		}

		return $tanggal_hasil;
	}

	public function tanggal_ymd($tanggal = '')
	{
		$tanggal_hasil = '';
		if ($tanggal != '') {
			$tanggal_hasil = substr($tanggal, -4) . '-' . substr($tanggal, 3, 2) . '-' . substr($tanggal, 0, 2);
		}

		return $tanggal_hasil;
	}

	// Nama-nama function yang di dalam file ini tidak dapat lagi digunakan
	// saat membuat function controller	

}
