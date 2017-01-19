<?php
	$lang = get_lang();
	$news = load_news(array(4),100);
	$div = '<ol id="normatives">';
	foreach($news as $n) {
		$div .= '<li>';
		$div .= '<a href="/kz/article/'.$n['id'].'">'.$n['title'].'</a>';
		$div .= '<p>'.$n['intro'].'</p>';
		$div .= '</li>';
	}
	$div .= '</ol>';
	echo $div;
?>