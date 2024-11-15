-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 15 nov. 2024 à 15:35
-- Version du serveur : 10.10.2-MariaDB
-- Version de PHP : 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `boite_a_idee`
--

-- --------------------------------------------------------

--
-- Structure de la table `idea`
--

DROP TABLE IF EXISTS `idea`;
CREATE TABLE IF NOT EXISTS `idea` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(60) NOT NULL,
  `description` text NOT NULL,
  `author` varchar(15) NOT NULL,
  `created_date` datetime DEFAULT current_timestamp(),
  `vote_positive` int(11) DEFAULT 0,
  `vote_negative` int(11) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `idea`
--

INSERT INTO `idea` (`id`, `title`, `description`, `author`, `created_date`, `vote_positive`, `vote_negative`) VALUES
(1, 'aaaaaaaaaaaaaa', 'bbbbbbbbbbbbbbbbbbbb', 'Zasir', '2024-11-15 16:28:21', 1, 0),
(2, 'AbraCadabra', 'azdscxcw', 'Renaud', '2024-11-15 16:29:15', 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `vote`
--

DROP TABLE IF EXISTS `vote`;
CREATE TABLE IF NOT EXISTS `vote` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_idea` int(11) NOT NULL,
  `voter_name` varchar(100) NOT NULL,
  `vote_type` enum('positive','negative') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_idea` (`id_idea`,`voter_name`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `vote`
--

INSERT INTO `vote` (`id`, `id_idea`, `voter_name`, `vote_type`) VALUES
(1, 1, 'Zasir', 'positive');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
