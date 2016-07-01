-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mer 21 Mai 2014 à 08:36
-- Version du serveur: 5.6.12-log
-- Version de PHP: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `artemis`
--
CREATE DATABASE IF NOT EXISTS `artemis` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `artemis`;

-- --------------------------------------------------------

--
-- Structure de la table `link`
--

CREATE TABLE IF NOT EXISTS `link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `node1` varchar(25) NOT NULL,
  `node2` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `link`
--

INSERT INTO `link` (`id`, `node1`, `node2`) VALUES
(1, 'Machine 1', 'Machine 2'),
(2, 'Machine 2', 'Machine 3'),
(3, 'Machine 3', 'Machine 4'),
(4, 'Machine 1', 'Machine 5'),
(5, 'Machine 4', 'Machine 5'),
(6, 'Machine 5', 'Machine 2');

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(255) NOT NULL,
  `period` int(11) NOT NULL,
  `offset` int(11) NOT NULL,
  `wcet` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `message`
--

INSERT INTO `message` (`id`, `path`, `period`, `offset`, `wcet`) VALUES
(1, 'Machine 1,Machine 2,Machine 3,Machine 4', 1, 2, 3),
(2, 'Machine 1,Machine 2,Machine 5', 2, 3, 4);

-- --------------------------------------------------------

--
-- Structure de la table `node`
--

CREATE TABLE IF NOT EXISTS `node` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `ip_address` int(11) NOT NULL,
  `scheduling` varchar(25) NOT NULL DEFAULT 'FIFO',
  `criticality` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `node`
--

INSERT INTO `node` (`id`, `name`, `ip_address`, `scheduling`, `criticality`) VALUES
(1, 'Machine 1', 1, 'FIFO', 0),
(2, 'Machine 2', 12, 'FIFO', 1),
(3, 'Machine 3', 45, 'FIFO', 1),
(4, 'Machine 4', 11, 'FIFO', 0),
(5, 'Machine 5', 5, 'FIFO', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
