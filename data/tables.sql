-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 18 nov. 2024 à 16:24
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `travia`
--

-- --------------------------------------------------------

--
-- Structure de la table `log`
--

DROP TABLE IF EXISTS `log`;
CREATE TABLE IF NOT EXISTS `log` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL,
  `trace` text COLLATE utf8mb4_bin,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Structure de la table `planet`
--

DROP TABLE IF EXISTS `planet`;
CREATE TABLE IF NOT EXISTS `planet` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(190) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `coord` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `x` float NOT NULL,
  `y` float NOT NULL,
  `sunName` varchar(190) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `subGridCoord` varchar(190) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `subGridX` float NOT NULL,
  `subGridY` float NOT NULL,
  `region` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `sector` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `suns` int NOT NULL,
  `moons` int NOT NULL,
  `position` int NOT NULL,
  `distance` float NOT NULL,
  `lengthDay` float NOT NULL,
  `lengthYear` float NOT NULL,
  `diameter` float NOT NULL,
  `gravity` float NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `NAME` (`name`),
  KEY `REGION` (`region`),
  KEY `SECTOR` (`sector`),
  KEY `COORDINATES` (`coord`),
  KEY `X Y COORDINATES` (`x`,`y`),
  KEY `SUBGRID COORDINATES` (`subGridCoord`),
  KEY `X Y SUBGRID COORDINATES` (`subGridX`,`subGridY`),
  KEY `DISTANCE` (`distance`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Structure de la table `ship`
--

DROP TABLE IF EXISTS `ship`;
CREATE TABLE IF NOT EXISTS `ship` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  `camp` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  `speed_kmh` float NOT NULL,
  `capacity` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `NAME` (`name`),
  KEY `CAMP` (`camp`),
  KEY `CAPACITY` (`capacity`),
  KEY `SPEED` (`speed_kmh`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Structure de la table `trip`
--

DROP TABLE IF EXISTS `trip`;
CREATE TABLE IF NOT EXISTS `trip` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `departurePlanetId` int UNSIGNED NOT NULL,
  `destinationPlanetId` int UNSIGNED NOT NULL,
  `departureDay` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `departureTime` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `shipId` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `DEPARTURE PLANET` (`departurePlanetId`),
  KEY `DESTINATION PLANET` (`destinationPlanetId`),
  KEY `DEPARTURE DAY AND TIME` (`departureDay`,`departureTime`),
  KEY `SHIP ID` (`shipId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
