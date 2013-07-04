<?php
	session_start();
	if(!isset($_SESSION['user'])){
		die("This feature is not avaible while not logged in");
	}
	require_once($_SERVER['DOCUMENT_ROOT']."/fulltube/lib/php/db.php");
	$db = new db();
	$video_qualities = $db->assoc("SELECT id, resolution FROM quality");
?>
	<header>
		<h1><i class="icon-film"></i> Add movie</h1>
		<h2>Type in the title of your movie and link to youtube <u>full length movie!</u></h2>
	</header>
	<label>Movie title</label>
	<input type="text" name="title" id="movie_title">
	<label>Youtube link</label>
	<input type="text" name="youtube_url" id="youtube_url">
	<label>Video quality up to</label>
	<select name="video_quality" id="video_quality">
	<?php foreach($video_qualities as $quality): ?>
		<option value="<?php echo $quality['id']; ?>"><?php echo $quality['resolution'] ?></option>
	<?php endforeach; ?>
	</select>
	<input type="submit" id="lookup-movie" value="Lookup movie">
<script type="text/javascript" src="/fulltube/windows/movie/add-movie.js"></script>
