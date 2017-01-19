<?php 
	$uri = $_SERVER['REQUEST_URI'];
	$div = '<ul class="lang">';
	$lang = get_lang();
	if (stripos($uri, "/kz/") === 0 || stripos($uri, "/ru/") === 0 ||stripos($uri, "/en/") === 0) {
		$url = substr($uri, 3);
	} else {
		$url = $uri;
	}
	$div .= '<li';
	if ($lang == 'kz') {
		$div .= ' class="active"';
	}
	$div .= '><a href="/kz'.$url.'">Қазақша </a></li>';

	$div .= '<li';
	if ($lang == 'ru') {
		$div .= ' class="active"';
	}
	$div .= '><a href="/ru'.$url.'">Русский </a></li>';

	$div .= '<li';
	if ($lang == 'en') {
		$div .= ' class="active"';
	}
	$div .= '><a href="/en'.$url.'">English</a></li>';
	$div .= '</ul>';
	echo $div;
?>