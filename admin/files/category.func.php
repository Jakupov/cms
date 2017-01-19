<?php 
    function load_categories(){
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'edit':
                    edit_category($_GET['id'],$_GET['lang']);
                    break;
                case 'new':
                    new_category();
                    break;     
            }
        }
        else {
            echo '<div class="article container-fluid">
                <h4>'.CATEGORIES.'</h4>
                <div id="list" class="text col-md-8">';
                if (isset($_SESSION['current_c'])) $category = $_SESSION['current_c'];
                    else $category = 1;
            echo list_categories($category);
            echo '</div>';
            right_column_categories($category);
            echo '</div>';
        }
    }

    function list_categories($selected_category = 1, $search_text = '', $show_deleted = 0){
        $list = '<table class="table table-striped table-bordered">
            <thead><td>'.ID.'</td><td>'.TITLE.'</td><td>'.PARENT_CATEGORY.'</td><td>'.RUSSIAN.'</td><td>'.ENGLISH.'</td><td>'.STATUS.'</td><td class="fa fa-check"></td></thead>
            <tbody>';
        $conn = connect();
        $query = "SELECT c.id, c.title_kz, c.title_ru, c.title_en, g.title_kz, s.title, c.state 
        FROM categories c 
        LEFT JOIN categories g ON c.parent_id=g.id 
        LEFT JOIN states s ON c.state=s.id 
        WHERE (c.title_kz LIKE ? OR 
        c.title_ru LIKE ? OR 
        c.title_en LIKE ?) AND (c.parent_id=?)";
        if ($show_deleted==1) $query .= " AND (c.state=0)";
        else $query .= " AND (c.state=1)";
        $search_text = "%".$search_text."%";
        if ($stmt = $conn -> prepare($query)) {
            $stmt -> bind_result($id, $title_kz, $title_ru, $title_en, $parent_name, $state, $st);
            $stmt -> bind_param("sssi", $search_text, $search_text, $search_text, $selected_category);
            $stmt -> execute();
            while ($stmt -> fetch()){
                $list .= "<tr>
                <td>".$id."</td>
                <td><a href='/admin/categories/edit/".$id."/kz' >".(strlen($title_kz)>0?$title_kz:NO_TRANSLATION)."</a></td>
                <td>".$parent_name."</td>
                <td><a href='/admin/categories/edit/".$id."/ru' >".(strlen($title_ru)>0?$title_ru:NO_TRANSLATION)."</a></td>
                <td><a href='/admin/categories/edit/".$id."/en' >".(strlen($title_en)>0?$title_en:NO_TRANSLATION)."</a></td>
                <td class='text-center toggle'><span class='delete fa ".(($st==1)?"fa-toggle-on":"fa-toggle-off")."' name='categories'></span><input name='".(($st==0)?1:0)."' type='hidden' value='".$id."'/></td>
                <td><input type='checkbox' class='check'/><input type='hidden' value='".$id."'/></td>
                </tr>";
            }
        }
        $stmt -> close();
        $list .= '</tbody></table>';
        return $list;
    }

    function right_column_categories($selected_category = 1){
        echo '<div class="text col-md-4">
                <form actoin="" method="POST">
                    <div class="panel panel-default">
                        <div class="panel-heading">'.CATEGORY.'</div>
                        <div class="panel-body">
                            <input type="submit" name="new_category" class="fa fa-file-o btn btn-default" value="'.SAVE.'"/>
                            <span id="group_delete" class="btn btn-default fa fa-toggle-off" name="categories">'.DELETE.'</span>
                            <span id="group_restore" class="btn btn-default fa fa-toggle-on" name="categories">'.RESTORE.'</span>
                        </div>
                    </div>
                </form>

                <div class="panel panel-default">
                    <div class="panel-heading">'.VIEW_LIST.'</div>
                    <div class="panel-body">
                        <input type="checkbox" id="show_deleted" name="categories" class="fa btn btn-default">'.TRASH.'</input><br><br>
                        <label class="fa">'.CATEGORY.'</label>
                        <select id="categories" class="fa form-control" name="categories">';
                            ShowCategories($selected_category);
                        echo '</select><br><br>
                        <label class="fa">'.SEARCH.'</label>
                        <input type="text" class="fa form-control" id="search_text">
                        <span id="search" name="categories" class="btn btn-default fa fa-search">'.FIND.'</span>
                    </div>
                </div>';
        echo '</div>';
    }

    function ShowCategories($selected_id = 0, $parent_id = 1) { 
        $conn = connect();
        $query = "SELECT id, title_kz, parent_id FROM categories";
        if ($stmt = $conn -> prepare($query)) {
        	$stmt -> bind_result($id,$title,$parent_id);
        	$stmt -> execute();
        	$cats = array();
        	while ($stmt -> fetch()) {
        		$cats[$parent_id][$id]['id']=$id;
        		$cats[$parent_id][$id]['title']=$title;
        		$cats[$parent_id][$id]['parent_id']=$parent_id;
        	}
            if (isset($_SESSION['current_c'])) $selected_id = $_SESSION['current_c'];
                    else $selected_id = 1;
        	$cat = build_tree($cats, $parent_id, $selected_id);
            if ($selected_id==0) echo ROOT_OPTION_SELECTED.$cat;
        	else echo ROOT_OPTION.$cat;
        }
        $stmt->close(); 
    }

    function build_tree($cats,$parent_id,$selected_id,$i=0,$only_parent = false){
        if (is_array($cats) and isset($cats[$parent_id])){
            $i++;
            $tab = "";
            for ($j=0; $j < $i; $j++) $tab.="- ";
            $tree = "";
            if ($only_parent==false){
                foreach ($cats[$parent_id] as $cat){
                    if ($selected_id==$cat['id']) $tree .= '<option selected value="'.$cat['id'].'">'.$tab.$cat['title'].' #'.$cat['id'];
                    else $tree .= '<option value="'.$cat['id'].'">'.$tab.$cat['title'].' #'.$cat['id'];
                    $tree .=  build_tree($cats,$cat['id'],$selected_id,$i);
                    $tree .= '</option>';
                }
            }
            elseif (is_numeric($only_parent)){
                $cat = $cats[$parent_id][$only_parent];
                $tree .= '<option>'.$tab.$cat['name'].' #'.$cat['id'];
                $tree .=  build_tree($cats,$cat['id'],$i);
                $tree .= '</option>';
            }
            return $tree;
        }
        else return null;
    }

    function new_category(){
        echo '<div class="article container-fluid">
            <h4>'.NEW_CATEGORY.': <span class="label label-info">kz<span></h4>
            <div class="text col-md-8">
                <form actoin="" method="POST">
                    <div class="panel panel-default panel-body">
                        <input type="submit" name="save_category" class="fa btn btn-success" value="'.SAVE.'">
                        <input type="submit" name="save_and_new_category" class="fa btn btn-primary" value="'.SAVE_AND_NEW.'">
                        <input type="submit" name="save_and_close_category" class="fa btn btn-warning" value="'.SAVE_AND_CLOSE.'">
                        <input type="submit" name="close_category" class="fa btn btn-danger" value="'.CLOSE.'">
                    </div>
                    <div class="panel panel-default">
                            <div class="panel-heading">'.PARENT_CATEGORY.'</div>
                            <div class="panel-body">
                            <select class="fa form-control" id="category" name="categories">';
                                ShowCategories();
                            echo '</select>
                            </div>
                    </div>

                    <input type="hidden" name="selectedImg" id="selectedImg" value="0"/>

                    <div class="panel panel-default">
                        <div class="panel-heading">'.TITLE.'</div>
                        <div class="panel-body">
                            <input type="text" class="fa form-control" name="title">
                        </div>
                    </div>
                </form>
            </div>
            <div class="image col-md-4">';
                upload_form();  
            echo '</div>
        </div>';
    }

    function save_category($save_data){
        $conn = connect();
        $query = "INSERT INTO categories(title_kz, parent_id, image_id)
        VALUES(?,?,?)";
        if ($stmt = $conn -> prepare($query)) {
            $stmt -> bind_param("sii", $save_data['title'], $save_data['categories'], $save_data['selectedImg']);
            $stmt -> execute();
            return $conn -> insert_id;
        }
    }

    function update_category($save_data){
        $conn = connect();
        $query = "UPDATE categories";
        switch ($save_data['lang']) {
            case 'kz':
                $query .= " SET title_kz=?, ";
                break;
            case 'ru':
                $query .= " SET title_ru=?, ";
                break;
            case 'en':
                $query .= " SET title_en=?, ";
                break;
            
            default:
                $query .= " SET title_kz=?, ";
                break;
        }
        $query .= " parent_id=?, image_id=? WHERE id=?";
        if ($stmt = $conn -> prepare($query)) {
            $stmt -> bind_param("siii", $save_data['title'], $save_data['categories'], $save_data['selectedImg'], $save_data['id']);
            $stmt -> execute(); 
            return $save_data['id'];
        }
    }

    function edit_category($insert_id,$lang){
        $conn = connect();
        $query = "";
        switch ($lang) {
            case 'kz':
                $query .= "SELECT c.title_kz, ";
                break;
            case 'ru':
                $query .= "SELECT c.title_ru, ";
                break;
            case 'en':
                $query .= "SELECT c.title_en, ";
                break;
            
            default:
                $query .= "SELECT c.title_kz, ";
                break;
        }
        $query .= " c.parent_id, c.image_id, i.gallery_id 
        FROM categories c
        LEFT JOIN images i ON i.id=c.id
        WHERE c.id=?";
        if ($stmt = $conn -> prepare($query)) {
            $stmt -> bind_result($title, $category_id, $image_id, $gallery_id);
            $stmt -> bind_param("i", $insert_id);
            $stmt -> execute();
            $stmt -> fetch();
        }
        $stmt -> close();

        echo '<div class="article container-fluid">
            <h4>'.EDIT_CATEGORY.': <span class="label label-info">'.$lang.'<span></h4>
            <div class="text col-md-8">
                <form actoin="" method="POST">
                    <div class="panel panel-default panel-body">
                        <input type="hidden" name="lang" value="'.$lang.'">
                        <input type="hidden" name="id" value="'.$insert_id.'">
                        <input type="submit" name="update_category" class="fa btn btn-success" value="'.SAVE.'">
                        <input type="submit" name="update_and_new_category" class="fa btn btn-primary" value="'.SAVE_AND_NEW.'">
                        <input type="submit" name="update_and_close_category" class="fa btn btn-warning" value="'.SAVE_AND_CLOSE.'">
                        <input type="submit" name="close_update_category" class="fa btn btn-danger" value="'.CLOSE.'">
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">'.PARENT_CATEGORY.'</div>
                        <div class="panel-body">
                            <select class="fa form-control" id="category" name="categories">';
                                ShowCategories($category_id);
                            echo '</select>
                        </div>
                    </div>

                    <input type="hidden" name="selectedImg" id="selectedImg" value="'.$image_id.'"/>

                    <div class="panel panel-default">
                        <div class="panel-heading">'.TITLE.'</div>
                        <div class="panel-body">
                            <input type="text" value="'.$title.'" class="fa form-control" name="title">
                        </div>
                    </div>
                </form>
            </div>
            <div class="image col-md-4">';
                upload_form($gallery_id);
            echo '</div>
        </div>';
    }
?>