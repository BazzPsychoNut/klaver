-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 20 jan 2013 om 09:26
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
-- Tabelstructuur voor tabel `competition`
--

CREATE TABLE IF NOT EXISTS `competition` (
  `season` tinyint(4) NOT NULL AUTO_INCREMENT,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `end_results` text,
  PRIMARY KEY (`season`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

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
  KEY `match_id` (`match_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `matches`
--

CREATE TABLE IF NOT EXISTS `matches` (
  `match_id` int(11) NOT NULL AUTO_INCREMENT,
  `round` tinyint(4) NOT NULL,
  `poule_id` int(11) NOT NULL,
  `scheduled_date` date NOT NULL,
  `picked_date` date DEFAULT NULL,
  `played_date` date DEFAULT NULL,
  `id_team1` int(11) NOT NULL,
  `id_team2` int(11) NOT NULL,
  `points_team1` int(11) NOT NULL DEFAULT '0',
  `points_team2` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`match_id`),
  KEY `team1_id` (`id_team1`,`id_team2`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `match_planning`
--

CREATE TABLE IF NOT EXISTS `match_planning` (
  `match_id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `plan_date` date DEFAULT NULL,
  `availability` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `match_planning_comments`
--

CREATE TABLE IF NOT EXISTS `match_planning_comments` (
  `match_id` int(11) NOT NULL,
  `player_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `create_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `poules`
--

CREATE TABLE IF NOT EXISTS `poules` (
  `poule_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  PRIMARY KEY (`poule_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
