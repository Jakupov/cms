<?php
	function load_articles(){
		if (isset($_GET['action'])) {
			switch ($_GET['action']) {
				case 'edit':
					edit_article($_GET['id'],$_GET['lang']);
					break;
				case 'new':
					new_article();
					break;	
			}
		}
		else { 
			echo '<div class="article container-fluid">
			<h4>'.ARTICLES.'</h4>
			<div id="list" class="text col-md-8">';
			if (isset($_SESSION['current_c'])) $category = $_SESSION['current_c'];
					else $category = 1;
			echo list_articles($category);
			echo '</div>';
			right_column_articles($category);
			echo '</div>';
		}
	}

	function list_articles($selected_category = 1, $search_text = '', $show_deleted = 0){
		$list = '<table class="table table-striped table-bordered">
		<thead><td>'.ID.'</td><td>'.TITLE.'</td><td>'.CATEGORY.'</td><td>'.RUSSIAN.'</td><td>'.ENGLISH.'</td><td>'.STATUS.'</td><td class="fa fa-check"></td></thead>
		<tbody>';
			$conn = connect();
			$query = "SELECT c.id, c.title_kz, c.title_ru, c.title_en, g.title_kz, s.title, c.state  
			FROM contents c 
			LEFT JOIN categories g ON c.category_id=g.id 
			LEFT JOIN states s ON c.state=s.id 
			WHERE (c.title_kz LIKE ? OR 
			c.title_ru LIKE ? OR 
			c.title_en LIKE ?) AND (c.category_id=?)";
			if ($show_deleted==1) $query .= " AND (c.state=0)";
            else $query .= " AND (c.state=1)";
			$search_text = "%".$search_text."%";
			if ($stmt = $conn -> prepare($query)) {
				$stmt -> bind_result($id, $title_kz, $title_ru, $title_en, $category_name, $state, $st);
				$stmt -> bind_param("sssi", $search_text, $search_text, $search_text, $selected_category);
				$stmt -> execute();
				while ($stmt -> fetch()){
					$list .= "<tr>
					<td>".$id."</td>
					<td><a href='/admin/articles/edit/".$id."/kz' >".(strlen($title_kz)>0?$title_kz:NO_TRANSLATION)."</a></td>
					<td>".$category_name."</td>
					<td><a href='/admin/articles/edit/".$id."/ru' >".(strlen($title_ru)>0?$title_ru:NO_TRANSLATION)."</a></td>
					<td><a href='/admin/articles/edit/".$id."/en' >".(strlen($title_en)>0?$title_en:NO_TRANSLATION)."</a></td>
					<td class='text-center toggle'><span class='delete fa ".(($st==1)?"fa-toggle-on":"fa-toggle-off")."' name='articles'></span><input name='".(($st==0)?1:0)."' type='hidden' value='".$id."'/></td>
					<td><input type='checkbox' class='check'/><input type='hidden' value='".$id."'/></td>
					</tr>";

				}
			}
			$stmt -> close();
		$list .= '</tbody></table>';
		return $list;

	}

	function right_column_articles($selected_category = 1){
		echo '<div class="text col-md-4">
				<form actoin="" method="POST">
					<div class="panel panel-default">
							<div class="panel-heading">'.ARTICLE.'</div>
							<div class="panel-body">
							<input type="submit" name="new_article" class="fa fa-file-o btn btn-default" value="'.CREATE.'">
							<span id="group_delete" class="btn btn-default fa fa-toggle-off" name="articles">'.DELETE.'</span>
	                        <span id="group_restore" class="btn btn-default fa fa-toggle-on" name="articles">'.RESTORE.'</span>
	                        <span id="group_copy" class="btn btn-default fa fa-clone" name="articles"> '.COPY.'</span>
	                        </div>
		  			</div>
	  			</form>

				<div class="panel panel-default">
					<div class="panel-heading">'.VIEW_LIST.'</div>
					<div class=" panel-body">
	                    <input type="checkbox" id="show_deleted" name="articles" class="fa btn btn-default">'.TRASH.'</input><br><br>
				    	<label class="fa">'.CATEGORY.'</label>
				    	<select class="fa form-control" id="categories" name="articles">';
				    		ShowCategories($selected_category);
						echo '</select><br><br>
				    	<label class="fa">'.SEARCH.'</label>
				    	<input type="text" class="fa form-control" id="search_text">
						<span id="search" name="articles" class="btn btn-default fa fa-search">'.FIND.'</span>
	                </div>
	  			</div>';
		echo '</div>';
	}

	function new_article(){
		echo '<div class="article container-fluid row">
		<h4>'.NEW_ARTICLE.': <span class="label label-info">kz<span></h4>
		<div class="text col-md-8">
		<form actoin="" method="POST">
			<div class="panel panel-default panel-body">
					<input type="submit" name="save_article" class="fa btn btn-success" value="'.SAVE.'">
                    <input type="submit" name="save_and_new_article" class="fa btn btn-primary" value="'.SAVE_AND_NEW.'">
                    <input type="submit" name="save_and_close_article" class="fa btn btn-warning" value="'.SAVE_AND_CLOSE.'">
                    <input type="submit" name="close_article" class="fa btn btn-danger" value="'.CLOSE.'">
	  		</div>
			<div class="panel panel-default">
		    	<div class="panel-heading">'.CATEGORY.'</div>
		    	<div class="panel-body">
		    	<select class="fa form-control" id="category" name="categories">';
		    			ShowCategories();
					echo '</select>
				</div>
	  		</div>

    		<input type="hidden" name="selectedImg" id="selectedImg" value="0"/>

	  		<div class="panel panel-default">
	  		<div class="panel-heading">'.ARTICLE.'</div>
	  		<div class="panel-body">
				<div class="form-group">
					<label for="category">'.TITLE.'</label>
					<input type="text" class="form-control" name="title">
				</div>
				<div class="form-group">
		  			<label for="intro">'.INTRO.'</label>
		  			<textarea class="form-control" rows="3" name="intro"></textarea>
		  		</div>
		  		<div class="form-group">
		  			<label for="fulltext">'.FULL_TEXT.'</label>
		  			<textarea class="form-control" rows="3" name="fulltext"></textarea>
		  		</div>
		  	</div>
	  		</div>
		</form>
	</div>
	<div class="col-md-4">';
	upload_form();
	uploaddoc_form();		
	echo '</div>
</div>';
	}

	function save_article($save_data){
		$conn = connect();
		$query = "INSERT INTO contents(title_kz, intro_kz, fulltext_kz, category_id, created_date, image_id)
		VALUES(?,?,?,?,?,?)";
		if ($stmt = $conn -> prepare($query)) {
			$stmt -> bind_param("sssisi", $save_data['title'], $save_data['intro'], $save_data['fulltext'], $save_data['categories'], date("Y-m-d"), $save_data['selectedImg']);
			$stmt -> execute();
			return $conn -> insert_id;
		}
	}

	function update_article($save_data){
		$conn = connect();
		$query = "UPDATE contents";
		switch ($save_data['lang']) {
			case 'kz':
				$query .= " SET title_kz=?, intro_kz=?, fulltext_kz=?";
				break;
			case 'ru':
				$query .= " SET title_ru=?, intro_ru=?, fulltext_ru=?";
				break;
			case 'en':
				$query .= " SET title_en=?, intro_en=?, fulltext_en=?";
				break;
			
			default:
				$query .= " SET title_kz=?, intro_kz=?, fulltext_kz=?";
				break;
		}
		$query .= ", category_id=?, image_id=? WHERE id=?";
		if ($stmt = $conn -> prepare($query)) {
			$stmt -> bind_param("sssiii", $save_data['title'],  $save_data['intro'], $save_data['fulltext'], $save_data['categories'], $save_data['selectedImg'], $save_data['id']);
			$stmt -> execute(); 
			return $save_data['id'];
		}
	}

	function edit_article($insert_id, $lang){
		$conn = connect();
		$query = "";
		switch ($lang) {
			case 'kz':
				$query .= "SELECT c.title_kz, c.title_kz, c.intro_kz, c.fulltext_kz";
				break;
			case 'ru':
				$query .= "SELECT c.title_kz, c.title_ru, c.intro_ru, c.fulltext_ru";
				break;
			case 'en':
				$query .= "SELECT c.title_kz, c.title_en, c.intro_en, c.fulltext_en";
				break;
			default:
				$query .= "SELECT c.title_kz, c.title_kz, c.intro_kz, c.fulltext_kz";
				break;
		}
		
		$query .= ", c.category_id, c.image_id, i.gallery_id 
		FROM contents c
		LEFT JOIN images i ON i.id=c.id
		WHERE c.id=?";
		if ($stmt = $conn -> prepare($query)) {
			$stmt -> bind_result($title_original, $title, $intro, $fulltext, $category_id, $image_id, $gallery_id);
			$stmt -> bind_param("i", $insert_id);
			$stmt -> execute();
			$stmt -> fetch();
		}
		$stmt -> close();

		echo '<div class="article container-fluid">
		<h4>'.EDIT_ARTICLE.': <span class="label label-info">'.$lang.'<span></h4>
		<div class="text col-md-8">
		<form actoin="" method="POST">
			<div class="panel panel-default panel-body">
		  		<input type="hidden" name="lang" value="'.$lang.'">
		  		<input type="hidden" name="id" value="'.$insert_id.'">
		  		<input type="submit" name="update_article" class="fa btn btn-success" value="'.SAVE.'">
                <input type="submit" name="update_and_new_article" class="fa btn btn-primary" value="'.SAVE_AND_NEW.'">
                <input type="submit" name="update_and_close_article" class="fa btn btn-warning" value="'.SAVE_AND_CLOSE.'">
                <input type="submit" name="close_update_article" class="fa btn btn-danger" value="'.CLOSE.'">
	  		</div>

			<div class="panel panel-default">
		    	<div class="panel-heading">'.CATEGORY.'</div>
		    	<div class="panel-body">
		    		<select class="fa form-control" id="category" name="categories">';
		    			ShowCategories($category_id);
					echo '</select>
				</div>
	  		</div>

	  		<input type="hidden" name="selectedImg" id="selectedImg" value="'.$image_id.'"/>

	  		<div class="panel panel-default">
	  		<div class="panel-heading">'.ARTICLE.'</div>
	  		<div class="panel-body">  		
				<div class="form-group">
                    <label>Оригинальное название</label>
                    <label class="fa form-control-static">'.$title_original.'</label>
                </div>
				<div class="form-group">
					<label for="category">'.TITLE.'</label>
					<input type="text" value="'.$title.'" class="form-control"  name="title">
				</div>
				<div class="form-group">
		  			<label for="intro">'.INTRO.'</label>
		  			<textarea class="form-control" rows="3" name="intro">'.$intro.'</textarea>
		  		</div>
		  		<div class="form-group">
		  			<label for="fulltext">'.FULL_TEXT.'</label>
		  			<textarea class="form-control" rows="3" name="fulltext">'.$fulltext.'</textarea>
		  		</div>
		  	</div>
	  		</div>
		</form>
	</div>
	<div class="image col-md-4">';
		upload_form($gallery_id);	
		uploaddoc_form();
	echo '</div>
</div>';
	}
?>
