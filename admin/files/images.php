<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/admin/files/func.php';
	if (isset($_GET['action'])){
		switch ($_GET['action']) {
			case 'upload':
				upload($_POST, $_FILES);
				break;
			case 'new_gallery':
				new_gallery($_POST);
				break;
		}
	}

	elseif (isset($_GET['gallery'])){
		$gallery_id = $_GET['gallery'];
		$images = "<div class='images'>";
		$conn = connect();
		$query = "SELECT id, preview FROM images WHERE gallery_id = ?";
		if ($stmt = $conn -> prepare($query)) {
			$stmt -> bind_result($id, $preview);
			$stmt -> bind_param("i", $gallery_id);
			$stmt -> execute();
			while ($stmt -> fetch()){
				if ($id==$_GET['selectedId']){
					$images .= "<img id='".$id."' class='prev_images selectedImg' src='".$preview."' onclick='setMainImage(this)'>";
				}
				else {
					$images .= "<img id='".$id."' class='prev_images' src='".$preview."' onclick='setMainImage(this)'>";
				}
			}
		}
		$images .= "</div>";
		echo $images;
	}

	function new_gallery($post_data){
		$gallery_title = $post_data['gallery_title'];
		$conn = connect();
		$query = "INSERT INTO galleries(title_kz)
		VALUES(?)";
		if ($stmt = $conn -> prepare($query)) {
			$stmt -> bind_param("s", $gallery_title);
			$stmt -> execute();
			$gallery_id = $conn -> insert_id;
		}
		echo update_form($gallery_id);
	}

	function upload($post_data, $files_data){
		$gallery_id = $post_data['galleries'];
		$uploaddir = $_SERVER['DOCUMENT_ROOT'].'/images/'.$gallery_id.'/';
		$url = '/images/'.$gallery_id.'/';
		if (!file_exists($uploaddir)){
		    mkdir($uploaddir);
		}
		$error_files = "";
		foreach ($files_data["pictures"]["error"] as $key => $error) {
			if ($error == UPLOAD_ERR_OK) {
				$tmp_name = $files_data["pictures"]["tmp_name"][$key];
		        $name = basename($files_data["pictures"]["name"][$key]);
		        if (file_exists($uploaddir.$name)) {
		        	$name = rand().$name;
		        }
		        $path = $uploaddir.$name;
		        $ext = $files_data["pictures"]["type"][$key];
		        $image = "";
		        $preview = $uploaddir."pre_".$name;
		        $preview_x = 150;
		        $preview_y = 150;
		        switch ($ext){
					case "image/jpeg": 
						$image = imagecreatefromjpeg($tmp_name); 
						header('Content-Type: image/jpeg'); 
						//preview
				        $x = imagesx($image);
				        $y = imagesy($image);
				        if ($x>$y) $x=$y; else $y=$x;
						$prev_image = imagecreatetruecolor($preview_x, $preview_y);
						imagecopyresampled($prev_image, $image, 0, 0, 0, 0, $preview_x, 
						$preview_y, $x, $y);
						imagejpeg($prev_image, $preview);
				        //
						imagejpeg($image, $path);
						imagedestroy($image);
						break;
					case "image/png": 
						$image = imagecreatefrompng($tmp_name); 
						header('Content-Type: image/png'); 	
						//preview
				        $x = imagesx($image);
				        $y = imagesy($image);
				        if ($x>$y) $x=$y; else $y=$x;
						$prev_image = imagecreatetruecolor($preview_x, $preview_y);
						imagecopyresampled($prev_image, $image, 0, 0, 0, 0, $preview_x, 
						$preview_y, $x, $y);
						imagepng($prev_image, $preview);
				        //
						imagepng($image, $path);
						imagedestroy($image);
						break;
					default: $error_files .= $name.", "; break;
				}
				$imgurl = $url.$name;
				$prevurl = $url."pre_".$name;
				$conn = connect();
				$query = "INSERT INTO images(url, preview, gallery_id)
				VALUES(?,?,?)";
				if ($stmt = $conn -> prepare($query)){
					$stmt -> bind_param("ssi", $imgurl, $prevurl, $gallery_id);
					$stmt -> execute();
				}
			} 
			else {
				$error_files .= basename($files_data["pictures"]["name"][$key]).", ";
			}
			
		}
		if ($error_files!=""){
			$result = "<div class='alert alert-danger'>
			 	<strong>Error!</strong>Файлы ".$error_files." не загружены.
			</div>";
		}
		echo update_form($gallery_id);
	}
?>