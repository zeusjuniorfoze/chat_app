<?php
require_once('../conn.php');
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
// on recherche tout les utulisateur different de celui qu est connexter

// Requête SQL pour récupérer les utilisateurs
$sql0 = $con->prepare("SELECT * FROM user WHERE  ID != ?");
$sql0->execute(array($id));
// on recherche la liste des groupes 
$sql1 = $con->prepare("SELECT * FROM groupe");
$sql1->execute();
// requette pour affichet tout les groupe de l'utulisateur
$sql3 = $con->prepare("SELECT G.IDG,G.NOM,G.DESCRIPTION FROM groupe G , user_group A WHERE A.IDG=G.IDG AND ID=? GROUP BY IDG");
$sql3->execute(array($_SESSION['id']));
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
      window.location = "admin.php";
    </script>

  <?php } else { ?>
    // Si aucun fichier n'a été téléchargé
    <script type="text/javascript">
      alert('Veillez Choisir une image avant');
    </script>
  <?php }
}
// on fait une requette de verification de l'utulisateur
$sql2 = $con->prepare(" SELECT * FROM user WHERE id = ?");
$sql2->execute(array($id));
$recup_id = $sql2->fetch();
$chemin = "../";
$photo_bd = $recup_id['PHOTO'];
$total = $chemin . $photo_bd;
if ($sql2->rowcount() > 0) {
  ?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link href="../boxicons/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../cssm/discuadmin.css">
    <title>ADMIN BORD</title>
    <style>
      .selectes {

        text-align: center;
        color: green;
      }

      .statut {
        text-align: center;
        color: red;
      }
    </style>
  </head>

  <body>
    <nav class="cc-nav navbar nav-dark ">
      <div class="container-fluid">
        <!-- image du logo -->
        <a class="navbar-brand py-1 mx-3" href="home.php">
          <img src="../img/simro_chat.PNG" alt="" width="100" height="100" class="d-inline-block align-text-top">
        </a>
        <!-- liste des element du  menue -->
        <h1 class="fw-bolder"><i style="color: #120cef;">SIMRO</i><i style="color: #f3940b;">CHAT</i></h1>
        <ul class="navbar-nav ms-auto mb-2lg-0">
          <li><a style=" font-size: 25px; " href="../deconnexion.php" class="btn btn-con"><i class='bx bx-log-in'></i> DECONNEXION</a></li>
        </ul>
      </div>
    </nav>
    <div class="sidebar">
      <ul class="menu">
        <li><a href="#page1"><i class="bx bx-home"></i> Accueil</a></li>
        <li><a href="#page2"><i class="bx bx-user-circle"></i>Liste Des Utulisateurs</a></li>
        <li><a href="#page3"><i class="bx bx-group"></i>Liste Des Groupes</a></li>
        <li><a href="tchatadmin.php"><i class="bx bx-envelope"></i>Discussion</a></li>
        <li><a href="#page4"><i class="bx bx-user"></i> Profil</a></li>
        <li><a href="#page5"><i class="bx bx-cog"></i>Parametre</a></li>
        <li><a href="#page6"><i class="bx bx-help-circle"></i>AIDE</a></li>

        <!-- Ajoutez d'autres liens avec des icônes ici -->
      </ul>
    </div>
    <div class="content">
      <p class="home" style="text-align: center; font-size: 30px">BIENVENUE SUR SIMROCHAT <strong> <?php echo $recup_id['PRENOM'];
                                                                                                    echo " ";
                                                                                                    echo $recup_id['NOM']; ?></strong><br>Envoyez et recevez des messages pour pouvoir comuniquer plus facilement et le plus rapide possible avec SIMROCHAT </p><br>
      <div id="page1" class="page">
        <p class="bienvenue">Cette application est une appliaction de chat qui vous permetra de comuniquer plus facilement avec d'autre membre de l'entreprise </p>
      </div>
      <div id="page2" class="page">
        <h1>Liste des Utilisateurs</h1>
        <div class="search-container">
          <input type="text" id="userSearchInput" placeholder="Rechercher...">
        </div>
        <a href="ajoutadmin.php" class="add-user-link"><i class="bx bx-plus-circle"></i> Ajouter un utilisateur</a>
        <table class="table" id="userTable">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nom</th>
              <th>Prénom</th>
              <th>Email</th>
              <th>Type</th>
              <th>Date de création</th>
              <th>Statut</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <!-- Contenu du tableau à remplir dynamiquement avec les données PHP -->
            <?php while ($a = $sql0->fetch()) {
              $class = "en ligne" == $a['STATUT'] ? 'selectes' : 'statut';
            ?>
              <tr>
                <td><?php echo $a['ID']; ?></td>
                <td><?php echo $a['NOM']; ?></td>
                <td><?php echo $a['PRENOM']; ?></td>
                <td><?php echo $a['EMAIL']; ?></td>
                <td><?php echo $a['TYPE']; ?></td>
                <td><?php echo $a['DATE_DE_CREATION']; ?></td>
                <td class="mes <?php echo $class; ?>"><?php echo $a['STATUT']; ?></td>
                <td>
                  <a style=" font-size: 30px" href="modifmembre.php?ID=<?php echo $a['ID']; ?>"><i class="bx bx-edit-alt"></i></a>
                  <a style="color: red; font-size: 30px" onclick="return confirm('Voulez Vous Suprimer Cet Utulisateur ? ')" href="supmembre.php?ID=<?php echo $a['ID'] ?>"><i class="bx bx-trash"></i></a>
                </td>
              <?php } ?>
              </tr>
              <!-- Ajoutez d'autres lignes si nécessaire -->
          </tbody>
        </table>
      </div>
      <div id="page3" class="page">
        <h1>Liste des Groupes</h1>
        <div class="search-container">
          <input type="text" id="groupSearchInput" placeholder="Rechercher...">
        </div>
        <a href="../groupe/cree_admin_group.php" class="add-group-link"><i class="bx bx-plus-circle"></i> Ajouter un groupe</a>
        <table class="table" id="groupTable">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nom</th>
              <th>Description</th>
              <th>Date de création</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <!-- Contenu du tableau à remplir dynamiquement avec les données PHP -->
            <?php while ($b = $sql1->fetch()) { ?>
              <tr>
                <td><?php echo $b['IDG']; ?></td>
                <td><?php echo $b['NOM']; ?></td>
                <td><?php echo $b['DESCRIPTION']; ?></td>
                <td><?php echo $b['DATE_DE_CREATION']; ?></td>
                <td>
                  <a style="font-size: 30px" href="../groupe/modifgroup.php?ID=<?php echo $b['IDG']; ?>"><i class="bx bx-edit-alt"></i></a>
                  <a style="color: red; font-size: 30px" onclick="return confirm('Voulez Vous Suprimer Ce Groupe ?')" href="../groupe/supgroup.php?ID=<?php echo $b['IDG'] ?>"><i class="bx bx-trash"></i></a>
                </td>
              <?php } ?>
              </tr>
              <!-- Ajoutez d'autres lignes si nécessaire -->
          </tbody>
        </table>
      </div>


      <div id="page4" class="page">
        <h1>Profil</h1>
        <div class="profile-header">
          <div class="profile-picture">
            <form method="POST" enctype="multipart/form-data">
              <div class="form-groupe">
                <div class="image-container">
                  <img src="<?php echo $total; ?>">
                  <input type="file" id="photo_profil" name="photo" accept="image/*">
                  <label class="add-button">Ajouter</slabel>
                </div>
              </div>
          </div>
          <button type="submit" id="button" name="modifier">Enregister</button>
          </form>
          <div class="profile-groups">
            <h3>Mes Groupes</h3>
            <ul style=" overflow-y: hidden; ">
              <!-- Liste des groupes auxquels l'utilisateur appartient -->

              <?php while ($a = $sql3->fetch()) { ?>
                <a style="text-decoration: none; color:black;  font-size: 20px " href="../groupe/tchat_admin_group.php?IDG=<?php echo $a['IDG']; ?>">
                  <li><?php echo $a['NOM']; ?></li>
                </a>
              <?php } ?>
              <!-- Ajoutez d'autres groupes si nécessaire -->
            </ul>
          </div>
        </div>
        <div class="profile-info">
          <p><strong>Nom: </strong><?php echo $recup_id['NOM']; ?></p>
          <p><strong>Email: </strong><?php echo $recup_id['EMAIL']; ?></p>
          <p><strong>Type: </strong><?php echo $recup_id['TYPE']; ?></p>
          <p><strong>Date de création: </strong><?php echo $recup_id['DATE_DE_CREATION']; ?></p>
          <p><strong>Statut: </strong><?php echo $recup_id['STATUT']; ?></p>
          <!-- Ajoutez d'autres informations du profil ici -->
        </div>
        <div class="profile-actions">
          <button onclick="window.location('modifuser.php'); "><i class="bx bx-edit"></i> Modifier Profil</button>
          <button><i class="bx bx-log-out"></i> Déconnexion</button>
          <!-- Ajoutez d'autres actions du profil ici -->
        </div>
        <!-- Ajoutez d'autres pages ici -->
      </div>
      <div id="page5" class="page">
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
        <!-- Ajoutez d'autres éléments de paramètres selon vos besoins -->
      </div>
      <div id="page6" class="page">
        <p class="bienvenue">Aide</p>
        <div class="help-container">
          <p>Besoin d'aide? Contactez le support via support@jmail.com</p>
          <!-- Ajoutez d'autres informations d'aide si nécessaire -->
        </div>
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
            document.querySelector('.content p').style.display = 'none'; // Masquer le paragraphe
            targetPage.classList.add('active');
            e.preventDefault();
          });
        });
      });

      // code de  recherche des utulisateur
      document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('userSearchInput');
        const userTable = document.getElementById('userTable');
        const rows = userTable.getElementsByTagName('tr');

        searchInput.addEventListener('input', function() {
          const filter = searchInput.value.toUpperCase();
          for (let i = 0; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName('td');
            let found = false;
            for (let j = 0; j < cells.length; j++) {
              const cell = cells[j];
              if (cell) {
                const textValue = cell.textContent || cell.innerText;
                if (textValue.toUpperCase().indexOf(filter) > -1) {
                  found = true;
                  break;
                }
              }
            }
            if (found) {
              rows[i].style.display = '';
            } else {
              rows[i].style.display = 'none';
            }
          }
        });
      });

      // code de recherche des groupes
      document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('groupSearchInput');
        const userTable = document.getElementById('groupTable');
        const rows = userTable.getElementsByTagName('tr');

        searchInput.addEventListener('input', function() {
          const filter = searchInput.value.toUpperCase();
          for (let i = 0; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName('td');
            let found = false;
            for (let j = 0; j < cells.length; j++) {
              const cell = cells[j];
              if (cell) {
                const textValue = cell.textContent || cell.innerText;
                if (textValue.toUpperCase().indexOf(filter) > -1) {
                  found = true;
                  break;
                }
              }
            }
            if (found) {
              rows[i].style.display = '';
            } else {
              rows[i].style.display = 'none';
            }
          }
        });
      });
      // pour changer la photo de profil
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

<?php } else { ?>
  <script type="text/javascript">
    alert("Votre Comte a ete suprimer par l'admin");
    window.location = "../connexion.php";
  </script>
<?php } ?>