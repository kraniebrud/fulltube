<?php
	session_start();
?>
<!DOCTYPE html>
<html lang="EN">
<head>
	<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta charset="utf-8">
	<link rel="stylesheet" href="css/stylesheet.css" type="text/css" charset="utf-8">
	<link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css" type="text/css" charset="utf-8">
	<link rel="stylesheet" href="css/window-pops.css" type="text/css" charset="utf-8">
	<script type="text/javascript" src="lib/js/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="lib/js/jquery.mousewheel.min.js"></script>
<title>Fulltube</title>
</head>
<body>
	<header id="site-header">
		<h1>
			<a href="/fulltube">
				<span>FULL</span>tube
			</a>
		</h1>
	</header>
	<nav id="sidebar">
		<ul>
			<li><a href="#" class="icon-search" id="search"><span>Search</span></a></li>
			<li><a href="#" class="icon-group" id="browse-users"><span>Browse users</span></a></li>
			<li><a href="#" class="icon-reorder" id="reorder"><span>Reorder</span></a></li>
		</ul>
		<?php if(isset($_SESSION['user'])):	?>
		<ul class="logged">
			<li><a href="#" class="icon-user" id="account"><span><?php echo $_SESSION['user']['username']; ?></span></a></li>
			<li><a href="#" class="icon-plus" id="add-movie"><span>Add movie</span></a></li>
			<li><a href="#movie:favorite=<?php echo $_SESSION['user']['id'] ?>" class="icon-heart" id="favorites"><span>Favorites</span></a></li>
			<li><a href="#" class="icon-list" id="movie-lists"><span>Movie lists</span></a></li>
			<li><a href="#movie:personal-rating=<?php echo $_SESSION['user']['id'] ?>" class="icon-star" id="personal-rated"><span>Personal rated</span></a></li>
		</ul>
		<?php else: ?>
		<ul>
			<li><a href="#" class="icon-lock" id="account"><span>Login</span></a></li>
		</ul>
		<?php endif; ?>
	</nav>
	<div id="movie-holder">
	</div>
	<section id="window-pop">
		<article>
		</article>
	</section>
	<script type="text/javascript" src="lib/js/movie-sorter.ajax.js"></script>
</body>
</html>
