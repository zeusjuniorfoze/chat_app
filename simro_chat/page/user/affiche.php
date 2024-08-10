<?php
require_once '../conn.php';

// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('location: ../connexion.php');
    exit;
}

$messages = [];
if (isset($_SESSION['tchat'])) {
    $user = $_SESSION['user'];
    $id = $_SESSION['id'];
    $tchat = $_SESSION['tchat'];

    // Sélectionner les messages de la discussion
    $requet = $con->prepare("
        SELECT * FROM `messages` 
        WHERE discussion_id = ?
        ORDER BY HEURE_ENVOI ASC
    ");
    $requet->execute([$tchat]);
    $messages = $requet->fetchAll(PDO::FETCH_ASSOC);
} else {
    $_SESSION['tchat'] = null;
}
?>

    <div class="chat-container">
        <?php if (!empty($messages)): ?>
            <?php foreach ($messages as $row): 
                $class = $id == $row['sender_id'] ? 'msg msg-me' : 'msg msg-frnd';
            ?>
                <div class="<?php echo htmlspecialchars($class); ?>">
                    <p><?php echo htmlspecialchars($row['CONTENU']); ?><br> 
                    <span><?php echo htmlspecialchars($row['HEURE_ENVOI']); ?></span></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p> </p>
        <?php endif; ?>
    </div>