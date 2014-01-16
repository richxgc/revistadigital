<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model {

	var $usr_table = 'usuarios_revista';
	var $usr_id;
	var $usr_curp;
	var $usr_nombre;
	var $usr_email;
	var $usr_password;
	var $usr_imagen;
	var $usr_tipo;

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