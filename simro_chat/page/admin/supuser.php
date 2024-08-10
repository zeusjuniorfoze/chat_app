<?php
// on recupere notre email passer en parametre
$email = $_GET['email'];
// connexion a al bd 
require_once('../conn.php');
// on verifie si la sesion user est active
if (!$_SESSION['user']) {
	header('location: ../connexion.php');
}
// requete pour suprimer sont compte
$sql = $con->prepare(" DELETE FROM user WHERE EMAIL=?");
$sql->execute(array($email));
// redirection apris la supresion
header('location: ../deconnexion.php');
?>
