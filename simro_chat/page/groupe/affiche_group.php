<?php
require_once('../conn.php');

// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: ../connexion.php');
    exit;
}

// Vérifier si un groupe est sélectionné
if (isset($_SESSION['tchat'])) {
    $userId = $_SESSION['id'];
    $groupId = $_SESSION['tchat'];
    
    // Préparer et exécuter la requête pour récupérer les messages du groupe
    $requet = $con->prepare("SELECT U.NOM, U.PRENOM, U.ID, M.CONTENU, M.HEURE_ENVOI 
                             FROM messages M
                             JOIN user U ON M.sender_id = U.ID
                             WHERE M.IDG = ?
                             ORDER BY M.HEURE_ENVOI ASC ");
    $requet->execute([$groupId]);
} else {
    $_SESSION['tchat'] = null;
    echo " ";
    exit;
}
?>
<body>
    <div class="messages-container">
        <?php if (isset($_SESSION['tchat'])) { ?>
            <?php while ($row = $requet->fetch()) {
                // Déterminer la classe du message en fonction de l'utilisateur
                $class = $userId == $row['ID'] ? 'msg msg-me' : 'msg msg-frnd';
                // Déterminer le nom de l'expéditeur
                $senderName = $userId == $row['ID'] ? ' ' : htmlspecialchars($row['NOM'] . ' ' . $row['PRENOM']);
            ?>
                <div class="<?php echo htmlspecialchars($class); ?>">
                    <p>
                        <?php echo $senderName ; ?><br>
                        <?php echo htmlspecialchars($row['CONTENU']); ?><br>
                        <span><?php echo htmlspecialchars($row['HEURE_ENVOI']); ?></span>
                    </p>
                </div>
            <?php } ?>
        <?php } else { ?>
            <p> </p>
        <?php } ?>
    </div>
</body>
