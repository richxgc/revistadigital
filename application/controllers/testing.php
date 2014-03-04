<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Testing extends CI_Controller {

	var $user_session;
	var $user_name;
	var $response;

	function __construct(){
		parent::__construct();
		$this->user_session = $this->session->userdata('admin_id');
		$this->user_name = $this->session->userdata('admin_name');
		$this->response = new stdClass();
	}


	/************TESTING***********/

	public function delete(){
		//unlink('http://localhost/revista/users_uploads/curriculums/52bbaa9355abf98a2ed7b7f341578351.pdf');
		$s = unlink('./users_uploads/curriculums/52bbaa9355abf98a2ed7b7f341578351.pdf');
	}

	//unit testing
	public function insert_borrador(){

		for($i=0; $i<100; $i++){
			$this->db->set('adm_id',$this->user_session);
			$this->db->set('art_titulo','articulo-'.$i);
			$this->db->set('art_url','articulo-'.$i);
			$this->db->set('art_portada','http://*.jpg');
			$this->db->set('art_abstracto','articulo-'.$i);
			$this->db->set('art_contenido','articulo-'.$i);
			$this->db->set('art_etiquetas','tags');
			$this->db->set('art_estado','borrador');
			$this->db->set('art_fecha','2014-01-29');
			$this->db->set('art_pdf','');
			$this->db->insert('articulos_revista');
		}
	}

	public function insert_categorias(){
		for($i=0; $i<50; $i++){
			$this->db->set('cat_super_id',NULL);
			$this->db->set('cat_nombre','categoria-'.$i);
			$this->db->set('cat_url','categoria-'.$i);
			$this->db->set('cat_color','#000000');
			$this->db->insert('categorias_revista');
		}
	}

	public function insert_usuarios(){
		for($i=0; $i<30; $i++){
			$this->db->set('adm_nombre','usuario-'.$i);
			$this->db->set('adm_email','usuario.'.$i.'@mail.com');
			$this->db->set('adm_password',crypt('12345'));
			$this->db->insert('usuarios_administracion');
		}
	}
}

/* End of file testing.php */
/* Location: ./application/controllers/testing.php */