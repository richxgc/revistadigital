<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cover_model extends CI_Model {

	//modelado de la tabla de portadas
	var $por_table = 'portadas_revista';
	var $por_id;
	var $cat_id;
	var $por_nombre;
	var $por_datos;
	var $por_tipo;

	/*************FRONTEND**************/

	function get_main_cover(){
		$this->db->select('por_datos');
		$this->db->where('por_tipo','principal');
		$this->db->from($this->por_table);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$cover = $query->row();
			return json_decode($cover->por_datos);
		} else {
			return NULL;
		}
	}

	function get_category_cover(){
		if($this->cat_id == NULL){
			return NULL;
		}
		$this->db->select('por_datos');
		$this->db->where('por_tipo','categoria');
		$this->db->where('cat_id',$this->cat_id);
		$this->db->from($this->por_table);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$cover = $query->row();
			return json_decode($cover->por_datos);
		} else {
			return NULL;
		}
	}

	/*************BACKEND**************/

	function get_cover(){
		if($this->por_id == NULL){
			return FALSE;
		}
		$this->db->select('*');
		$this->db->where('por_id',$this->por_id);
		$this->db->from($this->por_table);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			return $query->row();
		} else {
			return FALSE;
		}
	}
	
	function get_covers_at($limit,$offset){
		$covers = array();
		//obten la portada principal
		if($offset == 0){
			$this->db->select('por_id,por_nombre');
			$this->db->where('por_tipo','principal');
			$this->db->from($this->por_table);
			$cover = $this->db->get()->row();
			$cover->por_estado = 'creado';
			$covers[] = $cover;
			$limit = 9;
		} else {
			$offset -= 1;
		}
		//obtiene las potadas de todas las categorias
		$this->db->select('cat_id,cat_nombre');
		$this->db->order_by('cat_id','DESC');
		$this->db->limit($limit,$offset);
		$this->db->from('categorias_revista');
		$categories = $this->db->get()->result();
		foreach($categories as $category){
			$this->db->select('por_id,por_nombre');
			$this->db->where('cat_id',$category->cat_id);
			$this->db->from($this->por_table);
			$query = $this->db->get();
			if($query->num_rows() > 0){
				$cover = $query->row();
				$cover->por_estado = 'creado';
				$covers[] = $cover;
			} else {
				$category->por_estado = 'no_creado';
				$covers[] = $category;
			}
		}
		return $covers;
	}

	function get_total_covers(){
		$response = 0;
		$this->db->select('por_id');
		$this->db->where('por_tipo','principal');
		$this->db->from($this->por_table);
		$query = $this->db->get();
		$response += $query->num_rows();
		//count all categories
		$this->db->select('cat_id');
		$this->db->from('categorias_revista');
		$query = $this->db->get();
		$response += $query->num_rows();
		return $response;
	}

	function create_category_cover(){
		$response = new stdClass();
		if($this->cat_id == NULL || $this->por_nombre == NULL || $this->por_tipo == NULL){
			$response->succeed = FALSE;
			return $response;
		}
		$this->db->set('cat_id',$this->cat_id);
		$this->db->set('por_nombre',$this->por_nombre);
		$this->db->set('por_datos',$this->por_datos);
		$this->db->set('por_tipo',$this->por_tipo);
		$this->db->insert($this->por_table);
		$this->por_id = $this->db->insert_id();
		if($this->db->affected_rows() > 0){
			$response->succeed = TRUE;
			$response->por_id = $this->por_id;
		} else {
			$response->succeed = FALSE;
		}
		return $response;
	}

	function save_cover(){
		if($this->por_id == NULL){
			return FALSE;
		}
		$data = array(
			'por_datos' => $this->por_datos
		);
		$this->db->where('por_id',$this->por_id);
		if($this->db->update($this->por_table,$data)){
			return TRUE;
		} else {
			return FALSE;
		}
	}

}

/* End of file cover_model.php */
/* Location: ./application/models/cover_model.php */