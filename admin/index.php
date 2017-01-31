<?php 
	header('Content-Type: text/html; charset=utf-8');
	session_start();
	require_once($_SERVER['DOCUMENT_ROOT']."/admin/defines.php");
	require_once(ADMIN . FILES . "func.php");
	if (isset($_POST['signin'])) {
		check_auth();
		header("Location: /admin/"); exit;
	}
	if (isset($_GET['logout'])) {
		logout();
	}
	$auth = authorized();
	if ($auth) {
		require_once(ADMIN . FILES . "checks.php");
	}
	
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?=load_title();?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo SITE ?>admin/css/jquery-ui.css">
	<link rel="stylesheet" href="<?php echo SITE ?>css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo SITE ?>css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo SITE ?>admin/css/style.css?s=<?php echo rand(); ?>">
	<script src="<?php echo SITE ?>js/jquery-3.1.0.min.js"></script>
	<script src="<?php echo SITE ?>js/jquery-ui.min.js"></script>
	<script src="<?php echo SITE ?>admin/components/tinymce/tinymce.min.js"></script>
  	<script>tinymce.init({ 
  		selector:'textarea',
  		plugins: "code link"
  		});
  	</script>
  	<script src="<?php echo SITE ?>admin/js/func.js?k=<?php echo rand(); ?>"></script>
</head>
<body>
	<div class="wrapper">
	<?php
		if ($auth) {
			admin();
		}
		else {
			echo login_form(); 
		}
	?>
	</div>
<script src="<?php echo SITE ?>admin/js/script.js?v=<?php echo rand() ?>"></script>	
</body>
</html>
