<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_model extends CI_Model {

	//modelado de la tabla de usuarios
	var $adm_table = 'usuarios_administracion';
	var $adm_id;
	var $adm_nombre;
	var $adm_email;
	var $adm_password;
	var $adm_tipo;
	var $adm_modulos;

	function login(){
		$response = new stdClass();
		//verifica si existen datos de entrada
		if($this->adm_email == NULL || $this->adm_password == NULL){
			$response->succeed = FALSE;
			return $response;
		}
		//obtiene los datos del usuario segun el email ingresado
		$this->db->select('*');
		$this->db->where('adm_email',$this->adm_email);
		$this->db->from($this->adm_table);
		$user = $this->db->get()->row();
		if(sizeof($user) > 0){
			//si el usuario existe verifica que sea la contraseña correcta
			$hashed_pswd = $user->adm_password;
			if(crypt($this->adm_password,$hashed_pswd) == $hashed_pswd){
				//la contraseña y el usuario son correctos
				$response->succeed = TRUE;
				$response->adm_id = $user->adm_id;
				$response->adm_nombre = $user->adm_nombre;
			} else {
				//la contraseña no es la correcta
				$response->succeed = FALSE;
			}
		} else {
			//el usuario no existe
			$response->succeed = FALSE;
		}
		//regresa la respuesta al controlador
		return $response;
	}

	function get_admin_type(){
		if($this->adm_id == NULL){
			return NULL;
		}
		$this->db->select('adm_tipo');
		$this->db->where('adm_id',$this->adm_id);
		$this->db->from($this->adm_table);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$adm_tipo = $query->row()->adm_tipo;
			if($adm_tipo == 'super'){
				return 'super';
			} else if($adm_tipo != NULL){
				return explode(',', $adm_tipo);	
			} else{
				return NULL;
			}
		} else {
			return NULL;
		}
	}

	function get_total_users(){
		$this->db->select('adm_id');
		$this->db->from($this->adm_table);
		$query = $this->db->get();
		return $query->num_rows();
	}

	function get_user(){
		if($this->adm_id == NULL){
			return FALSE;
		}
		$this->db->select('adm_id,adm_nombre,adm_email,adm_tipo');
		$this->db->where('adm_id',$this->adm_id);
		$this->db->from($this->adm_table);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$user = $query->row();
			$user->adm_modulos = $this->get_user_modules_array($user->adm_id);
			return $user;
		} else {
			return FALSE;
		}
	}

	function get_users_at($limit,$offset){
		$this->db->select('adm_id,adm_nombre,adm_email,adm_tipo');
		$this->db->order_by('adm_id','ASC');
		$this->db->limit($limit,$offset);
		$this->db->from($this->adm_table);
		$users = $this->db->get()->result();
		if(sizeof($users) > 0){
			foreach($users as &$user){
				$user->adm_modulos = $this->get_user_modules($user->adm_id);
			}
		}
		return $users;
	}

	private function get_user_modules_array($user_id){
		$this->db->select('mad_nombre');
		$this->db->join('acceso_administracion','acceso_administracion.mad_id = modulos_administracion.mad_id');
		$this->db->join('usuarios_administracion','usuarios_administracion.adm_id = acceso_administracion.adm_id');
		$this->db->where('usuarios_administracion.adm_id',$user_id);
		$this->db->order_by('modulos_administracion.mad_id','ASC');
		$this->db->from('modulos_administracion');
		$query = $this->db->get();
		return $query->result();
	}

	private function get_user_modules($user_id){
		$this->db->select('*');
		$this->db->join('acceso_administracion','acceso_administracion.mad_id = modulos_administracion.mad_id');
		$this->db->join('usuarios_administracion','usuarios_administracion.adm_id = acceso_administracion.adm_id');
		$this->db->where('usuarios_administracion.adm_id',$user_id);
		$this->db->order_by('modulos_administracion.mad_id','ASC');
		$this->db->from('modulos_administracion');
		$query = $this->db->get()->result();
		$modules = NULL;
		if(sizeof($query) > 0){
			$modules = '';
			foreach($query as $module){
				$modules .= $module->mad_menu.', ';
			}
			$modules = substr($modules,0,-2);
		} else{
			$modules = 'Ninguno';
		}
		return $modules;
	}

	function get_menu_by_user(){
		if($this->adm_id == NULL){
			return array();
		}
		$this->db->select('modulos_administracion.mad_id,modulos_administracion.mad_nombre,modulos_administracion.mad_url,modulos_administracion.mad_menu');
		$this->db->join('acceso_administracion','acceso_administracion.mad_id = modulos_administracion.mad_id');
		$this->db->join('usuarios_administracion','usuarios_administracion.adm_id = acceso_administracion.adm_id');
		$this->db->where('usuarios_administracion.adm_id',$this->adm_id);
		$this->db->order_by('modulos_administracion.mad_id','ASC');
		$this->db->from('modulos_administracion');
		$query = $this->db->get();
		return $query->result();
	}

	function get_user_access($module){
		$this->db->select('modulos_administracion.mad_id');
		$this->db->join('acceso_administracion','acceso_administracion.mad_id = modulos_administracion.mad_id');
		$this->db->join('usuarios_administracion','usuarios_administracion.adm_id = acceso_administracion.adm_id');
		$this->db->where('modulos_administracion.mad_nombre',$module);
		$this->db->where('usuarios_administracion.adm_id',$this->adm_id);
		$this->db->from('modulos_administracion');
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return TRUE;
		} else{
			return FALSE;
		}
	}

	function check_for_password(){
		$this->db->select('adm_password');
		$this->db->where('adm_id',$this->adm_id);
		$this->db->from($this->adm_table);
		$query = $this->db->get()->row();
		if(sizeof($query) > 0){
			$hashed_pswd = $query->adm_password;
			if(crypt($this->adm_password,$hashed_pswd) == $hashed_pswd){
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	function check_for_email(){
		$this->db->select('adm_id,adm_email');
		$this->db->where('adm_email',$this->adm_email);
		$this->db->from($this->adm_table);
		$query = $this->db->get()->row();
		if(sizeof($query) > 0 && $this->adm_id != $query->adm_id){
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function save_account(){
		if($this->adm_id == NULL || $this->adm_nombre == NULL || $this->adm_email == NULL){
			return FALSE;
		}
		$data = NULL;
		if($this->adm_password == NULL || $this->adm_password == ''){
			$data = array(
				'adm_nombre' => $this->adm_nombre,
				'adm_email' => $this->adm_email,
			);
		} else {
			$data = array(
				'adm_nombre' => $this->adm_nombre,
				'adm_email' => $this->adm_email,
				'adm_password' => crypt($this->adm_password, 'do'),
			);
		}
		$this->db->where('adm_id',$this->adm_id);
		if($this->db->update($this->adm_table,$data)){
			return TRUE;
		} else{
			return FALSE;
		}

	}

	function create_user(){
		if($this->adm_nombre == NULL || $this->adm_email == NULL || $this->adm_password == NULL){
			return FALSE;
		}
		//comienza transacción en la base de datos
		$this->db->trans_begin();
		$this->db->set('adm_nombre',$this->adm_nombre);
		$this->db->set('adm_email',$this->adm_email);
		$this->db->set('adm_password',crypt($this->adm_password, 'do'));
		$this->db->set('adm_tipo',$this->adm_tipo);
		$this->db->insert($this->adm_table);
		$this->adm_id = $this->db->insert_id();
		if($this->adm_modulos != NULL && $this->adm_modulos != ''){
			foreach($this->adm_modulos as $module){
				//obtener el id del modulo que se desea dar de alta
				$this->db->select('mad_id');
				$this->db->where('mad_nombre',$module);
				$this->db->from('modulos_administracion');
				$module_id = $this->db->get()->row()->mad_id;
				//insertar la relacion de modulos en la base de datos
				$this->db->set('adm_id',$this->adm_id);
				$this->db->set('mad_id',$module_id);
				$this->db->insert('acceso_administracion');
			}
		}
		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return FALSE;
		} else{
			$this->db->trans_commit();
			return TRUE;
		}
	}

	function save_user(){
		if($this->adm_id == NULL || $this->adm_nombre == NULL || $this->adm_email == NULL){
			return FALSE;
		}
		//comienza transacción en la base de datos
		$this->db->trans_begin();
		$data = array(
			'adm_nombre' => $this->adm_nombre,
			'adm_email' => $this->adm_email,
			'adm_tipo' => $this->adm_tipo,
		);
		$this->db->where('adm_id',$this->adm_id);
		$this->db->update($this->adm_table,$data);
		//verifica si es el ultimo usuario del sistema y le prohibe modificar sus permisos
		$total_users = $this->get_total_users();
		//si hay mas de 1 usuario en el sistema continua con la modificacion de permisos
		if($total_users > 1 && $this->adm_modulos != 'all'){
			//elimina los permisos anteriores para insertar los nuevos
			$this->db->where('adm_id',$this->adm_id);
			$this->db->delete('acceso_administracion');
			if($this->adm_modulos != NULL || $this->adm_modulos != ''){	
				foreach($this->adm_modulos as $module){
					//obtener el id del modulo que se desea dar de alta
					$this->db->select('mad_id');
					$this->db->where('mad_nombre',$module);
					$this->db->from('modulos_administracion');
					$module_id = $this->db->get()->row()->mad_id;
					//insertar la relacion de modulos en la base de datos
					$this->db->set('adm_id',$this->adm_id);
					$this->db->set('mad_id',$module_id);
					$this->db->insert('acceso_administracion');
				}
			}
		}
		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return FALSE;
		} else{
			$this->db->trans_commit();
			return TRUE;
		}
	}

	function delete_user(){
		if($this->adm_id == NULL){
			return FALSE;
		}
		//busca en la base de datos la cantidad de usuarios
		$total_users = $this->get_total_users();
		if($total_users == 1){
			return FALSE;
		} elseif($total_users == 2){
			$this->db->trans_begin();
			$this->db->where('adm_id',$this->adm_id);
			$this->db->delete($this->adm_table);
			//asigna todos los privilegios al ultimo usuario
			$this->db->select('adm_id');
			$this->db->limit(1);
			$this->db->order_by('adm_id','ASC');
			$this->db->from($this->adm_table);
			$last_user_id = $this->db->get()->row()->adm_id;
			//elimina los permisos establecidos anteriormente
			$this->db->where('adm_id',$last_user_id);
			$this->db->delete('acceso_administracion');
			//obtiene de la base de datos todos los modulos disponibles
			$this->db->select('mad_id');
			$this->db->order_by('mad_id','ASC');
			$this->db->from('modulos_administracion');
			$modules = $this->db->get()->result();
			//escribe todos los permisos de nuevo
			foreach($modules as $module){
				$this->db->set('adm_id',$last_user_id);
				$this->db->set('mad_id',$module->mad_id);
				$this->db->insert('acceso_administracion');	
			}
			//verifica la transacción para responder segun sea el caso
			if($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				return FALSE;
			} else{
				$this->db->trans_commit();
				return TRUE;
			}

		} else{
			$this->db->where('adm_id',$this->adm_id);
			$this->db->delete($this->adm_table);
			//respuesta de la consulta generada
			if($this->db->affected_rows() > 0){
				return TRUE;
			} else{
				return FALSE;
			}
		}
	}
}

/* End of file admin_model.php */
/* Location: ./application/models/admin_model.php */