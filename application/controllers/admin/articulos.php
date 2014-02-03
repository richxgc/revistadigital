<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Articulos extends CI_Controller {

	var $response;
	var $user_session;
	var $user_name;

	public function __construct(){
		parent::__construct();
		$this->response = new stdClass();
		$this->user_session = $this->session->userdata('admin_id');
		$this->user_name = $this->session->userdata('admin_name');
	}

	//funcion especial para poder enviar todos los parametros a la funcion index
	public function _remap($method,$params = array()){
		if(method_exists($this, $method)){
			return call_user_func_array(array($this, $method), $params);
		} else {
			$offset = 1; $order = 'fecha'; $search = NULL;
			if(isset($params[0])){$offset = $params[0];}
			if(isset($params[1])){$order = $params[1];}
			if(isset($params[2])){$search = $params[2];}
			$this->index($method,$offset,$order,$search);
		}
	}

	public function index($status="publicado",$offset=1,$order="fecha",$search=NULL){
		if($this->user_session == FALSE){
			redirect('/admin/login');
			return;
		}
		//si el usuario tiene acceso al modulo ser carga la vista, si no se redirige al inicio
		$this->load->model('admin_model');
		$this->admin_model->adm_id = $this->user_session;
		if($this->admin_model->get_user_access('articles') == FALSE){
			redirect('/admin');
			return;
		}
		//carga los datos que se enviaran a la vista
		$data['menu'] = $this->admin_model->get_menu_by_user();
		$data['active'] = 'articles';
		$data['title'] = 'Publicación de artículos';
		$data['user'] = $this->user_name;
		$data['status'] = $status;
		$data['order'] = $order;
		if($search != NULL){
			$search = urldecode($search);
			$data['search'] = $search;
		} else {
			$data['search'] = '';
		}
		$this->load->model('article_model');
		$data['articles'] = $this->article_model->get_articles_at(10,(($offset-1)*10),$status,$order,$search);
		$data['total_articles'] = $this->article_model->get_total_articles($status,$search);
		//scripts
		$data['scripts'] = array(
			base_url('scripts').'/system/articles/home_articles.js'
		);
		//cargar vistas de pagina principal de categorias
		$this->load->view('system/common/header',$data);
		$this->load->view('system/common/navbar');
		$this->load->view('system/articles/articles');
		$this->load->view('system/common/footer');
	}

	public function new_article(){
		if($this->user_session == FALSE){
			redirect('/admin/login');
			return;
		}
		//si el usuario tiene acceso al modulo ser carga la vista, si no se redirige al inicio
		$this->load->model('admin_model');
		$this->admin_model->adm_id = $this->user_session;
		if($this->admin_model->get_user_access('articles') == FALSE){
			redirect('/admin');
			return;
		}
		//carga los datos que se enviaran a la vista
		$data['menu'] = $this->admin_model->get_menu_by_user();
		$data['active'] = 'articles';
		$data['title'] = 'Publicar artículo';
		$data['user'] = $this->user_name;
		$this->load->model('category_model');
		$data['categories'] = $this->category_model->get_categories();
		//stilos que se cargaran en la vista
		$data['styles'] = array(
			base_url('stylesheets').'/system/wysihtml5.css',
			base_url('stylesheets').'/system/filesystem.css',
		);
		//scripts que se cargaran en la vista
		$data['scripts'] = array(
			base_url('libraries').'/wysihtml5-0.3.0.min.js',
			base_url('scripts').'/system/articles/bootstrap3-wysihtml5.js',
			base_url('scripts').'/system/articles/bootstrap-wysihtml5.es-ES.js',
			base_url('scripts').'/system/articles/create_article.js',
			base_url('scripts').'/system/articles/authors.js',
			base_url('scripts').'/system/articles/categories.js',
			base_url('scripts').'/system/articles/filesystem.js',
		);
		//cargar vistas de pagina principal de categorias
		$this->load->view('system/common/header',$data);
		$this->load->view('system/common/navbar');
		$this->load->view('system/articles/create');
		$this->load->view('system/articles/filesystem');
		$this->load->view('system/common/footer');
	}

	public function create_article(){
		//verifica si la solicitud se produce mediante xhr, de no ser asi redirige al usuario al inicio
		if($this->input->is_ajax_request() == FALSE){
			redirect('/admin');
			return;
		}
		//verifica si existe una sesion activa para guardar la informacion en la base de datos
		if($this->user_session == FALSE){
			$this->response->succeed = FALSE;
			$this->response->code = 401; //no autorizado
			$this->response->status = 'La sesión ha expirado';
			$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
			return;
		}
		//verifica si el usuario tiene los permisos suficientes para guardar datos de categorias
		$this->load->model('admin_model');
		$this->admin_model->adm_id = $this->user_session;
		if($this->admin_model->get_user_access('articles') == FALSE){
			$this->response->succeed = FALSE;
			$this->response->code = 550; //permiso denegado
			$this->response->status = 'No tienes privilegios suficientes para realizar esta acción';
			$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
			return;
		}
		//si todo es correcto continua con la funcion
		$this->load->model('article_model');
		$this->article_model->adm_id = $this->user_session;
		$this->article_model->art_titulo = $this->input->post('art_titulo');
		$this->article_model->art_url = $this->input->post('art_url');
		$this->article_model->art_portada = $this->input->post('art_portada');
		$this->article_model->art_abstracto = $this->input->post('art_abstracto');
		$this->article_model->art_contenido = $this->input->post('art_contenido');
		$this->article_model->art_etiquetas = $this->input->post('art_etiquetas');
		$this->article_model->art_estado = $this->input->post('art_estado');
		$this->article_model->art_fecha = $this->input->post('art_fecha');
		$this->article_model->art_pdf = $this->input->post('art_pdf');
		$this->article_model->art_autores = $this->input->post('art_autores');
		$this->article_model->art_categorias = $this->input->post('art_categorias');
		$this->response->succeed = $this->article_model->create_article();
		if($this->response->succeed == TRUE){
			$this->response->code = 200; //ok
			$this->response->status = 'El artículo se ha guardado correctamente';
		} else{
			$this->response->code = 500; //error interno del servidor
			$this->response->status = 'Ha ocurrido un error, intentelo de nuevo más tarde';
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
		return;
	}

	public function edit_article($article_id){
		if($this->user_session == FALSE){
			redirect('/admin/login');
			return;
		}
		//si el usuario tiene acceso al modulo ser carga la vista, si no se redirige al inicio
		$this->load->model('admin_model');
		$this->admin_model->adm_id = $this->user_session;
		if($this->admin_model->get_user_access('articles') == FALSE){
			redirect('/admin');
			return;
		}
		//obtener los datos del articulo
		$this->load->model('article_model');
		$this->article_model->art_id = $article_id;
		$data['article'] = $this->article_model->get_article();
		$data['menu'] = $this->admin_model->get_menu_by_user();
		$data['active'] = 'articles';
		$data['title'] = 'Editar - '.$data['article']->art_titulo;
		$data['user'] = $this->user_name;
		$this->load->model('category_model');
		$data['categories'] = $this->category_model->get_categories();
		//stilos que se cargaran en la vista
		$data['styles'] = array(
			base_url('stylesheets').'/system/wysihtml5.css',
			base_url('stylesheets').'/system/filesystem.css',
		);
		//scripts que se cargaran en la vista
		$data['scripts'] = array(
			base_url('libraries').'/wysihtml5-0.3.0.min.js',
			base_url('scripts').'/system/articles/bootstrap3-wysihtml5.js',
			base_url('scripts').'/system/articles/bootstrap-wysihtml5.es-ES.js',
			base_url('scripts').'/system/articles/edit_article.js',
			base_url('scripts').'/system/articles/authors.js',
			base_url('scripts').'/system/articles/categories.js',
			base_url('scripts').'/system/articles/filesystem.js',
		);
		//cargar vistas de pagina principal de categorias
		$this->load->view('system/common/header',$data);
		$this->load->view('system/common/navbar');
		$this->load->view('system/articles/edit');
		$this->load->view('system/articles/filesystem');
		$this->load->view('system/common/footer');
	}

	public function save_article(){
		//verifica si la solicitud se produce mediante xhr, de no ser asi redirige al usuario al inicio
		if($this->input->is_ajax_request() == FALSE){
			redirect('/admin');
			return;
		}
		//verifica si existe una sesion activa para guardar la informacion en la base de datos
		if($this->user_session == FALSE){
			$this->response->succeed = FALSE;
			$this->response->code = 401; //no autorizado
			$this->response->status = 'La sesión ha expirado.';
			$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
			return;
		}
		//verifica si el usuario tiene los permisos suficientes para guardar datos de categorias
		$this->load->model('admin_model');
		$this->admin_model->adm_id = $this->user_session;
		if($this->admin_model->get_user_access('articles') == FALSE){
			$this->response->succeed = FALSE;
			$this->response->code = 550; //permiso denegado
			$this->response->status = 'No tienes privilegios suficientes para realizar esta acción.';
			$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
			return;
		}
		//si se cumplen las condiciones anteriores continua con el proceso de guardado
		$this->load->model('article_model');
		$this->article_model->adm_id = $this->user_session;
		$this->article_model->art_id = $this->input->post('art_id');
		$this->article_model->art_titulo = $this->input->post('art_titulo');
		$this->article_model->art_url = $this->input->post('art_url');
		$this->article_model->art_portada = $this->input->post('art_portada');
		$this->article_model->art_abstracto = $this->input->post('art_abstracto');
		$this->article_model->art_contenido = $this->input->post('art_contenido');
		$this->article_model->art_etiquetas = $this->input->post('art_etiquetas');
		$this->article_model->art_estado = $this->input->post('art_estado');
		$this->article_model->art_fecha = $this->input->post('art_fecha');
		$this->article_model->art_pdf = $this->input->post('art_pdf');
		$this->article_model->art_autores = $this->input->post('art_autores');
		$this->article_model->art_categorias = $this->input->post('art_categorias');
		$this->response->succeed = $this->article_model->save_article();
		if($this->response->succeed == TRUE){
			$this->response->code = 200; //ok
			$this->response->status = 'El artículo se ha guardado correctamente.';
		} else{
			$this->response->code = 500; //error interno del servidor
			$this->response->status = 'Ha ocurrido un error, intentelo de nuevo más tarde.';
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
		return;
	}

	public function delete_article(){
		//verifica si la solicitud se produce mediante xhr, de no ser asi redirige al usuario al inicio
		if($this->input->is_ajax_request() == FALSE){
			redirect('/admin');
			return;
		}
		//verifica si existe una sesion activa para guardar la informacion en la base de datos
		if($this->user_session == FALSE){
			$this->response->succeed = FALSE;
			$this->response->code = 401; //no autorizado
			$this->response->status = 'La sesión ha expirado.';
			$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
			return;
		}
		//verifica si el usuario tiene los permisos suficientes para guardar datos de categorias
		$this->load->model('admin_model');
		$this->admin_model->adm_id = $this->user_session;
		if($this->admin_model->get_user_access('articles') == FALSE){
			$this->response->succeed = FALSE;
			$this->response->code = 550; //permiso denegado
			$this->response->status = 'No tienes privilegios suficientes para realizar esta acción.';
			$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
			return;
		}
		//verifica si la contraseña proporcionada por el usuario es correcta
		if($this->check_for_password($this->user_session,$this->input->post('adm_password')) == FALSE){
			$this->response->succeed = FALSE;
			$this->response->code = 550;
			$this->response->status = 'La contraseña proporcionada no es la correcta.';
			$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
			return;
		}
		//si las condiciones anteriores se cumplen continua con la eliminacion de la categoria
		$this->load->model('article_model');
		$this->article_model->art_id = $this->input->post('art_id');
		$this->response->succeed = $this->article_model->delete_article();
		if($this->response->succeed == TRUE){
			$this->response->code = 200; //ok
			$this->response->status = 'El artículo se ha eliminado correctamente.';
		} else{
			$this->response->code = 500; //error interno del servidor
			$this->response->status = 'Ha ocurrido un error, intentelo de nuevo más tarde.';
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
		return;
	}

	public function search_authors(){
		//verifica si la solicitud se produce mediante xhr, de no ser asi redirige al usuario al inicio
		if($this->input->is_ajax_request() == FALSE){
			redirect('/admin');
			return;
		}
		//verifica si existe una sesion activa para guardar la informacion en la base de datos
		if($this->user_session == FALSE){
			$this->response->succeed = FALSE;
			$this->response->code = 401; //no autorizado
			$this->response->status = 'La sesión ha expirado';
			$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
			return;
		}
		//verifica si el usuario tiene los permisos suficientes para guardar datos de categorias
		$this->load->model('admin_model');
		$this->admin_model->adm_id = $this->user_session;
		if($this->admin_model->get_user_access('articles') == FALSE){
			$this->response->succeed = FALSE;
			$this->response->code = 550; //permiso denegado
			$this->response->status = 'No tienes privilegios suficientes para realizar esta acción';
			$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
			return;
		}
		$this->load->model('user_model');
		$this->response = $this->user_model->search_authors($this->input->post('search'));
		$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
		return;
	}

	private function check_for_password($user_id,$password){
		$this->load->model('admin_model');
		$this->admin_model->adm_id = $user_id;
		$this->admin_model->adm_password = $password;
		return $this->admin_model->check_for_password();
	}

}

/* End of file articulos.php */
/* Location: ./application/controllers/admin/articulos.php */