<?php
	session_start();
	if(isset($_SESSION['user'])):
?>
	<div id="window-state" class="window-account">
		<header>
			<h1><a href="#account-home"><i class="icon-user"></i> <?php echo $_SESSION['user']['username']; ?></a></h1>
		</header>
		<section id="window-message"></section>
		<div id="account-content">
		</div>
		<footer>
			<a href="#logout" id="logout-user" class="button"><i class="icon-signout"></i> Logout</a>
		</footer>
	</div>
	<script type="text/javascript" src="/fulltube/windows/account/js/account.js"></script>
<?php
	else:
		include('login.php');
	endif;
?>
