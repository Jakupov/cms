<?php
	$mysqli = connect();
	$folder = "links/";
	$banners = get_images_list($folder);
	$urls = get_images_links($folder);
	$div = '<div class="module carousel clearfix hidden-xs hidden-sm">';
	$div .= '<div class="carousel-wrapper"><ul id="links" class="hidden carousels">';
	$i = 0;
	foreach ($banners as $b) {
		if (isset($urls[$b])) {
			$i++;
			$div .= '<li>';
			$div .= '<a href="'.$urls[$b].'"><img src="'.SITE.IMG. $folder.$b.'" alt="" class="hidden-xs"></a>';
			$div .= '</li>';
		}
	}
	$div .= '</ul></div></div>';
	echo $div;
?>
