<?php

if(strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'kindle') || strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'mobile') || strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'android')) {
	echo "".'<?xml version="1.0" encoding="utf-8"?>'."\n";
	echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML Basic 1.1//EN\" \"http://www.w3.org/TR/xhtml-basic/xhtml-basic11.dtd\">";
	echo "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"en\">";
	echo "<meta name=\"apple-mobile-web-app-capable\" content=\"yes\">";
	echo "<meta name=\"viewport\" content=\"width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;\">";
	echo "<meta name=\"apple-mobile-web-app-capable\" content=\"yes\">";
} else {
	echo "<html>";
}

?>
<head>
	<title>Kindle Library</title>
	<script type="text/JavaScript" src="js/functions.js"></script>
	<?php if (strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'kindle') && !strstr(strtolower($_SERVER['HTTP_USER_AGENT']), 'fire')) { ?>
		<link rel="stylesheet" href="css/kindle.css" type="text/css" />
	<?php } else { ?>
		<link rel="stylesheet" href="css/styles.css" type="text/css" />
	<?php } ?>
</head>

<body>
