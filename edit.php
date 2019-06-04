<?php require("./includes/head.php") ?>
<body>
	<?php if(SignSys::checkLoggedIn()): ?>
		<?php
			if ($_SERVER["REQUEST_METHOD"] == "POST" && SignSys::checkLoggedIn()){
				$id = $_COOKIE["uid"];
				$fields = array();
				foreach($_POST as $k => $v){
					if(!empty($v))
						$fields[$k] = $v;
				}
				SignSys::editUser($_COOKIE["uid"],$fields);
			}
		?>
		<h1 style="text-align:center">Edit Information</h1>
		<form action="/edit.php" method="POST">
			<input name="csrf" type="hidden" value="<?= $_SESSION['csrf']; ?>">
			<div id="fullnamediv" class="input-group vertical">
				<label for="fullname">Fullname:</label>
				<input type="text" name="fullname" id="fullname" placeholder="Enter Fullname">
			</div>
			<div class="input-group vertical">
				<label for="email">Email:</label>
				<input type="email" name="email" id="email" placeholder="Enter email">
			</div>
			<div class="input-group vertical">
				<label for="password">Password:</label>
				<input type="password" name="password" id="password" placeholder="Enter Password">
			</div>
			<div class="input-group vertical">
				<button style="margin:8px 4px" class="inverse" type="submit">Edit</button>
			</div>
		</form>
	<?php else: ?>
		<h1>404 Page not found!</h1>
		<?php http_response_code(404) ?>
	<?php endif; ?>
</body>
<?php require("./includes/foot.php") ?>