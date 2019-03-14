<?php
	require('f.php');
	session_start();
	if(!isset($_SESSION['namespace'])) {
		header('HTTP/1.0 403 Forbidden');
		die();
	}
	$n = "/home/socha/.vm/namespace/" . $_SESSION['namespace'];

	if(!file_exists($n)) {
		header('HTTP/1.0 403 Forbidden');
		die();
	}
	$data = json_decode(file_get_contents("php://input"), true);
	$ret=array();
	if(!isset($data['vm'])) {
		$ret['status'] = 100;
		$ret['error'] = 'incorrect request';
		echo json_encode($ret);
		die();
	}
	$vm = $data['vm'];
	if(@strlen($vm)==0 || !preg_match("/^[a-z][-a-z0-9]*$/",$vm)) {
		$ret['status'] = 101;
		$ret['error'] = 'incorrect vm name';
		echo json_encode($ret);
		die();
	}
	if(file_exists('/home/socha/.vm/pool/' . $vm)) {
		$ret['status'] = 102;
		$ret['error'] = 'vm already exists';
		echo json_encode($ret);
		die();

	}
	$last=exec('/var/www/scripts/spawn-new-xhr ' . $vm,$out,$res);
	if($res!=0) {
		$ret['status'] = 200;
		$ret['error'] = 'create vm failed';
		$ret['info'] = $last;
		$ret['rc'] = $res;
	} else {
		$ret['status'] = 0;
		$ret['info'] = $last;
	}
	echo json_encode($ret);
	die();

