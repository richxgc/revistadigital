<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Portadas extends CI_Controller {

	var $response;
	var $user_session;
	var $user_name;

	public function __construct(){
		parent::__construct();
		$this->response = new stdClass();
		$this->user_session = $this->session->userdata('admin_id');
		$this->user_name = $this->session->userdata('admin_name');
	}

	public function index($offset=1){
		if($this->user_session == FALSE){
			redirect('/admin/login');
			return;
		}
		//si el usuario tiene acceso al modulo ser carga la vista, si no se redirige al inicio
		$this->load->model('admin_model');
		$this->admin_model->adm_id = $this->user_session;
		if($this->admin_model->get_user_access('covers') == FALSE){
			redirect('/admin');
			return;
		}
		//carga los datos que se enviaran a la vista
		$data['menu'] = $this->admin_model->get_menu_by_user();
		$data['active'] = 'covers';
		$data['title'] = 'Edición de portadas';
		$data['user'] = $this->user_name;
		//cargar los datos de las portadas
		$this->load->model('cover_model');
		$data['covers'] = $this->cover_model->get_covers_at(10,(($offset-1)*10));
		$data['total_covers'] = $this->cover_model->get_total_covers();
		//envia los scripts de la vista
		$data['scripts'] = Array(
			base_url('scripts').'/system/covers/home_covers.js'
		);
		//cargar vistas de pagina principal de portadas
		$this->load->view('system/common/header',$data);
		$this->load->view('system/common/navbar');
		$this->load->view('system/covers/covers');
		$this->load->view('system/common/footer');
	}

	public function edit_cover($cover_id){
		if($this->user_session == FALSE){
			redirect('/admin/login');
			return;
		}
		//si el usuario tiene acceso al modulo ser carga la vista, si no se redirige al inicio
		$this->load->model('admin_model');
		$this->admin_model->adm_id = $this->user_session;
		if($this->admin_model->get_user_access('covers') == FALSE){
			redirect('/admin');
			return;
		}
		//obtiene los datos de la portada que se desea editar
		$this->load->model('cover_model');
		$this->cover_model->por_id = $cover_id;
		$cover = $this->cover_model->get_cover();
		if($cover == FALSE){
			show_404();
			return;
		} else {
			$data['cover'] = $cover;
		}
		//carga los datos que se enviaran a la vista
		$data['menu'] = $this->admin_model->get_menu_by_user();
		$data['active'] = 'covers';
		$data['title'] = 'Editar Portada '.$cover->por_nombre;
		$data['user'] = $this->user_name;
		//cargar los scripts
		if($cover->por_tipo == 'principal'){
			$data['scripts'] = array(
				base_url('scripts').'/system/covers/edit_principal.js'
			);	
		} elseif($cover->por_tipo == 'categoria') {
			$data['scripts'] = array(
				base_url('scripts').'/system/covers/edit_categoria.js'
			);
		}
		//cargar vistas de pagina principal de portadas
		$this->load->view('system/common/header',$data);
		$this->load->view('system/common/navbar');
		if($cover->por_tipo == 'principal'){
			$this->load->view('system/covers/edit_principal');	

		} elseif($cover->por_tipo == 'categoria') {
			$this->load->view('system/covers/edit_categoria');
		}
		$this->load->view('system/common/footer');
	}

	public function save_cover(){
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
		if($this->admin_model->get_user_access('covers') == FALSE){
			$this->response->succeed = FALSE;
			$this->response->code = 550; //permiso denegado
			$this->response->status = 'No tienes privilegios suficientes para realizar esta acción.';
			$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
			return;
		}
		$this->load->model('cover_model');
		$this->cover_model->por_id = $this->input->post('por_id');
		$this->cover_model->por_datos = $this->input->post('por_datos');
		$this->response->succeed = $this->cover_model->save_cover();
		if($this->response->succeed == TRUE){
			$this->response->code = 200; //ok
			$this->response->status = 'La portada se ha guardado correctamente.';
		} else {
			$this->response->code = 500; //error interno del servidor
			$this->response->status = 'Ha ocurrido un error, intentelo de nuevo más tarde.';
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
		return;
	}

	public function create_category_cover(){
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
		if($this->admin_model->get_user_access('covers') == FALSE){
			$this->response->succeed = FALSE;
			$this->response->code = 550; //permiso denegado
			$this->response->status = 'No tienes privilegios suficientes para realizar esta acción.';
			$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
			return;
		}
		//obtine el nombre de la categoria
		$this->load->model('category_model');
		$this->category_model->cat_id = $this->input->post('cat_id');
		$cat_nombre = $this->category_model->get_category_name();
		//crea la nueva portada segun la categoria especificada
		$this->load->model('cover_model');
		$this->cover_model->cat_id = $this->input->post('cat_id');
		$this->cover_model->por_nombre = $cat_nombre;
		$this->cover_model->por_datos = NULL;
		$this->cover_model->por_tipo = 'categoria';
		$this->response = $this->cover_model->create_category_cover();
		if($this->response->succeed == true){
			$this->response->code = 200; //ok
			$this->response->status = 'La portada se ha creado correctamente.';
		} else {
			$this->response->code = 500; //error interno del servidor
			$this->response->status = 'Ha ocurrido un error, intentelo de nuevo más tarde.';
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
		return;
	}

	public function search_articles(){
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
		if($this->admin_model->get_user_access('covers') == FALSE){
			$this->response->succeed = FALSE;
			$this->response->code = 550; //permiso denegado
			$this->response->status = 'No tienes privilegios suficientes para realizar esta acción.';
			$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
			return;
		}
		$this->load->model('article_model');
		$this->response = $this->article_model->search_articles($this->input->post('search'));
		$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
		return;
	}

}

/* End of file portadas.php */
/* Location: ./application/controllers/admin/portadas.php */