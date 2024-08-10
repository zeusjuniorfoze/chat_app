<?php
require_once '../conn.php';

// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si la session utilisateur est active
if (!isset($_SESSION['user'])) {
    echo json_encode([]);
    exit;
}

// Récupérer l'utilisateur connecté
$user = $_SESSION['user'];
$userId = $_SESSION['id'];

// Récupérer les discussions pour l'utilisateur connecté
$reqDiscussions = $con->prepare("SELECT 
        d.id as discussion_id, 
        u.nom, 
        u.PHOTO, 
        (SELECT m.CONTENU FROM messages m WHERE m.discussion_id = d.id ORDER BY m.HEURE_ENVOI DESC LIMIT 1) as last_message,
        (SELECT m.HEURE_ENVOI FROM messages m WHERE m.discussion_id = d.id ORDER BY m.HEURE_ENVOI DESC LIMIT 1) as last_message_time,
        (SELECT COUNT(*) FROM messages m WHERE m.discussion_id = d.id AND m.is_read = '0' AND m.sender_id != ?) as unread_count,
        d.user1_id, 
        d.user2_id,
        'discussion' as type
    FROM 
        discussions d
    JOIN 
        user u ON (u.ID = d.user1_id OR u.ID = d.user2_id) AND u.ID != ?
    WHERE 
        d.user1_id = ? OR d.user2_id = ?");
$reqDiscussions->execute([$userId, $userId, $userId, $userId]);
$discussions = $reqDiscussions->fetchAll(PDO::FETCH_ASSOC);


$reqGroupes = $con->prepare("SELECT g.IDG as group_id, g.NOM, g.PHOTO,
       (SELECT m.CONTENU 
        FROM messages m 
        WHERE m.IDG = g.IDG 
        ORDER BY m.HEURE_ENVOI DESC 
        LIMIT 1) as last_message,
       (SELECT m.HEURE_ENVOI 
        FROM messages m 
        WHERE m.IDG = g.IDG 
        ORDER BY m.HEURE_ENVOI DESC 
        LIMIT 1) as last_message_time,
       (SELECT COUNT(*) 
        FROM user_messages um 
        WHERE um.group_id = g.IDG 
          AND um.is_read = FALSE 
          AND um.user_id = ?) as unread_count,
       'group' as type
FROM groupe g
JOIN user_group ug ON ug.IDG = g.IDG
WHERE ug.ID = ?
GROUP BY g.IDG;
");
$reqGroupes->execute([$userId, $userId]);
$groupes = $reqGroupes->fetchAll(PDO::FETCH_ASSOC);

// Fusionner les résultats des discussions et des groupes
$chats = array_merge($discussions, $groupes);

// Trier les résultats par la dernière activité
usort($chats, function ($a, $b) {
    return strtotime($b['last_message_time']) - strtotime($a['last_message_time']);
});

// Retourner les résultats en JSON
echo json_encode($chats);
