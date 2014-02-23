<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class System_model extends CI_Model {

	//modelado de la tabla de usuarios
	var $adm_table = 'usuarios_administracion';
	var $adm_id;
	var $adm_nombre;
	var $adm_email;
	var $adm_password;
	//modelado de la tabla de modulos
	var $mad_table = 'modulos_administracion';
	var $aca_table = 'acceso_administracion';
	var $mad_id;

	function db_enabled(){
		$this->db->select('*');
		$this->db->limit(1);
		$this->db->from($this->adm_table);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return TRUE;
		} else{
			return FALSE;
		}
	}

	function install_system(){
		if($this->adm_nombre == NULL || $this->adm_email == NULL || $this->adm_password == NULL){
			return FALSE;
		}
		//inicia una transaccion en la base de datos para asegurar la integridad de la informacion inicial
		$this->db->trans_begin();
		//insertar datos del usuario principal
		$this->db->set('adm_nombre',$this->adm_nombre);
		$this->db->set('adm_email',$this->adm_email);
		$this->db->set('adm_password',crypt($this->adm_password));
		$this->db->set('adm_tipo','super'); //define al super administrador del sistema
		$this->db->insert($this->adm_table);
		$this->adm_id = $this->db->insert_id();
		//insertar datos de las rutas de los modulos de administracion
		//datos del modulo de estadisticas
		$this->db->set('mad_nombre','stats');
		$this->db->set('mad_url','estadisticas');
		$this->db->set('mad_menu','Estadísticas');
		$this->db->insert($this->mad_table);
		$this->mad_id = $this->db->insert_id();
		//datos del modulo de portadas
		$this->db->set('mad_nombre','covers');
		$this->db->set('mad_url','portadas');
		$this->db->set('mad_menu','Portadas');
		$this->db->insert($this->mad_table);
		$this->mad_id = $this->db->insert_id();
		//permisos de acceso al usuario de instalacion para el modulo de estadisticas
		$this->db->set('adm_id',$this->adm_id);
		$this->db->set('mad_id',$this->mad_id);
		$this->db->insert($this->aca_table);
		//datos del modulo de publicacion de contenidos
		$this->db->set('mad_nombre','articles');
		$this->db->set('mad_url','articulos');
		$this->db->set('mad_menu','Artículos');
		$this->db->insert($this->mad_table);
		$this->mad_id = $this->db->insert_id();
		//permisos de acceso al usuario de instalacion para el modulo de publicacion
		$this->db->set('adm_id',$this->adm_id);
		$this->db->set('mad_id',$this->mad_id);
		$this->db->insert($this->aca_table);
		//datos del modulo de administracion de categorias
		$this->db->set('mad_nombre','categories');
		$this->db->set('mad_url','categorias');
		$this->db->set('mad_menu','Categorías');
		$this->db->insert($this->mad_table);
		$this->mad_id = $this->db->insert_id();
		//permisos de acceso al usuario de instalacion para el modulo de categorias
		$this->db->set('adm_id',$this->adm_id);
		$this->db->set('mad_id',$this->mad_id);
		$this->db->insert($this->aca_table);
		//datos del modulo de administracion de usuarios
		$this->db->set('mad_nombre','users');
		$this->db->set('mad_url','usuarios');
		$this->db->set('mad_menu','Usuarios');
		$this->db->insert($this->mad_table);
		$this->mad_id = $this->db->insert_id();
		//permisos de acceso al usuario de instalacion para el modulo de usuarios
		$this->db->set('adm_id',$this->adm_id);
		$this->db->set('mad_id',$this->mad_id);
		$this->db->insert($this->aca_table);
		//crear el cascaron para la portada principal
		$this->db->set('por_nombre','Principal');
		$this->db->set('por_tipo','principal');
		$this->db->insert('portadas_revista');
		//verificar el estado de la transaccion y notificar al controlador
		if($this->db->trans_status() === FALSE){
			//hacer rollback si la transaccion fallo
			$this->db->trans_rollback();
			return FALSE;
		} else{
			//hacer commit si la transaccion es exitosa
			$this->db->trans_commit();
			return TRUE;
		}
	}
}

/* End of file system_model.php */
/* Location: ./application/models/system_model.php */