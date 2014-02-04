<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}

	public function index(){

		$this->load->model('category_model');
		$data['categories'] = $this->category_model->get_main_categories();
		$data['title'] = 'Revista del Instituto Tecnológico de Morelia';
		$data['active'] = 'home';

		$this->load->view('front/common/header',$data);
		$this->load->view('front/common/leftbar');
		$this->load->view('front/common/footer');
	}

}

/* End of file home.php */
/* Location: ./application/controllers/home.php */