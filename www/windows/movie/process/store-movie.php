<?php
	session_start();
	if(!isset($_SESSION['user'])){
		die("This feature is not avaible while not logged in");
	}
	require_once($_SERVER['DOCUMENT_ROOT']."/fulltube/lib/php/ch.php");
	require_once($_SERVER['DOCUMENT_ROOT']."/fulltube/lib/php/db.php");
	$db = new db();
	$output = "The requested movie does already exists";
	$request = array();
	$request['api_key'] = '45c80e6b2744a32e35c346da83443a3c';
	$request['append_to_response']='trailers,casts';
	$url_given = addslashes($_REQUEST['href']);
	$url_info = explode("&", $url_given);
	$tmdb_id = $url_info[0];
	$movie_url = addslashes($url_info[1]);
	$quality_id = addslashes($url_info[2]);
	$movie_exists = $db->row("SELECT tmdb_id FROM movie WHERE tmdb_id=".$tmdb_id);
	if($movie_exists==NULL){
		$output = ch::getOutput("http://api.themoviedb.org/3/movie/".$tmdb_id."?".http_build_query($request));
		$movieData = json_decode($output);
		$trailer = isset($movieData->trailers->youtube[0]->source) ? $movieData->trailers->youtube[0]->source : NULL;
		$title = isset($movieData->title) ? $movieData->title : NULL;
		$production_company = isset($movieData->production_companies[0]->name) ? $movieData->production_companies[0]->name : NULL;
		$storeMovie = $db->query("
				INSERT INTO movie (
				tmdb_id, imdb_id, original_title, title, release_date, runtime, vote_average, vote_count, 
				poster_path, tagline, youtube_trailer, overview
			) VALUES (
				".$movieData->id.",
				'".$movieData->imdb_id."', 
				'".addslashes($movieData->original_title)."', 
				'".addslashes($title)."', 
				'".$movieData->release_date."',
				'".$movieData->runtime."',
				".$movieData->vote_average.",
				".$movieData->vote_count.",
				'".$movieData->poster_path."',
				'".addslashes($movieData->tagline)."', 
				'".$trailer."',
				'".addslashes($movieData->overview)."'
			)
		");
		if($storeMovie){
			$output = 'Movie "'.$movieData->title.'" stored succesfully!';
			$movie_id = mysql_insert_id();
			//insert movieurl and provider // only youtube for now - provider id is 1
			$db->query("INSERT INTO movie_url (url, provider_id, movie_id, quality_id) VALUES ('".$movie_url."',1 ,".$movie_id.",".$quality_id.")");
			//insert casts for movie
			if(count($movieData->casts->cast)>0){
				$cast_ids = array_map(function($o){
					return $o->id;
				},$movieData->casts->cast);
				$cast_select = "SELECT id, tmdb_id FROM cast WHERE tmdb_id IN('".implode("','",$cast_ids)."')";
				$cast_find = $db->assoc($cast_select);
				$all_casts = $movieData->casts->cast;
				if(count($cast_find)>0){
					foreach($all_casts as $i=>$cast){
						foreach($cast_find as $old_cast){
							if($cast->id == $old_cast['tmdb_id']){
								//$existing_cast_ids[] = $old_cast['id'];
								unset($all_casts[$i]);
							}
						}
					}
				}
				if(count($all_casts)>0){
					foreach($all_casts as $cast){
						$cast_to_insert[] = "'".implode("','",array($cast->id, addslashes($cast->name), $cast->profile_path))."'"; 
					}
					//insert new actors for new movie
					$db->query("INSERT INTO cast (tmdb_id, full_name, profile_path) VALUES(".implode('),(', $cast_to_insert).")");
				}
				//clean up
				$db->query('DELETE FROM movie_casts WHERE movie_id='.$movie_id);
				//find all presented casts for new movie
				$get_fresh_casts = $db->assoc($cast_select);
				$movie_cast_insert = array();
				if(count($get_fresh_casts)>0){
					foreach($get_fresh_casts as $cast){
						$movie_cast_insert[] = "'".implode("','",array($movie_id, $cast['id']))."'";
					}
				}
				//insert movie casts
				$db->query("INSERT INTO movie_casts(movie_id, cast_id) VALUES(".implode('),(', $movie_cast_insert).")");
			}
			//insert genre
			if(count($movieData->genres)>0){
				$genre_ids = array_map(function($o){
					return $o->name;
				},$movieData->genres);
				$genre_select = "SELECT id, name FROM genre WHERE name IN('".implode("','",$genre_ids)."')";
				$genre_find = $db->assoc($genre_select);
				$all_genre = $movieData->genres;
				if(count($genre_find)>0){
					foreach($all_genre as $i=>$genre){
						foreach($genre_find as $old_genre){
							if($genre->name == $old_genre['name']){
								//$existing_genre_ids[] = $old_genre['id'];
								unset($all_genre[$i]);
							}
						}
					}
				}
				if(count($all_genre)>0){
					foreach($all_genre as $genre){
						$genre_to_insert[] = "'".implode("','",array($genre->name))."'";
					}
					$db->query("INSERT INTO genre (name) VALUES(".implode('),(', $genre_to_insert).")");
				}
				$get_fresh_genre = $db->assoc($genre_select);
				foreach($get_fresh_genre as $genre){
					$movie_genre_insert[] = "'".implode("','",array($genre['id'], $movie_id))."'";
				}
				$db->query("INSERT INTO movie_genre(genre_id, movie_id) VALUES(".implode('),(', $movie_genre_insert).")");
			}
		}else{
			$output = "Failed to store movie in database";
		}
	}
	echo '<h1>'.$output.'</h1>';
?>
