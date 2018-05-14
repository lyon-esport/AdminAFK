-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  lun. 14 mai 2018 à 09:29
-- Version du serveur :  5.7.19
-- Version de PHP :  5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `adminafk`
--

-- --------------------------------------------------------

--
-- Structure de la table `configs`
--

DROP TABLE IF EXISTS `configs`;
CREATE TABLE IF NOT EXISTS `configs` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(3000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `configs`
--

INSERT INTO `configs` (`id`, `name`, `value`, `type`) VALUES
(1, 'url_ebot', '', 'string'),
(2, 'url_glyphicon', '', 'string'),
(3, 'default_ebot_rules', '', 'string'),
(4, 'default_ebot_pass', '', 'string'),
(5, 'default_ebot_over_mmr', '', 'integer'),
(6, 'default_ebot_over_money', '', 'integer'),
(7, 'toornament_api', '', 'string'),
(8, 'toornament_client_id', '', 'string'),
(9, 'toornament_client_secret', '', 'string'),
(10, 'toornament_id', '', 'string'),
(11, 'display_connect', '1', 'boolean'),
(12, 'display_bracket', '1', 'boolean'),
(13, 'display_participants', '1', 'boolean'),
(14, 'display_schedule', '1', 'boolean'),
(15, 'display_stream', '1', 'boolean');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
