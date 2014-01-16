<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class System extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('system_model');
	}

	public function index(){
		if($this->system_model->db_enabled() == TRUE){
			redirect('/admin');
			return;
		}
		$data['title'] = 'Instalación del Sistema';
		$this->load->view('system/installation',$data);
		return;
	}

	public function install_system(){
		if($this->system_model->db_enabled() == TRUE || $this->input->is_ajax_request() == FALSE){
			redirect('/admin');
			return;
		}
		$response = new stdClass();
		$this->system_model->adm_nombre = $this->input->post('adm_nombre');
		$this->system_model->adm_email = $this->input->post('adm_email');
		$this->system_model->adm_password = sha1($this->input->post('adm_password'));
		$response->succeed = $this->system_model->install_system();
		$this->output->set_content_type('application/json')->set_output(json_encode($response));
		return;
	}

}

/* End of file system.php */
/* Location: ./application/controllers/system.php */