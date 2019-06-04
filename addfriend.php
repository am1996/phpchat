<?php require("./includes/head.php") ?>
<?php
	$added = $_POST["id"];
	$adder = $_COOKIE["uid"];
	$a = $frienddb->addFriend($adder,$added);
	header("location: ".BASE_URL);
?>
<?php require("./includes/foot.php") ?>