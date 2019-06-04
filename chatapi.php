<?php
include("./includes/headers.php");

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$uniqid = uniqid() . "-" . time();
	$msgdb->saveMsg($_POST["sndr"],$_POST["rcvr"],$_POST["msg"],$uniqid);
}else if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET["rcvr"])) {
	echo json_encode($msgdb->getMsgs($_SESSION["user"]["id"],$_GET["rcvr"]));
}
?>