<?php	
	session_start();
	if(isset($_SESSION['namespace'])) {
		session_destroy();
	}
	header("Location: /");
