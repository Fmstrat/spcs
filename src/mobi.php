<?php include_once "include/require-login.php"; ?><?php include_once "include/config.php"; ?><?php

$filename=$books_folder.$_GET['m'];
if (file_exists($filename)) {
	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename="'.basename($filename).'"');
	header('Content-Transfer-Encoding: binary');
	header('Expires: 0');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	header('Content-Length: ' . filesize($filename));
	ob_clean();
	flush();
	readfile($filename);
	exit;
}
?>
