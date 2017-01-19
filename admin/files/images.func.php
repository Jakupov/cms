<?php
    function ShowGalleries($selected_id = 0) { 
        $gal = "";
        $conn = connect();
        $query="SELECT id, title_kz FROM galleries";
        if ($stmt = $conn->prepare($query)) {
        	$stmt->bind_result($id,$title);
        	$stmt->execute();
        	$cats = array();
        	while ($stmt->fetch()) {
                if ($selected_id == $id) $gal .= "<option selected value='".$id."'>".$title."</option>";
                else $gal .= "<option value='".$id."'>".$title."</option>";
        	}
        }
        echo $gal;
        $stmt->close(); 
    }

    function upload_form($selected_gallery = 0){
        echo '<div id="update_form">';
            update_form($selected_gallery);
        echo '</div>';
    }

    function update_form($selected_gallery = 0){
       echo '<form id="uploadimages" enctype="multipart/form-data" action="" method="POST">
           <div class="panel panel-default">
                <div class="panel-heading">'.IMAGES.'</div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="new_gallery">'.NEW_GALLERY.'</label>
                        <input class="btn btn-default" name="gallery_title"  id="gallery_title"/>
                        <input class="fa btn btn-default" type="button" value="'.CREATE.'" id="create_gallery"/>
                    </div>
                    <div class="form-group">
                        <label for="galleries">'.GALLERIES.'</label>
                        <select class="fa form-control" name="galleries" id="galleries" onchange="showGallery(this.value)">';
                            ShowGalleries($selected_gallery);
                    echo '</select>
                    </div>
                    <div id="gallery" class="form-group"></div>
                    <div class="form-group">
                        <label>'.UPLOAD_GALLERY.'</label>
                        <input type="hidden" name="MAX_FILE_SIZE" value="'.MAX_UPLOAD_IMAGE_SIZE.'" />
                        <input name="pictures[]" type="file" multiple="multiple" class="fa btn btn-default"/>
                    </div>
                    <div class="form-group">
                        <input class="fa btn btn-default" type="submit" value="'.UPLOAD.'" />
                    </div> 
                    <div id="message"></div>
                </div>
            </div>
        </form>';
    }
?>