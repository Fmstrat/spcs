<?php include_once "include/require-login.php"; ?>
<?php include_once "include/config.php"; ?>
<?php

if (isset($_POST["submit"])) {

	if ($_POST["submit"] == "Logout") {
		header("Location: login.php");
	} else {
		try {
			$file_db = new PDO('sqlite:'.$calibre_db);
			$file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$emailaddress = $_POST["emailaddress"];
			$password = $_POST["password"];
			$q = "update spcs set emailaddress='".$emailaddress."'";
			if ($password != '') {
				$q = $q.", password='".md5($password)."'";
			}
			$q = $q." where id=".$id.";";
			$result = $file_db->query($q);
			header("Location: index.php");
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
	}

} else {

	try {
		$file_db = new PDO('sqlite:'.$calibre_db);
		$file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$q = "select emailaddress from spcs where id=".$id.";";
		$result = $file_db->query($q);
		foreach ($result as $row) {
			$emailaddress = $row['emailaddress'];
		}
		$file_db = null;
	} catch(PDOException $e) {
		echo $e->getMessage();
	}

?>
<?php include_once "include/header.php"; ?>


<form method="POST">
<div class="searchbar">
	<div class="searchinner">
		Settings
	</div>
</div>

<div class="settings">

"Send to" email address:<br>
<input type=text class=input name=emailaddress value="<?php echo $emailaddress; ?>" style="width:80%"><br>
<span style="font-size: 8pt">&nbsp;If using a Kindle, this would be your <a href="http://askville.amazon.com/find-Kindle-email-address/AnswerViewer.do?requestId=75022941" target="_new">Kindle's email address</a>.</span><br>

<br>

Update password:<br>
<input type=password class=input name=password value="" style="width:80%"><br>
<span style="font-size: 8pt">&nbsp;Leave blank if you do not wish to change your password.</span><br>



</div>

<br>
<div class="footerbar">
	<div class="footerinner">
		<input class=ybutton type=submit name=submit value='Save'>
		<input class=ybutton style="position: absolute; right: 10px" type=submit name=submit value='Logout'>
	</div>
</div>
</form>

<?

	}

?>
<?php include_once "include/footer.php"; ?>
