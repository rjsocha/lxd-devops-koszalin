<?php
	session_start();
	header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
	if(!isset($_SESSION['namespace'])) {
		header('HTTP/1.0 403 Forbidden');
	}
	die();

