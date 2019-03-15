<?php
	require('f.php');
	session_start();
	if(!isset($_SESSION['namespace'])) {
		header('HTTP/1.0 403 Forbidden');
		die();
	}
	$ns = $_SESSION['namespace'];
	$ns_dir = "/home/socha/.vm/namespace/" . $ns;

	if(!file_exists($ns_dir)) {
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
	if(@strlen($vm)==0 || !preg_match("/^[a-zA-Z]([0-9a-zA-Z]){1,15}$/",$vm)) {
		$ret['status'] = 101;
		$ret['error'] = 'incorrect vm name';
		echo json_encode($ret);
		die();
	}
	if(!file_exists($ns_dir . '/vm/' . $vm)) {
		$ret['status'] = 102;
		$ret['error'] = 'vm do not exist';
		echo json_encode($ret);
		die();

	}
	$last=exec(sprintf("/var/www/scripts/start_vm %s %s",$vm,$ns),$out,$res);
	if($res!=0) {
		$ret['status'] = 200;
		$ret['error'] = 'start vm failed';
		$ret['info'] = $last;
		$ret['rc'] = $res;
	} else {
		$ret['status'] = 0;
		$ret['info'] = $last;
	}
	echo json_encode($ret);
	die();

