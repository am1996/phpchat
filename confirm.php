<?php require("./includes/head.php") ?>
<?php
	$id = $_GET["id"];
	$token = $_GET["token"];
	$msg = SignSys::confirmUser($id,$token);
	echo $msg;
?>
<?php require("./includes/foot.php") ?>