<?php
	include("protectionsys.php");
	$host = "localhost";
	$db_user = "root";
	$db_password = "";
	$db_name = "website";
	$URL = "http://{$_SERVER['HTTP_HOST']}/{$_SERVER['REQUEST_URI']}";
	if(!isset($_SESSION["csrf"]))
		$_SESSION["csrf"] = bin2hex(random_bytes(32));
?>