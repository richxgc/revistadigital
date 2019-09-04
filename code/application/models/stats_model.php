<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stats_model extends CI_Model {

	/* ARTICULOS */

	function get_category_articles(){
		$this->db->select('cat_id,cat_nombre,cat_color');
		$this->db->order_by('cat_id','DESC');
		$this->db->from('categorias_revista');
		$query = $this->db->get();
		$categories = NULL;
		if($query->num_rows > 0){
			$categories = $query->result();
			foreach($categories as &$category){
				$this->db->select('articulos_revista.art_id,articulos_revista.art_estado,categorias_articulo.cat_id');
				$this->db->join('categorias_articulo','categorias_articulo.art_id = articulos_revista.art_id');
				$this->db->where('categorias_articulo.cat_id',$category->cat_id);
				$this->db->where('art_estado','publicado');
				$this->db->from('articulos_revista');
				$query = $this->db->get();
				$category->cat_articulos = $query->num_rows();
			}
		}
		return $categories;
	}

	function get_most_read_articles(){
		$this->db->select('art_id,art_titulo,art_estado,art_leido');
		$this->db->where('art_estado','publicado');
		$this->db->order_by('art_leido','DESC');
		$this->db->limit(5);
		$this->db->from('articulos_revista');
		$query = $this->db->get();
		$articles = NULL;
		if($query->num_rows() > 0){
			$articles = $query->result();
		}
		return $articles;
	}

	function get_monthly_publications(){
		//selecciona todas las categorias que tienen articulos publicados
		$this->db->select('categorias_revista.cat_id,categorias_revista.cat_nombre,COUNT(articulos_revista.art_id) as total');
		$this->db->join('categorias_articulo','categorias_articulo.cat_id = categorias_revista.cat_id');
		$this->db->join('articulos_revista','articulos_revista.art_id = categorias_articulo.art_id','left');
		$this->db->group_by('categorias_revista.cat_id');
		$this->db->order_by('categorias_revista.cat_id','DESC');
		$this->db->from('categorias_revista');
		$query = $this->db->get();
		$articles = new stdClass();
		if($query->num_rows() > 0){
			$articles->header = $query->result();
			$articles->months = array();
			for($i=-2; $i <= 0; $i++){
				$month = get_month_dates($i);
				$tmp_month = array();
				$tmp_month[] = substr($month[0],0,7);
				foreach($articles->header as $category){
					//por cada categoria hace el conteo del total de articulos publicados en x mes
					$this->db->select('COUNT(articulos_revista.art_id) as total');
					$this->db->join('categorias_articulo','categorias_articulo.art_id = articulos_revista.art_id');
					$this->db->where('categorias_articulo.cat_id',$category->cat_id);
					$this->db->where('articulos_revista.art_fecha >=',$month[0]);
					$this->db->where('articulos_revista.art_fecha <=',$month[1]);
					$this->db->from('articulos_revista');
					$tmp_month[] = intval($this->db->get()->row()->total);
				}
				$average = 0; $index = 0;
				foreach($tmp_month as $subtotal => $value){
					if($index != 0){
						$average += $value;	
					}
					$index++;
				}
				$tmp_month[] = ($average / (sizeof($tmp_month)-1));
				$articles->months[] = $tmp_month;
			}
		}
		return $articles;
	}

	/* USUARIOS */

	function get_users_stats(){
		$count = new stdClass();
		$count->sessions = $this->db->count_all_results('ci_sessions'); 
		$count->users = $this->db->count_all_results('usuarios_revista');
		return $count;
	}

	function get_curriculum_count(){
		$count = new stdClass();
		$count->no = 0; $count->yes = 0;
		$this->db->select('usuarios_revista.usr_id, curriculum_usuarios.cur_id');
		$this->db->join('curriculum_usuarios','curriculum_usuarios.usr_id = usuarios_revista.usr_id','left');
		$this->db->from('usuarios_revista');
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$users = $query->result();
			foreach ($users as $user) {
				if($user->cur_id != NULL){
					$count->yes++;
				} else {
					$count->no++;
				}
			}
		}
		return $count;
	}
}

/* End of file stats_model.php */
/* Location: ./application/models/stats_model.php */