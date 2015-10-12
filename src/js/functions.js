
function SetCookie(cookieName,cookieValue,nDays) {
	var today = new Date();
	var expire = new Date();
	expire.setTime(today.getTime() + 3600000*24*nDays);
	document.cookie = cookieName+"="+escape(cookieValue) + ";expires="+expire.toGMTString();
}

searchsort = "b";

function toggleSort() {
	if (document.getElementById('sortwindow').style.visibility == "visible") {
		document.getElementById('sortwindow').style.visibility = "hidden";
	} else {
		document.getElementById('sortwindow').style.visibility = "visible";
	}
}

function sort(s) {
	SetCookie("sort", s, 90); // 90 days
	document.location.reload(true);
}

function doSearch() {
	var query = document.getElementById('query').value;
	setTimeout(function() { document.location = "index.php?query=" + query; }, 500);
}

function sendBook(path) {
	document.location = "sendbook.php?m=" + path;
}

function downloadBook(path) {
	document.location = "mobi.php?m=" + path;
}
