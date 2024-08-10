<?php 
require_once('../conn.php');
if(!$_SESSION['user']){
		header('location: ../connexion.php');
	}
	$e=$_SESSION['user'];
	$sql=$con->prepare("DELETE FROM user WHERE email=?");
			$sql->execute(array($e));
			header('location: ../deconnexion.php');
 ?>