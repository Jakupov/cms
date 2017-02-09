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
  		plugins: "code link",
  		theme: "modern",
    paste_data_images: true,
    plugins: [
      "advlist autolink lists link image charmap print preview hr anchor pagebreak",
      "searchreplace wordcount visualblocks visualchars code fullscreen",
      "insertdatetime media nonbreaking save table contextmenu directionality",
      "emoticons template paste textcolor colorpicker textpattern"
    ],
    toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
    toolbar2: "print preview media | forecolor backcolor emoticons",
    image_advtab: true,
    file_picker_callback: function(callback, value, meta) {
		      if (meta.filetype == 'image') {
		        $('#upload').trigger('click');
		        $('#upload').on('change', function() {
		          var file = this.files[0];
		          var reader = new FileReader();
		          reader.onload = function(e) {
		            callback(e.target.result, {
		              alt: ''
		            });
		          };
		          reader.readAsDataURL(file);
		        });
		      }
		    }
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
