<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Estadisticas extends CI_Controller {

	var $response;
	var $user_session;
	var $user_name;

	public function __construct(){
		parent::__construct();
		$this->response = new stdClass();
		$this->user_session = $this->session->userdata('admin_id');
		$this->user_name = $this->session->userdata('admin_name');
	}

	public function index(){
		redirect('/admin/estadisticas/usuarios');
	}

	public function stats_articles(){
		
	}

	public function stats_users(){
		//si no existe sesion de usuario redirige a iniciar sesion
		if($this->user_session == FALSE){
			redirect('/admin/login');
			return;
		}
		//si el usuario tiene acceso al modulo ser carga la vista, si no se redirige al inicio
		$this->load->model('admin_model');
		$this->admin_model->adm_id = $this->user_session;
		if($this->admin_model->get_user_access('stats') == FALSE){
			redirect('/admin');
			return;
		}
		//carga los datos que se enviaran a la vista
		$data['menu'] = $this->admin_model->get_menu_by_user();
		$data['active'] = 'stats';
		$data['title'] = 'Panel de administración';
		$data['user'] = $this->user_name;
		//scripts de la vista
		$data['scripts'] = Array(
			'https://www.google.com/jsapi',
			base_url('scripts').'/system/stats/stats_users.js'
		);
		
		//carga las vistas
		$this->load->view('system/common/header',$data);
		$this->load->view('system/common/navbar');
		$this->load->view('system/stats/stats_users');
		$this->load->view('system/common/footer');
	}

	public function get_users_stats(){
		if($this->input->is_ajax_request() == FALSE){
			redirect('/admin/estadisticas');
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
		if($this->admin_model->get_user_access('stats') == FALSE){
			$this->response->succeed = FALSE;
			$this->response->code = 550; //permiso denegado
			$this->response->status = 'No tienes privilegios suficientes para realizar esta acción.';
			$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
			return;
		}

		$this->load->model('stats_model');
		$this->response = $this->stats_model->get_users_stats();
		$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
		return;
	}

	public function get_curriculum_count(){
		if($this->input->is_ajax_request() == FALSE){
			redirect('/admin/estadisticas');
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
		if($this->admin_model->get_user_access('stats') == FALSE){
			$this->response->succeed = FALSE;
			$this->response->code = 550; //permiso denegado
			$this->response->status = 'No tienes privilegios suficientes para realizar esta acción.';
			$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
			return;
		}

		$this->load->model('stats_model');
		$this->response = $this->stats_model->get_curriculum_count();
		$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
		return;
	}
}

/* End of file estadisticas.php */
/* Location: ./application/controllers/admin/estadisticas.php */