<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Categories extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function index($cat_url){
		if(!$cat_url){
			show_404();
			return;
		}
		//obtener los datos de la categoria
		$this->load->model('category_model');
		$this->category_model->cat_url = $cat_url;
		$category = $this->category_model->get_category_url();
		if($category == FALSE){
			show_404();
			return;
		} else {
			$data['category'] = $category;
		}
		//obten las categorias para la barra de navegacion
		if(isset($category->cat_hijas)){
			$child = $category->cat_hijas[sizeof($category->cat_hijas)-1];
			$this->category_model->cat_id = $child->cat_id;
			$this->category_model->cat_super_id = $child->cat_super_id;
			$data['categories'] = $this->category_model->get_categories_from($child);
		} else {
			$this->category_model->cat_id = $category->cat_id;
			$this->category_model->cat_super_id = $category->cat_super_id;
			$data['categories'] = $this->category_model->get_categories_from($category);	
		}
		//obtener los datos de la portada de la categoria actual
		$this->load->model('cover_model');
		$this->cover_model->cat_id = $category->cat_id;
		$data['cover'] = $this->cover_model->get_category_cover();
		//datos de la vista
		$data['title'] = $category->cat_nombre.' | Instituto Tecnológico de Morelia';
		$data['active'] = $category->cat_id;
		//obtener los primeros 10 articulos en la lista
		$this->load->model('article_model');
		$data['articles'] = $this->article_model->get_front_content(10,0,$category->cat_id);
		//cargar scripts
		$data['scripts'] = Array(
			base_url('scripts').'/front/scroll-articles.js',
		);
		//cargar las vistas
		$this->load->view('front/common/header',$data);
		$this->load->view('front/category');
		$this->load->view('front/common/navbar');
		$this->load->view('front/common/footer');
	}

}

/* End of file categories.php */
/* Location: ./application/controllers/front/categories.php */