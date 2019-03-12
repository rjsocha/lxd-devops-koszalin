<?php
	session_start();
	if(!isset($_SESSION['namespace'])) {
		header('Location: /');
		die();
	}
	require('f.php');
	$n = "/home/socha/.vm/namespace/" . $_SESSION['namespace'];
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
	if(file_exists('/home/socha/.vm/pool/' . $vm)) {
		warnme('VM o nazwie <b>' . $vm . '</b> już istnieje!');
		header('Location: /app.php');
		die();
	}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>devops://Koszalin</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/asset/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="/asset/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="/asset/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="/asset/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<style>
/* Center the loader */
#loader {
  position: absolute;
  left: 50%;
  top: 50%;
  z-index: 1;
  width: 150px;
  height: 150px;
  margin: -75px 0 0 -75px;
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 220px;
  height: 220px;
  -webkit-animation: spin 2s linear infinite;
  animation: spin 2s linear infinite;
  display: none;
}

@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Add animation to "page content" */
.animate-bottom {
  position: relative;
  -webkit-animation-name: animatebottom;
  -webkit-animation-duration: 1s;
  animation-name: animatebottom;
  animation-duration: 1s
}

@-webkit-keyframes animatebottom {
  from { bottom:-100px; opacity:0 } 
  to { bottom:0px; opacity:1 }
}

@keyframes animatebottom { 
  from{ bottom:-100px; opacity:0 } 
  to{ bottom:0; opacity:1 }
}

#result {
  display: none;
  text-align: center;
}
</style>
</head>
<body style="margin-top: 32px">
<div class="container" style="margin-top: 0px">

<div id="loader"></div>
<div id="creating" style="text-align: center">
<h1>tworzenie nowej maszyny wirtualnej</h1>
</div>
<div style="display:none;" id="result" class="animate-bottom">
</div>
<?php
	$rnd=rand();
	$job = array('version' => 'v0', 'action' => 'create', 'name' => $vm);
	$_SESSION['job_' . $rnd]=json_encode($job);
?>
<script>
$(document).ajaxStop(function() {
    $('#loader').hide();
    $('#creating').hide();
    $('#result').show();
});
$(document).ajaxStart(function() {
    $('#loader').show();
});
$("#result").load( "/vm.php?<?php echo "key=$rnd";?>");
</script>
</div>
</body>
</html>
