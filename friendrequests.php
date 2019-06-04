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
			<div class="row center">
				<?php $friends = $frienddb->getRequestsForUser($_COOKIE["uid"]); ?>
				<?php if(count($friends) > 0): ?>
					<?php foreach($friends as $person): ?>
					<div class="col-md-4 col-sm-12">
						<div class="card">
							<h4 class="doc">
								<?= $person["fullname"] ?>
								<small><?= $person["email"] ?></small>
							</h4>
							<div>
								<button id="accept" href=<?= "/response.php?id={$person['id']}&res=accept&csrf={$_SESSION['csrf']}" ?> style="width:calc(50% - 18.5px);color:#fff" class="rbtn primary">Accept</button>
								<button id="remove" href=<?= "/response.php?id={$person['id']}&res=remove&csrf={$_SESSION['csrf']}"?> style="width:calc(50% - 18.5px);color:#fff" class="rbtn secondary">Remove</button>
							</div>
						</div>
					</div>
					<?php endforeach ?>
				<?php else: ?>	
					<h1 style="text-align:center;width:100%;">You have no Requests yet.</h1>
				<?php endif ?>
			</div>
		</div>
</body>
<script>
	var rbtns = document.getElementsByClassName("rbtn");
	for(var btn of rbtns){
		btn.addEventListener("click",(e)=>{
			let href = e.target.getAttribute("href");
			axios.get(href).then((data)=>{
				let json = data.data;
				if(json.status == 200)
					e.target.parentElement.parentElement.remove();				
			}).catch((error)=>{
				alert(error);
			});
		});
	}
</script>
<?php require("./includes/foot.php") ?>