-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  lun. 08 avr. 2019 à 07:41
-- Version du serveur :  5.7.23
-- Version de PHP :  7.2.10

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
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `configs`
--

INSERT INTO `configs` (`id`, `name`, `value`, `type`) VALUES
(1, 'url_ebot', '', 'string'),
(2, 'url_glyphicon', '', 'string'),
(3, 'default_ebot_rules', 'Lyon e-Sport', 'string'),
(4, 'default_ebot_pass', 'LES', 'string'),
(8, 'default_ebot_ot_mmr', '5', 'integer'),
(9, 'default_ebot_ot_money', '16000', 'integer'),
(10, 'toornament_api', '', 'string'),
(11, 'toornament_client_id', '', 'string'),
(12, 'toornament_client_secret', '', 'string'),
(13, 'toornament_id', '', 'string'),
(14, 'display_connect', '1', 'boolean'),
(16, 'display_bracket', '1', 'boolean'),
(17, 'display_participants', '1', 'boolean'),
(18, 'display_schedule', '1', 'boolean'),
(19, 'display_stream', '1', 'boolean'),
(6, 'default_ebot_ot_status', '1', 'boolean'),
(7, 'default_ebot_knife', '1', 'boolean'),
(5, 'default_ebot_match_mmr', '15', 'integer'),
(20, 'steam_api', '', 'string'),
(21, 'steamid_api', '', 'string'),
(15, 'display_veto', '1', 'boolean'),
(22, 'display_vac_ban', '1', 'boolean');

-- --------------------------------------------------------

--
-- Structure de la table `log`
--

DROP TABLE IF EXISTS `log`;
CREATE TABLE IF NOT EXISTS `log` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `action` varchar(3000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `created_by` varchar(3000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(3000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `level` int(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `login_fail`
--

DROP TABLE IF EXISTS `login_fail`;
CREATE TABLE IF NOT EXISTS `login_fail` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `login_try` varchar(3000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `fail` varchar(3000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(3000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `token`
--

DROP TABLE IF EXISTS `token`;
CREATE TABLE IF NOT EXISTS `token` (
  `name` varchar(300) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `number` varchar(3000) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` date NOT NULL,
  `end_at` date NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `token`
--

INSERT INTO `token` (`name`, `number`, `created_at`, `end_at`) VALUES
('organizer:admin', '', '2018-07-13', '2018-07-13'),
('organizer:result', '', '2018-07-13', '2018-07-13'),
('organizer:participant', '', '2018-07-13', '2018-07-13'),
('organizer:view', '', '2018-07-13', '2018-07-13');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `login` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `level` int(255) NOT NULL DEFAULT '2',
  `created_at` datetime NOT NULL,
  `created_by` varchar(3000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `update_at` datetime NOT NULL,
  `update_by` varchar(3000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime NOT NULL,
  `last_ip` varchar(3000) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`login`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`login`, `password`, `level`, `created_at`, `created_by`, `update_at`, `update_by`, `last_login`, `last_ip`) VALUES
('admin', '$2y$10$lGz.ChiP2zfaI0vWLNQUreTfiOldZ0aFdd/9OO5b.FHCebsGaup7.', 1, '2018-04-08 00:00:00', 'admin', '2018-05-05 13:51:29', 'admin', '2018-07-08 14:32:14', '127.0.0.1');

-- --------------------------------------------------------

--
-- Structure de la table `veto`
--

DROP TABLE IF EXISTS `veto`;
CREATE TABLE IF NOT EXISTS `veto` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `team_1` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `team_2` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `format` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `mode` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `de_dust2` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `de_cache` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `de_mirage` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `de_overpass` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `de_nuke` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `de_cobblestone` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `de_train` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `de_inferno` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `de_vertigo` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `ban_order` varchar(3000) CHARACTER SET utf32 COLLATE utf32_unicode_ci DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
