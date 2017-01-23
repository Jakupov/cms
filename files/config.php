<?php
	function connect() {
		$mysqli = new mysqli("p:localhost", "root", "", "dbname");
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
		$mysqli->set_charset("utf8");
		return $mysqli;
	}
?>
