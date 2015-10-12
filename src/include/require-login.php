<?php

	if (!isset($_COOKIE['id'])) {
		header("Location: login.php");
	} else {
		$id = $_COOKIE['id'];
	}

?>