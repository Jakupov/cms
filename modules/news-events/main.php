<?php
	$lang = get_lang();
	$news = load_news(array(90,91,92,93,94,95,123,124,126,127,128,129),4);
	$div = '<div class="module"><h4>'.EVENTS.'</h4>';
	$div .= '<ul class="news" id="events">';
	foreach($news as $n) {
		$div .= '<li>';
		$div .= '<a href="'.NEWSURL.'/?option=com_content&view=article&id='.$n['id'].'">'.$n['title'].'</a>';
		if (isset($n['image']) && strlen($n['image']) > 0) {
			$div .= '<img src="'.NEWSURL.'/'.$n['image'].'" alt="'.PICTURE.$n['title'].'">';
		} else {
			$div .= '<img src="/images/news.jpg" alt="'.PICTURE.$n['title'].'">';
		}
		$div .= '<div class="intro">'.$n['intro'].'<div class="desc">'.$n['created'].'<div class="hits"><span class="fa fa-eye"></span> '.$n['hits'].'</div></div></div>';
		$div .= '</li>';
	}
	$div .= '</ul></div>';
	echo $div;
?>
