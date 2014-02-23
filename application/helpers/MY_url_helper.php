<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!function_exists('get_user_url')){
	function get_user_url($user_id){
		return base_url().index_page().'/u/'.$user_id;
	}
}

if(!function_exists('get_category_url')){
	function get_category_url($cat_url){
		return base_url().index_page().'/categoria/'.$cat_url;
	}
}

if(!function_exists('get_article_url')){
	function get_article_url($art_url){
		return base_url().index_page().'/articulo/'.$art_url;
	}
}

// ------------------------------------------------------------------------
/* End of file MY_url_helper.php */
/* Location: ./helpers/MY_url_helper.php */