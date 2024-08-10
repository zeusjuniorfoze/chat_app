<?php
require_once('../conn.php');
// si la session n'est pas declarer 
if (!$_SESSION['user']) {
  header('location: ../connexion.php');
}
// on affecte notre session id a une variable 
$id = $_SESSION['id'];
// si on insere le bouton avec ou san les champs remplis
if (isset($_POST['login'])) {
  // si les champs ne sont pas vide 
  if (!empty($_FILES['photo']) and !empty($_POST['nom']) and !empty($_POST['description'])) {
    // on recupere les informations entree par l'utulisateur
    $nom = htmlspecialchars(trim($_POST['nom']));
    $description = htmlspecialchars(trim($_POST['description']));
    // on de clarre notre variable d'erreur
    $erreu = "";
    // Vérifier s'il y a une erreur lors du téléchargement de la photo
    if ($_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
      $erre = "Erreur lors du téléchargement de la photo.";
    } else {
      // Déplacer la photo téléchargée vers un dossier sur le serveur
      $uploadDir = '../uploads/';
      $photoName = $_FILES['photo']['name'];
      $photoPath = $uploadDir . $photoName;
      $bd = 'uploads/' . $photoName;

      if (move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath)) {
        // Insertion des données dans la base de données avec le chemin de la photo de profil
        $sql = $con->prepare("INSERT INTO groupe (PHOTO,NOM,DESCRIPTION,DATE_DE_CREATION) 
			VALUES (?,?,?,?)");
        $sql->execute(array($bd, $nom, $description, date('d/m/y')));
        if ($sql) {
          // requette de recuperation de l'id du groupe cree
          $sql1 = $con->prepare(" SELECT * FROM groupe WHERE NOM=? AND DESCRIPTION=?");
          $sql1->execute(array($nom, $description));
          $recup_group = $sql1->fetch();
          $id_groupe = $recup_group['IDG'];
          // requette d'insertion du user qui cree le groupe
          $sql2 = $con->prepare(" INSERT INTO user_group (ID,IDG,TYPE_USER_GROUP,DATE_AJOUT)
				VALUES (?,?,?,?)");
          $sql2->execute(array($id, $id_groupe, "admin", date('d/m/y')));
          // Redirection après l'inscription
          echo '<script>alert("Groupe créé avec succès !"); window.location="../user/discussion.php";</script>';
          exit();
        }
      } else {
        $erre = "Erreur lors du téléchargement de la photo.";
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
  <link rel="stylesheet" type="text/css" href="../cssm/code.css">

  <title>Formulaire d'Inscription</title>
  <style>

  </style>
</head>

<body>
  <div class="container">
    <form method="POST" enctype="multipart/form-data">
      <h2>Créer un nouveau Groupe</h2>
      <div class="form-groupe">
        <label for="photo_profil">Photo de profil :</label>
        <div class="image-container">
          <img src="../img/icone.png" alt="Photo de profil">
          <input type="file" id="photo_profil" name="photo" accept="image/*">
          <label for="photo_profil" class="add-button">Ajouter</label>
        </div>
      </div>
      <div class="form-group">
        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" placeholder="nom">
      </div>
      <div class="form-group">
        <label for="prenom">Description :</label>
        <input type="text" id="prenom" name="description" placeholder="description">
      </div>

      <?php if (isset($erreu)) { ?>
        <p class='erreu'><?php echo "$erreu"; ?></p>
      <?php } ?>

      <?php if (isset($erre)) { ?>
        <p class='erreu'><?php echo "$erre"; ?></p>
      <?php } ?>
      <div class="button-container">
        <button type="button" class="cancel-button" onclick="window.location.href = '../user/tchat.php';">Annuler</button>
        <button type="submit" class="submit-button" name="login">Valider</button>
      </div>
    </form>
  </div>
</body>
<script type="text/javascript">
  // appercue avant
  document.addEventListener('DOMContentLoaded', function() {
    const inputPhoto = document.getElementById('photo_profil');
    const imgPreview = document.querySelector('.image-container img');

    inputPhoto.addEventListener('change', function() {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          imgPreview.src = e.target.result;
        }
        reader.readAsDataURL(file);
      }
    });
  });
</script>

</html>