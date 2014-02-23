<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Article_model extends CI_Model {

	var $art_table = 'articulos_revista';
	var $art_id;
	var $adm_id;
	var $art_titulo;
	var $art_url;
	var $art_portada;
	var $art_abstracto;
	var $art_contenido;
	var $art_etiquetas;
	var $art_estado;
	var $art_fecha;
	var $art_pdf;
	var $art_leido;
	var $art_autores;
	var $art_categorias;

	/*--------------FRONTEND FUNCTIONS--------------*/

	function get_popular_content($limit){
		$this->db->select('art_titulo,art_url,art_portada,art_fecha');
		$this->db->where('art_estado','publicado');
		$this->db->order_by('art_leido','DESC');
		$this->db->limit($limit);
		$this->db->from($this->art_table);
		$query = $this->db->get();
		return $query->result();
	}

	function get_related_content($cat_id,$art_id,$limit){
		$this->db->select('articulos_revista.art_id,articulos_revista.art_titulo,articulos_revista.art_url,articulos_revista.art_portada,articulos_revista.art_fecha');
		$this->db->join('categorias_articulo','categorias_articulo.art_id = articulos_revista.art_id');
		$this->db->join('categorias_revista','categorias_revista.cat_id = categorias_articulo.cat_id');
		$this->db->where('articulos_revista.art_id !=',$art_id);
		$this->db->where('categorias_revista.cat_id',$cat_id);
		$this->db->where('articulos_revista.art_estado','publicado');
		$this->db->order_by('articulos_revista.art_leido','DESC');
		$this->db->limit($limit);
		$this->db->from($this->art_table);
		$query = $this->db->get();
		return $query->result();
	}

	function get_front_content($limit,$offset,$cat_id){
		$this->db->select('articulos_revista.art_id,art_titulo,art_url,art_portada,art_abstracto,art_fecha,art_estado');
		if($cat_id != FALSE || $cat_id != NULL){
			$this->db->join('categorias_articulo','categorias_articulo.art_id = articulos_revista.art_id');
			$this->db->where('categorias_articulo.cat_id',$cat_id);
		}
		$this->db->where('art_estado','publicado');
		$this->db->order_by('art_fecha','DESC');
		$this->db->limit($limit,$offset);
		$this->db->from($this->art_table);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$articles = $query->result();
			foreach ($articles as &$article){
				$article->art_autores = $this->get_article_authors_array($article->art_id);
				$article->art_categorias = $this->get_article_categories_array($article->art_id);
			}
			return $articles;
		} else {
			return NULL;
		}		
	}

	function get_article_url(){
		if($this->art_url == NULL){
			return FALSE;
		}
		$this->db->select('*');
		$this->db->where('art_url',$this->art_url);
		$this->db->from($this->art_table);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$article = $query->row();
			$article->art_autores = $this->get_article_authors_array($article->art_id);
			$article->art_categorias = $this->get_article_categories_array($article->art_id);
			return $article;
		} else {
			return FALSE;
		}
	}

	function increment_visited_counter(){
		if($this->art_id == NULL || $this->art_leido == NULL){
			return FALSE;
		}
		$data = Array(
			'art_leido' => (intval($this->art_leido)+1)
		);
		$this->db->where('art_id',$this->art_id);
		$this->db->update($this->art_table,$data);
		if($this->db->affected_rows() > 0){
			return TRUE;
		} else {
			return FALSE;
		}
	}

	/*--------------BACKEND FUNCTIONS--------------*/

	function get_total_articles($status,$search,$admin_acc){
		if((is_array($admin_acc) && sizeof($admin_acc) == 0) || $admin_acc == NULL){
			return 0;
		}
		$this->db->select('articulos_revista.art_id');
		$this->db->join('categorias_articulo','categorias_articulo.art_id = articulos_revista.art_id','left');
		if($admin_acc != 'super'){
			if(is_array($admin_acc) && sizeof($admin_acc) > 0){
				$this->db->where_in('categorias_articulo.cat_id',$admin_acc);
			}
		}
		$this->db->where('art_estado',$status);
		if($search != NULL){
			$this->db->like('art_titulo',$search);
		}
		$this->db->group_by('articulos_revista.art_id');
		$this->db->from($this->art_table);
		$query = $this->db->get();
		return $query->num_rows();
	}

	function get_articles_at($limit,$offset,$status,$order,$search,$admin_acc){
		if((is_array($admin_acc) && sizeof($admin_acc) == 0) || $admin_acc == NULL){
			return NULL;
		}
		$this->db->select('articulos_revista.art_id,articulos_revista.art_titulo,articulos_revista.art_fecha,usuarios_administracion.adm_nombre');
		$this->db->join('usuarios_administracion','usuarios_administracion.adm_id = articulos_revista.adm_id');
		$this->db->join('categorias_articulo','categorias_articulo.art_id = articulos_revista.art_id','left');
		if($admin_acc != 'super'){
			if(is_array($admin_acc) && sizeof($admin_acc) > 0){
				$this->db->where_in('categorias_articulo.cat_id',$admin_acc);
			}
		}
		$this->db->where('art_estado',$status);
		if($search != NULL){
			$this->db->like('art_titulo',$search);
		}
		$this->db->group_by('articulos_revista.art_id');
		$this->db->limit($limit,$offset);
		switch ($order){
			case 'fecha':
				$this->db->order_by('art_fecha','DESC');
				$this->db->order_by('art_id','DESC');
				break;
			case 'titulo':
				$this->db->order_by('art_titulo','ASC');
				break;
			default:
				$this->db->order_by('art_id','DESC');
				break;
		}
		$this->db->from($this->art_table);
		$articles = $this->db->get()->result();
		if(sizeof($articles) > 0){
			foreach($articles as &$article){
				$article->art_autores = $this->get_article_authors($article->art_id);
				$article->art_categorias = $this->get_article_categories($article->art_id);
			}
		}
		return $articles;
	}

	function get_article(){
		if($this->art_id == NULL){
			return FALSE;		
		}
		$this->db->select('*');
		$this->db->where('art_id',$this->art_id);
		$this->db->from($this->art_table);
		$query = $this->db->get();
		if($query->num_rows() > 0){
			$article = $query->row();
			$article->art_autores = $this->get_article_authors_array($this->art_id);
			$article->art_categorias = $this->get_article_categories_array($this->art_id);
			return $article;
		} else {
			return FALSE;
		}
	}

	private function get_article_authors_array($article_id){
		$this->db->select('usuarios_revista.usr_id,usuarios_revista.usr_nombre,usuarios_revista.usr_imagen');
		$this->db->join('autores_articulo','autores_articulo.usr_id = usuarios_revista.usr_id');
		$this->db->join('articulos_revista','autores_articulo.art_id = articulos_revista.art_id');
		$this->db->where('articulos_revista.art_id',$article_id);
		$this->db->order_by('autores_articulo.usr_id','ASC');
		$this->db->from('usuarios_revista');
		$users = $this->db->get()->result();
		return $users;
	}

	private function get_article_authors($article_id){
		$this->db->select('usuarios_revista.usr_nombre');
		$this->db->join('autores_articulo','autores_articulo.usr_id = usuarios_revista.usr_id');
		$this->db->join('articulos_revista','autores_articulo.art_id = articulos_revista.art_id');
		$this->db->where('articulos_revista.art_id',$article_id);
		$this->db->order_by('autores_articulo.usr_id','ASC');
		$this->db->from('usuarios_revista');
		$query = $this->db->get()->result();
		$users = '';
		if(sizeof($query) > 0){
			foreach($query as $user){
				$users .= $user->usr_nombre.', ';
			}
			$users = substr($users,0,-2);
		}
		return $users;
	}


	private function get_article_categories_array($article_id){
		$this->db->select('categorias_revista.cat_id,categorias_revista.cat_super_id,categorias_revista.cat_nombre,categorias_revista.cat_url,categorias_revista.cat_color');
		$this->db->join('categorias_articulo','categorias_articulo.cat_id = categorias_revista.cat_id');
		$this->db->join('articulos_revista','categorias_articulo.art_id = articulos_revista.art_id');
		$this->db->where('articulos_revista.art_id',$article_id);
		$this->db->order_by('categorias_articulo.cat_id','ASC');
		$this->db->from('categorias_revista');
		$categories = $this->db->get()->result();
		return $categories;
	}

	private function get_article_categories($article_id){
		$this->db->select('categorias_revista.cat_nombre');
		$this->db->join('categorias_articulo','categorias_articulo.cat_id = categorias_revista.cat_id');
		$this->db->join('articulos_revista','categorias_articulo.art_id = articulos_revista.art_id');
		$this->db->where('articulos_revista.art_id',$article_id);
		$this->db->order_by('categorias_articulo.cat_id','ASC');
		$this->db->from('categorias_revista');
		$query = $this->db->get()->result();
		$categories = '';
		if(sizeof($query) > 0){
			foreach($query as $category){
				$categories .= $category->cat_nombre.', ';
			}
			$categories = substr($categories,0,-2);
		}
		return $categories;
	}

	function create_article(){
		if($this->adm_id == NULL || $this->art_titulo == NULL || $this->art_url == NULL || $this->art_portada == NULL || 
			$this->art_abstracto == NULL || $this->art_contenido == NULL || $this->art_estado == NULL || 
			$this->art_fecha == NULL){
			return FALSE;
		}
		if($this->art_estado == 'publicado' && ($this->art_autores == NULL || $this->art_categorias == NULL)){
			return FALSE;
		}
		//comienza la transaccion
		$this->db->trans_begin();
		//inserta los datos del articulo
		$this->db->set('adm_id',$this->adm_id);
		$this->db->set('art_titulo',$this->art_titulo);
		$this->db->set('art_url',$this->art_url);
		$this->db->set('art_portada',$this->art_portada);
		$this->db->set('art_abstracto',preg_replace("/[\\n\\r]+/", " ", strip_tags($this->art_abstracto)));
		$this->db->set('art_contenido',htmlspecialchars($this->art_contenido));
		$this->db->set('art_etiquetas',$this->art_etiquetas);
		$this->db->set('art_estado',$this->art_estado);
		$this->db->set('art_fecha',$this->art_fecha);
		$this->db->set('art_pdf',$this->art_pdf);
		$this->db->insert($this->art_table);
		$this->art_id = $this->db->insert_id();
		//inserta las relaciones con los autores del articulo
		if($this->art_autores != NULL){
			foreach($this->art_autores as $autor){
				$this->db->set('usr_id',$autor);
				$this->db->set('art_id',$this->art_id);
				$this->db->insert('autores_articulo');
			}	
		}
		//inserta las relaciones con las categorias del articulo
		if($this->art_categorias != NULL){
			foreach($this->art_categorias as $categoria){
				$this->db->set('cat_id',$categoria);
				$this->db->set('art_id',$this->art_id);
				$this->db->insert('categorias_articulo');
			}
		}
		//verifica el estado de la transaccion
		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return FALSE;
		} else{
			$this->db->trans_commit();
			return TRUE;
		}
	}

	function save_article(){
		if($this->adm_id == NULL || $this->art_id == NULL || $this->art_titulo == NULL || $this->art_url == NULL || 
			$this->art_portada == NULL || $this->art_abstracto == NULL || $this->art_contenido == NULL || $this->art_estado == NULL || 
			$this->art_fecha == NULL){
			return FALSE;
		}
		if($this->art_estado == 'publicado' && ($this->art_autores == NULL || $this->art_categorias == NULL)){
			return FALSE;
		}
		$this->db->trans_begin();
		$data = array(
			'adm_id' => $this->adm_id,
			'art_titulo' => $this->art_titulo,
			'art_url' => $this->art_url,
			'art_portada' => $this->art_portada,
			'art_abstracto' => preg_replace("/[\\n\\r]+/", " ", strip_tags($this->art_abstracto)),
			'art_contenido' => htmlspecialchars($this->art_contenido),
			'art_etiquetas' => $this->art_etiquetas,
			'art_pdf' => $this->art_pdf,
			'art_estado' => $this->art_estado,
			'art_fecha' => $this->art_fecha,
		);
		$this->db->where('art_id',$this->art_id);
		$this->db->update($this->art_table,$data);
		//inserta los nuevos autores
		if($this->art_autores != NULL){
			//busca los usuarios que actualmente estan relacionados al articulo
			$this->db->select('usr_id');
			$this->db->where('art_id',$this->art_id);
			$this->db->from('autores_articulo');
			$query_usr = $this->db->get();
			if($query_usr->num_rows() > 0){
				//convierte el resultado de la consulta en un arreglo
				$origin_usr = Array();
				foreach ($query_usr->result() as $usr) {
					$origin_usr[] = $usr->usr_id;
				}
				//elimina los usuarios que no coinciden
				foreach ($origin_usr as $usr) {
					if(!in_array($usr, $this->art_autores)){
						$this->db->where('usr_id',$usr);
						$this->db->where('art_id',$this->art_id);
						$this->db->delete('autores_articulo');
					}
				}
				//agrega los nuevos usuarios
				foreach ($this->art_autores as $usr) {
					if(!in_array($usr, $origin_usr)){
						$this->db->set('usr_id',$usr);
						$this->db->set('art_id',$this->art_id);
						$this->db->insert('autores_articulo');
					}
				}
			} else {
				foreach ($this->art_autores as $usr) {
					$this->db->set('usr_id',$usr);
					$this->db->set('art_id',$this->art_id);
					$this->db->insert('autores_articulo');
				}
			}
		}
		//inserta las nuevas categorias
		if($this->art_categorias != NULL){
			//busca las categorias que actualmente tiene el articulo
			$this->db->select('cat_id');
			$this->db->where('art_id',$this->art_id);
			$this->db->from('categorias_articulo');
			$query_cat = $this->db->get();
			if($query_cat->num_rows() > 0){
				//convierte el resultado de la consulta en un arreglo
				$origin_cat = Array();
				foreach ($query_cat->result() as $cat) {
					$origin_cat[] = $cat->cat_id;
				}
				//elimina las categorias que no coinciden
				foreach ($origin_cat as $cat) {
					if(!in_array($cat, $this->art_categorias)){
						$this->db->where('cat_id',$cat);
						$this->db->where('art_id',$this->art_id);
						$this->db->delete('categorias_articulo');
					}
				}
				//agrega las nuevas categorias
				foreach ($this->art_categorias as $cat) {
					if(!in_array($cat, $origin_cat)){
						$this->db->set('cat_id',$cat);
						$this->db->set('art_id',$this->art_id);
						$this->db->insert('categorias_articulo');
					}
				}
			} else {
				foreach ($this->art_categorias as $cat) {
					$this->db->set('cat_id',$cat);
					$this->db->set('art_id',$this->art_id);
					$this->db->insert('categorias_articulo');
				}
			}
		}
		//verifica el estado de la transaccion
		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return FALSE;
		} else{
			$this->db->trans_commit();
			return TRUE;
		}
	}

	function delete_article(){
		if($this->art_id == NULL){
			return FALSE;
		}
		//elimina el articulo de la bd, la relaciones se eliminan automaticamente
		$this->db->where('art_id',$this->art_id);
		$this->db->delete($this->art_table);
		//respuesta de la consulta generada
		if($this->db->affected_rows() > 0){
			return TRUE;
		} else{
			return FALSE;
		}
	}

	function search_articles($search_string){
		$this->db->select('art_id,art_titulo,art_url,art_portada,art_abstracto,art_fecha,art_estado');
		$this->db->where('art_estado','publicado');
		$this->db->like('art_titulo',$search_string);
		$this->db->from($this->art_table);
		$articles = $this->db->get()->result();
		if(sizeof($articles) > 0){
			foreach($articles as &$article){
				$article->art_url = base_url().index_page().'/articulo/'.$article->art_url;
				$article->art_autores = $this->get_article_authors($article->art_id);
				$article->art_categorias = $this->get_article_categories_for_search($article->art_id);
			}
		}
		return $articles;
	}

	private function get_article_categories_for_search($article_id){
		$this->db->select('categorias_revista.cat_id,categorias_revista.cat_super_id,categorias_revista.cat_nombre,categorias_revista.cat_url,categorias_revista.cat_color');
		$this->db->join('categorias_articulo','categorias_articulo.cat_id = categorias_revista.cat_id');
		$this->db->join('articulos_revista','categorias_articulo.art_id = articulos_revista.art_id');
		$this->db->where('articulos_revista.art_id',$article_id);
		$this->db->order_by('categorias_articulo.cat_id','ASC');
		$this->db->from('categorias_revista');
		$categories = $this->db->get()->result();
		if(sizeof($categories) > 0){
			foreach($categories as &$category){
				$category->cat_url = base_url().index_page().'/categoria/'.$category->cat_url;
			}
		}
		return $categories;
	}
}

/* End of file article_model.php */
/* Location: ./application/models/article_model.php */