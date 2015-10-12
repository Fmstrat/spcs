<?php include_once "include/require-login.php"; ?><?php

include_once "include/config.php";

$filename=$_GET['i'];
header('Content-type: image/jpeg');
$image = imagecreatefromjpeg($books_folder.$filename);
imagejpeg($image, null, 100);

?>
