<?php
require_once 'conn.php';
if (isset($_POST['login'])) {
	if (isset($_POST['email']) && !empty($_POST['email'])) {
		$email = htmlspecialchars(trim($_POST['email']));
		$erreu = "";
		$erre = "";
		$sql = $con->prepare("SELECT * FROM user WHERE EMAIL = ?"); // requete de verificatition des element entree par l'utulisateur
		$sql->execute(array($email)); // on stocker ses element dans un tableaux
		if ($sql->rowCount() > 0) {
			$_SESSION['recup'] = $email;
			header('location: modifpass.php');
		} else {
			$erreu = "Votre Compte N'existe Pas Réessayer !";
		}
	} else {
		$erre = "Veillez Entrée Votre Email !";
	}
}
?>

<!DOCTYPE html>
<html>

<head>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="cssm/pass.css">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="boxicons/css/boxicons.min.css" rel="stylesheet">
	<title>RECUPERATION</title>
</head>

<body>
	<div>
		<nav class="cc-nav navbar ">
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
	</div>
	<div>
		<section>
			<form action="" method="POST">
				<h2>RETOUVER VOTRE COMPTE </h2>
				<fieldset class="b">
					<label form="pass">Veillez entrer votre email du compte pour rechercher votre compte</label><br>
					<input type="email" name="email" id="email">
				</fieldset><br>
				<?php if (isset($erreu)) { ?>
					<p class='erreu'><?php echo "$erreu"; ?></p>
				<?php } ?>

				<?php if (isset($erre)) { ?>
					<p class='erreu'><?php echo "$erre"; ?></p>
				<?php } ?>

				<div class="button-container">
					<button type="button" class="cancel-button" onclick="window.location.href = 'connexion.php';">Annuler</button>
					<button type="submit" class="submit-button" name="login">Rechercher</button>
				</div>
			</form>
		</section>
	</div>
</body>

</html>