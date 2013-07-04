<?php
	require_once($_SERVER['DOCUMENT_ROOT']."/fulltube/lib/php/db.php");
	$db = new db();
	$username = addslashes($_POST['username']);
	$password = md5($_POST['password']);
	if(strlen($username)<1 || strlen($password)<1){
		$output = "<p>You must type your username and password</p>";
	}else{
		$user = $db->row("
			SELECT id, username, password, email FROM user 
			WHERE username='".$username."' AND password='".$password."'
		");
		$output = "Wrong username or password";
		if(count($user)>0){
			session_start();
			$_SESSION['user'] = array("id"=> $user['id'],"username"=>$user['username'], "password"=>$user['password'], "email"=>$user['email']);
			$output="success";
		}
	}
	echo $output;
?>
