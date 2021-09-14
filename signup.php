<?php require("./includes/head.php") ?>
<?php
	if ($_SERVER["REQUEST_METHOD"] == "POST" && !SignSys::checkLoggedIn()){
		$fullname = $_POST["fullname"];
		$email = $_POST["email"];
		$password = $_POST["password"];
		SignSys::register($fullname,$email,$password);
		header("location:http://127.0.0.1:8000?success=true");
	}
	if(SignSys::checkLoggedIn())
		die("You are already logged in.");
?>
<body>
	<h1 style="text-align:center">Signup</h1>
	<form action="/signup.php" method="POST">
		<input name="csrf" type="hidden" value="<?= $_SESSION['csrf']; ?>">
		<div class="input-group vertical">
			<label for="fullname">Fullname:</label>
			<input type="text" name="fullname" id="fullname" placeholder="Enter Fullname" required>
		</div>
		<div class="input-group vertical">
			<label for="email">Email:</label>
			<input type="email" name="email" id="email" placeholder="Enter email" required>
		</div>
		<div class="input-group vertical">
			<label for="password">Password:</label>
			<input type="password" name="password" id="password" placeholder="Enter Password" required>
		</div>
		<div class="input-group vertical">
			<button style="margin:8px 4px" class="inverse" type="submit">Login</button>
		</div>
	</form>
</body>
<?php require("./includes/foot.php") ?>