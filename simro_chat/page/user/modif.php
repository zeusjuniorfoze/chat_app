<?php
require_once '../conn.php';
if (!$_SESSION['user']) {
    header('location: ../connexion.php');
}
$e = $_SESSION['user'];
$modif = $con->prepare(" SELECT * FROM user WHERE EMAIL = ?");
$modif->execute(array($e));
$result = $modif->fetch();
if (isset($_POST['login'])) {
    $erreu = "";
    if (!empty($_POST['nom']) and !empty($_POST['prenom']) and !empty($_POST['email'])) {
        $nom = htmlspecialchars(trim($_POST['nom']));
        $prenom = htmlspecialchars(trim($_POST['prenom']));
        $email = htmlspecialchars(trim($_POST['email']));
        $sql = $con->prepare("UPDATE user SET nom=?,prenom=?,email=? WHERE email=?");
        $sql->execute(array($nom, $prenom, $email, $e));
        header('location: ../deconnexion.php');
    } else {
        $erreu = "Veillez Remplir Tout Les Champs";
    }

}
?>
<!DOCTYPE html>
<html>
	<head>
		<style type="text/css">
			/* pour le style des erreur*/
			.l{
				text-decoration: none;
				color: white;
			}
			p{
				color: red;
				margin: 10px 0;
				text-align: center;
				background-color: white;
				border-radius: 10px;
			}
		</style>
		<link rel="stylesheet" type="text/css" href="../cssm/code.css">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>INSCRIPTION</title>
	</head>
	<body>
		<section>
			<div class="imgbox"></div>
				<div class="contentbox">
					<div class="formbox">
						<form id="ins" method="POST" action="">
							<h1><img src="../img/3.png" width="100" height="100"></h1><!-- image sur le formulaire -->
							<?php if (isset($erreu)) {?>
								<p class ='erreu'><?php echo "$erreu"; ?></p>
							<?php }?>

							<div class="imputbox">
								<label>Nouveau Nom</label>
								<input type="text" name="nom" id="nom" placeholder="fozet" value="<?php echo $result['NOM']; ?>"><br>
							</div>
							<div class="imputbox">
								<label>Nouveau Prenom</label>
								<input type="text" name="prenom" id="prenom" placeholder="junior" value="<?php echo $result['PRENOM']; ?>"><br>
							</div>
							<div class="imputbox">
								<label for="email">Nouveau Email</label>
								<input type="text" name="email" id="email" placeholder="fozetj29@gmail.com" value="<?php echo $result['EMAIL']; ?>">
							</div>
							<div class="imputbox">
								<button type="submit" class="lien" name="login" onclick="return confirm('Information Modifier Avec Succes')">Modifier</button>
								<a href="discussion.php" class="l">Annuler</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</section>
	</body>
</html>