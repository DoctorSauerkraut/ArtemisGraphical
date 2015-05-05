-- phpMyAdmin SQL Dump
-- version 4.1.8
-- http://www.phpmyadmin.net
--
-- Client :  localhost:8889
-- Généré le :  Mer 11 Février 2015 à 17:51
-- Version du serveur :  5.5.34
-- Version de PHP :  5.5.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `artemis`
--
CREATE DATABASE IF NOT EXISTS `artemis` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `artemis`;

-- --------------------------------------------------------

--
-- Structure de la table `config`
--

DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(60) NOT NULL,
  `value` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

--
-- Vider la table avant d'insérer `config`
--

TRUNCATE TABLE `config`;
--
-- Contenu de la table `config`
--

INSERT INTO `config` (`id`, `key`, `value`) VALUES
(24, 'timelimit', '300'),
(25, 'elatency', '10');

-- --------------------------------------------------------

--
-- Structure de la table `critlevels`
--

DROP TABLE IF EXISTS `critlevels`;
CREATE TABLE `critlevels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(2) NOT NULL,
  `name` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Vider la table avant d'insérer `critlevels`
--

TRUNCATE TABLE `critlevels`;
--
-- Contenu de la table `critlevels`
--

INSERT INTO `critlevels` (`id`, `code`, `name`) VALUES
(1, 'NC', 'Non-critical'),
(9, 'C', 'Critical'),
(10, 'SC', 'Safety Critical');

-- --------------------------------------------------------

--
-- Structure de la table `critswitches`
--

DROP TABLE IF EXISTS `critswitches`;
CREATE TABLE `critswitches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) DEFAULT NULL,
  `level` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Vider la table avant d'insérer `critswitches`
--

TRUNCATE TABLE `critswitches`;
-- --------------------------------------------------------

--
-- Structure de la table `link`
--

DROP TABLE IF EXISTS `link`;
CREATE TABLE `link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `node1` int(11) NOT NULL,
  `node2` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Vider la table avant d'insérer `link`
--

TRUNCATE TABLE `link`;
-- --------------------------------------------------------

--
-- Structure de la table `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(255) NOT NULL,
  `period` int(11) NOT NULL,
  `offset` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Vider la table avant d'insérer `message`
--

TRUNCATE TABLE `message`;
-- --------------------------------------------------------

--
-- Structure de la table `node`
--

DROP TABLE IF EXISTS `node`;
CREATE TABLE `node` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `ip_address` int(11) NOT NULL,
  `scheduling` varchar(25) NOT NULL DEFAULT 'FIFO',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Vider la table avant d'insérer `node`
--

TRUNCATE TABLE `node`;
--
-- Contenu de la table `node`
--

INSERT INTO `node` (`id`, `name`, `ip_address`, `scheduling`) VALUES
(1, 'Unnamed ', 0, 'FIFO'),
(2, 'Unnamed ', 0, 'FIFO');

-- --------------------------------------------------------

--
-- Structure de la table `wcets`
--

DROP TABLE IF EXISTS `wcets`;
CREATE TABLE `wcets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_msg` int(11) NOT NULL,
  `id_clvl` int(11) NOT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Vider la table avant d'insérer `wcets`
--

TRUNCATE TABLE `wcets`;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
