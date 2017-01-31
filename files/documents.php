<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/admin/files/documents.func.php';
	if(isset($_GET['folder'])){
		$folder = $_GET['folder'];
		$dir = $_SERVER['DOCUMENT_ROOT'].'/documents/'.$folder.'/*';
	    $dirs = array_filter(glob($dir), "is_file");
		$folders = "<div class='images'>";
	        foreach ($dirs as $value) {
	        	$name = basename($value);
	        	$p = pathinfo($name);
	        	$ext = substr($p['extension'], 0, 3);
				$folders .= "<div class='prev_docs col-md-2'><input readonly id='/documents/".$folder."/".$name."' class='btn btn-default ft ".$ext."' onclick='copyUrl(this)'><h6>".$name."</h6></div>";
	        }
		$folders .= "</div>";
		echo $folders;
	}

	elseif(isset($_GET['action'])){
		$types = array("application/pdf","application/vnd.ms-excel","application/vnd.openxmlformats-officedocument.spreadsheetml.sheet","application/vnd.ms-powerpoint","application/vnd.openxmlformats-officedocument.presentationml.presentation","application/msword","application/vnd.openxmlformats-officedocument.wordprocessingml.document","application/x-rar-compressed","application/rtf","application/x-compressed","application/x-zip-compressed","application/zip","multipart/x-zip");
		$folder = date("Y-m-d");
		$uploaddir = $_SERVER['DOCUMENT_ROOT'].'/documents/'.$folder.'/';
		$url = '/images/'.$folder.'/';
		if (!file_exists($uploaddir)) {
		    mkdir($uploaddir);
		}
		$error_files = "";
		foreach ($_FILES["docs"]["error"] as $key => $error){
			if ($error == UPLOAD_ERR_OK){
				$tmp_name = $_FILES["docs"]["tmp_name"][$key];
		        $name = basename($_FILES["docs"]["name"][$key]);
		        if (file_exists($uploaddir.$name)) {
		        	$name = rand().$name;
		        }
		        $path = $uploaddir.$name;
		        $ext = $_FILES["docs"]["type"][$key];
		        $doc = "";
		        if(in_array($ext, $types)){
					if(!copy($tmp_name, $path)){
						$error_files .= basename($_FILES["docs"]["name"][$key]).", ";
					}
				}
			} 
			else{
				$error_files .= basename($_FILES["docs"]["name"][$key]).", ";
			}	
		}
		if ($error_files != ""){
			$result = "Файлы ".$error_files." не загружены";
		}
		echo updatedoc_form($folder);
	}
?>