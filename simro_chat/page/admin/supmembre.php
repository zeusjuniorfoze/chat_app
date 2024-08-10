<?php
// connexion a la bd
require_once('../conn.php');
// verification si la session user est active 
if (!$_SESSION['user']) {
	header('location: ../connexion.php');
}

if (isset($_GET['ID']) and !empty($_GET['ID'])) {
	$id = $_GET['ID'];
	$sql1 = $con->prepare('SELECT * FROM user WHERE ID=?');
	$sql1->execute(array($id));
	if ($sql1->rowCount() > 0) {
		$sql2 = $con->prepare('DELETE FROM user WHERE ID=?');
		$sql2->execute(array($id)); ?>
		<script type="text/javascript">
			alert('Utulisateur Suprimer Avec Sucess');
			window.location = "admin.php";
		</script>
<?php  } else {
		echo "aucun membre n'a ete trouver";
	}
} else {
	echo "id non recuperer";
}
?>