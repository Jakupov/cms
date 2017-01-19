<?php
	$mysqli = connect();
	$folder = "database/";
	$banners = get_images_list($folder);
	$urls = get_images_links($folder);
	$div = '<div class="module carousel clearfix hidden-xs hidden-sm">';
	$div .= '<div class="carousel-wrapper"><ul id="database" class="carousels hidden">';
	$i = 0;
	foreach ($banners as $b) {
		if (isset($urls[$b])) {
			$i++;
			$div .= '<li>';
			$div .= '<a href="'.$urls[$b].'">';
			$div .= '<img src="/'.IMG. $folder.$b.'" alt="">';
			$div .= '</a>';
			$div .= '</li>';
		}
	}
	$div .= '</ul></div></div>';
	echo $div;
?>
