-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mer. 20 juin 2018 à 00:25
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
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `configs`
--

INSERT INTO `configs` (`id`, `name`, `value`, `type`) VALUES
(1, 'url_ebot', '', 'string'),
(2, 'url_glyphicon', '', 'string'),
(3, 'default_ebot_rules', 'Lyon e-Sport', 'string'),
(4, 'default_ebot_pass', 'LES', 'string'),
(8, 'default_ebot_ot_mmr', '3', 'integer'),
(9, 'default_ebot_ot_money', '16000', 'integer'),
(10, 'toornament_api', '', 'string'),
(11, 'toornament_client_id', '', 'string'),
(12, 'toornament_client_secret', '', 'string'),
(13, 'toornament_id', '', 'string'),
(14, 'display_connect', '1', 'boolean'),
(15, 'display_bracket', '1', 'boolean'),
(16, 'display_participants', '1', 'boolean'),
(17, 'display_schedule', '1', 'boolean'),
(18, 'display_stream', '1', 'boolean'),
(6, 'default_ebot_ot_status', '1', 'boolean'),
(7, 'default_ebot_knife', '1', 'boolean'),
(5, 'default_ebot_match_mmr', '15', 'integer'),
(19, 'steam_api', '', 'string'),
(20, 'steamid_api', '', 'string'),
(21, 'display_vac_ban', '1', 'boolean');

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
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `log`
--

INSERT INTO `log` (`id`, `action`, `created_at`, `created_by`, `ip`, `level`) VALUES
(1, 'Updated toornament configuration !', '2018-06-17 16:25:15', 'admin', '127.0.0.1', 1),
(2, 'Updated toornament configuration !', '2018-06-17 16:25:36', 'admin', '127.0.0.1', 1),
(3, 'Updated toornament configuration !', '2018-06-18 12:50:28', 'admin', '127.0.0.1', 1),
(4, 'Updated toornament configuration !', '2018-06-18 12:50:41', 'admin', '127.0.0.1', 1),
(5, 'Updated toornament configuration !', '2018-06-18 13:40:49', 'admin', '127.0.0.1', 1),
(6, 'Updated toornament configuration !', '2018-06-19 19:14:57', 'admin', '127.0.0.1', 1),
(7, 'Updated toornament configuration !', '2018-06-19 19:17:19', 'admin', '127.0.0.1', 1),
(8, 'Updated Steam / SteamID configuration !', '2018-06-20 00:20:34', 'admin', '127.0.0.1', 1),
(9, 'Updated Steam / SteamID configuration !', '2018-06-20 00:21:38', 'admin', '127.0.0.1', 1),
(10, 'Updated Steam / SteamID configuration !', '2018-06-20 00:22:10', 'admin', '127.0.0.1', 1),
(11, 'Updated Steam / SteamID configuration !', '2018-06-20 00:22:14', 'admin', '127.0.0.1', 1),
(12, 'Updated Steam / SteamID configuration !', '2018-06-20 00:22:42', 'admin', '127.0.0.1', 1),
(13, 'Updated Steam / SteamID configuration !', '2018-06-20 00:23:03', 'admin', '127.0.0.1', 1),
(14, 'Updated Steam / SteamID configuration !', '2018-06-20 00:23:11', 'admin', '127.0.0.1', 1);

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
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `number` varchar(3000) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` date NOT NULL,
  `end_at` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `token`
--

INSERT INTO `token` (`id`, `number`, `created_at`, `end_at`) VALUES
(1, '', '2018-04-05', '2018-04-06');

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
('admin', '$2y$10$lGz.ChiP2zfaI0vWLNQUreTfiOldZ0aFdd/9OO5b.FHCebsGaup7.', 1, '2018-04-08 00:00:00', 'admin', '2018-05-05 13:51:29', 'admin', '2018-06-19 19:14:18', '127.0.0.1');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
