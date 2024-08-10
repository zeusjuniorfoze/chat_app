<?php
require_once '../conn.php';

// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si la session utilisateur est active
if (!isset($_SESSION['user'])) {
    header('Location: ../connexion.php');
    exit;
}

$_SESSION['tchat'] = null;

// Récupérer l'utilisateur connecté
$user = $_SESSION['user'];
$userId = $_SESSION['id'];
$_SESSION['chat_with'] = null;

// Récupérer tous les utilisateurs sauf l'utilisateur connecté
$recup = $con->prepare("SELECT * FROM user WHERE ID != ?");
$recup->execute(array($userId));

// Pour la propre photo de l'utilisateur
$reqe = $con->prepare("SELECT * FROM user WHERE EMAIL = ?");
$reqe->execute([$user]);
$photo = $reqe->fetch();
$chemin = "../";
$photo_bd = $photo['PHOTO'];
$total = $chemin . $photo_bd;

// Récupérer l'utilisateur sélectionné dans l'URL
$tchat = null;
$result = null;

if (isset($_GET['id'])) {
    $tchat = (int) $_GET['id']; // Assurez-vous que l'ID est bien un entier
    $_SESSION['tchat'] = $tchat;

    // Mettre à jour les messages non lus pour les marquer comme lus si l'utilisateur est le destinataire
    $updateQuery = $con->prepare("UPDATE messages SET is_read = '1' WHERE discussion_id = ? AND is_read = '0' AND sender_id != ?");
    $updateQuery->execute([$tchat, $userId]);

    // Préparez et exécutez la requête pour récupérer les détails de la discussion
    $t = $con->prepare("SELECT D.*, U.PHOTO, U.NOM, U.STATUT FROM discussions D 
                        JOIN user U ON (D.user1_id = U.ID OR D.user2_id = U.ID) 
                        WHERE D.id = ? AND U.ID != ?");
    $t->execute([$tchat, $userId]); // Utilisez $userId au lieu de $_SESSION['user_id']
    $result = $t->fetch();
}

// Envoi du message
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['message']) && isset($_SESSION['tchat'])) {
    $message = htmlspecialchars($_POST['message']);
    $sql = $con->prepare("INSERT INTO messages (sender_id, discussion_id, CONTENU, is_read, HEURE_ENVOI) VALUES (?, ?, ?, ?, ?)");
    $sql->execute([$userId, $_SESSION['tchat'], $message, '0', date("H:i")]);

    // Mettre à jour les messages non lus pour les marquer comme lus si l'utilisateur est le destinataire
    $updateQuery = $con->prepare("UPDATE messages SET is_read = '1' WHERE discussion_id = ? AND is_read = '0' AND sender_id != ?");
    $updateQuery->execute([$_SESSION['tchat'], $userId]);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JMAIL APPLICATION</title>
    <link rel="stylesheet" href="../cssm/tchat.css">
    <link href="../boxicons/css/boxicons.min.css" rel="stylesheet">
    <script src="../jss/jquery.js"></script>
</head>

<body>
    <div class="container">
        <!-- Left side -->
        <div class="left-side">
            <div class="header">
                <div><a href="discussion.php" style="font-size: 30px; color: black;"><i class="bx bx-left-arrow-alt"></i></a></div>
                <div class="user-imgBx">
                    <img src="<?php echo htmlspecialchars($total); ?>" alt="User Image">
                </div>
                <ul class="nav-icons">
                    <h1 class="fw-bolder"><i style="color: #120cef; font-size: 30px;">SIMRO</i><i style="color: #f3940b; font-size: 30px;">CHAT</i></h1>
                    <li class="ellipsis-menu">
                        <ion-icon name="ellipsis-vertical" class="bx bx-plus-circle"></ion-icon>
                        <div class="dropdown-menu">
                            <ul class="options">
                                <li><ion-icon name="add-circle-outline" class="bx bx-plus-circle"></ion-icon><a href="../groupe/cree_group.php">Créer un groupe</a></li>
                            </ul>
                            <?php while ($membre = $recup->fetch()) { ?>
                                <ul class="members">
                                    <li>
                                        <a href="create_user_discussion.php?id=<?php echo $membre['ID']; ?>">
                                            <div class="avatar">
                                                <img src="<?php echo $chemin . $membre['PHOTO']; ?>" alt="">
                                            </div>
                                            <div class="info">
                                                <span><?php echo htmlspecialchars($membre['NOM'] . " " . $membre['PRENOM']); ?></span>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            <?php } ?>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="search-chat">
                <input type="search" id="searchInput" placeholder="Search or start new chat">
                <ion-icon name="search-outline" class="bx bx-search"></ion-icon>
            </div>
            <div class="chat-list" id="chat-list">
                <!-- La liste des chats sera actualisée ici -->
            </div>
        </div>

        <!-- Right side -->
        <div class="right-side">
            <div class="header">
                <div class="user-details">
                    <div class="user-imgBx">
                        <?php if ($result) { ?>
                            <img src="<?php echo htmlspecialchars($chemin . $result['PHOTO']); ?>" alt="Selected User Image">
                        <?php } ?>
                    </div>
                    <?php if ($result) { ?>
                        <h4><?php echo htmlspecialchars($result['NOM']); ?><br>
                            <span><?php echo htmlspecialchars($result['STATUT']); ?></span>
                        </h4>
                    <?php } ?>
                </div>
            </div>
            <div class="chatBx" id="chatBx"></div>
            <form method="POST">
                <div class="chat-input">
                    <ion-icon name="happy-outline" class="bx bx-happy"></ion-icon>
                    <ion-icon name="attach-outline" class="bx bx-paperclip"></ion-icon>
                    <input type="text" name="message" placeholder="Type a message">
                    <button style="border: none; background: transparent;" onclick="loadDoc()" name="login" type="submit"><i class="bx bx-send" style="font-size: 40px;"></i></button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ellipsisMenu = document.querySelector('.ellipsis-menu');
            const dropdownMenu = document.querySelector('.dropdown-menu');

            // Ecouteur pour afficher/cacher le menu
            ellipsisMenu.addEventListener('click', function() {
                dropdownMenu.style.display = (dropdownMenu.style.display === 'block') ? 'none' : 'block';
            });

            // Ecouteur pour cacher le menu lorsqu'on clique ailleurs
            document.addEventListener('click', function(event) {
                const target = event.target;
                // Vérifier si l'élément cliqué est en dehors du menu ellipsis et du dropdown
                if (!target.closest('.ellipsis-menu') && !target.closest('.dropdown-menu')) {
                    dropdownMenu.style.display = 'none';
                }
            });
        });

        function loadChatList() {
    $.ajax({
        url: 'update_chat_list.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            let chatList = '';
            data.forEach(function(chat) {
                let inCurrentChat = chat.discussion_id == <?php echo isset($_GET['id']) ? $_GET['id'] : 'null'; ?>;

                if (chat.type === 'discussion') {
                    chatList += `
                        <a style="text-decoration: none; color: black;" href="?id=${chat.discussion_id}">
                            <div class="chat ${inCurrentChat ? 'active' : ''}">
                                <div class="imgBx">
                                    <img src="${'<?php echo $chemin; ?>' + chat.PHOTO}" alt="User Image">
                                </div>
                                <div class="details">
                                    <div class="head">
                                        <h4 class="name">${chat.nom}</h4>
                                        <span class="time">${chat.last_message_time}</span>
                                    </div>
                                    <div class="msgs">
                                        <p class="msg">${chat.last_message}</p>
                                        ${!inCurrentChat && chat.unread_count > 0 ? `<div style="background-color: #06d755; color: #fff; min-width: 20px; height: 20px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: .90rem;" class="num unread">${chat.unread_count}</div>` : ''}
                                    </div>
                                </div>
                            </div>
                        </a>`;
                } else if (chat.type === 'group') {
                    chatList += `
                    <a style="text-decoration: none; color: black;" href="../groupe/tchat_group.php?IDG=${chat.group_id}">
                        <div class="chat ${inCurrentChat ? 'active' : ''}">
                            <div class="imgBx">
                                <img src="${'<?php echo $chemin; ?>' + chat.PHOTO}" alt="Group Image">
                            </div>
                            <div class="details">
                                <div class="head">
                                    <h4 class="name">${chat.NOM}</h4>
                                    <span class="time">${chat.last_message_time}</span>
                                </div>
                                <div class="msgs">
                                    <p class="msg">${chat.last_message}</p>
                                    ${!inCurrentChat && chat.unread_count > 0 ? `<div style="background-color: #06d755; color: #fff; min-width: 20px; height: 20px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: .90rem;" class="num unread">${chat.unread_count}</div>` : ''}
                                </div>
                            </div>
                        </div>
                    </a>`;
                }
            });
            $('#chat-list').html(chatList);
        }
    });
}


        $(document).ready(function() {
            loadChatList();
            setInterval(loadChatList, 1000); // Actualiser toutes les 1 seconde
            $('#searchInput').on('input', function() {
                var searchText = $(this).val().toLowerCase();
                $('.chat-list .chat').each(function() {
                    var chatName = $(this).find('.name').text().toLowerCase();
                    var chatMsg = $(this).find('.msg').text().toLowerCase();
                    if (chatName.indexOf(searchText) === -1 && chatMsg.indexOf(searchText) === -1) {
                        $(this).hide();
                    } else {
                        $(this).show();
                    }
                });
            });
        });

        function loadDoc() {
            setInterval(function() {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        document.getElementById("chatBx").innerHTML = this.responseText;
                    }
                };
                xhttp.open("GET", "affiche.php", true);
                xhttp.send();
            }, 100);
        }

        loadDoc();
    </script>
</body>

</html>
