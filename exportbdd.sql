-- phpMyAdmin SQL Dump
-- version 4.1.8
-- http://www.phpmyadmin.net
--
-- Client :  localhost:8889
-- Généré le :  Lun 08 Juin 2015 à 15:11
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Contenu de la table `message`
--

INSERT INTO `message` (`id`, `id_simu`, `path`, `period`, `offset`) VALUES
(1, 0, 'ES1,S1,S3,ES5', 120, 0),
(2, 0, 'ES2,S1,S3,ES5', 150, 0),
(3, 0, 'ES3,S2,S3,ES5', 400, 0),
(4, 0, 'ES4,S2,S3,ES5', 100, 10),
(5, 0, 'ES1,S1,S3,ES5', 100, 20),
(7, 0, 'ES3,S2,S3,ES5', 200, 20);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
