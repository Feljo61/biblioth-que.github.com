-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 01 mars 2024 à 17:18
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
-- Base de données : `bibliotheque`
--

-- --------------------------------------------------------

--
-- Structure de la table `editions`
--

CREATE TABLE `editions` (
  `id_edition` int(10) NOT NULL,
  `editions` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `emprunts`
--

CREATE TABLE `emprunts` (
  `id_emprunt` int(10) NOT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `titre` varchar(20) DEFAULT NULL,
  `cote` varchar(10) DEFAULT NULL,
  `auteur` varchar(50) DEFAULT NULL,
  `id_lecteur` int(10) DEFAULT NULL,
  `id_ouvrage` int(10) DEFAULT NULL,
  `date_emprunt` date DEFAULT NULL,
  `date_retour_prevue` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `lecteurs`
--

CREATE TABLE `lecteurs` (
  `id_lecteur` int(10) NOT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `sexe` varchar(10) DEFAULT NULL,
  `date_naissance` date DEFAULT NULL,
  `type_piece` varchar(20) DEFAULT NULL,
  `num_piece` varchar(20) DEFAULT NULL,
  `telephone` varchar(15) DEFAULT NULL,
  `adresse` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `lecteurs`
--

INSERT INTO `lecteurs` (`id_lecteur`, `nom`, `prenom`, `email`, `sexe`, `date_naissance`, `type_piece`, `num_piece`, `telephone`, `adresse`) VALUES
(1, 'MILAN ', 'Louane', 'milanlou@gmail.com', 'Femme', '2003-07-21', 'CNI', '8596GAB', '077236336', 'Batterie IV'),
(2, 'PERREIRA', 'Noah Clémence', 'clemnono@yahoo.fr', 'Femme', '2005-11-30', 'Carte_scolaire', '4526GAB', '077859610', 'PK 8 '),
(3, 'BEKALE MENGUE', 'Léora', 'leo@gmail.com', 'Femme', '2004-04-29', 'Passeport', '9633GAB', '060211221', 'ONDOGO'),
(4, 'DJENNO', 'Malcolm', 'dash@gmail.com', 'Homme', '1998-03-05', 'Permis', 'GAB8558', '011258974', 'AGUNGU'),
(6, 'STOOW', 'Jonathan', 'jojoba@gmail.com', 'Homme', '2008-08-21', 'Carte_etudiant', 'GAB8741', '074523695', 'Carrefour gigi'),
(7, 'STOOW', 'Jonathan', 'jojoba@gmail.com', 'Homme', '2008-08-21', 'Carte_etudiant', 'GAB8741', '074523695', 'Carrefour Gigi');

-- --------------------------------------------------------

--
-- Structure de la table `ouvrages`
--

CREATE TABLE `ouvrages` (
  `id_ouvrage` int(10) NOT NULL,
  `titre` varchar(20) DEFAULT NULL,
  `cote` varchar(10) DEFAULT NULL,
  `auteur` varchar(50) DEFAULT NULL,
  `edition` varchar(50) DEFAULT NULL,
  `date_publication` date DEFAULT NULL,
  `genre` varchar(50) DEFAULT NULL,
  `id_edition` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ouvrages`
--

INSERT INTO `ouvrages` (`id_ouvrage`, `titre`, `cote`, `auteur`, `edition`, `date_publication`, `genre`, `id_edition`) VALUES
(1, 'Les Ombres du Crépus', 'A1234', 'Camille Dupont', 'Gallimard', '2019-05-15', 'Fantastique', NULL),
(2, 'Les Ombres du Crépus', 'A1234', 'Camille Dupont', 'Editions Mystères ', '2020-04-15', 'Fantastique', NULL),
(3, 'Au delà des Etoiles', 'AE7892', 'Xavier', 'Intrigue Enigmatique', '2021-03-20', 'Science-fiction', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

CREATE TABLE `reservations` (
  `id_reservation` int(10) NOT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `cote` varchar(10) DEFAULT NULL,
  `titre` varchar(50) DEFAULT NULL,
  `auteur` varchar(50) DEFAULT NULL,
  `date_reserv` date DEFAULT NULL,
  `date_retour` date DEFAULT NULL,
  `id_lecteur` int(10) DEFAULT NULL,
  `id_ouvrage` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `reservations`
--

INSERT INTO `reservations` (`id_reservation`, `nom`, `prenom`, `cote`, `titre`, `auteur`, `date_reserv`, `date_retour`, `id_lecteur`, `id_ouvrage`) VALUES
(1, 'MILAN', 'Louane', 'A1234', 'Les Ombres du Crépuscule', 'Camille Dupont', '2023-06-21', '2023-07-01', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `sanctions`
--

CREATE TABLE `sanctions` (
  `id_sanction` int(10) NOT NULL,
  `noml` varchar(50) DEFAULT NULL,
  `prenoml` varchar(50) DEFAULT NULL,
  `cote` varchar(10) DEFAULT NULL,
  `titre` varchar(50) DEFAULT NULL,
  `auteur` varchar(50) DEFAULT NULL,
  `date_retour` date DEFAULT NULL,
  `sanction` varchar(50) DEFAULT NULL,
  `etat_livre` varchar(50) NOT NULL,
  `id_lecteur` int(10) DEFAULT NULL,
  `id_ouvrage` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id_user` int(10) NOT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `date_naissance` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `editions`
--
ALTER TABLE `editions`
  ADD PRIMARY KEY (`id_edition`);

--
-- Index pour la table `emprunts`
--
ALTER TABLE `emprunts`
  ADD PRIMARY KEY (`id_emprunt`),
  ADD KEY `id_lecteur` (`id_lecteur`),
  ADD KEY `id_ouvrage` (`id_ouvrage`);

--
-- Index pour la table `lecteurs`
--
ALTER TABLE `lecteurs`
  ADD PRIMARY KEY (`id_lecteur`);

--
-- Index pour la table `ouvrages`
--
ALTER TABLE `ouvrages`
  ADD PRIMARY KEY (`id_ouvrage`),
  ADD KEY `id_edition` (`id_edition`);

--
-- Index pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id_reservation`),
  ADD KEY `id_lecteur` (`id_lecteur`),
  ADD KEY `id_ouvrage` (`id_ouvrage`);

--
-- Index pour la table `sanctions`
--
ALTER TABLE `sanctions`
  ADD PRIMARY KEY (`id_sanction`),
  ADD KEY `id_lecteur` (`id_lecteur`),
  ADD KEY `id_ouvrage` (`id_ouvrage`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `editions`
--
ALTER TABLE `editions`
  MODIFY `id_edition` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `emprunts`
--
ALTER TABLE `emprunts`
  MODIFY `id_emprunt` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `lecteurs`
--
ALTER TABLE `lecteurs`
  MODIFY `id_lecteur` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `ouvrages`
--
ALTER TABLE `ouvrages`
  MODIFY `id_ouvrage` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id_reservation` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `sanctions`
--
ALTER TABLE `sanctions`
  MODIFY `id_sanction` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id_user` int(10) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `emprunts`
--
ALTER TABLE `emprunts`
  ADD CONSTRAINT `emprunts_ibfk_1` FOREIGN KEY (`id_lecteur`) REFERENCES `lecteur` (`id_lecteur`),
  ADD CONSTRAINT `emprunts_ibfk_2` FOREIGN KEY (`id_ouvrage`) REFERENCES `ouvrage` (`id_ouvrage`);

--
-- Contraintes pour la table `ouvrages`
--
ALTER TABLE `ouvrages`
  ADD CONSTRAINT `ouvrages_ibfk_1` FOREIGN KEY (`id_edition`) REFERENCES `edition` (`id_edition`);

--
-- Contraintes pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`id_lecteur`) REFERENCES `lecteurs` (`id_lecteur`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`id_ouvrage`) REFERENCES `ouvrages` (`id_ouvrage`);

--
-- Contraintes pour la table `sanctions`
--
ALTER TABLE `sanctions`
  ADD CONSTRAINT `sanctions_ibfk_1` FOREIGN KEY (`id_lecteur`) REFERENCES `lecteurs` (`id_lecteur`),
  ADD CONSTRAINT `sanctions_ibfk_2` FOREIGN KEY (`id_ouvrage`) REFERENCES `ouvrages` (`id_ouvrage`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
