-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 10 jan 2013 om 21:32
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
  `game` tinyint(4) NOT NULL,
  `match_id` int(11) NOT NULL,
  `id_team1` int(11) NOT NULL,
  `id_team2` int(11) NOT NULL,
  `points_team1` int(11) NOT NULL DEFAULT '0',
  `points_team2` int(11) NOT NULL DEFAULT '0',
  `roem_team1` int(11) NOT NULL DEFAULT '0',
  `roem_team2` int(11) NOT NULL DEFAULT '0',
  `special_team1` varchar(3) DEFAULT NULL,
  `special_team2` varchar(3) DEFAULT NULL,
  `owner_id` int(11) NOT NULL,
  PRIMARY KEY (`game`),
  KEY `match_id` (`match_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden uitgevoerd voor tabel `games`
--

INSERT INTO `games` (`game`, `match_id`, `id_team1`, `id_team2`, `points_team1`, `points_team2`, `roem_team1`, `roem_team2`, `special_team1`, `special_team2`, `owner_id`) VALUES
(1, 31, 9, 12, 162, 0, 20, 0, NULL, 'N', 23),
(2, 31, 9, 12, 62, 100, 20, 0, NULL, NULL, 23),
(3, 31, 9, 12, 162, 0, 0, 0, NULL, 'N', 23),
(4, 31, 9, 12, 62, 100, 0, 0, NULL, NULL, 23),
(5, 31, 9, 12, 84, 78, 30, 0, NULL, NULL, 23),
(6, 31, 9, 12, 162, 0, 100, 0, NULL, 'P', 23),
(7, 31, 9, 12, 64, 98, 20, 0, NULL, NULL, 23),
(8, 31, 9, 12, 142, 20, 30, 20, NULL, NULL, 23),
(9, 31, 9, 12, 60, 102, 0, 0, NULL, NULL, 23),
(10, 31, 9, 12, 62, 100, 0, 0, NULL, NULL, 23),
(11, 31, 9, 12, 62, 100, 0, 0, NULL, NULL, 23),
(12, 31, 9, 12, 128, 34, 0, 30, NULL, NULL, 23),
(13, 31, 9, 12, 0, 162, 0, 100, 'P', NULL, 23),
(14, 31, 9, 12, 128, 34, 0, 0, NULL, NULL, 23),
(15, 31, 9, 12, 137, 25, 0, 0, NULL, NULL, 23),
(16, 31, 9, 12, 37, 125, 0, 0, NULL, NULL, 23);

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
  `points_team1` int(11) NOT NULL DEFAULT '0',
  `points_team2` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`match_id`),
  KEY `team1_id` (`id_team1`,`id_team2`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=57 ;

--
-- Gegevens worden uitgevoerd voor tabel `matches`
--

INSERT INTO `matches` (`match_id`, `round`, `poule_id`, `scheduled_date`, `played_date`, `id_team1`, `id_team2`, `points_team1`, `points_team2`) VALUES
(1, 1, 1, '2013-01-28', NULL, 1, 2, 0, 0),
(2, 2, 1, '2013-02-18', NULL, 1, 3, 0, 0),
(3, 3, 1, '2013-03-11', NULL, 1, 4, 0, 0),
(4, 4, 1, '2013-04-01', NULL, 1, 5, 0, 0),
(5, 5, 1, '2013-04-22', NULL, 1, 6, 0, 0),
(6, 6, 1, '2013-05-13', NULL, 1, 7, 0, 0),
(7, 7, 1, '2013-06-03', NULL, 1, 8, 0, 0),
(8, 3, 1, '2013-03-11', NULL, 2, 3, 0, 0),
(9, 4, 1, '2013-04-01', NULL, 2, 4, 0, 0),
(10, 5, 1, '2013-04-22', NULL, 2, 5, 0, 0),
(11, 6, 1, '2013-05-13', NULL, 2, 6, 0, 0),
(12, 7, 1, '2013-06-03', NULL, 2, 7, 0, 0),
(13, 8, 1, '2013-06-24', NULL, 2, 8, 0, 0),
(14, 5, 1, '2013-04-22', NULL, 3, 4, 0, 0),
(15, 6, 1, '2013-05-13', NULL, 3, 5, 0, 0),
(16, 7, 1, '2013-06-03', NULL, 3, 6, 0, 0),
(17, 8, 1, '2013-06-24', NULL, 3, 7, 0, 0),
(18, 1, 1, '2013-01-28', NULL, 3, 8, 0, 0),
(19, 7, 1, '2013-06-03', NULL, 4, 5, 0, 0),
(20, 8, 1, '2013-06-24', NULL, 4, 6, 0, 0),
(21, 1, 1, '2013-01-28', NULL, 4, 7, 0, 0),
(22, 2, 1, '2013-02-18', NULL, 4, 8, 0, 0),
(23, 1, 1, '2013-01-28', NULL, 5, 6, 0, 0),
(24, 2, 1, '2013-02-18', NULL, 5, 7, 0, 0),
(25, 3, 1, '2013-03-11', NULL, 5, 8, 0, 0),
(26, 3, 1, '2013-03-11', NULL, 6, 7, 0, 0),
(27, 4, 1, '2013-04-01', NULL, 6, 8, 0, 0),
(28, 5, 1, '2013-04-22', NULL, 7, 8, 0, 0),
(29, 1, 2, '2013-01-28', NULL, 9, 10, 0, 0),
(30, 2, 2, '2013-02-18', NULL, 9, 11, 0, 0),
(31, 3, 2, '2013-03-11', '2013-01-01', 9, 12, 1514, 1078),
(32, 4, 2, '2013-04-01', NULL, 9, 13, 0, 0),
(33, 5, 2, '2013-04-22', NULL, 9, 14, 0, 0),
(34, 6, 2, '2013-05-13', NULL, 9, 15, 0, 0),
(35, 7, 2, '2013-06-03', NULL, 9, 16, 0, 0),
(36, 3, 2, '2013-03-11', NULL, 10, 11, 0, 0),
(37, 4, 2, '2013-04-01', NULL, 10, 12, 0, 0),
(38, 5, 2, '2013-04-22', NULL, 10, 13, 0, 0),
(39, 6, 2, '2013-05-13', NULL, 10, 14, 0, 0),
(40, 7, 2, '2013-06-03', NULL, 10, 15, 0, 0),
(41, 8, 2, '2013-06-24', NULL, 10, 16, 0, 0),
(42, 5, 2, '2013-04-22', NULL, 11, 12, 0, 0),
(43, 6, 2, '2013-05-13', NULL, 11, 13, 0, 0),
(44, 7, 2, '2013-06-03', NULL, 11, 14, 0, 0),
(45, 8, 2, '2013-06-24', NULL, 11, 15, 0, 0),
(46, 1, 2, '2013-01-28', NULL, 11, 16, 0, 0),
(47, 7, 2, '2013-06-03', NULL, 12, 13, 0, 0),
(48, 8, 2, '2013-06-24', NULL, 12, 14, 0, 0),
(49, 1, 2, '2013-01-28', NULL, 12, 15, 0, 0),
(50, 2, 2, '2013-02-18', NULL, 12, 16, 0, 0),
(51, 1, 2, '2013-01-28', NULL, 13, 14, 0, 0),
(52, 2, 2, '2013-02-18', NULL, 13, 15, 0, 0),
(53, 3, 2, '2013-03-11', NULL, 13, 16, 0, 0),
(54, 3, 2, '2013-03-11', NULL, 14, 15, 0, 0),
(55, 4, 2, '2013-04-01', NULL, 14, 16, 0, 0),
(56, 5, 2, '2013-04-22', NULL, 15, 16, 0, 0);

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
  `name` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `level` tinyint(4) NOT NULL DEFAULT '1',
  `team_id` int(11) DEFAULT NULL,
  `confirmation` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`player_id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `confirmation` (`confirmation`),
  KEY `team_id` (`team_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=35 ;

--
-- Gegevens worden uitgevoerd voor tabel `players`
--

INSERT INTO `players` (`player_id`, `name`, `password`, `email`, `level`, `team_id`, `confirmation`) VALUES
(1, 'dummy1', '6e069b5b87a0977be4cc786914fe98edda05d587 ipCdFd2Jfj', 'dummy1@basderuiter.nl', 1, 1, NULL),
(2, 'dummy2', '6e069b5b87a0977be4cc786914fe98edda05d587 ipCdFd2Jfj', 'dummy2@basderuiter.nl', 1, 1, NULL),
(3, 'dummy3', '6e069b5b87a0977be4cc786914fe98edda05d587 ipCdFd2Jfj', 'dummy3@basderuiter.nl', 1, 2, NULL),
(4, 'dummy4', '6e069b5b87a0977be4cc786914fe98edda05d587 ipCdFd2Jfj', 'dummy4@basderuiter.nl', 1, 2, NULL),
(5, 'dummy5', '6e069b5b87a0977be4cc786914fe98edda05d587 ipCdFd2Jfj', 'dummy5@basderuiter.nl', 1, 3, NULL),
(6, 'dummy6', '6e069b5b87a0977be4cc786914fe98edda05d587 ipCdFd2Jfj', 'dummy6@basderuiter.nl', 1, 3, NULL),
(7, 'dummy7', '6e069b5b87a0977be4cc786914fe98edda05d587 ipCdFd2Jfj', 'dummy7@basderuiter.nl', 1, 4, NULL),
(8, 'dummy8', '6e069b5b87a0977be4cc786914fe98edda05d587 ipCdFd2Jfj', 'dummy8@basderuiter.nl', 1, 4, NULL),
(9, 'dummy9', '6e069b5b87a0977be4cc786914fe98edda05d587 ipCdFd2Jfj', 'dummy9@basderuiter.nl', 1, 5, NULL),
(10, 'dummy10', '6e069b5b87a0977be4cc786914fe98edda05d587 ipCdFd2Jfj', 'dummy10@basderuiter.nl', 1, 5, NULL),
(11, 'dummy11', '6e069b5b87a0977be4cc786914fe98edda05d587 ipCdFd2Jfj', 'dummy11@basderuiter.nl', 1, 6, NULL),
(12, 'dummy12', '6e069b5b87a0977be4cc786914fe98edda05d587 ipCdFd2Jfj', 'dummy12@basderuiter.nl', 1, 6, NULL),
(13, 'dummy13', '6e069b5b87a0977be4cc786914fe98edda05d587 ipCdFd2Jfj', 'dummy13@basderuiter.nl', 1, 7, NULL),
(14, 'dummy14', '6e069b5b87a0977be4cc786914fe98edda05d587 ipCdFd2Jfj', 'dummy14@basderuiter.nl', 1, 7, NULL),
(15, 'dummy15', '6e069b5b87a0977be4cc786914fe98edda05d587 ipCdFd2Jfj', 'dummy15@basderuiter.nl', 1, 8, NULL),
(16, 'dummy16', '6e069b5b87a0977be4cc786914fe98edda05d587 ipCdFd2Jfj', 'dummy16@basderuiter.nl', 1, 8, NULL),
(17, 'dummy17', '6e069b5b87a0977be4cc786914fe98edda05d587 ipCdFd2Jfj', 'dummy17@basderuiter.nl', 1, 9, NULL),
(18, 'dummy18', '6e069b5b87a0977be4cc786914fe98edda05d587 ipCdFd2Jfj', 'dummy18@basderuiter.nl', 1, 9, NULL),
(19, 'dummy19', '6e069b5b87a0977be4cc786914fe98edda05d587 ipCdFd2Jfj', 'dummy19@basderuiter.nl', 1, 10, NULL),
(20, 'dummy20', '6e069b5b87a0977be4cc786914fe98edda05d587 ipCdFd2Jfj', 'dummy20@basderuiter.nl', 1, 10, NULL),
(21, 'dummy21', '6e069b5b87a0977be4cc786914fe98edda05d587 ipCdFd2Jfj', 'dummy21@basderuiter.nl', 1, 11, NULL),
(22, 'dummy22', '6e069b5b87a0977be4cc786914fe98edda05d587 ipCdFd2Jfj', 'dummy22@basderuiter.nl', 1, 11, NULL),
(23, 'Bas de Ruiter', '7fb4fb28e0edd752a53cee79f57aa9646ca9738a NZGCaoNboi', 'deruiterb@gmail.com', 1, 12, NULL),
(24, 'dummy24', '6e069b5b87a0977be4cc786914fe98edda05d587 ipCdFd2Jfj', 'dummy24@basderuiter.nl', 1, 12, NULL),
(25, 'dummy25', '6e069b5b87a0977be4cc786914fe98edda05d587 ipCdFd2Jfj', 'dummy25@basderuiter.nl', 1, 13, NULL),
(26, 'dummy26', '6e069b5b87a0977be4cc786914fe98edda05d587 ipCdFd2Jfj', 'dummy26@basderuiter.nl', 1, 13, NULL),
(27, 'dummy27', '6e069b5b87a0977be4cc786914fe98edda05d587 ipCdFd2Jfj', 'dummy27@basderuiter.nl', 1, 14, NULL),
(28, 'dummy28', '6e069b5b87a0977be4cc786914fe98edda05d587 ipCdFd2Jfj', 'dummy28@basderuiter.nl', 1, 14, NULL),
(29, 'dummy29', '6e069b5b87a0977be4cc786914fe98edda05d587 ipCdFd2Jfj', 'dummy29@basderuiter.nl', 1, 15, NULL),
(30, 'dummy30', '6e069b5b87a0977be4cc786914fe98edda05d587 ipCdFd2Jfj', 'dummy30@basderuiter.nl', 1, 15, NULL),
(31, 'dummy31', '6e069b5b87a0977be4cc786914fe98edda05d587 ipCdFd2Jfj', 'dummy31@basderuiter.nl', 1, 16, NULL),
(32, 'dummy32', '6e069b5b87a0977be4cc786914fe98edda05d587 ipCdFd2Jfj', 'dummy32@basderuiter.nl', 1, 16, NULL),
(33, 'Test', '6e069b5b87a0977be4cc786914fe98edda05d587 ipCdFd2Jfj', 'Test@basderuiter.nl', 1, 17, NULL),
(34, 'Test Teamloos', '6e069b5b87a0977be4cc786914fe98edda05d587 ipCdFd2Jfj', 'Test Teamloos@basderuiter.nl', 1, NULL, NULL);

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
  `poule_id` int(11) DEFAULT NULL,
  `played` tinyint(4) NOT NULL DEFAULT '0',
  `wins` tinyint(4) NOT NULL DEFAULT '0',
  `losses` tinyint(4) NOT NULL DEFAULT '0',
  `points` int(4) NOT NULL DEFAULT '0',
  `points_against` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`team_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Gegevens worden uitgevoerd voor tabel `teams`
--

INSERT INTO `teams` (`team_id`, `name`, `poule_id`, `played`, `wins`, `losses`, `points`, `points_against`) VALUES
(1, 'Team 1', 1, 0, 0, 0, 0, 0),
(2, 'Team 2', 1, 0, 0, 0, 0, 0),
(3, 'Team 3', 1, 0, 0, 0, 0, 0),
(4, 'Team 4', 1, 0, 0, 0, 0, 0),
(5, 'Team 5', 1, 0, 0, 0, 0, 0),
(6, 'Team 6', 1, 0, 0, 0, 0, 0),
(7, 'Team 7', 1, 0, 0, 0, 0, 0),
(8, 'Team 8', 1, 0, 0, 0, 0, 0),
(9, 'Team 9', 2, 1, 1, 0, 1514, 1078),
(10, 'Team 10', 2, 0, 0, 0, 0, 0),
(11, 'Team 11', 2, 0, 0, 0, 0, 0),
(12, 'Team 12', 2, 1, 0, 1, 1078, 1514),
(13, 'Team 13', 2, 0, 0, 0, 0, 0),
(14, 'Team 14', 2, 0, 0, 0, 0, 0),
(15, 'Team 15', 2, 0, 0, 0, 0, 0),
(16, 'Team 16', 2, 0, 0, 0, 0, 0),
(17, 'De raggende mannen', NULL, 0, 0, 0, 0, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
