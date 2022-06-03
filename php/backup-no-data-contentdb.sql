-- Adminer 4.3.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `files`;
CREATE TABLE `files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `filename` varchar(250) COLLATE utf8_bin NOT NULL,
  `filesize` int(11) NOT NULL,
  `web_path` varchar(250) COLLATE utf8_bin NOT NULL,
  `system_path` varchar(250) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


DROP TABLE IF EXISTS `matrix`;
CREATE TABLE `matrix` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `author` varchar(255) DEFAULT NULL,
  `pub_date` datetime DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `category` int(11) DEFAULT NULL,
  `mtid` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `model` int(11) DEFAULT NULL,
  `content_url` varchar(255) DEFAULT NULL,
  `a4_url` varchar(255) DEFAULT NULL,
  `image` mediumint(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `matrix_model`;
CREATE TABLE `matrix_model` (
  `matrix_id` mediumint(8) unsigned NOT NULL,
  `model_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`matrix_id`,`model_id`),
  KEY `model_id` (`model_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `matrix_tag`;
CREATE TABLE `matrix_tag` (
  `matrix_id` mediumint(8) unsigned NOT NULL,
  `tag_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`matrix_id`,`tag_id`),
  KEY `tag_id` (`tag_id`),
  CONSTRAINT `matrix_tag_ibfk_1` FOREIGN KEY (`matrix_id`) REFERENCES `matrix` (`id`) ON DELETE CASCADE,
  CONSTRAINT `matrix_tag_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `models`;
CREATE TABLE `models` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `tag`;
CREATE TABLE `tag` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `type`;
CREATE TABLE `type` (
  `id` mediumint(8) unsigned NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- 2022-06-02 13:27:37
