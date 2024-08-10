	<?php 
	require_once('../conn.php');
	if(!$_SESSION['user']){
		header('location: ../connexion.php');
	}
	$_GET['ID'];
	$user=$_GET['ID'];
	$id=$_SESSION['id'];
	$tchat=$_SESSION['tchat'];

// on recupere les informations de l'utulisateur qui veux supprimer
	$sql=$con->prepare(" SELECT * FROM user_group WHERE ID=? AND IDG=?");
	$sql->execute(array($id,$tchat));
	$retour=$sql->fetch();
// on verifier si c'est un admin qui veux suprimer
	if ($retour['TYPE_USER_GROUP'] == 'admin') {
		$sql=$con->prepare(" UPDATE user_group SET TYPE_USER_GROUP=? WHERE ID=? AND IDG=?");
		$sql->execute(array('admin',$user,$tchat)); ?>
		<script type="text/javascript">
			alert('Utulisateur Retirer Avec Success !');
			window.location='info_group.php';
		</script>
	<?php } else { ?>
		<script type="text/javascript">
			alert('Seul Les Administrateur Du Groupe Peuvent Suprimer Des Membres !!');
			window.location='info_group.php';
		</script>
	<?php  }
 ?> 