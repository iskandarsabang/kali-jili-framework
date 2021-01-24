<?php

	function tanggal_dmy($tanggal='')
	{
		$tanggal_hasil='';
		if($tanggal != '')
		{
			$tanggal_hasil = substr($tanggal,-2).'-'.substr($tanggal,5,2).'-'.substr($tanggal,0,4);
		}

		return $tanggal_hasil;
	}

	function tanggal_ymd($tanggal='')
	{
		$tanggal_hasil='';
		if($tanggal != '')
		{
			$tanggal_hasil = substr($tanggal,-4).'-'.substr($tanggal,3,2).'-'.substr($tanggal,0,2);
		}

		return $tanggal_hasil;
	}


	function cetak_var($var=[])
	{
		echo "<pre>";
		print_r($var);
		echo "</pre>";

	}


?>