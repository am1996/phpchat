<?php require("./includes/head.php") ?>
<body>
	<?php
		if($_SERVER["REQUEST_METHOD"] == "POST" && !SignSys::checkLoggedIn()){
			$email = $_POST["email"];
			$password = $_POST["password"];
			if(SignSys::login($email,$password)){
				$_SESSION["logged"] = true;
				header("location: http://{$_SERVER['HTTP_HOST']}");
			}else{
				$_SESSION["logged"] = false;
				header("location: http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}");
			}
		}
		if(SignSys::checkLoggedIn())
			die("You are already logged in.");
	?>
	<h1 style="text-align:center">Login</h1>
	<form action="/login.php" method="POST">
		
		<input name="csrf" type="hidden" value="<?= $_SESSION['csrf']; ?>">
		<div class="input-group vertical">
			<label for="email">Email:</label>
			<input type="email" name="email" id="email" placeholder="Enter email">
		</div>
		<div class="input-group vertical">
			<label for="password">Password:</label>
			<input type="password" name="password" id="password" placeholder="Enter Password">
		</div>
		<div class="input-group vertical">
			<button style="margin:8px 4px" class="inverse" type="submit">Login</button>
		</div>
	</form>
</body>
<?php require("./includes/foot.php") ?>