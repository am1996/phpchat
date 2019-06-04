<?php require("./includes/head.php"); ?>
<head>
	<style>
		form{padding:0;margin:0;display:inline;color:none;border:none;background:none !important}
		.card form button{display: block;width: calc(100% - 16px);margin: auto;margin-bottom: 8px;margin-top: 8px}
		.card{max-width:100% !important;margin:5px 0px;}
	</style>
</head>
<body>
	<?php include("./includes/nav.php"); ?>
	<div class="container">
		<?php if(SignSys::checkLoggedIn()): ?>
			<h4>Current Friends</h4>
			<hr>
			<div class="row center">
				<?php $friends = $frienddb->getFriends($_COOKIE["uid"]); ?>
				<?php if(count($friends) > 0): ?>
					<?php foreach($friends as $person): ?>
					<?php $pid= $person["id"] ?>
					<div class="col-md-4 col-sm-12">
						<div class="card">
							<h4 class="doc">
								<?= $person["fullname"] ?>
								<small><?= $person["email"] ?></small>
							</h4>
							<div>
								<button onclick="window.location='/chat.php?p=<?= $pid ?>'" 
										style="width:calc(50% - 18.5px);color:#fff" 
										class="rbtn primary">
										Chat
								</button>
								<button id="remove" 
										href=<?= "/response.php?id={$person['id']}&res=remove&csrf={$_SESSION['csrf']}"?> 
										style="width:calc(50% - 18.5px);color:#fff" 
										class="rbtn secondary">
										Remove
								</button>
							</div>
						</div>
					</div>
					<?php endforeach ?>
				<?php else: ?>	
					<h1 style="text-align:center;width:100%;">You have no friends.</h1>
				<?php endif ?>
			</div>
		<?php endif ?>
	</div>
	<div class="container">
		<?php if(SignSys::checkLoggedIn()): ?>
			<hr>
			<h4>Recommended Friends</h4>
			<hr>
			<div class="row center">
				<?php foreach($frienddb->getNoRequests($_COOKIE["uid"]) as $person): ?>
				<div class="col-md-4 col-sm-12">
					<div class="card">
						<h4 class="doc">
							<?= $person[1] ?>
							<small><?= $person[2] ?></small>
						</h4>
						<?php if($frienddb->getFriendRequest($_COOKIE["uid"],$person[0])): ?>
						<form method="post" action="/removerequest.php">
							<input name="csrf" type="hidden" value="<?= $_SESSION['csrf']; ?>">
							<input name="id" type="hidden" value="<?= $person[0]; ?>">
							<button class="primary" type="submit">Remove Friend</button>
						</form>
						<?php else: ?>
						<form method="post" action="/addfriend.php">
							<input name="csrf" type="hidden" value="<?= $_SESSION['csrf']; ?>">
							<input name="id" type="hidden" value="<?= $person[0]; ?>">
							<button class="primary" type="submit">Add Friend</button>
						</form>
						<?php endif ?>
					</div>
				</div>
				<?php endforeach ?>
			</div>
		<?php endif ?>
	</div>
	<?php if(@$user["confirm"] == false && SignSys::checkLoggedIn()): ?>
		<p>Check your mail inbox to confirm your email!</p>
	<?php endif ?>

	<?php if(!SignSys::checkLoggedIn()): ?>
		<div style="text-align:center" class="container">
			<br>
			<img src="/assets/img.png" alt="">
			<h1>Chat</h1>
			<p>Chat is simple website that let's you add friends and chat with people from all around the world.</p>
			<a class="button primary" href='/login.php'>Login</a>
			<a class="button primary" href='/signup.php'>Signup</a>
		</div>
	<?php endif ?>
</body>
<?php require("./includes/foot.php") ?>