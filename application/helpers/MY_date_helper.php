<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('get_year')){
	function get_year($mysql_date){
		//2014-02-07
		return substr($mysql_date,0,4);
	}
}

if(!function_exists('get_simplified_date')){
	function get_simplified_date($mysql_date){
		//2014-02-07
		$data = explode('-', $mysql_date);
		$year = $data[0];
		$month = $data[1];
		$day = $data[2];

		switch ($month) {
			case '01':
				$month = 'Enero';
				break;
			case '02':
				$month = 'Febrero';
				break;
			case '03':
				$month = 'Marzo';
				break;
			case '04':
				$month = 'Abril';
				break;
			case '05':
				$month = 'Mayo';
				break;
			case '06':
				$month = 'Junio';
				break;
			case '07':
				$month = 'Julio';
				break;
			case '08':
				$month = 'Agosto';
				break;
			case '09':
				$month = 'Septiembre';
				break;
			case '10':
				$month = 'Octubre';
				break;
			case '11':
				$month = 'Noviembre';
				break;
			case '12':
				$month = 'Diciembre';
				break;
		}

		return $day.' de '.$month.', '.$year;
	}
}

// ------------------------------------------------------------------------
/* End of file MY_date_helper.php */
/* Location: ./helpers/MY_date_helper.php */