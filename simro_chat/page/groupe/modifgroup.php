<?php
$id = $_GET['ID'];
// on verifie si la session est active 
require_once('../conn.php');
if (!$_SESSION['user']) {
	header('location: ../connexion.php');
}
// requette de verification de la perssonne connecter 
$sql1 = $con->prepare("SELECT * FROM groupe WHERE IDG= ?");
$sql1->execute(array($id));
$result = $sql1->fetch();
// verifie si on click sur le boutton login
if (isset($_POST['login'])) {
	// on verifie si on a inserer les informations 
	if (isset($_POST['nom']) and !empty($_POST['nom']) and isset($_POST['description']) and !empty($_POST['description'])) {
		// on recupere les information entree par l'utulisateur
		$nom = htmlspecialchars(trim($_POST['nom']));
		$description = htmlspecialchars(trim($_POST['description']));
		$erreu = "";
		$erre = "";
		// requete de verificatition l'email entree par l'utulisateur
		$sql = $con->prepare("SELECT * FROM groupe WHERE IDG = ?");
		$sql->execute(array($id));
		if ($sql->rowCount() > 0) {
			// requette la modification des information des utulisateurs 
			$req = $con->prepare("UPDATE groupe SET NOM=?,DESCRIPTION=? WHERE IDG=?");
			$req->execute(array($nom, $description, $id)); ?>
			<script type="text/javascript">
				alert('Information Modifier Avec succes');
				window.location = ('../admin/admin.php');
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

	<title>Formulaire d'Inscription</title>
	<style>

	</style>
</head>

<body>
	<div class="container">
		<form method="POST">
			<h2>Créer un nouveau Groupe</h2>
			<div class="form-group">
				<label for="nom">Nom :</label>
				<input type="text" id="nom" name="nom" value="<?php echo $result['NOM']; ?>">
			</div>
			<div class="form-group">
				<label for="prenom">Description :</label>
				<input type="text" id="prenom" name="description" value="<?php echo $result['DESCRIPTION']; ?>">
			</div>

			<?php if (isset($erreu)) { ?>
				<p class='erreu'><?php echo "$erreu"; ?></p>
			<?php } ?>

			<?php if (isset($erre)) { ?>
				<p class='erreu'><?php echo "$erre"; ?></p>
			<?php } ?>
			<div class="button-container">
				<button type="button" class="cancel-button" onclick="window.location.href = '../admin/admin.php';">Annuler</button>
				<button type="submit" class="submit-button" name="login">Modifier</button>
			</div>
		</form>
	</div>
</body>

</html>