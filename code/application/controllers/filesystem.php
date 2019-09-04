<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Filesystem extends CI_Controller {

	var $user_session;
	var $response;

	function __construct(){
		parent::__construct();
		$this->user_session = $this->session->userdata('admin_id');
		$this->response = new stdClass();
	}

	public function index(){
		redirect();
	}

	public function list_directories($path='uploads'){
		//verifica si la solicitud se produce mediante xhr, de no ser asi redirige al usuario al inicio
		if($this->input->is_ajax_request() == FALSE){
			redirect('/admin');
			return;
		}
		//verifica si existe una sesion activa para guardar la informacion en la base de datos
		if($this->user_session == FALSE){
			$this->response->succeed = FALSE;
			$this->response->code = 401; //no autorizado
			$this->response->status = 'La sesión ha expirado';
			$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
			return;
		}
		//replace paremeter string to url type
		$path = str_replace('-', '/', $path);
		//list directories inside uploads folder
		$this->response->files = array();
		$dir = opendir('./'.$path);
		while($file = readdir($dir)){
			if($file != '.' && $file != '..'){
				if(is_dir('./'.$path.'/'.$file)){
					$s_dir = str_replace('/', '-', $path);
					$this->response->files[] = array('file' => $file, 'dir' => $s_dir);
				}
			}
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
		return;
	}

	public function create_directory(){
		//verifica si la solicitud se produce mediante xhr, de no ser asi redirige al usuario al inicio
		if($this->input->is_ajax_request() == FALSE){
			redirect('/admin');
			return;
		}
		//verifica si existe una sesion activa para guardar la informacion en la base de datos
		if($this->user_session == FALSE){
			$this->response->succeed = FALSE;
			$this->response->code = 401; //no autorizado
			$this->response->status = 'La sesión ha expirado';
			$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
			return;
		}
		$path = str_replace('-','/',$this->input->post('folder_route'));
		$name = $this->input->post('folder_name');
		$new_folder = './'.$path.'/'.$name;
		if(mkdir($new_folder,0777)){
			$this->response->succeed = TRUE;
			$this->response->code = 200;
			$this->response->status = 'La carpeta se ha creado correctamente.';
		} else{
			$this->response->succeed = FALSE;
			$this->response->code = 500;
			$this->response->status = 'Ha ocurrido un error al crear la carpeta.';
		}
		
		$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
		return;
	}

	public function list_files($path){
		//verifica si la solicitud se produce mediante xhr, de no ser asi redirige al usuario al inicio
		if($this->input->is_ajax_request() == FALSE){
			redirect('/admin');
			return;
		}
		//verifica si existe una sesion activa para guardar la informacion en la base de datos
		if($this->user_session == FALSE){
			$this->response->succeed = FALSE;
			$this->response->code = 401; //no autorizado
			$this->response->status = 'La sesión ha expirado';
			$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
			return;
		}
		//replace paremeter string to url type
		$path = str_replace('-', '/', $path);
		//list directories inside uploads folder
		$this->response->files = array();
		$dir = opendir('./'.$path);
		while($file = readdir($dir)){
			if($file != '.' && $file != '..'){
				if(!is_dir('./'.$path.'/'.$file)){
					$type = $this->get_file_type($file);
					if($type != 'unknown'){
						$this->response->files[] = array('file_name'=> $file, 'file' => base_url().$path.'/'.$file, 'type' => $type);	
					}
				}
			}
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
		return;
	}

	public function upload(){
		//verifica si existe una sesion activa para guardar la informacion en la base de datos
		if($this->user_session == FALSE){
			$this->response->succeed = FALSE;
			$this->response->code = 401; //no autorizado
			$this->response->status = 'La sesión ha expirado';
			$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
			return;
		}
		//verifica si existe una peticion para subir un archivo
		if(!isset($_REQUEST['file'])){
			$this->response->succeed = FALSE;
			$this->response->code = 400; //no autorizado
			$this->response->status = 'La solicitud no es correcta.';
			$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
			return;	
		}
		//read de request data
		$request_num = $_REQUEST['reqnum'];
		$path = str_replace('-', '/', $_REQUEST['path']);
		$file = $_REQUEST['file'];
		$file_data = $this->get_file_data($_REQUEST['name']);
		$name = $file_data[0];
		$extension = '.'.$file_data[1];
		//create temp file to store base64 string
		if(file_exists('./'.$path.'/'.$name.$extension)){
			$i=0;
			while(file_exists('./'.$path.'/'.$name.'-'.$i.$extension)) $i++;
			$input64 = fopen('./'.$path.'/'.$name.'-'.$i.$extension.'.tmp','wb');
			fwrite($input64, $file);
			fclose($input64);
			$this->base64_to_file(file_get_contents('./'.$path.'/'.$name.'-'.$i.$extension.'.tmp'),'./'.$path.'/'.$name.'-'.$i.$extension);
			unlink('./'.$path.'/'.$name.'-'.$i.$extension.'.tmp');
			if(file_exists('./'.$path.'/'.$name.'-'.$i.$extension)){
				$this->response->succeed = TRUE;
				$this->response->reqnum = $request_num;
				$this->response->name = $name.'-'.$i.$extension;
				$this->response->type = $this->get_file_type($this->response->name);
				$this->response->fullpath = base_url().$path.'/'.$this->response->name;
			} else{
				$this->response->succeed = FALSE;
				$this->response->status = "No se ha podido subir el archivo ".$name.$extension;
			}
		} else{
			$input64 = fopen('./'.$path.'/'.$name.$extension.'.tmp','wb');
			fwrite($input64, $file);
			fclose($input64);
			$this->base64_to_file(file_get_contents('./'.$path.'/'.$name.$extension.'.tmp'),'./'.$path.'/'.$name.$extension);
			unlink('./'.$path.'/'.$name.$extension.'.tmp');
			if(file_exists('./'.$path.'/'.$name.$extension)){
				$this->response->succeed = TRUE;
				$this->response->reqnum = $request_num;
				$this->response->name = $name.$extension;
				$this->response->type = $this->get_file_type($this->response->name);
				$this->response->fullpath = base_url().$path.'/'.$this->response->name;
			} else{
				$this->response->succeed = FALSE;
				$this->response->status = $this->response->status = "No se ha podido subir el archivo ".$name.$extension;
			}
		}
		//send response to client
		$this->output->set_content_type('application/json')->set_output(json_encode($this->response));
		return;
	}

	private function get_file_data($file_name){
		$data = explode('.',$file_name);
		return $data;
	}

	private function base64_to_file($base64_string, $output_file){
		$output_stream = fopen($output_file,'wb');
		$data = explode(',',$base64_string);
		fwrite($output_stream, base64_decode($data[1]));
		fclose($output_stream);
		return $output_file;
	}

	private function get_file_type($file){
		$data = explode('.', $file);
		$ext = $data[sizeof($data)-1];
		//TODO: add more mime types
		if(in_array($ext, array('jpg','jpeg','png','bmp','gif','webp'))){
			return 'image';
		} elseif(in_array($ext, array('pdf'))){
			return 'pdf';
		} else{
			return 'unknown';
		}
	}

}

/* End of file filesystem.php */
/* Location: ./application/controllers/filesystem.php */