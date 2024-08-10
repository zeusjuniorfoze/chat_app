<?php 
require_once('../conn.php');
if (!isset($_SESSION['id'])) {
    header("Location: ../connexion.php");
    exit;
}

if (isset($_GET['id'])) {
    $tchat = (int) $_GET['id']; // Assurez-vous que l'ID est bien un entier
    $userId = $_SESSION['id'];
    echo $tchat;
    echo $userId;

    // Vérifie si la discussion n'existe pas déjà
    $query = "SELECT id FROM discussions WHERE (user1_id = ? AND user2_id = ?) OR (user1_id = ? AND user2_id = ?)";
    $stmt = $con->prepare($query);
    $stmt->execute([$userId, $tchat, $tchat, $userId]);
    $discussion = $stmt->fetch();

    if (!$discussion) {
        // Insère une nouvelle discussion avec la date de création
        $date_creation = date('Y-m-d');
        $stmt = $con->prepare("INSERT INTO discussions (user1_id, user2_id, creation_date) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $tchat, $date_creation]);
        $discussion_id = $con->lastInsertId();
        // on redirige vers la discussion nouvellement créée
        header("Location: tchat.php?id=" . $discussion_id);
    } else {
        // on redirige vers la discussion déjà existante
        header("Location: tchat.php?id=" . $discussion['id']);
    }
} else {
    echo "Pas de récupération de l'ID";
}
?>
