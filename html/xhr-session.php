<?php
	session_start();
	if(!isset($_SESSION['namespace'])) {
		header('HTTP/1.0 403 Forbidden');
	}
	die();

