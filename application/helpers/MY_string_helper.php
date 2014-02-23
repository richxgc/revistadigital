<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('perms_to_array')){
	function perms_to_array($perm_string){
		return explode(',', $perm_string);
	}
}

if(!function_exists('strip_text')){
	function strip_text($text,$chars){
		return substr($text,0,($chars-3)).'...';
	}
}

//funcion recursiva para imprimir los submenus
if(!function_exists('list_children_categories')){
	function list_children_categories($childrens,$active,$padding=0){
		$padding += 15;
		$html = '<ul class="submenu">';
		foreach ($childrens as $children) {
			if($active == $children->cat_id){
				$html .= '<li class="active" style="padding-left:'.$padding.'px;"><a href="'.get_category_url($children->cat_url).'">'.$children->cat_nombre.'</a></li>';
			} else {
				$html .= '<li style="padding-left:'.$padding.'px;"><a href="'.get_category_url($children->cat_url).'">'.$children->cat_nombre.'</a></li>';	
			}
			if(isset($children->cat_hijas)){
				$html .= list_children_categories($children->cat_hijas,$active,$padding);
			}
		}
		$html .= '</ul>';
		return $html;
	}
}

// ------------------------------------------------------------------------
/* End of file MY_date_helper.php */
/* Location: ./helpers/MY_date_helper.php */