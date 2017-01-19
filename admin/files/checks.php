<?php
	//articles
	if(isset($_POST['save_article'])){
		$insert_id = save_article($_POST);
		if ($insert_id>0){
			header("location: /admin/articles/edit/".$insert_id."/kz");
			exit;
		}
		else echo NOT_SAVED;
	}
	if(isset($_POST['save_and_new_article'])){
		$insert_id = save_article($_POST);
		if ($insert_id>0){
			header("location: /admin/articles/new");
			exit;
		}
		else echo NOT_SAVED;
	}
	if(isset($_POST['save_and_close_article'])){
		$insert_id = save_article($_POST);
		if ($insert_id>0){
			header("location: /admin/articles");
			exit;
		}
		else echo NOT_SAVED;
	}
	if(isset($_POST['close_article'])){
		header("location: /admin/articles");
		exit;
	}

	if(isset($_POST['update_article'])){
		$insert_id = update_article($_POST);
		if ($insert_id>0){
			header("location: /admin/articles/edit/".$insert_id."/".$_POST['lang']);
			exit;
		}
		else echo NOT_SAVED;
	}
	if(isset($_POST['update_and_new_article'])){
		$insert_id = update_article($_POST);
		if ($insert_id>0){
			header("location: /admin/articles/new");
			exit;
		}
		else echo NOT_SAVED;
	}
	if(isset($_POST['update_and_close_article'])){
		$insert_id = update_article($_POST);
		if ($insert_id>0){
			header("location: /admin/articles");
			exit;
		}
		else echo NOT_SAVED;
	}
	if(isset($_POST['close_update_article'])){
		header("location: /admin/articles");
		exit;
	}

	if(isset($_POST['new_article'])){
		header("location: /admin/articles/new");
		exit;
	}

	//categories
	if(isset($_POST['save_category'])){
		$insert_id = save_category($_POST);
		if ($insert_id>0){
			header("location: /admin/categories/edit/".$insert_id."/kz");
			exit;
		}
		else echo NOT_SAVED;
	}
	if(isset($_POST['save_and_new_category'])){
		$insert_id = save_category($_POST);
		if ($insert_id>0){
			header("location: /admin/categories/new");
			exit;
		}
		else echo NOT_SAVED;
	}
	if(isset($_POST['save_and_close_category'])){
		$insert_id = save_category($_POST);
		if ($insert_id>0){
			header("location: /admin/categories");
			exit;
		}
		else echo NOT_SAVED;
	}
	if(isset($_POST['close_category'])){
		header("location: /admin/categories");
		exit;
	}

	if(isset($_POST['update_category'])){
		$insert_id = update_category($_POST);
		if ($insert_id>0){
			header("location: /admin/categories/edit/".$insert_id."/".$_POST['lang']);
			exit;
		}
		else echo NOT_SAVED;
	}
	if(isset($_POST['update_and_new_category'])){
		$insert_id = update_category($_POST);
		if ($insert_id>0){
			header("location: /admin/categories/new");
			exit;
		}
		else echo NOT_SAVED;
	}
	if(isset($_POST['update_and_close_category'])){
		$insert_id = update_category($_POST);
		if ($insert_id>0){
			header("location: /admin/categories");
			exit;
		}
		else echo NOT_SAVED;
	}
	if(isset($_POST['close_update_category'])){
		header("location: /admin/categories");
		exit;
	}

	if(isset($_POST['new_category'])){
		header("location: /admin/categories/new");
		exit;
	}

	//menus
	if(isset($_POST['new_menu'])){
		header("location: /admin/menu/new");
		exit;
	}
	if(isset($_POST['save_menu'])){
		$insert_id = save_menu($_POST);
		if ($insert_id>0){
			header("location: /admin/menu/edit/".$insert_id."/kz");
			exit;
		}
		else echo NOT_SAVED;
	}
	if(isset($_POST['save_and_new_menu'])){
		$insert_id = save_menu($_POST);
		if ($insert_id>0){
			header("location: /admin/menu/new");
			exit;
		}
		else echo NOT_SAVED;
	}
	if(isset($_POST['save_and_close_menu'])){
		$insert_id = save_menu($_POST);
		if ($insert_id>0){
			header("location: /admin/menu");
			exit;
		}
		else echo NOT_SAVED;
	}
	if(isset($_POST['close_menu'])){
		header("location: /admin/menu");
		exit;
	}
	if(isset($_POST['update_menu'])){
		$insert_id = update_menu($_POST);
		if ($insert_id>0){
			header("location: /admin/menu/edit/".$insert_id."/".$_POST['lang']);
			exit;
		}
		else echo NOT_SAVED;
	}
	if(isset($_POST['update_and_new_menu'])){
		$insert_id = update_menu($_POST);
		if ($insert_id>0){
			header("location: /admin/menu/new");
			exit;
		}
		else echo NOT_SAVED;
	}
	if(isset($_POST['update_and_close_menu'])){
		$insert_id = update_menu($_POST);
		if ($insert_id>0){
			header("location: /admin/menu");
			exit;
		}
		else echo NOT_SAVED;
	}
	if(isset($_POST['close_update_menu'])){
			header("location: /admin/menu");
			exit;
	}
?>