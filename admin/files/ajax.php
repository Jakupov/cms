<?php
	require_once $_SERVER['DOCUMENT_ROOT'].'/admin/files/func.php';
	session_start();
	if (isset($_GET['action'])) {
		switch ($_GET['action']) {
			case 'sort':
				$conn = connect();
				$query = "UPDATE menus SET sort_order=? WHERE id=?";
				if ($stmt = $conn -> prepare($query)){
					$i = 1;
					foreach ($_POST['item'] as $value) {
					    $stmt -> bind_param("ii", $i, $value);
					    $stmt -> execute();
					    $i++;
					}
					$stmt -> close();
				}
				echo REORDERED;
				break;
			case 'menu_type':
				echo ShowMenuCategories($_POST['menu_type'],1);
				break;
			case 'setArticles':
				echo set_articles($_POST['selected_category'],$_POST['search_text']);
				break;
			case 'link_type':
				switch ($_POST['link_type']) {
		            case 1:
		                setArticle();
		                break;
		            case 2:
		                setHref(); 
		                break;
		            case 3:
		                setCategory(); 
		                break;
		            case 4:
		                setModule(); 
		                break;
		            case 5:
		                setBlog(); 
		                break;
	        	}
				break;
			case 'menu':
				if (isset($_GET['update'])&&($_GET['update']=='list')) {
					if (isset($_SESSION['current'])) $category = $_SESSION['current'];
					else $category = 1;
					echo list_menus($category,$_POST['search_text'],$_POST['state'],$_POST['menu_type']);
				}
				else {
					echo ShowMenuCategories($_POST['menu_type'],1);
				}
				break;
			case 'delete':
				switch ($_POST['view']) {
					case 'articles':
						$conn = connect();
						$query = "UPDATE contents SET state=? WHERE id=?";
						if ($stmt = $conn -> prepare($query)) {
							$stmt -> bind_param("ii", $_POST['delete'], $_POST['id']);
							$stmt -> execute();
							$stmt -> fetch();
						}
						$stmt -> close();
						echo list_articles($_POST['selected_category'],$_POST['search_text'],$_POST['state']);
						break;
					case 'categories':
						$conn = connect();
				        $query = "UPDATE categories SET state=? WHERE id=?";
				        if ($stmt = $conn -> prepare($query)) {
				            $stmt -> bind_param("ii", $_POST['delete'], $_POST['id']);
				            $stmt -> execute();
				            $stmt -> fetch();
				        }
				        $stmt -> close();
				        echo list_categories($_POST['selected_category'],$_POST['search_text'],$_POST['state']);
						break;
					case 'menus':
						$conn = connect();
				        $query = "UPDATE menus SET state=? WHERE id=?";
				        if ($stmt = $conn -> prepare($query)) {
				            $stmt -> bind_param("ii", $_POST['delete'], $_POST['id']);
				            $stmt -> execute();
				            $stmt -> fetch();
				        }
				        $stmt -> close();
				        echo list_menus($_POST['selected_category'],$_POST['search_text'],$_POST['state']);
						break;
				}
				break;
			case 'group_delete':
				$ids = $_POST['ids'];
				switch ($_POST['view']) {
					case 'articles':
						$conn = connect();
						$query = "UPDATE contents SET state=? WHERE id=?";
						if ($stmt = $conn -> prepare($query)) {
							foreach ($ids as $id) {
								$stmt -> bind_param("ii", $_POST['delete'], $id);
								$stmt -> execute();
							}
						}
						$stmt -> close();
						echo list_articles($_POST['selected_category'],$_POST['search_text'],$_POST['state']);
						break;
					case 'categories':
						$conn = connect();
				        $query = "UPDATE categories SET state=? WHERE id=?";
				        if ($stmt = $conn -> prepare($query)) {
				        	foreach ($ids as $id) {
					            $stmt -> bind_param("ii", $_POST['delete'], $id);
					            $stmt -> execute();
				        	}
				        }
				        $stmt -> close();
				        echo list_categories($_POST['selected_category'],$_POST['search_text'],$_POST['state']);
						break;
					case 'menus':
						$conn = connect();
				        $query = "UPDATE menus SET state=? WHERE id=?";
				        if ($stmt = $conn -> prepare($query)) {
				        	foreach ($ids as $id) {
					            $stmt -> bind_param("ii", $_POST['delete'], $id);
					            $stmt -> execute();
				        	}
				        }
				        $stmt -> close();
				        echo list_menus($_POST['selected_category'],$_POST['search_text'],$_POST['state']);
						break;
				}
				break;
		}
	}

	elseif(isset($_POST['view'])){
		switch ($_POST['view']) {
			case 'articles':
				$_SESSION['current_c']=$_POST['selected_category'];
				echo list_articles($_POST['selected_category'],$_POST['search_text'],$_POST['state']);
				break;
			case 'categories':
				$_SESSION['current_c']=$_POST['selected_category'];
				echo list_categories($_POST['selected_category'],$_POST['search_text'],$_POST['state']);
				break;
			case 'menus':
				$_SESSION['current']=$_POST['menu_categories'];
				echo list_menus($_POST['menu_categories'],$_POST['search_text'],$_POST['state'],$_POST['menu_type']);
				break;
		}
	}
?>