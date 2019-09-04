<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	var $response;
	var $front_uid;
	var $front_uem;

	public function __construct(){
		parent::__construct();
		$this->response = new stdClass();
		$this->front_uid = $this->session->userdata('front_uid');
		$this->front_uem = $this->session->userdata('front_uem');
	}

	public function index(){
		//obtener las categorias principales
		$this->load->model('category_model');
		$data['categories'] = $this->category_model->get_main_categories();
		//obtener los datos de la portada principal
		$this->load->model('cover_model');
		$data['cover'] = $this->cover_model->get_main_cover();
		//obtener datos de los articulos más populares
		$this->load->model('article_model');
		$data['popular'] = $this->article_model->get_popular_content(6);
		//obtener los primeros 5 articulos en la lista
		$data['articles'] = $this->article_model->get_front_content(5,0,FALSE);
		$data['title'] = 'Revista Electrónica';
		$data['active'] = 'home';
		$data['front_uid'] = $this->front_uid;
		$data['front_uem'] = $this->front_uem;
		//establecer etiquetas meta
		$data['meta_tags'] = Array(
			'<link rel="canonical" href="'.base_url().'">',
			'<meta name="description" content="La Revista de Difusión Científica del Instituto Tecnológico de Morelia se encarga de publicar las investigaciones generadas dentro del instituto para darlas a conocer a toda la comunidad." />',
			'<meta name="keywords" content="Instituto Tecnológico de Morelia,ITM,Revista Electrónica,Difusión Científica,México,Michoacán,Morelia,Ciencia,Tecnología,Investigación"/>',
			'<meta name="copyright" content="© '.get_year(mysql_date()).' Instituto Tecnológico de Morelia"/>',
			//facebook opengraph tags
			'<meta property="fb:app_id" content="660426037349263"/>',
			'<meta property="og:title" content="Revista del Instituto Tecnológico de Morelia"/>',
			'<meta property="og:site_name" content="Revista del Instituto Tecnológico de Morelia"/>',
			'<meta property="og:url" content="'.base_url().'"/>',
			'<meta property="og:description" content="La Revista de Difusión Científica del Instituto Tecnológico de Morelia se encarga de publicar las investigaciones generadas dentro del instituto para darlas a conocer a toda la comunidad."/>',
			'<meta property="og:image" content="'.base_url('images').'/logo_original.png"/>',
			'<meta property="og:type" content="website"/>',
			'<meta property="og:locale" content="es_LA"/>',
		);
		//cargar scripts
		$data['scripts'] = Array(
			base_url('scripts').'/front/scroll-articles.js',
		);
		$this->load->view('front/common/header',$data);
		$this->load->view('front/home');
		$this->load->view('front/common/navbar');
		$this->load->view('front/common/footer');
	}

	public function search(){
		//obtener las categorias principales
		$this->load->model('category_model');
		$data['categories'] = $this->category_model->get_main_categories();
		//datos que se envian a la vista
		$data['title'] = 'Revista Electrónica';
		$data['active'] = 'home';
		$data['front_uid'] = $this->front_uid;
		$data['front_uem'] = $this->front_uem;

		$this->load->model('article_model');
		$data['articles'] = $this->article_model->search($this->input->get('s'));
		$data['search'] = $this->input->get('s');

		$data['meta_tags'] = Array(
			'<meta name="robots" content="noindex,nofollow"/>'
		);

		//cargar vistas
		$this->load->view('front/common/header',$data);
		$this->load->view('front/search');
		$this->load->view('front/common/navbar');
		$this->load->view('front/common/footer');
	}

	public function about(){
		//obtener las categorias principales
		$this->load->model('category_model');
		$data['categories'] = $this->category_model->get_main_categories();
		//datos que se envian a la vista
		$data['title'] = 'Revista Electrónica';
		$data['active'] = 'home';
		$data['front_uid'] = $this->front_uid;
		$data['front_uem'] = $this->front_uem;
		//cargar vistas
		$this->load->view('front/common/header',$data);
		$this->load->view('front/about');
		$this->load->view('front/common/navbar');
		$this->load->view('front/common/footer');
	}

	public function privacy(){
		//obtener las categorias principales
		$this->load->model('category_model');
		$data['categories'] = $this->category_model->get_main_categories();
		//datos que se envian a la vista
		$data['title'] = 'Revista Electrónica';
		$data['active'] = 'home';
		$data['front_uid'] = $this->front_uid;
		$data['front_uem'] = $this->front_uem;
		//cargar vistas
		$this->load->view('front/common/header',$data);
		$this->load->view('front/privacy');
		$this->load->view('front/common/navbar');
		$this->load->view('front/common/footer');
	}
}

/* End of file home.php */
/* Location: ./application/controllers/front/home.php */