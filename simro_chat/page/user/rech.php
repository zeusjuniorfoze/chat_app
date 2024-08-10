<?php
require_once('../conn.php');
if(!$_SESSION['user']){
      header('location: ../connexion.php');
   }

if (isset($_GET['user'])) {
    $user = $_GET['user'];
    $req = $con->prepare("SELECT * FROM user WHERE NOM LIKE ?");
    $req->execute(array("%$user%"));

 while ($a=$req->fetch()) { 
         $class= "en ligne" == $a['STATUT'] ? 'selectes': 'statut';
      ?>
      <a style="text-decoration: none; color: black;" href="tchat.php?ID=<?php echo $a['ID'];?>">
         <div class="membre">
            <strong><img src="../img/icone.png" width="48" height="50"><?php echo $a['NOM'];?></strong><br>
            <span><?php echo $a['TYPE'];?></span>
            <strong class="mes <?php echo $class; ?>">
               <?php echo $a['STATUT'];?>
            </strong>
         </div>
      </a>
         <?php } 
    }
?>


