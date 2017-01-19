<?php
	$mysqli = connect();
	$folder = "slider/";
	$banners = get_images_list($folder);
	//$urls = get_images_links($folder);
	$titles = get_images_titles($folder);
	$intros = get_images_intros($folder);
	$div = '<div id="slides" class="module hidden-xs hidden-sm">';
	$div .= '<ul id="slideshow" class="hidden">';
	$i = 0;
	foreach ($banners as $b) {
		if (@($titles[$b])) {
			$div .= '<li>';
			$div .= '<div class="slider-info">';
			$div .= '<h3>'.$titles[$b].'</h3>';
			$div .= '<div>'.$intros[$b].'</div>';
			$div .= '</div>'; //slider-info;
			$div .= '<img src="'.SITE.IMG. $folder.$b.'" alt="">';
			$div .= '</li>';
		}
	}
	$div .= '</ul>';

	$div .= '<div class="arrows"></div>';
	$div .= '<div class="buttons"></div>';
	$div .= '</div>';
	echo $div;
?>
