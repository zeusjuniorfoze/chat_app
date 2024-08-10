<?php 
require_once('conn.php');
if(!$_SESSION['user']){
		header('location: ../connexion.php');
	}
		$id=$_POST['id'];
		$nom=$_POST['nom'];
		$prenom=$_POST['prenom'];
		$email=$_POST['email'];
		$sql="UPDATE user SET nom=?,prenom=?,email=? WHERE id=?";
		$param=array($nom,$prenom,$email,$id);
		$res=$con->prepare($sql);
		$res->execute($param);
		
 ?>