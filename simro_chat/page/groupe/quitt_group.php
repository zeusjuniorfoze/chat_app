<?php
require_once('../conn.php');
if (!$_SESSION['user']) {
	header('location: ../connexion.php');
}
$id = $_SESSION['id'];
$tchat = $_SESSION['tchat'];
$sql = $con->prepare(" DELETE FROM user_group WHERE ID=? AND IDG=?");
$sql->execute(array($id, $tchat));
if ($sql->rowCount() > 0) {
	header('location: ../user/tchat.php');
}
?>