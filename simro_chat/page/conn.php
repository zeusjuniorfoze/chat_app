<?php
    session_start();

    // Vérifier la connexion à la base de données
    try {
        $con = new PDO('mysql:host=localhost;dbname=sut_bd;charset=utf8', 'root', '');
    } catch (PDOException $e) {
        echo '<div style="text-align: center; color: orange; font-size: 40px;">Erreur de connexion à la base de données. Veuillez démarrer MySQL.</div>';
        die(); // Arrêter l'exécution du script
        
    }
?>
