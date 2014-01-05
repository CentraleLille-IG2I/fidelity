-- phpMyAdmin SQL Dump
-- version 4.0.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 05, 2014 at 09:52 PM
-- Server version: 5.5.33
-- PHP Version: 5.5.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `fidelity`
--

-- --------------------------------------------------------

--
-- Table structure for table `Clients`
--

CREATE TABLE `Clients` (
  `id` int(11) NOT NULL COMMENT 'Numéro de carte',
  `nom` varchar(50) NOT NULL COMMENT 'Nom du client',
  `prenom` varchar(50) NOT NULL COMMENT 'Prénom du client',
  `adresse` varchar(200) NOT NULL COMMENT 'Adresse du client',
  `ville` varchar(50) NOT NULL COMMENT 'Ville du client',
  `code_postal` int(11) DEFAULT NULL COMMENT 'Code postal du client',
  `telephone` varchar(20) DEFAULT NULL COMMENT 'Numéro de téléphone',
  `mail` varchar(100) DEFAULT NULL COMMENT 'Adresse mail du client',
  `abo_mail` tinyint(4) NOT NULL COMMENT 'Booléen d''abonnement mail',
  `abo_sms` tinyint(4) NOT NULL COMMENT 'Booléen d''abonnement SMS',
  `cagnotte` float NOT NULL COMMENT 'Cagnotte en cours',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table de clients';
