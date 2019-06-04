<?php require("./includes/headers.php"); ?>
<?php
	$csrf = $_GET["csrf"];
	$id   = $_GET["id"];
	$resp = $_GET["res"];
	if($csrf != $_SESSION["csrf"]) die("CSRF attack detected");
	if($resp === "accept"){
		if($frienddb->acceptFriend($id)){	
			echo json_encode(["response" => "added successfully","status" => 200]);
			http_response_code(200);
		}else{
			echo json_encode(["error" => "internal server error","status" => 500]);
			http_response_code(500);
		}
	}else if($resp === "remove"){
		if($frienddb->removeFriendRequest($id, $_SESSION['user']['id'])){
			echo json_encode(["response" => "removed successfully","status" => 200]);
			http_response_code(200);
		}else{
			echo json_encode(["error" => "internal server error","status" => 500]);
			http_response_code(500);
		}
	}
?>