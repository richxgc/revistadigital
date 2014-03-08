<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stats_model extends CI_Model {

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