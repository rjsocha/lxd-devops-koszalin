<?php
	session_start();
	if(!isset($_SESSION['namespace'])) {
		header('Location: /');
		die();
	}
	require('f.php');
	$ns = $_SESSION['namespace'];
	$n = "/home/socha/.vm/namespace/" . $ns;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>devops://Koszalin</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap-confirmation2/dist/bootstrap-confirmation.min.js"></script>
  <style>
.tooltip-inner {
    max-width: 100% !important;
}
.tty__container.enabled button{
  display:none;
}

.tty__container iframe {
  border: 0;
  width: 100%;
}
 </style>
</head>
<body>

<div class="jumbotron text-center" style="margin-bottom:0">
  <h1>devops://Koszalin</h1>
</div>

<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="/">/</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/password.php">Hasło</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/logout.php">Wyloguj</a>
      </li>
      <li class="nav-item">
	<?php
	if(file_exists($n . '/email')) {
		$email = file_get_contents($n . '/email');
		echo '<span class="nav-link disabled">' . $email . '</span>';
	}
	?>
      </li>
    </ul>
  </div>  
</nav>

<div class="container" style="margin-top:30px">

<?php
		show_note();
?>
  <div class="row">
    <div class="col-sm-4">
      <h2>Pula VM</h2>
      <ul class="nav nav-pills flex-column">
<?php
	$p=get_pool();
	shuffle($p);
	foreach($p as $vm) {
        	echo '<li class="nav-item">';
		$vi = get_vm_info($vm);
		if(isset($vi[0]['state']['network']['eth0']['addresses'][1])) {
			$t=$vi[0]['state']['network']['eth0']['addresses'][1]['address'] . '/64';
		} else {
			$t="collecting";
		}
		echo "<a class=\"nav-link\" data-toggle=\"tooltip\" title=\"". $t . "\" data-placement=\"left\" href=\"/use.php?vm=$vm\">$vm</a>";
        	echo '</li>';
	}

?>
      </ul>
      <div>
  <form  action="/createvm.php" method=post>
    <div class="form-group form-check-inline">
      <input type="text" class="form-control" id="email" placeholder="Nazwa dla VM" name="vm">
&nbsp;&nbsp;
    <button type="submit" class="btn btn-success">Nowa</button>
   </div>
  </form>
	</div>
    </div>
    <div class="col-sm-8">
<table class="table table-striped">
  <tbody>
<?php
	$p=get_pool($ns);
	sort($p);
	foreach($p as $vm) {
		echo "<tr><td>";
		$vi = get_vm_info($vm);
		$status=$vi[0]['status'];
		if($status=='Running') {
			echo '<button type="button" class="btn btn-warning btn-lg">' .$vm  .'</button></br>';
		if(isset($vi[0]['state']['network']['eth0']['addresses'][0])) {
			echo "<h5>INET4: " . $vi[0]['state']['network']['eth0']['addresses'][0]['address']  . "</h5>";
		}
		if(isset($vi[0]['state']['network']['eth0']['addresses'][1])) {
			echo "<h5>INET6: " . $vi[0]['state']['network']['eth0']['addresses'][1]['address'] . "</h5>";
		}
		$handle = fopen($n . "/vm/" . $vm, "r");
		if ($handle) {
		while (($line = fgets($handle)) !== false) {
			if(preg_match("/^LXD_PASS=(.+)$/",$line,$match)) {
				echo "Login: ubuntu</br>";
				echo "Hasło: <b>" . $match[1] . "</b>";
			}
    		}
    		fclose($handle);
		} 
		echo "</br>";
		echo "SSH: ${vm}.lxd.nauka.ga</br>";
		} else {
			echo '<button type="button" class="btn btn-secondary btn-lg">' .$vm  .'</button></br>';
		}
		if($status=='Running') {
			echo "Konsola web: ";
			echo "<a href=\"http://${vm}.lxd.nauka.ga:8022/\" target=\"_blank\">http://${vm}.lxd.nauka.ga:8022/</a><br>";
                        echo '<div class="tty__container">';
                        echo '<button class="tty__open" data-url="' . "http://${vm}.lxd.nauka.ga:8022/" . '">';
                        echo 'KONSOLA';
                        echo '</button></div>';

		}
		echo "</br>";
		echo '<div class="row">';
		echo '<div class="col">';
		if($status=='Running') {
			echo '<a role="button" class="btn btn-primary" href="/stop.php?vm=' .$vm . '">STOP</a>';
		}
		if($status=='Stopped') {
			echo '<a role="button" class="btn btn-success" href="/start.php?vm=' .$vm . '">START</a>';
		}
		echo '</div>';
		echo '<div class="col text-right">';
		echo '<a href="/delete.php?vm=' .$vm .'" class="btn btn-large btn-danger" data-toggle="confirmation"';
		echo ' data-btn-cancel-label="NIE" data-btn-cancel-class="btn-success"';
		echo ' data-btn-ok-label="TAK" data-btn-ok-class="btn-danger"';
		echo ' data-title="UWAGA" data-content="Kontynuować?">';
		echo 'USUŃ</a>';
		echo '</div></div>';

		//echo "<pre>";
		//print_r($vi);
		echo "</td></tr>";
	}
?>
  </tbody>
</table>
    </div>
  </div>
</div>
<script>
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();   
});
$('[data-toggle=confirmation]').confirmation({
  rootSelector: '[data-toggle=confirmation]',
  // other options
});
(function(d){
  var btn = d.querySelector('.tty__open');
  var container = d.querySelector('.tty__container');
  var iframe = null;
  
  btn.addEventListener('click', function(e){
    e.stopPropagation();
    e.preventDefault();
    
    container.classList.add('enabled');
    iframe = d.createElement('iframe');
    iframe.setAttribute('src', btn.getAttribute('data-url'));
    
    container.appendChild(iframe);
  });
})(document);
</script>
</body>
</html>
