<?php
	session_start();
	if(!(isset($_SESSION['user']))){
		die("YOU NEED TO BE LOGGED IN ORDER ORDER TO USE THIS FEATURE");
	}
	require_once($_SERVER['DOCUMENT_ROOT']."/fulltube/www/lib/php/db.php");
	$db = new db();
	$movie_id = addslashes($_GET['movie_id']);
	$movie_feature = $db->row("SELECT favorite, personal_rating, movie_list_id FROM movie_feature WHERE movie_id=".$movie_id. " AND user_id=".$_SESSION['user']['id']);
	if(count($movie_feature)<1){
		$db->query("
			INSERT INTO movie_feature(movie_id, user_id) 
			VALUES(".$movie_id.", ".$_SESSION['user']['id'].")
		");
	}
	//insert favorite
	if(isset($_GET['favorite'])){
		$favorite = addslashes($_GET['favorite']==1);
		if($favorite==1){
			if($db->query("UPDATE movie_feature SET favorite=1 WHERE movie_id=".$movie_id." AND user_id=".$_SESSION['user']['id'])){
				echo "SUCCES: Movie added as favorite";
			}
		}else if($favorite==0){
			$db->query("UPDATE movie_feature SET favorite=NULL WHERE movie_id=".$movie_id." AND user_id=".$_SESSION['user']['id']);
		}
	}
	//insert personal rating
	if(isset($_GET['personal_rating'])){
		$personal_rating = $_GET['personal_rating'];
		if($personal_rating>0){
			if($db->query("UPDATE movie_feature SET personal_rating=".$personal_rating." WHERE movie_id=".$movie_id." AND user_id=".$_SESSION['user']['id'])){
				echo "SUCCES: PERSONAL RATING";
			}
		}else{
			$db->query("UPDATE movie_feature SET personal_rating=NULL WHERE movie_id=".$movie_id." AND user_id=".$_SESSION['user']['id']);
		}
	}
?>
