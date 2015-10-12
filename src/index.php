<?php include_once "include/require-login.php"; ?>
<?php include_once "include/config.php"; ?>
<?php include_once "include/header.php"; ?>

<?php

	$page = 0;
	$pageoffset = 0;
	if (isset($_GET['page'])) {
		$page = $_GET['page'];
	}
	if (isset($_GET['search'])) {
		if ($_GET['search'] == "Next") {
			$page = $page + 1;
		}
		if ($_GET['search'] == "Prev") {
			$page = $page - 1;
		}
	}
	$pageoffset = $page * $results_per_page;


	function makeWhere($word) {
			$str = "(title like '% ".$word." %' or title like '% ".$word."' or title like '".$word." %')";
			$str = $str." or (author_sort like '% ".$word." %' or author_sort like '% ".$word."' or author_sort like '".$word." %')";
			$str = $str." or (name like '% ".$word." %' or name like '% ".$word."' or name like '".$word." %')";
			return $str;
	}

	function makeCaseWholePhrase($phrase) {
			$str = "(case when title like '% ".$phrase." %' then 2 else 0 end + case when title like '% ".$phrase."' then 2 else 0 end + case when title like '".$phrase." %' then 2 else 0 end) + ";
			$str = $str."(case when author_sort like '% ".$phrase." %' then 2 else 0 end + case when author_sort like '% ".$phrase."' then 2 else 0 end + case when author_sort like '".$phrase." %' then 2 else 0 end) + ";
			$str = $str."(case when name like '% ".$phrase." %' then 2 else 0 end + case when name like '% ".$phrase."' then 2 else 0 end + case when name like '".$phrase." %' then 2 else 0 end) + ";
			return $str;
	}

	function makeCaseWord($word) {
			$str = "(case when title like '% ".$word." %' then 1 else 0 end + case when title like '% ".$word."' then 1 else 0 end + case when title like '".$word." %' then 1 else 0 end) + ";
			$str = $str."(case when author_sort like '% ".$word." %' then 1 else 0 end + case when author_sort like '% ".$word."' then 1 else 0 end + case when author_sort like '".$word." %' then 1 else 0 end) + ";
			$str = $str."(case when name like '% ".$word." %' then 1 else 0 end + case when name like '% ".$word."' then 1 else 0 end + case when name like '".$word." %' then 1 else 0 end)";
			return $str;
	}

?>

<div class="searchbar">
	<div class="searchinner">
		<form method="GET">
		<input type="text" class=input style="width:50%" name="query" id="query" value="<?php if (isset($_GET['query'])) echo $_GET['query']; ?>">
		<input type="button" onclick="doSearch();" class=ybutton name="search" value="Search">
		</form>
	</div>
</div>

