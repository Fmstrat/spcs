<?php include_once "include/config.php"; ?>
<?php

	function exception_handler($exception) {
		echo "Uncaught exception: " , $exception->getMessage(), "<br>Make sure the metadata.db file and the folder it is housed in have write permissions.\n";
	}

	set_exception_handler('exception_handler');

	try {
		$file_db = new PDO('sqlite:'.$calibre_db);
		$file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$q = "SELECT name FROM sqlite_master WHERE type='table' AND name='spcs';";
		$result = $file_db->query($q);
		$exists = 0;
		foreach ($result as $row) {
			$exists = 1;
		}

		if ($exists == 0) {
				$q = "create table spcs (id integer primary key, username text, password text, emailaddress text);";
				$file_db->query($q);
				$q = "insert into spcs (id, username, password) values (null, \"admin\", \"".md5("password")."\");";
				$file_db->query($q);
				echo "Table spcs created with user \"admin\" and password \"password\"";
		} else {
			echo "Table already exists.";
		}
		$file_db = null;
	} catch (Exception $e) {
		//throw new Exception('Error', 0, $e);
		throw $e;
	}
?>
