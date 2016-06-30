-- phpMyAdmin SQL Dump
-- version 4.1.8
-- http://www.phpmyadmin.net
--
-- Client :  localhost:8889
-- Généré le :  Mer 20 Avril 2016 à 08:55
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
CREATE DATABASE IF NOT EXISTS `artemistest` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `artemistest`;

-- --------------------------------------------------------

--
-- Structure de la table `config`
--

CREATE TABLE `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_simu` int(11) NOT NULL DEFAULT '0',
  `key` varchar(60) NOT NULL,
  `value` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `critlevels`
--

CREATE TABLE `critlevels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(2) NOT NULL,
  `name` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `critswitches`
--

CREATE TABLE `critswitches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_simu` int(11) NOT NULL DEFAULT '0',
  `time` int(11) DEFAULT NULL,
  `level` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `link`
--

CREATE TABLE `link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_simu` int(11) NOT NULL,
  `node1` int(11) NOT NULL,
  `node2` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_simu` int(11) NOT NULL DEFAULT '0',
  `path` varchar(255) NOT NULL,
  `period` int(11) NOT NULL,
  `offset` int(11) NOT NULL,
  `color` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `node`
--

CREATE TABLE `node` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_simu` int(11) NOT NULL DEFAULT '0',
  `name` varchar(25) NOT NULL,
  `ip_address` int(11) NOT NULL,
  `scheduling` varchar(25) NOT NULL DEFAULT 'FIFO',
  `displayed` tinyint(1) NOT NULL DEFAULT '0',
  `speed` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `simulations`
--

CREATE TABLE `simulations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_simu` int(11) NOT NULL,
  `id_session` varchar(100) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `wcets`
--

CREATE TABLE `wcets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_msg` int(11) NOT NULL,
  `id_clvl` int(11) NOT NULL,
  `value` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
