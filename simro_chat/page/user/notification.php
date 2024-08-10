<?php
require_once '../conn.php';
if (!$_SESSION['user']) {
    header('location: ../connexion.php');
}
$email = $_SESSION['notification'];
$e = $_SESSION['ID'];
$noti = $con->prepare("SELECT SUM(*) AS notification FROM message WHERE STATUT= ? AND ID= ? AND IDR= ? OR ID= ? AND IDR= ?");
$noti->execute(array('0', $email, $e, $e, $email));
while ($no = $noti->fetch()) {;
    echo $no['notification'];}
?>
