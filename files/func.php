<?php
	require_once(dirname(__FILE__) . "/config.php");
	function get_title() {
		return "NaN";
	}
	function build_menu($cats,$parent_id,$l,$only_parent = false){
		$lang = get_lang();
		if(is_array($cats) and isset($cats[$parent_id])){
			$tree = '<ul class="menu level'.$l.'">';
			if($only_parent==false){
				foreach($cats[$parent_id] as $cat){
					if ($parent_id==1) {
						$active_link = $cat['id'];
					}
					else $active_link = $parent_id;
					if ($cat['type'] == 2) {
						$active_link = "";
					} else {
						$active_link = "/".$active_link;
					}
					if (isset($_GET['menuid']) && $_GET['menuid']==$cat['id']) {
						$tree .= '<li class="active"><a href="'.$cat['link'].$active_link.'">'.$cat['title'].'</a>';
					}
					else $tree .= '<li><a href="'.$cat['link'].$active_link.'">'.$cat['title'].'</a>';
					$tree .=  build_menu($cats,$cat['id'],$l+1);
					$tree .= '</li>';
				}
			}elseif(is_numeric($only_parent)){
				$cat = $cats[$parent_id][$only_parent];
					$tree .= '<li><a href="'.$cat['link'].'">'.$cat['title'].'</a>';
				$tree .=  build_menu($cats,$cat['id'],$l+1);
				$tree .= '</li>';
			}
		    $tree .= '</ul>';
		}
		else return null;
		return $tree;
	}
	function load_menu($mt) {
		$conn = connect();
		$query="SELECT id, title_kz, title_ru, title_en, parent_id, link, image_id, item_type FROM menus WHERE menu_type = ? AND state=1 ORDER BY sort_order";
		if ($stmt = $conn->prepare($query)) {
			$stmt -> bind_param("s", $mt);
			$stmt->bind_result($id,$title['kz'],$title['ru'],$title['en'],$parent_id, $link, $img_id, $it);
			$stmt->execute();
			$cats = array();
			while ($stmt->fetch()) {
				$cats[$parent_id][$id]['id']=$id;
				$cats[$parent_id][$id]['title']=$title[get_lang()];
				$cats[$parent_id][$id]['parent_id']=$parent_id;
				$cats[$parent_id][$id]['link']=$link;
				$cats[$parent_id][$id]['type']=$it;
				$cats[$parent_id][$id]['img_id']=$img_id;
			}
			$cat = build_menu($cats,1,1);
			echo $cat;
		}
	}

	function get_lang() {
		$lang = 'kz';
		if (isset($_GET['lang'])) {
			switch($_GET['lang']) {
				case 'ru': $lang = 'ru'; break;
				case 'en': $lang = 'en'; break;
			}
		}
		return $lang;
	}

	function load_module($name, $main = null) {
		if (isset($main)) {
			switch ($name) {
				case 'virtual': $title = VIRTUALTOUR; break;
				case 'map': $title = MAP; break;
				default: return load_404($main); break;
			}
				$main -> set_title($title .' | '.SITENAME);
				return file_get_contents(DR . MOD . $name . "/main.php");
		} else
		include(DR . MOD . $name . "/main.php");
	}

	function load_category_children($parent_id) {
		$mysqli = connect();
		$lang = get_lang();
		$sql = "SELECT c.id, c.title_$lang, a.id, a.title_$lang, i.preview
				FROM categories c
				LEFT JOIN contents a ON c.id = a.category_id AND a.state = 1
				LEFT JOIN images i ON a.image_id = i.id
				WHERE c.parent_id = ? AND c.state = 1 ORDER BY c.sort_order, c.id, a.created_date DESC ";
		$query = $mysqli -> prepare($sql);
		$query -> bind_param("i", $parent_id);
		$query -> bind_result($id, $title, $aid, $atitle, $aimage);
		$query -> execute();
		$cc = 0; //Current Category
		$i = 0;
		$j = 0;
		$div = '<ul class="categories">';
		while ($query -> fetch()) {
			if ($cc != $id) {
				if ($i > 0) {
					if ($j > 0) {
						$div .= '</ul>';
					}
					$div .= '</li>';
				}
				$div .= '<li class="col-xs-12 col-md-sm-12 col-md-6 col-lg-4"><a class="h4" href="/'.$lang.'/category/'.$id.'">'.$title.'</a>';
				$cc = $id;
				$i++;
				$j = 0;
				if (isset($aid)) {
					$div .= '<ul class="childarticles hidden">';
					$j++;
				}
			}
			if (isset($aid)) {
				$div .= '<li>';
				if (isset($aimage)) {
					$div .= '<img src="'.$aimage.'">';
				} else {
					$div .= '<img src="/images/logo.png">';
				}
				$div .= '<a href="/'.$lang.'/article/'.$aid.'">'.$atitle.'</a></li>';
			}
		}
		if ($i > 0) {
			if ($j > 0) {
				$div .= '</ul>';
			}
			$div .= '</li>';
		}
		$div .= '</ul>';
		if ($cc > 0) {
			return $div;
		} else {
			return "";
		}
	}

	function load_articles($cat_id, $children) {
		$mysqli = connect();
		if ($children) {

		} else {
			$sql = "SELECT a.id, a.title_kz, a.title_ru, a.title_en,
					i.preview
					FROM contents a
					LEFT JOIN images i ON a.image_id = i.id
					WHERE a.category_id = ?";
		}
		$query = $mysqli -> prepare($sql);
		$query -> bind_param("i", $cat_id);
		$query -> bind_result($aid, $title['kz'], $title['ru'], $title['en'], $preview);
		$query -> execute();
		$res = '';
		$lang = get_lang();
		$i = 0;
		while ($query -> fetch()) {
			$img = "";
			if (isset($preview)) {
				$img = '<img src="'.$preview.'" alt="'.$title[$lang].'">';
			}
			$res .= '<li><a href="/'.$lang.'/article/'.$aid.'">'.$img.$title[$lang].'</a></li>';
			$i++;
		}
		if ($i > 0) {
			$res = '<ul class="articles">'.$res.'</ul>';
		}
		return $res;
	}

	function load_404($main) {
		$main -> set_title(E404 . " | ". SITENAME);
		return "404";
	}

	function load_category($id, $main, $params = null) {
		$lang = get_lang();
		$res = "";
		$mysqli = connect();
		$sql = "SELECT title_$lang, params FROM categories WHERE id = ? AND state = 1";
		$query = $mysqli -> prepare($sql);
		$query -> bind_param("i", $id);
		$query -> bind_result($title, $p);
		$query -> execute();
		if ($query -> fetch()) {
			$main -> set_title($title .' | '.SITENAME);
			if (!is_array($params)) {
				$params = unserialize($p);
			}
			$res .= load_parents_list($id);
			if (is_array($params)) {
				if (isset($params['child_categories']) && $params['child_categories']) {
					$res .= load_category_children($id);
				}
				if (isset($params['child_articles']) && $params['child_articles']) {
					$res .= load_articles($id, true);
				}
			} else {
				$res .= load_category_children($id);
				$res .= load_articles($id, false);
			}
		} else {
			$res .= load_404($main);
		}
		return $res;
	}

	function load_article($id, $main) {
		$lang = get_lang();
		$mysqli = connect();
		$sql = "SELECT a.id, a.title_$lang,
							 a.intro_$lang,
							 a.fulltext_$lang,
							 c.title_$lang,
							 a.category_id, a.created_date, a.image_id,
							 i.url, i.gallery_id, i.caption_$lang
				FROM contents a
				LEFT JOIN categories c ON c.id = a.category_id
				LEFT JOIN images i ON a.image_id = i.id
				WHERE a.id = ? AND a.state = 1";
		$query = $mysqli -> prepare($sql);
		$query -> bind_param("i", $id);
		$query -> bind_result($aid, $at, $ai, $af, $ct, $cid, $cdate, $img, $iurl, $igallery, $icaption);
		$query -> execute();
		if ($query -> fetch()) {
			$main -> set_title($at .' | '.SITENAME);
			$div = load_parents_list($cid);
			$div .= '<article class="article">';
			$div .= '<h2 class="article-title">'.$at.'</h2>';
			$date = date('d.m.Y', strtotime($cdate));
			$share_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
			$div .= '<div class="article-info">

						<div class="share-buttons">
						    <a href="http://www.facebook.com/sharer.php?u='.$share_link.'" target="_blank">
						        <img src="images/social/fb.png" alt="Facebook" />
						    </a>
						    <a href="https://plus.google.com/share?url='.$share_link.'" target="_blank">
						        <img src="images/social/gg.png" alt="Google" />
						    </a>
						    <a href="https://twitter.com/share?url='.$share_link.'&amp;hashtags=wksukz,phimwksukz" target="_blank">
						        <img src="images/social/tw.png" alt="Twitter" />
						    </a>
						    <a href="http://vkontakte.ru/share.php?url='.$share_link.'" target="_blank">
						        <img src="images/social/vk.png" alt="VK" />
						    </a>
						</div>
						<div class="article-category">
							<a href="/'.$lang.'/category/'.$cid.'">'.$ct.'</a>
						</div>
						<div class="article-date">
							<span>'.$date.'</span>
						</div>
					</div>';
			$div .= '<div class="article-body">';
			if ($img > 0) {
				if ($igallery > 0) {
					$div .= '<a href="/'.$lang.'/gallery/'.$igallery.'">';
					$div .= '<figure>';
					$div .= '<img src="'.$iurl.'" alt="">';
					if (isset($icaption) && strlen($icaption) > 0) {
						$div .= '<figcation>'.$icaption.'</figcaption>';
					}
					$div .= '</figure>';
					$div .= '</a>';
				} else {
					$div .= '<figure>';
					$div .= '<img src="'.$iurl.'" alt="">';
					if (isset($icaption) && strlen($icaption) > 0) {
						$div .= '<figcation>'.$icaption.'</figcaption>';
					}
					$div .= '</figure>';
				}
			}
			$div .= $ai.$af;
			$div .= '</div>';
			$div .= '</article>';
			return $div;
		} else {
			return load_404($main);
		}
	}

	function load_gallery($id, $main) {
		$lang = get_lang();
		$mysqli = connect();
		$sql = "SELECT g.id, g.title_$lang,
				i.url, i.caption_$lang
				FROM galleries g
				LEFT JOIN images i ON i.gallery_id = g.id
				WHERE g.id = ? AND g.id > 0";
		$query = $mysqli -> prepare($sql);
		$query -> bind_param("i", $id);
		$query -> bind_result($gid, $gtitle, $url, $icaption);
		$query -> execute();
		$i = 0;
		$div = '';
		while ($query -> fetch()) {
			$i++;
			if ($i == 1) {
				$main -> set_title($gtitle .' | '.SITENAME);
				$div .= '<article class="photogallery">';
				$div .= '<h2>'.$gtitle.'</h2>';
				$div .= '<div id="slides">';
				$div .= '<ul id="slideshow" class="hidden">';
			}
			$div .= '<li><figure>';
			$div .= '<img src="'.$url.'" alt="">';
			if (isset($icaption) && strlen($icaption) > 0) {
				$div .= '<figcation>'.$icaption.'</figcaption>';
			}
			$div .= '</figure></li>';
		}
		if ($i > 0) {
			$div .= '</ul><div class="arrows"></div><div class="buttons"></div></div>';
			$div .= '</article>';
			return $div;
		}else {
			return load_404($main);
		}
	}

	function load_main($main) {
		$main -> set_title(SITENAME);
		$main -> set_view("main");
		return load_category(2,$main);
	}

	function get_images_list($folder) {
		$path = DR . IMG . $folder;
		$res = array();
		$images = array('jpg', 'jpeg', 'png', 'svg', 'gif');
		$i = 0;
		if ($files = scandir($path)) {
			foreach ($files as $f) {
				$ext = substr($f, strrpos($f, '.') + 1);
				if (in_array($ext, $images)) {
					$res[$i]=$f;
					$i++;
				}
			}
		}
		return $res;
	}

	function get_post_comments($bpid, $total, $page = 1) {
		$mysqli = connect();
		$limit = ($page - 1) * PERPAGE;
		$pp = PERPAGE;
		$sql = "SELECT id, title, username, comment, comment_date, answer, answer_date
				FROM blog_comments
				WHERE blogpost_id = ? AND state = 1
				ORDER BY comment_date DESC LIMIT ?,?";
		$query = $mysqli -> prepare($sql);
		$query -> bind_param("iii", $bpid, $limit, $pp);
		$query -> bind_result($cid, $ctitle, $username, $comment, $cdate, $answer, $adate);
		$query -> execute();
		$div = '';

		while ($query -> fetch()) {
			$div .= '<div class="comment">';
			$div .= '<h4>'.$ctitle.'</h4>';
			$div .= '<div class="comment-info">';
			$div .= '<div class="username">'.$username;
			$div .= '</div>';
			$cdate = date('d.m.Y', strtotime($cdate));
			$div .= '<div class="date">'.$cdate;
			$div .= '</div>';
			$div .= '</div>';
			$div .= '<div class="comment-body">'.$comment;
			$div .= '</div>';
			if (isset($answer) && strlen($answer) > 1) {
				$div .= '<div class="comment-answer">';
				$div .= '<div class="answer-info">';
				$adate = date('d.m.Y', strtotime($adate));
				$div .= '<div class="date">'.$adate;
				$div .= '</div>';
				$div .= '</div>';
				$div .= '<div class="answer-text">'.$answer;
				$div .= '</div>';
				$div .= '</div>';
			}
			$div .= '</div>';
		}
		$div .= get_pagination($page, $total, $bpid, "page");
		return $div;
	}

	function load_blog($id, $main) {
		$lang = get_lang();
		$mysqli = connect();
		/*$sql = "SELECT b.title_$lang, p.id, p.title_$lang, p.content_$lang, p.created, c.id, c.title, c.username, c.comment, c.comment_date, c.answer, c.answer_date
				FROM blogs b
				LEFT JOIN blog_posts p ON b.id = p.blog_id AND p.state = 1
				LEFT JOIN blog_comments c ON c.blogpost_id = p.id AND c.state = 1
				WHERE b.id = ?
				ORDER BY p.created DESC, c.comment_date ASC";*/
		$sql = "SELECT b.title_$lang, p.id, p.title_$lang, p.content_$lang, p.created, COUNT(c.id)
				FROM blogs b
				LEFT JOIN blog_posts p ON b.id = p.blog_id AND p.state = 1
				LEFT JOIN blog_comments c ON c.blogpost_id = p.id AND c.state = 1
				WHERE b.id = ?
				GROUP BY p.id
				ORDER BY p.created DESC";
		$query = $mysqli -> prepare($sql);
		$query -> bind_param("i", $id);
		$query -> bind_result($btitle, $pid, $ptitle, $content, $date, $total);
		$query -> execute();
		$div = '<article class="blog">';
		$div .= '<div id="messages"></div>';
		$first = true;
		$cpid = 0;
		while ($query -> fetch()) {
			if ($pid != $cpid) {
				if (!$first) {
					$div .= '</div><div class="clearfix"></div></div></div>';
				}
				$div .= '<div class="blog-item">';
				$div .= '<h2 class="blog-title">'.$btitle.'&nbsp;<span class="fa fa-caret-right"></span>&nbsp;'.$ptitle.'</h2>';
				$date = date('d.m.Y', strtotime($date));
				$div .= '<div class="blog-info">
							<div class="blog-date">
								<span>'.$date.'</span>
							</div>
						</div>';
				$div .= '<div class="blog-body">';
				$div .= $content;
				$div .= '</div>';

				$div .= '<ul class="nav nav-tabs">';
				$div .= '<li class="active"><a data-toggle="tab" href="#comment'.$pid.'">'.COMMENT.'</a></li>';
				$div .= '<li><a data-toggle="tab" data-post="'.$pid.'" data-page="1" data-total="'.$total.'" class="page" href="#comments'.$pid.'">'.COMMENTS.'</a></li>';
				$div .= '</ul>';

				$div .= '<div class="tab-content">';
				$div .= '<div id="comment'.$pid.'" class="tab-pane fade in active col-xs-12 col-sm-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">';
				$div .= '<form class="comment-form">';
				$div .= '<input class="id" type="hidden" value="'.$pid.'">';
				$div .= '<div class="form-group">';
				$div .= '<div class="input-group">';
				$div .= '<div class="input-group-addon"><span class="fa fa-pencil"></span></div>';
				$div .= '<input type="text" class="form-control subject" required placeholder="'.SUBJECT.'">';
				$div .= '</div>';
				$div .= '</div>';
				$div .= '<div class="form-group">';
				$div .= '<div class="input-group">';
				$div .= '<div class="input-group-addon"><span class="fa fa-user"></span></div>';
				$div .= '<input type="text" class="form-control name" required placeholder="'.NAME.'">';
				$div .= '</div>';
				$div .= '</div>';
				$div .= '<div class="form-group">';
				$div .= '<div class="input-group">';
				$div .= '<div class="input-group-addon"><span class="fa fa-envelope"></span></div>';
				$div .= '<input type="email" class="form-control email" placeholder="'.EMAIL.'">';
				$div .= '</div>';
				$div .= '</div>';
				$div .= '<div class="form-group">';
				$div .= '<textarea class="form-control text" required placeholder="'.TEXT.'"></textarea>';
				$div .= '</div>';
				$div .= '<div class="form-group">';
				$div .= '<div class="g-recaptcha" data-sitekey="'.RECAPTCHAPUBLIC.'"></div>';
				$div .= '</div>';
				$div .= '<div class="form-group">';
				$div .= '<input type="submit" class="btn btn-primary" value="'.SEND.'">';
				$div .= '</div>';
				$div .= '</form>';
				$div .= '';
				$div .= '</div>';
				$div .= '<div id="comments'.$pid.'" class="comments tab-pane fade">';
				$cpid = $pid;
			}
			if ($first) {
				$main -> set_title($btitle .' | '.SITENAME);
				$first = false;
			}
		}
		$div .= '</div><div class="clearfix"></div></div></div>';
		$div .= '</article>';
		if ($first){
			return load_404($main);
		} else {
			return $div;
		}
	}

	function blog_comment($p) {
		if (isset($p['response']) ) {
			if (check_captcha($p['response'])) {
				if (isset($p['name']) && isset($p['subject']) && isset($p['text'])) {
					$mysqli = connect();
					if (!isset($p['email'])) {
						$p['email'] = '';
					}
					$sql = "INSERT INTO blog_comments (blogpost_id, title, username, email, comment, comment_date)
					VALUES(?,?,?,?,CURDATE())";
					$query = $mysqli -> prepare($sql);
					$query -> bind_param("isss", $p['id'], $p['subject'], $p['name'], $p['email'], $p['text']);
					if ($query -> execute()) {
						$alert = 'success';
						$strong = SUCCESS;
						$text = COMMENTSENT;
					} else {
						$alert='danger';
						$strong=ERROR;
						$text=UNKNOWNERROR;
					}

				} else {
					$alert='danger';
					$strong=ERROR;
					$text=FILLFORM;
				}

			} else {
				$alert='danger';
				$strong=ERROR;
				$text=WRONGCAPTCHA;
			}
		} else {
				$alert='danger';
				$strong=ERROR;
				$text=EMPTYCAPTCHA;
		}
		$div = '<div class="alert alert-'.$alert.' alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong>'.$strong.'</strong> '.$text.'
</div>';
		return $div;
	}

	function check_captcha($response) {
		$url = 'https://www.google.com/recaptcha/api/siteverify';
		$data = array('secret' => RECAPTCHAPRIVATE,
					  'response' => $response,
					  'remoteip' => $_SERVER['REMOTE_ADDR']);
		// use key 'http' even if you send the request to https://...
		$options = array(
			'http' => array(
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query($data)
			)
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		if ($result === false) {
			$res = false;
		} else {
			$resp = json_decode($result);
			$res = $resp -> success;
		}
		return $res;
	}

	function load_search($text, $main) {
		$lang = get_lang();
		$mysqli = connect();
		$main -> set_title(SEARCHRESULTS . ' | ' .SITENAME);
		$div = '<h2>'.SEARCHRESULTS.'</h2>';
		$div .= '<ol class="search-results">';
		$sql = "SELECT a.id, a.title_$lang, a.intro_$lang, a.fulltext_$lang, c.id, c.title_$lang
						FROM contents a
						LEFT JOIN categories c ON c.id = a.category_id
						WHERE (a.title_$lang LIKE ? OR a.intro_$lang LIKE ? OR a.fulltext_$lang LIKE ?) AND a.state = 1
						ORDER BY a.created_date DESC";
						//echo $sql;
		$query = $mysqli -> prepare($sql);
		$text = "%".$text."%";
		$query -> bind_param("sss", $text, $text, $text);
		$query -> bind_result($aid, $atitle, $intro, $full, $cid, $ctitle);
		$query -> execute();
		while ($query -> fetch()) {
			$div .= '<li class="search-item">';
			$div .= '<h4 class="item-title"><a href="/'.$lang.'/article/'.$aid.'">'.$atitle.'</a></h4>';
			$div .= '<div class="item-category">
							<a href="/'.$lang.'/category/'.$cid.'">'.$ctitle.'</a>
					</div></li>';
		}
		$div .= '</ol>';
		return $div;
	}

	function get_images_links($folder) {
		$path = DR . IMG . $folder;
		$res = array();
		if (file_exists($path. "links.xml")) {
			$xml = simplexml_load_file($path . 'links.xml');
			foreach($xml -> link as $link) {
				$res[(string)$link -> img] = $link -> href;
			}
		} else $res = false;
		return $res;
	}

	function get_images_titles($folder) {
		$path = DR . IMG . $folder;
		$res = array();
		if (file_exists($path. "links.xml")) {
			$xml = simplexml_load_file($path . 'links.xml');
			foreach($xml -> link as $link) {
				$res[(string)$link -> img] = $link -> title;
			}
		} else $res = false;
		return $res;
	}

	function get_images_intros($folder) {
		$path = DR . IMG . $folder;
		$res = array();
		if (file_exists($path. "links.xml")) {
			$xml = simplexml_load_file($path . 'links.xml');
			foreach($xml -> link as $link) {
				$res[(string)$link -> img] = $link -> intro;
			}
		} else $res = false;
		return $res;
	}

	function get_pagination($current, $results, $id = 0, $class = "") {
		$pages  = '<ul class="pagination">';
		$maxpage = intval($results / PERPAGE);
		if ($results % PERPAGE > 0) $maxpage++;
		if ($current < 5) {
			$l = 1;
		} else {
			$l = $current - 4;
		}
		if ($maxpage - $l < 9) {
			$r = $maxpage;
			if ($maxpage > 8) {
				$l = $r - 8;
			}
		} else {
			$r = $l + 8;
		}
		$pages .= '<li';
		if ($current == 1) $pages .= ' class="disabled"';
		$pages .= '><a href="#" class="'.$class.'" data-post="'.$id.'" data-page="1" data-total="'.$results.'"><span class="fa fa-fast-backward"></span></a></li>';
		for ($i=$l; $i<=$r; $i++) {
			$pages .= '<li';
			if ($i == $current) {
				$pages .= ' class="active"';
			}
			$pages .= '><a href="#" class="'.$class.'" data-post="'.$id.'" data-page="'.$i.'" data-total="'.$results.'">'.$i.'</a></li>';
		}
		$pages .= '<li';
		if ($current == $maxpage) $pages .= ' class="disabled"';
		$pages .= '><a href="#" class="'.$class.'" data-post="'.$id.'" data-page="'.$maxpage.'" data-total="'.$results.'"><span class="fa fa-fast-forward"></span></a></li>';
		$pages .= '</ul>';
		return $pages;
	}

	function load_galleries($main) {
		$mysqli = connect();
		$lang = get_lang();
		$sql = "SELECT g.id, g.title_$lang, i.url FROM galleries g
				LEFT JOIN images i ON g.id = i.gallery_id
				WHERE g.id > 0 AND i.url IS NOT NULL GROUP BY g.id ORDER BY g.id DESC ";
		$query = $mysqli -> prepare($sql);
		$query -> bind_result($id, $title, $url);
		$query -> execute();
		$i = 0;
		$div = '';
		while ($query -> fetch()) {
			$i++;
			if ($i == 1) {
				$main -> set_title(GALLERIES .' | '.SITENAME);
				$div .= '<article class="photogallery">';
				$div .= '<h2>'.GALLERIES.'</h2>';
				$div .= '<div id="slides">';
				$div .= '<ul id="slideshow" class="hidden">';
			}
			$div .= '<li>';
			$div .= '<a href="/'.$lang.'/gallery/'.$id.'">';
			$div .= '<div class="slider-info">';
			if (isset($title) && strlen($title) > 0) {
				$div .= '<h3>'.$title.'</h3>';
			}
			$div .= '</div>';
			$div .= '<img src="'.$url.'" alt="">';
			$div .= '</a></li>';
		}
		if ($i > 0) {
			$div .= '</ul><div class="arrows"></div><div class="buttons"></div></div>';
			$div .= '</article>';
			return $div;
		} else {
			return load_404($main);
		}
	}

	function load_content($main) {
		//print_r($_GET);
		//echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
		if (isset($_GET['error'])) {
			switch ($_GET['error']) {
				case 404: $res = load_404($main); break;
			}
		} else {
			/*if (isset($_GET['option'])) {
				load_joomla($_GET);
			}*/
			if (isset($_GET['view']) && (isset($_GET['id'])) || isset($_GET['text'])) {
				switch($_GET['view']) {
					case 'category': $res = load_category(intval($_GET['id']), $main); break;
					case 'article': $res = load_article(intval($_GET['id']), $main); break;
					case 'blog': $res = load_blog(intval($_GET['id']), $main); break;
					case 'search': $res = load_search($_GET['text'], $main); break;
					case 'module': $res = load_module($_GET['text'], $main); break;
					case 'gallery': $res = load_gallery($_GET['id'], $main); break;
					case 'galleries': $res = load_galleries($main); break;
					default: $res = load_404($main); break;
				}
			} else {
				$res =  load_main($main);
			}
		}
		$main -> set_content($res);
	}

	function load_parent($id, $query, $array, $level) {
		$query -> bind_param("i", $id);
		$query -> bind_result($parent, $title);
		$query -> execute();
		if ($query -> fetch()) {
			$array[$level]['id'] = $id;
			$array[$level]['title'] = $title;
			if ($parent > 0) {
				$level++;
				$res = load_parent($parent, $query, $array, $level);
			} else {
				$res = $array;
			}
		} else {
			$res = $array;
		}
		return $res;
	}

	function load_parents_list($id) {
		$mysqli = connect();
		$lang = get_lang();
		$sql = "SELECT parent_id, title_$lang FROM categories WHERE id=?";
		$array = array();
		$query = $mysqli -> prepare($sql);
		$res = load_parent($id, $query, $array, 0);
		$count = count($res);
		$div = "";
		if ($count > 0) {
			$div .= '<ul class="breadcrumbs">';
			for ($i = $count-1; $i >= 0; $i--) {
				$div .= '<li><a href="/'.$lang.'/category/'.$res[$i]['id'].'">'.$res[$i]['title'].'</a></li>';
			}
			$div .= '</ul>';
		}
		return $div;
	}

	function load_news($cats, $limit = 10) {
		$mysqli = connect();
		$catstr = '0';
		foreach ($cats as $c) {
			$catstr .= ','.intval($c);
		}
		$lang = get_lang();
		switch ($lang) {
			case 'en': $lin = 1; break;
			case 'ru': $lin = 2; break;
			default: $lin = 3; break;
		}
		$sql = "SELECT c.id, c.title_$lang, c.intro_$lang, i.url, i.preview, c.created_date
                FROM contents c
				LEFT JOIN images i ON i.id = c.image_id
				WHERE c.state = 1 AND c.category_id IN ($catstr) ORDER BY c.created_date DESC LIMIT ?";
		$query = $mysqli -> prepare($sql);
		$query -> bind_param("i", $limit);
		$query -> bind_result($id, $title, $intro, $url, $preview, $date);
		$query -> execute();
		$res = array();
		$i = 0;
		while ($query -> fetch()) {
			$res[$i]['id'] = $id;
            $res[$i]['title'] = $title;
			$res[$i]['intro'] = $intro;
			$res[$i]['image'] = $url;
			$res[$i]['preview'] = $preview;
			$res[$i]['created_date'] = $date;
			$i++;
		}
		return $res;
	}
?>
