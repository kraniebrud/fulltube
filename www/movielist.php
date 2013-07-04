<?php
	session_start();
	require_once('lib/php/db.php');
	$db = new db();
	//default movieOrder
	$movieOrder = "<span>Order by:</span> Recently added";
	if(isset($_GET['movie'])){
		//
		$movie_get = explode("=",$_GET['movie']);
		//request, this can be search, sort, reorder... etc.
		$request = $movie_get[0];
		//what to do with the request,
		$find = $movie_get[1];
		$movieData = array();
		if($request=='orderby'){
			switch($find){
				case "recently":
					$movieData = $db->assoc("SELECT * FROM movie ORDER BY id DESC");
					$movieOrder = "<span>Order by:</span> Recently added";
				break;
				case "alphabet":
					$movieData = $db->assoc("SELECT * FROM movie ORDER BY original_title ASC");
					$movieOrder = "<span>Order by:</span> Alphabetically";
				break;
				case "rating":
					$movieData = $db->assoc("SELECT * FROM movie ORDER BY vote_average DESC");
					$movieOrder = "<span>Order by:</span> Rating";
				break;
				case "release_date":
					$movieData = $db->assoc("SELECT * FROM movie ORDER BY release_date DESC");
					$movieOrder = "<span>Order by:</span> Release date";
				break;
				case "runtime":
					$movieData = $db->assoc("SELECT * FROM movie ORDER BY runtime DESC");
					$movieOrder = "<span>Order by:</span> Runtime";
				break;
			}
		}
		if($request=='search'){
			$search = addslashes(urldecode($find));
			$movieData = $db->assoc("SELECT * FROM movie WHERE original_title OR title LIKE '%".$search."%'");
			$movieOrder = "<span>Search for:</span> ".$search;
		}
		if($request=='genre'){
			$find_genre = addslashes($find);
			$movieData = $db->assoc("
				SELECT movie.id as id, original_title, tagline, youtube_trailer, 
				vote_average, vote_count, release_date, runtime, 
				poster_path, overview, tmdb_id, imdb_id
				FROM movie
				LEFT JOIN movie_genre ON movie.id = movie_genre.movie_id
				LEFT JOIN genre ON movie_genre.genre_id = genre.id
				WHERE genre.name = '".$find_genre."'");
			$movieOrder = "<span>Browsing genre:</span> ".$find_genre;
		}
		if($request=='favorite'){
			$movieData = $db->assoc("
				SELECT 
					movie.id as id, original_title, tagline, youtube_trailer, 
					vote_average, vote_count, release_date, runtime, 
					poster_path, overview, tmdb_id, imdb_id, username
				FROM movie 
				LEFT JOIN movie_feature ON movie.id = movie_feature.movie_id
				LEFT JOIN user ON movie_feature.user_id = user.id 
				WHERE movie_feature.favorite = 1 AND user_id=".$find
			);
			$movieOrder = "<span>Browsing: </span>".$movieData[0]['username']."'s Favorites";
		}
		if($request=='personal-rating'){
			$movieData = $db->assoc("
				SELECT 
					movie.id as id, original_title, tagline, youtube_trailer, 
					vote_average, vote_count, release_date, runtime, 
					poster_path, overview, tmdb_id, imdb_id, username
				FROM movie 
				LEFT JOIN movie_feature ON movie.id = movie_feature.movie_id
				LEFT JOIN user ON movie_feature.user_id = user.id 
				WHERE movie_feature.personal_rating>0 AND user_id=".$find." ORDER BY personal_rating DESC"
			);
			$movieOrder = "<span>Browsing: </span>".$movieData[0]['username']."'s Favorites";
		}
	}else{
		$movieData = $db->assoc("SELECT * FROM movie ORDER BY id DESC");
	}
	if(!(count($movieData)>0)):
	?>
	<div id="movie-holder">
		<div id="movie-list">
			<ul>
				<li><a href="#">Empty</a></li>
			</ul>
		</div>
		<span id="movie-order">
			<a href="/fulltube" alt="Change movie order or search for specific movie" class="change-order"><?php echo $movieOrder ?> <i class="icon-sort"></i></a>
		</span>
		<div id="movies">
			<span id="movie-order">
				<a href="/fulltube" alt="Clear and reset to default movie list" class="icon-remove-sign icon-2x"></a>
				 There is no movies, damn!
			</span>
		</div>
	</div>
	<?php
	else:
	$movie_url = $db->assoc("
		SELECT url, provider_id, movie_id, resolution FROM movie_url
		LEFT JOIN provider ON movie_url.provider_id = provider.id
		LEFT JOIN quality ON movie_url.quality_id = quality.id
		ORDER BY movie_id
	");
	foreach($movie_url as $data){
		$provider_url[$data['movie_id']] = $data['url'];
		$movie_quality[$data['movie_id']] = $data['resolution'];
	}
	$movie_genre = $db->assoc("
		SELECT name, movie_id FROM genre
		LEFT JOIN movie_genre ON genre.id = movie_genre.genre_id
	");
	foreach($movie_genre as $data){
		$genre[$data['movie_id']][] = $data['name'];
	}
	//CAST
	$movie_cast = $db->assoc("
		SELECT movie_id, full_name, profile_path, tmdb_id FROM cast
		LEFT JOIN movie_casts ON cast.id = movie_casts.cast_id
	");
	foreach($movie_cast as $data){
		$cast[$data['movie_id']][] = array('full_name'=>$data['full_name'],'profile_path'=>$data['profile_path'],'tmdb_id'=>$data['tmdb_id']);
	}
	//MOVIE FEATURES
	if(isset($_SESSION['user'])){
		$movie_feature = $db->assoc("SELECT * FROM movie_feature WHERE user_id=".$_SESSION['user']['id']);
		if(count($movie_feature)>0){
			foreach($movie_feature as $data){
				$feature[$data['movie_id']] = array(
					"feature"=>array(
						'movie_id'=>$data['movie_id'], 
						'user_id'=>$data['user_id'], 
						'favorite'=>$data['favorite'], 
						'personal_rating'=>$data['personal_rating'],
						'movie_list_id'=>$data['movie_list_id']
					)
				);
			}
		}
	}
?>
	<div id="movie-list">
		<ul>
			<?php foreach($movieData as $data): ?>
			<li><a href="#" id="<?php echo $data['id'] ?>"><?php echo isset($data['title']) ? $data['title'] : $data['original_title'] ?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<span id="movie-order">
		<a href="/fulltube" alt="Change movie order or search for specific movie" class="change-order"><?php echo $movieOrder ?> <i class="icon-sort"></i></a>
	</span>
	<div id="movies">
	<?php 
		foreach($movieData as $data):
			$movie_title = isset($data['title']) ? $data['title'] : $data['original_title'];
	?>
		<article data-movie-id="<?php echo $data['id'] ?>">
			<aside>
				<figure>
					<a href="http://cf2.imgobject.com/t/p/w500/<?php echo $data['poster_path'] ?>">
						<img src="http://cf2.imgobject.com/t/p/w185/<?php echo $data['poster_path'] ?>">
					</a>
				</figure>
				<ul class="play-buttons">
					<li>
						<a href="<?php echo $provider_url[$data['id']] ?>" class="play-tube play-movie">play</a>
					</li>
					<?php if($data['youtube_trailer']): ?>
					<li>
						<a href="<?php echo $data['youtube_trailer'] ?>" class="play-tube">watch trailer</a>
					</li>
					<?php endif; ?>
				</ul>
			<?php 
				if(isset($_SESSION['user'])):
			?>
				<ul class="movie-feature">
					<li><i class="icon-list"></i></li>
					<li class="add-favorite">
				<?php
						if(isset($feature[$data['id']]['feature']) && $feature[$data['id']]['feature']['favorite']!=NULL){
							echo '<i class="icon-heart check" alt="Unfavorite movie '.$movie_title.'" data-feature="'.$data['id'].'"></i>';
						}else{
							echo '<i class="icon-heart" alt="Add movie '.$movie_title.' as favorite" data-feature="'.$data['id'].'"></i>';
						}
				?>
					</li>
					<li class="personal-rating">
				<?php
						if(isset($feature[$data['id']]['feature']) && $feature[$data['id']]['feature']['personal_rating']!=NULL){
							echo '<i class="icon-star check" alt="Rate movie '.$movie_title.'" data-feature="'.$data['id'].'"><span>'.$feature[$data['id']]['feature']['personal_rating'].'</span></i>';
						}else{
							echo '<i class="icon-star" alt="Rate movie '.$movie_title.'" data-feature="'.$data['id'].'"><span>0</span></i>';
						}
				?>
					</li>
				</ul>
				<a href="#report" class="broken icon-warning-sign"> report broken</a>
			<?php 
				endif; 
			?>
			</aside>
			<div class="spec-holder">
				<h1><?php echo $movie_title ?></h1>
				<h2><?php echo $data['tagline'] ?></h2>
				<div class="genre">
			<?php
				foreach($genre[$data['id']] as $genre_data){
					echo '<span><a href="#movie:genre='.$genre_data.'">'.$genre_data.'</a></span>';
				}
			?>
				</div>
				<ul class="movie-spec">
					<?php if($data['vote_average']): ?>
					<li>Rating: <?php echo $data['vote_average'] ?> (<?php echo $data['vote_count'] ?> votes)</li>
					<?php endif; ?>
					<li>Director(s): </li>
					<li>Writer(s): </li>
					<li>Release date: <?php echo $data['release_date'] ?></li>
					<li>Production company: </li>
					<li>Runtime: <?php echo $data['runtime'] ?> minutes</li>
					<li>Quality: <?php echo $movie_quality[$data['id']] ?></li>
				</ul>
				<ul class="movie-spec">
					<?php 
						foreach($cast[$data['id']] as $cast_data){
							echo '<li>'.$cast_data['full_name'].'</li>';
						}
					 ?>
				</ul>
				<h3 class="clear-both">Description:</h3>
				<p><?php echo $data['overview'] ?></p>
			</div>
		</article>		
		<?php endforeach; ?>
	</div>
	<?php endif; ?>
	<script type="text/javascript" src="lib/js/controls.js"></script>
	<script type="text/javascript" src="lib/js/window-pops.js"></script>
	<?php 
		if(isset($_SESSION['user'])){
			echo '<script type="text/javascript" src="windows/account/js/movie-feature.js"></script>';
		}
	?>
