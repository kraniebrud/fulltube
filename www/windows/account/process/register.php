<?php
	require_once($_SERVER['DOCUMENT_ROOT']."/fulltube/lib/php/db.php");
	$db = new db();
	$username = addslashes($_POST['username']);
	$password = addslashes($_POST['password']);
	$email = addslashes($_POST['email']);
	$output = "";
	if(strlen($username)<2){
		$output .= "<p>Username must be atleast 2 characters long</p>";
	}
	if(strlen($password)<4){
		$output .= "<p>Password must be atleast 4 characters long</p>";
	}
	if(filter_var($email,FILTER_VALIDATE_EMAIL) === false){
		$output .= "<p>Please enter a valid email adress</p>";
	}
	if(strlen($output)<1){
		if(count($db->row("SELECT id FROM user WHERE username='".$username."' OR email='".$email."'"))>0){
			$output = "Username ".$username." or email ".$email." does already exist";
		}else{
			$password = md5($password);
			// remove activated when mailserver works!!
			$create_user = $db->query("
				INSERT INTO user (username, password, email, activated) 
				VALUES ('".$username."', '".$password."', '".$email."', 1)
			");
			if($create_user){
				$output = "success";
			}else{
				$output = "Failed to create user";
			}
		}
	}
	echo $output;
?>
