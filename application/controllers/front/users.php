<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Controller {

	var $response;
	var $front_uid;
	var $front_uem;

	public function __construct(){
		parent::__construct();
		$this->response = new stdClass();
		$this->front_uid = $this->session->userdata('front_uid');
		$this->front_uem = $this->session->userdata('front_uem');
	}

	public function index($usr_id){
		if($usr_id == NULL){
			show_404();
			return;
		}
		$this->load->model('user_model');
		$this->user_model->usr_id = $usr_id;
		$account = $this->user_model->get_public_account();
		//si no exite el usuario muestra un error 404
		if($account == FALSE){
			show_404();
			return;
		} else {
			$data['user'] = $account;
		}
		//datos que se envian a la vista
		$data['title'] = $account->usr_nombre.' | Revista ITM';
		$data['front_uid'] = $this->front_uid;
		$data['front_uem'] = $this->front_uem;
		//scripts
		$data['scripts'] = Array(
			base_url('scripts').'/front/users/profile_picture.js'
		);
		$data['meta_tags'] = Array(
			//direccion canonica del articulo
			'<link rel="canonical" href="'.get_user_url($account->usr_id).'">',
			'<meta name="description" content="'.strip_text($account->cur_abstract,150).'"/>',
			'<meta name="keywords" content="'.$account->usr_nombre.'"/>',
			'<meta name="author" content="'.$account->usr_nombre.'"/>',
			'<meta name="copyright" content="© '.get_year(mysql_date()).' Instituto Tecnológico de Morelia, '.$account->usr_nombre.'"/>',
			//facebook opengraph tags
			'<meta property="fb:app_id" content="660426037349263"/>',
			'<meta property="og:title" content="'.$account->usr_nombre.' | Revista ITM"/>',
			'<meta property="og:site_name" content="Revista del Instituto Tecnológico de Morelia"/>',
			'<meta property="og:url" content="'.get_user_url($account->usr_id).'"/>',
			'<meta property="og:description" content="'.strip_text($account->cur_abstract,150).'"/>',
			'<meta property="og:image" content="'.base_url().$account->usr_imagen.'"/>',
			'<meta property="og:type" content="profile"/>',
			'<meta property="og:locale" content="es_LA"/>',
		);
		//cargar las vistas
		$this->load->view('front/common/header',$data);
		$this->load->view('front/users/public_account');
		$this->load->view('front/common/footer');
	}

	public function account(){
		//si no existe la sesion redirige a la vista de login
		if($this->front_uid == FALSE){
			redirect('/login');
			return;
		}
		//obtiene los datos del ususario
		$this->load->model('user_model');
		$this->user_model->usr_id = $this->front_uid;
		$account = $this->user_model->get_account();
		//si no existe el usuario redirige a vista de login
		if($account == FALSE){
			redirect('/login');
			return;
		} else {
			$data['user'] = $account;	
		}
		//datos que se envian a la vista
		$data['title'] = 'Mi Cuenta | Revista ITM';
		$data['front_uid'] = $this->front_uid;
		$data['front_uem'] = $this->front_uem;
		//scripts
		$data['scripts'] = Array(
			base_url('scripts').'/front/users/profile_picture.js'
		);
		//cargar las vistas
		$this->load->view('front/common/header',$data);
		$this->load->view('front/users/account');
		$this->load->view('front/common/footer');
	}

	public function edit_account(){
		//si no existe la sesion redirige a la vista de login
		if($this->front_uid == FALSE){
			redirect('/login');
			return;
		}
		//obtiene los datos del usuario
		$this->load->model('user_model');
		$this->user_model->usr_id = $this->front_uid;
		$account = $this->user_model->get_account_edit();
		//si no existe el usuario redirige a vista de login
		if($account == FALSE){
			redirect('/login');
			return;
		} else {
			$data['user'] = $account;	
		}
		//datos que se envian a la vista
		$data['title'] = 'Editar Mi Cuenta | Revista ITM';
		$data['front_uid'] = $this->front_uid;
		$data['front_uem'] = $this->front_uem;
		//scripts que se envian a la vista
		$data['scripts'] = Array(
			base_url('scripts').'/front/users/profile_picture.js',
			base_url('scripts').'/front/users/edit_user.js'
		);
		//cargar las vistas
		$this->load->view('front/common/header',$data);
		$this->load->view('front/users/edit_account');
		$this->load->view('front/common/footer');
	}

	public function save_account(){
		//si no existe la sesion redirige a la vista de login
		if($this->front_uid == FALSE){
			redirect('/login');
			return;
		}
		$this->load->model('user_model');
		//configura los datos para subir la imagen de perfil
		$config['upload_path'] = './users_uploads/images/';
		$config['allowed_types'] = 'jpg|jpeg|png|bmp';
		$config['max_size'] = '1024';
		$config['encrypt_name'] = TRUE;
		$config['remove_spaces'] = TRUE;
		$this->load->library('upload',$config);
		//hace la subida de la imagen
		if(!$this->upload->do_upload('usr_imagen')){ //si no se subio ningun archivo o fallo la subida
			//solo guarda los datos del nombre y contraseña
			$this->user_model->usr_id = $this->front_uid;
			$this->user_model->usr_nombre = $this->input->post('usr_nombre');
			if($this->input->post('usr_password') != null || $this->input->post('usr_password') != ''){
				$this->user_model->usr_password = $this->input->post('usr_password');
			}
			$this->user_model->save_account();
		} else { //si se subio un nuevo archivo
			//guarda los datos de nombre, contraseña e imagen
			$img_data = $this->upload->data();
			$this->user_model->usr_id = $this->front_uid;
			$this->user_model->usr_nombre = $this->input->post('usr_nombre');
			$this->user_model->usr_imagen = 'users_uploads/images/'.$img_data['file_name'];
			if($this->input->post('usr_password') != null || $this->input->post('usr_password') != ''){
				$this->user_model->usr_password = $this->input->post('usr_password');
			}
			$this->user_model->save_account();
		}
		redirect('/mi_cuenta');
	}

	public function edit_curriculum(){
		//si no existe la sesion redirige a la vista de login
		if($this->front_uid == FALSE){
			redirect('/login');
			return;
		}
		//obtiene los datos del usuario
		$this->load->model('user_model');
		$this->user_model->usr_id = $this->front_uid;
		$account = $this->user_model->get_curriculum_edit();
		//si no existe el usuario redirige a vista de login
		if($account == FALSE){
			redirect('/login');
			return;
		} else {
			$data['user'] = $account;
		}
		//datos que se envian a la vista
		$data['title'] = 'Editar Mi Curriculum | Revista ITM';
		$data['front_uid'] = $this->front_uid;
		$data['front_uem'] = $this->front_uem;
		//scripts que se envian a la vista
		$data['scripts'] = Array(
			base_url('scripts').'/front/users/edit_curriculum.js'
		);
		//cargar las vistas
		$this->load->view('front/common/header',$data);
		$this->load->view('front/users/edit_curriculum');
		$this->load->view('front/common/footer');
	}

	public function save_curriculum(){
		//si no existe la sesion redirige a la vista de login
		if($this->front_uid == FALSE){
			redirect('/login');
			return;
		}
		$this->load->model('user_model');
		//configura los datos para subir la imagen de perfil
		$config['upload_path'] = './users_uploads/curriculums/';
		$config['allowed_types'] = 'pdf';
		$config['max_size'] = '2048';
		$config['encrypt_name'] = TRUE;
		$config['remove_spaces'] = TRUE;
		$this->load->library('upload',$config);
		//hace la subida de la imagen
		if(!$this->upload->do_upload('cur_pdf')){
			$this->user_model->usr_id = $this->front_uid;
			$this->user_model->cur_id = $this->input->post('cur_id');
			$this->user_model->cur_url = $this->input->post('cur_url');
			$this->user_model->cur_email = $this->input->post('cur_email');
			$this->user_model->cur_abstract = $this->input->post('cur_abstract');
			$this->user_model->save_curriculum();
		} else {
			$cur_data = $this->upload->data();
			$this->user_model->usr_id = $this->front_uid;
			$this->user_model->cur_id = $this->input->post('cur_id');
			$this->user_model->cur_url = $this->input->post('cur_url');
			$this->user_model->cur_email = $this->input->post('cur_email');
			$this->user_model->cur_abstract = $this->input->post('cur_abstract');
			$this->user_model->cur_pdf = 'users_uploads/curriculums/'.$cur_data['file_name'];
			$this->user_model->save_curriculum();
		}
		redirect('/mi_cuenta');
	}

	public function bookmarks($offset=1){
		if($this->front_uid == FALSE){
			redirect('/login');
			return;
		}
		//obtiene los datos del usuario
		$this->load->model('user_model');
		$this->user_model->usr_id = $this->front_uid;
		$data['bookmarks'] = $this->user_model->get_account_library(12,(($offset-1)*12));
		$data['total_bookmarks'] = $this->user_model->get_total_bookmarks();
		//datos que se envian a la vista
		$data['title'] = 'Marcadores | Revista ITM';
		$data['front_uid'] = $this->front_uid;
		$data['front_uem'] = $this->front_uem;
		//scripts que se envian a la vista
		$data['scripts'] = Array(
			base_url('scripts').'/front/users/bookmarks.js'
		);
		//cargar las vistas
		$this->load->view('front/common/header',$data);
		$this->load->view('front/users/bookmarks');
		$this->load->view('front/common/footer');
	}

	public function save_bookmark(){
		//verifica si la solicitud se produce mediante xhr, de no ser asi redirige al usuario al inicio
		if($this->input->is_ajax_request() == FALSE){
			redirect('/login');
			return;
		}
		//verifica si ya hay una sesion iniciada
		if($this->front_uid == FALSE){
			$this->response->succeed = FALSE;
			$this->response->code = 400;
			$this->response->status = 'Tu sesión se ha cerrado o es invalida.';
			$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
			return;
		}
		$this->load->model('user_model');
		$this->user_model->usr_id = $this->front_uid;
		$this->response->succeed = $this->user_model->save_bookmark($this->input->post('art_id'));
		if($this->response->succeed == TRUE){
			$this->response->code = 200;
			$this->response->status = '¡El artículo se ha guardado correctamente en tu biblioteca!';
		} else { 
			$this->response->code = 500;
			$this->response->status = 'No hemos podido guardar el artículo en tu biblioteca, intentalo de nuevo más tarde.';
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
		return;
	}

	public function remove_bookmark(){
		//verifica si la solicitud se produce mediante xhr, de no ser asi redirige al usuario al inicio
		if($this->input->is_ajax_request() == FALSE){
			redirect('/login');
			return;
		}
		//verifica si ya hay una sesion iniciada
		if($this->front_uid == FALSE){
			$this->response->succeed = FALSE;
			$this->response->code = 400;
			$this->response->status = 'Tu sesión se ha cerrado o es invalida.';
			$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
			return;
		}
		$this->load->model('user_model');
		$this->user_model->usr_id = $this->front_uid;
		$this->response->succeed = $this->user_model->remove_bookmark($this->input->post('bus_id'));
		if($this->response->succeed == TRUE){
			$this->response->code = 200;
			$this->response->status = '¡Se ha eliminado el marcador!';
		} else { 
			$this->response->code = 500;
			$this->response->status = 'No hemos podido eliminar tu marcador, intentalo de nuevo más tarde.';
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
		return;
	}

	public function login(){
		//si existe la sesión redirige al panel de usuario
		if($this->front_uid != FALSE){
			redirect('/mi_cuenta');
			return;
		}
		//datos que se envian a la vista
		$data['title'] = 'Iniciar Sesión | Revista ITM';
		$data['front_uid'] = $this->front_uid;
		$data['front_uem'] = $this->front_uem;
		//scripts de la vista
		$data['scripts'] = Array(
			base_url('scripts').'/front/users/login.js'
		);
		//cargar las vistas
		$this->load->view('front/common/header',$data);
		$this->load->view('front/users/login');
		$this->load->view('front/common/footer');
	}

	public function login_now(){
		//si la llamada no se realiza mediante ajax, el sistema no hara caso de la peticion
		if($this->input->is_ajax_request() == FALSE){
			redirect('/login');
			return;
		}
		//si existe una sesion activa informa el error
		if($this->front_uid != FALSE){
			$this->response->succeed = FALSE;
			$this->response->code = 301; //redireccion
			$this->response->status = 'Ya has iniciado sesión anteriormente.';
			$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
			return;
		}
		//si las condiciones anteriores se cumplen continua con la funcion
		$this->load->model('user_model');
		$this->user_model->usr_email = $this->input->post('usr_email');
		$this->user_model->usr_password = $this->input->post('usr_password');
		$this->response = $this->user_model->login();
		if($this->response->succeed == TRUE){
			$this->response->code = 200; //ok request
			$this->response->status = 'La sesión se ha iniciado correctamente.';
			$this->session->set_userdata('front_uid',$this->response->usr_id);
			$this->session->set_userdata('front_uem',$this->response->usr_email);
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
		return;
	}

	public function logout(){
		$this->session->unset_userdata('front_uid');
		$this->session->unset_userdata('front_uem');
		redirect('/login');
		return;
	}

	public function register(){
		if($this->front_uid != FALSE){
			redirect('/mi_cuenta');
			return;
		}
		//datos que se envian a la vista
		$data['title'] = 'Registro | Revista del ITM';
		$data['front_uid'] = $this->front_uid;
		$data['front_uem'] = $this->front_uem;
		//scripts de la vista
		$data['scripts'] = Array(
			base_url('scripts').'/front/users/register.js'
		);
		//cargar las vistas
		$this->load->view('front/common/header',$data);
		$this->load->view('front/users/register');
		$this->load->view('front/common/footer');
	}

	public function register_new(){
		//verifica si la solicitud se produce mediante xhr, de no ser asi redirige al usuario al inicio
		if($this->input->is_ajax_request() == FALSE){
			redirect('/registrar');
			return;
		}
		//verifica si ya hay una sesion iniciada
		if($this->front_uid != FALSE){
			$this->response->succeed = FALSE;
			$this->response->code = 400;
			$this->response->status = 'Ya has iniciado sesión.';
			$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
			return;
		}
		//verifica si el correo electronico que ha introducido es correcto
		if($this->check_for_email(0,$this->input->post('usr_email')) == TRUE){
			$this->response->succeed = FALSE;
			$this->response->code = 200;
			$this->response->status = 'El correo electrónico ya existe, por favor ingresa uno diferente.';
			$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
			return;
		}
		//verifica si las contraseñas son correctas
		if($this->input->post('usr_password') != $this->input->post('usr_rep_password')){
			$this->response->succeed = FALSE;
			$this->response->code = 200;
			$this->response->status = 'Las contraseñas no coinciden o no son correctas.';
			$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
			return;
		}
		//si las condiciones se cumplen continua con el registro
		$this->load->model('user_model');
		$this->user_model->usr_nombre = $this->input->post('usr_nombre');
		$this->user_model->usr_email = $this->input->post('usr_email');
		$this->user_model->usr_password = $this->input->post('usr_password');
		$this->response = $this->user_model->register_new();
		if($this->response->succeed == TRUE){
			$this->response->code = 200; //ok
			$this->response->status = '¡Tu cuenta se ha creado!';
			//TODO: enviar correo de verificacion
			//http://revista/index.php/activar/codigo_verificacion
		} else{
			$this->response->code = 500; //error interno del servidor
			$this->response->status = 'Ha ocurrido un error, intentalo de nuevo más tarde.';
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
		return;
	}

	public function activate_user($activation_code){
		//verificar si existe la sesion
		if($this->front_uid != FALSE){
			redirect('/mi_cuenta');
			return;
		}
		$this->load->model('user_model');
		$this->user_model->usr_codigo = $activation_code;
		$this->response = $this->user_model->activate_user();
		if($this->response->succeed == TRUE){
			$this->response->message = '¡Tu cuenta <b>('.$this->response->email.')</b> se ha activado correctamente! Ahora puedes ir a la vista de 
			inicio de sesión para comenzar a configurar tu cuenta.';
		} else {
			$this->response->message = '¡Ha ocurrido un error! Al parecer el código de verificación es incorrecto
			o tu cuenta ya se ha activado previamente. Si ya la has activado simplemente tienes que iniciar sesión.';
		}
		//datos ha enviar a la vista
		$data['title'] = 'Activación de la cuenta | Revista del ITM';
		$data['front_uid'] = $this->front_uid;
		$data['front_uem'] = $this->front_uem;
		$data['response'] = $this->response;
		//cargar las vistas
		$this->load->view('front/common/header',$data);
		$this->load->view('front/users/activate');
		$this->load->view('front/common/footer');
	}

	/*---------------FUNCTIONS FOR VALIDATION----------------*/

	public function check_for_password(){
		//verifica si es una llamada ajax
		if($this->input->is_ajax_request() == FALSE){
			redirect('/mi_cuenta');
			return;
		}
		//verifica si ya hay una sesion iniciada
		if($this->front_uid == FALSE){
			$this->response->succeed = FALSE;
			$this->response->code = 400;
			$this->response->status = 'Tu sesión se ha cerrado o es invalida.';
			$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
			return;
		}
		//continua con el procedimiento
		$this->load->model('user_model');
		$this->user_model->usr_id = $this->front_uid;
		$this->user_model->usr_password = $this->input->post('usr_password');
		$this->response->succeed = $this->user_model->check_for_password();
		if($this->response->succeed == TRUE){
			$this->response->code = 200;
			$this->response->status = "Contraseña correcta.";
		} else {
			$this->response->code = 401;
			$this->response->status = "La contraseña no coincide.";
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
		return;
	}

	private function check_for_email($user_id,$email){
		$this->load->model('user_model');
		$this->user_model->usr_id = $user_id;
		$this->user_model->usr_email = $email;
		return $this->user_model->check_for_email();
	}
}

/* End of file users.php */
/* Location: ./application/controllers/front/users.php */