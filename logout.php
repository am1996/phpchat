<?php require("./includes/head.php"); ?>
<?php
	if(SignSys::checkLoggedIn()){
		SignSys::logout();
		header("location: http://{$_SERVER['HTTP_HOST']}");
	}else{
		http_response_code(404);
		die("<h1>404 Page not found!</h1>");
	}

?>
<?php require("./includes/foot.php") ?>