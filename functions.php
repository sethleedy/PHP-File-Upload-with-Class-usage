<?php


class FileManipulation {
	
	// Start of class
	function __construct() {
		// Create the folder if it is not there.
		$this->createFolder(dirname(__FILE__)."/uploads/");
	}
	
	public function getPath() {
		
		return dirname(__FILE__)."/uploads/";
	}
	
	private function createFolder($newFolder) {
		
		//Check if the directory already exists.
		if( !is_dir($newFolder)) {
			//Directory does not exist, so lets create it.
			mkdir($newFolder, 0777);
		}
		
	}
	
	function handleFile($files) {
		
		// Check for existing file first.
		$path=$this->getPath();
		$chkFile = $path.$files['uploadFile']['name'];
		if (file_exists($chkFile)) {
			
			$message="File already exists.";
			
			$this->filesListing=""; // To stop the showing of the list of files.
			
			return $message;
		}
		
		// Check for errors before processing
		if($files['uploadFile'] ['error'] > 0) {
			switch ($files['uploadFile'] ['error']){
				case 1: 
					$message='File exceeded maximum server upload size';
					break;
				case 2: 
					$message= 'File exceeded maximum file size';
					break;
				case 3: 
					$message='File only partially uploaded';
					break;
				case 4: 
					$message='No file uploaded';
					break;
			}
			
		} else {
			
			$aryImages=array("image/jpeg","image/png");
			$aryDocs=array("application/msword","application/vnd.openxmlformats-officedocument.wordprocessingml.document","application/vnd.openxmlformats-officedocument.spreadsheetml.sheet","application/pdf","video/x-msvideo");
			$filename=$this->filenameSafe($files['uploadFile']['name']);
	
			$fileType=$files["uploadFile"]["type"];
	
			if (in_array($fileType,$aryImages)) {
				
				// Move original file to upload location
				move_uploaded_file($files['uploadFile']['tmp_name'],$path.$filename);
				
				// Create a thumbnail version of it, if the GD module is installed
				$this->createThumb($fileType, $path.$filename, $filename, 100,100);
				
				$message="Thank you, your image was successfully uploaded.";
				
				// Refresh the file listing in uploads
				$this->filesListing=$this->getFiles();
	
			} elseif (in_array($fileType,$aryDocs)) {
				
				$path=$this->getPath();
				move_uploaded_file($files['uploadFile']['tmp_name'], $path.$filename);
				
				$message="Thank you, your file was successfully uploaded.";
				
				$this->filesListing=$this->getFiles();
				
			} else {
				$message="Sorry, that is not a valid file";
			}
		}
		
		return $message;
	}
	
	function filenameSafe($filename) {
		$temp = $filename;
		// Lower case
		$temp = strtolower($temp);
		// Replace spaces with a _
		$temp = str_replace(" ", "_", $temp);
		// Loop through string
		$result = "";
		for ($i=0; $i<strlen($temp); $i++) {
			if (preg_match('([0-9]|[a-z]|_|.)', $temp[$i])) {
				$result = $result.$temp[$i];
			}
		}
		
		return $result;
	}
	
	function createThumb($fileType,$tmpname,$filename,$new_w,$new_h){
		$path=$this->getPath();
		$thumbFilename="tmb-".$filename;
		$src_img="";
		
		if (function_exists("imagecreatefromjpeg") && function_exists("imagecreatefrompng")) { // GD module may not be included in PHP setup
			if (is_numeric(strpos($fileType,"jpeg"))) {

					$src_img=imagecreatefromjpeg($tmpname);

			}
			if (is_numeric(strpos($fileType,"png"))) {

					$src_img=imagecreatefrompng($tmpname);

			}
		
			// If an image was created then ...
			if ($src_img != "") {
				$old_x=imageSX($src_img);
				$old_y=imageSY($src_img);
				if ($old_x > $old_y) {
					$thumb_w=$new_w;
					$thumb_h=$old_y*($new_h/$old_x);
				}
				if ($old_x < $old_y) {
					$thumb_w=$old_x*($new_w/$old_y);
					$thumb_h=$new_h;
				}
				if ($old_x == $old_y) {
					$thumb_w=$new_w;
					$thumb_h=$new_h;
				}
				$dst_img=imagecreatetruecolor($thumb_w,$thumb_h);
				imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);

				if (is_numeric(strpos($fileType,"jpeg"))){
					imagejpeg($dst_img,$path.$thumbFilename);
					imagejpeg($src_img,$path.$filename);
				}
				if (is_numeric(strpos($fileType,"png"))){
					imagepng($dst_img,$path.$thumbFilename);
					imagepng($src_img,$path.$filename);
				}
				imagedestroy($dst_img);
				imagedestroy($src_img);

			}
		}
		
	}
	
	function getFiles(){
		
		// Get a listing of all files in the directory. Returns an Array
		$arrFiles1 = array_diff(scandir($this->getPath()), array('..', '.')); // Removing any "." & ".." from Linux.
		
		return $arrFiles1;
		
	}
	
	function deleteFile($filename) {
		
		$path=$this->getPath();
		$thumbFilename="tmb-".$filename;
		
		if (file_exists($path.$fileName)) {
			unlink($path.$fileName);
		}

		if (file_exists($path.$thumbFilename)) {
			unlink($path.$thumbFilename);
		}
		
	}

}


?>
