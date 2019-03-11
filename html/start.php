<?php
	session_start();
	if(!isset($_SESSION['namespace'])) {
		header('Location: /');
		die();
	}
	require('f.php');
	$ns = $_SESSION['namespace'];
	$n = "/home/socha/.vm/namespace/" . $ns;
	if(!file_exists($n)) {
		warnme('Internal error a021');
		header('Location: /app.php');
		die();
	}

	$vm = $_REQUEST['vm'];
	if(@strlen($vm)==0) {
		warnme('Nie podano nazwy dla VM');
		header('Location: /app.php');
		die();
	}
	if(!preg_match("/^[a-z][-a-z0-9]*$/",$vm)) {
		warnme('Niepoprawna nazwa dla VM');
		header('Location: /app.php');
		die();
	}
	if(!file_exists('/home/socha/.vm/pool/' . $vm)) {
		warnme('VM o nazwie <b>' . $vm . '</b> nie istnieje!');
		header('Location: /app.php');
		die();
	}
	$last=exec(sprintf("/home/socha/start_vm %s %s",$vm,$ns),$out,$res);
	if($res == 0) {
		infome('Maszyna wirtualna <b>'.$vm.'</b> została uruchomiona.');
	} else {
		warnme('Wystąpił bład przy uruchamianiu maszyny wirtualnej (' .$last. ').');
	}
	header('Location: /app.php');
