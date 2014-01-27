<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {

	var $user_session;
	var $user_name;
	var $response;

	function __construct(){
		parent::__construct();
		$this->user_session = $this->session->userdata('admin_id');
		$this->user_name = $this->session->userdata('admin_name');
		$this->response = new stdClass();
	}

	public function index(){
		if($this->user_session == FALSE){
			redirect('/admin/login');
			return;
		}
		$this->load->model('admin_model');
		$this->admin_model->adm_id = $this->user_session;
		$data['menu'] = $this->admin_model->get_menu_by_user();
		$data['active'] = 'home';
		$data['title'] = 'Panel de administración';
		$data['user'] = $this->user_name;
		//load views
		$this->load->view('system/common/header',$data);
		$this->load->view('system/common/navbar');
		$this->load->view('system/common/footer');
		return;
	}

	/*--------------------FUNCIONES DE PUBLICACION----------------------*/

	public function articulos($limit=10,$offset=0,$status="publicado"){
		if($this->user_session == FALSE){
			redirect('/admin/login');
			return;
		}
		//si el usuario tiene acceso al modulo ser carga la vista, si no se redirige al inicio
		$this->load->model('admin_model');
		$this->admin_model->adm_id = $this->user_session;
		if($this->admin_model->get_user_access('articles') == TRUE){
			$data['menu'] = $this->admin_model->get_menu_by_user();
			$data['active'] = 'articles';
			$data['title'] = 'Publicación de artículos';
			$data['user'] = $this->user_name;
			$data['status'] = $status;
			$this->load->model('article_model');
			$data['articles'] = $this->article_model->get_articles_at($limit,$offset,$status);
			//cargar vistas de pagina principal de categorias
			$this->load->view('system/common/header',$data);
			$this->load->view('system/common/navbar');
			$this->load->view('system/articles/articles');
			$this->load->view('system/common/footer');
			return;
		} else{
			redirect('/admin');
			return;
		}
	}

	public function new_article(){
		if($this->user_session == FALSE){
			redirect('/admin/login');
			return;
		}
		//si el usuario tiene acceso al modulo ser carga la vista, si no se redirige al inicio
		$this->load->model('admin_model');
		$this->admin_model->adm_id = $this->user_session;
		if($this->admin_model->get_user_access('articles') == TRUE){
			//si no existe una categoria redirge a crear una
			$this->load->model('category_model');
			if($this->category_model->get_total_categories() == 0){
				redirect('/admin/categorias');
				return;
			}
			$data['menu'] = $this->admin_model->get_menu_by_user();
			$data['active'] = 'articles';
			$data['title'] = 'Publicar artículo';
			$data['user'] = $this->user_name;
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
			return;
		} else{
			redirect('/admin');
			return;
		}
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

	/*--------------------FUNCIONES DE CATEGORIAS----------------------*/

	public function categorias($limit=10,$offset=0){
		if($this->user_session == FALSE){
			redirect('/admin/login');
			return;
		}
		//si el usuario tiene acceso al modulo ser carga la vista, si no se redirige al inicio
		$this->load->model('admin_model');
		$this->admin_model->adm_id = $this->user_session;
		if($this->admin_model->get_user_access('categories') == TRUE){
			$data['menu'] = $this->admin_model->get_menu_by_user();
			$data['active'] = 'categories';
			$data['title'] = 'Categorias de artículos';
			$data['user'] = $this->user_name;
			$this->load->model('category_model');
			$data['categories'] = $this->category_model->get_categories_at($limit,$offset);
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
			return;
		} else{
			redirect('/admin');
			return;
		}
	}

	public function new_category(){
		if($this->user_session == FALSE){
			redirect('/admin/login');
			return;
		}
		$this->load->model('admin_model');
		$this->admin_model->adm_id = $this->user_session;
		if($this->admin_model->get_user_access('categories') == TRUE){
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
			return;
		} else{
			redirect('/admin');
			return;
		}
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
		}else{
			$this->category_model->cat_super_id = $this->input->post('cat_super_id');	
		}
		$this->category_model->cat_nombre = $this->input->post('cat_nombre');
		$this->category_model->cat_url = $this->input->post('cat_url');
		$this->category_model->cat_color = $this->input->post('cat_color');
		$this->response->succeed = $this->category_model->create_category();
		if($this->response->succeed == TRUE){
			$this->response->code = 200; //ok
			$this->response->status = 'La categoria se ha guardado correctamente';
		} else{
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
		if($this->admin_model->get_user_access('categories') == TRUE){
			$data['menu'] = $this->admin_model->get_menu_by_user();
			$data['active'] = 'categories';
			$data['user'] = $this->user_name;
			//enviar datos de las categorias existentes
			$this->load->model('category_model');
			$data['categories'] = $this->category_model->get_categories();
			$this->category_model->cat_id = $cat_id;
			$data['category'] = $this->category_model->get_category();
			$data['title'] = 'Editar categoria '. $data['category']->cat_nombre;
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
			return;
		} else{
			redirect('/admin');
		}
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
		}else{
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
		} else{
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
		if($this->get_user_password($this->user_session) != sha1($this->input->post('adm_password'))){
			$this->response->succeed = FALSE;
			$this->response->code = 550;
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

	/*--------------------FUNCIONES DE USUARIOS----------------------*/

	public function usuarios($limit=10,$offset=0){
		if($this->user_session == FALSE){
			redirect('/admin/login');
			return;
		}
		//si el usuario tiene acceso al modulo ser carga la vista, si no se redirige al inicio
		$this->load->model('admin_model');
		$this->admin_model->adm_id = $this->user_session;
		if($this->admin_model->get_user_access('users') == TRUE){
			$data['menu'] = $this->admin_model->get_menu_by_user();
			$data['active'] = 'users';
			$data['title'] = 'Usuarios del sistema';
			$data['user'] = $this->user_name;
			$data['users'] = $this->admin_model->get_users_at($limit,$offset);
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
			return;
		} else{
			redirect('/admin');
			return;
		}
	}

	public function new_user(){
		if($this->user_session == FALSE){
			redirect('/admin/login');
			return;
		}
		$this->load->model('admin_model');
		$this->admin_model->adm_id = $this->user_session;
		if($this->admin_model->get_user_access('users') == TRUE){
			$data['menu'] = $this->admin_model->get_menu_by_user();
			$data['active'] = 'users';
			$data['title'] = 'Nuevo usuario';
			$data['user'] = $this->user_name;
			//scripts que se cargaran en la vista
			$data['scripts'] = array(
				base_url('scripts').'/system/users/create_user.js',
			);
			//cargar vista para nueva categoria
			$this->load->view('system/common/header',$data);
			$this->load->view('system/common/navbar');
			$this->load->view('system/users/create');
			$this->load->view('system/common/footer');
			return;
		} else{
			redirect('/admin');
			return;
		}
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
		$this->admin_model->adm_password = sha1($this->input->post('adm_password'));
		$this->admin_model->adm_modulos = $this->input->post('modules');
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
		if($this->admin_model->get_user_access('users') == TRUE){
			$data['menu'] = $this->admin_model->get_menu_by_user();
			$data['active'] = 'users';
			$data['user'] = $this->user_name;
			//enviar datos de las categorias existentes
			$this->admin_model->adm_id = $user_id;
			$data['adm_user'] = $this->admin_model->get_user();
			$data['title'] = 'Editar usuario '. $data['adm_user']->adm_nombre;
			//scripts que se cargaran en la vista
			$data['scripts'] = array(
				base_url('scripts').'/system/users/edit_user.js',
			);

			//cargar vista para nueva categoria
			$this->load->view('system/common/header',$data);
			$this->load->view('system/common/navbar');
			$this->load->view('system/users/edit');
			$this->load->view('system/common/footer');
			return;
		} else{
			redirect('/admin');
		}
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
		$this->admin_model->adm_password = sha1($this->input->post('adm_password'));
		$this->admin_model->adm_modulos = $this->input->post('modules');
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
		//verifica si el usuario tiene los permisos suficientes para guardar datos de categorias
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
		if($this->get_user_password($this->user_session) != sha1($this->input->post('adm_password'))){
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
		$this->admin_model->adm_nombre = $this->input->post('adm_nombre');
		$this->admin_model->adm_password = sha1($this->input->post('adm_password'));
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

	private function get_user_password($user_id){
		$this->load->model('admin_model');
		$this->admin_model->adm_id = $user_id;
		return $this->admin_model->get_user_password();
	}
}

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */