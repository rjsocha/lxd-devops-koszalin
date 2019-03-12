<?php
function wrn($w) {
	warnme($w);
	header('Location: /app.php');
	die();
}

	require('f.php');
	session_start();
	if(!isset($_SESSION['namespace'])) {
		header("Location: /");
		die();
	}

	$n = "/home/socha/.vm/namespace/" . $_SESSION['namespace'];
        if(file_exists($n . "/password")) {
	        $usr_pass = file_get_contents($n . "/password");
        } else {
		wrn('internal error a000');
	}
	if(!(isset($_REQUEST['opassword']) && isset($_REQUEST['npassword1']) && isset($_REQUEST['npassword2']))) {
		wrn('internal error a001');
	}
	if(strlen($_REQUEST['opassword'])==0 || strlen($_REQUEST['npassword1'])==0 || strlen($_REQUEST['npassword2'])==0) {
		wrn('Hasła nie mogą być puste!');
	}
	$opass = md5($_REQUEST['opassword']);
	if($opass !=  $usr_pass) {
		wrn('Niepoprawne hasło użytkownika!');
	}
	$npass1 = md5($_REQUEST['npassword1']);
	$npass2 = md5($_REQUEST['npassword2']);
	if($npass1 != $npass2) {
		wrn('Nowe hasło nie zostało prawidłowo powtórzone!');
	}

	if($opass == $npass1) {
		wrn('Nowe hasło jest takie samo jak poprzednie!');
	}
	file_put_contents($n . "/password",$npass1);
	infome('Hasło zostało prawidłowo zmienione!');
	header('Location: /app.php');
