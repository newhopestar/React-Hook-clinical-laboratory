-- Adminer 4.6.2 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `access_token` text,
  `email_verify` text,
  `verify_code` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`, `phone`, `access_token`, `email_verify`, `verify_code`) VALUES
(9,	'Jafarali',	'Maknojiya',	'jafaraliwork14@gmail.com',	'$2y$10$2s1uwb.TiTeVbcXLCGo/AeTt2OVcsIpi40yJmPPQNg4IXtGmo3X1.',	'9898547604',	'4f71ba813509f31456ec473c05d1baba',	NULL,	NULL);

-- 2020-01-31 17:19:29
