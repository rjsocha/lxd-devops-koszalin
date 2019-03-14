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
  <script src="/asset/axios.min.js"></script>
  <style>
.tooltip-inner {
    max-width: 100% !important;
}
.tty_container.enabled button{
  display:none;
}

.tty_container iframe {
  border: 1px solid lightgrey;
  width: 100%;
  height: 100%;
}
.ui-resizable-helper { border: 2px dotted #00F; }

.tty_container{  padding-bottom: 18px; }
.tty_container.enabled{ background-color: lightgrey; height: 25em; }
.tty_container .status-bar { display: none; }
.tty_container.enabled .status-bar { display: flex; justify-content: space-between; right: 30px; position: absolute; bottom: 3px; left: 5px; font-size: 12px; color: #555; font-weight: bold; }

.red { background-color: red; color: white; }

.red::placeholder { 
 color: white;

}

.bg-trans {
	transition: background-color 0.6s ease;
}

.copyme{
	cursor:pointer;
}

.copyme:hover{
	text-decoration: underline;
}
 
.clipme {
	cursor: pointer;
	width: 1em;
	height: 1em;
	vertical-align: initial;
}

.reload {
	cursor: pointer;
	width: 18px;
	height: 18px;
	vertical-align: top;
}
.reload.spin {
	animation: spin 2s linear infinite;
}

.clipme:hover {
  animation: shake 0.82s cubic-bezier(.36,.07,.19,.97) both;
  transform: translate3d(0, 0, 0);
  backface-visibility: hidden;
}

@keyframes shake {
  10%, 90% {
    transform: translate3d(-1px, 0, 0);
  }
  
  20%, 80% {
    transform: translate3d(2px, 0, 0);
  }

  30%, 50%, 70% {
    transform: translate3d(-2px, 0, 0);
  }

  40%, 60% {
    transform: translate3d(2px, 0, 0);
  }
}
.footer {
  position: fixed;
  left: 0;
  bottom: 0;
  width: 100%;
  background-color: crimson;
  color: white;
  text-align: center;
}

#vm_loader {
  position: absolute;
  left: 50%;
  top: 50%;
  z-index: 1;
  margin: -3em 0 0 -3em;
  border: 16px solid #f3f3f3;
  border-radius: 50%;
  border-top: 16px solid #3498db;
  width: 6em;
  height: 6em;
  animation: spin 2s linear infinite;
  display: none;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.animate-bottom {
  position: relative;
  animation-name: animatebottom;
  animation-duration: 1s
}

@keyframes animatebottom { 
  from{ bottom:-100px; opacity:0 } 
  to{ bottom:0; opacity:1 }
}
</style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-dark navbar-dark">
  <div class="navbar-brand">devops://Koszalin</div>
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

<div class="ontainer-fluid" style="margin:30px 20px;">

<?php
		show_note();
?>
  <div class="row">
    <div class="col-sm-2">
      <h2>Pula VM <img src="/asset/reload.svg" class="reload" id="reload_vm_pool">  </h2>
      <ul class="nav nav-pills flex-column" id="vms">
      </ul>
      <div>
  		<form  action="/createvm.php" method=post>
    		<div class="form-group form-check-inline">
		      <input type="text" class="form-control bg-trans" id="vm_name" placeholder="Nazwa dla VM" name="vm">
		      &nbsp;&nbsp;
		      <button type="submit" class="btn btn-success" id="vm_add">Nowa</button>
		</div>
		</form>
	</div>
    </div>
    <div class="col-sm-10">
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
			echo '<button type="button" class="btn btn-warning btn-lg">' .$vm  .'.lxd.nauka.ga <img src="/asset/clip.svg" data-clipboard-text="'. $vm . '.lxd.nauka.ga" class="clipme"> </button></br>';
		if(isset($vi[0]['state']['network']['eth0']['addresses'][0])) {
			echo "<h5>INET4: " . $vi[0]['state']['network']['eth0']['addresses'][0]['address']  . "</h5>";
		}
		if(isset($vi[0]['state']['network']['eth0']['addresses'][1])) {
			$addr = $vi[0]['state']['network']['eth0']['addresses'][1]['address'];
			echo "<h5>INET6: $addr <img src=\"/asset/clip.svg\" class=\"clipme\" data-clipboard-text=\"$addr\"></h5>";
		}
		$handle = fopen($n . "/vm/" . $vm, "r");
		if ($handle) {
		while (($line = fgets($handle)) !== false) {
			if(preg_match("/^LXD_PASS=(.+)$/",$line,$match)) {
				$vm_pass = $match[1];
				echo "Login: <span  class=\"copyme\" data-command-enter=\"ubuntu\">ubuntu</span> &nbsp;&nbsp;<img src=\"/asset/clip.svg\" class=\"clipme\" data-clipboard-text=\"ubuntu\"></br>";
				echo "Hasło: <b><span class=\"copyme\"  style=\"color:#14c504\" data-command-enter=\"" .$vm_pass . "\">" . $vm_pass . "</span>&nbsp;&nbsp;<img src=\"/asset/clip.svg\" class=\"clipme\" data-clipboard-text=\"$vm_pass\"></b>";
			}
    		}
    		fclose($handle);
		} 
		echo "</br>";
		//echo "DNS: <span class=\"copyme\" data-command=\"${vm}.lxd.nauka.ga\">${vm}.lxd.nauka.ga</span></br>";
		} else {
			echo '<button type="button" class="btn btn-secondary btn-lg">' .$vm  .'</button></br>';
		}
		if($status=='Running') {
			//echo "Konsola web: ";
			//echo "<a href=\"https://${vm}.lxd.nauka.ga:8022/\" target=\"_blank\">https://${vm}.lxd.nauka.ga:8022/</a><br>";
                        echo '<div class="tty_container terminal">';
                        echo '<button class="tty_open btn btn-dark " data-url="' . "https://${vm}.lxd.nauka.ga:8022" . '">KONSOLA</button>';
			echo '<div class="status-bar"><div>' . $vm . '.lxd.nauka.ga</div><div><span class="copyme" data-command="&#12;">CLS</span> <span class="copyme" data-command="&#03;">CTRL+C</span></div><div><b><span class="copyme" data-command="&#04;">CTRL+D</span> </b> </div> <div><span class="copyme" data-command="fontsize:10">10</span> <span class="copyme" data-command="fontsize:12">12</span> <span class="copyme" data-command="fontsize:14">14</span> <span class="copyme" data-command="fontsize:16">16</span> <span class="copyme" data-command="fontsize:18">18</span> <span class="copyme" data-command="fontsize:20">20</span></div></div></div>';

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
		//console.log(el.innerHTML, parent);
		var iframe = parent.querySelector('iframe');
		if(null !== iframe){
			var target = iframe.contentWindow;

			if(el.hasAttribute('data-command-enter')){
				target.postMessage(el.getAttribute('data-command-enter')+"\n", iframe.src);
			}

			if(el.hasAttribute('data-command')){
				target.postMessage(el.getAttribute('data-command'), iframe.src);
			}
		}	
	});
});

