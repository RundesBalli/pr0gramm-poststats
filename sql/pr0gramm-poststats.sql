-- Adminer 4.8.1 MySQL 5.5.5-10.1.48-MariaDB-0+deb9u2 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP DATABASE IF EXISTS `pr0gramm-poststats`;
CREATE DATABASE `pr0gramm-poststats` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;
USE `pr0gramm-poststats`;

DELIMITER ;;

CREATE EVENT `Sitzungsbereinigung` ON SCHEDULE EVERY 1 HOUR STARTS '2019-09-21 20:33:20' ON COMPLETION NOT PRESERVE ENABLE COMMENT 'Löscht abgelaufene Sitzungen nach sechs Wochen' DO DELETE FROM `sessions` WHERE `lastActivity` < DATE_SUB(NOW(), INTERVAL 6 WEEK);;

DELIMITER ;

DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Laufende ID',
  `postId` int(10) unsigned NOT NULL COMMENT 'Post-ID (pr0gramm)',
  `commentId` int(10) unsigned NOT NULL COMMENT 'Comment-ID (pr0gramm)',
  `score` int(10) NOT NULL COMMENT 'Benis',
  `up` int(10) unsigned NOT NULL COMMENT 'Plus',
  `down` int(10) unsigned NOT NULL COMMENT 'Minus',
  `username` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Username (pr0gramm)',
  PRIMARY KEY (`id`),
  KEY `postId` (`postId`),
  KEY `commentId` (`commentId`),
  KEY `score` (`score`),
  KEY `plus` (`up`),
  KEY `minus` (`down`),
  KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Kommentartabelle';


DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Laufende ID',
  `userId` int(10) unsigned NOT NULL COMMENT 'User ID',
  `hash` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Sitzungshash',
  `lastActivity` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Zeitpunkt der letzten Aktivität',
  PRIMARY KEY (`id`),
  KEY `userid` (`userId`),
  KEY `hash` (`hash`),
  KEY `lastactivity` (`lastActivity`),
  CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Laufende ID',
  `postId` int(10) unsigned NOT NULL COMMENT 'Post-ID (pr0gramm)',
  `tag` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tag',
  `confidence` double(7,6) NOT NULL COMMENT 'Confidence',
  PRIMARY KEY (`id`),
  KEY `postId` (`postId`),
  KEY `confidence` (`confidence`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tag Tabelle';


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Laufende ID',
  `username` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Username',
  `password` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Passwort',
  `salt` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Salt',
  `lastRequest` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP COMMENT 'Letzte Aktivität',
  `requestCount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Anzahl aller Abfragen',
  PRIMARY KEY (`id`),
  KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Account Tabelle';


-- 2022-11-05 11:32:38
