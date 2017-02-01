<?php 
    function load_menus(){
        if (isset($_GET['action'])){
            switch ($_GET['action']){
                case 'edit':
                    edit_menu($_GET['id'],$_GET['lang']);
                    break;
                case 'new':
                    new_menu();
                    break;
            }
        }
        else {
            echo '<div class="menu container-fluid">
            <h4>Список меню</h4>
            <div id="messages"></div>
            <div id="list" class="text col-md-8">';
            if (isset($_SESSION['current'])) $category = $_SESSION['current'];
            else $category = 1;
            echo list_menus($category, "", 0, "topmenu");
            echo '</div>';
            right_column_menus();
            echo '</div>';
        }
    }

    function list_menus($parent_id = 1, $search_text = '', $show_deleted = 0, $menutype = "topmenu"){
        $list ='<ul id="sortable" class="table panel panel-default">
            <li class="row panel-heading">
                <div class="cell fa fa-sort-numeric-asc"> ID</div><div class="cell">Название</div><div class="cell">Тип меню</div><div class="cell">Русский</div><div class="cell">Английский</div><div class="cell">Статус</div><div class="cell fa fa-check"></div>
            </li>';
        $conn = connect();
        $query = "SELECT m.id, m.title_kz, m.title_ru, m.title_en, m.menu_type, s.title, m.state 
        FROM menus m 
        LEFT JOIN menus g ON m.parent_id=g.id 
        LEFT JOIN states s ON m.state=s.id
        WHERE (m.title_kz LIKE ? OR 
        m.title_ru LIKE ? OR 
        m.title_en LIKE ?) ";
        if ($show_deleted==1) $query .= " AND (m.state=0)";
        else $query .= " AND (m.state=1)";
        if ($search_text=="") {
                $query .= " AND (m.parent_id=?)";
            }
            else {
                $query .= " AND (m.parent_id<>?)";
                $parent_id = 0;
            }
        $query .= " AND (m.menu_type LIKE ?) ORDER BY m.sort_order";
        
        $search_text = "%".$search_text."%";
        if ($stmt = $conn -> prepare($query)) {
            $stmt -> bind_result($id, $title_kz, $title_ru, $title_en, $parent_name, $state, $st);
            $stmt -> bind_param("sssis", $search_text, $search_text, $search_text, $parent_id, $menutype);
            $stmt -> execute();
            while ($stmt -> fetch()){
                $list .= "<li id='item-".$id."' class='row panel-body'>
                <div class='cell move'>".$id."</div>
                <div class='cell'><a href='/admin/menu/edit/".$id."/kz' >".(strlen($title_kz)>0?$title_kz:"<span class='red'>Нет перевода</span>")."</a></div>
                <div class='cell'>".$parent_name."</div>
                <div class='cell'><a href='/admin/menu/edit/".$id."/ru' >".(strlen($title_ru)>0?$title_ru:"<span class='red'>Нет перевода</span>")."</a></div>
                <div class='cell'><a href='/admin/menu/edit/".$id."/en' >".(strlen($title_en)>0?$title_en:"<span class='red'>Нет перевода</span>")."</a></div>
                <div class='cell text-center toggle'><span class='delete fa ".(($st==1)?"fa-toggle-on":"fa-toggle-off")."' name='menus'></span><input name='".(($st==0)?1:0)."' type='hidden' value='".$id."'/></div>
                <div class='cell'><input type='checkbox' class='check'/><input type='hidden' value='".$id."'/></div>
                </li>";

            }
        }
        $stmt -> close();
        $list .= '</ul>
            <script>
                $(function(){
                    $("#list #sortable").sortable({
                        placeholder: "ui-state-highlight",
                        axis: "y",
                        stop: function (event, ui) {
                            var data = $(this).sortable("serialize");
                            $.ajax({
                                data: data,
                                type: "POST",
                                url: "/admin/files/ajax.php?action=sort",
                                success: function(data)   // A function to be called if request succeeds
                                {
                                    $("#messages").html(data);
                                }
                            });
                        }
                    });
                    $( "#list #sortable" ).disableSelection();
                });
            </script>';
        return $list;
    }

    function right_column_menus(){
        echo '<div class="text col-md-4">
                <form actoin="" method="POST">
                    <div class="panel panel-default">
                            <div class="panel-heading">Меню</div>
                            <div class="panel-body">
                            <input type="submit" name="new_menu" class="fa fa-file-o btn btn-default" value=" '.CREATE.'">
                            <span id="group_delete" class="btn btn-default fa fa-toggle-off" name="menus">" '.DELETE.'"</span>
                            <span id="group_restore" class="btn btn-default fa fa-toggle-on" name="menus"> "'.RESTORE.'"</span>
                            <span id="group_copy" class="btn btn-default fa fa-clone" name="menus"> "'.COPY.'"</span>
                            </div>
                    </div>
                </form>

                <div class="panel panel-default">
                    <div class="panel-heading">Список</div>
                    <div class="panel-body">
                        <input type="checkbox" id="show_deleted" name="menus" class="fa btn btn-default">Корзина</input><br><br>
                        <label class="fa">Типы меню</label>
                        <select id="menus" class="fa form-control" name="menus">';
                            ShowMenus();
                        echo '</select><br><br>
                        <label class="fa">Пункты меню</label>
                        <select id="menu_categories" class="fa form-control" name="menus">';
                            ShowMenuCategories();
                        echo '</select><br><br>
                        <label class="fa">Поиск</label>
                        <input type="text" class="fa form-control" id="search_text">
                        <span id="search" name="menus" class="btn btn-default fa fa-search">
                            Искать</span>
                    </div>
                </div>';
        echo '</div>';
    }

    function ShowMenus($selected = 'mainmenu') { 
        $conn = connect();
        $query = "SELECT type_title, type_name FROM menutypes";
        if ($stmt = $conn -> prepare($query)) {
            $stmt -> bind_result($title,$type_name);
            $stmt -> execute();
            $menu = "";
            while ($stmt -> fetch()) {
                if($type_name == $selected) $menu .= "<option selected value='".$type_name."'>".$title."</option>";
                else $menu .= "<option value='".$type_name."'>".$title."</option>";
            }
            echo $menu;
        }
        $stmt->close(); 
    }

    function ShowMenuCategories($menutype = 'topmenu', $selected_id = 1){ 
        $conn = connect();
        $query="SELECT id, title_ru, parent_id FROM menus WHERE menu_type=?";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("s", $menutype);
            $stmt->bind_result($id,$title,$parent_id);
            $stmt->execute();
            $cats = array();
            while ($stmt->fetch()) {
                $cats[$parent_id][$id]['id']=$id;
                $cats[$parent_id][$id]['title']=$title;
                $cats[$parent_id][$id]['parent_id']=$parent_id;
            }
            if (isset($_SESSION['current'])) {
               $selected_id = $_SESSION['current'];
            }
            if ($selected_id==1) $cat = "<option value='1' selected ><strong>Корневой</strong></option>";
            else $cat = "<option value='1'><strong>Корневой</strong></option>";
            $cat .= build_tree_menu($cats,1,$selected_id);
            echo $cat;
        }
        $stmt->close(); 
    }

    function build_tree_menu($cats,$parent_id,$selected_id,$i,$only_parent = false){
        if(is_array($cats) and isset($cats[$parent_id])){
            $i++;
            $tab = "";
            for ($j = 0; $j < $i; $j++) $tab.="- ";
            $tree = "";
            if ($only_parent==false){
                foreach($cats[$parent_id] as $cat){
                    if ($selected_id==$cat['id']) $tree .= '<option selected value="'.$cat['id'].'">'.$tab.$cat['title'].' #'.$cat['id'];
                    else $tree .= '<option value="'.$cat['id'].'">'.$tab.$cat['title'].' #'.$cat['id'];
                    $tree .=  build_tree_menu($cats,$cat['id'],$selected_id,$i);
                    $tree .= '</option>';
                }
            }
            elseif (is_numeric($only_parent)){
                $cat = $cats[$parent_id][$only_parent];
                $tree .= '<option value="'.$tab.$cat['id'].'">'.$tab.$cat['title'].' #'.$cat['id'];
                $tree .=  build_tree_menu($cats,$cat['id'],$i);
                $tree .= '</option>';
            }
            return $tree;
        }
        else return null;
    }

    function new_menu(){
        echo '<div class="menu container-fluid">
            <h4>Новый меню: <span class="label label-info">kz<span></h4>
            <div class="text col-md-8">
                <form actoin="" method="POST">
                    <div class="panel panel-default panel-body">
                        <input type="submit" name="save_menu" class="fa btn btn-success" value="Сохранить">
                        <input type="submit" name="save_and_new_menu" class="fa btn btn-primary" value="Сохранить и создать">
                        <input type="submit" name="save_and_close_menu" class="fa btn btn-warning" value="Сохранить и закрыть">
                        <input type="submit" name="close_menu" class="fa btn btn-danger" value="Закрыть без сохранений">
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">Меню</div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label>Тип меню</label>
                                <select class="fa form-control" id="menu_type" name="menus">';
                                    ShowMenus();
                                echo '</select>
                            </div>

                            <div class="form-group">
                                <label>Родительское меню</label>
                                <select class="fa form-control" id="category" name="categories">';
                                    ShowMenuCategories();
                                echo '</select>
                            </div>

                            <input type="hidden" name="selectedImg" id="selectedImg" value="0"/>

                            <div class="form-group">
                                <label>Название</label>
                                <input type="text" class="fa form-control" placeholder="Название" name="title">
                            </div>

                            <div class="form-group">
                                <label>Ссылка</label>
                                <select class="fa form-control" id="links" name="links">';
                            showLinks();        
                            echo '</select>
                            </div>

                            <input type="hidden" name="selectedArticle" id="selectedArticle" value="0"/>
                        </div>
                    </div>

                    <div id="link">';
                        setArticle();
                    echo '</div>
                </form>
            </div>
            <div class="image col-md-4">';
                upload_form();  
            echo '</div>
        </div>';
    }

    function showLinks($link=0){///
        $links = array('#','Материал','Внешняя ссылка', 'Категория', 'Модуль', 'Блог');
        for ($i=0; $i <= 5; $i++) { 
            if ($link==$i) {
                echo '<option value="'.$i.'" selected>'.$links[$i].'</option>';
            }
            else echo '<option value="'.$i.'">'.$links[$i].'</option>';
        }
    }

    function set_articles($selected_category = 0, $search_text = ''){
        $list = '<table class="table table-striped table-bordered">
        <thead><td>ID</td><td>Название</td></thead>
        <tbody>';
            $conn = connect();
            $query = "SELECT c.id, c.title_ru
            FROM contents c 
            WHERE (c.title_kz LIKE ? OR 
            c.title_ru LIKE ? OR 
            c.title_en LIKE ?) AND (c.category_id=?) AND (c.state=1)";
            $search_text = "%".$search_text."%";
            if ($stmt = $conn -> prepare($query)) {
                $stmt -> bind_result($id, $title_kz);
                $stmt -> bind_param("sssi", $search_text, $search_text, $search_text, $selected_category);
                $stmt -> execute();
                while ($stmt -> fetch()){
                    $list .= "<tr>
                    <td>".$id."</td>
                    <td class='article_select' id='".$id."'>".(strlen($title_kz)>0?$title_kz:"<span class='red'>Нет перевода</span>")."</a></td>
                    </tr>";
                }
            }
            $stmt -> close();
        $list .= '</tbody></table>';
        return $list;
    }

    function setHref($link = '#'){
        echo '<div class="panel panel-default">
                <div class="panel-heading">Материал</div>
                <div class=" panel-body">
                    <label class="fa">Ссылка</label>
                    <input type="text" class="fa form-control" name="href_text" value="'.$link.'">
                </div>   
            </div>';
    }

    function setCategory($category_id){
        echo '<div class="panel panel-default">
                <div class="panel-heading">Материал</div>
                <div class=" panel-body">
                    <label class="fa">Категория</label>
                    <select class="fa form-control" name="selectedCategory">';
                        ShowCategories($category_id);
                    echo '</select>
                </div>   
            </div>';
    }

    function ShowModules($module) { 
        $dir = $_SERVER['DOCUMENT_ROOT'].'/modules/*';
        $dirs = glob($dir, GLOB_ONLYDIR);
        $folders = "";
        foreach ($dirs as $value){
            $name = basename($value);
            if ($module == $name) $folders .= "<option selected value='".$name."'>".$name."</option>";
            else $folders .= "<option value='".$name."'>".$name."</option>";
        }
        echo $folders;
    }

    function setModule($module){
        echo '<div class="panel panel-default">
                <div class="panel-heading">Материал</div>
                <div class=" panel-body">
                    <label class="fa">'.MODULES.'</label>
                    <select class="fa form-control" name="selectedModule">';
                        ShowModules($module);
                    echo '</select>
                </div>  
            </div>';
    }

    function setArticle($id = 0, $category_id = 0){
        echo '<div class="panel panel-default">
                <div class="panel-heading">Материал</div>
                <div class=" panel-body">
                    <label class="fa">'.CATEGORIES.'</label>
                    <select class="fa form-control" id="article_categories" name="articles">';
                        ShowCategories($category_id);
                    echo '</select><br><br>
                    <label class="fa">Поиск</label>
                    <input type="text" class="fa form-control" id="search_text">
                    <span id="search" name="articles" class="btn btn-default fa fa-search">
                        Искать</span>
                </div>
                <div id="list"></div>    
            </div>';
    }

    function save_menu($save_data){
        if (isset($save_data["selectedArticle"])) $link = "article/".$save_data['selectedArticle'];
        if (isset($save_data["href_text"])) $link = $save_data["href_text"];
        if (isset($save_data["selectedCategory"])) $link = "category/".$save_data["selectedCategory"];
        if (isset($save_data["selectedModule"])) $link = "module/".$save_data["selectedModule"];
        if ($save_data["links"]==0) $link = "#";
        $conn = connect();
        $query = "INSERT INTO menus(title_kz, parent_id, image_id, menu_type, link, item_type)
        VALUES(?,?,?,?,?,?)";
        if ($stmt = $conn -> prepare($query)) {
            $stmt -> bind_param("siissi", $save_data['title'], $save_data['categories'], $save_data['selectedImg'], $save_data['menus'], $link, $save_data['links']);
            $stmt -> execute();
            return $conn -> insert_id;
        }
    }

    function update_menu($save_data){
        if (isset($save_data["selectedArticle"])) $link = "article/".$save_data['selectedArticle'];
        if (isset($save_data["href_text"])) $link = $save_data["href_text"];
        if (isset($save_data["selectedCategory"])) $link = "category/".$save_data["selectedCategory"];
        if (isset($save_data["selectedModule"])) $link = "module/".$save_data["selectedModule"];
        if ($save_data["links"]==0) $link = "#";
        $conn = connect();
        $query = "UPDATE menus";
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
        $query .= " parent_id=?, image_id=?, menu_type=?, link=?, item_type=? WHERE id=?";
        if ($stmt = $conn -> prepare($query)) {
            $stmt -> bind_param("siissii", $save_data['title'], $save_data['categories'], $save_data['selectedImg'], $save_data['menus'], $link, $save_data['links'], $save_data['id']);
            $stmt -> execute(); 
            return $save_data['id'];
        }
    }

    function edit_menu($insert_id,$lang){
        $conn = connect();
        $query = "";
        switch ($lang) {
            case 'kz':
                $query .= "SELECT c.title_kz, c.title_kz, ";
                break;
            case 'ru':
                $query .= "SELECT c.title_kz, c.title_ru, ";
                break;
            case 'en':
                $query .= "SELECT c.title_kz, c.title_en, ";
                break;
            default:
                $query .= "SELECT c.title_kz, c.title_kz, ";
                break;
        }
        $query .= " c.parent_id, c.image_id, i.gallery_id, c.menu_type, c.item_type, c.link
        FROM menus c
        LEFT JOIN images i ON i.id=c.id
        WHERE c.id=?";
        if ($stmt = $conn -> prepare($query)) {
            $stmt -> bind_result($title_original, $title, $category_id, $image_id, $gallery_id, $menutype, $item_type, $link);
            $stmt -> bind_param("i", $insert_id);
            $stmt -> execute();
            $stmt -> fetch();
        }
        $stmt -> close();
        $arr = explode("/", $link, 2);
        $article_id = $arr[1];
        echo '<div class="menu container-fluid">
            <h4>Редактировать меню: <span class="label label-info">'.$lang.'<span></h4>
            <div class="text col-md-8">
                <form actoin="" method="POST">
                    <div class="panel panel-default panel-body">
                        <input type="hidden" name="lang" value="'.$lang.'">
                        <input type="hidden" name="id" value="'.$insert_id.'">
                        <input type="submit" name="update_menu" class="fa btn btn-success" value="Сохранить">
                        <input type="submit" name="update_and_new_menu" class="fa btn btn-primary" value="Сохранить и создать">
                        <input type="submit" name="update_and_close_menu" class="fa btn btn-warning" value="Сохранить и закрыть">
                        <input type="submit" name="close_update_menu" class="fa btn btn-danger" value="Закрыть без сохранений">
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">Меню</div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label>Оригинал:</label>
                                <label class="fa form-control-static">'.$title_original.'</label>
                            </div>
                            <div class="form-group">
                                <label>Тип меню</label>
                                <select class="fa form-control" id="menu_type" name="menus">';
                                    ShowMenus($menutype);
                                echo '</select>
                            </div>

                            <div class="form-group">
                                <label>Родительское меню</label>
                                <select class="fa form-control" id="category" name="categories">';
                                    ShowMenuCategories($menutype, $category_id);
                                echo '</select>
                            </div>

                            <input type="hidden" name="selectedImg" id="selectedImg" value="'.$image_id.'"/>

                            <div class="form-group">
                                <label>Название</label>
                                <input type="text" class="fa form-control" placeholder="Название" name="title" value="'.$title.'">
                            </div>

                            <div class="form-group">
                                <label>Ссылка</label>
                                <select class="fa form-control" id="links" name="links">
                                    ';
                                showLinks($item_type);
                                echo '</select>
                            </div>

                            <input type="text" name="selectedArticle" id="selectedArticle" value="'.$article_id.'"/>
                        </div>
                    </div>

                    <div id="link">';
                    switch ($item_type) {
                        case 1:
                            setArticle($article_id);
                            break;
                        case 2:
                            setHref($link);
                            break;
                        case 3:
                            setCategory($article_id); 
                            break;
                        case 4:
                            setModule($article_id); 
                            break;
                        case 5:
                            setBlog(); 
                            break;
                        default:
                            # code...
                            break;
                    }
                    echo '</div>
                </form>
            </div>
            <div class="image col-md-4">';
                upload_form($gallery_id);
            echo '</div>
        </div>';
    }

?>