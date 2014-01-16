<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Filesystem extends CI_Controller {

	public function index()
	{
				
	}

	public function upload(){
		//read contents from the input stream
		$inputHandler = fopen('php://input','r');
		//create a temp file where to save data from the input stream
		$fileHandler = fopen('./uploads/image.tmp', 'w+');
		//save data from the input stream
		while(true){
			$buffer = fgets($inputHandler,4096);
			if(strlen($buffer) == 0){
				fclose($inputHandler);
				fclose($fileHandler);
				return true;
			}
			fwrite($fileHandler, $buffer);
			$this->base64_to_jpeg(file_get_contents('./uploads/image.tmp'),'./uploads/image.jpg');
		}
	}

	function base64_to_jpeg($base64_string, $output_file) {
    $ifp = fopen($output_file, "wb"); 

    $data = explode(',', $base64_string);

    fwrite($ifp, base64_decode($data[1])); 
    fclose($ifp); 

    return $output_file; 
	}

}
	/*public function upload(){
		// read contents from the input stream
$inputHandler = fopen('php://input', "r");
// create a temp file where to save data from the input stream
$fileHandler = fopen('/tmp/myfile.tmp', "w+");
 
// save data from the input stream
while(true) {
    $buffer = fgets($inputHandler, 4096);
    if (strlen($buffer) == 0) {
        fclose($inputHandler);
        fclose($fileHandler);
        return true;
    }
 
    fwrite($fileHandler, $buffer);
}
 
// done
- See more at: http://www.webiny.com/blog/2012/05/07/webiny-file-upload-with-html5-and-ajax-using-php-streams/#sthash.FX3Ji3zW.dpuf
	}

}*/

/* End of file filesystem.php */
/* Location: ./application/controllers/filesystem.php */