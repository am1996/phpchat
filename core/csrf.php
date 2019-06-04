<?php
	if ($_SERVER['REQUEST_METHOD'] == 'POST' && @$_SESSION["csrf"] != @$_POST["csrf"]) {
		die("CSRF protection activated.");
	}
?>