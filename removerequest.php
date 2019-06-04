<?php require("./includes/head.php") ?>
<?php
	$added = $_POST["id"];
	$adder = $_COOKIE["uid"];
	$res = $frienddb->removeFriendRequest($adder,$added);
	if($res) header("location: ".BASE_URL);
	else die("Internal Server Error");
?>
<?php require("./includes/foot.php") ?>