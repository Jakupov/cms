<?php 
    function ShowFolders($selected_folder= 0) { 
        $dir = $_SERVER['DOCUMENT_ROOT'].'/documents/*';
        $dirs = glob($dir, GLOB_ONLYDIR);
        $folders = "";
        foreach ($dirs as $value){
            $name = basename($value);
            if ($selected_folder == $name) $folders .= "<option selected value='".$name."'>".$name."</option>";
            else $folders .= "<option value='".$name."'>".$name."</option>";
        }
        echo $folders;
    }

    function uploaddoc_form(){
        echo '<div id="updatedoc_form">';
            updatedoc_form();
        echo '</div>';
    }

    function updatedoc_form($folder = 0){
       echo '<form id="uploaddocs" enctype="multipart/form-data" action="" method="POST">
            <div  class="panel panel-default">
                <div class="panel-heading">'.DOCUMENTS.'</div>
                <div class="panel-body">
                    <div class="form-group">
                        <label for="url">'.DOCUMENT_LINK.'</label>
                        <input class="btn btn-default form-control" id="url" name="url" value=""/>
                    </div>
                    <div class="form-group">
                        <label for="docs">'.DOCUMENTS_FOLDER.'</label>
                        <select class="fa form-control" name="docs" id="docs" onchange="showFolder(this.value)">';
                            ShowFolders($folder);
                        echo '</select>
                    </div>
                    <div id="doc" class="form-group"></div>
                    <div class="form-group">
                        <label>'.UPLOAD_DOCUMENTS.'</label>
                        <input type="hidden" name="MAX_FILE_SIZE" value="'.MAX_UPLOAD_DOCUMENT_SIZE.'" />
                        <input name="docs[]" type="file" multiple="multiple" class="fa btn btn-default"/>
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