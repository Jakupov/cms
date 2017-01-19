<?php 
	define('PASSWORD','Hide2042');
	define('SITENAME','https://makhambet.kz/');
	if (isset($_GET['key']) && $_GET['key'] === PASSWORD) {
		function connect() {
			$mysqli = new mysqli("localhost", "root", "JUjkads17", "project3");
			if (mysqli_connect_errno()) {
				printf("Connect failed: %s\n", mysqli_connect_error());
				exit();
			}
			$mysqli->set_charset("utf8");
			return $mysqli;
		}
		function get_ids($tablename) {
			$mysqli = connect();
			$sql = "SELECT id FROM $tablename WHERE state = 1";
			$query = $mysqli -> prepare($sql);
			$query -> bind_result($id);
			$query -> execute();
			$ids = array();
			$i = 0;
			while ($query -> fetch()) {
				$ids[$i] = $id;
				$i++;
			}
			return $ids;
		}
		$xml = new domDocument("1.0", "UTF-8");
		$root = $xml -> createElement("urlset");
		$root -> setAttribute("xmlns","http://www.sitemaps.org/schemas/sitemap/0.9");
		$root -> setAttribute("xmlns:xhtml","http://www.w3.org/1999/xhtml");
		
		$count = 0;
		$sc = 1;
		$p = "1";
		$langs = array('kz/', 'ru/', 'en/');
		$codes = array('kk', 'ru', 'en');
		foreach ($langs as $lang) {
			$loc = $xml -> createElement("loc", SITENAME . $lang );
			$priority = $xml -> createElement("priority", $p);
			$url = $xml -> createElement("url");
			$url -> appendChild($loc);
			$url -> appendChild($priority);
			$i = 0;
			$count++;
			foreach($langs as $l){
				$link = $xml -> createElement("xhtml:link");
				$link -> setAttribute("rel", "alternate");
				$link -> setAttribute("hreflang", $codes[$i]);
				$link -> setAttribute("href", SITENAME . $l);
				$url -> appendChild($link);
				$i++;
			}
			$root -> appendChild($url);
		}
		$p = "0.8";
		$articles = get_ids("contents");
		foreach ($articles as $elem) {		
			foreach ($langs as $lang) {
				$loc = $xml -> createElement("loc", SITENAME . $lang . 'article/' . $elem);
				$priority = $xml -> createElement("priority", $p);
				$url = $xml -> createElement("url");
				$url -> appendChild($loc);
				$url -> appendChild($priority);
				$i = 0;
				$count++;
				foreach($langs as $l){
					$link = $xml -> createElement("xhtml:link");
					$link -> setAttribute("rel", "alternate");
					$link -> setAttribute("hreflang", $codes[$i]);
					$link -> setAttribute("href", SITENAME . $l . 'article/' . $elem);
					$url -> appendChild($link);
					$i++;
				}
				$root -> appendChild($url);
			}
		}
		$p = "0.7";
		$blogs = get_ids("blogs");
		foreach ($blogs as $elem) {		
			foreach ($langs as $lang) {
				$loc = $xml -> createElement("loc", SITENAME . $lang . 'blog/' . $elem);
				$priority = $xml -> createElement("priority", $p);
				$url = $xml -> createElement("url");
				$url -> appendChild($loc);
				$url -> appendChild($priority);
				$i = 0;
				$count++;
				foreach($langs as $l){
					$link = $xml -> createElement("xhtml:link");
					$link -> setAttribute("rel", "alternate");
					$link -> setAttribute("hreflang", $codes[$i]);
					$link -> setAttribute("href", SITENAME . $l . 'blog/' . $elem);
					$url -> appendChild($link);
					$i++;
				}
				$root -> appendChild($url);
			}
		}
		$p = "0.5";
		$categories = get_ids("categories");
		foreach ($categories as $elem) {		
			foreach ($langs as $lang) {
				$loc = $xml -> createElement("loc", SITENAME . $lang . 'category/' . $elem);
				$priority = $xml -> createElement("priority", $p);
				$url = $xml -> createElement("url");
				$url -> appendChild($loc);
				$url -> appendChild($priority);
				$i = 0;
				$count++;
				foreach($langs as $l){
					$link = $xml -> createElement("xhtml:link");
					$link -> setAttribute("rel", "alternate");
					$link -> setAttribute("hreflang", $codes[$i]);
					$link -> setAttribute("href", SITENAME . $l . 'category/' . $elem);
					$url -> appendChild($link);
					$i++;
				}
				$root -> appendChild($url);
			}
		}
		$xml -> appendChild($root);
		$xml -> save($_SERVER['DOCUMENT_ROOT'] . "/sitemap.xml");
		
		
		echo $count;
	}
?>