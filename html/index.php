<?php
	session_start();
	require('f.php');
	if(isset($_SESSION['namespace'])) {
		header("Location: /app.php");
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

  <center><p>client ip: <strong><?php	echo $_SERVER['REMOTE_ADDR']; ?></strong> </p></center>
</div>
</body>
</html>
