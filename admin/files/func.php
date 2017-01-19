<?php 
	require_once $_SERVER['DOCUMENT_ROOT'].'/admin/defines.php';
	require_once ADMIN.FILES.'article.func.php';
	require_once ADMIN.FILES.'category.func.php';
	require_once ADMIN.FILES.'menu.func.php';
	require_once ADMIN.FILES.'images.func.php';
	require_once ADMIN.FILES.'documents.func.php';
	
	function load_title(){
		return "OK";
	}

	function connect(){
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if (mysqli_connect_errno()){
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
		$mysqli -> set_charset("utf8");
		return $mysqli;
	}

	function login_form(){
		$form = '<div class="login-form">';
		$form .= '<form method="post" action="">';
		$form .= '<div class="form-group"><label for="login">'.LOGIN.'</label><input class="form-control" type="text" id="login" name="login"></div>';
		$form .= '<div class="form-group"><label for="pass">'.PASS.'</label><input class="form-control" type="password" id="pass" name="pass"></div>';
		$form .= '<div class="form-group"><input class="form-control btn btn-danger" type="submit" name="signin" value="'.SIGN_IN.'"></div>';
		$form .= '</form>';
		$form .= '</div>';
		return $form;
	}

	function check_auth(){
		$p = $_POST;
		$mysqli = connect();
		$query = $mysqli -> prepare("SELECT id, password, salt FROM users WHERE login = ?");
		$query -> bind_param("s", $p['login']);
		$query -> bind_result($uid, $upass, $usalt);
		$query -> execute();
		if ($query -> fetch() && crypt($p['pass'], $usalt) === $upass){
			$hash = generate_code();
			$mysqli -> close();
			$mysqli = connect();
			$tmp = $mysqli -> prepare("UPDATE users SET hash = ? WHERE id = ?");
			$tmp -> bind_param("si", $hash, $uid);
			$tmp -> execute();
			$_SESSION['id'] = $uid;
			$_SESSION['hash'] = $hash;
			return true;
		}
		else return false;
	}

	function generate_code($size = 10) {
		$str = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
		$result = "";
		for ($i = 0; $i < $size; $i++) {
			$result .= substr($str,rand(0,strlen($str)-1),1);
		}
		return $result;
	}	
		
	function logout() {
		session_destroy();
		header("Location: ".SITE."admin/"); exit;
	}

	function authorized() {
		$res = false;
		if (isset($_SESSION['hash'])) {
			$hash = $_SESSION['hash'];
			$mysqli = connect();
			$query = $mysqli -> prepare("SELECT id FROM users 
										WHERE hash = ? ");
			$query -> bind_param("s", $hash);
			$query -> bind_result($id);
			$query -> execute();
			if ($query -> fetch()) {
				$res = true;
			}
		}
		return $res;
	}

	function admin(){
		$menu = '<nav class="menu navbar navbar-default" role="navigation">
			<ul class="mlevel-1">
				<li class="level-1"><a href="'.SITE.'admin/categories">'.CATEGORIES.'</a></li>
				<li class="level-1"><a href="'.SITE.'admin/articles">'.ARTICLES.'</a></li>
				<li class="level-1"><a href="'.SITE.'admin/menu">'.MENUS.'</a></li>
				<li class="level-1"><a href="'.SITE.'admin/modules">'.MODULES.'</a></li>
				<li class="level-1"><a href="'.SITE.'admin/galleries">'.GALLERIES.'</a></li>
				<li class="level-1"><a href="'.SITE.'admin/components">'.COMPONENTS.'</a></li>
			</ul>
			<div class="version">Version: '.VERSION.' </div>
		</nav>';
		echo $menu;
		if (isset($_GET['view'])){
			switch ($_GET['view']){
				case 'articles':
					load_articles();
					break;
				case 'menu':
					load_menus();
					break;
				case 'categories':
					load_categories();
					break;
				case 'modules':
					break;
			}
		}
	}

?>