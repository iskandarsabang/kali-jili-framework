<!doctype html>
<html class="fixed">
<head>

	<title>HALAMAN ERROR</title>

</head>
<body>


	<?php
	echo '<pre>';
	echo 'Pesan error: <br>';
	print_r($data->getMessage());

	echo '<br><br>========= Penjelasan ========= <br><br>';
	$no = 0;
	foreach ($data->getTrace() as $penjelasan) {
		$no++;
		echo $no. ': '.$penjelasan['file'] . ' di line: ' . $penjelasan['line'] . '<br>';
	}
	echo '</pre>';
	?>


</body>
</html>
