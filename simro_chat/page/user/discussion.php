<?php
require_once '../conn.php';
// on verifie si la session de pass est bien active ou pas
if (!$_SESSION['user']) {
  header('location: ../connexion.php');
} else {
  $user = $_SESSION['user'];
  $sql = $con->prepare("UPDATE user SET STATUT =? WHERE EMAIL=?");
  $sql->execute(array('en ligne', $user));
}

// on affecte notre session a une variable
$id = $_SESSION['id'];
$_SESSION['tchat'] = null;

// on fait une requette de verification de l'utulisateur
$sql1 = $con->prepare(" SELECT * FROM user WHERE id = ?");
$sql1->execute(array($id));
$recup_id = $sql1->fetch();

$chemin = "../";
$photo_bd = $recup_id['PHOTO'];
$total = $chemin . $photo_bd;

// requette pour affichet tout les groupe de l'utulisateur
$sql0 = $con->prepare("SELECT G.IDG,G.NOM,G.DESCRIPTION FROM groupe G , user_group A WHERE A.IDG=G.IDG AND ID=? GROUP BY IDG");
$sql0->execute(array($_SESSION['id']));

// pour modifier la photo de profil
if (isset($_POST['modifier'])) {
  // Vérifier si un fichier a été téléchargé
  if (!empty($_FILES['photo']['name'])) {
    $photoTmp = $_FILES['photo']['tmp_name'];
    $photoNom = $_FILES['photo']['name'];
    $parent = '../uploads/' . $photoNom;
    $photoPath = 'uploads/' . $photoNom; // Chemin où enregistrer l'image sur le serveur

    // Déplacer le fichier téléchargé vers le dossier d'images
    move_uploaded_file($photoTmp, $parent);

    // Mettre à jour le chemin de l'image de profil dans la base de données
    $sqlUpdate = $con->prepare("UPDATE user SET PHOTO = ? WHERE ID = ?");
    $sqlUpdate->execute(array($photoPath, $_SESSION['id'])); ?>

    <script type="text/javascript">
      alert('Photo De Profil Modifier Avec Success !');
      window.location = "discussion.php";
    </script>

  <?php
  } else { ?>
    // Si aucun fichier n'a été téléchargé
    <script type="text/javascript">
      alert('Veillez Choisir une image avant');
    </script>
<?php }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <link href="../boxicons/css/boxicons.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="../cssm/discussion.css">
  <style type="text/css">
    
  </style>
  <title>USER BORD</title>
</head>

<body>
  <nav class="cc-nav navbar nav-dark ">
    <div class="container-fluid">
      <!-- image du logo -->
      <a class="navbar-brand py-1 mx-3" href="#">
        <img src="../img/simro_chat.PNG" alt="" width="100" height="100" class="d-inline-block align-text-top">
      </a>
      <!-- liste des element du  menue -->
      <h1 class="fw-bolder"><i style="color: #120cef; font-size: 40px;  ">SIMRO</i><i style="color: #f3940b;">CHAT</i></h1>
      <ul class="navbar-nav ms-auto mb-2lg-0">
        <li><a style=" font-size: 25px; " href="../deconnexion.php" class="btn btn-con"><i class='bx bx-log-in'> </i> DECONNEXION</a></li>
      </ul>
    </div>
  </nav>
  <div class="sidebar">
    <ul class="menu">
      <li><a href="#page1"><i class="bx bx-home"></i> Accueil</a></li>
      <li><a href="tchat.php"><i class="bx bx-envelope"></i>Discussion</a></li>
      <li><a href="#page2"><i class="bx bx-user"></i> Profil</a></li>
      <li><a href="#page3"><i class="bx bx-cog"></i>Parametre</a></li>
      <li><a href="#page4"><i class="bx bx-help-circle"></i>Aide</a></li>
      <!-- Ajoutez d'autres liens avec des icônes ici -->
    </ul>
  </div>
  <div class="content">
    <p class="home" style="text-align: center; font-size: 30px">BIENVENUE SUR SIMRO CHAT <strong> <?php echo $recup_id['PRENOM']; echo " "; echo $recup_id['NOM']; ?></strong><br>Envoyez et recevez des messages pour pouvoir comuniquer plus facilement et le plus rapide possible avec  SIMRO CHAT </p><b>
    <!-- Contenu des différentes pages -->
    <div id="page1" class="page">
      <p class="bienvenue">Cette application est une appliaction de chat qui vous permetra de comuniquer plus facilement avec d'autre membre de l'entreprise </p>
    </div>
    <div id="page2" class="page">
      <h1>Profil</h1>
      <div class="profile-header">
        <div class="profile-picture">
          <form method="POST" enctype="multipart/form-data">
            <div class="form-groupe">
              <div class="image-container">
                <img src="<?php echo $total; ?>">
                <input type="file" id="photo_profil" name="photo" accept="image/*">
                <label class="add-button">Ajouter</label>
              </div>
            </div>
        </div>
        <button type="submit" id="button" name="modifier">Enregister</button>
        </form>
        <div class="profile-groups">
          <h3>Mes Groupes</h3>
          <ul>
            <!-- Liste des groupes auxquels l'utilisateur appartient -->
            <?php while ($a = $sql0->fetch()) { ?>
              <a style="text-decoration: none; color:black;  font-size: 20px " href="../groupe/tchat_group.php?IDG=<?php echo $a['IDG']; ?>">
                <li><?php echo $a['NOM']; ?></li>
              </a>
            <?php } ?>
            <!-- Ajoutez d'autres groupes si nécessaire -->
          </ul>

        </div>
      </div>
      <div class="profile-info">
        <p><strong>Nom:</strong> <?php echo $recup_id['NOM']; ?></p>
        <p><strong>Prenom:</strong> <?php echo $recup_id['PRENOM']; ?></p>
        <p><strong>Email:</strong> <?php echo $recup_id['EMAIL']; ?></p>
        <p><strong>Type:</strong> <?php echo $recup_id['TYPE']; ?></p>
        <p><strong>Date de création:</strong> <?php echo $recup_id['DATE_DE_CREATION']; ?></p>
        <p><strong>Statut:</strong> <?php echo $recup_id['STATUT']; ?></p>
        <!-- Ajoutez d'autres informations du profil ici -->
      </div>
      <div class="profile-actions">
        <button><i class="bx bx-edit"></i> Modifier Profil</button>
        <button><i class="bx bx-log-out"></i> Déconnexion</button>
        <!-- Ajoutez d'autres actions du profil ici -->
      </div>
    </div>
    <!-- Ajoutez d'autres pages ici -->
    <div id="page3" class="page">
      <p class="bienvenue">Paramètres</p>
      <div class="setting-item">
        <label for="username">Nom d'utilisateur:</label>
        <input type="text" id="username" name="username" placeholder="Entrez votre nom d'utilisateur">
      </div>
      <div class="setting-item">
        <label for="theme">Thème:</label>
        <select id="theme" name="theme">
          <option value="light">Clair</option>
          <option value="dark">Sombre</option>
          <option value="custom">Personnalisé</option>
        </select>
      </div>
      <div class="setting-item">
        <label for="notifications">Notifications:</label>
        <input type="checkbox" id="notifications" name="notifications">
        <label for="notifications">Activer les notifications</label>
      </div>
    </div>
    <div id="page4" class="page">
      <p>Besoin d'aide? Contactez le support via support@jmail.com</p>
      <!-- Ajoutez d'autres informations d'aide si nécessaire -->
    </div>

  </div>
  <script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
      const links = document.querySelectorAll('.sidebar .menu li a');
      links.forEach(link => {
        link.addEventListener('click', function(e) {
          const targetId = this.getAttribute('href');
          const targetPage = document.querySelector(targetId);
          document.querySelectorAll('.page').forEach(page => {
            page.classList.remove('active');
          });
          document.querySelector('.content p ').style.display = 'none'; // Masquer le paragraphe
          targetPage.classList.add('active');
          e.preventDefault();
        });
      });
    });
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
</body>

</html>