<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category_model extends CI_Model {

	var $cat_table = 'categorias_revista';
	var $cat_id;
	var $cat_super_id;
	var $cat_nombre;
	var $cat_url;
	var $cat_color;

	/*--------------FRONTEND FUNCTIONS--------------*/

	function get_main_categories(){
		$this->db->select('*');
		$this->db->where('cat_super_id',NULL);
		$this->db->order_by('cat_id','ASC');
		$this->db->from($this->cat_table);
		$query = $this->db->get();
		return $query->result();
	}


	/*--------------BACKEND FUNCTIONS--------------*/

	function get_categories(){
		$this->db->select('*');
		$this->db->order_by('cat_id','DESC');
		$this->db->from($this->cat_table);
		$query = $this->db->get();
		return $query->result();
	}

	function get_categories_at($limit,$offset){
		$this->db->select('*');
		$this->db->order_by('cat_id','DESC');
		$this->db->limit($limit,$offset);
		$this->db->from($this->cat_table);
		$categories = $this->db->get()->result();
		if(sizeof($categories) > 0){
			foreach($categories as &$category){
				$category->cat_articulos = $this->get_artciles_count($category->cat_id);
				if($category->cat_super_id != NULL && $category->cat_super_id != ''){
					$category->cat_super = $this->get_super_name($category->cat_super_id);
				} else{
					$category->cat_super = 'Ninguno';
				}
			}
		}
		return $categories;
	}

	function get_total_categories(){
		return $this->db->count_all($this->cat_table);
	}

	function get_category(){
		if($this->cat_id == NULL){
			return array();
		}
		$this->db->select('*');
		$this->db->where('cat_id',$this->cat_id);
		$this->db->from($this->cat_table);
		$query = $this->db->get();
		return $query->row();
	}

	private function get_super_name($category_id){
		$this->db->select('cat_nombre');
		$this->db->where('cat_id',$category_id);
		$this->db->from($this->cat_table);
		$query = $this->db->get()->row();
		return $query->cat_nombre;
	}

	private function get_artciles_count($category_id){
		$this->db->select('*');
		$this->db->where('cat_id',$category_id);
		$this->db->from('categorias_articulo');
		$query = $this->db->get();
		return $query->num_rows();
	}

	function create_category(){
		if($this->cat_nombre == NULL || $this->cat_url == NULL || $this->cat_color == NULL){
			return FALSE;
		}
		$this->db->set('cat_super_id',$this->cat_super_id);
		$this->db->set('cat_nombre',$this->cat_nombre);
		$this->db->set('cat_url',$this->cat_url);
		$this->db->set('cat_color',$this->cat_color);
		$this->db->insert($this->cat_table);
		if($this->db->affected_rows() > 0){
			return TRUE;
		} else{
			return FALSE;
		}
	}

	function save_category(){
		if($this->cat_id == NULL || $this->cat_nombre == NULL || $this->cat_url == NULL || $this->cat_color == NULL){
			return FALSE;
		}
		$data = array(
			'cat_super_id' => $this->cat_super_id,
			'cat_nombre' => $this->cat_nombre,
			'cat_url' => $this->cat_url,
			'cat_color' => $this->cat_color
		);
		$this->db->where('cat_id',$this->cat_id);
		$this->db->update($this->cat_table,$data);
		if($this->db->affected_rows() > 0){
			return TRUE;
		} else{
			return FALSE;
		}
	}

	function delete_category(){
		if($this->cat_id == NULL){
			return FALSE;
		}
		$this->db->where('cat_id',$this->cat_id);
		$this->db->delete($this->cat_table);
		if($this->db->affected_rows() > 0){
			return TRUE;
		} else{
			return FALSE;
		}
	}
}

/* End of file category_model.php */
/* Location: ./application/models/category_model.php */