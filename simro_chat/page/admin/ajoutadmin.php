<?php
require_once('../conn.php');
if (!$_SESSION['user']) {
	header('location: ../connexion.php');
}
if (isset($_POST['login'])) {
	if (!empty($_POST['type']) and !empty($_POST['email']) and !empty($_POST['pass']) and !empty($_POST['nom']) and !empty($_POST['prenom'])) {
		$type = $_POST['type'];
		$nom = htmlspecialchars(trim($_POST['nom']));
		$prenom = htmlspecialchars(trim($_POST['prenom']));
		$email = htmlspecialchars(trim($_POST['email']));
		$pass = sha1(htmlspecialchars(trim($_POST['pass'])));
		$erreu = "";
		$erre = "";
		//requete de verificatition l'email entree par l'utulisateur
		$sql = $con->prepare("SELECT * FROM user WHERE email = ?");
		$sql->execute(array($email));
		if ($sql->rowCount() > 0) {
			$erreu = "Cet Rmail Ne Respecte Pas Les Normes Déja Veillez Entrée Un Nouveau !";
		} else {
			$req = $con->prepare("INSERT INTO user (TYPE,NOM,PRENOM,EMAIL,STATUT,DATE_DE_CREATION,MOT_DE_PASS) 
				VALUES (?,?,?,?,?,?,?)");
			$req->execute(array("user", $nom, $prenom, $email, "hors ligne", date('d/m/y'), $pass)); ?>
			<script type="text/javascript">
				alert('Compte  crée avec success ! connecter vous');
				window.location = "admin.php";
			</script>
<?php }
	} else {
		$erre = "Veillez Remplir Tout Les Champs !";
	}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="../cssm/code.css">

	<title>Formulaire d'Inscription</title>
	<style>

	</style>
</head>

<body>
	<div class="container">
		<form method="POST">
			<h2>Créer un nouveau compte</h2>
			<div class="form-group">
				<label for="type">Type :</label>
				<div class="checkbox-container">
					<input type="radio" name="type" id="admin" value="admin" checked>
					<label for="admin">Admin</label>

					<input type="radio" name="type" id="user" value="user">
					<label for="user">User</label>
				</div>
				<div class="form-group">
					<label for="nom">Nom :</label>
					<input type="text" id="nom" name="nom">
				</div>
				<div class="form-group">
					<label for="prenom">Prénom :</label>
					<input type="text" id="prenom" name="prenom">
				</div>
				<div class="form-group">
					<label for="email">Email :</label>
					<input type="email" id="email" name="email">
				</div>
				<div class="form-group">
					<label for="mot_de_passe">Mot de passe :</label>
					<input type="password" id="mot_de_passe" name="pass">
				</div>
				<?php if (isset($erreu)) { ?>
					<p class='erreu'><?php echo "$erreu"; ?></p>
				<?php } ?>

				<?php if (isset($erre)) { ?>
					<p class='erreu'><?php echo "$erre"; ?></p>
				<?php } ?>
				<div class="button-container">
					<button type="button" class="cancel-button" onclick="window.location.href = 'admin.php';">Annuler</button>
					<button type="submit" class="submit-button" name="login">Valider</button>
				</div>
		</form>
	</div>
</body>

</html>