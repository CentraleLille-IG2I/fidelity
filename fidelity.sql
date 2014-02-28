-- phpMyAdmin SQL Dump
-- version 4.0.6
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Ven 14 Février 2014 à 10:52
-- Version du serveur: 5.5.33
-- Version de PHP: 5.5.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `fidelity`
-- Structure et données-types pour la base de données `fidelity`
--

-- --------------------------------------------------------

--
-- Structure de la table `Clients`
--
-- Création: Jeu 13 Février 2014 à 22:04
--

CREATE TABLE `Clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Numéro de carte',
  `numeroCarte` int(20) NOT NULL,
  `nom` varchar(50) NOT NULL COMMENT 'Nom du client',
  `prenom` varchar(50) NOT NULL COMMENT 'Prénom du client',
  `adresse` varchar(200) NOT NULL COMMENT 'Adresse du client',
  `ville` varchar(50) NOT NULL COMMENT 'Ville du client',
  `codePostal` int(11) DEFAULT NULL COMMENT 'Code postal du client',
  `telephone` varchar(20) DEFAULT NULL COMMENT 'Numéro de téléphone',
  `telephone2` varchar(20) DEFAULT NULL COMMENT 'Autre numéro de téléphone',
  `mail` varchar(100) DEFAULT NULL COMMENT 'Adresse mail du client',
  `aboMail` tinyint(4) NOT NULL COMMENT 'Booléen d''abonnement mail',
  `aboSms` tinyint(4) NOT NULL COMMENT 'Booléen d''abonnement SMS',
  `cagnotte` float NOT NULL COMMENT 'Cagnotte en cours',
  `dateDeNaissance` date DEFAULT NULL COMMENT 'Date de naissance du client',
  `interets` varchar(300) DEFAULT NULL COMMENT 'Centre d''intéret du client',
  PRIMARY KEY (`id`),
  UNIQUE KEY `numeroCarte` (`numeroCarte`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Table de clients' AUTO_INCREMENT=13 ;

--
-- Contenu de la table `Clients`
--

INSERT INTO `Clients` (`id`, `numeroCarte`, `nom`, `prenom`, `adresse`, `ville`, `codePostal`, `telephone`, `mail`, `aboMail`, `aboSms`, `cagnotte`, `dateDeNaissance`, `interets`) VALUES
(9, 123456789, 'Degenne', 'Richard', '13 rue de la Paix', 'Lens', 62300, '0361191882', 'richard.degenne@ig2i.fr', 1, 1, 50.92, '1995-11-19', 'Musique, Informatique'),
(10, 987654321, 'Deprez', 'Adrien', '3 rue d''Évreux', 'Lens', 62300, '0625860982', 'adrien.deprez@ig2i.fr', 0, 1, 42.42, '1994-08-24', 'Jeux vidéos, Sports'),
(11, 246808642, 'Marcinkowski', 'Marc', '12 rue de la Perche', 'Lens', 62300, '0642424242', 'marc.marcinkowski@ig2i.fr', 1, 0, 13.37, '1994-01-23', 'Jeux vidéos, musique'),
(12, 135797531, 'Lemaire', 'Mélody', 'Yolo yolo', 'Lens', 62300, '0601020304', 'melody.lemaire@ig2i.fr', 0, 0, 12.34, '1994-01-01', 'Poneys');

-- --------------------------------------------------------

--
-- Structure de la table `Historique`
--
-- Création: Jeu 13 Février 2014 à 22:04
--

CREATE TABLE `Historique` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL COMMENT 'Date d''application',
  `idClient` int(11) NOT NULL,
  `idReduction` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idClient` (`idClient`),
  KEY `idReduction` (`idReduction`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `Reductions`
--
-- Création: Ven 14 Février 2014 à 09:46
--

CREATE TABLE `Reductions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(200) NOT NULL,
  `cout` float NOT NULL COMMENT 'Cout en cagnotte de la réduction',
  `type` varchar(10) NOT NULL COMMENT '''pourcent'' ou ''brut''',
  `valeur` float NOT NULL COMMENT 'Valeur de la réduction (€ ou %)',
  `debut` date NOT NULL COMMENT 'Date de début',
  `fin` date NOT NULL COMMENT 'Date de fin',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `Reductions`
--

INSERT INTO `Reductions` (`id`, `description`, `cout`, `type`, `valeur`, `debut`, `fin`) VALUES
(1, 'Réduction numéro 1', 25, 'brut', 0.6, '2014-03-01', '2014-03-31'),
(2, 'Autre réduction', 10, 'pourcent', 2.5, '2014-02-15', '2014-03-15');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `Historique`
--
ALTER TABLE `Historique`
  ADD CONSTRAINT `historique_ibfk_1` FOREIGN KEY (`idClient`) REFERENCES `Clients` (`id`),
  ADD CONSTRAINT `historique_ibfk_2` FOREIGN KEY (`idReduction`) REFERENCES `Reductions` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
