<?php
// on recupere l'id dans url
$id = $_GET['ID'];
// on verifie si la session est active 
require_once('../conn.php');
if (!$_SESSION['user']) {
	header('location: ../connexion.php');
}
// requette de verification de la perssonne connecter 
$sql1 = $con->prepare("SELECT * FROM user WHERE ID= ?");
$sql1->execute(array($id));
$result = $sql1->fetch();
// verifie si on click sur le boutton login
if (isset($_POST['login'])) {
	// on verifie si on a inserer les informations 
	if (isset($_POST['type']) and !empty($_POST['type']) and isset($_POST['nom']) and !empty($_POST['nom']) and isset($_POST['prenom']) and !empty($_POST['prenom']) and isset($_POST['email']) and !empty($_POST['email'])) {
		// on recupere les information entree par l'utulisateur
		$type = $_POST['type'];
		$nom = htmlspecialchars(trim($_POST['nom']));
		$prenom = htmlspecialchars(trim($_POST['prenom']));
		$email = htmlspecialchars(trim($_POST['email']));
		$erreu = "";
		$erre = "";
		// requete de verificatition l'email entree par l'utulisateur
		$sql = $con->prepare("SELECT * FROM user WHERE ID = ?");
		$sql->execute(array($id));
		if ($sql->rowCount() > 0) {
			// requette la modification des information des utulisateurs 
			$req = $con->prepare("UPDATE user SET TYPE=?,NOM=?,PRENOM=?,EMAIL=? WHERE ID=?");
			$req->execute(array($type, $nom, $prenom, $email, $id)); ?>
			<script type="text/javascript">
				alert('Information Modifier Avec succes');
				window.location = ('admin.php');
			</script>

		<?php } else { ?>
			<script type="text/javascript">
				alert('Information non Modifier');
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

	<title>Formulaire de Modification</title>
	<style>

	</style>
</head>

<body>
	<div class="container">
		<form method="POST">
			<h2>Modification Des Infos</h2>
			<?php if (isset($erreu)) { ?>
				<p class='erreu'><?php echo "$erreu"; ?></p>
			<?php } ?>

			<?php if (isset($erre)) { ?>
				<p class='erreu'><?php echo "$erre"; ?></p>
			<?php } ?>
			<div class="form-group">
				<label for="type">Type :</label>
				<div class="checkbox-container">
					<input type="radio" name="type" id="admin" value="admin" checked>
					<label for="admin">Admin</label>

					<input type="radio" name="type" id="user" value="user">
					<label for="user">User</label>
				</div>
			</div>

			<div class="form-group">
				<label for="nom">Nom :</label>
				<input type="text" id="nom" name="nom" value="<?php echo $result['NOM'];  ?>">
			</div>
			<div class="form-group">
				<label for="prenom">Prénom :</label>
				<input type="text" id="prenom" name="prenom" value="<?php echo $result['PRENOM']; ?>">
			</div>
			<div class="form-group">
				<label for="email">Email :</label>
				<input type="email" id="email" name="email" value="<?php echo $result['EMAIL'];  ?>">
			</div>
			<div class="button-container">
				<button type="button" class="cancel-button" onclick="window.location.href = 'admin.php';">Annuler</button>
				<button type="submit" class="submit-button" name="login">Modifier</button>
			</div>
		</form>
	</div>
</body>

</html>