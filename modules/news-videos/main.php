<?php
	$lang = get_lang();
	$news = load_news(array(102,103,104,105,106,107),5);
	$div = '<div class="module"><h4>'.VIDEOS.'</h4>';
	$div .= '<ul class="news" id="videos">';
	foreach($news as $n) {
		$div .= '<li>';
		$div .= '<a href="'.NEWSURL.'/?option=com_content&view=article&id='.$n['id'].'">'.$n['title'].'</a>';
		if (isset($n['image']) && strlen($n['image']) > 0) {
			$div .= '<img src="'.NEWSURL.'/'.$n['image'].'" alt="'.PICTURE.$n['title'].'">';
		} else {
			$div .= '<img src="/images/videos.jpg" alt="'.PICTURE.$n['title'].'">';
		}
		$div .= '<div class="intro"><div class="desc">'.$n['created'].'<div class="hits"><span class="fa fa-eye"></span> '.$n['hits'].'</div></div></div>';
		$div .= '</li>';
	}
	$div .= '</ul></div>';
	echo $div;
?>
