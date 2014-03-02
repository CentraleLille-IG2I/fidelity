-- phpMyAdmin SQL Dump
-- version 4.0.6
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Dim 02 Mars 2014 à 15:19
-- Version du serveur: 5.5.33
-- Version de PHP: 5.2.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données: `fidelity`
--

-- --------------------------------------------------------

--
-- Structure de la table `Clients`
--
-- Création: Dim 02 Mars 2014 à 14:15
--

DROP TABLE IF EXISTS `Clients`;
CREATE TABLE `Clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Numéro de carte',
  `numeroCarte` int(20) NOT NULL,
  `civilite` varchar(4) NOT NULL COMMENT '"Mr", "Mme", "Mlle"',
  `nom` varchar(50) CHARACTER SET latin1 NOT NULL COMMENT 'Nom du client',
  `prenom` varchar(50) CHARACTER SET latin1 NOT NULL COMMENT 'Prénom du client',
  `adresse` varchar(200) CHARACTER SET latin1 NOT NULL COMMENT 'Adresse du client',
  `ville` varchar(50) CHARACTER SET latin1 NOT NULL COMMENT 'Ville du client',
  `codePostal` int(11) DEFAULT NULL COMMENT 'Code postal du client',
  `telephone` varchar(20) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Numéro de téléphone',
  `telephone2` varchar(20) DEFAULT NULL COMMENT 'Deuxième téléphone',
  `mail` varchar(100) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Adresse mail du client',
  `aboMail` tinyint(4) NOT NULL COMMENT 'Booléen d''abonnement mail',
  `aboSms` tinyint(4) NOT NULL COMMENT 'Booléen d''abonnement SMS',
  `cagnotte` float NOT NULL COMMENT 'Cagnotte en cours',
  `dateDeNaissance` date DEFAULT NULL COMMENT 'Date de naissance du client',
  `interets` varchar(300) CHARACTER SET latin1 DEFAULT NULL COMMENT 'Centre d''intéret du client',
  PRIMARY KEY (`id`),
  UNIQUE KEY `numeroCarte` (`numeroCarte`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Table de clients' AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Structure de la table `Historique`
--
-- Création: Mar 25 Février 2014 à 18:27
--

DROP TABLE IF EXISTS `Historique`;
CREATE TABLE `Historique` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idClient` int(11) NOT NULL,
  `idReduction` int(11) NOT NULL,
  `date` date NOT NULL COMMENT 'Date d''application',
  `total` float NOT NULL,
  `reduction` float NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idClient` (`idClient`),
  KEY `idReduction` (`idReduction`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `Reductions`
--
-- Création: Mar 18 Février 2014 à 16:27
--

DROP TABLE IF EXISTS `Reductions`;
CREATE TABLE `Reductions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(200) CHARACTER SET latin1 NOT NULL,
  `cout` float NOT NULL COMMENT 'Cout en cagnotte de la réduction',
  `type` varchar(10) CHARACTER SET latin1 NOT NULL COMMENT '''pourcent'' ou ''brut''',
  `valeur` float NOT NULL COMMENT 'Valeur de la réduction (€ ou %)',
  `debut` date NOT NULL COMMENT 'Date de début',
  `fin` date NOT NULL COMMENT 'Date de fin',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `Historique`
--
ALTER TABLE `Historique`
  ADD CONSTRAINT `historique_ibfk_1` FOREIGN KEY (`idClient`) REFERENCES `Clients` (`id`),
  ADD CONSTRAINT `historique_ibfk_2` FOREIGN KEY (`idReduction`) REFERENCES `Reductions` (`id`);
