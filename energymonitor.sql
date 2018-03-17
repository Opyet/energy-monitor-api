-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 17, 2018 at 11:01 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `energymonitor`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category`) VALUES
(1, 'Administrative Blocks'),
(2, 'Laboratory'),
(3, 'Hostel'),
(4, 'Lecture Hall'),
(5, 'Business Unit'),
(6, 'Others');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE IF NOT EXISTS `login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `nodes`
--

CREATE TABLE IF NOT EXISTS `nodes` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `categoryId` int(20) unsigned NOT NULL,
  `nodeName` varchar(50) NOT NULL,
  `dayIndex` double unsigned DEFAULT NULL,
  `monthIndex` double unsigned DEFAULT NULL,
  `yearIndex` double unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_categoryId` (`categoryId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `nodes`
--

INSERT INTO `nodes` (`id`, `categoryId`, `nodeName`, `dayIndex`, `monthIndex`, `yearIndex`) VALUES
(1, 1, 'Faculty of Technology Building', 88, 2990, 34754),
(2, 3, 'Awolowo Hall', NULL, NULL, NULL),
(3, 4, 'BOOC', NULL, NULL, NULL),
(4, 2, 'ICT Laboratory', NULL, NULL, NULL),
(5, 5, 'Badejoko Stores', NULL, NULL, NULL),
(6, 6, 'Others', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `readings`
--

CREATE TABLE IF NOT EXISTS `readings` (
  `nodeId` int(20) unsigned NOT NULL,
  `reading` double unsigned NOT NULL,
  `created` timestamp NOT NULL,
  `dayAccumulation` double unsigned NOT NULL,
  `monthAccumulation` double unsigned NOT NULL,
  `yearAccumulation` double unsigned NOT NULL,
  KEY `fk_node_id` (`nodeId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `readings`
--

INSERT INTO `readings` (`nodeId`, `reading`, `created`, `dayAccumulation`, `monthAccumulation`, `yearAccumulation`) VALUES
(1, 65, '2018-03-08 11:55:00', 65, 65, 65),
(1, 68.43, '2018-03-08 11:55:08', 133.43, 133.43, 133.43),
(1, 70, '2018-03-11 22:36:17', 70, 203.43, 203.43),
(1, 78, '2018-03-11 22:36:27', 148, 281.43, 281.43),
(1, 80.45, '2018-03-11 22:39:58', 228.45, 361.88, 361.88),
(1, 90.45, '2018-03-12 00:25:58', 90.45, 452.33, 452.33),
(1, 78, '2018-03-12 00:27:02', 168.45, 530.33, 530.33),
(1, 83, '2018-03-12 08:38:12', 251.45, 613.33, 613.33),
(1, 81.2, '2018-03-12 08:38:36', 332.65, 694.53, 694.53),
(1, 80.4, '2018-03-12 08:38:45', 413.05, 774.93, 774.93),
(1, 77.9, '2018-03-12 08:38:55', 490.95, 852.83, 852.83);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `nodes`
--
ALTER TABLE `nodes`
  ADD CONSTRAINT `fk_categoryId` FOREIGN KEY (`categoryId`) REFERENCES `categories` (`id`);

--
-- Constraints for table `readings`
--
ALTER TABLE `readings`
  ADD CONSTRAINT `fk_node_id` FOREIGN KEY (`nodeId`) REFERENCES `nodes` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
