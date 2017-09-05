-- MySQL dump 10.13  Distrib 5.7.19, for Linux (x86_64)
--
-- Host: localhost    Database: UrlShortenerDB
-- ------------------------------------------------------
-- Server version	5.7.19-0ubuntu0.16.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE = @@TIME_ZONE */;
/*!40103 SET TIME_ZONE = '+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS = @@UNIQUE_CHECKS, UNIQUE_CHECKS = 0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS = 0 */;
/*!40101 SET @OLD_SQL_MODE = @@SQL_MODE, SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES = @@SQL_NOTES, SQL_NOTES = 0 */;

--
-- Table structure for table `Users`
--

DROP TABLE IF EXISTS `Users`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Users` (
  `id`       INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`     VARCHAR(32)      NOT NULL,
  `passhash` VARCHAR(60)      NOT NULL,
  `email`    VARCHAR(256)     NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Users_email_uindex` (`email`)
)
  ENGINE = InnoDB
  AUTO_INCREMENT = 12
  DEFAULT CHARSET = latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE = @OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE = @OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS = @OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS = @OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES = @OLD_SQL_NOTES */;

--
-- Table structure for table `Links`
--

DROP TABLE IF EXISTS `Links`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Links` (
  `id`         INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`    INT(10) UNSIGNED NOT NULL,
  `full_link`  VARCHAR(256)     NOT NULL,
  `short_link` VARCHAR(8)                DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `short_link` (`short_link`),
  KEY `idx_user_id` (`user_id`),
  CONSTRAINT `Links_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`id`)
    ON DELETE CASCADE
)
  ENGINE = InnoDB
  AUTO_INCREMENT = 22
  DEFAULT CHARSET = latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Clicks`
--

DROP TABLE IF EXISTS `Clicks`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Clicks` (
  `id`         INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `link_id`    INT(10) UNSIGNED NOT NULL,
  `click_time` TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `referer`    VARCHAR(60)               DEFAULT 'Unknown',
  PRIMARY KEY (`id`),
  KEY `idx_link_id` (`link_id`),
  CONSTRAINT `Clicks_ibfk_1` FOREIGN KEY (`link_id`) REFERENCES `Links` (`id`)
    ON DELETE CASCADE
)
  ENGINE = InnoDB
  AUTO_INCREMENT = 28
  DEFAULT CHARSET = latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

-- Dump completed on 2017-09-05  7:00:41
