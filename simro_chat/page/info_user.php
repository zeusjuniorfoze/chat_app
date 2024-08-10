<?php
require_once('conn.php');
$id_tchat = $_SESSION['tchat'];
$sql = $con->prepare(" SELECT * FROM user WHERE ID= ?");
$sql->execute(array($id_tchat));
$a = $sql->fetch();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Méta-données de la page -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Styles CSS pour la mise en page -->
    <link rel="stylesheet" href="cssm/stylesu.css">

    <!-- Inclure la bibliothèque Boxicons pour les icônes -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- Titre de la page -->
    <title>A propos utilisateur</title>
</head>

<body>
    <!-- Conteneur principal avec thème clair -->
    <div class="contact-container light-theme">

        <!-- Section du profil utilisateur -->
        <div class="profile light-theme">
            <!-- Image de profil -->
            <img src="img/icone.png" alt="" width="100" height="100" class="d-inline-block align-text-top">

            <!-- Informations de l'utilisateur -->
            <div class="user-info">
                <!-- Nom de l'utilisateur -->
                <p class="name"><?php echo $a['NOM']; ?></p>
                <!-- Numéro de téléphone de l'utilisateur -->
                <p class="number"><?php echo $a['EMAIL'] ?></p>
            </div>

            <!-- Actions de l'utilisateur (Audio, Video, Recherche) -->
            <div class="actions">
                <button><i class='bx bxs-phone-call'></i> Audio</button>
                <button><i class='bx bxs-video'></i> Video</button>
                <button><i class='bx bx-search'></i> Recherche</button>
            </div>
        </div>

        <!-- Section des groupes en commun -->
        <div class="groups light-theme">
            <!-- Titre de la section -->
            <h2>Groupes en commun</h2>

            <!-- Liste des groupes avec interaction au survol -->
            <ul class="group-list">
                <li>Etude GSI 2</li>
            </ul>

            <!-- Bouton pour créer un groupe avec l'utilisateur -->
            <a style="text-decoration: none;" href="groupe/"><button class="create-group-btn">Créer un groupe avec <?php echo $a['NOM']; ?></button></a>
        </div>
    </div>

    <!-- Inclusion de la bibliothèque Boxicons (son fichier javascript) -->
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
</body>

</html>