<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Article_model extends CI_Model {

	var $art_table = 'articulos_revista';
	var $art_id;
	var $adm_id;
	var $art_titulo;
	var $art_url;
	var $art_abstracto;
	var $art_contenido;
	var $art_etiquetas;
	var $art_pdf;
	var $art_estado;
	var $art_fecha;

	function get_articles_at($limit,$offset,$status){
		$this->db->select('*');
		$this->db->join('usuarios_administracion','usuarios_administracion.adm_id = articulos_revista.adm_id');
		$this->db->where('art_estado',$status);
		$this->db->limit($limit,$offset);
		$this->db->order_by('art_fecha','DESC');
		$this->db->from($this->art_table);
		$articles = $this->db->get()->result();
		if(sizeof($articles) > 0){
			foreach($articles as &$article){
				$article->art_autores = $this->get_article_authors($article->art_id);
			}
		}
		return $articles;
	}

	private function get_article_authors($article_id){
		$this->db->select('usr_id,usr_nombre,usr_paterno,usr_materno');
		$this->db->join('autores_articulo','autores_articulo.usr_id = usuarios_revista.usr_id');
		$this->db->join('articulos_revista','autores_articulo.art_id = articulos_revista.art_id');
		$this->db->where('articulos_revista.art_id',$article_id);
		$this->db->order_by('autores_articulo.usr_id','ASC');
		$this->db->from('usuarios_revista');
		$users = $this->db->get();
		return $users->result();
	}

}

/* End of file article_model.php */
/* Location: ./application/models/article_model.php */