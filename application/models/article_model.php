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
	var $art_autores;
	var $art_categorias;


	function get_articles_at($limit,$offset,$status){
		$this->db->select('art_id,art_titulo,art_fecha,adm_nombre');
		$this->db->join('usuarios_administracion','usuarios_administracion.adm_id = articulos_revista.adm_id');
		$this->db->where('art_estado',$status);
		$this->db->limit($limit,$offset);
		$this->db->order_by('art_fecha','DESC');
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
			$this->art_fecha == NULL || $this->art_autores == NULL || $this->art_categorias == NULL){
			return FALSE;
		}
		//comienza la transaccion
		$this->db->trans_begin();
		//inserta los datos del articulo
		$this->db->set('adm_id',$this->adm_id);
		$this->db->set('art_titulo',$this->art_titulo);
		$this->db->set('art_url',$this->art_url);
		$this->db->set('art_portada',$this->art_portada);
		$this->db->set('art_abstracto',$this->art_abstracto);
		$this->db->set('art_contenido',$this->art_contenido);
		$this->db->set('art_etiquetas',$this->art_etiquetas);
		$this->db->set('art_estado',$this->art_estado);
		$this->db->set('art_fecha',$this->art_fecha);
		$this->db->set('art_pdf',$this->art_pdf);
		$this->db->insert($this->art_table);
		$this->art_id = $this->db->insert_id();
		//inserta las relaciones con los autores del articulo
		foreach($this->art_autores as $autor){
			$this->db->set('usr_id',$autor);
			$this->db->set('art_id',$this->art_id);
			$this->db->insert('autores_articulo');
		}
		//inserta las relaciones con las categorias del articulo
		foreach($this->art_categorias as $categoria){
			$this->db->set('cat_id',$categoria);
			$this->db->set('art_id',$this->art_id);
			$this->db->insert('categorias_articulo');
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

}

/* End of file article_model.php */
/* Location: ./application/models/article_model.php */