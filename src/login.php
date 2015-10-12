<?php include_once "include/config.php"; ?><?php

setcookie ("id", "", time() - 3600);
setcookie ("sort", "", time() - 3600);

$error = 0;
if (isset($_POST["submit"])) {
	try {
		$file_db = new PDO('sqlite:'.$calibre_db);
		$file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$username = $_POST["username"];
		$password = $_POST["password"];
		$q = "select id from spcs where username='".$username."' and (password='".md5($password)."' or password='' or password is null);";
		$result = $file_db->query($q);
		$found = 0;
		foreach ($result as $row) {
			setcookie("id", $row['id'], time()+7776000); // 90 days
			setcookie("sort", "b", time()+7776000); // 90 days
			$found = 1;
		}
		if ($found == 1) {
			header("Location: index.php");
		} else {
			$error = 1;
		}
		$file_db = null;
	} catch(PDOException $e) {
		echo $e->getMessage();
	}
}

?>
<?php include_once "include/header.php"; ?>


<form method="POST">
<div class="searchbar">
	<div class="searchinner">
		Login
	</div>
</div>

<div class="settings">

<?php
	if ($error == 1) {
		echo "<font color='red'>Invalid username and/or password.<br><br></font>";
	}
?>

Username:<br>
<input type=text class=input name=username style="width:80%"><br>
<br>
Password:<br>
<input type=password class=input name=password style="width:80%"><br>
</div>

<br>
<div class="footerbar">
	<div class="footerinner">
		<input class=ybutton type=submit name=submit value='Login'>
	</div>
</div>
</form>

<?php include_once "include/footer.php"; ?>
