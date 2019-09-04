<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Articles extends CI_Controller {

	var $response;
	var $front_uid;
	var $front_uem;

	public function __construct(){
		parent::__construct();
		$this->response = new stdClass();
		$this->front_uid = $this->session->userdata('front_uid');
		$this->front_uem = $this->session->userdata('front_uem');
	}

	public function index($art_url){
		if(!$art_url){
			show_404();
			return;
		}
		//obtener los datos del articulos
		$this->load->model('article_model');
		$this->article_model->art_url = $art_url;
		$article = $this->article_model->get_article_url();
		if($article == FALSE){
			show_404();
			return;
		} else {
			$data['article'] = $article;
			//incrementa en 1 las veces que se ha leido el articulo
			$this->article_model->art_id = $article->art_id;
			$this->article_model->art_leido = $article->art_leido;
			$this->article_model->increment_visited_counter();
		}
		//obtener la ultima categoria en la lista del articulo
		$category = $article->art_categorias[sizeof($article->art_categorias)-1];
		//obtener las categorias principales
		$this->load->model('category_model');
		$this->category_model->cat_id = $category->cat_id;
		$this->category_model->cat_super_id = $category->cat_super_id;
		$data['categories'] = $this->category_model->get_categories_from($category);
		//obtener articulos relacionados
		$data['related'] = $this->article_model->get_related_content($category->cat_id,$article->art_id,3);
		//obtener datos de los articulos más populares
		$data['popular'] = $this->article_model->get_popular_content(6);		
		//datos de la vista
		$data['title'] = $article->art_titulo.' | Instituto Tecnológico de Morelia';
		$data['active'] = $category->cat_id;
		$data['front_uid'] = $this->front_uid;
		$data['front_uem'] = $this->front_uem;
		//si existe sesion de usuario carga algunos scripts
		if($this->front_uid != FALSE){
			$data['scripts'] = Array(
				base_url('scripts').'/front/users/save_to_lib.js'
			);
		}
		//establecer etiquetas meta
		$authors = NULL;
		foreach($article->art_autores as $author){
			$authors .= $author->usr_nombre.', ';
		}
		$authors = substr($authors,0,-2);
		$data['meta_tags'] = Array(
			//direccion canonica del articulo
			'<link rel="canonical" href="'.base_url().index_page().'/articulo/'.$article->art_url.'">',
			'<meta name="description" content="'.strip_text($article->art_abstracto,150).'" />',
			'<meta name="keywords" content="'.$article->art_etiquetas.'"/>',
			'<meta name="author" content="'.$authors.'"/>',
			'<meta name="copyright" content="© '.get_year($article->art_fecha).' Instituto Tecnológico de Morelia, '.$authors.'"/>',
			//facebook opengraph tags
			'<meta property="fb:app_id" content="660426037349263"/>',
			'<meta property="og:title" content="'.$article->art_titulo.'"/>',
			'<meta property="og:site_name" content="Revista del Instituto Tecnológico de Morelia"/>',
			'<meta property="og:url" content="'.current_url().'"/>',
			'<meta property="og:description" content="'.strip_text($article->art_abstracto,150).'"/>',
			'<meta property="og:image" content="'.$article->art_portada.'"/>',
			'<meta property="og:type" content="article"/>',
			'<meta property="og:locale" content="es_LA"/>',
			'<meta property="article:author" content="'.$authors.'"/>',
			'<meta property="article:publisher" content="https://www.facebook.com/ITMoreliaOficial"/>',
		);
		//cargar las vistas
		$this->load->view('front/common/header',$data);
		$this->load->view('front/article');
		$this->load->view('front/common/navbar');
		$this->load->view('front/common/footer');

	}

	public function get_front_content($offset,$category=FALSE){
		$this->load->model('article_model');
		$data['articles'] = $this->article_model->get_front_content(5,$offset,$category);
		$this->load->view('front/components/articles',$data);
	}

}

/* End of file articles.php */
/* Location: ./application/controllers/front/articles.php */