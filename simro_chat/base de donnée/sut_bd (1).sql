-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 28 juil. 2024 à 21:44
-- Version du serveur : 10.4.27-MariaDB
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `sut_bd`
--

-- --------------------------------------------------------

--
-- Structure de la table `discussions`
--

CREATE TABLE `discussions` (
  `id` int(11) NOT NULL,
  `user1_id` int(11) NOT NULL,
  `user2_id` int(11) NOT NULL,
  `last_message` varchar(100) DEFAULT NULL,
  `last_message_time` time NOT NULL DEFAULT current_timestamp(),
  `creation_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `groupe`
--

CREATE TABLE `groupe` (
  `PHOTO` varchar(100) DEFAULT NULL,
  `IDG` int(11) NOT NULL,
  `NOM` varchar(100) DEFAULT NULL,
  `DESCRIPTION` varchar(100) DEFAULT NULL,
  `DATE_DE_CREATION` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `discussion_id` int(11) DEFAULT NULL,
  `IDG` int(11) DEFAULT NULL,
  `sender_id` int(11) NOT NULL,
  `CONTENU` text NOT NULL,
  `HEURE_ENVOI` time DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message_id` int(11) NOT NULL,
  `notification_text` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `PHOTO` varchar(100) NOT NULL,
  `ID` int(11) NOT NULL,
  `TYPE` varchar(100) DEFAULT NULL,
  `NOM` varchar(100) DEFAULT NULL,
  `PRENOM` varchar(100) NOT NULL,
  `EMAIL` varchar(100) DEFAULT NULL,
  `STATUT` varchar(100) DEFAULT NULL,
  `DATE_DE_CREATION` date DEFAULT NULL,
  `MOT_DE_PASS` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`PHOTO`, `ID`, `TYPE`, `NOM`, `PRENOM`, `EMAIL`, `STATUT`, `DATE_DE_CREATION`, `MOT_DE_PASS`) VALUES
('uploads/DXJH7919.png', 1, 'admin', 'fozet', 'junior', 'fozetj29@gmail.com', 'en ligne', '2024-07-21', 'ed705e1e063c7d1e1d0580df845e08b8d134cdf0'),
('uploads/LFBR1710.JPG', 2, 'admin', 'ariane', 'kenwo', 'kuateariane@gmail.com', 'en ligne', '2024-07-22', 'fc58d173ddb3c6636e00ec1f54b83e9467c2ffbe'),
('uploads/IMG-20230622-WA0026.jpg', 3, 'admin', 'ngoueni', 'mirande', 'ngouenimirande@gmail.com', 'en ligne', '2024-07-22', 'fc58d173ddb3c6636e00ec1f54b83e9467c2ffbe'),
('uploads/PDOO4621.JPG', 4, 'admin', 'kenwo', 'cyntia', 'ariane@icloud.com', 'en ligne', '2022-07-24', 'cb7c379943446995a06988af67c6d946dda3d4ef'),
('uploads/s_logo.jpg', 5, 'user', 'teku', 'paul', 'polio@gmail.com', 'en ligne', '2024-07-22', 'd48f43406c53520705a1bd5106a6e4481af4fdcb'),
('uploads/IMG_0506.PNG', 6, 'user', 'zeus', 'junior', 'zeusjunior@gmail.com', 'en ligne', '2024-07-24', 'ed705e1e063c7d1e1d0580df845e08b8d134cdf0');

-- --------------------------------------------------------

--
-- Structure de la table `user_group`
--

CREATE TABLE `user_group` (
  `ID_ADMIN_AJOUT` int(11) DEFAULT NULL,
  `ID` int(11) NOT NULL,
  `IDG` int(11) NOT NULL,
  `TYPE_USER_GROUP` varchar(50) DEFAULT NULL,
  `DATE_AJOUT` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user_messages`
--

CREATE TABLE `user_messages` (
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `message_id` int(11) NOT NULL,
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `discussions`
--
ALTER TABLE `discussions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user1_id` (`user1_id`),
  ADD KEY `user2_id` (`user2_id`);

--
-- Index pour la table `groupe`
--
ALTER TABLE `groupe`
  ADD PRIMARY KEY (`IDG`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDG` (`IDG`),
  ADD KEY `discussion_id` (`discussion_id`),
  ADD KEY `sender_id` (`sender_id`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `message_id` (`message_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `user_group`
--
ALTER TABLE `user_group`
  ADD KEY `ID` (`ID`),
  ADD KEY `IDG` (`IDG`);

--
-- Index pour la table `user_messages`
--
ALTER TABLE `user_messages`
  ADD PRIMARY KEY (`user_id`,`group_id`,`message_id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `message_id` (`message_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `discussions`
--
ALTER TABLE `discussions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `groupe`
--
ALTER TABLE `groupe`
  MODIFY `IDG` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `discussions`
--
ALTER TABLE `discussions`
  ADD CONSTRAINT `discussions_ibfk_1` FOREIGN KEY (`user1_id`) REFERENCES `user` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `discussions_ibfk_2` FOREIGN KEY (`user2_id`) REFERENCES `user` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`IDG`) REFERENCES `groupe` (`IDG`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`discussion_id`) REFERENCES `discussions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `messages_ibfk_3` FOREIGN KEY (`sender_id`) REFERENCES `user` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `user_group`
--
ALTER TABLE `user_group`
  ADD CONSTRAINT `user_group_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `user` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_group_ibfk_2` FOREIGN KEY (`IDG`) REFERENCES `groupe` (`IDG`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `user_messages`
--
ALTER TABLE `user_messages`
  ADD CONSTRAINT `user_messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_messages_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groupe` (`IDG`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_messages_ibfk_3` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
