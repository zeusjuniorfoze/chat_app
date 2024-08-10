<?php
// 
require_once 'conn.php';
?>
<!DOCTYPE html>
<html>

<head>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="cssm/s avec boostrap.css">
	<link href="boxicons/css/boxicons.min.css" rel="stylesheet">
	<title>ACCUEIL</title>
</head>

<body>
	<nav class="cc-nav navbar nav-dark ">
		<div class="container-fluid">
			<!-- image du logo -->
			<a class="navbar-brand py-1 mx-3" href="home.php">
				<img src="img/simro_logo.PNG" alt="" width="100" height="100" class="d-inline-block align-text-top">
			</a>
			<!-- liste des element du  menue -->
			<h1 class="fw-bolder"><i style="color: #120cef;">SIMRO</i><i style="color: #f3940b;">CHAT</i></h1>
			<ul class="navbar-nav ms-auto mb-2lg-0">
				<li><a style=" font-size: 25px; " href="inscription.php" class="btn btn-lg btn-in my-2"><i class='bx bx-user-plus'></i> S'INSCRIRE</a></li>
				<li><a style=" font-size: 25px; " href="connexion.php" class="btn btn-con"><i class='bx bx-log-in'></i> SE CONNECTER</a></li>
			</ul>
		</div>
	</nav>
</body>
</html>