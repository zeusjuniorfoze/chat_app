<?php 
	require_once('conn.php');
	$user=$_SESSION['user'];
	$sql=$con->prepare("UPDATE user SET STATUT = ? WHERE email=?");
	$sql->execute(array('hors ligne',$user));	
	session_destroy();
	header('location: connexion.php');
 ?>