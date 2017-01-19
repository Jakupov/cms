<?php
	header("Content-Type: text/html; charset=utf8");
	session_start();
	require_once("defines.php");
	require_once(DR . FILES . "classes.php");
	require_once(DR . FILES . "func.php");
	$lang = get_lang();
	switch ($lang) {
		case 'ru': require_once(DR . LANG . "ru.php"); break;
		case 'en': require_once(DR . LANG . "en.php"); break;
		default: require_once(DR . LANG . "kz.php"); break;
	}
	$main = new Main();
	load_content($main);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<base href="<?php echo SITE ?><?=$lang;?>/">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?=$main -> get_title();?></title>
		<style>@font-face{font-family:'FontAwesome';src:url('../fonts/fontawesome-webfont.eot?v=4.6.3');src:url('../fonts/fontawesome-webfont.eot?#iefix&v=4.6.3') format('embedded-opentype'), url('../fonts/fontawesome-webfont.woff2?v=4.6.3') format('woff2'), url('../fonts/fontawesome-webfont.woff?v=4.6.3') format('woff'), url('../fonts/fontawesome-webfont.ttf?v=4.6.3') format('truetype'), url('../fonts/fontawesome-webfont.svg?v=4.6.3#fontawesomeregular') format('svg');font-weight:normal;font-style:normal}.fa{display:inline-block;font:normal normal normal 14px/1 FontAwesome;font-size:inherit;text-rendering:auto;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.fa-search:before{content:"\f002"}.fa-navicon:before,.fa-reorder:before,.fa-bars:before{content:"\f0c9"}@font-face{font-family:'Uzor';src:url('/fonts/mirokuzor.woff?v=1.0') format('woff'), url('/fonts/mirokuzor.ttf?v=1.0') format('truetype');font-weight:normal;font-style:normal}.body{color:#000}.template{display:none}.template img{position:absolute;width:1170px;opacity:.5;top:0;left:50%;margin-left:-585px;z-index:-1;z-index:100}.img{max-width:100%}.top-menu li:last-child{border-right:none}.top-menu .menu li a:hover{text-decoration:none}.gallery{list-style:none;position:relative;overflow:hidden;padding:0}.gallery li{position:absolute;left:100%;width:100%;top:0;z-index:0;transition:left .2s ease-out}.gallery li.active{left:0;z-index:1}#partners{width:100%;height:150px}.mobile-icon{display:none}#logo{background:url(/images/logo/main.png) no-repeat left 21px top 28px;background-size:89px auto;height:162px;padding-top:81px;width:350px;text-transform:uppercase;margin-bottom:10px}#logo h4{text-align:right}#logo h4 span{display:block;font-size:14px;padding-bottom:2px}#logo .makhambet{font-size:22px;font-weight:bold}#logo a{color:#0d4c70;text-decoration:none}.lang li:last-child a{padding-right:0}.lang .active a{font-weight:bold}.search-module{float:right;margin-top:10px}.search-module input{height:33px;padding:5px 22px;border-radius:5px;border:none;outline:none;width:155px}.search-module button{background:#0d4c70;height:33px;width:33px;color:#fff;border-radius:5px;border:none;outline:none;padding:5px;margin:0 -5px 0 5px;font-size:1.3em}#slides{width:100%; height: 500px; margin-top: 20px;}.secondary-menu{background:#0d4c70;padding-bottom:182px}.secondary-menu .menu{display:table;width:100%}.secondary-menu li{display:table-cell;text-align:center}.secondary-menu .menu li a{width:100%;height:40px;line-height:40px;color:#fff;text-transform:uppercase;text-decoration:none}.main h4{text-transform:uppercase;font-size:14px;margin:0;padding:10px;background:#cb8000}.news{list-style:none;padding:0}.news img{width:100%}#events li,#videos li{background:#fff;margin-bottom:10px;color:#002f65;padding:10px}#events .desc,#videos .desc{color:#999;padding:5px 0}#events .desc .hits,#videos .desc .hits{float:right}#events a,#videos a{color:#002f65;text-transform:uppercase;text-decoration:underline;padding:5px 0}#slides{position:relative}#slideshow{list-style:none;padding:7px;border-radius:5px;margin:5px auto 0;border:1px solid #eee;width:934px;position:relative;background:#fff}#slideshow:after{content:"";display:block;position:absolute;width:100%;height:1px;background:transparent;bottom:0px;left:0;z-index:-1;border-radius:10%;}#slides li{float:left;position:relative;height:460px}#slides li a{display:block;width:100%;height:300px;color:#fff}#slides .slider-info{position:absolute;color:#fff;bottom:0;right:0;padding:15px}#slides .slider-info h3{text-shadow:1px 1px 2px #000;text-transform:uppercase;font-weight:bold;font-size:32px;width:521px}#slides .slider-info div{background:#0d4c70;padding:10px;text-indent:30px;width:485px;height:111px;overflow:hidden;font-style:italic;float:right}#slides img{height:460px;width:100%;border:1px solid #000}#slides .arrows{position:absolute;top:0;left:0;height:300px;width:100%}.uzor:before{font-family:Uzor;display:inline-block;width:100px;margin-left:-100px;font-size:350px;text-rendering:auto;position:absolute;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.uzor-left:before{content:"<"}.uzor-right:before{content:">"}#slides .slick-arrow{height:300px;width:0;top:-150px;color:#e7eef2;padding:0}#slides .slick-prev{left:-10px}#slides .slick-next{right:120px}#slides .buttons{margin:0 auto;width:81px;text-align:center;padding:17px 0}#slides .slick-dots li{display:block;border-radius:15px;border:1px solid #ddd;color:transparent;background:#cad7df;margin:0 5px;height:17px;width:17px;display:inline-block;box-shadow:inset 2px 2px 3px 0 #000}#slides .slick-dots{list-style:none;float:left;padding:0}#slides .slick-dots .slick-active{background:#fff}#slides .slick-dots button{background:transparent;border:none;outline:none}.breadcrumbs{list-style:none;padding:0;height:30px}.breadcrumbs li{float:left;padding:5px;height:30px}.breadcrumbs li a{color:#123e70}.breadcrumbs li:after{content:"\f0da";color:#123e70;margin-left:15px;font:normal normal normal 14px/1 FontAwesome;font-size:inherit;text-rendering:auto;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;display:inline-block}.breadcrumbs li:last-child:after{content:none}.categories{list-style:none;padding:0}.categories > li{height:200px;margin-bottom:30px}.categories > li > a{display:block;font-size:14px;font-weight:normal;padding:10px;background:#cb8000;color:#000;text-transform:uppercase}.articles{list-style:none;clear:both;padding:15px;background:#fff}.article{background:#fff;padding:5px 15px}.article .article-info{height:30px;padding:0 15px;line-height:30px;border-radius:5px;background:#f3efee}.article .article-category{float:left}.article .article-date{float:right}.article .article-body{padding:15px 0}.article .article-body p{text-indent:30px}.childarticles{padding:0;list-style:none;background:#fff;padding:10px 15px;margin-top:-10px;height:calc(100% - 44px);position:relative}.childarticles li{float:left}.childarticles img{display:block;height:130px;width:130px;margin:8px 15px 0 0;border-radius:5px;position:relative;float:left}.childarticles .slick-dots{position:absolute;list-style:none;bottom:15px;padding:0;right:15px}.childarticles .slick-dots li button{font-size:0;line-height:0;display:block;width:10px;height:10px;padding:5px;cursor:pointer;color:transparent;border:0;outline:none;background:#999;margin:0 2px;border-radius:5px}.childarticles .slick-dots li.slick-active button{background:#000}.slick-list{overflow:hidden}.photogallery{margin-right:-15px;margin-left:-15px}@media (max-width: 1200px){.uzor-left:before{margin-left:-200px}.uzor-right:before{margin-left:0}}</style>
		<link rel="icon" type="image/png" href="/tmpl/<?=TEMPLATE?>favicon.png" />
		<script src="<?php echo SITE ?>js/jquery-3.1.0.min.js" defer></script>
		<script src="<?php echo SITE ?>js/bootstrap.min.js" defer></script>
		<script src="<?php echo SITE ?>js/slick.min.js" defer></script>
		<script src='https://www.google.com/recaptcha/api.js' defer></script>
		<script src="<?php echo SITE ?>tmpl/<?=TEMPLATE?>js/main.js" defer></script>
	</head>
	<body onload="init();" class="body">
		<?php
			require_once(DR . 'tmpl/' . TEMPLATE . 'index.php');
		?>
		<link rel="stylesheet" type="text/css" href="<?php echo SITE ?>css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo SITE ?>css/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo SITE ?>tmpl/<?=TEMPLATE?>css/style.css">
	</body>
</html>
