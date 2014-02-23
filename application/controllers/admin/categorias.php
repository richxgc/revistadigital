<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Categorias extends CI_Controller {

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
		if($this->admin_model->get_user_access('categories') == FALSE){
			redirect('/admin');
			return;
		}
		//carga los datos que se enviaran a la vista
		$data['menu'] = $this->admin_model->get_menu_by_user();
		$data['active'] = 'categories';
		$data['title'] = 'Categorias de artículos';
		$data['user'] = $this->user_name;
		$this->load->model('category_model');
		$data['categories'] = $this->category_model->get_categories_at(10,(($offset-1)*10));
		$data['total_categories'] = $this->category_model->get_total_categories();
		//scripts que se cargaran en la vista
		$data['scripts'] = array(
			base_url('scripts').'/system/categories/home_categories.js',
		);
		//cargar vistas de pagina principal de categorias
		$this->load->view('system/common/header',$data);
		$this->load->view('system/common/navbar');
		$this->load->view('system/categories/categories');
		$this->load->view('system/common/footer');
	}

	public function new_category(){
		if($this->user_session == FALSE){
			redirect('/admin/login');
			return;
		}
		$this->load->model('admin_model');
		$this->admin_model->adm_id = $this->user_session;
		if($this->admin_model->get_user_access('categories') == FALSE){
			redirect('/admin');
			return;
		}
		//carga los datos que se enviaran a la vista
		$data['menu'] = $this->admin_model->get_menu_by_user();
		$data['active'] = 'categories';
		$data['title'] = 'Nueva categoría';
		$data['user'] = $this->user_name;
		//enviar datos de las categorias existentes
		$this->load->model('category_model');
		$data['categories'] = $this->category_model->get_categories();
		//stilos que se cargaran en la vista
		$data['styles'] = array(
			base_url('stylesheets').'/system/colorpicker.css',
		);
		//scripts que se cargaran en la vista
		$data['scripts'] = array(
			base_url('scripts').'/system/categories/colorpicker.js',
			base_url('scripts').'/system/categories/create_category.js',
		);
		//cargar vista para nueva categoria
		$this->load->view('system/common/header',$data);
		$this->load->view('system/common/navbar');
		$this->load->view('system/categories/create');
		$this->load->view('system/common/footer');
	}

	public function create_category(){
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
		if($this->admin_model->get_user_access('categories') == FALSE){
			$this->response->succeed = FALSE;
			$this->response->code = 550; //permiso denegado
			$this->response->status = 'No tienes privilegios suficientes para realizar esta acción';
			$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
			return;
		}
		//si se cumplen las condiciones anteriores continua con el proceso de guardado
		$this->load->model('category_model');
		if($this->input->post('cat_super_id') == '' || $this->input->post('cat_super_id') == NULL){
			$this->category_model->cat_super_id = NULL;
		} else {
			$this->category_model->cat_super_id = $this->input->post('cat_super_id');	
		}
		$this->category_model->cat_nombre = $this->input->post('cat_nombre');
		$this->category_model->cat_url = $this->input->post('cat_url');
		$this->category_model->cat_color = $this->input->post('cat_color');
		$this->response->succeed = $this->category_model->create_category();
		if($this->response->succeed == TRUE){
			$this->response->code = 200; //ok
			$this->response->status = 'La categoria se ha guardado correctamente';
		} else {
			$this->response->code = 500; //error interno del servidor
			$this->response->status = 'Ha ocurrido un error, intentelo de nuevo más tarde';
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
		return;
	}

	public function edit_category($cat_id){
		if($this->user_session == FALSE){
			redirect('/admin/login');
			return;
		}
		//si el usuario tiene acceso al modulo ser carga la vista, si no se redirige al inicio
		$this->load->model('admin_model');
		$this->admin_model->adm_id = $this->user_session;
		if($this->admin_model->get_user_access('categories') == FALSE){
			redirect('/admin');
			return;
		}
		//obtiene los datos de la categoria
		$this->load->model('category_model');
		$this->category_model->cat_id = $cat_id;
		$category = $this->category_model->get_category();
		if($category == FALSE){
			show_404();
			return;
		}
		//carga los datos que se enviaran a la vista
		$data['menu'] = $this->admin_model->get_menu_by_user();
		$data['active'] = 'categories';
		$data['user'] = $this->user_name;
		$data['title'] = 'Editar categoria '. $category->cat_nombre;
		$data['category'] = $category;
		$data['categories'] = $this->category_model->get_categories();
		//stilos que se cargaran en la vista
		$data['styles'] = array(
			base_url('stylesheets').'/system/colorpicker.css',
		);
		//scripts que se cargaran en la vista
		$data['scripts'] = array(
			base_url('scripts').'/system/categories/colorpicker.js',
			base_url('scripts').'/system/categories/edit_category.js',
		);
		//cargar vista para nueva categoria
		$this->load->view('system/common/header',$data);
		$this->load->view('system/common/navbar');
		$this->load->view('system/categories/edit');
		$this->load->view('system/common/footer');
	}

	public function save_category(){
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
		if($this->admin_model->get_user_access('categories') == FALSE){
			$this->response->succeed = FALSE;
			$this->response->code = 550; //permiso denegado
			$this->response->status = 'No tienes privilegios suficientes para realizar esta acción';
			$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
			return;
		}
		//si se cumplen las condiciones anteriores continua con el proceso de guardado
		$this->load->model('category_model');
		if($this->input->post('cat_super_id') == '' || $this->input->post('cat_super_id') == NULL){
			$this->category_model->cat_super_id = NULL;
		} else {
			$this->category_model->cat_super_id = $this->input->post('cat_super_id');	
		}
		$this->category_model->cat_id = $this->input->post('cat_id');
		$this->category_model->cat_nombre = $this->input->post('cat_nombre');
		$this->category_model->cat_url = $this->input->post('cat_url');
		$this->category_model->cat_color = $this->input->post('cat_color');
		$this->response->succeed = $this->category_model->save_category();
		if($this->response->succeed == TRUE){
			$this->response->code = 200; //ok
			$this->response->status = 'La categoria se ha guardado correctamente';
		} else {
			$this->response->code = 500; //error interno del servidor
			$this->response->status = 'Ha ocurrido un error, intentelo de nuevo más tarde';
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
		return;
	}

	public function delete_category(){
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
		if($this->admin_model->get_user_access('categories') == FALSE){
			$this->response->succeed = FALSE;
			$this->response->code = 550; //permiso denegado
			$this->response->status = 'No tienes privilegios suficientes para realizar esta acción.';
			$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
			return;
		}
		//verifica si la contraseña proporcionada por el usuario es correcta
		if($this->check_for_password($this->user_session,$this->input->post('adm_password')) == FALSE){
			$this->response->succeed = FALSE;
			$this->response->code = 550; //permiso denegado
			$this->response->status = 'La contraseña proporcionada no es la correcta.';
			$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
			return;
		}
		//si las condiciones anteriores se cumplen continua con la eliminacion de la categoria
		$this->load->model('category_model');
		$this->category_model->cat_id = $this->input->post('cat_id');
		$this->response->succeed = $this->category_model->delete_category();
		if($this->response->succeed == TRUE){
			$this->response->code = 200; //ok
			$this->response->status = 'La categoria se ha eliminado correctamente.';
		} else{
			$this->response->code = 500; //error interno del servidor
			$this->response->status = 'Ha ocurrido un error, intentelo de nuevo más tarde.';
		}
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

/* End of file categorias.php */
/* Location: ./application/controllers/admin/categorias.php */