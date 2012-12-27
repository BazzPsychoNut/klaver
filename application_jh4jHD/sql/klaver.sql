-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 27 dec 2012 om 20:44
-- Serverversie: 5.5.24-log
-- PHP-versie: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databank: `klaver`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `games`
--

CREATE TABLE IF NOT EXISTS `games` (
  `game_id` int(11) NOT NULL AUTO_INCREMENT,
  `match_id` int(11) NOT NULL,
  `points_team1` int(11) NOT NULL DEFAULT '0',
  `points_team2` int(11) NOT NULL DEFAULT '0',
  `roem_team1` int(11) NOT NULL DEFAULT '0',
  `roem_team2` int(11) NOT NULL DEFAULT '0',
  `special_team1` varchar(3) DEFAULT NULL,
  `special_team2` varchar(3) DEFAULT NULL,
  PRIMARY KEY (`game_id`),
  KEY `match_id` (`match_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `matches`
--

CREATE TABLE IF NOT EXISTS `matches` (
  `match_id` int(11) NOT NULL AUTO_INCREMENT,
  `round` tinyint(4) NOT NULL,
  `poule_id` int(11) NOT NULL,
  `scheduled_date` date NOT NULL,
  `played_date` date DEFAULT NULL,
  `id_team1` int(11) NOT NULL,
  `id_team2` int(11) NOT NULL,
  `score_team1` int(11) DEFAULT NULL,
  `score_team2` int(11) DEFAULT NULL,
  PRIMARY KEY (`match_id`),
  KEY `team1_id` (`id_team1`,`id_team2`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=57 ;

--
-- Gegevens worden uitgevoerd voor tabel `matches`
--

INSERT INTO `matches` (`match_id`, `round`, `poule_id`, `scheduled_date`, `played_date`, `id_team1`, `id_team2`, `score_team1`, `score_team2`) VALUES
(1, 1, 1, '2013-01-28', NULL, 1, 2, NULL, NULL),
(2, 2, 1, '2013-02-18', NULL, 1, 3, NULL, NULL),
(3, 3, 1, '2013-03-11', NULL, 1, 4, NULL, NULL),
(4, 4, 1, '2013-04-01', NULL, 1, 5, NULL, NULL),
(5, 5, 1, '2013-04-22', NULL, 1, 6, NULL, NULL),
(6, 6, 1, '2013-05-13', NULL, 1, 7, NULL, NULL),
(7, 7, 1, '2013-06-03', NULL, 1, 8, NULL, NULL),
(8, 3, 1, '2013-03-11', NULL, 2, 3, NULL, NULL),
(9, 4, 1, '2013-04-01', NULL, 2, 4, NULL, NULL),
(10, 5, 1, '2013-04-22', NULL, 2, 5, NULL, NULL),
(11, 6, 1, '2013-05-13', NULL, 2, 6, NULL, NULL),
(12, 7, 1, '2013-06-03', NULL, 2, 7, NULL, NULL),
(13, 8, 1, '2013-06-24', NULL, 2, 8, NULL, NULL),
(14, 5, 1, '2013-04-22', NULL, 3, 4, NULL, NULL),
(15, 6, 1, '2013-05-13', NULL, 3, 5, NULL, NULL),
(16, 7, 1, '2013-06-03', NULL, 3, 6, NULL, NULL),
(17, 8, 1, '2013-06-24', NULL, 3, 7, NULL, NULL),
(18, 1, 1, '2013-01-28', NULL, 3, 8, NULL, NULL),
(19, 7, 1, '2013-06-03', NULL, 4, 5, NULL, NULL),
(20, 8, 1, '2013-06-24', NULL, 4, 6, NULL, NULL),
(21, 1, 1, '2013-01-28', NULL, 4, 7, NULL, NULL),
(22, 2, 1, '2013-02-18', NULL, 4, 8, NULL, NULL),
(23, 1, 1, '2013-01-28', NULL, 5, 6, NULL, NULL),
(24, 2, 1, '2013-02-18', NULL, 5, 7, NULL, NULL),
(25, 3, 1, '2013-03-11', NULL, 5, 8, NULL, NULL),
(26, 3, 1, '2013-03-11', NULL, 6, 7, NULL, NULL),
(27, 4, 1, '2013-04-01', NULL, 6, 8, NULL, NULL),
(28, 5, 1, '2013-04-22', NULL, 7, 8, NULL, NULL),
(29, 1, 2, '2013-01-28', NULL, 9, 10, NULL, NULL),
(30, 2, 2, '2013-02-18', NULL, 9, 11, NULL, NULL),
(31, 3, 2, '2013-03-11', NULL, 9, 12, NULL, NULL),
(32, 4, 2, '2013-04-01', NULL, 9, 13, NULL, NULL),
(33, 5, 2, '2013-04-22', NULL, 9, 14, NULL, NULL),
(34, 6, 2, '2013-05-13', NULL, 9, 15, NULL, NULL),
(35, 7, 2, '2013-06-03', NULL, 9, 16, NULL, NULL),
(36, 3, 2, '2013-03-11', NULL, 10, 11, NULL, NULL),
(37, 4, 2, '2013-04-01', NULL, 10, 12, NULL, NULL),
(38, 5, 2, '2013-04-22', NULL, 10, 13, NULL, NULL),
(39, 6, 2, '2013-05-13', NULL, 10, 14, NULL, NULL),
(40, 7, 2, '2013-06-03', NULL, 10, 15, NULL, NULL),
(41, 8, 2, '2013-06-24', NULL, 10, 16, NULL, NULL),
(42, 5, 2, '2013-04-22', NULL, 11, 12, NULL, NULL),
(43, 6, 2, '2013-05-13', NULL, 11, 13, NULL, NULL),
(44, 7, 2, '2013-06-03', NULL, 11, 14, NULL, NULL),
(45, 8, 2, '2013-06-24', NULL, 11, 15, NULL, NULL),
(46, 1, 2, '2013-01-28', NULL, 11, 16, NULL, NULL),
(47, 7, 2, '2013-06-03', NULL, 12, 13, NULL, NULL),
(48, 8, 2, '2013-06-24', NULL, 12, 14, NULL, NULL),
(49, 1, 2, '2013-01-28', NULL, 12, 15, NULL, NULL),
(50, 2, 2, '2013-02-18', NULL, 12, 16, NULL, NULL),
(51, 1, 2, '2013-01-28', NULL, 13, 14, NULL, NULL),
(52, 2, 2, '2013-02-18', NULL, 13, 15, NULL, NULL),
(53, 3, 2, '2013-03-11', NULL, 13, 16, NULL, NULL),
(54, 3, 2, '2013-03-11', NULL, 14, 15, NULL, NULL),
(55, 4, 2, '2013-04-01', NULL, 14, 16, NULL, NULL),
(56, 5, 2, '2013-04-22', NULL, 15, 16, NULL, NULL);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `matches_backup`
--

CREATE TABLE IF NOT EXISTS `matches_backup` (
  `match_id` int(11) NOT NULL DEFAULT '0',
  `round` tinyint(4) NOT NULL,
  `poule_id` int(11) NOT NULL,
  `scheduled_date` date NOT NULL,
  `played_date` date DEFAULT NULL,
  `id_team1` int(11) NOT NULL,
  `id_team2` int(11) NOT NULL,
  `score_team1` int(11) DEFAULT NULL,
  `score_team2` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Gegevens worden uitgevoerd voor tabel `matches_backup`
--

INSERT INTO `matches_backup` (`match_id`, `round`, `poule_id`, `scheduled_date`, `played_date`, `id_team1`, `id_team2`, `score_team1`, `score_team2`) VALUES
(1, 1, 1, '2012-09-09', NULL, 1, 2, 1000, 600);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `players`
--

CREATE TABLE IF NOT EXISTS `players` (
  `player_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `password` char(32) NOT NULL,
  `email` text NOT NULL,
  `level` tinyint(4) NOT NULL DEFAULT '1',
  `team_id` int(11) NOT NULL,
  PRIMARY KEY (`player_id`),
  KEY `team_id` (`team_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

--
-- Gegevens worden uitgevoerd voor tabel `players`
--

INSERT INTO `players` (`player_id`, `name`, `password`, `email`, `level`, `team_id`) VALUES
(1, 'dummy1', 'p', 'e', 1, 1),
(2, 'dummy2', 'p', 'e', 1, 1),
(3, 'dummy3', 'p', 'e', 1, 2),
(4, 'dummy4', 'p', 'e', 1, 2),
(5, 'dummy5', 'p', 'e', 1, 3),
(6, 'dummy6', 'p', 'e', 1, 3),
(7, 'dummy7', 'p', 'e', 1, 4),
(8, 'dummy8', 'p', 'e', 1, 4),
(9, 'dummy9', 'p', 'e', 1, 5),
(10, 'dummy10', 'p', 'e', 1, 5),
(11, 'dummy11', 'p', 'e', 1, 6),
(12, 'dummy12', 'p', 'e', 1, 6),
(13, 'dummy13', 'p', 'e', 1, 7),
(14, 'dummy14', 'p', 'e', 1, 7),
(15, 'dummy15', 'p', 'e', 1, 8),
(16, 'dummy16', 'p', 'e', 1, 8),
(17, 'dummy17', 'p', 'e', 1, 9),
(18, 'dummy18', 'p', 'e', 1, 9),
(19, 'dummy19', 'p', 'e', 1, 10),
(20, 'dummy20', 'p', 'e', 1, 10),
(21, 'dummy21', 'p', 'e', 1, 11),
(22, 'dummy22', 'p', 'e', 1, 11),
(23, 'dummy23', 'p', 'e', 1, 12),
(24, 'dummy24', 'p', 'e', 1, 12),
(25, 'dummy25', 'p', 'e', 1, 13),
(26, 'dummy26', 'p', 'e', 1, 13),
(27, 'dummy27', 'p', 'e', 1, 14),
(28, 'dummy28', 'p', 'e', 1, 14),
(29, 'dummy29', 'p', 'e', 1, 15),
(30, 'dummy30', 'p', 'e', 1, 15),
(31, 'dummy31', 'p', 'e', 1, 16),
(32, 'dummy32', 'p', 'e', 1, 16);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `poules`
--

CREATE TABLE IF NOT EXISTS `poules` (
  `poule_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  PRIMARY KEY (`poule_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Gegevens worden uitgevoerd voor tabel `poules`
--

INSERT INTO `poules` (`poule_id`, `name`) VALUES
(1, 'Poule A'),
(2, 'Poule B');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `teams`
--

CREATE TABLE IF NOT EXISTS `teams` (
  `team_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `poule_id` int(11) NOT NULL,
  `played` tinyint(4) NOT NULL DEFAULT '0',
  `wins` tinyint(4) NOT NULL DEFAULT '0',
  `losses` tinyint(4) NOT NULL DEFAULT '0',
  `score` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`team_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Gegevens worden uitgevoerd voor tabel `teams`
--

INSERT INTO `teams` (`team_id`, `name`, `poule_id`, `played`, `wins`, `losses`, `score`) VALUES
(1, 'Team 1', 1, 0, 0, 0, 0),
(2, 'Team 2', 1, 0, 0, 0, 0),
(3, 'Team 3', 1, 0, 0, 0, 0),
(4, 'Team 4', 1, 0, 0, 0, 0),
(5, 'Team 5', 1, 0, 0, 0, 0),
(6, 'Team 6', 1, 0, 0, 0, 0),
(7, 'Team 7', 1, 0, 0, 0, 0),
(8, 'Team 8', 1, 0, 0, 0, 0),
(9, 'Team 9', 2, 0, 0, 0, 0),
(10, 'Team 10', 2, 0, 0, 0, 0),
(11, 'Team 11', 2, 0, 0, 0, 0),
(12, 'Team 12', 2, 0, 0, 0, 0),
(13, 'Team 13', 2, 0, 0, 0, 0),
(14, 'Team 14', 2, 0, 0, 0, 0),
(15, 'Team 15', 2, 0, 0, 0, 0),
(16, 'Team 16', 2, 0, 0, 0, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
