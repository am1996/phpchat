<?php require("./includes/head.php"); ?>
<?php
	if(!isset($_GET["p"])){
		die("No one to chat with");
	}
	$frnd = $userdb->getUserById($_GET["p"]);
	if(!isset($frnd) || $frienddb->checkFriends($_GET["p"],$_SESSION["user"]["id"]) === NULL )
		die("You aren't his friend. You can't chat with him.");
?>
<body>
	<style>
		form{text-align:center;padding:10px;}
		div{
			padding:10px;
			text-align:center;
			border:1px solid #ddd;
			border-radius:3px;
			background-color:#f0f0f0;
			color:#999;
			margin:8px;
			box-sizing:border-box;
		}
		.msgbox{
			height:50vh;
			overflow-y:auto;
			overscroll-behavior: contain contain;
			scroll-behavior: smooth;
			overflow-wrap: break-word;
			padding-bottom: 40px;
		}
		#msg{height:42px;width:92%;resize:none;margin:0;box-sizing:border-box;float:left;}
		#send{width:7%;margin:0;}
		.me{text-align:right;color:blue;}
		.you{text-align:left;color:red;}
	</style>
	<?php if(SignSys::checkLoggedIn()): ?>
	<section id="container">
		<div>Chatting with <?= $frnd["email"] ?> - <?= $frnd["fullname"] ?></div>
		<div v-on:scroll="scrolling = true" class="msgbox" id="msgbox">
			<p v-for="msg in msgs"  v-bind:class="[(msg.sender == 'me') ? 'me':'you']">
				{{msg.msg}}
			</p>
		</div>
		<form method="POST" action="chat.php">
			<input id="uid" name="sender" type="hidden" value="<?= $_SESSION['user']['id'] ?>">
			<input id="csrf" name="csrf" type="hidden" value="<?= $_SESSION['csrf']; ?>">
			<textarea v-model="msg" placeholder="Your Message..." name="msg" id="msg"></textarea>
			<button v-on:click="sendMsg($event)" id="send" class="primary" type="sumbit">&#9993;</button>
		</form>
	</section>
	<?php else: ?>
		<h1>Please login to use this page!</h1>
	<?php endif; ?>
	<script type="text/javascript" src="/assets/vue.min.js"></script>
	<script src="/assets/main.min.js"></script>
</body>
<?php require("./includes/foot.php") ?>