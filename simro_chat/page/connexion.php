<?php
require_once("conn.php");
if (isset($_POST['login'])) {
    // si on insere le boutton login  
    if (!empty($_POST['email']) and !empty($_POST['pass'])) {
        // si l'email et le mot de pass sont entree
        $email =  addslashes(htmlspecialchars(trim(
            $_POST['email']
        ))); // recupere l'email
        $_SESSION['user'] = $email;
        $pass = sha1(htmlspecialchars(trim($_POST['pass']))); // recupere le mot de pass
        $erreu = "";
        $erre = "";
        $sql = $con->prepare("SELECT * FROM user WHERE email = ? AND mot_de_pass = ? ");
        // requete de verificatition des element entree par l'utulisateur
        $sql->execute(array($email, $pass));
        $id = $sql->fetch();
        // on stocker ses element dans un tableaux
        $req = $con->prepare(" SELECT * FROM user WHERE email=? AND type=?"); // on verifie le type de compte en fonction des information entree 
        $req->execute(array($email, 'admin'));
        if ($sql->rowCount() > 0) { // on verifie si le tableau eest vide ou pas
            if ($req->rowCount() > 0) {
                $_SESSION['id'] = $id['ID'];
                header('location: admin/admin.php');
            } else {
                $_SESSION['id'] = $id['ID'];
                header('location: user/discussion.php');
            }
        } else {
            $erreu = "Email Ou Mot De Pass Incoreccts !";
        }
    } else {
        $erre = "Veillez Remplir Tout Les Champs !";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="cssm/inscrip.css">
    <title>JMAIL APLICATION</title>
</head>

<body>
    <div class="container">
        <form id="loginForm" method="POST">
            <h2 class="conn">Connexion</h2>
            <label for="username">Email de l'utilisateur</label>
            <input type="email" id="username" name="email" placeholder="nom@gmail.com">

            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="pass" placeholder="**********">
            <?php if (isset($erreu)) { ?>
                <p class='erreu'><?php echo "$erreu"; ?></p>
            <?php } ?>

            <?php if (isset($erre)) { ?>
                <p class='erreu'><?php echo "$erre"; ?></p>
            <?php } ?>
            <button type="submit" name="login">Se connecter</button>
            <div class="form-links">
                <a href="passoublier.php">Mot de passe oublié</a>
                <span class="separator">|</span>
                <a href="inscription.php">Créer un nouveau compte</a>
            </div>
        </form>
    </div>
</body>

</html>