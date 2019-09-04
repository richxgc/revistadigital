<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuarios extends CI_Controller {

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
		if($this->admin_model->get_user_access('users') == FALSE){
			redirect('/admin');
			return;
		}
		//carga los datos que se envian a la vista
		$data['menu'] = $this->admin_model->get_menu_by_user();
		$data['active'] = 'users';
		$data['title'] = 'Usuarios del sistema';
		$data['user'] = $this->user_name;
		$data['users'] = $this->admin_model->get_users_at(10,(($offset-1)*10));
		$data['total_users'] = $this->admin_model->get_total_users();
		//scripts que se cargaran en la vista
		$data['scripts'] = array(
			base_url('scripts').'/system/users/home_users.js',
		);
		//cargar vistas de pagina principal de categorias
		$this->load->view('system/common/header',$data);
		$this->load->view('system/common/navbar');
		$this->load->view('system/users/users');
		$this->load->view('system/common/footer');
	}

	public function new_user(){
		if($this->user_session == FALSE){
			redirect('/admin/login');
			return;
		}
		//si el usuario tiene acceso al modulo ser carga la vista, si no se redirige al inicio
		$this->load->model('admin_model');
		$this->admin_model->adm_id = $this->user_session;
		if($this->admin_model->get_user_access('users') == FALSE){
			redirect('/admin');
			return;
		}
		//carga los datos que se envian a la vista
		$data['menu'] = $this->admin_model->get_menu_by_user();
		$data['active'] = 'users';
		$data['title'] = 'Nuevo usuario';
		$data['user'] = $this->user_name;
		//carga las categorias del sistema
		$this->load->model('category_model');
		$data['categories'] = $this->category_model->get_categories();
		//scripts que se cargaran en la vista
		$data['scripts'] = array(
			base_url('scripts').'/system/users/create_user.js',
			base_url('scripts').'/system/users/access.js'
		);
		//cargar vista para nueva categoria
		$this->load->view('system/common/header',$data);
		$this->load->view('system/common/navbar');
		$this->load->view('system/users/create');
		$this->load->view('system/common/footer');
	}

	public function create_user(){
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
		if($this->admin_model->get_user_access('users') == FALSE){
			$this->response->succeed = FALSE;
			$this->response->code = 550; //permiso denegado
			$this->response->status = 'No tienes privilegios suficientes para realizar esta acción';
			$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
			return;
		}
		//verifica si el correo electronico que ha introducido es correcto
		if($this->check_for_email(0,$this->input->post('adm_email')) == TRUE){
			$this->response->succeed = FALSE;
			$this->response->code = 200;
			$this->response->status = 'El correo electrónico ya existe, por favor ingresa uno diferente.';
			$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
			return;
		}
		//verifica si las contraseñas son correctas
		if($this->input->post('adm_password') != $this->input->post('adm_rep_password')){
			$this->response->succeed = FALSE;
			$this->response->code = 200;
			$this->response->status = 'Las contraseñas no coinciden o no son correctas.';
			$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
			return;
		}
		//si se cumplen las condiciones anteriores continua con el proceso de guardado
		$this->admin_model->adm_nombre = $this->input->post('adm_nombre');
		$this->admin_model->adm_email = $this->input->post('adm_email');
		$this->admin_model->adm_password = $this->input->post('adm_password');
		$this->admin_model->adm_modulos = $this->input->post('modules');
		$permisos = $this->input->post('art_cat_permiso');
		if($permisos != NULL && $permisos != ''){
			$adm_tipo = '';
			foreach ($permisos as $permiso) {
				$adm_tipo .= $permiso.',';
			}
			$adm_tipo = substr($adm_tipo,0,-1);
			$this->admin_model->adm_tipo = $adm_tipo;
		} else {
			$this->admin_model->adm_tipo = NULL;
		}
		$this->response->succeed = $this->admin_model->create_user();
		if($this->response->succeed == TRUE){
			$this->response->code = 200; //ok
			$this->response->status = 'El usuario se ha guardado correctamente';
		} else{
			$this->response->code = 500; //error interno del servidor
			$this->response->status = 'Ha ocurrido un error, intentelo de nuevo más tarde';
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
		return;
	}

	public function edit_user($user_id){
		if($this->user_session == FALSE){
			redirect('/admin/login');
			return;
		}
		//si el usuario tiene acceso al modulo ser carga la vista, si no se redirige al inicio
		$this->load->model('admin_model');
		$this->admin_model->adm_id = $this->user_session;
		if($this->admin_model->get_user_access('users') == FALSE){
			redirect('/admin');
			return;
		}
		//carga los datos del usuario
		$this->admin_model->adm_id = $user_id;
		$user = $this->admin_model->get_user();
		//si el usuario no exite envia un error 404
		if($user == FALSE){
			show_404();
			return;
		}
		//datos que se enviaran a la vista
		$data['menu'] = $this->admin_model->get_menu_by_user();
		$data['active'] = 'users';
		$data['user'] = $this->user_name;
		$data['title'] = 'Editar usuario '. $user->adm_nombre;
		$data['adm_user'] = $user;
		//carga las categorias del sistema
		$this->load->model('category_model');
		$data['categories'] = $this->category_model->get_categories();
		//scripts que se cargaran en la vista
		$data['scripts'] = array(
			base_url('scripts').'/system/users/edit_user.js',
			base_url('scripts').'/system/users/access.js',
		);
		//cargar vista para nueva categoria
		$this->load->view('system/common/header',$data);
		$this->load->view('system/common/navbar');
		$this->load->view('system/users/edit');
		$this->load->view('system/common/footer');
	}

	public function save_user(){
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
		if($this->admin_model->get_user_access('users') == FALSE){
			$this->response->succeed = FALSE;
			$this->response->code = 550; //permiso denegado
			$this->response->status = 'No tienes privilegios suficientes para realizar esta acción';
			$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
			return;
		}
		//verifica si el correo electronico que ha introducido es correcto
		if($this->check_for_email($this->input->post('adm_id'),$this->input->post('adm_email')) == TRUE){
			$this->response->succeed = FALSE;
			$this->response->code = 200;
			$this->response->status = 'El correo electrónico ya existe, por favor ingresa uno diferente.';
			$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
			return;
		}
		//verifica si las contraseñas son correctas
		if($this->input->post('adm_password') != $this->input->post('adm_rep_password')){
			$this->response->succeed = FALSE;
			$this->response->code = 200;
			$this->response->status = 'Las contraseñas no coinciden o no son correctas.';
			$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
			return;
		}
		//si se cumplen las condiciones anteriores continua con el proceso de guardado
		$this->admin_model->adm_id = $this->input->post('adm_id');
		$this->admin_model->adm_nombre = $this->input->post('adm_nombre');
		$this->admin_model->adm_email = $this->input->post('adm_email');
		$this->admin_model->adm_modulos = $this->input->post('modules');
		if($this->input->post('adm_tipo') == 'super'){
			$this->admin_model->adm_tipo = 'super';
		} else {
			$permisos = $this->input->post('art_cat_permiso');
			if($permisos != NULL && $permisos != ''){
				$adm_tipo = '';
				foreach ($permisos as $permiso) {
					$adm_tipo .= $permiso.',';
				}
				$adm_tipo = substr($adm_tipo,0,-1);
				$this->admin_model->adm_tipo = $adm_tipo;
			} else {
				$this->admin_model->adm_tipo = NULL;
			}
		}
		$this->response->succeed = $this->admin_model->save_user();
		if($this->response->succeed == TRUE){
			$this->response->code = 200; //ok
			$this->response->status = 'El usuario se ha guardado correctamente';
		} else{
			$this->response->code = 500; //error interno del servidor
			$this->response->status = 'Ha ocurrido un error, intentelo de nuevo más tarde';
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
		return;
	}

	public function delete_user(){
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
		//verifica si el usuario tiene los permisos suficientes para modificar datos del usuario
		$this->load->model('admin_model');
		$this->admin_model->adm_id = $this->user_session;
		if($this->admin_model->get_user_access('users') == FALSE){
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
		//si las condiciones anteriores se cumplen continua con la eliminacion del usuario
		$this->admin_model->adm_id = $this->input->post('adm_id');
		$this->response->succeed = $this->admin_model->delete_user();
		if($this->response->succeed == TRUE){
			if($this->input->post('adm_id') == $this->user_session){
				$this->response->code = 301; //estado de redirección
				$this->response->status = 'Has eliminado tu perfil, si quieres recuperarlo contacta un administrador.';
			} else{
				$this->response->code = 200; //ok
				$this->response->status = 'El usuario se ha eliminado correctamente.';	
			}
		} else{
			$this->response->code = 500; //error interno del servidor
			$this->response->status = 'Ha ocurrido un error, intentelo de nuevo más tarde.';
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
		return;
	}

	/*---------------USER ACCOUNT FUNCTIONS------------------*/

	public function account(){
		if($this->user_session == FALSE){
			redirect('/admin/login');
			return;
		}
		$this->load->model('admin_model');
		$this->admin_model->adm_id = $this->user_session;
		//datos que se envian a las vistas
		$data['menu'] = $this->admin_model->get_menu_by_user();
		$data['active'] = '';
		$data['title'] = 'Configuración de cuenta';
		$data['user'] = $this->user_name;
		//datos del usuario
		$data['account'] = $this->admin_model->get_user();
		//scripts de la vista
		$data['scripts'] = array(
			base_url('scripts').'/system/users/account.js',
		);
		//cargar vistas de pagina principal de categorias
		$this->load->view('system/common/header',$data);
		$this->load->view('system/common/navbar');
		$this->load->view('system/users/account');
		$this->load->view('system/common/footer');
	}

	public function save_account(){
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
		//verifica si la contraseña proporcionada por el usuario es correcta
		if($this->check_for_password($this->user_session,$this->input->post('adm_password_origin')) == FALSE){
			$this->response->succeed = FALSE;
			$this->response->code = 550; //permiso denegado
			$this->response->status = 'La contraseña proporcionada no es la correcta.';
			$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
			return;
		}
		//verifica si el correo electronico que ha introducido es correcto
		if($this->check_for_email($this->user_session,$this->input->post('adm_email')) == TRUE){
			$this->response->succeed = FALSE;
			$this->response->code = 200;
			$this->response->status = 'El correo electrónico ya existe, por favor ingresa uno diferente.';
			$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
			return;
		}
		//verifica si las contraseñas son correctas
		if($this->input->post('adm_password') != $this->input->post('adm_rep_password')){
			$this->response->succeed = FALSE;
			$this->response->code = 200; //ok
			$this->response->status = 'Las contraseñas no coinciden o no son correctas.';
			$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
			return;
		}
		//si se cumplen las condiciones anteriores continua con el proceso de guardado
		$this->admin_model->adm_id = $this->user_session;
		$this->admin_model->adm_nombre = $this->input->post('adm_nombre');
		$this->admin_model->adm_email = $this->input->post('adm_email');
		$this->admin_model->adm_password = $this->input->post('adm_password');
		$this->response->succeed = $this->admin_model->save_account();
		if($this->response->succeed == TRUE){
			$this->response->code = 200; //ok
			$this->response->status = 'Tus datos se han guardado correctamente.';
		} else{
			$this->response->code = 500; //error interno del servidor
			$this->response->status = 'Ha ocurrido un error, intentelo de nuevo más tarde';
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
		return;
	}

	/*---------------PRIVATE FUNCTION FOR VALIDATION----------------*/

	private function check_for_password($user_id,$password){
		$this->load->model('admin_model');
		$this->admin_model->adm_id = $user_id;
		$this->admin_model->adm_password = $password;
		return $this->admin_model->check_for_password();
	}

	private function check_for_email($user_id,$email){
		$this->load->model('admin_model');
		$this->admin_model->adm_id = $user_id;
		$this->admin_model->adm_email = $email;
		return $this->admin_model->check_for_email();
	}

}

/* End of file usuarios.php */
/* Location: ./application/controllers/admin/usuarios.php */