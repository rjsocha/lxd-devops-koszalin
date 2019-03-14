<?php
	require('f.php');
	session_start();
	if(!isset($_SESSION['namespace'])) {
		header('HTTP/1.0 403 Forbidden');
		die();
	}
	$p=get_pool();
	shuffle($p);
	$ret = array();
	foreach($p as $vm) {
		$vi = get_vm_info($vm);
		if(isset($vi[0]['state']['network']['eth0']['addresses'][1])) {
			$inet6=$vi[0]['state']['network']['eth0']['addresses'][1]['address'] . '/64';
		} else {
			$inet6="collecting";
		}
		$ret[] = array("vm"=>$vm,"inet6" => $inet6);
	}
	echo json_encode($ret);
