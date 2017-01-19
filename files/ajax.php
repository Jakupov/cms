<?php 
	$result = "";
	if (isset($_GET['action'])) {
		include("../defines.php");
		include("func.php");
		switch ($_GET['action']) {
			case 'blogcomment': $result = blog_comment($_POST); break;
			case 'blogcomments': $result = get_post_comments($_POST['id'],$_POST['total'],$_POST['page']); break;
		}
	}
	echo $result;
?>