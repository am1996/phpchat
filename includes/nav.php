<header>
	<span class="logo col-sm-3 col-md-3">
		<a style="text-decoration:none;color:var(--header-fore-color) !important;" href="/">
		<?php if(!SignSys::checkLoggedIn()): ?>
			Chat
		<?php else: ?>
			<?= explode(" ",$_SESSION["user"]["fullname"])[0] ?>
		<?php endif ?>
		</a>
	</span>
	<?php if(!SignSys::checkLoggedIn()): ?>
		<a style="float:right" class="button col-sm-2 col-md-2" href='/login.php'>Login</a>
		<a style="float:right" class="button col-sm-2 col-md-2" href='/signup.php'>Signup</a>
	<?php else: ?>
		<?php $user = $_SESSION["user"]; ?>
		<a style="float:right" class="button col-sm-4 col-md-4" href='/logout.php'>Logout</a>
		<a style="float:right" class="button col-sm-4 col-md-4" href='/friendrequests.php'>Requests</a>
		<a style="float:right" class="button col-sm-4 col-md-4" href='/edit.php'>Edit</a>
	<?php endif ?>
</header>