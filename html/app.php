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
  <link rel="stylesheet" href="/asset/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="/asset/jquery-ui.min.css">
  <script src="/asset/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="/asset/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="/asset/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <script src="/asset/bootstrap-confirmation.min.js"></script>
  <script src="/asset/clipboard.min.js"></script>
  <script src="/asset/jquery-ui.min.js"></script>
  <style>
.tooltip-inner {
    max-width: 100% !important;
}
.tty_container.enabled button{
  display:none;
}

.tty_container iframe {
  border: 1px solid green;
  width: 100%;
  height: 100%;
}
.ui-resizable-helper { border: 2px dotted #00F; }

.tty_container{  padding-bottom: 15px; }

.copyme{
	cursor:pointer;
}

.copyme:hover{
	text-decoration: underline;
}
 
 </style>
</head>
<body>

<div class="text-center" style="margin-bottom:0; height: 100%; background: #a6a6a6">
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
	$eid=0;
	foreach($p as $vm) {
		echo "<tr><td class=\"vm-container\">";
		$vi = get_vm_info($vm);
		$status=$vi[0]['status'];
		if($status=='Running') {
			echo '<button type="button" class="btn btn-warning btn-lg">' .$vm  .'</button></br>';
		if(isset($vi[0]['state']['network']['eth0']['addresses'][0])) {
			echo "<h5>INET4: " . $vi[0]['state']['network']['eth0']['addresses'][0]['address']  . "</h5>";
		}
		if(isset($vi[0]['state']['network']['eth0']['addresses'][1])) {
			$addr = $vi[0]['state']['network']['eth0']['addresses'][1]['address'];
			echo "<h5>INET6: <span class=\"copyme\" data-command=\"$addr\">$addr</span></h5>";
		}
		$handle = fopen($n . "/vm/" . $vm, "r");
		if ($handle) {
		while (($line = fgets($handle)) !== false) {
			if(preg_match("/^LXD_PASS=(.+)$/",$line,$match)) {
				echo "Login: <span  class=\"copyme\" data-command-enter=\"ubuntu\">ubuntu</span></br>";
				echo "Hasło: <b><span class=\"copyme\"  style=\"color:#14c504\" data-command-enter=\"" .$match[1] . "\">" . $match[1] . "</span></b>";
			}
    		}
    		fclose($handle);
		} 
		echo "</br>";
		echo "DNS: <span class=\"copyme\" data-command=\"${vm}.lxd.nauka.ga\">${vm}.lxd.nauka.ga</span></br>";
		} else {
			echo '<button type="button" class="btn btn-secondary btn-lg">' .$vm  .'</button></br>';
		}
		if($status=='Running') {
			//echo "Konsola web: ";
			//echo "<a href=\"https://${vm}.lxd.nauka.ga:8022/\" target=\"_blank\">https://${vm}.lxd.nauka.ga:8022/</a><br>";
                        echo '<div class="tty_container terminal">';
                        echo '<button class="tty_open btn btn-dark " data-url="' . "https://${vm}.lxd.nauka.ga:8022" . '">KONSOLA</button>';
			echo '</div>';

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
		$eid += 10;
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

terms=document.querySelectorAll('.terminal');
for(var i=0;i<terms.length;i++) {
	var btn=terms[i].querySelector('.tty_open');
	var iframe = null;
	btn.addEventListener('click', function(e){
    	e.stopPropagation();
    	e.preventDefault();
		this.parentNode.classList.add('enabled');
		this.parentNode.classList.add('resizable');
		iframe = document.createElement('iframe');
		iframe.setAttribute('src', this.getAttribute('data-url'));
		this.parentNode.insertBefore(iframe,this);
 		$(".resizable" ).resizable({
			helper: "ui-resizable-helper"
	    });
	});
}

window.onbeforeunload = function() {
	$(".terminal").html("");
}

document.querySelectorAll('.copyme').forEach(function(el){
	var parent = el.closest('td');
	el.addEventListener('click', function(e){
		console.log(el.innerHTML, parent);
		var iframe = parent.querySelector('iframe');
		if(null !== iframe){
			var target = iframe.contentWindow;

			if(el.hasAttribute('data-command-enter')){
				target.postMessage(JSON.stringify({value:el.innerHTML+"\n"}), iframe.src);
			}

			if(el.hasAttribute('data-command')){
				target.postMessage(JSON.stringify({value:el.innerHTML}), iframe.src);
			}
		}	
	});
});


//var clipboard = new ClipboardJS('.copyme');
//clipboard.on('success', function(e) {
//        console.log(e);
//});
//clipboard.on('error', function(e) {
//   console.log(e);
//});
</script>
</body>
</html>
