<?php 
	require_once('../conn.php');
// verification si la session user est active 
	if(!$_SESSION['user']){
		header('location: ../connexion.php');
	}
	
	if (isset($_GET['ID']) AND !empty($_GET['ID'])) {
		$id=$_GET['ID'];
		$sql1=$con->prepare('SELECT * FROM groupe WHERE IDG=?');
		$sql1->execute(array($id));
		if ($sql1->rowCount()>0) {
			$sql2=$con->prepare('DELETE FROM groupe WHERE IDG=?');
			$sql2->execute(array($id)); ?>
			<script type="text/javascript">
				alert('Groupe Suprimer avec success');
				window.location=('../admin/admin.php');
			</script>
		<?php  }else{
			echo"aucun membre n'a ete trouver";
		}
	}else{
		echo "id non recuperer";
	}
  ?>