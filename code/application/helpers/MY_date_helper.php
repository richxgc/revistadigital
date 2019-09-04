<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('get_month_dates')){
	function get_month_dates($mt=0){
		$date =  gmt_to_local(now(),'UM6',TRUE);
		$date = date('Y-m-d',$date);
		$month = intval(substr($date,5,2)) + ($mt);
		$year = intval(substr($date,0,4));
		$output = array();
		switch ($month) {
			case 0:
				$month = 12;
				break;
			case -1:
				$month = 11;
				break;
			default:
				$month = $month;
				break;
		}
		if($month > intval(substr($date,5,2))){ $year--; }
		if(in_array($month, array(1,2,3,4,5,6,7,8,9))){
			$output[] = $year.'-0'.$month.'-01';
			$output[] = $year.'-0'.$month.'-31';
		} else {
			$output[] = $year.'-'.$month.'-01';
			$output[] = $year.'-'.$month.'-31';
		}
		return $output;
	}
}

if(!function_exists('get_year')){
	function get_year($mysql_date){
		//2014-02-07
		return substr($mysql_date,0,4);
	}
}

if(!function_exists('mysql_date')){
	function mysql_date(){
		$date = gmt_to_local(now(),'UM6',TRUE);
		return date('Y-m-d',$date);
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