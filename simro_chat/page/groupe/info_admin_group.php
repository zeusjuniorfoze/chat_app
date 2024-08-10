<?php
require_once("../conn.php");
if (!$_SESSION['user']) {
    header('location: ../connexion.php');
}
$id_tchat = $_SESSION['tchat'];
// requette pour afficher la liste des utulisateur associer a au groupe 
$sql1 = $con->prepare("SELECT u.PHOTO , u.NOM,u.PRENOM,a.TYPE_USER_GROUP,u.ID FROM user_group a,user u WHERE a.ID=u.ID AND a.IDG=? GROUP BY a.ID");
$sql1->execute(array($id_tchat));
// requtte pour compter le nombre de membre dans le groupe et le nom du groupe
$sql2 = $con->prepare("SELECT g.PHOTO, g.NOM , COUNT(a.ID) as nombre_membre FROM user_group a , groupe g WHERE a.IDG=g.IDG AND a.IDG= ?");
$sql2->execute(array($id_tchat));
$c = $sql2->fetch();
$chemin = "../";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Méta-données de la page -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Styles CSS pour la mise en page -->
    <link rel="stylesheet" href="../cssm/stylesg.css">

    <!-- Inclure la bibliothèque Boxicons pour les icônes -->
    <link href='../boxicons/css/boxicons.min.css' rel='stylesheet'>
    <!-- Titre de la page -->
    <title>Info_group</title>
</head>

<body>

    <!-- Conteneur principal avec thème clair -->
    <div class="contact-container light-theme">
        <a class="left-arrow-alt" href="tchat_admin_group.php" style="font-size: 30px; color: black; padding: 100px; margin-top: -5%; position: absolute;"><i class="bx bx-left-arrow-alt"></i></a>
        <!-- Section du profil du groupe -->
        <div class="profile light-theme">
            <!-- Image du groupe -->
            <img src="<?php echo $chemin . $c['PHOTO']; ?>" alt="Group Picture">

            <!-- Informations du groupe -->
            <div class="user-info">
                <!-- Nom du groupe -->
                <p class="name"><?php echo $c['NOM']; ?></p>
                <!-- Nombre de membres du groupe -->
                <p class="number"><?php echo $c['nombre_membre']; ?> membres</p>
            </div>
        </div>

        <!-- Section des groupes en commun -->
        <div class="groups light-theme">
            <!-- Titre de la section -->
            <h2>Listes des membres</h2>

            <!-- Liste des membres du groupe -->
            <ul class="member-list" style=" overflow-y: scroll; ">
                <?php while ($b = $sql1->fetch()) { ?>
                    <li class="member">
                        <!-- Image du membre -->
                        <div class="member-avatar">
                            <img src="<?php echo $chemin . $b['PHOTO']; ?>" alt="Photo de profil">
                        </div>
                        <!-- Informations du membre -->
                        <div class="member-info">
                            <!-- Nom et prénom du membre -->
                            <p class="member-name"><?php echo $b['NOM']; ?> <?php echo $b['PRENOM']; ?></p>
                            <!-- Type de groupe du membre -->
                            <p class="member-type"><?php echo $b['TYPE_USER_GROUP']; ?></p>
                            <a style=" color: red; font-size: 30px; " onclick="return confirm('Voulez Vous Suprimer Cet Utulisateur Du Groupe ?')" href="suprim_admin_membre.php?ID=<?php echo $b['ID'] ?>"><i class="bx bx-trash"></i></a>
                        </div>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <div>
            <!-- Bouton pour ajouter des membres au groupe -->
            <a style="text-decoration: none;" href="ajout_admin_user_group.php"><button class="create-group-btn1" style="margin-bottom: 4%; "><i class='bx bx-plus-circle' style="font-size: 15px ;"></i> Ajouter des membres au groupe</button></a>
            <!-- Bouton pour inviter dans le groupe via un lien -->
            <a style="text-decoration: none;" onclick="return confirm('Voulez Vous Quitter Le Groupe ???')" href="quitt_group.php"><button class="create-group-btn"><i class='bx bx-log-out'></i> Quitter Le Groupe</button> </a>
        </div>
    </div>
</body>

</html>