<?php include_once "include/require-login.php"; ?>
<?php include_once "include/config.php"; ?>
<?php include_once "include/header.php"; ?>
<?php

	$err = 0;
	$to = "";
	try {
		$file_db = new PDO('sqlite:'.$calibre_db);
		$file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$q = "select emailaddress from spcs where id=".$id.";";
		$result = $file_db->query($q);
		foreach ($result as $row) {
			$to = $row['emailaddress'];
		}
		$file_db = null;
	} catch(PDOException $e) {
		echo $e->getMessage();
	}

	if ($to != "") {

		require_once "Mail.php";
		require_once "Mail/mime.php";

		$subject   = "Book";
		$bodyTxt   = "Book attached";

		$filepath=$books_folder.$_GET['m'];
		$fileContentType="text/plain";

		$mime = new Mail_Mime("\r\n");
		$mime->setTXTBody($bodyTxt);
		$mime->addAttachment($filepath,$fileContentType);

		$headerInfo=array(
			'From'      => $from,
			'To'        => $to,
			'Subject'   => $subject
		);

		$body = $mime->get();
		$headers = $mime->headers($headerInfo);

		$smtp = Mail::factory('smtp',$config);

		$mail = $smtp->send($to, $headers, $body);

		if(!(PEAR::isError($mail))) {
			$err = 0;
		} else {
			echo $mail->getMessage();
			$err = 1;
		}

	} else {
		$err = 1;
	}

?>

<div class="searchbar">
	<div class="searchinner">
		Sending Book
	</div>
</div>

<div class="booksent">
<?php

	if ($err == 0) {
		echo "<b>Complete!</b><br><br>Wait a few minutes and sync to check for new books.";
	} else {
		echo "<b>ERROR: An unknown error occured while sending your book.</b>";
	}

?>
</div>

<div class="footerbar">
	<div class="footerinner">
		<input class=ybutton type=button name=back onclick='history.go(-1)' value='Return to Search'>
	</div>
</div>

<?php include_once "include/footer.php"; ?>
