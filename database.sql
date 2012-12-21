-- phpMyAdmin SQL Dump
-- version 3.5.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 21, 2012 at 02:36 PM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `doodiro`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `organizer` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET latin1 NOT NULL,
  `description` varchar(255) CHARACTER SET latin1 NOT NULL,
  `type` enum('public','private') CHARACTER SET latin1 NOT NULL,
  `duration` int(2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `organizer` (`organizer`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `organizer`, `name`, `description`, `type`, `duration`) VALUES
(2, 4, 'Examen final', 'Test final pour le cours IFT3225', 'private', 0),
(7, 1, 'Noel', 'La fête de Noel!', 'public', 12);

-- --------------------------------------------------------

--
-- Table structure for table `event_dates`
--

CREATE TABLE IF NOT EXISTS `event_dates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  PRIMARY KEY (`id`),
  KEY `event_id` (`event_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

--
-- Dumping data for table `event_dates`
--

INSERT INTO `event_dates` (`id`, `event_id`, `date`, `start_time`, `end_time`) VALUES
(1, 1, '2012-12-06', '08:00:00', '22:00:00'),
(2, 1, '2012-12-07', '08:00:00', '22:00:00'),
(3, 1, '2012-12-08', '08:00:00', '22:00:00'),
(4, 2, '2013-01-13', '08:00:00', '15:00:00'),
(5, 2, '2013-01-14', '08:00:00', '15:00:00'),
(6, 2, '2013-01-15', '08:00:00', '15:00:00'),
(7, 2, '2013-01-16', '08:00:00', '15:00:00'),
(8, 6, '2012-12-24', '05:00:00', '14:00:00'),
(9, 7, '2012-12-25', '00:00:00', '23:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `invitations`
--

CREATE TABLE IF NOT EXISTS `invitations` (
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`event_id`),
  KEY `event_id` (`event_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `invitations`
--

INSERT INTO `invitations` (`user_id`, `event_id`) VALUES
(1, 2),
(2, 2),
(3, 2),
(4, 2),
(1, 7),
(2, 7),
(3, 7),
(4, 7);

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE IF NOT EXISTS `reservations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `event_date_id` int(11) NOT NULL,
  `reservation_time` time NOT NULL,
  `can_go` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `event_date_id` (`event_date_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `user_id`, `event_date_id`, `reservation_time`, `can_go`) VALUES
(1, 1, 1, '10:00:00', 1),
(2, 1, 1, '11:00:00', 1),
(3, 1, 2, '13:00:00', 0),
(4, 1, 2, '14:00:00', 0),
(5, 1, 7, '08:00:00', 1),
(6, 2, 1, '10:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET latin1 NOT NULL,
  `password` varchar(255) CHARACTER SET latin1 NOT NULL,
  `firstname` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `lastname` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `firstname`, `lastname`, `is_admin`) VALUES
(1, 'foleybov@iro.umontreal.ca', '766c450ad1bf2ba22512def225bfc61400f08d5a', 'Vincent', 'Foley-Bourgon', 1),
(2, 'phamlemi@iro.umontreal.ca', 'fbcdd71afc9b3b3f4f69f280863f10da8684b480', 'Truong', 'Pham', 0),
(3, 'vaudrypl@iro.umontreal.ca', '6764366a0308eaf1590c74f2cd58f408f4fb0009', 'Pierre-Luc', 'Vaudry', 0),
(4, 'lapalme@iro.umontreal.ca', 'ac92e42ee79150dab054951bc737240d96300c24', 'Guy', 'Lapalme', 0);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_2` FOREIGN KEY (`organizer`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`organizer`) REFERENCES `users` (`id`);

--
-- Constraints for table `invitations`
--
ALTER TABLE `invitations`
  ADD CONSTRAINT `invitations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `invitations_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`event_date_id`) REFERENCES `event_dates` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
