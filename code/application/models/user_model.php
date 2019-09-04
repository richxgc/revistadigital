<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model {

	var $usr_table = 'usuarios_revista';
	var $usr_id;
	var $usr_nombre;
	var $usr_email;
	var $usr_password;
	var $usr_imagen;
	var $usr_activo;
	var $usr_codigo;
	//curriculum model
	var $cur_table = 'curriculum_usuarios';
	var $cur_id;
	var $cur_url;
	var $cur_email;
	var $cur_abstract;
	var $cur_pdf;

	/***********FRONTEND********/

	function get_public_account(){
		if($this->usr_id == NULL){
			return FALSE;
		}
		$this->db->select('usuarios_revista.usr_id,usuarios_revista.usr_nombre,usuarios_revista.usr_email,usuarios_revista.usr_imagen,
			curriculum_usuarios.cur_id,curriculum_usuarios.cur_url,curriculum_usuarios.cur_abstract,curriculum_usuarios.cur_pdf,curriculum_usuarios.cur_email');
		$this->db->join('curriculum_usuarios','curriculum_usuarios.usr_id = usuarios_revista.usr_id','left');
		$this->db->where('usuarios_revista.usr_id',$this->usr_id);
		$this->db->from($this->usr_table);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$user = $query->row();
			$user->usr_articulos = $this->get_account_articles();
			return $user;
		} else {
			return FALSE;
		}
	}

	function get_account(){
		if($this->usr_id == NULL || $this->usr_id == FALSE){
			return FALSE;
		}
		$this->db->select('usuarios_revista.usr_id,usuarios_revista.usr_nombre,usuarios_revista.usr_email,usuarios_revista.usr_imagen,
			curriculum_usuarios.cur_id,curriculum_usuarios.cur_url,curriculum_usuarios.cur_abstract,curriculum_usuarios.cur_pdf,curriculum_usuarios.cur_email');
		$this->db->join('curriculum_usuarios','curriculum_usuarios.usr_id = usuarios_revista.usr_id','left');
		$this->db->where('usuarios_revista.usr_id',$this->usr_id);
		$this->db->from($this->usr_table);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$user = $query->row();
			//obtiene todos los articulos publicados por el usuario
			$user->usr_articulos = $this->get_account_articles();
			//obtiene los primeros 5 articulos en la biblioteca del usuario
			$user->usr_biblioteca = $this->get_account_library(2,0);
			return $user;
		} else {
			return FALSE;
		}
	}

	function get_account_edit(){
		if($this->usr_id == NULL || $this->usr_id == FALSE){
			return FALSE;
		}
		$this->db->select('usr_id,usr_nombre,usr_imagen');
		$this->db->where('usr_id',$this->usr_id);
		$this->db->from($this->usr_table);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->row();
		} else {
			return FALSE;
		}
	}

	function get_curriculum_edit(){
		if($this->usr_id == NULL || $this->usr_id == FALSE){
			return FALSE;
		}
		$this->db->select('usuarios_revista.usr_id,curriculum_usuarios.cur_id,curriculum_usuarios.cur_url,curriculum_usuarios.cur_abstract,curriculum_usuarios.cur_pdf,curriculum_usuarios.cur_email');
		$this->db->join('curriculum_usuarios','curriculum_usuarios.usr_id = usuarios_revista.usr_id','left');
		$this->db->where('usuarios_revista.usr_id',$this->usr_id);
		$this->db->from($this->usr_table);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->row();
		} else {
			return NULL;
		}
	}

	function get_account_articles(){
		if($this->usr_id == NULL){
			return NULL;
		}
		$this->db->select('*');
		$this->db->join('autores_articulo','autores_articulo.art_id = articulos_revista.art_id');
		$this->db->where('autores_articulo.usr_id',$this->usr_id);
		$this->db->where('art_estado','publicado');
		$this->db->from('articulos_revista');
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->result();
		} else {
			return NULL;
		}
	}

	function get_account_library($limit,$offset){
		if($this->usr_id == NULL){
			return NULL;
		}
		$this->db->select('*');
		$this->db->join('biblioteca_usuario','biblioteca_usuario.art_id = articulos_revista.art_id');
		$this->db->where('biblioteca_usuario.usr_id',$this->usr_id);
		$this->db->order_by('biblioteca_usuario.bus_fecha','DESC');
		$this->db->limit($limit,$offset);
		$this->db->from('articulos_revista');
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->result();
		} else {
			return NULL;
		}
	}

	function get_total_bookmarks(){
		if($this->usr_id == NULL){
			return 0;
		}
		$this->db->where('usr_id',$this->usr_id);
		$this->db->from('biblioteca_usuario');
		return $this->db->count_all_results();
	}

	function save_bookmark($art_id){
		if($this->usr_id == NULL || $art_id == NULL){
			return FALSE;
		}
		$this->db->set('usr_id',$this->usr_id);
		$this->db->set('art_id',$art_id);
		$this->db->set('bus_fecha',mysql_date());
		$this->db->insert('biblioteca_usuario');
		if($this->db->affected_rows() > 0){
			return TRUE;
		} else {
			return FALSE;
		}

	}

	function remove_bookmark($bus_id){
		if($this->usr_id == NULL || $bus_id == NULL){
			return FALSE;
		}
		$this->db->where('bus_id',$bus_id);
		$this->db->where('usr_id',$this->usr_id);
		$this->db->delete('biblioteca_usuario');
		if($this->db->affected_rows() > 0){
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function save_account(){
		if($this->usr_id == NULL || $this->usr_id == FALSE || $this->usr_nombre == NULL){
			return FALSE;
		}
		$data = Array(
			'usr_nombre' => $this->usr_nombre
		);
		if($this->usr_password != NULL){
			$data['usr_password'] = crypt($this->usr_password, 'do');
		}
		if($this->usr_imagen != NULL){
			$data['usr_imagen'] = $this->usr_imagen;
			//elimina el archivo actual
			$this->db->select('usr_imagen');
			$this->db->where('usr_id',$this->usr_id);
			$this->db->from($this->usr_table);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				$image = $query->row()->usr_imagen;
				unlink('./'.$image);
			}
		}
		$this->db->where('usr_id',$this->usr_id);
		if($this->db->update($this->usr_table,$data)){
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function save_curriculum(){
		if($this->usr_id == NULL || $this->usr_id == FALSE){
			return FALSE;
		}
		if($this->cur_id == NULL){ //crea el nuevo curriculum
			$this->db->set('usr_id',$this->usr_id);
			$this->db->set('cur_url',$this->cur_url);
			$this->db->set('cur_email',$this->cur_email);
			$this->db->set('cur_abstract',preg_replace("/[\\n\\r]+/", " ", strip_tags($this->cur_abstract)));
			$this->db->set('cur_pdf',$this->cur_pdf);
			$this->db->insert($this->cur_table);
			if($this->db->affected_rows() > 0){
				return TRUE;
			} else {
				return FALSE;
			}
		} else { //actualiza el curriculum actual
			$data = Array(
				'cur_url' => $this->cur_url,
				'cur_email' => $this->cur_email,
				'cur_abstract' => preg_replace("/[\\n\\r]+/", " ", strip_tags($this->cur_abstract)),
			);
			if($this->cur_pdf != NULL){
				$data['cur_pdf'] = $this->cur_pdf;
				//elimina el archivo actual
				$this->db->select('cur_pdf');
				$this->db->where('cur_id',$this->cur_id);
				$this->db->from($this->cur_table);
				$query = $this->db->get();
				if($query->num_rows() > 0){
					$curriculum = $query->row()->cur_pdf;
					unlink('./'.$curriculum);
				}
			}
			$this->db->where('cur_id',$this->cur_id);
			if($this->db->update($this->cur_table,$data)){
				return TRUE;
			} else {
				return FALSE;
			}
		}
	}

	function login(){
		$response = new stdClass();
		if($this->usr_email == NULL || $this->usr_password == NULL){
			$response->succeed = FALSE;
			$response->code = 400; //bad request
			$response->status = 'La solicitud no es adecuada.';
			return $response;
		}
		//obtiene los datos del email ingresado
		$this->db->select('usr_id,usr_email,usr_password,usr_activo');
		$this->db->where('usr_email',$this->usr_email);
		$this->db->from($this->usr_table);
		$query = $this->db->get();
		//si el usuario existe 
		if($query->num_rows() > 0){
			//si el usuario esta activo continua
			$user = $query->row();
			if($user->usr_activo == 1){
				$hashed_pswd = $user->usr_password;
				//verifica que la contraseña sea la correcta
				if(crypt($this->usr_password,$hashed_pswd) == $hashed_pswd){
					//la contraseña y el usuario son correctos
					$response->succeed = TRUE;
					$response->usr_id = $user->usr_id;
					$response->usr_email = $user->usr_email;
				} else { //si la contraseña no es la correcta
					$response->succeed = FALSE;
					$response->code = 550; //permission denied
					$response->status = 'La contraseña proporcionada no es la correcta.';
				}
			} else { //si no esta activo
				$response->succeed = FALSE;
				$response->code = 401; // unauthorized
				$response->status = 'Tu cuenta aún no ha sido verificada.';
			}
		} else { // el usuario no existe
			$response->succeed = FALSE;
			$response->code = 550; //permission denied
			$response->status = 'El usuario que has ingresado no existe.';
		}
		return $response;
	}

	function check_for_email(){
		$this->db->select('usr_id,usr_email');
		$this->db->where('usr_email',$this->usr_email);
		$this->db->from($this->usr_table);
		$query = $this->db->get()->row();
		if(sizeof($query) > 0 && $this->usr_id != $query->usr_id){
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function register_new(){
		$response = new stdClass();
		if($this->usr_nombre == NULL || $this->usr_email == NULL || $this->usr_password == NULL){
			$response->succeed = FALSE;
			return $response;
		}
		$this->db->set('usr_nombre',$this->usr_nombre);
		$this->db->set('usr_email',$this->usr_email);
		$this->db->set('usr_password',crypt($this->usr_password, 'do'));
		$this->db->set('usr_activo',0);
		$activation_code = urlencode(crypt($this->usr_nombre.$this->usr_email, 'do'));
		$this->db->set('usr_codigo',$activation_code);
		$this->db->insert($this->usr_table);
		if($this->db->affected_rows() > 0){
			$response->succeed = TRUE;
			$response->activation_code = $activation_code;
		} else{
			$response->succeed = FALSE;
		}
		return $response;
	}

	function activate_user(){
		$response = new stdClass();
		if($this->usr_codigo == NULL){
			$response->succeed = FALSE;
			return $response;
		}
		//verifica si existe un usuario con el codigo de verificación especificado
		$this->db->select('usr_id,usr_email');
		$this->db->where('usr_codigo',$this->usr_codigo);
		$this->db->from($this->usr_table);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$user = $query->row();
			//cambia el estado del usuario a activo (1)
			$data = Array(
				'usr_activo' => 1,
			);
			$this->db->where('usr_id',$user->usr_id);
			$this->db->update($this->usr_table,$data);
			if($this->db->affected_rows() > 0){
				$response->succeed = TRUE;
				$response->email = $user->usr_email;
			} else {
				$response->succeed = FALSE;
			}
			return $response;
		} else {
			$response->succeed = FALSE;
			return $response;
		}
	}

	function check_for_password(){
		$this->db->select('usr_password');
		$this->db->where('usr_id',$this->usr_id);
		$this->db->from($this->usr_table);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$user = $query->row();
			$hashed_pswd = $user->usr_password;
			if(crypt($this->usr_password,$hashed_pswd) == $hashed_pswd){
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	/********BACKEND*******/

	function search_authors($search_string){
		$this->db->select('usr_id,usr_nombre,usr_imagen');
		$this->db->like('usr_nombre',$search_string);
		$this->db->from($this->usr_table);
		$user = $this->db->get();
		return $user->result();
	}
}

/* End of file user_model.php */
/* Location: ./application/models/user_model.php */