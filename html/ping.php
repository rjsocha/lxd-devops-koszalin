<?php
	session_start();
	$_SESSION['ping']=rand();
	echo $_SESSION['ping'];
