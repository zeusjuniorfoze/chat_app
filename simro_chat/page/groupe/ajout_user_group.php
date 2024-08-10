<?php
require_once('../conn.php');
if (!$_SESSION['user']) {
    header('location: ../connexion.php');
}
$id_group = $_SESSION['tchat'];
$id_user = $_SESSION['id'];
$sql = $con->prepare("SELECT ID, NOM, PRENOM FROM user WHERE ID != ?");
$sql->execute(array($id_user));

if (isset($_POST['login'])) {
    $user = $_POST['user'];

    // Vérifier si l'utilisateur actuel est un administrateur du groupe
    $check_admin = $con->prepare("SELECT * FROM user_group WHERE ID = ? AND IDG = ? AND TYPE_USER_GROUP = 'admin'");
    $check_admin->execute(array($id_user, $id_group));
    $is_admin = $check_admin->rowCount();

    if ($is_admin > 0) {
        // L'utilisateur actuel est un administrateur, procéder à l'ajout
        // Vérifier si l'utilisateur sélectionné existe déjà dans le groupe
        $check_existing_user = $con->prepare("SELECT * FROM user_group WHERE ID = ? AND IDG = ?");
        $check_existing_user->execute(array($user, $id_group));
        $existing_user_count = $check_existing_user->rowCount();

        if ($existing_user_count > 0) {
            $erre = "Cet utilisateur est déjà dans ce groupe.";
        } else {
            // Insérer l'utilisateur dans le groupe
            $insert_sql = $con->prepare("INSERT INTO user_group (ID_ADMIN_AJOUT, ID, IDG, TYPE_USER_GROUP, DATE_AJOUT)
    VALUES (?, ?, ?, ?, ?)");
            $insert_sql->execute(array($id_user, $user, $id_group, "user", date('y/m/d')));
?>
            <script type="text/javascript">
                alert(" Utulisateur ajouter avec success !");
                window.location = "info_group.php";
            </script>
<?php }
    } else {
        // L'utilisateur actuel n'est pas un administrateur
        $erre = "Seuls les administrateurs peuvent ajouter des membres.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AJOUT_MEMBRE</title>
    <style>
        body {
            background: linear-gradient(rgba(1, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url("../img/4.jpg") center center fixed no-repeat;
            background-size: cover;
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .contentbox {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .formbox {
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .form-control {
            font-size: 20px;
            width: 19rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-color: blue;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .inputbox {
            text-align: center;
        }

        .cancel-button {
            margin-top: 1rem;
            float: left;
            border-radius: 2px;
            text-decoration: none;
            background-color: red;
            border-color: red;
            color: white;
            font-size: 30px;
            border: none;
        }

        .add-button {
            font-size: 20px;
            margin-top: 1rem;
            float: right;
            background-color: green;
            border-color: green;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 0.5rem 1rem;
            cursor: pointer;
        }

        p {
            color: red;
            margin: 10px 0;
            text-align: center;
            background-color: white;
            border-radius: 10px;
        }

        h2 {
            color: white;
            text-align: center;
            font-family: arial;
        }

        .cancel-button:hover {
            background-color: red;
            border-color: red;
        }

        .add-button:hover {
            background-color: darkgreen;
            border-color: darkgreen;
        }

        .error-message {
            color: red;
            margin-top: 1rem;
            text-align: center;
        }
    </style>
</head>

<body>
    <section>
        <div class="contentbox">
            <div class="formbox">
                <form id="ins" method="POST" action="">
                    <h2> Ajouter Au Groupe</h2>
                    <?php if (isset($erreu)) { ?>
                        <p class='error-message'><?php echo "$erreu"; ?></p>
                    <?php } ?>

                    <?php if (isset($erre)) { ?>
                        <p class='error-message'><?php echo "$erre"; ?></p>
                    <?php } ?>
                    <div class="form-group">
                        <label for="exampleFormControlSelect2">Choisir un utilisateur :</label>
                        <select class="form-control" name="user" id="exampleFormControlSelect2">
                            <?php while ($a = $sql->fetch()) { ?>
                                <option value="<?php echo $a['ID']; ?>"><?php echo $a['NOM'] . " " . $a['PRENOM']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="inputbox">
                        <button type="submit" name="login" class="btn add-button">Ajouter</button>
                        <a href="info_group.php" class="btn cancel-button">Annuler</a>
                    </div>

                </form>
            </div>
        </div>
    </section>
</body>

</html>