newvm = document.querySelector('#vm_add');
newvm.addEventListener('click', function(event){
        event.stopPropagation();
        event.preventDefault();
	var value=document.querySelector("#vm_name").value;
	var rx = /^[a-zA-Z][0-9a-zA-Z]+$/
	if( value == "" || rx.test(value) == false ) {
		setTimeout(function() {
			document.querySelector("#vm_name").classList.remove("red");
		},550);
		document.querySelector("#vm_name").classList.add("red");
		return;
	}
	document.querySelector('#reload_vm_pool').classList.add("spin");
	event.target.disabled=true;
	document.querySelector("#vm_name").disabled=true;
	axios.post('/xhr-create-vm.php', {vm: value})
  .then(function (response) {
    // handle success
	  console.log(response);
	  console.log(response.data);
	  document.querySelector("#vm_name").value="";
  })
  .catch(function (error) {
    // handle error
    console.log('error',error);
  })
  .then(function () {
    // always executed
	event.target.disabled=false;
	document.querySelector("#vm_name").disabled=false;
	document.querySelector('#reload_vm_pool').classList.remove("spin");
	vms_refresh();
  });

});
var vmpool_lock = 0;

vmpool = document.querySelector('#reload_vm_pool');
vmpool.addEventListener('click', function(event){
	if(vmpool_lock)
		return;
	vmpool_lock=1;
	vms_refresh();
});

function vms_refresh(event) {
	document.querySelector('#reload_vm_pool').classList.add("spin");
	axios.get('/xhr-vms.php').then(function (response) {
		// handle success
		if(Array.isArray(response.data)) {
			var arr = response.data;
			vmhtml="";
			for(var i=0; i<arr.length; i++) {
				vm=arr[i];
				vmhtml = vmhtml + '<li class="nav-item"><a class="nav-link" data-toggle="tooltip" title="' + vm.inet6 + '" data-placement="left" href="/use.php?vm=' + vm.vm + '">' + vm.vm +'</a></li>';
			}
			if(vmhtml.length>0) {
				document.querySelector("#vms").innerHTML=vmhtml;
			}
		}
  	}).catch(function (error) {
    		// handle error
	    	console.log('error',error);
  	}).then(function () {
	 	// always executed
		document.querySelector('#reload_vm_pool').classList.remove("spin");
		vmpool_lock=0;
	 });
}
var clipboard = new ClipboardJS('.clipme');

vms_refresh();

//clipboard.on('success', function(e) {
//        console.log(e);
//});
//clipboard.on('error', function(e) {
//   console.log(e);
//});
</script>
<footer class="footer">
 <div class="container">
	Wersja: <?php echo get_my_version(); ?>
 </div>
</footer>
</body>
</html>
