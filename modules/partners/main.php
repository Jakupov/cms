<?php 
	$mysqli = connect();
	$banners = get_images_list("partners/");
	$urls = get_images_links("partners/");
	$div = '<div class="module"><h3>'.PARTNERS.'</h3>';
	$div .= '<ul id="partners" class="gallery">';
	$i = 0;
	foreach ($banners as $b) {
		if (isset($urls[$b])) {
			$i++;
			$div .= ($i == 1) ? '<li class="active">' : '<li>';
			
			$div .= '<a href="'.$urls[$b].'" title="">';
			$div .= '<img src="/'.IMG. 'partners/'.$b.'" alt="">';
			$div .= '</a>';
			$div .= '</li>';
		}
	}
	$div .= '</ul></div>';
	echo $div;
?>