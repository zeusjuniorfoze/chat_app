<?php
require_once 'conn.php';

if (isset($_POST['login'])) {
    if (!empty($_FILES['photo']) && !empty($_POST['email']) && !empty($_POST['pass']) && !empty($_POST['nom']) && !empty($_POST['prenom'])) {
        $nom = htmlspecialchars(trim($_POST['nom']));
        $prenom = htmlspecialchars(trim($_POST['prenom']));
        $email = htmlspecialchars(trim($_POST['email']));
        $pass = sha1(htmlspecialchars(trim($_POST['pass'])));
        $erreu = "";
        $erre = "";

        // Vérifier s'il y a une erreur lors du téléchargement de la photo
        if ($_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
            $erreu = "Erreur lors du téléchargement de la photo.";
        } else {
            // Déplacer la photo téléchargée vers un dossier sur le serveur
            $uploadDir = 'uploads/';
            $photoName = $_FILES['photo']['name'];
            $photoPath = $uploadDir . $photoName;

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath)) {
                // Insertion des données dans la base de données avec le chemin de la photo de profil
                $req = $con->prepare("INSERT INTO user (TYPE, NOM, PRENOM, EMAIL, STATUT, DATE_DE_CREATION, MOT_DE_PASS, PHOTO) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $req->execute(array("user", $nom, $prenom, $email, "hors ligne", date('y/m/d'), $pass, $photoPath));

                // Redirection après l'inscription
                echo '<script>alert("Compte créé avec succès ! Connectez-vous."); window.location="connexion.php";</script>';
                exit();
            } else {
                $erreu = "Erreur lors du téléchargement de la photo.";
            }
        }
    } else {
        $erre = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="cssm/code.css">
    <title>Formulaire d'Inscription</title>
</head>
<body>
<div class="container">
    <form method="POST" enctype="multipart/form-data">
        <div class="form-groupe">
            <label for="photo_profil">Photo de profil :</label>
            <div class="image-container">
                <img src="img/icone.png" alt="Photo de profil">
                <input type="file" id="photo_profil" name="photo" accept="image/*">
                <label for="photo_profil" class="add-button">Ajouter</label>
            </div>
        </div>
        <?php if (isset($erreu)) {?>
            <p class='erreu'><?php echo "$erreu"; ?></p>
        <?php }?>
        <div class="form-group">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom">
        </div>
        <div class="form-group">
            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom">
        </div>
        <div class="form-group">
            <label for="email">Email :</label>
            <input type="email" id="email" name="email">
        </div>
        <div class="form-group">
            <label for="mot_de_passe">Mot de passe :</label>
            <input type="password" id="mot_de_passe" name="pass">
        </div>
        <?php if (isset($erre)) {?>
            <p class='erreu'><?php echo "$erre"; ?></p>
        <?php }?>
        <div class="button-container">
            <button type="button" class="cancel-button" onclick="window.location.href = 'home.php';">Annuler</button>
            <button type="submit" class="submit-button" name="login">Valider</button>
        </div>
    </form>
</div>
</body>
</html>
