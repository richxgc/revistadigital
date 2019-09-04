<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inicio extends CI_Controller {

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
		//si no existe sesion de usuario redirige a iniciar sesion
		if($this->user_session == FALSE){
			return redirect('/admin/login');
		}
		$this->load->model('admin_model');
		$this->admin_model->adm_id = $this->user_session;
		//carga los datos que se enviaran a la vista
		$data['menu'] = $this->admin_model->get_menu_by_user();
		$data['active'] = 'home';
		$data['title'] = 'Panel de administración';
		$data['user'] = $this->user_name;
		//carga las vistas
		$this->load->view('system/common/header',$data);
		$this->load->view('system/common/navbar');
		$this->load->view('system/welcome');
		$this->load->view('system/common/footer');
	}

	/*---------------MANEJO DE LA SESION--------------*/

	public function login(){
		//si existe una sesion activa redirige al panel de administracion
		if($this->user_session != FALSE){
			redirect('/admin');
			return;
		}
		//verifica si el sistema no se ha instalado aun, de ser asi redirige al proceso de instalacion
		$this->load->model('system_model');
		if($this->system_model->db_enabled() == FALSE){
			redirect('/system');
			return;
		}
		//cargar vista de inicio de sesion
		$data['title'] = 'Iniciar Sesión';
		$this->load->view('system/login',$data);
		return;
	}

	public function login_now(){
		//si la llamada no se realiza mediante ajax, el sistema no hara caso de la peticion
		if($this->user_session != FALSE || $this->input->is_ajax_request() == FALSE){
			redirect('/admin');
			return;
		}
		//si las condiciones son optimas realiza el inicio de sesion
		$this->load->model('admin_model');
		$this->admin_model->adm_email = $this->input->post('adm_email');
		$this->admin_model->adm_password = $this->input->post('adm_password');
		$this->response = $this->admin_model->login();
		if($this->response->succeed == TRUE){
			$this->session->set_userdata('admin_id',$this->response->adm_id);
			$this->session->set_userdata('admin_name',$this->response->adm_nombre);
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
		return;
	}

	public function logout(){
		$this->session->unset_userdata('admin_id');
		$this->session->unset_userdata('admin_name');
		redirect('/admin');
		return;
	}

}

/* End of file inicio.php */
/* Location: ./application/controllers/admin/inicio.php */