<div class="results">
<?php

	try {
		$file_db = new PDO('sqlite:'.$calibre_db);
		$file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		if (isset($_COOKIE["sort"]) && $_COOKIE["sort"] != '') {
			$sort = $_COOKIE["sort"];
		} else {
			$sort = "b";
		}

		if (isset($_GET['query']) && $_GET['query'] != '') {
			$tmpquery = preg_replace('/[^a-z0-9 ]+/i', '', $_GET['query']);
			$where = "";
			$case = "";
			if (strpos($tmpquery, " ") !== false) {
				$case = makeCaseWholePhrase($tmpquery);
			}
			$tok = strtok($tmpquery, " ");
			$c = 0;
			while ($tok !== false) {
				if ($c != 0) {
					$where = $where." or ";
					$case = $case." + ";
				}
				$where = $where.makeWhere($tok);
				$case = $case.makeCaseWord($tok);
				$c = $c + 1;
				$tok = strtok(" ");
			}

			$q = "SELECT strftime('%s', last_modified) as last_modified, title, author_sort, series_index, path, books.id, series, name,  ";
			$q = $q."(".$case.") as score ";
			$q = $q."FROM books left outer join books_series_link on books.id = books_series_link.book left outer join series on books_series_link.series = series.id WHERE (";
			$q = $q.$where;
			if ($sort == "a") {
				$q = $q.") order by author_sort, score desc, title, series limit ".$pageoffset.",".$results_per_page.";";
			} elseif ($sort == "t") {
				$q = $q.") order by title, score desc, author_sort, series limit ".$pageoffset.",".$results_per_page.";";
			} elseif ($sort == "s") {
				$q = $q.") order by series, author_sort, score desc, title limit ".$pageoffset.",".$results_per_page.";";
			} elseif ($sort == "ad") {
				$q = $q.") order by last_modified desc, author_sort, title, series limit ".$pageoffset.",".$results_per_page.";";
			} else {
				$q = $q.") order by score desc, author_sort, title, series limit ".$pageoffset.",".$results_per_page.";";
			}
		} else {
			$q = "SELECT strftime('%s', last_modified) as last_modified, title, author_sort, series_index, path, books.id, series, name ";
			$q = $q."FROM books left outer join books_series_link on books.id = books_series_link.book left outer join series on books_series_link.series = series.id ";
			if ($sort == "t") {
				$q = $q." order by title, author_sort, series limit ".$pageoffset.",".$results_per_page.";";
			} elseif ($sort == "s") {
				$q = $q." order by series, author_sort, title limit ".$pageoffset.",".$results_per_page.";";
			} elseif ($sort == "a") {
				$q = $q." order by author_sort, title, series limit ".$pageoffset.",".$results_per_page.";";
			} else {
				$q = $q." order by last_modified desc, author_sort, title, series limit ".$pageoffset.",".$results_per_page.";";
			}
		}

		//echo $q."<br><br>";
		$result = $file_db->query($q);

		$c = 0;
		$bg = "#ffffff";
		$t = time();
		foreach ($result as $row) {
			echo "<div class='result' style='background: ".$bg."'>";
			echo "<div class='cover'>";
			if ($t - $row['last_modified'] < 604800) // Less than one week old
				echo "<div class='newbar'>NEW!</div>";
			echo "<a target=\"_new\" href=\"showimg.php?i=".htmlspecialchars($row['path'])."/cover.jpg\"><img width=56 height=90 src=\"img-cropped.php?x=56&y=90&i=".htmlspecialchars($row['path'])."/cover.jpg\" border=0></a>";
			echo "</div>";
			echo "<div class='booktitle'>";
			echo "<b>".$row['title']."</b>";
			if (isset($row['name']) && $row['name'] != '') {
				echo "<i> [".$row['name']." book ".$row['series_index']."]</i>";
			}
			echo "<br>\n";
			echo "</div>";
			echo "<div class='author'><i>".$row['author_sort']."</i></div>\n";
			//echo "Score: ".$row['score']."<br>";
			$q = "select name from data where format='".strtoupper($book_type)."' and book = ".$row['id'];
			$result2 = $file_db->query($q);
			$bookcount = 0;
			foreach ($result2 as $row2) {
				echo "<input type=button class=button value='Send' onclick='sendBook(\"" . urlencode($row['path'] . "/" . $row2['name'] . ".".$book_type)."\")'>";
				echo "<input type=button class=button onclick='downloadBook(\"" . urlencode($row['path'] . "/" . $row2['name'] . ".".$book_type)."\")' value='Download'>";
				$bookcount++;
			}
			if ($bookcount == 0) {
				$q = "select name from data where format='PDF' and book = ".$row['id'];
				$result2 = $file_db->query($q);
				foreach ($result2 as $row2) {
					echo "<input type=button class=button value='Send' onclick='sendBook(\"" . urlencode($row['path'] . "/" . $row2['name'] . ".".$book_type)."\")'>";
					echo "<input type=button class=button onclick='downloadBook(\"" . urlencode($row['path'] . "/" . $row2['name'] . ".pdf")."\")' value='Get PDF'>";
				}
			}
			echo "</div>";
			$c = $c + 1;
			if ($bg == "#ffffff") {
				$bg = "#eeeeee";
			} else {
				$bg = "#ffffff";
			}
		}
		if ($c == 0) {
			echo "No Results.<br><br>\n";
		}

		// Close file db connection
		$file_db = null;
	} catch(PDOException $e) {
		// Print PDOException message
		echo $e->getMessage();
	}

?>
</div>

<br>
<div class="footerbar">
	<div class="footerinner">
		<form method="GET">
			<?php
				echo "<input type=hidden name=page value='".$page."'>";
				echo "<input type=hidden name=query value='".$_GET['query']."'>";
				echo "<input type=hidden name=search value='Search'>";
				if ($page > 0) {
					echo "<input class=ybutton type=submit name=search value='Prev'>";
				}
				echo " " . ($page + 1) . " ";
				if ($c >= $results_per_page) {
					echo "<input class=ybutton type=submit name=search value='Next'>";
				}
			?>
		</form>
	</div>
</div>

<div class="settingsbutton">
		<a href="settings.php"><img class="squarebutton" style="float:right" border=0 src="images/settings.png"></a>
</div>
<div class="sortbutton">
		<a href="javascript:toggleSort()"><img class="squarebutton" style="float:left" border=0 src="images/sort.png"></a>
</div>

<div id="sortwindow" class="sortwindow">
	<div id="sort_b" class="sortoption<? if (isset($_COOKIE["sort"]) && $_COOKIE["sort"] == "b") { echo "active"; } ?>" onclick="sort('b')">Best Match</div>
	<div id="sort_a" class="sortoption<? if (isset($_COOKIE["sort"]) && $_COOKIE["sort"] == "a") { echo "active"; } ?>" onclick="sort('a')">Author</div>
	<div id="sort_t" class="sortoption<? if (isset($_COOKIE["sort"]) && $_COOKIE["sort"] == "t") { echo "active"; } ?>" onclick="sort('t')">Title</div>
	<div id="sort_s" class="sortoption<? if (isset($_COOKIE["sort"]) && $_COOKIE["sort"] == "s") { echo "active"; } ?>" onclick="sort('s')">Series</div>
	<div id="sort_s" class="sortoption<? if (isset($_COOKIE["sort"]) && $_COOKIE["sort"] == "ad") { echo "active"; } ?>" onclick="sort('ad')">Added</div>
</div>

<?php include_once "include/footer.php"; ?>
