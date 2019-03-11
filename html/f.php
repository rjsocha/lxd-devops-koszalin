<?php
function warnme($w) {
	$_SESSION['msg_warning'] = $w;
}
function infome($m) {
	$_SESSION['msg_info'] = $m;
}
function show_note() {
	if(isset($_SESSION['msg_warning'])) {
		$wrn=$_SESSION['msg_warning'];
		unset($_SESSION['msg_warning']);
		echo '<div class="alert alert-danger" role="alert">' . $wrn . '</div>';
	}
	if(isset($_SESSION['msg_info'])) {
		$msg=$_SESSION['msg_info'];
		unset($_SESSION['msg_info']);
		echo '<div class="alert alert-success" role="alert">' . $msg . '</div>';
	}
	echo "<script>setTimeout(function() { $('[role=\"alert\"]').fadeOut('slow','swing');}, 7000);</script>";
}

function get_pool($ns="") {
	if(strlen($ns)) {
		$p = "/home/socha/.vm/namespace/$ns/vm";
	} else {
		$p = '/home/socha/.vm/free';
	}
	$fvn=array();
	if(!file_exists($p)) {
		return $fvn;
	}
	if ($handle = opendir($p)) {
		while (false !== ($entry = readdir($handle))) {
			if($entry== '.' || $entry=='..') {
				continue;
			}
			if(is_link($p . "/" . $entry)) {
				$fvn[] = $entry;
			}
		}
	}
	return $fvn;
}
function get_vm_info($vm) {
	$last=exec('/home/socha/get_info ' . $vm,$out,$res);
	if($res==0) {
		return json_decode($last,TRUE);
	}
	return array();
}
?>
