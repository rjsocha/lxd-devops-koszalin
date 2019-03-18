<?php
	session_start();
	require('f.php');
	if(isset($_SESSION['namespace'])) {
		header("Location: /app.html");
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
</head>
<style>
.blink_me {
  animation: blinker 1s linear infinite;
}

@keyframes blinker {
  50% {
    opacity: 0;
  }
}
</style>
<body>

<div class="container" style="margin-top: 25px">
  <center><h2>devops://Koszalin</h2></center>
   <?php
	show_note();
?>
  <form action="/namespace.php" method=post>
    <div class="form-group">
      <label for="email">Email:</label>
      <input type="text" class="form-control" id="email" placeholder="Adres email" name="email">
    </div>
    <div class="form-group">
      <label for="pwd">Hasło:</label>
      <input type="password" class="form-control" id="pwd" placeholder="Podaj hasło" name="password">
    </div>
    <button type="submit" class="btn btn-primary">Zaloguj</button>
  </form>

  <center>
<?php
	$ip =  $_SERVER['REMOTE_ADDR'];
	if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
		echo '<p>IP: <strong>' . $_SERVER['REMOTE_ADDR'] . '</strong> </p>';
	} else {
		echo "<p style='font-size: 350%; color: red'><span class='blink_me'>SERIOUSLY?! IPv4???</br> $ip</span></br><small style='font-size: 15%'>(ale będzie częściowo działać ;)</small></p>";
	}
?>
</center>
</div>
</body>
</html>
