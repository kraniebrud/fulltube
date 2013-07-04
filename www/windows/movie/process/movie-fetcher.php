<?php
	session_start();
	if(!isset($_SESSION['user'])){
		die("This feature is not avaible while not logged in");
	}
	require_once($_SERVER['DOCUMENT_ROOT']."/fulltube/lib/php/ch.php");
	require_once($_SERVER['DOCUMENT_ROOT']."/fulltube/lib/php/db.php");
	$db = new db();
	$output = "No request given";
	$request = array();
	$request['api_key'] = '45c80e6b2744a32e35c346da83443a3c';
	$request['append_to_response']='trailers,casts';
	if(isset($_REQUEST['search'])){
		$request['query'] = trim($_REQUEST['search']);
		$search = strtolower(addslashes(trim($_REQUEST['search'])));
		$youtube_url = addslashes(trim($_REQUEST['youtube_url']));
		$quality_id = addslashes($_REQUEST['quality_id']);
		if (preg_match('/youtube\.com\/watch\?v=([^\&\?\/]+)/', $youtube_url, $id)) {
		  $youtube_id = $id[1];
		} else if (preg_match('/youtube\.com\/embed\/([^\&\?\/]+)/', $youtube_url, $id)) {
		  $youtube_id = $id[1];
		} else if (preg_match('/youtube\.com\/v\/([^\&\?\/]+)/', $youtube_url, $id)) {
		  $youtube_id = $id[1];
		} else if (preg_match('/youtu\.be\/([^\&\?\/]+)/', $youtube_url, $id)) {
		  $youtube_id = $id[1];
		} else {  
			die("Please enter a valid youtube adress");
		}
		/*$search_exists = $db->row(
			"SELECT movie.id, movie.original_title, movie.title, moviesearch.request
			FROM movie
			LEFT JOIN moviesearch ON movie.id = moviesearch.movie_id 
			WHERE movie.original_title OR movie.title OR moviesearch.request = '".$search."'
			"
		);
		if(count($search_exists)>0){
			$output = "movie already exists";
		}else{*/
			$output = ch::getOutput("http://api.themoviedb.org/3/search/movie?".http_build_query($request));
		//}
	}
	$return=$output;
	$movieData = json_decode($return);
?>
	<header>
		<h1>Results for requested movie "<?php echo $search ?>"</h1>
		<h2>To ensure the right result, please select a title from the list below</h2>
	</header>
	<select name="insert_movie">
	<?php foreach($movieData->results as $data): ?>
		<option value="<?php echo $data->id.'&'.$youtube_id.'&'.$quality_id; ?>">
			<?php echo $data->original_title ?> - <?php echo substr($data->release_date, 0, 4)  ?>
		</option>
	<?php endforeach; ?>
	</select>
	<input type="submit" value="Store movie" id="store-movie">
	<footer>
		<h3>Didn't find the movie you were looking for?</h3>
		<p>Try specifying your movie title further for better results!</p>
		<a href="#add-movie" id="return" class="button">Go back</a>
	</footer>
