<?php
	include("protectionsys.php");
	$d = "dev";
	if($d = "dev"){
		$host = "localhost";
		$db_user = "root";
		$db_password = "";
		$db_name = "website";
		$URL = "http://{$_SERVER['HTTP_HOST']}/{$_SERVER['REQUEST_URI']}";
	}else if($d = "dev"){
		$host = "localhost";
		$db_user = "am1996";
		$db_password = "\Be8fjYemp}&TK8u";
		$db_name = "id17593104_website";
		$URL = "http://{$_SERVER['HTTP_HOST']}/{$_SERVER['REQUEST_URI']}";
	}
	if(!isset($_SESSION["csrf"]))
		$_SESSION["csrf"] = bin2hex(random_bytes(32));
?>