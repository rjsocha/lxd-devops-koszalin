<?php
	session_start();
	if(!isset($_SESSION['namespace']) || !isset($_REQUEST['key']) || !isset($_SESSION['job_' . $_REQUEST['key']]) ) {
		echo '<div class="alert alert-danger" role="alert">BŁĄD WYWOŁANIA</div>';
		die();
	}
	$job = $_SESSION['job_' . $_REQUEST['key']];
	unset($_SESSION['job_' . $_REQUEST['key']]);
	$job=json_decode($job,TRUE);
	$res=0;
	if($job['version'] == 'v0' && $job['action'] == 'create' && strlen($job['name'])) {
		$last=exec('/home/socha/spawn-new ' . $job['name'],$out,$res);
	}
	if($res!=0) {
		echo '<div class="alert alert-danger" role="alert">NIEPOWODZENIE (' . $last . ')</div>';
		echo '<p></p><p></p><h3><a class="btn btn-primary" role="button" href="/app.php">POWRÓT</a><h3>';
	} else {
		//echo '<div class="alert alert-success" role="alert">GOTOWE</div>';
		echo '<script>window.location.href = "/";</script>';
	}
	//

