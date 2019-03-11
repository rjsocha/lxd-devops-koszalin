<?php
	session_start();
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
  <style>
  .fakeimg {
    height: 200px;
    background: #aaa;
  }
  </style>
</head>
<body>

<div class="jumbotron text-center" style="margin-bottom:0">
  <h1>devops://Koszalin</h1>
</div>

<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
  <a class="navbar-brand" href="/app.php">/</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="/logout.php">Wyloguj</a>
      </li>
    </ul>
  </div>  
</nav>

<div class="container" style="margin-top:30px">
  <form action="/password_change.php" method=post>
    <div class="form-group">
    <div class="form-group">
      <input type="password" class="form-control" id="pwd" placeholder="Podaj aktualne hasło" name="opassword">
	<p></p>
      <input type="password" class="form-control" id="pwd" placeholder="Podaj nowe hasło" name="npassword1">
	<p></p>
      <input type="password" class="form-control" id="pwd" placeholder="Powtórz nowe hasło" name="npassword2">
    </div>
    <button type="submit" class="btn btn-primary">Zmień</button>
  </form>
</div>

</body>
</html>
