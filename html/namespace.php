<?php	
	session_start();
	require('f.php');

	if(isset($_SESSION['namespace'])) {
		header("Location: /app.html");
		die();
	}

function go_login($w="") {
	if(strlen($w)) {
		warnme($w);
	}
	header("Location: /");
	die();
}
function create_session($n) {
	$_SESSION['namespace']=$n;
	header("Location: /app.html");
	die();
}

if(!(isset($_REQUEST['email']) && isset($_REQUEST['password']))) {
	go_login();
}
	$email=$_REQUEST['email'];
	$pass=$_REQUEST['password'];

	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		go_login('Należy podać prawidłowy adres email!');
	}

	$nm = "/home/socha/.vm/namespace/";
	if(!file_exists($nm)) {
		mkdir($nm);
	}

	$email_hash=md5($email);
	$pass = md5($pass);

	if(file_exists($nm . $email_hash)) {
		if(file_exists($nm . $email_hash . "/password")) {
			$usr_pass = file_get_contents($nm . $email_hash . "/password");
		} else {
			die('<h1>ups...</h1>');
		}
		if($pass == $usr_pass) {
			create_session($email_hash);
		} else {
			go_login('Nieprawidłowy login lub hasło!');
		}
	} else {
		// devops
		$def_pass = 'a21c218df41f6d7fd032535fe20394e2';
		if($def_pass == $pass) {
			if(!file_exists("/home/socha/.vm")) {
				mkdir("/home/socha/.vm");
			}
			if(!file_exists($nm)) {
				mkdir($nm);
			}
			mkdir($nm . $email_hash);
			file_put_contents($nm . $email_hash . "/password",$def_pass);
			file_put_contents($nm . $email_hash . "/email",$email);
			create_session($email_hash);
		} else {
			go_login('Nieprawidłowy login lub hasło!');
		}
	}
