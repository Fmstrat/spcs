<?php include_once "include/require-login.php"; ?><?php

include_once("include/config.php");
include_once("include/resize-class.php");

$filename=$_GET['i'];
header('Content-type: image/jpeg');
$resizeObj = new resize($books_folder.$filename);
$resizeObj -> resizeImage($_GET['x'], $_GET['y'], 'crop');
$resizeObj -> showImage(80);

?>
