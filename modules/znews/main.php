<?php
	$lang = get_lang();
	$news = load_news(array(90,91,92,93,94,95,123,124,126,127,128,129),10);
	$div = '<div class="module">';
	$div .= '<ul class="news" id="events">';
	foreach($news as $n) {
		$div .= '<li>';
		$div .= '<a href="/kz/article/'.$n['id'].'">'.$n['title'].'</a>';
		$div .= '<p>'.$n['intro'].'</p>';
		$div .= '</li>';
	}
	$div .= '</ul></div>';
	echo $div;
?>
