<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Filesystem extends CI_Controller {

	public function index(){
		redirect();
	}

	public function upload(){
		//create response object
		$response = new stdClass();
		//read de request data
		$request = $_REQUEST;
		$file = $_REQUEST['file'];
		$file_data = $this->get_file_data($_REQUEST['name']);
		$name = $file_data[0];
		$extension = '.'.$file_data[1];
		//create temp file to store base64 string
		if(file_exists('./uploads/'.$name.$extension)){
			$i=0;
			while(file_exists('./uploads/'.$name.'-'.$i.$extension)) $i++;
			$input64 = fopen('./uploads/'.$name.'-'.$i.$extension.'.tmp','wb');
			fwrite($input64, $file);
			$this->base64_to_file(file_get_contents('./uploads/'.$name.'-'.$i.$extension.'.tmp'),'./uploads/'.$name.'-'.$i.$extension);
			//unlink('./uploads/'.$name.'-'.$i.$extension.'.tmp'); //descomentar en el servidor de producción
			if(file_exists('./uploads/'.$name.'-'.$i.$extension)){
				$response->succeed = TRUE;
				$response->name = $name.'-'.$i.$extension;
			} else{
				$response->succeed = FALSE;
				$response->status = "No se ha podido subir el archivo ".$name.$extension;
			}
		} else{
			$input64 = fopen('./uploads/'.$name.$extension.'.tmp','wb');
			fwrite($input64, $file);
			$this->base64_to_file(file_get_contents('./uploads/'.$name.$extension.'.tmp'),'./uploads/'.$name.$extension);
			//unlink('./uploads/'.$name.$extension.'.tmp'); //descomentar en el servidor de producción
			if(file_exists('./uploads/'.$name.$extension)){
				$response->succeed = TRUE;
				$response->name = $name.$extension;
			} else{
				$response->succeed = FALSE;
				$response->status = $response->status = "No se ha podido subir el archivo ".$name.$extension;
			}
		}
		//send response to client
		$this->output->set_content_type('application/json')->set_output(json_encode($response));
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

}

/* End of file filesystem.php */
/* Location: ./application/controllers/filesystem.php */