<?php

Class aplikasiku {

	protected $controller = 'home';
	protected $metode = 'index';
	protected $paramater_data = [];


	public function __construct()
	{

		
		$nilai_control_methode_data = $this->split_control_metode_data();
		

		if(isset($nilai_control_methode_data)) {
			if(file_exists('aplikasi/control/'.$nilai_control_methode_data[0].'.php'))
			{

				$this->controller = $nilai_control_methode_data[0];
				unset($nilai_control_methode_data[0]);
			}
		}



		require_once 'aplikasi/control/'.$this->controller.'.php';
		$this->controller = new $this->controller;



		if(isset($nilai_control_methode_data[1]))
		{
			if(method_exists($this->controller, $nilai_control_methode_data[1]))
			{
				$this->metode = $nilai_control_methode_data[1];
				unset($nilai_control_methode_data[1]);
			}
		}



		if(!empty($nilai_control_methode_data))
		{
			$this->paramater_data = array_values($nilai_control_methode_data);

		}

		call_user_func_array([$this->controller,$this->metode], $this->paramater_data);

	}

	public function split_control_metode_data()
	{

		if(isset($_GET['control_metode_data']))
		{
			$nilai_control_methode_data = rtrim($_GET['control_metode_data'],'/');

			$nilai_control_methode_data = filter_var($nilai_control_methode_data, FILTER_SANITIZE_URL);
			$nilai_control_methode_data = explode('/', $nilai_control_methode_data);
			return $nilai_control_methode_data;
		}
	}

}

 ?>
