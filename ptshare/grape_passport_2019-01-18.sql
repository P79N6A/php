# ************************************************************
# Sequel Pro SQL dump
# Version 5425
#
# https://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.6.29-log)
# Database: grape_passport
# Generation Time: 2019-01-18 06:52:48 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table follower_0
# ------------------------------------------------------------

CREATE TABLE `follower_0` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `idx_uid_id` (`uid`,`id`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_1
# ------------------------------------------------------------

CREATE TABLE `follower_1` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_10
# ------------------------------------------------------------

CREATE TABLE `follower_10` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `follower_10` WRITE;
/*!40000 ALTER TABLE `follower_10` DISABLE KEYS */;

INSERT INTO `follower_10` (`id`, `uid`, `fid`, `notice`, `addtime`)
VALUES
	(3,21000210,21000205,'Y','2018-06-21 19:58:28');

/*!40000 ALTER TABLE `follower_10` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table follower_11
# ------------------------------------------------------------

CREATE TABLE `follower_11` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_12
# ------------------------------------------------------------

CREATE TABLE `follower_12` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_13
# ------------------------------------------------------------

CREATE TABLE `follower_13` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `follower_13` WRITE;
/*!40000 ALTER TABLE `follower_13` DISABLE KEYS */;

INSERT INTO `follower_13` (`id`, `uid`, `fid`, `notice`, `addtime`)
VALUES
	(2,21000213,21000204,'Y','2018-06-15 14:38:53'),
	(4,21000213,21000205,'Y','2018-06-21 20:17:06');

/*!40000 ALTER TABLE `follower_13` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table follower_14
# ------------------------------------------------------------

CREATE TABLE `follower_14` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `follower_14` WRITE;
/*!40000 ALTER TABLE `follower_14` DISABLE KEYS */;

INSERT INTO `follower_14` (`id`, `uid`, `fid`, `notice`, `addtime`)
VALUES
	(1,21000214,21000204,'Y','2018-06-15 14:38:57'),
	(9,21000214,21000205,'Y','2018-06-21 20:17:04');

/*!40000 ALTER TABLE `follower_14` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table follower_15
# ------------------------------------------------------------

CREATE TABLE `follower_15` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`,`id`) USING BTREE,
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `follower_15` WRITE;
/*!40000 ALTER TABLE `follower_15` DISABLE KEYS */;

INSERT INTO `follower_15` (`id`, `uid`, `fid`, `notice`, `addtime`)
VALUES
	(2,21000215,21000204,'Y','2018-06-15 14:39:01'),
	(10,21000215,21000205,'Y','2018-06-23 17:46:13');

/*!40000 ALTER TABLE `follower_15` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table follower_16
# ------------------------------------------------------------

CREATE TABLE `follower_16` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `follower_16` WRITE;
/*!40000 ALTER TABLE `follower_16` DISABLE KEYS */;

INSERT INTO `follower_16` (`id`, `uid`, `fid`, `notice`, `addtime`)
VALUES
	(4,21000216,21000205,'Y','2018-06-22 10:10:35');

/*!40000 ALTER TABLE `follower_16` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table follower_17
# ------------------------------------------------------------

CREATE TABLE `follower_17` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `follower_17` WRITE;
/*!40000 ALTER TABLE `follower_17` DISABLE KEYS */;

INSERT INTO `follower_17` (`id`, `uid`, `fid`, `notice`, `addtime`)
VALUES
	(14,21000217,21000205,'Y','2018-06-23 10:16:45'),
	(15,21000217,21000225,'Y','2018-06-29 19:32:35'),
	(16,21000217,21000203,'Y','2018-06-30 16:07:42'),
	(17,21000217,20000019,'Y','2018-06-30 17:01:43'),
	(19,21000217,21000209,'Y','2018-07-01 09:18:22'),
	(20,21000217,20000023,'Y','2018-07-04 15:24:25'),
	(21,21000217,20000031,'Y','2018-07-06 14:13:11');

/*!40000 ALTER TABLE `follower_17` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table follower_18
# ------------------------------------------------------------

CREATE TABLE `follower_18` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `follower_18` WRITE;
/*!40000 ALTER TABLE `follower_18` DISABLE KEYS */;

INSERT INTO `follower_18` (`id`, `uid`, `fid`, `notice`, `addtime`)
VALUES
	(18,21000218,21000204,'Y','2018-06-21 15:46:49'),
	(28,21000218,21000217,'Y','2018-06-21 20:04:45'),
	(31,21000218,20000019,'Y','2018-06-22 18:17:00'),
	(34,21000218,21000225,'Y','2018-06-22 19:43:06'),
	(48,21000218,21000205,'Y','2018-06-23 10:17:07'),
	(49,21000218,21000202,'Y','2018-06-23 11:18:57'),
	(50,21000218,21000224,'Y','2018-06-23 13:44:15'),
	(51,21000218,20000031,'Y','2018-07-06 14:13:27');

/*!40000 ALTER TABLE `follower_18` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table follower_19
# ------------------------------------------------------------

CREATE TABLE `follower_19` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `follower_19` WRITE;
/*!40000 ALTER TABLE `follower_19` DISABLE KEYS */;

INSERT INTO `follower_19` (`id`, `uid`, `fid`, `notice`, `addtime`)
VALUES
	(7,20000019,21000205,'Y','2018-06-23 10:16:43'),
	(9,20000019,20000031,'Y','2018-07-06 14:13:02');

/*!40000 ALTER TABLE `follower_19` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table follower_2
# ------------------------------------------------------------

CREATE TABLE `follower_2` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `follower_2` WRITE;
/*!40000 ALTER TABLE `follower_2` DISABLE KEYS */;

INSERT INTO `follower_2` (`id`, `uid`, `fid`, `notice`, `addtime`)
VALUES
	(5,21000202,21000205,'Y','2018-06-23 17:41:06'),
	(6,21000202,20000031,'Y','2018-07-06 14:12:59');

/*!40000 ALTER TABLE `follower_2` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table follower_20
# ------------------------------------------------------------

CREATE TABLE `follower_20` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_21
# ------------------------------------------------------------

CREATE TABLE `follower_21` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_22
# ------------------------------------------------------------

CREATE TABLE `follower_22` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_23
# ------------------------------------------------------------

CREATE TABLE `follower_23` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `follower_23` WRITE;
/*!40000 ALTER TABLE `follower_23` DISABLE KEYS */;

INSERT INTO `follower_23` (`id`, `uid`, `fid`, `notice`, `addtime`)
VALUES
	(2,20000023,20000031,'Y','2018-07-19 16:02:53');

/*!40000 ALTER TABLE `follower_23` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table follower_24
# ------------------------------------------------------------

CREATE TABLE `follower_24` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `follower_24` WRITE;
/*!40000 ALTER TABLE `follower_24` DISABLE KEYS */;

INSERT INTO `follower_24` (`id`, `uid`, `fid`, `notice`, `addtime`)
VALUES
	(1,21000224,21000205,'Y','2018-06-23 17:41:07'),
	(3,21000224,20000031,'Y','2018-07-27 15:31:13');

/*!40000 ALTER TABLE `follower_24` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table follower_25
# ------------------------------------------------------------

CREATE TABLE `follower_25` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `follower_25` WRITE;
/*!40000 ALTER TABLE `follower_25` DISABLE KEYS */;

INSERT INTO `follower_25` (`id`, `uid`, `fid`, `notice`, `addtime`)
VALUES
	(11,21000225,21000205,'Y','2018-06-30 11:38:44'),
	(12,21000225,21000203,'Y','2018-06-30 16:07:37'),
	(16,21000225,20000023,'Y','2018-07-04 15:24:23'),
	(17,21000225,20000031,'Y','2018-07-06 14:13:00'),
	(19,21000225,21000216,'Y','2018-07-12 15:21:04');

/*!40000 ALTER TABLE `follower_25` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table follower_26
# ------------------------------------------------------------

CREATE TABLE `follower_26` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_27
# ------------------------------------------------------------

CREATE TABLE `follower_27` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_28
# ------------------------------------------------------------

CREATE TABLE `follower_28` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_29
# ------------------------------------------------------------

CREATE TABLE `follower_29` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_3
# ------------------------------------------------------------

CREATE TABLE `follower_3` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `follower_3` WRITE;
/*!40000 ALTER TABLE `follower_3` DISABLE KEYS */;

INSERT INTO `follower_3` (`id`, `uid`, `fid`, `notice`, `addtime`)
VALUES
	(3,21000203,20000019,'Y','2018-06-30 17:32:35'),
	(5,21000203,21000205,'Y','2018-06-30 18:48:46'),
	(6,21000203,20000031,'Y','2018-07-06 14:14:01');

/*!40000 ALTER TABLE `follower_3` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table follower_30
# ------------------------------------------------------------

CREATE TABLE `follower_30` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_31
# ------------------------------------------------------------

CREATE TABLE `follower_31` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `follower_31` WRITE;
/*!40000 ALTER TABLE `follower_31` DISABLE KEYS */;

INSERT INTO `follower_31` (`id`, `uid`, `fid`, `notice`, `addtime`)
VALUES
	(1,20000031,21000224,'Y','2018-07-12 18:44:28');

/*!40000 ALTER TABLE `follower_31` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table follower_32
# ------------------------------------------------------------

CREATE TABLE `follower_32` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_33
# ------------------------------------------------------------

CREATE TABLE `follower_33` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_34
# ------------------------------------------------------------

CREATE TABLE `follower_34` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_35
# ------------------------------------------------------------

CREATE TABLE `follower_35` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_36
# ------------------------------------------------------------

CREATE TABLE `follower_36` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_37
# ------------------------------------------------------------

CREATE TABLE `follower_37` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_38
# ------------------------------------------------------------

CREATE TABLE `follower_38` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_39
# ------------------------------------------------------------

CREATE TABLE `follower_39` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_4
# ------------------------------------------------------------

CREATE TABLE `follower_4` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `follower_4` WRITE;
/*!40000 ALTER TABLE `follower_4` DISABLE KEYS */;

INSERT INTO `follower_4` (`id`, `uid`, `fid`, `notice`, `addtime`)
VALUES
	(19,21000204,21000206,'Y','2018-06-22 14:40:26'),
	(27,21000204,21000205,'Y','2018-06-23 10:16:59'),
	(28,21000204,21000214,'Y','2018-07-02 16:57:27'),
	(29,21000204,20000031,'Y','2018-07-06 14:13:12');

/*!40000 ALTER TABLE `follower_4` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table follower_40
# ------------------------------------------------------------

CREATE TABLE `follower_40` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_41
# ------------------------------------------------------------

CREATE TABLE `follower_41` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_42
# ------------------------------------------------------------

CREATE TABLE `follower_42` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_43
# ------------------------------------------------------------

CREATE TABLE `follower_43` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_44
# ------------------------------------------------------------

CREATE TABLE `follower_44` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_45
# ------------------------------------------------------------

CREATE TABLE `follower_45` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_46
# ------------------------------------------------------------

CREATE TABLE `follower_46` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_47
# ------------------------------------------------------------

CREATE TABLE `follower_47` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_48
# ------------------------------------------------------------

CREATE TABLE `follower_48` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_49
# ------------------------------------------------------------

CREATE TABLE `follower_49` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_5
# ------------------------------------------------------------

CREATE TABLE `follower_5` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `follower_5` WRITE;
/*!40000 ALTER TABLE `follower_5` DISABLE KEYS */;

INSERT INTO `follower_5` (`id`, `uid`, `fid`, `notice`, `addtime`)
VALUES
	(2,21000205,21000204,'Y','2018-06-15 18:18:25'),
	(35,21000205,21000218,'Y','2018-06-21 11:28:48'),
	(42,21000205,21000203,'Y','2018-06-21 20:02:59'),
	(43,21000205,21000217,'Y','2018-06-21 20:04:37'),
	(44,21000205,21000206,'Y','2018-06-22 14:40:27'),
	(48,21000205,21000202,'Y','2018-06-23 13:47:12'),
	(49,21000205,21000224,'Y','2018-06-23 13:53:40'),
	(50,21000205,21000225,'Y','2018-06-29 14:40:50'),
	(51,21000205,20000019,'Y','2018-06-30 17:01:28'),
	(52,21000205,21000214,'Y','2018-07-02 16:57:28'),
	(53,21000205,20000031,'Y','2018-07-19 16:39:21');

/*!40000 ALTER TABLE `follower_5` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table follower_50
# ------------------------------------------------------------

CREATE TABLE `follower_50` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_51
# ------------------------------------------------------------

CREATE TABLE `follower_51` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_52
# ------------------------------------------------------------

CREATE TABLE `follower_52` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_53
# ------------------------------------------------------------

CREATE TABLE `follower_53` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_54
# ------------------------------------------------------------

CREATE TABLE `follower_54` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_55
# ------------------------------------------------------------

CREATE TABLE `follower_55` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_56
# ------------------------------------------------------------

CREATE TABLE `follower_56` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_57
# ------------------------------------------------------------

CREATE TABLE `follower_57` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_58
# ------------------------------------------------------------

CREATE TABLE `follower_58` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_59
# ------------------------------------------------------------

CREATE TABLE `follower_59` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_6
# ------------------------------------------------------------

CREATE TABLE `follower_6` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `follower_6` WRITE;
/*!40000 ALTER TABLE `follower_6` DISABLE KEYS */;

INSERT INTO `follower_6` (`id`, `uid`, `fid`, `notice`, `addtime`)
VALUES
	(1,21000206,21000204,'Y','2018-06-15 10:46:22'),
	(15,21000206,21000205,'Y','2018-06-22 19:14:15');

/*!40000 ALTER TABLE `follower_6` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table follower_60
# ------------------------------------------------------------

CREATE TABLE `follower_60` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_61
# ------------------------------------------------------------

CREATE TABLE `follower_61` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `idx_uid_id` (`uid`,`id`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_62
# ------------------------------------------------------------

CREATE TABLE `follower_62` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `idx_uid_id` (`uid`,`id`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_63
# ------------------------------------------------------------

CREATE TABLE `follower_63` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `idx_uid_id` (`uid`,`id`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_64
# ------------------------------------------------------------

CREATE TABLE `follower_64` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_65
# ------------------------------------------------------------

CREATE TABLE `follower_65` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_66
# ------------------------------------------------------------

CREATE TABLE `follower_66` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_67
# ------------------------------------------------------------

CREATE TABLE `follower_67` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_68
# ------------------------------------------------------------

CREATE TABLE `follower_68` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_69
# ------------------------------------------------------------

CREATE TABLE `follower_69` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_7
# ------------------------------------------------------------

CREATE TABLE `follower_7` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_70
# ------------------------------------------------------------

CREATE TABLE `follower_70` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_71
# ------------------------------------------------------------

CREATE TABLE `follower_71` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_72
# ------------------------------------------------------------

CREATE TABLE `follower_72` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_73
# ------------------------------------------------------------

CREATE TABLE `follower_73` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_74
# ------------------------------------------------------------

CREATE TABLE `follower_74` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_75
# ------------------------------------------------------------

CREATE TABLE `follower_75` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_76
# ------------------------------------------------------------

CREATE TABLE `follower_76` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_77
# ------------------------------------------------------------

CREATE TABLE `follower_77` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_78
# ------------------------------------------------------------

CREATE TABLE `follower_78` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_79
# ------------------------------------------------------------

CREATE TABLE `follower_79` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_8
# ------------------------------------------------------------

CREATE TABLE `follower_8` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_80
# ------------------------------------------------------------

CREATE TABLE `follower_80` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_81
# ------------------------------------------------------------

CREATE TABLE `follower_81` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_82
# ------------------------------------------------------------

CREATE TABLE `follower_82` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_83
# ------------------------------------------------------------

CREATE TABLE `follower_83` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_84
# ------------------------------------------------------------

CREATE TABLE `follower_84` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_85
# ------------------------------------------------------------

CREATE TABLE `follower_85` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_86
# ------------------------------------------------------------

CREATE TABLE `follower_86` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_87
# ------------------------------------------------------------

CREATE TABLE `follower_87` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_88
# ------------------------------------------------------------

CREATE TABLE `follower_88` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_89
# ------------------------------------------------------------

CREATE TABLE `follower_89` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_9
# ------------------------------------------------------------

CREATE TABLE `follower_9` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `follower_9` WRITE;
/*!40000 ALTER TABLE `follower_9` DISABLE KEYS */;

INSERT INTO `follower_9` (`id`, `uid`, `fid`, `notice`, `addtime`)
VALUES
	(3,21000209,21000204,'Y','2018-06-19 20:55:40'),
	(4,21000209,21000205,'Y','2018-06-21 19:58:27'),
	(5,21000209,21000202,'Y','2018-06-23 13:13:29'),
	(6,21000209,20000019,'Y','2018-06-30 17:32:35'),
	(7,21000209,20000031,'Y','2018-07-06 14:13:25');

/*!40000 ALTER TABLE `follower_9` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table follower_90
# ------------------------------------------------------------

CREATE TABLE `follower_90` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_91
# ------------------------------------------------------------

CREATE TABLE `follower_91` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_92
# ------------------------------------------------------------

CREATE TABLE `follower_92` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_93
# ------------------------------------------------------------

CREATE TABLE `follower_93` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_94
# ------------------------------------------------------------

CREATE TABLE `follower_94` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_95
# ------------------------------------------------------------

CREATE TABLE `follower_95` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_96
# ------------------------------------------------------------

CREATE TABLE `follower_96` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_97
# ------------------------------------------------------------

CREATE TABLE `follower_97` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_98
# ------------------------------------------------------------

CREATE TABLE `follower_98` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_99
# ------------------------------------------------------------

CREATE TABLE `follower_99` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`),
  KEY `uk_uid_notice` (`uid`,`notice`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table follower_robot
# ------------------------------------------------------------

CREATE TABLE `follower_robot` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `num` int(11) NOT NULL DEFAULT '0' COMMENT '添加粉丝总数',
  `expired` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '过期时间',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `adminid` int(11) NOT NULL DEFAULT '0' COMMENT '管理员id',
  `adminname` varchar(20) COLLATE utf8mb4_bin NOT NULL DEFAULT '' COMMENT '管理员名称',
  `deleted` enum('N','Y') COLLATE utf8mb4_bin NOT NULL DEFAULT 'N' COMMENT '是否删除了',
  `modtime` datetime DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin COMMENT='机器人';



# Dump of table follower_robot_log
# ------------------------------------------------------------

CREATE TABLE `follower_robot_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `fid` int(11) NOT NULL DEFAULT '0' COMMENT '机器人粉丝id',
  `addtime` datetime DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin COMMENT='机器人详情';



# Dump of table following_0
# ------------------------------------------------------------

CREATE TABLE `following_0` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_1
# ------------------------------------------------------------

CREATE TABLE `following_1` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_10
# ------------------------------------------------------------

CREATE TABLE `following_10` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_11
# ------------------------------------------------------------

CREATE TABLE `following_11` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_12
# ------------------------------------------------------------

CREATE TABLE `following_12` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_13
# ------------------------------------------------------------

CREATE TABLE `following_13` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_14
# ------------------------------------------------------------

CREATE TABLE `following_14` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `following_14` WRITE;
/*!40000 ALTER TABLE `following_14` DISABLE KEYS */;

INSERT INTO `following_14` (`id`, `uid`, `fid`, `notice`, `friend`, `addtime`)
VALUES
	(1,21000214,21000204,'Y','Y','2018-07-02 16:57:27'),
	(2,21000214,21000205,'Y','Y','2018-07-02 16:57:28');

/*!40000 ALTER TABLE `following_14` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table following_15
# ------------------------------------------------------------

CREATE TABLE `following_15` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_16
# ------------------------------------------------------------

CREATE TABLE `following_16` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `following_16` WRITE;
/*!40000 ALTER TABLE `following_16` DISABLE KEYS */;

INSERT INTO `following_16` (`id`, `uid`, `fid`, `notice`, `friend`, `addtime`)
VALUES
	(1,21000216,21000225,'Y','N','2018-07-12 15:21:04');

/*!40000 ALTER TABLE `following_16` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table following_17
# ------------------------------------------------------------

CREATE TABLE `following_17` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `following_17` WRITE;
/*!40000 ALTER TABLE `following_17` DISABLE KEYS */;

INSERT INTO `following_17` (`id`, `uid`, `fid`, `notice`, `friend`, `addtime`)
VALUES
	(14,21000217,21000205,'Y','Y','2018-06-21 20:04:37'),
	(15,21000217,21000218,'Y','N','2018-06-21 20:04:45');

/*!40000 ALTER TABLE `following_17` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table following_18
# ------------------------------------------------------------

CREATE TABLE `following_18` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `following_18` WRITE;
/*!40000 ALTER TABLE `following_18` DISABLE KEYS */;

INSERT INTO `following_18` (`id`, `uid`, `fid`, `notice`, `friend`, `addtime`)
VALUES
	(17,21000218,21000205,'Y','Y','2018-06-21 11:28:48');

/*!40000 ALTER TABLE `following_18` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table following_19
# ------------------------------------------------------------

CREATE TABLE `following_19` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `following_19` WRITE;
/*!40000 ALTER TABLE `following_19` DISABLE KEYS */;

INSERT INTO `following_19` (`id`, `uid`, `fid`, `notice`, `friend`, `addtime`)
VALUES
	(2,20000019,21000218,'Y','N','2018-06-22 18:17:00'),
	(9,20000019,21000205,'Y','Y','2018-06-30 17:01:28'),
	(10,20000019,21000217,'Y','N','2018-06-30 17:01:43'),
	(13,20000019,21000209,'Y','N','2018-06-30 17:32:35'),
	(14,20000019,21000203,'Y','N','2018-06-30 17:32:35');

/*!40000 ALTER TABLE `following_19` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table following_2
# ------------------------------------------------------------

CREATE TABLE `following_2` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `following_2` WRITE;
/*!40000 ALTER TABLE `following_2` DISABLE KEYS */;

INSERT INTO `following_2` (`id`, `uid`, `fid`, `notice`, `friend`, `addtime`)
VALUES
	(4,21000202,21000218,'Y','N','2018-06-23 11:18:57'),
	(5,21000202,21000209,'Y','N','2018-06-23 13:13:29'),
	(6,21000202,21000205,'Y','Y','2018-06-23 13:47:12');

/*!40000 ALTER TABLE `following_2` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table following_20
# ------------------------------------------------------------

CREATE TABLE `following_20` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_21
# ------------------------------------------------------------

CREATE TABLE `following_21` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_22
# ------------------------------------------------------------

CREATE TABLE `following_22` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_23
# ------------------------------------------------------------

CREATE TABLE `following_23` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `following_23` WRITE;
/*!40000 ALTER TABLE `following_23` DISABLE KEYS */;

INSERT INTO `following_23` (`id`, `uid`, `fid`, `notice`, `friend`, `addtime`)
VALUES
	(1,20000023,21000225,'Y','N','2018-07-04 15:24:23'),
	(2,20000023,21000217,'Y','N','2018-07-04 15:24:25');

/*!40000 ALTER TABLE `following_23` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table following_24
# ------------------------------------------------------------

CREATE TABLE `following_24` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `following_24` WRITE;
/*!40000 ALTER TABLE `following_24` DISABLE KEYS */;

INSERT INTO `following_24` (`id`, `uid`, `fid`, `notice`, `friend`, `addtime`)
VALUES
	(5,21000224,21000218,'Y','N','2018-06-23 13:44:15'),
	(6,21000224,21000205,'Y','Y','2018-06-23 13:53:40'),
	(9,21000224,20000031,'Y','Y','2018-07-12 18:44:28');

/*!40000 ALTER TABLE `following_24` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table following_25
# ------------------------------------------------------------

CREATE TABLE `following_25` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `following_25` WRITE;
/*!40000 ALTER TABLE `following_25` DISABLE KEYS */;

INSERT INTO `following_25` (`id`, `uid`, `fid`, `notice`, `friend`, `addtime`)
VALUES
	(21,21000225,21000218,'Y','N','2018-06-22 19:43:06'),
	(24,21000225,21000205,'Y','Y','2018-06-29 14:40:50'),
	(25,21000225,21000217,'Y','N','2018-06-29 19:32:35');

/*!40000 ALTER TABLE `following_25` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table following_26
# ------------------------------------------------------------

CREATE TABLE `following_26` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_27
# ------------------------------------------------------------

CREATE TABLE `following_27` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_28
# ------------------------------------------------------------

CREATE TABLE `following_28` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_29
# ------------------------------------------------------------

CREATE TABLE `following_29` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_3
# ------------------------------------------------------------

CREATE TABLE `following_3` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `following_3` WRITE;
/*!40000 ALTER TABLE `following_3` DISABLE KEYS */;

INSERT INTO `following_3` (`id`, `uid`, `fid`, `notice`, `friend`, `addtime`)
VALUES
	(4,21000203,21000205,'Y','Y','2018-06-21 20:02:59'),
	(5,21000203,21000225,'Y','N','2018-06-30 16:07:37'),
	(6,21000203,21000217,'Y','N','2018-06-30 16:07:42');

/*!40000 ALTER TABLE `following_3` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table following_30
# ------------------------------------------------------------

CREATE TABLE `following_30` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_31
# ------------------------------------------------------------

CREATE TABLE `following_31` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `following_31` WRITE;
/*!40000 ALTER TABLE `following_31` DISABLE KEYS */;

INSERT INTO `following_31` (`id`, `uid`, `fid`, `notice`, `friend`, `addtime`)
VALUES
	(2,20000031,21000202,'Y','N','2018-07-06 14:12:59'),
	(3,20000031,21000225,'Y','N','2018-07-06 14:13:00'),
	(4,20000031,20000019,'Y','N','2018-07-06 14:13:02'),
	(5,20000031,21000217,'Y','N','2018-07-06 14:13:11'),
	(6,20000031,21000204,'Y','N','2018-07-06 14:13:12'),
	(7,20000031,21000209,'Y','N','2018-07-06 14:13:25'),
	(8,20000031,21000218,'Y','N','2018-07-06 14:13:27'),
	(9,20000031,21000203,'Y','N','2018-07-06 14:14:01'),
	(11,20000031,20000023,'Y','N','2018-07-19 16:02:53'),
	(12,20000031,21000205,'Y','N','2018-07-19 16:39:21'),
	(13,20000031,21000224,'Y','Y','2018-07-27 15:31:13');

/*!40000 ALTER TABLE `following_31` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table following_32
# ------------------------------------------------------------

CREATE TABLE `following_32` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_33
# ------------------------------------------------------------

CREATE TABLE `following_33` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_34
# ------------------------------------------------------------

CREATE TABLE `following_34` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_35
# ------------------------------------------------------------

CREATE TABLE `following_35` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_36
# ------------------------------------------------------------

CREATE TABLE `following_36` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_37
# ------------------------------------------------------------

CREATE TABLE `following_37` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_38
# ------------------------------------------------------------

CREATE TABLE `following_38` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_39
# ------------------------------------------------------------

CREATE TABLE `following_39` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_4
# ------------------------------------------------------------

CREATE TABLE `following_4` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `following_4` WRITE;
/*!40000 ALTER TABLE `following_4` DISABLE KEYS */;

INSERT INTO `following_4` (`id`, `uid`, `fid`, `notice`, `friend`, `addtime`)
VALUES
	(2,21000204,21000206,'Y','Y','2018-06-15 10:46:22'),
	(3,21000204,21000213,'Y','N','2018-06-15 14:38:53'),
	(4,21000204,21000214,'Y','Y','2018-06-15 14:38:57'),
	(5,21000204,21000215,'Y','N','2018-06-15 14:39:01'),
	(6,21000204,21000205,'Y','Y','2018-06-15 18:18:25'),
	(7,21000204,21000209,'Y','N','2018-06-19 20:55:40'),
	(8,21000204,21000218,'Y','N','2018-06-21 15:46:49');

/*!40000 ALTER TABLE `following_4` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table following_40
# ------------------------------------------------------------

CREATE TABLE `following_40` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_41
# ------------------------------------------------------------

CREATE TABLE `following_41` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_42
# ------------------------------------------------------------

CREATE TABLE `following_42` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_43
# ------------------------------------------------------------

CREATE TABLE `following_43` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_44
# ------------------------------------------------------------

CREATE TABLE `following_44` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_45
# ------------------------------------------------------------

CREATE TABLE `following_45` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_46
# ------------------------------------------------------------

CREATE TABLE `following_46` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_47
# ------------------------------------------------------------

CREATE TABLE `following_47` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_48
# ------------------------------------------------------------

CREATE TABLE `following_48` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_49
# ------------------------------------------------------------

CREATE TABLE `following_49` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_5
# ------------------------------------------------------------

CREATE TABLE `following_5` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `following_5` WRITE;
/*!40000 ALTER TABLE `following_5` DISABLE KEYS */;

INSERT INTO `following_5` (`id`, `uid`, `fid`, `notice`, `friend`, `addtime`)
VALUES
	(74,21000205,21000209,'Y','N','2018-06-21 19:58:27'),
	(75,21000205,21000210,'Y','N','2018-06-21 19:58:28'),
	(80,21000205,21000214,'Y','Y','2018-06-21 20:17:04'),
	(81,21000205,21000213,'Y','N','2018-06-21 20:17:06'),
	(87,21000205,21000216,'Y','N','2018-06-22 10:10:35'),
	(94,21000205,21000206,'Y','Y','2018-06-22 19:14:15'),
	(126,21000205,20000019,'Y','Y','2018-06-23 10:16:43'),
	(127,21000205,21000217,'Y','Y','2018-06-23 10:16:45'),
	(128,21000205,21000204,'Y','Y','2018-06-23 10:16:59'),
	(130,21000205,21000218,'Y','Y','2018-06-23 10:17:07'),
	(131,21000205,21000202,'Y','Y','2018-06-23 17:41:06'),
	(132,21000205,21000224,'Y','Y','2018-06-23 17:41:07'),
	(133,21000205,21000215,'Y','N','2018-06-23 17:46:13'),
	(134,21000205,21000225,'Y','Y','2018-06-30 11:38:44'),
	(135,21000205,21000203,'Y','Y','2018-06-30 18:48:46');

/*!40000 ALTER TABLE `following_5` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table following_50
# ------------------------------------------------------------

CREATE TABLE `following_50` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_51
# ------------------------------------------------------------

CREATE TABLE `following_51` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_52
# ------------------------------------------------------------

CREATE TABLE `following_52` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_53
# ------------------------------------------------------------

CREATE TABLE `following_53` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_54
# ------------------------------------------------------------

CREATE TABLE `following_54` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_55
# ------------------------------------------------------------

CREATE TABLE `following_55` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_56
# ------------------------------------------------------------

CREATE TABLE `following_56` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_57
# ------------------------------------------------------------

CREATE TABLE `following_57` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_58
# ------------------------------------------------------------

CREATE TABLE `following_58` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_59
# ------------------------------------------------------------

CREATE TABLE `following_59` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_6
# ------------------------------------------------------------

CREATE TABLE `following_6` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `following_6` WRITE;
/*!40000 ALTER TABLE `following_6` DISABLE KEYS */;

INSERT INTO `following_6` (`id`, `uid`, `fid`, `notice`, `friend`, `addtime`)
VALUES
	(1,21000206,21000204,'Y','Y','2018-06-22 14:40:26'),
	(2,21000206,21000205,'Y','Y','2018-06-22 14:40:27');

/*!40000 ALTER TABLE `following_6` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table following_60
# ------------------------------------------------------------

CREATE TABLE `following_60` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_61
# ------------------------------------------------------------

CREATE TABLE `following_61` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_62
# ------------------------------------------------------------

CREATE TABLE `following_62` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_63
# ------------------------------------------------------------

CREATE TABLE `following_63` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_64
# ------------------------------------------------------------

CREATE TABLE `following_64` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_65
# ------------------------------------------------------------

CREATE TABLE `following_65` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_66
# ------------------------------------------------------------

CREATE TABLE `following_66` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_67
# ------------------------------------------------------------

CREATE TABLE `following_67` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_68
# ------------------------------------------------------------

CREATE TABLE `following_68` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_69
# ------------------------------------------------------------

CREATE TABLE `following_69` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_7
# ------------------------------------------------------------

CREATE TABLE `following_7` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_70
# ------------------------------------------------------------

CREATE TABLE `following_70` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_71
# ------------------------------------------------------------

CREATE TABLE `following_71` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_72
# ------------------------------------------------------------

CREATE TABLE `following_72` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_73
# ------------------------------------------------------------

CREATE TABLE `following_73` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_74
# ------------------------------------------------------------

CREATE TABLE `following_74` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_75
# ------------------------------------------------------------

CREATE TABLE `following_75` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_76
# ------------------------------------------------------------

CREATE TABLE `following_76` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_77
# ------------------------------------------------------------

CREATE TABLE `following_77` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_78
# ------------------------------------------------------------

CREATE TABLE `following_78` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_79
# ------------------------------------------------------------

CREATE TABLE `following_79` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_8
# ------------------------------------------------------------

CREATE TABLE `following_8` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_80
# ------------------------------------------------------------

CREATE TABLE `following_80` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_81
# ------------------------------------------------------------

CREATE TABLE `following_81` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_82
# ------------------------------------------------------------

CREATE TABLE `following_82` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_83
# ------------------------------------------------------------

CREATE TABLE `following_83` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_84
# ------------------------------------------------------------

CREATE TABLE `following_84` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_85
# ------------------------------------------------------------

CREATE TABLE `following_85` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_86
# ------------------------------------------------------------

CREATE TABLE `following_86` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_87
# ------------------------------------------------------------

CREATE TABLE `following_87` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_88
# ------------------------------------------------------------

CREATE TABLE `following_88` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_89
# ------------------------------------------------------------

CREATE TABLE `following_89` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_9
# ------------------------------------------------------------

CREATE TABLE `following_9` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `following_9` WRITE;
/*!40000 ALTER TABLE `following_9` DISABLE KEYS */;

INSERT INTO `following_9` (`id`, `uid`, `fid`, `notice`, `friend`, `addtime`)
VALUES
	(6,21000209,21000217,'Y','N','2018-07-01 09:18:22');

/*!40000 ALTER TABLE `following_9` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table following_90
# ------------------------------------------------------------

CREATE TABLE `following_90` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_91
# ------------------------------------------------------------

CREATE TABLE `following_91` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_92
# ------------------------------------------------------------

CREATE TABLE `following_92` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_93
# ------------------------------------------------------------

CREATE TABLE `following_93` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_94
# ------------------------------------------------------------

CREATE TABLE `following_94` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_95
# ------------------------------------------------------------

CREATE TABLE `following_95` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_96
# ------------------------------------------------------------

CREATE TABLE `following_96` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_97
# ------------------------------------------------------------

CREATE TABLE `following_97` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_98
# ------------------------------------------------------------

CREATE TABLE `following_98` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table following_99
# ------------------------------------------------------------

CREATE TABLE `following_99` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `notice` enum('Y','N') NOT NULL DEFAULT 'Y',
  `friend` enum('Y','N') NOT NULL DEFAULT 'N',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_fid` (`uid`,`fid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table followlog
# ------------------------------------------------------------

CREATE TABLE `followlog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `fid` int(10) unsigned NOT NULL DEFAULT '0',
  `action` enum('ADD','CANCEL') NOT NULL DEFAULT 'ADD',
  `reason` varchar(50) NOT NULL DEFAULT '',
  `amount` int(10) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`),
  KEY `idx_fid` (`fid`),
  KEY `idx_addtime` (`addtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `followlog` WRITE;
/*!40000 ALTER TABLE `followlog` DISABLE KEYS */;

INSERT INTO `followlog` (`id`, `uid`, `fid`, `action`, `reason`, `amount`, `addtime`)
VALUES
	(1,21000204,21000205,'ADD','',0,'2018-06-15 10:45:17'),
	(2,21000204,21000206,'ADD','',0,'2018-06-15 10:46:22'),
	(3,21000205,21000225,'ADD','',0,'2018-06-15 11:25:15'),
	(4,21000205,21000206,'ADD','',0,'2018-06-15 11:25:15'),
	(5,21000205,21000208,'ADD','',0,'2018-06-15 11:29:48'),
	(6,21000205,21000207,'ADD','',0,'2018-06-15 11:29:48'),
	(7,21000205,21000209,'ADD','',0,'2018-06-15 11:31:03'),
	(8,21000205,21000210,'ADD','',0,'2018-06-15 11:31:03'),
	(9,21000205,21000208,'CANCEL','',0,'2018-06-15 13:54:39'),
	(10,21000205,21000207,'CANCEL','',0,'2018-06-15 13:59:22'),
	(11,21000205,21000209,'CANCEL','',0,'2018-06-15 14:03:48'),
	(12,21000205,21000206,'CANCEL','',0,'2018-06-15 14:05:22'),
	(13,21000205,21000210,'CANCEL','',0,'2018-06-15 14:05:24'),
	(14,21000205,21000225,'CANCEL','',0,'2018-06-15 14:05:26'),
	(15,21000205,21000206,'ADD','',0,'2018-06-15 14:16:27'),
	(16,21000205,21000225,'ADD','',0,'2018-06-15 14:16:27'),
	(17,21000205,21000207,'ADD','',0,'2018-06-15 14:16:27'),
	(18,21000205,21000208,'ADD','',0,'2018-06-15 14:16:27'),
	(19,21000205,21000209,'ADD','',0,'2018-06-15 14:16:53'),
	(20,21000205,21000210,'ADD','',0,'2018-06-15 14:16:53'),
	(21,21000205,21000212,'ADD','',0,'2018-06-15 14:16:53'),
	(22,21000205,21000211,'ADD','',0,'2018-06-15 14:16:53'),
	(23,21000205,21000217,'ADD','',0,'2018-06-15 14:37:17'),
	(24,21000205,21000216,'ADD','',0,'2018-06-15 14:37:17'),
	(25,21000205,21000213,'ADD','',0,'2018-06-15 14:37:17'),
	(26,21000205,21000215,'ADD','',0,'2018-06-15 14:37:17'),
	(27,21000204,21000213,'ADD','',0,'2018-06-15 14:38:53'),
	(28,21000204,21000214,'ADD','',0,'2018-06-15 14:38:57'),
	(29,21000204,21000215,'ADD','',0,'2018-06-15 14:39:01'),
	(30,21000205,21000223,'ADD','',0,'2018-06-15 14:39:03'),
	(31,21000205,21000207,'CANCEL','',0,'2018-06-15 14:47:05'),
	(32,21000205,21000211,'CANCEL','',0,'2018-06-15 14:47:09'),
	(33,21000205,21000208,'CANCEL','',0,'2018-06-15 14:47:13'),
	(34,21000205,21000204,'ADD','',0,'2018-06-15 14:47:41'),
	(35,21000205,21000204,'CANCEL','',0,'2018-06-15 14:49:20'),
	(36,21000205,21000204,'ADD','',0,'2018-06-15 14:49:36'),
	(37,21000205,21000204,'CANCEL','',0,'2018-06-15 14:49:38'),
	(38,21000205,21000204,'ADD','',0,'2018-06-15 14:49:39'),
	(39,21000205,21000204,'CANCEL','',0,'2018-06-15 14:49:40'),
	(40,21000205,21000204,'ADD','',0,'2018-06-15 15:00:10'),
	(41,21000205,21000204,'CANCEL','',0,'2018-06-15 15:00:11'),
	(42,21000205,21000204,'ADD','',0,'2018-06-15 15:00:12'),
	(43,21000205,21000204,'CANCEL','',0,'2018-06-15 15:00:14'),
	(44,21000205,21000204,'ADD','',0,'2018-06-15 15:00:14'),
	(45,21000205,21000204,'CANCEL','',0,'2018-06-15 15:00:15'),
	(46,21000205,21000204,'ADD','',0,'2018-06-15 15:00:16'),
	(47,21000205,21000204,'CANCEL','',0,'2018-06-15 15:00:16'),
	(48,21000205,21000204,'ADD','',0,'2018-06-15 15:00:17'),
	(49,21000205,21000204,'CANCEL','',0,'2018-06-15 15:00:57'),
	(50,21000205,21000204,'ADD','',0,'2018-06-15 15:01:43'),
	(51,21000205,21000204,'CANCEL','',0,'2018-06-15 15:01:47'),
	(52,21000205,21000204,'ADD','',0,'2018-06-15 15:02:02'),
	(53,21000205,21000204,'CANCEL','',0,'2018-06-15 15:02:05'),
	(54,21000205,21000204,'ADD','',0,'2018-06-15 15:02:11'),
	(55,21000205,21000204,'CANCEL','',0,'2018-06-15 15:03:23'),
	(56,21000205,21000204,'ADD','',0,'2018-06-15 15:03:26'),
	(57,21000205,21000204,'CANCEL','',0,'2018-06-15 15:04:09'),
	(58,21000205,21000204,'ADD','',0,'2018-06-15 15:04:10'),
	(59,21000205,21000204,'CANCEL','',0,'2018-06-15 15:04:12'),
	(60,21000205,21000204,'ADD','',0,'2018-06-15 15:04:17'),
	(61,21000205,21000204,'CANCEL','',0,'2018-06-15 15:04:18'),
	(62,21000205,21000204,'ADD','',0,'2018-06-15 15:04:59'),
	(63,21000205,21000204,'CANCEL','',0,'2018-06-15 15:05:00'),
	(64,21000205,21000214,'ADD','',0,'2018-06-15 15:14:08'),
	(65,21000205,21000214,'CANCEL','',0,'2018-06-15 15:14:42'),
	(66,21000205,21000214,'ADD','',0,'2018-06-15 15:14:45'),
	(67,21000205,21000214,'CANCEL','',0,'2018-06-15 15:15:51'),
	(68,21000205,21000213,'CANCEL','',0,'2018-06-15 15:15:53'),
	(69,21000205,21000206,'CANCEL','',0,'2018-06-15 15:15:54'),
	(70,21000205,21000215,'CANCEL','',0,'2018-06-15 15:16:05'),
	(71,21000205,21000215,'ADD','',0,'2018-06-15 15:16:06'),
	(72,21000205,21000215,'CANCEL','',0,'2018-06-15 15:16:57'),
	(73,21000205,21000215,'ADD','',0,'2018-06-15 15:18:04'),
	(74,21000205,21000215,'CANCEL','',0,'2018-06-15 15:18:34'),
	(75,21000205,21000215,'ADD','',0,'2018-06-15 15:18:45'),
	(76,21000205,21000214,'ADD','',0,'2018-06-15 15:18:50'),
	(77,21000205,21000214,'CANCEL','',0,'2018-06-15 15:19:48'),
	(78,21000205,21000214,'ADD','',0,'2018-06-15 15:19:53'),
	(79,21000205,21000214,'CANCEL','',0,'2018-06-15 15:19:59'),
	(80,21000205,21000214,'ADD','',0,'2018-06-15 15:20:01'),
	(81,21000205,21000215,'CANCEL','',0,'2018-06-15 15:20:09'),
	(82,21000205,21000215,'ADD','',0,'2018-06-15 15:20:10'),
	(83,21000205,21000213,'ADD','',0,'2018-06-15 15:20:18'),
	(84,21000205,21000206,'ADD','',0,'2018-06-15 15:21:26'),
	(85,21000205,21000214,'CANCEL','',0,'2018-06-15 15:23:44'),
	(86,21000205,21000213,'CANCEL','',0,'2018-06-15 15:23:45'),
	(87,21000205,21000214,'ADD','',0,'2018-06-15 15:23:46'),
	(88,21000205,21000206,'CANCEL','',0,'2018-06-15 15:23:48'),
	(89,21000205,21000215,'CANCEL','',0,'2018-06-15 15:23:50'),
	(90,21000205,21000214,'CANCEL','',0,'2018-06-15 15:23:53'),
	(91,21000205,21000214,'ADD','',0,'2018-06-15 15:23:54'),
	(92,21000205,21000215,'ADD','',0,'2018-06-15 15:24:25'),
	(93,21000205,21000214,'CANCEL','',0,'2018-06-15 15:24:27'),
	(94,21000205,21000206,'ADD','',0,'2018-06-15 15:53:19'),
	(95,21000205,21000206,'CANCEL','',0,'2018-06-15 15:53:21'),
	(96,21000205,21000206,'ADD','',0,'2018-06-15 16:03:20'),
	(97,21000205,21000204,'ADD','',0,'2018-06-15 16:03:31'),
	(98,21000204,21000205,'CANCEL','',0,'2018-06-15 17:35:22'),
	(99,21000205,21000212,'CANCEL','',0,'2018-06-15 17:51:27'),
	(100,21000204,21000205,'ADD','',0,'2018-06-15 18:18:25'),
	(101,21000204,21000209,'ADD','',0,'2018-06-19 20:55:40'),
	(102,21000225,21000205,'ADD','',0,'2018-06-20 16:16:30'),
	(103,21000225,21000205,'CANCEL','',0,'2018-06-20 16:51:59'),
	(104,21000225,21000205,'ADD','',0,'2018-06-20 16:52:48'),
	(105,21000225,21000205,'CANCEL','',0,'2018-06-20 16:54:11'),
	(106,21000225,21000205,'ADD','',0,'2018-06-20 16:55:06'),
	(107,21000225,21000205,'CANCEL','',0,'2018-06-20 16:55:07'),
	(108,21000225,21000205,'ADD','',0,'2018-06-20 17:23:36'),
	(109,21000225,21000205,'CANCEL','',0,'2018-06-20 17:23:37'),
	(110,21000225,21000205,'ADD','',0,'2018-06-20 17:23:40'),
	(111,21000225,21000205,'CANCEL','',0,'2018-06-20 17:23:41'),
	(112,21000225,21000218,'ADD','',0,'2018-06-20 18:13:46'),
	(113,21000225,21000218,'CANCEL','',0,'2018-06-20 18:13:50'),
	(114,21000218,21000205,'ADD','',0,'2018-06-20 19:24:05'),
	(115,21000225,21000205,'ADD','',0,'2018-06-20 20:22:35'),
	(116,21000225,21000205,'CANCEL','',0,'2018-06-20 20:22:42'),
	(117,21000225,21000205,'ADD','',0,'2018-06-20 20:22:44'),
	(118,21000225,21000205,'CANCEL','',0,'2018-06-20 20:22:45'),
	(119,21000225,21000205,'ADD','',0,'2018-06-20 20:22:46'),
	(120,21000225,21000205,'CANCEL','',0,'2018-06-20 20:22:47'),
	(121,21000225,21000205,'ADD','',0,'2018-06-20 20:22:47'),
	(122,21000225,21000205,'CANCEL','',0,'2018-06-20 20:22:48'),
	(123,21000225,21000205,'ADD','',0,'2018-06-20 20:22:49'),
	(124,21000225,21000205,'CANCEL','',0,'2018-06-20 20:22:51'),
	(125,21000225,21000205,'ADD','',0,'2018-06-20 20:22:52'),
	(126,21000225,21000205,'CANCEL','',0,'2018-06-20 20:22:53'),
	(127,21000225,21000205,'ADD','',0,'2018-06-20 20:22:54'),
	(128,21000225,21000205,'CANCEL','',0,'2018-06-20 20:22:55'),
	(129,21000225,21000218,'ADD','',0,'2018-06-20 20:24:17'),
	(130,21000225,21000218,'CANCEL','',0,'2018-06-20 20:24:19'),
	(131,21000225,21000218,'ADD','',0,'2018-06-20 20:24:20'),
	(132,21000225,21000218,'CANCEL','',0,'2018-06-20 20:24:21'),
	(133,21000225,21000218,'ADD','',0,'2018-06-20 20:24:22'),
	(134,21000225,21000218,'CANCEL','',0,'2018-06-20 20:24:22'),
	(135,21000225,21000205,'ADD','',0,'2018-06-20 20:26:03'),
	(136,21000225,21000205,'CANCEL','',0,'2018-06-20 20:26:05'),
	(137,21000225,21000205,'ADD','',0,'2018-06-20 20:26:06'),
	(138,21000225,21000205,'CANCEL','',0,'2018-06-20 20:26:07'),
	(139,21000225,21000205,'ADD','',0,'2018-06-20 20:27:23'),
	(140,21000225,21000205,'CANCEL','',0,'2018-06-20 20:27:24'),
	(141,21000217,21000205,'ADD','',0,'2018-06-21 11:26:26'),
	(142,21000217,21000205,'CANCEL','',0,'2018-06-21 11:26:27'),
	(143,21000218,21000205,'CANCEL','',0,'2018-06-21 11:28:11'),
	(144,21000218,21000205,'ADD','',0,'2018-06-21 11:28:35'),
	(145,21000218,21000205,'CANCEL','',0,'2018-06-21 11:28:36'),
	(146,21000218,21000205,'ADD','',0,'2018-06-21 11:28:37'),
	(147,21000218,21000205,'CANCEL','',0,'2018-06-21 11:28:38'),
	(148,21000218,21000205,'ADD','',0,'2018-06-21 11:28:39'),
	(149,21000218,21000205,'CANCEL','',0,'2018-06-21 11:28:40'),
	(150,21000218,21000205,'ADD','',0,'2018-06-21 11:28:41'),
	(151,21000218,21000205,'CANCEL','',0,'2018-06-21 11:28:41'),
	(152,21000218,21000205,'ADD','',0,'2018-06-21 11:28:43'),
	(153,21000218,21000205,'CANCEL','',0,'2018-06-21 11:28:43'),
	(154,21000218,21000205,'ADD','',0,'2018-06-21 11:28:44'),
	(155,21000218,21000205,'CANCEL','',0,'2018-06-21 11:28:44'),
	(156,21000218,21000205,'ADD','',0,'2018-06-21 11:28:44'),
	(157,21000218,21000205,'CANCEL','',0,'2018-06-21 11:28:44'),
	(158,21000218,21000205,'ADD','',0,'2018-06-21 11:28:44'),
	(159,21000218,21000205,'CANCEL','',0,'2018-06-21 11:28:44'),
	(160,21000218,21000205,'ADD','',0,'2018-06-21 11:28:44'),
	(161,21000218,21000205,'CANCEL','',0,'2018-06-21 11:28:45'),
	(162,21000218,21000205,'ADD','',0,'2018-06-21 11:28:45'),
	(163,21000218,21000205,'CANCEL','',0,'2018-06-21 11:28:45'),
	(164,21000218,21000205,'ADD','',0,'2018-06-21 11:28:45'),
	(165,21000218,21000205,'CANCEL','',0,'2018-06-21 11:28:45'),
	(166,21000218,21000205,'ADD','',0,'2018-06-21 11:28:45'),
	(167,21000218,21000205,'CANCEL','',0,'2018-06-21 11:28:46'),
	(168,21000218,21000205,'ADD','',0,'2018-06-21 11:28:46'),
	(169,21000218,21000205,'CANCEL','',0,'2018-06-21 11:28:46'),
	(170,21000218,21000205,'ADD','',0,'2018-06-21 11:28:47'),
	(171,21000218,21000205,'CANCEL','',0,'2018-06-21 11:28:47'),
	(172,21000218,21000205,'ADD','',0,'2018-06-21 11:28:47'),
	(173,21000218,21000205,'CANCEL','',0,'2018-06-21 11:28:47'),
	(174,21000218,21000205,'ADD','',0,'2018-06-21 11:28:48'),
	(175,21000205,21000218,'ADD','',0,'2018-06-21 14:02:39'),
	(176,21000205,21000218,'CANCEL','',0,'2018-06-21 14:02:40'),
	(177,21000205,21000218,'ADD','',0,'2018-06-21 14:02:42'),
	(178,21000205,21000218,'CANCEL','',0,'2018-06-21 14:05:52'),
	(179,21000205,21000218,'ADD','',0,'2018-06-21 14:05:54'),
	(180,21000205,21000218,'CANCEL','',0,'2018-06-21 14:05:55'),
	(181,21000205,21000218,'ADD','',0,'2018-06-21 14:05:56'),
	(182,21000205,21000218,'CANCEL','',0,'2018-06-21 14:06:00'),
	(183,21000205,21000218,'ADD','',0,'2018-06-21 14:06:06'),
	(184,21000205,21000218,'CANCEL','',0,'2018-06-21 14:06:07'),
	(185,21000205,21000218,'ADD','',0,'2018-06-21 14:06:10'),
	(186,21000205,21000218,'CANCEL','',0,'2018-06-21 14:06:14'),
	(187,21000205,21000218,'ADD','',0,'2018-06-21 14:06:15'),
	(188,21000205,21000218,'CANCEL','',0,'2018-06-21 14:06:20'),
	(189,21000205,21000206,'CANCEL','',0,'2018-06-21 14:07:27'),
	(190,21000205,21000206,'ADD','',0,'2018-06-21 14:07:32'),
	(191,21000205,21000206,'CANCEL','',0,'2018-06-21 14:08:06'),
	(192,21000205,21000206,'ADD','',0,'2018-06-21 14:08:07'),
	(193,21000205,21000206,'CANCEL','',0,'2018-06-21 14:08:08'),
	(194,21000205,21000206,'ADD','',0,'2018-06-21 14:08:34'),
	(195,21000205,21000206,'CANCEL','',0,'2018-06-21 14:08:36'),
	(196,21000205,21000206,'ADD','',0,'2018-06-21 14:08:37'),
	(197,21000205,21000218,'ADD','',0,'2018-06-21 14:09:59'),
	(198,21000225,21000218,'ADD','',0,'2018-06-21 14:25:58'),
	(199,21000225,21000218,'CANCEL','',0,'2018-06-21 14:26:02'),
	(200,21000203,21000218,'ADD','',0,'2018-06-21 14:36:35'),
	(201,21000203,21000218,'CANCEL','',0,'2018-06-21 14:37:12'),
	(202,21000217,21000218,'ADD','',0,'2018-06-21 14:58:01'),
	(203,21000217,21000218,'CANCEL','',0,'2018-06-21 14:58:03'),
	(204,21000217,21000218,'ADD','',0,'2018-06-21 14:58:04'),
	(205,21000205,21000218,'CANCEL','',0,'2018-06-21 15:12:14'),
	(206,21000205,21000218,'ADD','',0,'2018-06-21 15:12:15'),
	(207,21000205,21000218,'CANCEL','',0,'2018-06-21 15:37:30'),
	(208,21000204,21000218,'ADD','',0,'2018-06-21 15:46:49'),
	(209,21000217,21000218,'CANCEL','',0,'2018-06-21 15:53:08'),
	(210,21000217,21000205,'ADD','',0,'2018-06-21 15:53:23'),
	(211,21000217,21000218,'ADD','',0,'2018-06-21 16:00:00'),
	(212,21000217,21000218,'CANCEL','',0,'2018-06-21 16:00:55'),
	(213,21000217,21000218,'ADD','',0,'2018-06-21 16:00:58'),
	(214,21000217,21000218,'CANCEL','',0,'2018-06-21 16:01:26'),
	(215,21000217,21000205,'CANCEL','',0,'2018-06-21 16:02:47'),
	(216,21000217,21000205,'ADD','',0,'2018-06-21 16:02:54'),
	(217,21000217,21000205,'CANCEL','',0,'2018-06-21 16:02:59'),
	(218,21000217,21000205,'ADD','',0,'2018-06-21 16:03:19'),
	(219,21000217,21000205,'CANCEL','',0,'2018-06-21 16:03:20'),
	(220,21000217,21000218,'ADD','',0,'2018-06-21 16:03:37'),
	(221,21000217,21000218,'CANCEL','',0,'2018-06-21 16:03:47'),
	(222,21000217,21000218,'ADD','',0,'2018-06-21 16:07:22'),
	(223,21000203,21000205,'ADD','',0,'2018-06-21 16:10:10'),
	(224,21000217,21000218,'CANCEL','',0,'2018-06-21 16:54:19'),
	(225,21000217,21000218,'ADD','',0,'2018-06-21 16:54:20'),
	(226,21000217,21000205,'ADD','',0,'2018-06-21 17:03:37'),
	(227,21000217,21000205,'CANCEL','',0,'2018-06-21 17:03:39'),
	(228,21000203,21000205,'CANCEL','',0,'2018-06-21 19:26:26'),
	(229,21000205,21000218,'ADD','',0,'2018-06-21 19:26:50'),
	(230,21000205,21000218,'CANCEL','',0,'2018-06-21 19:27:02'),
	(231,21000205,21000218,'ADD','',0,'2018-06-21 19:37:28'),
	(232,21000205,21000218,'CANCEL','',0,'2018-06-21 19:37:39'),
	(233,21000205,21000218,'ADD','',0,'2018-06-21 19:37:45'),
	(234,21000205,21000218,'CANCEL','',0,'2018-06-21 19:41:45'),
	(235,21000205,21000223,'CANCEL','',0,'2018-06-21 19:48:28'),
	(236,21000205,21000204,'CANCEL','',0,'2018-06-21 19:52:06'),
	(237,21000205,21000217,'CANCEL','',0,'2018-06-21 19:56:06'),
	(238,21000205,21000204,'ADD','',0,'2018-06-21 19:56:07'),
	(239,21000205,21000217,'ADD','',0,'2018-06-21 19:56:09'),
	(240,21000205,21000204,'CANCEL','',0,'2018-06-21 19:56:10'),
	(241,21000205,21000217,'CANCEL','',0,'2018-06-21 19:56:21'),
	(242,21000205,21000206,'CANCEL','',0,'2018-06-21 19:56:23'),
	(243,21000205,21000217,'ADD','',0,'2018-06-21 19:56:24'),
	(244,21000205,21000225,'CANCEL','',0,'2018-06-21 19:56:27'),
	(245,21000205,21000225,'ADD','',0,'2018-06-21 19:56:28'),
	(246,21000205,21000225,'CANCEL','',0,'2018-06-21 19:58:19'),
	(247,21000205,21000215,'CANCEL','',0,'2018-06-21 19:58:22'),
	(248,21000205,21000225,'ADD','',0,'2018-06-21 19:58:23'),
	(249,21000205,21000215,'ADD','',0,'2018-06-21 19:58:24'),
	(250,21000205,21000210,'CANCEL','',0,'2018-06-21 19:58:25'),
	(251,21000205,21000209,'CANCEL','',0,'2018-06-21 19:58:26'),
	(252,21000205,21000209,'ADD','',0,'2018-06-21 19:58:27'),
	(253,21000205,21000210,'ADD','',0,'2018-06-21 19:58:28'),
	(254,21000205,21000216,'CANCEL','',0,'2018-06-21 19:58:29'),
	(255,21000205,21000215,'CANCEL','',0,'2018-06-21 19:58:30'),
	(256,21000205,21000216,'ADD','',0,'2018-06-21 19:58:32'),
	(257,21000205,21000215,'ADD','',0,'2018-06-21 19:58:33'),
	(258,21000205,21000217,'CANCEL','',0,'2018-06-21 19:58:33'),
	(259,21000205,21000217,'ADD','',0,'2018-06-21 19:58:35'),
	(260,21000203,21000205,'ADD','',0,'2018-06-21 20:02:39'),
	(261,21000203,21000205,'CANCEL','',0,'2018-06-21 20:02:47'),
	(262,21000203,21000205,'ADD','',0,'2018-06-21 20:02:59'),
	(263,21000217,21000218,'CANCEL','',0,'2018-06-21 20:04:18'),
	(264,21000217,21000218,'ADD','',0,'2018-06-21 20:04:26'),
	(265,21000217,21000218,'CANCEL','',0,'2018-06-21 20:04:27'),
	(266,21000217,21000205,'ADD','',0,'2018-06-21 20:04:37'),
	(267,21000217,21000218,'ADD','',0,'2018-06-21 20:04:45'),
	(268,21000205,21000204,'ADD','',0,'2018-06-21 20:13:54'),
	(269,21000205,21000214,'ADD','',0,'2018-06-21 20:17:04'),
	(270,21000205,21000213,'ADD','',0,'2018-06-21 20:17:06'),
	(271,21000205,21000206,'ADD','',0,'2018-06-21 20:17:07'),
	(272,21000205,21000218,'ADD','',0,'2018-06-21 20:17:08'),
	(273,21000205,21000206,'CANCEL','',0,'2018-06-21 20:17:21'),
	(274,21000205,21000206,'ADD','',0,'2018-06-21 20:17:22'),
	(275,21000205,21000203,'ADD','',0,'2018-06-21 20:17:27'),
	(276,21000205,21000216,'CANCEL','',0,'2018-06-21 20:24:44'),
	(277,21000205,21000216,'ADD','',0,'2018-06-21 20:24:45'),
	(278,21000205,21000216,'CANCEL','',0,'2018-06-22 10:10:35'),
	(279,21000205,21000216,'ADD','',0,'2018-06-22 10:10:35'),
	(280,21000205,21000203,'CANCEL','',0,'2018-06-22 10:10:37'),
	(281,21000205,21000203,'ADD','',0,'2018-06-22 10:10:37'),
	(282,21000205,21000218,'CANCEL','',0,'2018-06-22 10:10:39'),
	(283,21000205,21000218,'ADD','',0,'2018-06-22 10:10:39'),
	(284,21000206,21000204,'ADD','',0,'2018-06-22 14:40:26'),
	(285,21000206,21000205,'ADD','',0,'2018-06-22 14:40:27'),
	(286,21000209,21000205,'ADD','',0,'2018-06-22 18:13:14'),
	(287,21000209,21000205,'CANCEL','',0,'2018-06-22 18:13:16'),
	(288,21000209,21000205,'ADD','',0,'2018-06-22 18:13:18'),
	(289,20000019,21000205,'ADD','',0,'2018-06-22 18:14:22'),
	(290,20000019,21000218,'ADD','',0,'2018-06-22 18:17:00'),
	(291,20000019,21000217,'ADD','',0,'2018-06-22 18:21:05'),
	(292,20000019,21000204,'ADD','',0,'2018-06-22 18:21:18'),
	(293,20000019,21000205,'CANCEL','',0,'2018-06-22 18:21:31'),
	(294,20000019,21000217,'CANCEL','',0,'2018-06-22 18:21:41'),
	(295,20000019,21000217,'ADD','',0,'2018-06-22 18:21:57'),
	(296,20000019,21000204,'CANCEL','',0,'2018-06-22 18:21:58'),
	(297,20000019,21000217,'CANCEL','',0,'2018-06-22 18:22:11'),
	(298,20000019,21000217,'ADD','',0,'2018-06-22 18:22:22'),
	(299,20000019,21000217,'CANCEL','',0,'2018-06-22 18:22:36'),
	(300,20000019,21000217,'ADD','',0,'2018-06-22 18:22:58'),
	(301,20000019,21000217,'CANCEL','',0,'2018-06-22 18:23:23'),
	(302,20000019,21000217,'ADD','',0,'2018-06-22 18:23:33'),
	(303,20000019,21000217,'CANCEL','',0,'2018-06-22 18:27:31'),
	(304,21000205,21000218,'CANCEL','',0,'2018-06-22 19:11:14'),
	(305,21000205,21000218,'ADD','',0,'2018-06-22 19:11:15'),
	(306,21000205,21000218,'CANCEL','',0,'2018-06-22 19:11:15'),
	(307,21000205,21000218,'ADD','',0,'2018-06-22 19:11:16'),
	(308,21000205,21000206,'CANCEL','',0,'2018-06-22 19:14:11'),
	(309,21000205,21000206,'ADD','',0,'2018-06-22 19:14:12'),
	(310,21000205,21000206,'CANCEL','',0,'2018-06-22 19:14:13'),
	(311,21000205,21000206,'ADD','',0,'2018-06-22 19:14:13'),
	(312,21000205,21000206,'CANCEL','',0,'2018-06-22 19:14:14'),
	(313,21000205,21000206,'ADD','',0,'2018-06-22 19:14:15'),
	(314,21000225,21000218,'ADD','',0,'2018-06-22 19:43:06'),
	(315,21000225,21000206,'ADD','',0,'2018-06-22 19:46:57'),
	(316,21000225,21000206,'CANCEL','',0,'2018-06-22 19:47:05'),
	(317,21000205,20000019,'ADD','',0,'2018-06-22 19:54:56'),
	(318,21000205,20000019,'CANCEL','',0,'2018-06-22 19:57:27'),
	(319,21000205,20000019,'ADD','',0,'2018-06-22 19:57:28'),
	(320,21000205,21000217,'CANCEL','',0,'2018-06-22 19:57:30'),
	(321,21000205,21000217,'ADD','',0,'2018-06-22 19:57:30'),
	(322,21000205,20000019,'CANCEL','',0,'2018-06-22 19:57:58'),
	(323,21000205,20000019,'ADD','',0,'2018-06-22 19:57:59'),
	(324,21000205,21000217,'CANCEL','',0,'2018-06-22 19:58:00'),
	(325,21000205,21000217,'ADD','',0,'2018-06-22 19:58:00'),
	(326,21000205,21000218,'CANCEL','',0,'2018-06-22 19:59:11'),
	(327,21000205,21000218,'ADD','',0,'2018-06-22 19:59:11'),
	(328,21000205,21000218,'CANCEL','',0,'2018-06-22 19:59:13'),
	(329,21000205,21000218,'ADD','',0,'2018-06-22 19:59:18'),
	(330,21000205,21000218,'CANCEL','',0,'2018-06-22 19:59:30'),
	(331,21000205,21000218,'ADD','',0,'2018-06-22 19:59:35'),
	(332,21000225,21000206,'ADD','',0,'2018-06-22 20:10:50'),
	(333,21000205,21000218,'CANCEL','',0,'2018-06-22 20:21:32'),
	(334,21000205,21000218,'ADD','',0,'2018-06-22 20:21:39'),
	(335,21000202,21000218,'ADD','',0,'2018-06-22 20:22:48'),
	(336,21000202,21000218,'CANCEL','',0,'2018-06-22 20:22:50'),
	(337,21000202,21000218,'ADD','',0,'2018-06-22 20:22:51'),
	(338,21000202,21000218,'CANCEL','',0,'2018-06-22 20:22:52'),
	(339,21000202,21000218,'ADD','',0,'2018-06-22 20:22:53'),
	(340,21000202,21000218,'CANCEL','',0,'2018-06-22 20:22:53'),
	(341,21000205,21000218,'CANCEL','',0,'2018-06-22 20:23:37'),
	(342,21000205,21000218,'ADD','',0,'2018-06-22 20:26:19'),
	(343,21000205,21000218,'CANCEL','',0,'2018-06-22 20:26:20'),
	(344,21000205,21000218,'ADD','',0,'2018-06-22 20:26:21'),
	(345,21000205,21000218,'CANCEL','',0,'2018-06-22 20:26:23'),
	(346,21000205,21000218,'ADD','',0,'2018-06-22 20:26:23'),
	(347,21000205,21000218,'CANCEL','',0,'2018-06-22 20:26:33'),
	(348,21000205,21000218,'ADD','',0,'2018-06-22 20:26:53'),
	(349,21000205,21000218,'CANCEL','',0,'2018-06-22 20:30:41'),
	(350,21000205,21000218,'ADD','',0,'2018-06-22 20:30:45'),
	(351,21000205,21000225,'CANCEL','',0,'2018-06-22 20:33:44'),
	(352,21000205,20000019,'CANCEL','',0,'2018-06-22 20:33:45'),
	(353,21000205,20000019,'ADD','',0,'2018-06-22 20:33:47'),
	(354,21000205,21000225,'ADD','',0,'2018-06-22 20:33:47'),
	(355,21000205,20000019,'CANCEL','',0,'2018-06-22 20:33:49'),
	(356,21000205,20000019,'ADD','',0,'2018-06-22 20:33:50'),
	(357,21000205,21000225,'CANCEL','',0,'2018-06-22 20:33:51'),
	(358,21000205,20000019,'CANCEL','',0,'2018-06-22 20:33:52'),
	(359,21000205,20000019,'ADD','',0,'2018-06-22 20:33:53'),
	(360,21000205,21000225,'ADD','',0,'2018-06-22 20:33:53'),
	(361,21000205,21000217,'CANCEL','',0,'2018-06-22 20:33:54'),
	(362,21000205,21000217,'ADD','',0,'2018-06-22 20:33:55'),
	(363,21000205,21000204,'CANCEL','',0,'2018-06-22 20:33:55'),
	(364,21000205,21000204,'ADD','',0,'2018-06-22 20:33:56'),
	(365,21000205,21000204,'CANCEL','',0,'2018-06-22 20:57:42'),
	(366,21000205,21000204,'ADD','',0,'2018-06-22 20:57:43'),
	(367,21000205,21000225,'CANCEL','',0,'2018-06-22 20:57:44'),
	(368,21000205,21000225,'ADD','',0,'2018-06-22 20:57:45'),
	(369,21000205,21000225,'CANCEL','',0,'2018-06-22 21:00:34'),
	(370,21000205,21000225,'ADD','',0,'2018-06-22 21:05:41'),
	(371,21000205,21000204,'CANCEL','',0,'2018-06-22 21:05:46'),
	(372,21000205,21000225,'CANCEL','',0,'2018-06-22 21:06:58'),
	(373,21000205,21000225,'ADD','',0,'2018-06-22 21:07:00'),
	(374,21000205,21000204,'ADD','',0,'2018-06-22 21:07:01'),
	(375,21000205,21000204,'CANCEL','',0,'2018-06-22 21:12:50'),
	(376,21000205,21000204,'ADD','',0,'2018-06-22 21:12:52'),
	(377,21000205,21000204,'CANCEL','',0,'2018-06-22 21:13:52'),
	(378,21000205,21000204,'ADD','',0,'2018-06-22 21:13:52'),
	(379,21000205,21000217,'CANCEL','',0,'2018-06-22 21:13:54'),
	(380,21000205,21000217,'ADD','',0,'2018-06-22 21:13:55'),
	(381,21000205,21000204,'CANCEL','',0,'2018-06-22 21:14:01'),
	(382,21000205,21000204,'ADD','',0,'2018-06-22 21:14:01'),
	(383,21000205,21000225,'CANCEL','',0,'2018-06-23 10:16:40'),
	(384,21000205,20000019,'CANCEL','',0,'2018-06-23 10:16:42'),
	(385,21000205,21000225,'ADD','',0,'2018-06-23 10:16:43'),
	(386,21000205,20000019,'ADD','',0,'2018-06-23 10:16:43'),
	(387,21000205,21000217,'CANCEL','',0,'2018-06-23 10:16:44'),
	(388,21000205,21000217,'ADD','',0,'2018-06-23 10:16:45'),
	(389,21000205,21000204,'CANCEL','',0,'2018-06-23 10:16:57'),
	(390,21000205,21000204,'ADD','',0,'2018-06-23 10:16:59'),
	(391,21000205,21000218,'CANCEL','',0,'2018-06-23 10:17:01'),
	(392,21000205,21000218,'ADD','',0,'2018-06-23 10:17:02'),
	(393,21000205,21000218,'CANCEL','',0,'2018-06-23 10:17:07'),
	(394,21000205,21000218,'ADD','',0,'2018-06-23 10:17:07'),
	(395,21000202,21000218,'ADD','',0,'2018-06-23 11:18:57'),
	(396,21000202,21000209,'ADD','',0,'2018-06-23 13:13:29'),
	(397,21000205,21000215,'CANCEL','',0,'2018-06-23 13:21:26'),
	(398,21000224,21000202,'ADD','',0,'2018-06-23 13:44:00'),
	(399,21000224,21000202,'CANCEL','',0,'2018-06-23 13:44:01'),
	(400,21000224,21000202,'ADD','',0,'2018-06-23 13:44:01'),
	(401,21000224,21000202,'CANCEL','',0,'2018-06-23 13:44:02'),
	(402,21000224,21000202,'ADD','',0,'2018-06-23 13:44:03'),
	(403,21000224,21000202,'CANCEL','',0,'2018-06-23 13:44:04'),
	(404,21000224,21000202,'ADD','',0,'2018-06-23 13:44:05'),
	(405,21000224,21000202,'CANCEL','',0,'2018-06-23 13:44:06'),
	(406,21000224,21000218,'ADD','',0,'2018-06-23 13:44:15'),
	(407,21000202,21000205,'ADD','',0,'2018-06-23 13:47:12'),
	(408,21000224,21000205,'ADD','',0,'2018-06-23 13:53:40'),
	(409,21000205,21000202,'ADD','',0,'2018-06-23 17:41:06'),
	(410,21000205,21000224,'ADD','',0,'2018-06-23 17:41:07'),
	(411,21000205,21000215,'ADD','',0,'2018-06-23 17:46:14'),
	(412,21000225,21000206,'CANCEL','',0,'2018-06-28 19:40:08'),
	(413,21000225,21000205,'ADD','',0,'2018-06-29 14:40:50'),
	(414,21000205,21000225,'CANCEL','',0,'2018-06-29 15:36:03'),
	(415,21000205,21000203,'CANCEL','',0,'2018-06-29 15:37:01'),
	(416,21000225,21000217,'ADD','',0,'2018-06-29 19:32:35'),
	(417,21000205,21000225,'ADD','',0,'2018-06-30 11:38:44'),
	(418,21000203,21000225,'ADD','',0,'2018-06-30 16:07:37'),
	(419,21000203,21000217,'ADD','',0,'2018-06-30 16:07:42'),
	(420,20000019,21000205,'ADD','',0,'2018-06-30 17:01:28'),
	(421,20000019,21000217,'ADD','',0,'2018-06-30 17:01:43'),
	(422,20000019,21000225,'ADD','',0,'2018-06-30 17:01:55'),
	(423,21000209,21000225,'ADD','',0,'2018-06-30 17:02:31'),
	(424,21000209,21000217,'ADD','',0,'2018-06-30 17:02:59'),
	(425,20000019,21000225,'CANCEL','',0,'2018-06-30 17:32:10'),
	(426,20000019,21000225,'ADD','',0,'2018-06-30 17:32:14'),
	(427,20000019,21000225,'CANCEL','',0,'2018-06-30 17:32:24'),
	(428,20000019,21000209,'ADD','',0,'2018-06-30 17:32:35'),
	(429,20000019,21000203,'ADD','',0,'2018-06-30 17:32:35'),
	(430,21000209,21000203,'ADD','',0,'2018-06-30 18:00:25'),
	(431,21000205,21000203,'ADD','',0,'2018-06-30 18:48:46'),
	(432,21000209,21000217,'CANCEL','',0,'2018-07-01 09:18:02'),
	(433,21000209,21000217,'ADD','',0,'2018-07-01 09:18:22'),
	(434,21000209,20000019,'ADD','',0,'2018-07-01 09:18:34'),
	(435,21000209,20000019,'CANCEL','',0,'2018-07-01 09:18:36'),
	(436,21000209,21000203,'CANCEL','',0,'2018-07-01 09:18:37'),
	(437,21000209,21000225,'CANCEL','',0,'2018-07-01 09:18:38'),
	(438,21000209,21000205,'CANCEL','',0,'2018-07-01 09:18:39'),
	(439,21000214,21000204,'ADD','',0,'2018-07-02 16:57:27'),
	(440,21000214,21000205,'ADD','',0,'2018-07-02 16:57:28'),
	(441,20000023,21000225,'ADD','',0,'2018-07-04 15:24:23'),
	(442,20000023,21000217,'ADD','',0,'2018-07-04 15:24:25'),
	(443,20000031,21000224,'ADD','',0,'2018-07-06 14:12:58'),
	(444,20000031,21000202,'ADD','',0,'2018-07-06 14:12:59'),
	(445,20000031,21000225,'ADD','',0,'2018-07-06 14:13:00'),
	(446,20000031,20000019,'ADD','',0,'2018-07-06 14:13:02'),
	(447,20000031,21000217,'ADD','',0,'2018-07-06 14:13:11'),
	(448,20000031,21000204,'ADD','',0,'2018-07-06 14:13:12'),
	(449,20000031,21000209,'ADD','',0,'2018-07-06 14:13:25'),
	(450,20000031,21000218,'ADD','',0,'2018-07-06 14:13:27'),
	(451,20000031,21000203,'ADD','',0,'2018-07-06 14:14:01'),
	(452,21000224,21000225,'ADD','',0,'2018-07-07 16:14:10'),
	(453,21000224,21000217,'ADD','',0,'2018-07-07 16:14:17'),
	(454,21000224,21000217,'CANCEL','',0,'2018-07-07 16:14:22'),
	(455,21000224,21000225,'CANCEL','',0,'2018-07-07 16:14:25'),
	(456,21000216,21000225,'ADD','',0,'2018-07-12 15:21:04'),
	(457,21000224,20000031,'ADD','',0,'2018-07-12 18:44:28'),
	(458,20000031,21000216,'ADD','',0,'2018-07-12 20:58:48'),
	(459,20000031,21000216,'CANCEL','',0,'2018-07-12 20:58:49'),
	(460,20000031,20000023,'ADD','',0,'2018-07-19 16:02:53'),
	(461,20000031,21000205,'ADD','',0,'2018-07-19 16:39:21'),
	(462,20000031,21000224,'CANCEL','',0,'2018-07-27 15:31:09'),
	(463,20000031,21000224,'ADD','',0,'2018-07-27 15:31:13');

/*!40000 ALTER TABLE `followlog` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table profile_0
# ------------------------------------------------------------

CREATE TABLE `profile_0` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_1
# ------------------------------------------------------------

CREATE TABLE `profile_1` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_10
# ------------------------------------------------------------

CREATE TABLE `profile_10` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_11
# ------------------------------------------------------------

CREATE TABLE `profile_11` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_12
# ------------------------------------------------------------

CREATE TABLE `profile_12` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_13
# ------------------------------------------------------------

CREATE TABLE `profile_13` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_14
# ------------------------------------------------------------

CREATE TABLE `profile_14` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `profile_14` WRITE;
/*!40000 ALTER TABLE `profile_14` DISABLE KEYS */;

INSERT INTO `profile_14` (`id`, `uid`, `item`, `value`, `addtime`, `modtime`)
VALUES
	(1,21000214,'tag','','2018-07-02 17:05:07','2018-07-02 17:05:07');

/*!40000 ALTER TABLE `profile_14` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table profile_15
# ------------------------------------------------------------

CREATE TABLE `profile_15` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_16
# ------------------------------------------------------------

CREATE TABLE `profile_16` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `profile_16` WRITE;
/*!40000 ALTER TABLE `profile_16` DISABLE KEYS */;

INSERT INTO `profile_16` (`id`, `uid`, `item`, `value`, `addtime`, `modtime`)
VALUES
	(5,21000216,'height','180','2018-06-23 17:57:34','2018-06-23 17:57:34'),
	(6,21000216,'weight','95','2018-06-23 17:57:34','2018-06-23 17:57:34'),
	(7,21000216,'shoe_size','42','2018-06-23 17:57:34','2018-06-23 17:57:34'),
	(8,21000216,'tag','逛街不买会死星人|灵活的胖子|小鲜肉|无肉不欢|衣柜永远缺一件','2018-06-23 17:57:34','2018-06-23 17:57:34'),
	(11,21000216,'signature','','2018-06-23 17:59:31','2018-06-23 17:59:31'),
	(12,21000216,'social','[]','2018-06-23 17:59:31','2018-06-23 17:59:31');

/*!40000 ALTER TABLE `profile_16` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table profile_17
# ------------------------------------------------------------

CREATE TABLE `profile_17` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `profile_17` WRITE;
/*!40000 ALTER TABLE `profile_17` DISABLE KEYS */;

INSERT INTO `profile_17` (`id`, `uid`, `item`, `value`, `addtime`, `modtime`)
VALUES
	(43,21000217,'height','170','2018-06-22 18:44:50','2018-06-22 18:44:50'),
	(44,21000217,'weight','75','2018-06-22 18:44:51','2018-06-22 18:44:51'),
	(45,21000217,'shoe_size','41','2018-06-22 18:44:51','2018-06-22 18:44:51'),
	(46,21000217,'tag','逛街不买会死星人|游戏发烧友|月光族|艺术家|宅就一个字','2018-06-22 18:44:51','2018-06-22 18:44:51'),
	(47,21000217,'signature','adsfadsfadsf','2018-06-22 19:08:56','2018-06-22 19:08:56'),
	(48,21000217,'social','[{\"name\":\"\\u6296\\u97f3\",\"val\":\"aaaaaaaaaaaaa\",\"type\":\"3\"},{\"name\":\"\\u5fae\\u4fe1\",\"val\":\"asdfasdfasdf\",\"type\":\"1\"},{\"name\":\"\\u8c46\\u74e3\",\"val\":\"1231466464\",\"type\":\"0\",\"input\":true}]','2018-06-22 19:08:56','2018-06-22 19:08:56');

/*!40000 ALTER TABLE `profile_17` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table profile_18
# ------------------------------------------------------------

CREATE TABLE `profile_18` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `profile_18` WRITE;
/*!40000 ALTER TABLE `profile_18` DISABLE KEYS */;

INSERT INTO `profile_18` (`id`, `uid`, `item`, `value`, `addtime`, `modtime`)
VALUES
	(155,21000218,'height','191','2018-07-19 16:47:39','2018-07-19 16:47:39'),
	(157,21000218,'weight','117','2018-07-19 16:47:39','2018-07-19 16:47:39'),
	(159,21000218,'shoe_size','46','2018-07-19 16:47:39','2018-07-19 16:47:39'),
	(161,21000218,'tag','逛街不买会死星人|是新手办的香气|商务人士|灵魂歌者|品酒大师','2018-07-19 16:47:39','2018-07-19 16:47:39');

/*!40000 ALTER TABLE `profile_18` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table profile_19
# ------------------------------------------------------------

CREATE TABLE `profile_19` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `profile_19` WRITE;
/*!40000 ALTER TABLE `profile_19` DISABLE KEYS */;

INSERT INTO `profile_19` (`id`, `uid`, `item`, `value`, `addtime`, `modtime`)
VALUES
	(15,20000019,'height','175','2018-06-22 18:15:55','2018-06-22 18:15:55'),
	(16,20000019,'weight','70','2018-06-22 18:15:55','2018-06-22 18:15:55'),
	(17,20000019,'shoe_size','42','2018-06-22 18:15:55','2018-06-22 18:15:55'),
	(18,20000019,'tag','欧美风|品酒大师|半专业影评人|我不管就要做死颜控|零食粉碎机','2018-06-22 18:15:55','2018-06-22 18:15:55'),
	(33,20000019,'signature','aaa','2018-06-30 17:30:21','2018-06-30 17:30:21'),
	(34,20000019,'social','[{\"name\":\"\\u5176\\u4ed6\",\"val\":\"aaa\",\"type\":\"0\",\"input\":true}]','2018-06-30 17:30:21','2018-06-30 17:30:21');

/*!40000 ALTER TABLE `profile_19` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table profile_2
# ------------------------------------------------------------

CREATE TABLE `profile_2` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `profile_2` WRITE;
/*!40000 ALTER TABLE `profile_2` DISABLE KEYS */;

INSERT INTO `profile_2` (`id`, `uid`, `item`, `value`, `addtime`, `modtime`)
VALUES
	(91,21000202,'height','160','2018-06-23 13:48:53','2018-06-23 13:48:53'),
	(92,21000202,'weight','50','2018-06-23 13:48:53','2018-06-23 13:48:53'),
	(93,21000202,'shoe_size','37','2018-06-23 13:48:53','2018-06-23 13:48:53'),
	(94,21000202,'tag','宅就一个字|天真无鞋|芒果台忠实粉丝|高大上','2018-06-23 13:48:53','2018-06-23 13:48:53'),
	(99,21000202,'signature','','2018-06-23 13:53:42','2018-06-23 13:53:42'),
	(100,21000202,'social','[{\"name\":\"\\u5fae\\u4fe1\",\"val\":\"\\u738b\\u5c0f\\u7f8e\",\"type\":\"1\"},{\"name\":\"\\u5fae\\u535a\",\"val\":\"@\\u6f14\\u5458\\u738b\\u5c0f\\u7f8e\",\"type\":\"2\"},{\"name\":\"\\u6296\\u97f3\",\"val\":\"ID\\uff1a35271543\",\"type\":\"3\"},{\"name\":\"\\u6dd8\\u5b9d\",\"val\":\"\\u7f8e\\u7f8e\\u7f8e\\u5986\\u5c16\\u8d27\\u5e97\",\"type\":\"4\"},{\"name\":\"\\u5929\\u732b\",\"val\":\"\\u7f8e\\u7f8e\\u7f8e\\u5986\\u5c16\\u8d27\\u5e97\",\"type\":\"5\"}]','2018-06-23 13:53:42','2018-06-23 13:53:42');

/*!40000 ALTER TABLE `profile_2` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table profile_20
# ------------------------------------------------------------

CREATE TABLE `profile_20` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `profile_20` WRITE;
/*!40000 ALTER TABLE `profile_20` DISABLE KEYS */;

INSERT INTO `profile_20` (`id`, `uid`, `item`, `value`, `addtime`, `modtime`)
VALUES
	(5,20000020,'height','179','2018-06-30 17:26:04','2018-06-30 17:26:04'),
	(6,20000020,'weight','82','2018-06-30 17:26:04','2018-06-30 17:26:04'),
	(7,20000020,'shoe_size','43','2018-06-30 17:26:04','2018-06-30 17:26:04'),
	(8,20000020,'tag','多肉爱好者|人民守护者','2018-06-30 17:26:04','2018-06-30 17:26:04');

/*!40000 ALTER TABLE `profile_20` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table profile_21
# ------------------------------------------------------------

CREATE TABLE `profile_21` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_22
# ------------------------------------------------------------

CREATE TABLE `profile_22` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `profile_22` WRITE;
/*!40000 ALTER TABLE `profile_22` DISABLE KEYS */;

INSERT INTO `profile_22` (`id`, `uid`, `item`, `value`, `addtime`, `modtime`)
VALUES
	(1,20000022,'height','160','2018-07-03 10:40:17','2018-07-03 10:40:17'),
	(2,20000022,'weight','53','2018-07-03 10:40:17','2018-07-03 10:40:17'),
	(3,20000022,'shoe_size','37','2018-07-03 10:40:17','2018-07-03 10:40:17'),
	(4,20000022,'tag','逛街不买会死星人|衣柜永远缺一件|不听歌毋宁死','2018-07-03 10:40:17','2018-07-03 10:40:17');

/*!40000 ALTER TABLE `profile_22` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table profile_23
# ------------------------------------------------------------

CREATE TABLE `profile_23` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_24
# ------------------------------------------------------------

CREATE TABLE `profile_24` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `profile_24` WRITE;
/*!40000 ALTER TABLE `profile_24` DISABLE KEYS */;

INSERT INTO `profile_24` (`id`, `uid`, `item`, `value`, `addtime`, `modtime`)
VALUES
	(5,21000224,'signature','','2018-06-21 11:24:06','2018-06-21 11:24:06'),
	(6,21000224,'social','[{\"name\":\"\\u5fae\\u535a\",\"val\":\"\\u54c8\\u54c8\",\"type\":\"2\"},{\"name\":\"\\u5fae\\u4fe1\",\"val\":\"\\u5475\\u5475\",\"type\":\"1\"}]','2018-06-21 11:24:06','2018-06-21 11:24:06'),
	(21,21000224,'height','140','2018-06-21 11:35:30','2018-06-21 11:35:30'),
	(22,21000224,'weight','120','2018-06-21 11:35:30','2018-06-21 11:35:30'),
	(23,21000224,'shoe_size','46','2018-06-21 11:35:30','2018-06-21 11:35:30'),
	(24,21000224,'tag','','2018-06-21 11:35:30','2018-06-21 11:35:30');

/*!40000 ALTER TABLE `profile_24` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table profile_25
# ------------------------------------------------------------

CREATE TABLE `profile_25` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `profile_25` WRITE;
/*!40000 ALTER TABLE `profile_25` DISABLE KEYS */;

INSERT INTO `profile_25` (`id`, `uid`, `item`, `value`, `addtime`, `modtime`)
VALUES
	(87,21000225,'height','140','2018-06-23 14:06:31','2018-06-23 14:06:31'),
	(88,21000225,'weight','40','2018-06-23 14:06:31','2018-06-23 14:06:31'),
	(89,21000225,'shoe_size','34','2018-06-23 14:06:31','2018-06-23 14:06:31'),
	(90,21000225,'tag','见过凌晨四点的路灯|要温度不要风度|我为国家省布料','2018-06-23 14:06:31','2018-06-23 14:06:31'),
	(93,21000225,'signature','','2018-06-23 14:39:04','2018-06-23 14:39:04'),
	(94,21000225,'social','[{\"name\":\"\\u5fae\\u4fe1\",\"val\":\"9911111\",\"type\":\"1\"},{\"name\":\"\\u5fae\\u535a\",\"val\":\"22222\",\"type\":\"2\"},{\"name\":\"\\u6296\\u97f3\",\"val\":\"33333\",\"type\":\"3\"},{\"name\":\"\\u81ea\\u5b9a\\u4e49\\u554a\",\"val\":\"\\u554a\\u554a\\u554a\\u554a\\u554a\",\"type\":\"0\",\"input\":true}]','2018-06-23 14:39:04','2018-06-23 14:39:04');

/*!40000 ALTER TABLE `profile_25` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table profile_26
# ------------------------------------------------------------

CREATE TABLE `profile_26` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_27
# ------------------------------------------------------------

CREATE TABLE `profile_27` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_28
# ------------------------------------------------------------

CREATE TABLE `profile_28` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_29
# ------------------------------------------------------------

CREATE TABLE `profile_29` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_3
# ------------------------------------------------------------

CREATE TABLE `profile_3` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `profile_3` WRITE;
/*!40000 ALTER TABLE `profile_3` DISABLE KEYS */;

INSERT INTO `profile_3` (`id`, `uid`, `item`, `value`, `addtime`, `modtime`)
VALUES
	(64,21000203,'height','175','2018-06-21 14:33:57','2018-06-21 14:33:57'),
	(65,21000203,'weight','85','2018-06-21 14:33:57','2018-06-21 14:33:57'),
	(66,21000203,'shoe_size','41','2018-06-21 14:33:57','2018-06-21 14:33:57'),
	(67,21000203,'tag','心灵手巧|宅就一个字|月光族|剁手大赛年度总冠军','2018-06-21 14:33:57','2018-06-21 14:33:57'),
	(70,21000203,'signature','🎴🎴🎴🎴♠️👁‍🗨🔊⬛️','2018-07-13 10:13:35','2018-07-13 10:13:35'),
	(71,21000203,'social','[]','2018-07-13 10:13:35','2018-07-13 10:13:35');

/*!40000 ALTER TABLE `profile_3` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table profile_30
# ------------------------------------------------------------

CREATE TABLE `profile_30` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_31
# ------------------------------------------------------------

CREATE TABLE `profile_31` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `profile_31` WRITE;
/*!40000 ALTER TABLE `profile_31` DISABLE KEYS */;

INSERT INTO `profile_31` (`id`, `uid`, `item`, `value`, `addtime`, `modtime`)
VALUES
	(1,20000031,'height','159','2018-07-05 17:41:08','2018-07-05 17:41:08'),
	(2,20000031,'weight','48','2018-07-05 17:41:08','2018-07-05 17:41:08'),
	(3,20000031,'shoe_size','35','2018-07-05 17:41:08','2018-07-05 17:41:08'),
	(4,20000031,'tag','佛系养生|码农|专业码字|骑行游世界','2018-07-05 17:41:08','2018-07-05 17:41:08');

/*!40000 ALTER TABLE `profile_31` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table profile_32
# ------------------------------------------------------------

CREATE TABLE `profile_32` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_33
# ------------------------------------------------------------

CREATE TABLE `profile_33` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_34
# ------------------------------------------------------------

CREATE TABLE `profile_34` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_35
# ------------------------------------------------------------

CREATE TABLE `profile_35` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_36
# ------------------------------------------------------------

CREATE TABLE `profile_36` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_37
# ------------------------------------------------------------

CREATE TABLE `profile_37` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_38
# ------------------------------------------------------------

CREATE TABLE `profile_38` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_39
# ------------------------------------------------------------

CREATE TABLE `profile_39` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_4
# ------------------------------------------------------------

CREATE TABLE `profile_4` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `profile_4` WRITE;
/*!40000 ALTER TABLE `profile_4` DISABLE KEYS */;

INSERT INTO `profile_4` (`id`, `uid`, `item`, `value`, `addtime`, `modtime`)
VALUES
	(1,21000204,'weixin','sdfsdfsdfds','2018-06-15 10:47:46','2018-06-15 10:47:46'),
	(2,21000204,'qq','123123','2018-06-15 10:47:46','2018-06-15 10:47:46'),
	(3,21000204,'douyin','1231233','2018-06-15 10:47:46','2018-06-15 10:47:46'),
	(13,21000204,'signature','','2018-06-21 16:03:49','2018-06-21 16:03:49'),
	(14,21000204,'social','[{\"name\":\"\\u5fae\\u4fe1\",\"type\":1,\"val\":\"xxxxx.com\"},{\"name\":\"\\u5fae\\u535a\",\"type\":2,\"val\":\"xxxxx.com\"},{\"name\":\"\\u6dd8\\u5b9d\",\"val\":\"\\u662f\\u6211\",\"type\":\"4\"},{\"name\":\"\\u5fae\\u4fe1\",\"val\":\"\\u63a5\\u53e3\",\"type\":\"1\"}]','2018-06-21 16:03:49','2018-06-21 16:03:49'),
	(25,21000204,'height','220','2018-06-21 16:06:24','2018-06-21 16:06:24'),
	(26,21000204,'weight','40','2018-06-21 16:06:24','2018-06-21 16:06:24'),
	(27,21000204,'shoe_size','46','2018-06-21 16:06:24','2018-06-21 16:06:24'),
	(28,21000204,'tag','软妹子|摄影大师','2018-06-21 16:06:25','2018-06-21 16:06:25');

/*!40000 ALTER TABLE `profile_4` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table profile_40
# ------------------------------------------------------------

CREATE TABLE `profile_40` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_41
# ------------------------------------------------------------

CREATE TABLE `profile_41` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_42
# ------------------------------------------------------------

CREATE TABLE `profile_42` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_43
# ------------------------------------------------------------

CREATE TABLE `profile_43` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_44
# ------------------------------------------------------------

CREATE TABLE `profile_44` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_45
# ------------------------------------------------------------

CREATE TABLE `profile_45` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_46
# ------------------------------------------------------------

CREATE TABLE `profile_46` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_47
# ------------------------------------------------------------

CREATE TABLE `profile_47` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_48
# ------------------------------------------------------------

CREATE TABLE `profile_48` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_49
# ------------------------------------------------------------

CREATE TABLE `profile_49` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_5
# ------------------------------------------------------------

CREATE TABLE `profile_5` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `profile_5` WRITE;
/*!40000 ALTER TABLE `profile_5` DISABLE KEYS */;

INSERT INTO `profile_5` (`id`, `uid`, `item`, `value`, `addtime`, `modtime`)
VALUES
	(115,21000205,'height','165','2018-06-28 14:20:08','2018-06-28 14:20:08'),
	(116,21000205,'weight','46','2018-06-28 14:20:08','2018-06-28 14:20:08'),
	(117,21000205,'shoe_size','35','2018-06-28 14:20:08','2018-06-28 14:20:08'),
	(118,21000205,'tag','天真无鞋|摄影大师|女汉子|码农','2018-06-28 14:20:08','2018-06-28 14:20:08'),
	(119,21000205,'signature','1111111呜呜呜','2018-06-29 15:33:34','2018-06-29 15:33:34'),
	(120,21000205,'social','[{\"name\":\"\\u5fae\\u4fe1\",\"val\":\"\\u963f\\u9e7f\",\"type\":\"1\"},{\"name\":\"\\u5fae\\u4fe1\",\"val\":\"\\u70c0\",\"type\":\"1\"}]','2018-06-29 15:33:34','2018-06-29 15:33:34');

/*!40000 ALTER TABLE `profile_5` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table profile_50
# ------------------------------------------------------------

CREATE TABLE `profile_50` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_51
# ------------------------------------------------------------

CREATE TABLE `profile_51` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_52
# ------------------------------------------------------------

CREATE TABLE `profile_52` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_53
# ------------------------------------------------------------

CREATE TABLE `profile_53` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_54
# ------------------------------------------------------------

CREATE TABLE `profile_54` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_55
# ------------------------------------------------------------

CREATE TABLE `profile_55` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_56
# ------------------------------------------------------------

CREATE TABLE `profile_56` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_57
# ------------------------------------------------------------

CREATE TABLE `profile_57` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_58
# ------------------------------------------------------------

CREATE TABLE `profile_58` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_59
# ------------------------------------------------------------

CREATE TABLE `profile_59` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_6
# ------------------------------------------------------------

CREATE TABLE `profile_6` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_60
# ------------------------------------------------------------

CREATE TABLE `profile_60` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_61
# ------------------------------------------------------------

CREATE TABLE `profile_61` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_62
# ------------------------------------------------------------

CREATE TABLE `profile_62` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_63
# ------------------------------------------------------------

CREATE TABLE `profile_63` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_64
# ------------------------------------------------------------

CREATE TABLE `profile_64` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_65
# ------------------------------------------------------------

CREATE TABLE `profile_65` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_66
# ------------------------------------------------------------

CREATE TABLE `profile_66` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_67
# ------------------------------------------------------------

CREATE TABLE `profile_67` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_68
# ------------------------------------------------------------

CREATE TABLE `profile_68` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_69
# ------------------------------------------------------------

CREATE TABLE `profile_69` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_7
# ------------------------------------------------------------

CREATE TABLE `profile_7` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_70
# ------------------------------------------------------------

CREATE TABLE `profile_70` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_71
# ------------------------------------------------------------

CREATE TABLE `profile_71` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_72
# ------------------------------------------------------------

CREATE TABLE `profile_72` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_73
# ------------------------------------------------------------

CREATE TABLE `profile_73` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_74
# ------------------------------------------------------------

CREATE TABLE `profile_74` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_75
# ------------------------------------------------------------

CREATE TABLE `profile_75` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_76
# ------------------------------------------------------------

CREATE TABLE `profile_76` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_77
# ------------------------------------------------------------

CREATE TABLE `profile_77` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_78
# ------------------------------------------------------------

CREATE TABLE `profile_78` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_79
# ------------------------------------------------------------

CREATE TABLE `profile_79` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_8
# ------------------------------------------------------------

CREATE TABLE `profile_8` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_80
# ------------------------------------------------------------

CREATE TABLE `profile_80` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_81
# ------------------------------------------------------------

CREATE TABLE `profile_81` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_82
# ------------------------------------------------------------

CREATE TABLE `profile_82` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_83
# ------------------------------------------------------------

CREATE TABLE `profile_83` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_84
# ------------------------------------------------------------

CREATE TABLE `profile_84` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_85
# ------------------------------------------------------------

CREATE TABLE `profile_85` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_86
# ------------------------------------------------------------

CREATE TABLE `profile_86` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_87
# ------------------------------------------------------------

CREATE TABLE `profile_87` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_88
# ------------------------------------------------------------

CREATE TABLE `profile_88` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_89
# ------------------------------------------------------------

CREATE TABLE `profile_89` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_9
# ------------------------------------------------------------

CREATE TABLE `profile_9` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `profile_9` WRITE;
/*!40000 ALTER TABLE `profile_9` DISABLE KEYS */;

INSERT INTO `profile_9` (`id`, `uid`, `item`, `value`, `addtime`, `modtime`)
VALUES
	(15,21000209,'height','170','2018-06-22 18:12:14','2018-06-22 18:12:14'),
	(16,21000209,'weight','65','2018-06-22 18:12:14','2018-06-22 18:12:14'),
	(17,21000209,'shoe_size','41','2018-06-22 18:12:14','2018-06-22 18:12:14'),
	(18,21000209,'tag','大叔|码农','2018-06-22 18:12:14','2018-06-22 18:12:14');

/*!40000 ALTER TABLE `profile_9` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table profile_90
# ------------------------------------------------------------

CREATE TABLE `profile_90` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_91
# ------------------------------------------------------------

CREATE TABLE `profile_91` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_92
# ------------------------------------------------------------

CREATE TABLE `profile_92` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_93
# ------------------------------------------------------------

CREATE TABLE `profile_93` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_94
# ------------------------------------------------------------

CREATE TABLE `profile_94` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_95
# ------------------------------------------------------------

CREATE TABLE `profile_95` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_96
# ------------------------------------------------------------

CREATE TABLE `profile_96` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_97
# ------------------------------------------------------------

CREATE TABLE `profile_97` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_98
# ------------------------------------------------------------

CREATE TABLE `profile_98` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table profile_99
# ------------------------------------------------------------

CREATE TABLE `profile_99` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `item` varchar(100) NOT NULL DEFAULT '',
  `value` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_item` (`uid`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table task
# ------------------------------------------------------------

CREATE TABLE `task` (
  `taskid` int(10) NOT NULL AUTO_INCREMENT,
  `type` int(10) unsigned NOT NULL DEFAULT '10000' COMMENT '任务类型',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `title` varchar(50) DEFAULT NULL COMMENT '任务标识',
  `totallimit` int(10) NOT NULL DEFAULT '0' COMMENT '总次数限制',
  `daylimit` int(10) NOT NULL DEFAULT '0' COMMENT '每日次数限制',
  `totalamount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '总数量限制',
  `dayamount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '每日数量限制',
  `begintime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '开始时间',
  `endtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '结束时间',
  `active` enum('Y','N') NOT NULL DEFAULT 'Y' COMMENT '状态: Y开启N关闭',
  `extend` varchar(16000) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  `score` int(10) unsigned DEFAULT '1000' COMMENT '排序,运营调整顺序',
  `dependid` int(10) unsigned DEFAULT '0' COMMENT '前置taskid',
  `level` int(10) unsigned DEFAULT '1' COMMENT '等级,用于等级任务',
  `schema` varchar(200) NOT NULL DEFAULT '' COMMENT '客户端跳转地址',
  `describe` text COMMENT '描述',
  `status` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '是否隐藏:Y是N否',
  PRIMARY KEY (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `task` WRITE;
/*!40000 ALTER TABLE `task` DISABLE KEYS */;

INSERT INTO `task` (`taskid`, `type`, `name`, `title`, `totallimit`, `daylimit`, `totalamount`, `dayamount`, `begintime`, `endtime`, `active`, `extend`, `addtime`, `modtime`, `score`, `dependid`, `level`, `schema`, `describe`, `status`)
VALUES
	(1,10001,'新用户注册','new_user_register',1,1,0,0,'0000-00-00 00:00:00','0000-00-00 00:00:00','Y','{\"award\":{\"exp\":{\"num\":5,\"type\":\"normal\"}},\"channel\":{\"apoker\":{\"exp\":{\"num\":5,\"type\":\"normal\"},\"grape\":{\"num\":20,\"type\":\"normal\"}},\"bpoker\":{\"exp\":{\"num\":5,\"type\":\"normal\"},\"grape\":{\"num\":30,\"type\":\"normal\"}}},\"condition\":[]}','0000-00-00 00:00:00','0000-00-00 00:00:00',1000,0,1,'',NULL,'N'),
	(2,10002,'分享给好友','share_friends',0,1,0,0,'0000-00-00 00:00:00','0000-00-00 00:00:00','Y','{\"award\":{\"grape\":{\"num\":2,\"type\":\"normal\"}},\"condition\":[]}','0000-00-00 00:00:00','0000-00-00 00:00:00',1000,0,1,'去分享','每日首次分享给好友或微信群即可获得','Y'),
	(3,10003,'关注公众号','follow_wechat_sub',1,1,0,0,'0000-00-00 00:00:00','0000-00-00 00:00:00','Y','{\"award\":{\"grape\":{\"num\":5,\"type\":\"normal\"}},\"condition\":[]}','0000-00-00 00:00:00','0000-00-00 00:00:00',1000,0,1,'去关注','关注【葡萄分享】公众号即可获得','Y'),
	(4,10004,'首次分享闲置','first_share_goods',1,1,0,0,'0000-00-00 00:00:00','0000-00-00 00:00:00','Y','{\"award\":{\"grape\":{\"num\":10,\"type\":\"normal\"}},\"condition\":[]}','0000-00-00 00:00:00','0000-00-00 00:00:00',1000,0,1,'去分享','首次完成分享闲置物品即可获得','Y'),
	(5,10005,'邀请新用户','invite_new_user',0,0,0,0,'0000-00-00 00:00:00','0000-00-00 00:00:00','Y','{\"award\":{\"exp\":{\"num\":5,\"type\":\"normal\"},\"grape\":{\"min\":3,\"max\":5,\"type\":\"rand\"}},\"condition\":[]}','0000-00-00 00:00:00','0000-00-00 00:00:00',1000,0,1,'去邀请','每邀请一个新用户即可获得','Y'),
	(6,10006,'每日群红包','everyday_group_redpacket',0,1,0,0,'0000-00-00 00:00:00','0000-00-00 00:00:00','N','{\"award\":{\"grape\":{\"min\":2,\"max\":20,\"type\":\"rand\"}},\"condition\":[]}','0000-00-00 00:00:00','0000-00-00 00:00:00',1000,0,1,'去分享','每天一个群红包，分享给好友即可获得','Y'),
	(7,10007,'完善资料','perfect_information',1,1,0,0,'0000-00-00 00:00:00','0000-00-00 00:00:00','Y','{\"award\":{\"grape\":{\"num\":10,\"type\":\"normal\"}},\"condition\":[]}','0000-00-00 00:00:00','0000-00-00 00:00:00',1000,0,1,'去完善','首次完整填写个人资料和标签即可获得','Y'),
	(8,10008,'粉丝红包','follower_redpacket',0,5,0,0,'0000-00-00 00:00:00','0000-00-00 00:00:00','Y','{\"award\":{\"grape\":{\"num\":1,\"type\":\"normal\"}},\"condition\":[]}','0000-00-00 00:00:00','0000-00-00 00:00:00',1000,0,1,'去关注','首次关注这些用户即可获得','Y'),
	(9,10009,'租用购买','renting_buying',0,0,0,0,'0000-00-00 00:00:00','0000-00-00 00:00:00','Y','{\"award\":{\"exp\":{\"num\":0.1,\"type\":\"rate\"}},\"condition\":[]}','0000-00-00 00:00:00','0000-00-00 00:00:00',1000,0,1,'','租用购买','N'),
	(10,10010,'分享闲置','share_goods',0,0,0,0,'0000-00-00 00:00:00','0000-00-00 00:00:00','Y','{\"award\":{\"exp\":{\"num\":0.05,\"type\":\"rate\"}},\"condition\":[]}','0000-00-00 00:00:00','0000-00-00 00:00:00',1000,0,1,'','完成分享闲置物品即可获得','N');

/*!40000 ALTER TABLE `task` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user
# ------------------------------------------------------------

CREATE TABLE `user` (
  `uid` int(10) unsigned NOT NULL COMMENT '葡萄号',
  `rid` varchar(50) NOT NULL DEFAULT '' COMMENT '关联ID',
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT 'openid',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '昵称',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
  `phone` varchar(30) NOT NULL DEFAULT '' COMMENT '手机号',
  `channel` varchar(20) NOT NULL DEFAULT '',
  `salt` char(6) NOT NULL DEFAULT '' COMMENT '加盐',
  `gender` enum('F','M','N') NOT NULL DEFAULT 'N' COMMENT '性别，F:女 M:男 N:未知',
  `city` varchar(50) NOT NULL DEFAULT '' COMMENT '城市',
  `province` varchar(50) NOT NULL DEFAULT '' COMMENT '省',
  `country` varchar(50) NOT NULL DEFAULT '' COMMENT '国家省',
  `product` varchar(20) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`),
  KEY `idx_modtime` (`modtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;

INSERT INTO `user` (`uid`, `rid`, `openid`, `nickname`, `avatar`, `phone`, `channel`, `salt`, `gender`, `city`, `province`, `country`, `product`, `addtime`, `modtime`)
VALUES
	(10000000,'','','葡萄精','','','','','F','','','','','0000-00-00 00:00:00','0000-00-00 00:00:00'),
	(20000000,'1','','猫小闲','/images/a5e78353bfefa801a0fc28ed01c64ca7.png','','','','F','','','','','2018-06-22 18:12:21','0000-00-00 00:00:00'),
	(20000019,'oFE7x1PshuPRzt33fXkzAqK5hJ9M','obTAJ42ZVCchXFLVY4uMXTdPvhtA','王绩超','/images/1d24dcf9be6631719d8cdf96c03f0334.jpg','18610384372','','135210','N','Haidian','Beijing','China','','2018-06-22 18:12:21','2018-06-30 18:13:02'),
	(20000020,'oFE7x1BoNRd6sAISeFm0g3-KCwgg','obTAJ46IVKOzzH39DJmxjzcAFDyc','Qinull','/images/6589fc916a241a939e0beb4eb000d491.jpg','','','908637','M','','Beijing','China','','2018-06-30 17:15:20','2018-06-30 18:13:03'),
	(20000021,'oFE7x1OX-xncpxz21r5Lsyx2MkSA','obTAJ48fGXwMak4xHF87eilIiq3U','谢冉','/images/61512ad69784dae9835f2266e203bd17.jpg','','','130684','M','Chaoyang','Beijing','China','','2018-07-01 09:55:57','2018-07-03 11:34:47'),
	(20000022,'oFE7x1IVF2HtLHr8wK7qlzJeK_c4','obTAJ466uTa1EwZu23PoBdHOx9NE','茶花菇凉🌛','/images/d970956a1d1c1dff71e4f1a832a4935a.jpg','','','708820','F','Xi\'an','Shaanxi','China','','2018-07-03 10:39:35','2018-07-03 11:34:48'),
	(20000023,'oFE7x1BPJKNsv7yrIohVsEe3BZU4','obTAJ467C9cqHK2tXe2DGLJEsPIk','孙晓萌','https://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83erzZYEiaK87u7HTo751qJBEtVpyYibSCC5MQhttiat1LIavCoa8m0Mck5FOoxkSR5guwDWx0jOicmv2ow/132','','','514306','F','Chaoyang','Beijing','China','','2018-07-03 14:55:11','2018-07-03 14:55:11'),
	(20000031,'oFE7x1A7rq2Bmls7nsVCjNZVESFQ','obTAJ41fEyfdmWUEhgoOzp8GL7wg','夏小兔','https://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83epsz571DFPzwUBchD0FsVtUYe64ZXicP8mPuglliaEicz7Rq71PMhSAZlezcm3HHXlFRstzWnGicYxnDQ/132','15210814721','bpoker','100980','F','Changping','Beijing','China','','2018-07-04 15:41:19','2018-07-27 17:30:08'),
	(20000032,'','openid1111','aaaaaa','avatar111','','','429621','F','city111','province111','country111','ptshare','2018-07-10 20:37:45','2018-07-10 20:50:20'),
	(20000045,'','0a0KWxKlKClGi5VYJStxGA==','1111','https://www.baidu.com','','','486218','F','','','','ptshare','2018-07-27 16:48:33','2018-07-27 16:48:33'),
	(20000046,'','obTAJ46WIUhVS5ldxIDD6cPhfZtU','1111','https://www.baidu.com','','','390200','F','','','','ptshare','2018-07-27 16:49:59','2018-07-27 16:49:59'),
	(20000047,'','oMN5V43wkb6CQr-rEj3bt8TyNodg','好望角','https://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83equKcia8uaEFYfDc3alunJ4CKGBoEpePl43R15ias6PiaJ6K5FOzxSsuzQunfM8AwIhukgHKZTLTsdrw/132','','','913409','M','Chaoyang','Beijing','China','ptshare','2018-07-27 20:14:31','2018-07-27 20:14:31'),
	(20000049,'','oMN5V4xatxRxY3sor_MgI0h4rSCo','夏小兔','https://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83epibNpKIwo4zFRTw8HgMo66cJOjfklNREWuE3zM48KBYpKOdLoAgAgOgOu5Ye6g16uJv6o7k2IPnWg/132','15210814721','','827460','N','Changping','Beijing','China','ptshare','2018-07-28 10:24:42','2018-08-01 16:06:16'),
	(20000050,'','oMN5V4_53p6nhsaqKBXqNjQ6bNP4','刘晨光','https://wx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTJLrmxaUI7a4htFRU5xtS4oia39ialfJ1ubpwwxyDbxxEErSmXhqsKySVficSm5eb1Ca8zQ5NevqicCsw/132','13691544388','','721004','N','Haidian','Beijing','China','ptshare','2018-07-28 14:01:11','2018-08-01 15:08:36'),
	(20000060,'','oMN5V4_JKpE3KeZqtF3pml7Of1IQ','杨青','https://wx.qlogo.cn/mmopen/vi_32/YD1RqdHwfuPYCIvjkwx1Uc1exLpUiaib8f4NB0fhZ3PdZAlNN4ARksvLCiaXG77nmZsbg1jtdibI3j9EJbNPZF16cw/132','','','132971','M','Haidian','Beijing','China','ptshare','2018-07-30 19:20:55','2018-07-30 19:20:55'),
	(20000061,'','oMN5V4_kC_4WOjMaG9DvxdUe4mJU','Stanley Wang','https://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83epUQyuEcByZDZ9346zRhzfKIzDX1TAppAoO11jbhHOa8FZLphwzAXQTo7C7NoXPHxwVRHmazzJ5XQ/132','','','454291','M','Chaoyang','Beijing','China','ptshare','2018-07-31 20:26:15','2018-07-31 20:26:15'),
	(20000062,'','oMN5V439TO3EqGmLTwJKungXWQss','張小贝💋','https://wx.qlogo.cn/mmopen/vi_32/PiajxSqBRaEKLhiaxdDbuia7g5f3bJVGNkNuex66XUTtBRQjdeN2ialcpbFyibCTicbM1KqiaAE7jhbKuNzKNdOugP2eQ/132','','','729034','F','','','','ptshare','2018-07-31 20:34:33','2018-07-31 20:34:33'),
	(20000071,'','oXP8t1evchPm4ZEpl_KPKdvEaQWc','夕阳照我去战斗','http://thirdwx.qlogo.cn/mmopen/vi_32/DYAIOgq83eqEmKJnflicx19sL2owDApxxUia0ameO0dlhd5X7lhs3ErTYUj3bykDFTiaHak6j1YNdyL1pFiaXzyqGw/132','','','964123','M','','','','ptshare','2018-08-02 11:16:14','2018-08-02 11:16:14'),
	(20000072,'','oXP8t1Uf6dG655Q_6AZ73yrHjmGM','王绩超','http://thirdwx.qlogo.cn/mmopen/vi_32/PiajxSqBRaELJq21ZTZdafkLk4l95GJicLUllCWaDhAIBPmDcRU0eNiaffEIxSvvDVCBZTObfuycoEqsx7lsKvQLA/132','','','687259','M','','','','ptshare','2018-08-07 20:42:25','2018-08-07 20:42:25'),
	(20000073,'','oXP8t1WwAbdfUytR84uYmybYHcRE','肖杰','http://thirdwx.qlogo.cn/mmopen/vi_32/kenDKAoibFRj7BJ7AVMHkScF9bicptWQoTHOI1pvbkkRYR5cV4rGbSg8HwRGdsba6g8Htr2IjEicibPaUljw3syibuw/132','','','839200','F','','','','ptshare','2018-08-07 20:55:47','2018-08-07 20:55:47'),
	(20000074,'','oXP8t1ek3-4Po1pb08EfSKoxgS1g','梁原','http://thirdwx.qlogo.cn/mmopen/vi_32/HY7jNvicCU0oiaX4WIeO0TPfyzUR7vibUBz69iaVbaibGHaeWsHNUpLky3HGVialXX76cUiacHEGm9OUeDD9zmphxQuxQ/132','','','255164','M','','','','ptshare','2018-08-07 22:31:31','2018-08-07 22:31:31'),
	(20000075,'','oMN5V45Obz5Gk5nAGV-wY6MnqMOg','脸滚键盘','https://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83erahpokyosoS1EvmbQ7l879AjiaWWsBI02DkRROsicjNmwKzPBAGorPm2b7T19uOJl5Qg6CnBVkNNTA/132','','','188122','M','Xinyang','Henan','China','ptshare','2018-08-16 11:13:05','2018-08-16 11:13:05'),
	(20000076,'','oMN5V45pTbtDip8fUTgtNyqzEi6M','曾强','https://wx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTI8k7Z3HGic6MDNav97LOyAibGxbEU6YBLfqsFp8DGnoZZDbKNAop8uIMpIXB0ooiciamdCGoa2sjc3dA/132','','','482376','M','Chaoyang','Beijing','China','ptshare','2018-08-16 18:55:01','2018-08-16 18:55:01'),
	(20000077,'','oMN5V4xx6DSaXJfWd0mncJfAzvG0','胡继波','https://wx.qlogo.cn/mmopen/vi_32/PiajxSqBRaEKDY3fOZsqcujuv7phmHnVQJcFDMX2Xcxo9RbOibEujicwV383GBJbM2W0ln1G3scgIQGTQP42Nu4kg/132','','','899619','M','Chengdu','Sichuan','China','ptshare','2018-08-16 19:04:13','2018-08-16 19:04:13'),
	(20000078,'','oMN5V4wLd97vrRnhPmuAc-kKCBd8','孙晓萌','https://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83ep9sRR1suQumHMbGkAtXEcicgGWqxfKEE4JbhtAGB6NBjFpIUDlDg70Fm88KyMIk0Kc8HqeY0QmkFw/132','','','693794','F','Chaoyang','Beijing','China','ptshare','2018-08-16 19:04:24','2018-08-16 19:04:24'),
	(20000079,'','oMN5V4xLoPYtpgmhGEGTXnl-gRJQ','茶花菇凉🌛','https://wx.qlogo.cn/mmopen/vi_32/V8oDkDQoExTc2a6YPmgESmkajQicDIBI2cKYxGKQT48iaR8oDsFjV1LpJV8ATLjB12CWLTLGhGzgJiaEat88EHaLg/132','','','799128','F','Xi\'an','Shaanxi','China','ptshare','2018-08-16 19:57:17','2018-08-16 19:57:17'),
	(21000202,'oFE7x1MVMAgdVrY6pUH8LNJtM2O0','obTAJ49QyYlrtoznNj6lKgNJi9J0','張小贝💋','/images/64cf96a81ecb149346fba40bad80d97c.jpg','18811474202','','687437','N','','','','','2018-05-24 17:47:04','2018-06-30 18:13:04'),
	(21000203,'oFE7x1OzH0yTT40Gw29CPdg2YrNo','obTAJ472Dar4VcWQyO75WoaLY_RU','好望角','/images/c2b0a5973a17fb02bb91b8b9a4e9ada5.jpg','13701383184','','334003','M','Chaoyang','Beijing','China','','2018-05-24 17:48:13','2018-06-30 18:13:04'),
	(21000205,'oFE7x1A7rq2Bmls7nsVCjNZVESFQ1111','obTAJ41fEyfdmWUEhgoOzp8GL7wg','夏小兔1111','https://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83epsz571DFPzwUBchD0FsVtUYe64ZXicP8mPuglliaEicz7Rq71PMhSAZlezcm3HHXlFRstzWnGicYxnDQ/132','','bpoker','100980','F','Changping','Beijing','China','','2018-07-04 15:41:19','2018-07-04 15:41:19'),
	(21000207,'oFE7x1JzRYhfXaWwLz79SCP7urVQ','obTAJ48WVQSXcDck2FUOmnJanf5E','兰э','/images/25427de3d2f8dd610357941df3a26323.jpg','','','327240','F','','','Andorra','','2018-05-24 18:29:05','2018-06-30 18:13:06'),
	(21000208,'oFE7x1OP-oAqgcIq3mHWfuHtpYzk','obTAJ46fFDJEhNc3o3b_L8PUGDZ4','張昊','/images/e50221672cfc67887af9042331c8c632.jpg','','','273565','M','','','Bhutan','','2018-05-24 20:31:24','2018-06-30 18:13:07'),
	(21000209,'oFE7x1Kkcj-Mi9xwh4Xn6Tv72Es4','obTAJ4yhMSnVAYhqPxG_-8z06eOM','曾强','/images/73fd6477587a059bb1486553213c7a30.jpg','','','361770','M','Chaoyang','Beijing','China','','2018-05-24 21:20:18','2018-06-30 18:13:07'),
	(21000210,'oFE7x1PkwigU770ll1jsfC-pTW8M','obTAJ40HtkJGhsm0wBZbWVSTtkLE','末小山','/images/de2a01b978b8d2353ab3db73b21dd240.jpg','','','121017','M','','','Iceland','','2018-05-24 21:31:36','2018-06-30 18:13:08'),
	(21000211,'oFE7x1BiTPBrDB0VnssSu73cKMKU','obTAJ43qqJNhlv1LY9pU_9ZBde5Y','小甜饼','/images/c4ab3059c2993084da36eb1bb5face28.jpg','','','856232','F','Yantai','Shandong','China','','2018-05-24 21:56:47','2018-06-30 18:13:08'),
	(21000212,'oFE7x1CFkoycweVERR5KV6n5196c','obTAJ4xBYj1fuW5a-x5U73tST_gI','慕非','/images/343c1999d12cbe090c9765ed5041c81e.jpg','','','602517','M','','','Jersey','','2018-05-24 21:58:27','2018-06-30 18:13:08'),
	(21000213,'oFE7x1C9zs1tW1qYQ3Qv642M9aZc','obTAJ45zW8mwr39i7ZnQjwmeTdfI','爱多','/images/644b6f103aa09d7d754bf3891e020fd0.jpg','','','503730','F','','Rouen','France','','2018-05-24 22:01:03','2018-06-30 18:13:09'),
	(21000214,'oFE7x1AHpoaD0Hi__ceO9Hi7hq2w','obTAJ476HA5E2HpZjlpdA-6QWuqo','双木','/images/019b81a0b9854b91c210e9d4c42807e1.jpg','','','162530','M','East','Beijing','China','','2018-05-24 22:10:29','2018-06-30 18:13:09'),
	(21000215,'oFE7x1NDs4G8VLwC6vH3mngJ3kbA','obTAJ41kfkNR-wCqtyPTV-9ZZDoE','曾子博','/images/3a7ef060c73045c87d2aeb82da3bcd49.jpg','','','357355','M','Leshan','Sichuan','China','','2018-05-25 06:52:49','2018-06-30 18:13:10'),
	(21000216,'oFE7x1K9wLQxAQ--YsZp6sjk1vbU','obTAJ4-921rmac-UysZAd_ix8_bo','Stanley Wang','/images/82071caf55b48ebf651ea52a8f0c1462.jpg','18614071125','','333743','M','Chaoyang','Beijing','China','','2018-05-25 10:47:36','2018-06-30 18:13:10'),
	(21000217,'oFE7x1GDT8uIY9ZdV2jmGx-iVukI','obTAJ41aoXyjfDnRBcjU9Rg68J3U','刘晨光','/images/2867629c5fbb70d9f7ac8cd1b77384f6.jpg','13691544388','','325259','M','Haidian','Beijing','China','','2018-05-25 10:55:56','2018-06-30 18:13:11'),
	(21000218,'oFE7x1Bom2uFSRwtcKgLTsBYyrUw','obTAJ49jRLHig0sLjL7tzLg8FAqA','高兴','/images/73823834ee87b5f488fd24d8219fe01b.jpg','','','377147','M','','Beijing','China','','2018-05-25 11:40:45','2018-06-30 18:13:11'),
	(21000219,'oFE7x1MSR3Oz-1Z8TE1SpEJNuiQs','obTAJ440TYNyO7oKpSLGuFvePGb8','一丁','/images/55150fbb5b4a4bb141ee44c37d5db7e2.jpg','','','800193','M','Chaoyang','Beijing','China','','2018-05-25 13:41:50','2018-06-30 18:13:12'),
	(21000220,'oFE7x1FXQfY-FJiobgXRCYjwLMQY','obTAJ4yWwESgCiwBtjJOAnorqKpQ','独秀一枝梅','/images/c75a345e579b93b972aa31a4cd6f14b2.jpg','','','461094','F','Leshan','Sichuan','China','','2018-05-25 21:30:21','2018-06-30 18:13:12'),
	(21000221,'oFE7x1D5nVgx0IrlkafAyeZtNxi0','obTAJ4-HQdFpNoWnynBoN5iC-KNE','落少','/images/70ece52981f3a7ebd4b744cdfc3f46b7.jpg','','','819219','F','Chaoyang','Beijing','China','','2018-06-01 11:16:17','2018-06-30 18:13:12'),
	(21000222,'oFE7x1IFbs_r3-34sGC-kMQnUH0k','obTAJ47lEQOIqO87etNijXnbWKQU','马原stephanie。','/images/6fb4c067d7e7d5370a122d29074f9f25.jpg','','','743608','F','New York','New York','United States','','2018-06-03 17:43:26','2018-06-30 18:13:13'),
	(21000223,'oFE7x1JUB21d8PrM6ptGsqhyFToo','obTAJ4yTwPf-TOs_UUHRAXxRemB4','酷壹柒','/images/1a1f1e299b966223c1588c18ab016e06.jpg','','','262230','F','','','China','','2018-06-03 23:38:31','2018-06-30 18:13:13'),
	(21000224,'oFE7x1GYo0pXS0V7Y46kjMw1l9Zo','obTAJ427pTXNMDVqrE75S_we4Anw','喵米','/images/89f687e09a86d1dfaa1c0c530aaad5f2.jpg','','','485220','F','','','Aruba','','2018-06-04 16:31:58','2018-06-30 18:13:14'),
	(21000225,'oFE7x1D-y-IQp_mYnyK96n8YZtHg','obTAJ44D7AXDe_r-f0KYTQLsmtkE','李嘻嘻…','/images/8397bba92599d9448f6f917a7a9db94b.jpg','','','763558','M','Chaoyang','Beijing','China','','2018-06-04 16:31:58','2018-06-30 18:13:14'),
	(21000226,'oFE7x1BsjQBd8A6zOam_T8U3Lezc','obTAJ44ot7kbGhgFjkEGR-VlKYHw','摆渡人','/images/d8a228bf5b5b88b457ef019164d12c95.jpg','','','669941','M','Chaoyang','Beijing','China','','2018-06-04 22:22:52','2018-06-30 18:13:15'),
	(21000331,'21000331','','异道大叔','/images/cbd52113d21ad05a21ac3b185c3efa1e.jpg','','','','F','','','China','','2018-06-20 21:04:59','2018-06-20 21:04:59'),
	(21000332,'21000332','','혼란마음','/images/54987fcce992e0e642a304f35583dd16.jpg','','','','F','','','China','','2018-06-20 21:04:59','2018-06-20 21:04:59'),
	(21000333,'21000333','','我不是马里奥','/images/37727525cf21748c64aa27fa2a001e43.jpg','','','','F','','','China','','2018-06-20 21:04:59','2018-06-20 21:04:59'),
	(21000334,'21000334','','兵长两米八','/images/76dbb71a3d0dfba1804c17d4d7885929.jpg','','','','F','','','China','','2018-06-20 21:04:59','2018-06-20 21:04:59'),
	(21000335,'21000335','','咪咕爸爸','/images/36cd216d6c5a8470df584aacebd6ed66.jpg','','','','F','','','China','','2018-06-20 21:04:59','2018-06-20 21:04:59'),
	(21000336,'21000336','','九尾狐大人','/images/39608c76bafb5a4904f18bd58b992776.jpg','','','','F','','','China','','2018-06-20 21:04:59','2018-06-20 21:04:59'),
	(21000337,'21000337','','ffffffox','/images/f5720e2daf1958b14f59fc39b6d607c3.png','','','','F','','','China','','2018-06-20 21:04:59','2018-06-20 21:04:59'),
	(21000338,'21000338','','EdisonQ','/images/9d4136784dc0a52777e718eb0cc8dcf5.jpg','','','','M','','','China','','2018-06-20 21:04:59','2018-06-20 21:04:59'),
	(21000339,'21000339','','草莓公主就是我','/images/5eeea33be37d51583b20e40e5e927375.jpg','','','','F','','','China','','2018-06-20 21:04:59','2018-06-20 21:04:59'),
	(21000340,'21000340','','小傻瓜.YYG','/images/682013a8bf161e38f5624209097d38f6.jpg','','','','F','','','China','','2018-06-20 21:04:59','2018-06-20 21:04:59');

/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_exp_0
# ------------------------------------------------------------

CREATE TABLE `user_exp_0` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_exp_0` WRITE;
/*!40000 ALTER TABLE `user_exp_0` DISABLE KEYS */;

INSERT INTO `user_exp_0` (`uid`, `exp`, `addtime`)
VALUES
	(10000000,3,'2018-07-13 17:08:39');

/*!40000 ALTER TABLE `user_exp_0` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_exp_1
# ------------------------------------------------------------

CREATE TABLE `user_exp_1` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_exp_1` WRITE;
/*!40000 ALTER TABLE `user_exp_1` DISABLE KEYS */;

INSERT INTO `user_exp_1` (`uid`, `exp`, `addtime`)
VALUES
	(20000001,1111,'0000-00-00 00:00:00');

/*!40000 ALTER TABLE `user_exp_1` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_exp_10
# ------------------------------------------------------------

CREATE TABLE `user_exp_10` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_11
# ------------------------------------------------------------

CREATE TABLE `user_exp_11` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_12
# ------------------------------------------------------------

CREATE TABLE `user_exp_12` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_13
# ------------------------------------------------------------

CREATE TABLE `user_exp_13` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_14
# ------------------------------------------------------------

CREATE TABLE `user_exp_14` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_exp_14` WRITE;
/*!40000 ALTER TABLE `user_exp_14` DISABLE KEYS */;

INSERT INTO `user_exp_14` (`uid`, `exp`, `addtime`)
VALUES
	(21000214,1,'2018-07-02 17:04:49');

/*!40000 ALTER TABLE `user_exp_14` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_exp_15
# ------------------------------------------------------------

CREATE TABLE `user_exp_15` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_16
# ------------------------------------------------------------

CREATE TABLE `user_exp_16` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_exp_16` WRITE;
/*!40000 ALTER TABLE `user_exp_16` DISABLE KEYS */;

INSERT INTO `user_exp_16` (`uid`, `exp`, `addtime`)
VALUES
	(20000016,5,'2018-05-29 21:24:14'),
	(21000216,14,'2018-07-03 11:35:14');

/*!40000 ALTER TABLE `user_exp_16` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_exp_17
# ------------------------------------------------------------

CREATE TABLE `user_exp_17` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_exp_17` WRITE;
/*!40000 ALTER TABLE `user_exp_17` DISABLE KEYS */;

INSERT INTO `user_exp_17` (`uid`, `exp`, `addtime`)
VALUES
	(20000017,5,'2018-05-30 10:20:10'),
	(21000217,136,'2018-06-21 16:09:15');

/*!40000 ALTER TABLE `user_exp_17` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_exp_18
# ------------------------------------------------------------

CREATE TABLE `user_exp_18` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_exp_18` WRITE;
/*!40000 ALTER TABLE `user_exp_18` DISABLE KEYS */;

INSERT INTO `user_exp_18` (`uid`, `exp`, `addtime`)
VALUES
	(20000018,5,'2018-05-30 11:05:57'),
	(21000218,5,'2018-06-14 17:30:18');

/*!40000 ALTER TABLE `user_exp_18` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_exp_19
# ------------------------------------------------------------

CREATE TABLE `user_exp_19` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_exp_19` WRITE;
/*!40000 ALTER TABLE `user_exp_19` DISABLE KEYS */;

INSERT INTO `user_exp_19` (`uid`, `exp`, `addtime`)
VALUES
	(20000019,10,'2018-06-22 18:12:21');

/*!40000 ALTER TABLE `user_exp_19` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_exp_2
# ------------------------------------------------------------

CREATE TABLE `user_exp_2` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_exp_2` WRITE;
/*!40000 ALTER TABLE `user_exp_2` DISABLE KEYS */;

INSERT INTO `user_exp_2` (`uid`, `exp`, `addtime`)
VALUES
	(21000202,5,'2018-06-23 13:34:05');

/*!40000 ALTER TABLE `user_exp_2` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_exp_20
# ------------------------------------------------------------

CREATE TABLE `user_exp_20` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_exp_20` WRITE;
/*!40000 ALTER TABLE `user_exp_20` DISABLE KEYS */;

INSERT INTO `user_exp_20` (`uid`, `exp`, `addtime`)
VALUES
	(20000020,10,'2018-06-30 17:15:20');

/*!40000 ALTER TABLE `user_exp_20` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_exp_21
# ------------------------------------------------------------

CREATE TABLE `user_exp_21` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_exp_21` WRITE;
/*!40000 ALTER TABLE `user_exp_21` DISABLE KEYS */;

INSERT INTO `user_exp_21` (`uid`, `exp`, `addtime`)
VALUES
	(20000021,5,'2018-07-01 09:55:57');

/*!40000 ALTER TABLE `user_exp_21` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_exp_22
# ------------------------------------------------------------

CREATE TABLE `user_exp_22` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_exp_22` WRITE;
/*!40000 ALTER TABLE `user_exp_22` DISABLE KEYS */;

INSERT INTO `user_exp_22` (`uid`, `exp`, `addtime`)
VALUES
	(20000022,207,'2018-07-03 10:39:35');

/*!40000 ALTER TABLE `user_exp_22` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_exp_23
# ------------------------------------------------------------

CREATE TABLE `user_exp_23` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_exp_23` WRITE;
/*!40000 ALTER TABLE `user_exp_23` DISABLE KEYS */;

INSERT INTO `user_exp_23` (`uid`, `exp`, `addtime`)
VALUES
	(20000023,30,'2018-07-03 14:55:11');

/*!40000 ALTER TABLE `user_exp_23` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_exp_24
# ------------------------------------------------------------

CREATE TABLE `user_exp_24` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_exp_24` WRITE;
/*!40000 ALTER TABLE `user_exp_24` DISABLE KEYS */;

INSERT INTO `user_exp_24` (`uid`, `exp`, `addtime`)
VALUES
	(20000024,5,'2018-07-03 19:28:23'),
	(21000224,27,'2018-06-23 13:53:04');

/*!40000 ALTER TABLE `user_exp_24` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_exp_25
# ------------------------------------------------------------

CREATE TABLE `user_exp_25` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_exp_25` WRITE;
/*!40000 ALTER TABLE `user_exp_25` DISABLE KEYS */;

INSERT INTO `user_exp_25` (`uid`, `exp`, `addtime`)
VALUES
	(20000025,5,'2018-07-03 19:36:28'),
	(21000225,261,'2018-06-14 19:31:01');

/*!40000 ALTER TABLE `user_exp_25` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_exp_26
# ------------------------------------------------------------

CREATE TABLE `user_exp_26` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_exp_26` WRITE;
/*!40000 ALTER TABLE `user_exp_26` DISABLE KEYS */;

INSERT INTO `user_exp_26` (`uid`, `exp`, `addtime`)
VALUES
	(20000026,5,'2018-07-03 19:37:55');

/*!40000 ALTER TABLE `user_exp_26` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_exp_27
# ------------------------------------------------------------

CREATE TABLE `user_exp_27` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_exp_27` WRITE;
/*!40000 ALTER TABLE `user_exp_27` DISABLE KEYS */;

INSERT INTO `user_exp_27` (`uid`, `exp`, `addtime`)
VALUES
	(20000027,5,'2018-07-03 19:43:23');

/*!40000 ALTER TABLE `user_exp_27` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_exp_28
# ------------------------------------------------------------

CREATE TABLE `user_exp_28` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_29
# ------------------------------------------------------------

CREATE TABLE `user_exp_29` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_exp_29` WRITE;
/*!40000 ALTER TABLE `user_exp_29` DISABLE KEYS */;

INSERT INTO `user_exp_29` (`uid`, `exp`, `addtime`)
VALUES
	(20000029,5,'2018-07-03 19:53:51');

/*!40000 ALTER TABLE `user_exp_29` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_exp_3
# ------------------------------------------------------------

CREATE TABLE `user_exp_3` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_exp_3` WRITE;
/*!40000 ALTER TABLE `user_exp_3` DISABLE KEYS */;

INSERT INTO `user_exp_3` (`uid`, `exp`, `addtime`)
VALUES
	(21000203,1090,'2018-06-30 17:30:43');

/*!40000 ALTER TABLE `user_exp_3` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_exp_30
# ------------------------------------------------------------

CREATE TABLE `user_exp_30` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_exp_30` WRITE;
/*!40000 ALTER TABLE `user_exp_30` DISABLE KEYS */;

INSERT INTO `user_exp_30` (`uid`, `exp`, `addtime`)
VALUES
	(20000030,5,'2018-07-04 15:10:43');

/*!40000 ALTER TABLE `user_exp_30` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_exp_31
# ------------------------------------------------------------

CREATE TABLE `user_exp_31` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_exp_31` WRITE;
/*!40000 ALTER TABLE `user_exp_31` DISABLE KEYS */;

INSERT INTO `user_exp_31` (`uid`, `exp`, `addtime`)
VALUES
	(20000031,16,'2018-07-04 15:41:19');

/*!40000 ALTER TABLE `user_exp_31` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_exp_32
# ------------------------------------------------------------

CREATE TABLE `user_exp_32` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_33
# ------------------------------------------------------------

CREATE TABLE `user_exp_33` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_34
# ------------------------------------------------------------

CREATE TABLE `user_exp_34` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_35
# ------------------------------------------------------------

CREATE TABLE `user_exp_35` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_36
# ------------------------------------------------------------

CREATE TABLE `user_exp_36` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_37
# ------------------------------------------------------------

CREATE TABLE `user_exp_37` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_38
# ------------------------------------------------------------

CREATE TABLE `user_exp_38` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_39
# ------------------------------------------------------------

CREATE TABLE `user_exp_39` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_4
# ------------------------------------------------------------

CREATE TABLE `user_exp_4` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_40
# ------------------------------------------------------------

CREATE TABLE `user_exp_40` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_41
# ------------------------------------------------------------

CREATE TABLE `user_exp_41` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_42
# ------------------------------------------------------------

CREATE TABLE `user_exp_42` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_43
# ------------------------------------------------------------

CREATE TABLE `user_exp_43` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_44
# ------------------------------------------------------------

CREATE TABLE `user_exp_44` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_45
# ------------------------------------------------------------

CREATE TABLE `user_exp_45` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_exp_45` WRITE;
/*!40000 ALTER TABLE `user_exp_45` DISABLE KEYS */;

INSERT INTO `user_exp_45` (`uid`, `exp`, `addtime`)
VALUES
	(21000345,129,'2018-05-08 20:50:32');

/*!40000 ALTER TABLE `user_exp_45` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_exp_46
# ------------------------------------------------------------

CREATE TABLE `user_exp_46` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_47
# ------------------------------------------------------------

CREATE TABLE `user_exp_47` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_48
# ------------------------------------------------------------

CREATE TABLE `user_exp_48` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_49
# ------------------------------------------------------------

CREATE TABLE `user_exp_49` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_5
# ------------------------------------------------------------

CREATE TABLE `user_exp_5` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_exp_5` WRITE;
/*!40000 ALTER TABLE `user_exp_5` DISABLE KEYS */;

INSERT INTO `user_exp_5` (`uid`, `exp`, `addtime`)
VALUES
	(21000205,613,'2018-06-14 20:05:32');

/*!40000 ALTER TABLE `user_exp_5` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_exp_50
# ------------------------------------------------------------

CREATE TABLE `user_exp_50` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_51
# ------------------------------------------------------------

CREATE TABLE `user_exp_51` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_52
# ------------------------------------------------------------

CREATE TABLE `user_exp_52` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_53
# ------------------------------------------------------------

CREATE TABLE `user_exp_53` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_54
# ------------------------------------------------------------

CREATE TABLE `user_exp_54` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_55
# ------------------------------------------------------------

CREATE TABLE `user_exp_55` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_56
# ------------------------------------------------------------

CREATE TABLE `user_exp_56` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_57
# ------------------------------------------------------------

CREATE TABLE `user_exp_57` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_58
# ------------------------------------------------------------

CREATE TABLE `user_exp_58` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_59
# ------------------------------------------------------------

CREATE TABLE `user_exp_59` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_6
# ------------------------------------------------------------

CREATE TABLE `user_exp_6` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_exp_6` WRITE;
/*!40000 ALTER TABLE `user_exp_6` DISABLE KEYS */;

INSERT INTO `user_exp_6` (`uid`, `exp`, `addtime`)
VALUES
	(21000206,217,'2018-06-21 11:28:01');

/*!40000 ALTER TABLE `user_exp_6` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_exp_60
# ------------------------------------------------------------

CREATE TABLE `user_exp_60` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_61
# ------------------------------------------------------------

CREATE TABLE `user_exp_61` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_62
# ------------------------------------------------------------

CREATE TABLE `user_exp_62` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_63
# ------------------------------------------------------------

CREATE TABLE `user_exp_63` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_64
# ------------------------------------------------------------

CREATE TABLE `user_exp_64` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_65
# ------------------------------------------------------------

CREATE TABLE `user_exp_65` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_66
# ------------------------------------------------------------

CREATE TABLE `user_exp_66` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_67
# ------------------------------------------------------------

CREATE TABLE `user_exp_67` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_68
# ------------------------------------------------------------

CREATE TABLE `user_exp_68` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_69
# ------------------------------------------------------------

CREATE TABLE `user_exp_69` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_7
# ------------------------------------------------------------

CREATE TABLE `user_exp_7` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_70
# ------------------------------------------------------------

CREATE TABLE `user_exp_70` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_71
# ------------------------------------------------------------

CREATE TABLE `user_exp_71` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_72
# ------------------------------------------------------------

CREATE TABLE `user_exp_72` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_73
# ------------------------------------------------------------

CREATE TABLE `user_exp_73` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_74
# ------------------------------------------------------------

CREATE TABLE `user_exp_74` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_75
# ------------------------------------------------------------

CREATE TABLE `user_exp_75` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_76
# ------------------------------------------------------------

CREATE TABLE `user_exp_76` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_77
# ------------------------------------------------------------

CREATE TABLE `user_exp_77` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_78
# ------------------------------------------------------------

CREATE TABLE `user_exp_78` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_79
# ------------------------------------------------------------

CREATE TABLE `user_exp_79` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_8
# ------------------------------------------------------------

CREATE TABLE `user_exp_8` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_80
# ------------------------------------------------------------

CREATE TABLE `user_exp_80` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_81
# ------------------------------------------------------------

CREATE TABLE `user_exp_81` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_82
# ------------------------------------------------------------

CREATE TABLE `user_exp_82` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_83
# ------------------------------------------------------------

CREATE TABLE `user_exp_83` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_84
# ------------------------------------------------------------

CREATE TABLE `user_exp_84` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_85
# ------------------------------------------------------------

CREATE TABLE `user_exp_85` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_86
# ------------------------------------------------------------

CREATE TABLE `user_exp_86` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_87
# ------------------------------------------------------------

CREATE TABLE `user_exp_87` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_88
# ------------------------------------------------------------

CREATE TABLE `user_exp_88` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_89
# ------------------------------------------------------------

CREATE TABLE `user_exp_89` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_9
# ------------------------------------------------------------

CREATE TABLE `user_exp_9` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_exp_9` WRITE;
/*!40000 ALTER TABLE `user_exp_9` DISABLE KEYS */;

INSERT INTO `user_exp_9` (`uid`, `exp`, `addtime`)
VALUES
	(21000209,6,'2018-06-22 18:43:48');

/*!40000 ALTER TABLE `user_exp_9` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_exp_90
# ------------------------------------------------------------

CREATE TABLE `user_exp_90` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_91
# ------------------------------------------------------------

CREATE TABLE `user_exp_91` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_92
# ------------------------------------------------------------

CREATE TABLE `user_exp_92` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_93
# ------------------------------------------------------------

CREATE TABLE `user_exp_93` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_94
# ------------------------------------------------------------

CREATE TABLE `user_exp_94` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_95
# ------------------------------------------------------------

CREATE TABLE `user_exp_95` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_96
# ------------------------------------------------------------

CREATE TABLE `user_exp_96` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_97
# ------------------------------------------------------------

CREATE TABLE `user_exp_97` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_98
# ------------------------------------------------------------

CREATE TABLE `user_exp_98` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_exp_99
# ------------------------------------------------------------

CREATE TABLE `user_exp_99` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `exp` bigint(20) unsigned NOT NULL DEFAULT '0',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_forms
# ------------------------------------------------------------

CREATE TABLE `user_forms` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `formid` varchar(50) NOT NULL DEFAULT '' COMMENT '小程序formid',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `endtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '过期时间',
  PRIMARY KEY (`id`),
  KEY `uid&endtime` (`uid`,`endtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_forms` WRITE;
/*!40000 ALTER TABLE `user_forms` DISABLE KEYS */;

INSERT INTO `user_forms` (`id`, `uid`, `formid`, `addtime`, `endtime`)
VALUES
	(1,20000031,'812a9647e54c57cdcea4d248b22d9c87','2018-07-13 16:07:26','2018-07-20 16:07:26'),
	(2,20000031,'wx16172149079718ef90a3cb391192487620','2018-07-16 17:22:31','2018-07-23 17:22:31'),
	(3,20000031,'wx16172329441184a5287a56da2711961770','2018-07-16 17:23:46','2018-07-23 17:23:46'),
	(4,20000031,'761f46fe9792b98cf46864e129d362d5','2018-07-16 20:12:29','2018-07-23 20:12:29'),
	(5,20000031,'a99fb3400bc14303a8dc30e47c5faa93','2018-07-16 20:13:48','2018-07-23 20:13:48'),
	(6,20000031,'54a323bab8187c4b94c2c399eac461f2','2018-07-16 20:13:53','2018-07-23 20:13:53'),
	(7,20000031,'48fcfb8527a20ebba341174898d09ab4','2018-07-16 20:24:30','2018-07-23 20:24:30'),
	(8,20000031,'3389d66fa70eee11ea939dc4481368d0','2018-07-16 20:29:47','2018-07-23 20:29:47'),
	(9,20000031,'2303fbfc1474434a73f47fc845388576','2018-07-16 20:30:00','2018-07-23 20:30:00'),
	(10,20000031,'a489310354af322a663c3d6617f6109d','2018-07-16 20:30:36','2018-07-23 20:30:36'),
	(11,20000031,'4d42994888a8af30a6a2845e99c30604','2018-07-16 20:32:23','2018-07-23 20:32:23'),
	(12,21000203,'wx17154947510167fcffe3c04d1395170608','2018-07-17 15:50:13','2018-07-24 15:50:13'),
	(13,20000031,'wx171554523056775f4c49337c3925191953','2018-07-17 15:55:11','2018-07-24 15:55:11'),
	(14,20000031,'wx17162543228762cd6ae9bd861858490789','2018-07-17 16:26:06','2018-07-24 16:26:06'),
	(15,20000031,'2d3e40fbc5967c207caa6fb89499d734','2018-07-17 16:27:27','2018-07-24 16:27:27'),
	(16,20000031,'a53d60a92791992babeafb1c31ff175f','2018-07-17 16:27:32','2018-07-24 16:27:32'),
	(17,20000031,'8cc7a545fdcb899b1aa03ae6bfc2b852','2018-07-17 16:33:43','2018-07-24 16:33:43'),
	(18,20000031,'acaf497d3a2ff9162e1fec039f9d5537','2018-07-17 16:33:46','2018-07-24 16:33:46'),
	(19,21000203,'wx17165844474185b7855e45403709769676','2018-07-17 16:59:13','2018-07-24 16:59:13'),
	(20,20000031,'f85821967312351a88079aae1e174276','2018-07-17 18:47:21','2018-07-24 18:47:21'),
	(21,20000031,'fc4925091c0c0210d62e7e134b3baa7b','2018-07-17 18:47:53','2018-07-24 18:47:53'),
	(22,21000206,'1531824679867','2018-07-17 18:51:19','2018-07-24 18:51:19'),
	(23,21000206,'wx17185118912018b5b8663dfa0957630466','2018-07-17 18:51:33','2018-07-24 18:51:33'),
	(24,21000206,'wx1718511968383945bfe0769b0655517219','2018-07-17 18:51:47','2018-07-24 18:51:47'),
	(25,20000031,'c984493c6d467e508e0f81e6e249c5b0','2018-07-17 19:42:47','2018-07-24 19:42:47'),
	(26,20000031,'ea4098bb24b97e7030f04c14cfc8cfcb','2018-07-17 19:43:01','2018-07-24 19:43:01'),
	(27,20000031,'ee6d3aac169a62b7bc9fa429f824ed23','2018-07-18 10:41:23','2018-07-25 10:41:23'),
	(28,20000031,'3877f97cb43f1afd4be8af8d2c3b36bf','2018-07-18 10:41:26','2018-07-25 10:41:26'),
	(29,20000031,'f01f6aa85ddb3c1407f57ced2ba941b3','2018-07-18 10:42:01','2018-07-25 10:42:01'),
	(30,21000203,'0a61bc7ed6243de3a6e7300723cbc03d','2018-07-18 10:43:54','2018-07-25 10:43:54'),
	(31,21000203,'3095488416f0e20cf412a5f37210f102','2018-07-18 10:44:05','2018-07-25 10:44:05'),
	(32,20000031,'dac9902e96c72b03f26ca1dfc9c42c7e','2018-07-18 10:48:53','2018-07-25 10:48:53'),
	(33,21000203,'17a976bff859b8e09e7d898e7b0af61f','2018-07-18 10:49:02','2018-07-25 10:49:02'),
	(34,20000031,'6f2edda115a62c0d7d0cf0e4c24eed60','2018-07-18 10:49:03','2018-07-25 10:49:03'),
	(35,21000203,'wx1810492761152199468935462859419517','2018-07-18 10:49:43','2018-07-25 10:49:43'),
	(36,20000031,'fb9e4934c3c7b11a26661960795e07c0','2018-07-18 10:50:15','2018-07-25 10:50:15'),
	(37,21000206,'wx181050100490757d431883c92149015523','2018-07-18 10:50:27','2018-07-25 10:50:27'),
	(38,21000217,'8ad1d5601dade7778a1d51873c884984','2018-07-18 10:50:28','2018-07-25 10:50:28'),
	(39,21000217,'e294ad06e8687bdb95387ea0ab706f9b','2018-07-18 10:51:39','2018-07-25 10:51:39'),
	(40,21000217,'7f7799ae3024d6ab9553ab9f2eb04fff','2018-07-18 10:53:34','2018-07-25 10:53:34'),
	(41,21000206,'1531882417115','2018-07-18 10:53:37','2018-07-25 10:53:37'),
	(42,21000203,'c191e6755a982e72a32a6cdc9620785f','2018-07-18 10:53:40','2018-07-25 10:53:40'),
	(43,21000206,'wx181053371487680e48f30f2f0659228842','2018-07-18 10:53:43','2018-07-25 10:53:43'),
	(44,21000203,'wx1810534088196056505e21723215396996','2018-07-18 10:53:45','2018-07-25 10:53:45'),
	(45,21000217,'wx18105334359342eff08cea312058072480','2018-07-18 10:53:46','2018-07-25 10:53:46'),
	(46,20000031,'f7a98dbc732b0b06d16f4c164e93f736','2018-07-18 10:54:25','2018-07-25 10:54:25'),
	(47,20000031,'f1cec8118724bfac8d7b64548dcb3754','2018-07-18 11:04:49','2018-07-25 11:04:49'),
	(48,21000203,'dce8ef52c1c38a4a26eaa74818fd778f','2018-07-18 11:05:47','2018-07-25 11:05:47'),
	(49,21000206,'wx18111204304836b03656ca753060798503','2018-07-18 11:12:10','2018-07-25 11:12:10'),
	(50,20000031,'5eec3ae17d5acf28a66eec0f159b7f87','2018-07-18 11:15:39','2018-07-25 11:15:39'),
	(51,20000031,'ae30654166e28ec87b6348dde72ea05a','2018-07-18 11:32:51','2018-07-25 11:32:51'),
	(52,21000203,'454988abedacf206f7a2516f1183e5bf','2018-07-18 11:33:24','2018-07-25 11:33:24'),
	(53,20000031,'7b45768350216f881277e8709c967ce8','2018-07-18 14:23:55','2018-07-25 14:23:55'),
	(54,20000031,'4f52477c75d2fadba935019eb8a37653','2018-07-18 14:24:33','2018-07-25 14:24:33'),
	(55,20000031,'578040cfa1261f09254df5a7e7254a37','2018-07-18 14:32:49','2018-07-25 14:32:49'),
	(56,20000031,'4e030b043ab7ebf87f48414ce3066873','2018-07-18 14:37:54','2018-07-25 14:37:54'),
	(57,20000031,'bd097226b145960485056e4a3e4f033d','2018-07-18 14:38:36','2018-07-25 14:38:36'),
	(58,20000031,'4b51371338b54d08e0d88f6dff1137c4','2018-07-18 14:41:13','2018-07-25 14:41:13'),
	(59,20000031,'9654ed74c56cc2b29389f77b8e9b1cec','2018-07-18 14:43:42','2018-07-25 14:43:42'),
	(60,20000031,'f3ed172a9695da67a85df8643ff235f9','2018-07-18 15:07:04','2018-07-25 15:07:04'),
	(61,21000217,'29ce25039044b6d32b30f1fbf109c9cd','2018-07-18 17:49:42','2018-07-25 17:49:42'),
	(62,21000217,'c579a92e3b4099808b381a6da3d6a32b','2018-07-18 17:51:03','2018-07-25 17:51:03'),
	(63,21000217,'8fe92496ae9f1adc0a5f66ea8f18924d','2018-07-18 17:55:21','2018-07-25 17:55:21'),
	(64,21000217,'f54e3c248f893411997b53a561e2bcbe','2018-07-18 17:57:19','2018-07-25 17:57:19'),
	(65,21000217,'84f3991b55abe1176fa361bd3fc747c5','2018-07-18 17:57:23','2018-07-25 17:57:23'),
	(66,21000217,'3504882116060ea0683e22ebcf058f73','2018-07-18 17:58:41','2018-07-25 17:58:41'),
	(67,21000217,'8394148d3dbb5749de9d470ce2065bef','2018-07-18 18:43:09','2018-07-25 18:43:09'),
	(68,21000217,'6a5a9ce73472e651d62e18f54670e7a3','2018-07-18 18:48:04','2018-07-25 18:48:04'),
	(69,20000031,'wx1818484039838160db835aaa1427930529','2018-07-18 18:49:09','2018-07-25 18:49:09'),
	(70,21000217,'123ea244453c02a9b93aeb84240cc284','2018-07-18 18:59:21','2018-07-25 18:59:21'),
	(71,20000031,'wx18190024952717bccf4a69780981149221','2018-07-18 19:00:32','2018-07-25 19:00:32'),
	(72,20000031,'4dce2a6587b72329102e10a1f7836ef2','2018-07-18 19:05:29','2018-07-25 19:05:29'),
	(73,20000031,'wx181909503076981b761c3ef60549026141','2018-07-18 19:09:58','2018-07-25 19:09:58'),
	(74,21000203,'wx18191250244570abf8de76634263427278','2018-07-18 19:13:06','2018-07-25 19:13:06'),
	(75,21000206,'wx181914069190215db53b1ed80313776857','2018-07-18 19:14:15','2018-07-25 19:14:15'),
	(76,20000031,'3f89a6a6f25b558af43fe019ee9f57d7','2018-07-18 19:14:39','2018-07-25 19:14:39'),
	(77,21000206,'wx18191441828404619860202e0367361065','2018-07-18 19:15:02','2018-07-25 19:15:02'),
	(78,21000206,'wx1819180683126479ee9231a43855145961','2018-07-18 19:18:13','2018-07-25 19:18:13'),
	(79,20000031,'82f02751403f88eba17f7bbbbca60d71','2018-07-18 19:28:53','2018-07-25 19:28:53'),
	(80,20000031,'wx18192926196879827687add41174184298','2018-07-18 19:29:32','2018-07-25 19:29:32'),
	(81,20000031,'269087a1e7b3163dd8029c228eb250c8','2018-07-18 19:30:03','2018-07-25 19:30:03'),
	(82,20000031,'452a42c29289e2f5cdf40abedb810467','2018-07-18 19:31:26','2018-07-25 19:31:26'),
	(83,20000031,'0208f025fed09903808f738feae51704','2018-07-18 19:31:32','2018-07-25 19:31:32'),
	(84,20000031,'wx181931353735732a695f5a320318288444','2018-07-18 19:31:40','2018-07-25 19:31:40'),
	(85,21000206,'wx18193156454222c1f4d57bf33039665404','2018-07-18 19:32:04','2018-07-25 19:32:04'),
	(86,21000217,'wx1819322587583163ca7446cd3002756125','2018-07-18 19:32:38','2018-07-25 19:32:38'),
	(87,21000217,'bdd29ee56c56e0be8dff002d02e3ff5a','2018-07-18 19:33:15','2018-07-25 19:33:15'),
	(88,20000031,'wx18193310710493d951ccc9702309099664','2018-07-18 19:33:17','2018-07-25 19:33:17'),
	(89,21000203,'wx18193314672305c2864a88003588506966','2018-07-18 19:33:23','2018-07-25 19:33:23'),
	(90,20000031,'f5c5e97dc68d5868dbf28ebab2f10af7','2018-07-18 19:33:40','2018-07-25 19:33:40'),
	(91,21000206,'wx18193353518700629dce1bb52569060212','2018-07-18 19:34:00','2018-07-25 19:34:00'),
	(92,21000217,'a9d9e46f8588df9c0a300d905eff8ea2','2018-07-18 19:44:26','2018-07-25 19:44:26'),
	(93,20000031,'8573c000321e88056f9ffbde9afaafa4','2018-07-18 20:00:59','2018-07-25 20:00:59'),
	(94,20000031,'wx182001047648634c535188110259877516','2018-07-18 20:01:11','2018-07-25 20:01:11'),
	(95,21000206,'wx18200156115677b7e996913f4084340994','2018-07-18 20:02:08','2018-07-25 20:02:08'),
	(96,21000217,'wx18200221567444ac3e983fba1679126036','2018-07-18 20:02:28','2018-07-25 20:02:28'),
	(97,21000203,'wx182002337077348a70765d103940625945','2018-07-18 20:02:39','2018-07-25 20:02:39'),
	(98,20000031,'ce6041b314f7ec8b7db6068d29f1ba81','2018-07-18 20:03:05','2018-07-25 20:03:05'),
	(99,20000031,'wx182003190121359fd048bb463909185424','2018-07-18 20:03:24','2018-07-25 20:03:24'),
	(100,20000031,'6b2bca7ff49cf83f305f251094b5258b','2018-07-18 20:03:34','2018-07-25 20:03:34'),
	(101,21000217,'fffb7fe9578564474080695fe1d0fe69','2018-07-18 20:03:39','2018-07-25 20:03:39'),
	(102,20000031,'bcec77a5e51e9c9827acfa0b86df7e91','2018-07-18 20:04:29','2018-07-25 20:04:29'),
	(103,21000206,'wx18200451005028ae08ca9ef00914441318','2018-07-18 20:05:02','2018-07-25 20:05:02'),
	(104,20000031,'85ebbffc6b0a882175b8d2f5dc379108','2018-07-18 20:30:07','2018-07-25 20:30:07'),
	(105,21000217,'beb01573a7d213c2d8b491fc701eec34','2018-07-18 20:32:15','2018-07-25 20:32:15'),
	(106,20000031,'11476379244186b9f93754c8893501d9','2018-07-18 20:55:56','2018-07-25 20:55:56'),
	(107,21000203,'5da35f60353f506b0e96cc87465b05c7','2018-07-18 20:59:29','2018-07-25 20:59:29'),
	(108,21000203,'d7d62d900b01c632af32a872d5fdbcf7','2018-07-19 10:19:00','2018-07-26 10:19:00'),
	(109,21000203,'eba341376d391631400ee37a5836d53e','2018-07-19 10:19:53','2018-07-26 10:19:53'),
	(110,21000217,'8ce8096847440259d10237f1b791a915','2018-07-19 10:27:57','2018-07-26 10:27:57'),
	(111,21000217,'336a8559f5128c78610433dd09e86fe0','2018-07-19 10:58:10','2018-07-26 10:58:10'),
	(112,21000217,'69b8e998d71468b2812b6ec9a8074128','2018-07-19 10:58:15','2018-07-26 10:58:15'),
	(113,21000217,'de786af54465a619a2de18f646548532','2018-07-19 11:05:18','2018-07-26 11:05:18'),
	(114,21000217,'05ff490f6df6558bb94668a7d7964c88','2018-07-19 11:05:23','2018-07-26 11:05:23'),
	(115,21000217,'5ced96d522d4bd908a3089c198420004','2018-07-19 11:05:40','2018-07-26 11:05:40'),
	(116,21000217,'6315eb172a927caa939de96965ba3882','2018-07-19 11:05:43','2018-07-26 11:05:43'),
	(117,21000217,'995c8a39b465ffa15edde30984ced481','2018-07-19 11:05:50','2018-07-26 11:05:50'),
	(118,21000217,'600ad9b0f3c7ef3b96979218b9a3f7b4','2018-07-19 11:13:35','2018-07-26 11:13:35'),
	(119,21000217,'5f484d93307690be3d398d515845270c','2018-07-19 11:17:10','2018-07-26 11:17:10'),
	(120,21000217,'73fc411060e1d5fdd9cca27e4af9a4a1','2018-07-19 11:17:16','2018-07-26 11:17:16'),
	(121,20000031,'f828418d799b9dd66646e0d969cf9a4e','2018-07-19 11:18:25','2018-07-26 11:18:25'),
	(122,21000217,'6650f4af32d02bf83ac0d0c78d631a5a','2018-07-19 11:33:16','2018-07-26 11:33:16'),
	(123,21000217,'31713079c86abb033bfeda81cbea4d05','2018-07-19 11:33:21','2018-07-26 11:33:21'),
	(124,21000217,'1e7762d4e7f0c107c7aeee080671485e','2018-07-19 11:33:33','2018-07-26 11:33:33'),
	(125,20000031,'1e067dc2afb356631f68193d892844bf','2018-07-19 11:33:33','2018-07-26 11:33:33'),
	(126,20000031,'16cfa65b0966ababe0e277f02b3d6beb','2018-07-19 11:37:33','2018-07-26 11:37:33'),
	(127,21000206,'1531979700943','2018-07-19 13:55:00','2018-07-26 13:55:00'),
	(128,21000206,'wx19135502893767e11ace43581342658788','2018-07-19 13:55:11','2018-07-26 13:55:11'),
	(129,21000217,'3a99caa254f8c968dad3b95f3fbbaae7','2018-07-19 14:07:12','2018-07-26 14:07:12'),
	(130,21000217,'3340f56156322a93955fbf1e1744c38a','2018-07-19 14:08:15','2018-07-26 14:08:15'),
	(131,21000217,'8653462f289c40d173a290ee17ef3439','2018-07-19 14:08:19','2018-07-26 14:08:19'),
	(132,20000031,'7a9989c3cd7e5697b153ac06e8f07981','2018-07-19 14:09:13','2018-07-26 14:09:13'),
	(133,21000217,'8a3737671fcea45aa6d356636b28d8d0','2018-07-19 14:12:17','2018-07-26 14:12:17'),
	(134,21000217,'77f76cdef71d2d85cfc5847124a04318','2018-07-19 14:21:54','2018-07-26 14:21:54'),
	(135,21000217,'f751715704e865c33461a552e2ca814e','2018-07-19 14:21:58','2018-07-26 14:21:58'),
	(136,21000217,'19294dace7f03ef8ac4f2e595aa2d486','2018-07-19 14:22:06','2018-07-26 14:22:06'),
	(137,21000217,'2473e8d992c128278a105e8bba260a72','2018-07-19 14:23:18','2018-07-26 14:23:18'),
	(138,20000031,'3b20a27ed276a04c3d82b82e77c09bb0','2018-07-19 14:24:36','2018-07-26 14:24:36'),
	(139,20000031,'2f2c5526fbb4c4cb9820140e6c6c9337','2018-07-19 14:25:12','2018-07-26 14:25:12'),
	(140,21000217,'043e59aa7edf09876943f34fa9688df9','2018-07-19 14:27:03','2018-07-26 14:27:03'),
	(141,21000217,'c91d8c44c9223ab6eb310d85e38fd4a9','2018-07-19 14:28:04','2018-07-26 14:28:04'),
	(142,21000217,'66966dc8493e924721157f0b089f2150','2018-07-19 14:28:37','2018-07-26 14:28:37'),
	(143,21000217,'b76456ade39099551af843dc14fbf4a8','2018-07-19 14:28:40','2018-07-26 14:28:40'),
	(144,21000217,'1af58092919d538fd733103841cd9158','2018-07-19 14:29:36','2018-07-26 14:29:36'),
	(145,20000031,'e7cbabda888719388b44ea0812b6aefd','2018-07-19 14:29:39','2018-07-26 14:29:39'),
	(146,20000031,'6ddf118540848e6ab339bcb919ac3b1c','2018-07-19 14:31:50','2018-07-26 14:31:50'),
	(147,21000217,'f9d20a650d0a7a95c7461db9a15cd8e5','2018-07-19 14:31:58','2018-07-26 14:31:58'),
	(148,20000031,'51188c8b98f10fb935db935cd3a68f42','2018-07-19 14:32:43','2018-07-26 14:32:43'),
	(149,21000217,'c0f965bf047b6b69d21790d2bb8ff65b','2018-07-19 14:32:46','2018-07-26 14:32:46'),
	(150,21000203,'03a33f9fb8f2dc1e3db143f4c483d382','2018-07-19 14:33:47','2018-07-26 14:33:47'),
	(151,21000206,'wx19143450341631f06eceb0392959430999','2018-07-19 14:34:56','2018-07-26 14:34:56'),
	(152,21000217,'f41901cdcb2652745b29848c26f2eeeb','2018-07-19 14:35:24','2018-07-26 14:35:24'),
	(153,21000217,'794e8f950e7cec89459dee0c8696ee9d','2018-07-19 14:41:14','2018-07-26 14:41:14'),
	(154,21000217,'7f4a0e8e635d00a8012a9cfbb0228653','2018-07-19 14:41:17','2018-07-26 14:41:17'),
	(155,21000217,'be3374faef878e989edc8144405d8a2f','2018-07-19 14:41:21','2018-07-26 14:41:21'),
	(156,21000217,'9034e7cb16e376f8c107912d7874cd5a','2018-07-19 14:41:24','2018-07-26 14:41:24'),
	(157,21000217,'9f85b075782533acc57bd3d301c786a6','2018-07-19 14:41:27','2018-07-26 14:41:27'),
	(158,21000217,'298fe45e7f5435463c72097bf6dad8ca','2018-07-19 14:41:32','2018-07-26 14:41:32'),
	(159,21000217,'5c05d7dbfdf9f8082a1c3ee361763843','2018-07-19 14:41:34','2018-07-26 14:41:34'),
	(160,21000217,'fbaa2e03454941cd46d74e7d16f1aaa0','2018-07-19 14:41:41','2018-07-26 14:41:41'),
	(161,21000217,'7a76b44568506939dd3a3a39ea98a0ff','2018-07-19 14:41:50','2018-07-26 14:41:50'),
	(162,21000217,'7128c73c81bcb992511042106c7641e8','2018-07-19 14:41:53','2018-07-26 14:41:53'),
	(163,21000217,'778fe9d68f4c65ddbe04f9f8207ad06c','2018-07-19 14:42:04','2018-07-26 14:42:04'),
	(164,21000217,'6aaeee61c2df4c71e7b53f3b1380f43e','2018-07-19 14:42:19','2018-07-26 14:42:19'),
	(165,21000217,'fb226be32f13a22581a9e195aeaf1d5b','2018-07-19 14:42:22','2018-07-26 14:42:22'),
	(166,21000217,'cb1e10c389757fc76e96395066a20d9f','2018-07-19 14:42:24','2018-07-26 14:42:24'),
	(167,20000031,'09219a0461ff4e28d7a1ac4e17279bd3','2018-07-19 15:34:45','2018-07-26 15:34:45'),
	(168,20000031,'4bd9b5067fe35db140d13de86e6b7107','2018-07-19 15:35:26','2018-07-26 15:35:26'),
	(169,20000031,'9807b65ef96e86e06f21ee9a299172db','2018-07-19 15:35:58','2018-07-26 15:35:58'),
	(170,20000031,'925cdc927c7e0b6ea09886619f9bd167','2018-07-19 15:37:13','2018-07-26 15:37:13'),
	(171,20000031,'98e3e13fe00f186c3811b268658f8d79','2018-07-19 15:40:26','2018-07-26 15:40:26'),
	(172,20000031,'49be7e12532cf966d41e94190ab24581','2018-07-19 15:46:08','2018-07-26 15:46:08'),
	(173,20000031,'2989552b01050d8cf76062b801325cc8','2018-07-19 15:46:10','2018-07-26 15:46:10'),
	(174,20000031,'0c82d5580152e99a902d4c355cd0fcad','2018-07-19 15:53:47','2018-07-26 15:53:47'),
	(175,21000217,'b78951a6f3aef82de2e9940b65250891','2018-07-19 16:00:35','2018-07-26 16:00:35'),
	(176,20000031,'567b507d7cb13875843282e688ba0820','2018-07-19 16:08:24','2018-07-26 16:08:24'),
	(177,20000031,'0125fbf20f0f0675d068f53ffb4f7033','2018-07-19 16:08:34','2018-07-26 16:08:34'),
	(178,20000031,'b4bb22cd22e468a71c6a6c6a0f0320f7','2018-07-19 16:19:38','2018-07-26 16:19:38'),
	(179,20000031,'cf8ebbe2993c3f8dc161557f2bbc7597','2018-07-19 16:30:06','2018-07-26 16:30:06'),
	(180,20000031,'0c305e457958ccc5ce5757204c4f298b','2018-07-19 16:30:08','2018-07-26 16:30:08'),
	(181,20000031,'8b2e1a4555c24db72003d720e8231688','2018-07-19 16:30:26','2018-07-26 16:30:26'),
	(182,20000031,'8ee3a8f8b53c329a40ddd742e582dc8e','2018-07-19 16:31:34','2018-07-26 16:31:34'),
	(183,21000202,'25a1f147490f0850340adf5f379cab6c','2018-07-19 16:32:01','2018-07-26 16:32:01'),
	(184,21000202,'83c4af39eee085a0ff9c5e499bd3a281','2018-07-19 16:32:38','2018-07-26 16:32:38'),
	(185,21000217,'1b12ec5be4d8e1f14374cac2fa54901a','2018-07-19 16:33:32','2018-07-26 16:33:32'),
	(186,20000031,'065d5c1ae5eee44b313fb9b2dbb8099d','2018-07-19 16:36:31','2018-07-26 16:36:31'),
	(187,20000031,'ebd934b775b7e3582e8f046925a1bea4','2018-07-19 16:42:39','2018-07-26 16:42:39'),
	(188,21000202,'75afd881c91a1034055c31f087454334','2018-07-19 16:42:43','2018-07-26 16:42:43'),
	(189,21000218,'8efba236a90c9b3cecda0a8bcafdf6f8','2018-07-19 16:45:35','2018-07-26 16:45:35'),
	(190,21000218,'af8fa61904de332c2c1ee0e2307c955d','2018-07-19 16:45:48','2018-07-26 16:45:48'),
	(191,21000218,'cd476b5c4b41624933268af4a416c1db','2018-07-19 16:46:03','2018-07-26 16:46:03'),
	(192,20000031,'fb52fc3e667800e7c0d9539bf3bdedc1','2018-07-19 16:46:06','2018-07-26 16:46:06'),
	(193,21000218,'7c86a50b4e6e047006f9924c27f16717','2018-07-19 16:46:10','2018-07-26 16:46:10'),
	(194,21000218,'c16bc5d428e67497bbae7675ba7fb64c','2018-07-19 16:46:18','2018-07-26 16:46:18'),
	(195,21000218,'8adb7f86fc10de57cd4020561624176d','2018-07-19 16:46:33','2018-07-26 16:46:33'),
	(196,21000218,'c60c870633efe0f23f8479d6ffe88858','2018-07-19 16:46:38','2018-07-26 16:46:38'),
	(197,21000218,'b20e50a14eaf669c05be6fff058b2fa7','2018-07-19 16:46:43','2018-07-26 16:46:43'),
	(198,21000218,'1f446a90640f28143fa9aec3c212300f','2018-07-19 16:46:46','2018-07-26 16:46:46'),
	(199,21000218,'19466547471fe5f8517beb8fe86a4dfa','2018-07-19 16:47:45','2018-07-26 16:47:45'),
	(200,21000218,'35e6200702b98475559cf44d992564d8','2018-07-19 16:47:49','2018-07-26 16:47:49'),
	(201,20000031,'5a0385c11a0b8388a5401293a131e0cc','2018-07-19 16:49:45','2018-07-26 16:49:45'),
	(202,20000031,'e578a96773db858c9d7fb84a410eac9f','2018-07-19 16:49:51','2018-07-26 16:49:51'),
	(203,20000031,'8a8725b7e3bd82cf104423a30abe274e','2018-07-19 16:50:08','2018-07-26 16:50:08'),
	(204,20000031,'3bbc42e65f5586a096c3221d1fecac06','2018-07-19 16:50:15','2018-07-26 16:50:15'),
	(205,20000031,'537c536d979f2d7b7c61b3c398616a55','2018-07-19 16:50:39','2018-07-26 16:50:39'),
	(206,21000202,'c22c9af93f6ecf44b6da5d90c4f93876','2018-07-19 16:51:04','2018-07-26 16:51:04'),
	(207,21000202,'146529b80a01a9fc7b5413be76409c5d','2018-07-19 16:52:43','2018-07-26 16:52:43'),
	(208,21000218,'4fe26f82698935a07556b36144f8b946','2018-07-19 20:23:03','2018-07-26 20:23:03'),
	(209,21000218,'4c900bd4f56113f6c5bd7402b35f62bd','2018-07-19 20:36:43','2018-07-26 20:36:43'),
	(210,21000218,'28a7395f2c16cbf62ae7489fb8e13ea2','2018-07-19 20:37:56','2018-07-26 20:37:56'),
	(211,21000202,'7fcf40cb6b0459ee8b7f3d6a9662f971','2018-07-19 23:32:31','2018-07-26 23:32:31'),
	(212,21000202,'bfb443734fb81386404bdb56845abd42','2018-07-19 23:39:50','2018-07-26 23:39:50'),
	(213,21000202,'10dafba84f765432461d1fa2d0bb5277','2018-07-19 23:41:08','2018-07-26 23:41:08'),
	(214,21000202,'43d78543988a30394751af247863ec54','2018-07-19 23:41:12','2018-07-26 23:41:12'),
	(215,21000217,'8a88cfbb2f2dd32f0973d82392b6f11c','2018-07-21 16:04:01','2018-07-28 16:04:01'),
	(216,21000217,'4f3c42ce310e153c3b092196c61cf5d5','2018-07-21 16:04:22','2018-07-28 16:04:22'),
	(217,21000217,'1c111cbe6b3a3cde8b72c539c2f75523','2018-07-21 16:04:25','2018-07-28 16:04:25'),
	(218,21000217,'d01e53a45f223bfc7f629ea6362e6184','2018-07-21 16:05:00','2018-07-28 16:05:00'),
	(219,21000217,'35a1dbbc160d8b3c45dfeacd035c93a3','2018-07-21 23:34:20','2018-07-28 23:34:20'),
	(220,21000217,'4ba4295fb2a8e8153385ca7e1cdcdac4','2018-07-21 23:34:23','2018-07-28 23:34:23'),
	(221,21000216,'074c2652172fca35a7f01dc49f312a1e','2018-07-23 19:29:47','2018-07-30 19:29:47'),
	(222,21000216,'aedd16509e171d3b347b36528a178cf2','2018-07-23 19:30:33','2018-07-30 19:30:33'),
	(223,21000216,'37318212adfc3980ae4c1d493473bcfe','2018-07-23 19:30:42','2018-07-30 19:30:42'),
	(224,20000031,'8c2029084f7050e21e3959e444e9d88a','2018-07-27 15:23:10','2018-08-03 15:23:10'),
	(225,20000031,'c27b6112bc5bd2cfc1d0745c04078585','2018-07-27 15:23:19','2018-08-03 15:23:19'),
	(226,20000031,'2b0b96e1681669ce85cdb533cf20cef3','2018-07-27 15:23:24','2018-08-03 15:23:24'),
	(227,20000031,'d6a4d715d60dbca5c655bcdb4291301f','2018-07-27 15:23:35','2018-08-03 15:23:35'),
	(228,20000031,'74280786c04106beeba71f8b6484b96a','2018-07-27 15:23:37','2018-08-03 15:23:37'),
	(229,20000031,'b5f2d06ffd759fe66bc6d109ad5d5451','2018-07-27 15:29:50','2018-08-03 15:29:50'),
	(230,20000031,'a0f54e195b3985319d4d949a4fb2e93b','2018-07-27 15:29:56','2018-08-03 15:29:56'),
	(231,20000031,'0afb3321aa5604144f5832a5c67c4e99','2018-07-27 15:30:02','2018-08-03 15:30:02'),
	(232,20000031,'f981fe5eee02582714f7cb8591994c26','2018-07-27 15:31:00','2018-08-03 15:31:00'),
	(233,20000031,'617fcda052d4a43129c673b544d5c87a','2018-07-27 15:31:07','2018-08-03 15:31:07'),
	(234,20000031,'7e4df701a939ce1b29732ba44aae2aae','2018-07-27 17:22:26','2018-08-03 17:22:26'),
	(235,20000031,'b407a7b80ed040c874173bb3f5a38b71','2018-07-27 17:22:29','2018-08-03 17:22:29'),
	(236,20000031,'131a8131dbc408671199787b838df16c','2018-07-27 17:22:32','2018-08-03 17:22:32'),
	(237,20000031,'6fea86add1afb9b1244ba6948014ee53','2018-07-27 17:22:44','2018-08-03 17:22:44'),
	(238,20000031,'476a2870e31b6df2b1f955a7ad2904c7','2018-07-27 17:29:12','2018-08-03 17:29:12'),
	(239,20000031,'772f0fdfa7e99d2762633a45fb03312f','2018-07-27 17:30:16','2018-08-03 17:30:16'),
	(240,20000031,'9c2d7a07dd296d4e7813766c9a21d09a','2018-07-27 19:20:18','2018-08-03 19:20:18'),
	(241,20000031,'1e85b4200ab5791dd02004b75008b750','2018-07-27 19:20:22','2018-08-03 19:20:22'),
	(242,21000216,'23dea20e08e33a650cae4a75fcb49006','2018-07-27 19:57:43','2018-08-03 19:57:43'),
	(243,21000216,'cb403e3ef78fbc71922cf0b870277bf5','2018-07-27 19:58:01','2018-08-03 19:58:01'),
	(244,21000216,'cc6ff46531276c3d1c72209eaa6e7a56','2018-07-27 19:58:10','2018-08-03 19:58:10'),
	(245,21000216,'b35fe803a9cf61b92771c21b4f0e7593','2018-07-27 19:58:15','2018-08-03 19:58:15'),
	(246,21000216,'9de3aad83611bfa2201170481c8736e3','2018-07-27 19:58:17','2018-08-03 19:58:17'),
	(247,21000216,'7436a679f79e2f2e0b4d00388a146488','2018-07-27 19:58:47','2018-08-03 19:58:47'),
	(248,21000216,'473cf5bb7cb10adc3d2e7ed76d027fb4','2018-07-27 20:24:40','2018-08-03 20:24:40'),
	(249,21000216,'1ebfe7d860ae83be803a83c704e862e0','2018-07-27 20:25:33','2018-08-03 20:25:33'),
	(250,21000216,'7de5af4de9e4dc7190adeb28ebe9642b','2018-07-27 20:25:39','2018-08-03 20:25:39'),
	(251,21000216,'b4bef8fa291980ca19612d048a874d51','2018-07-27 20:26:02','2018-08-03 20:26:02'),
	(252,21000216,'74a70cf560c40eea69075d1eeb803982','2018-07-27 20:26:06','2018-08-03 20:26:06'),
	(253,21000216,'0695caa2946f695c9720e45108aad69e','2018-07-27 20:26:16','2018-08-03 20:26:16'),
	(254,21000216,'47dc72f24c2d3015e849b402ff780627','2018-07-27 20:26:20','2018-08-03 20:26:20'),
	(255,21000216,'e318100cc65004d412ec0554d3b5ecb0','2018-07-27 20:26:24','2018-08-03 20:26:24'),
	(256,21000216,'8410460cdc1cf2f7916a6244baf195fa','2018-07-27 20:26:28','2018-08-03 20:26:28'),
	(257,21000216,'4d949f47926fb76ccc8142f6f86a9950','2018-07-27 20:26:35','2018-08-03 20:26:35'),
	(258,21000216,'3785746e8de150afe475d27223292af1','2018-07-27 20:26:47','2018-08-03 20:26:47'),
	(259,21000216,'f43230c8a14f39cf500c5e554a15c454','2018-07-28 18:42:28','2018-08-04 18:42:28');

/*!40000 ALTER TABLE `user_forms` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_inviter
# ------------------------------------------------------------

CREATE TABLE `user_inviter` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `inviter` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '邀请人uid',
  `type` varchar(50) NOT NULL DEFAULT '' COMMENT '邀请分类',
  `extend` varchar(255) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_inviter` WRITE;
/*!40000 ALTER TABLE `user_inviter` DISABLE KEYS */;

INSERT INTO `user_inviter` (`id`, `uid`, `inviter`, `type`, `extend`, `addtime`)
VALUES
	(1,20000012,200000001,'2','','2018-05-15 20:15:12');

/*!40000 ALTER TABLE `user_inviter` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_medal
# ------------------------------------------------------------

CREATE TABLE `user_medal` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `kind` varchar(50) NOT NULL DEFAULT '',
  `medal` varchar(255) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_user_kind` (`uid`,`kind`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_medal` WRITE;
/*!40000 ALTER TABLE `user_medal` DISABLE KEYS */;

INSERT INTO `user_medal` (`id`, `uid`, `kind`, `medal`, `addtime`)
VALUES
	(4,21000216,'vip','1540125234','2018-07-13 20:33:54'),
	(80,21000204,'vip','2019-06-15 14:38:57','0000-00-00 00:00:00'),
	(116,210000203,'vip','2018-06-15 14:38:57','2018-07-19 11:17:55'),
	(118,2100206,'vip','2018-06-15 14:38:57','2018-07-19 11:35:49'),
	(121,21000217,'vip','2019-07-19 14:27:32','2018-07-19 14:27:32'),
	(122,20000031,'vip','2020-07-19 11:32:41','2018-07-19 14:28:47'),
	(123,21000203,'vip','2021-07-18 20:02:38','2018-07-19 14:34:19'),
	(124,21000206,'vip','2020-07-19 13:55:10','2018-07-19 14:34:56'),
	(125,21000202,'vip','2019-07-19 16:28:22','2018-07-19 16:28:22');

/*!40000 ALTER TABLE `user_medal` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_0
# ------------------------------------------------------------

CREATE TABLE `user_task_0` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_0` WRITE;
/*!40000 ALTER TABLE `user_task_0` DISABLE KEYS */;

INSERT INTO `user_task_0` (`uid`, `taskid`, `totaltimes`, `daytimes`, `addtime`, `modtime`)
VALUES
	(10000000,9,1,1,'2018-07-13 17:08:39','2018-07-13 17:08:39');

/*!40000 ALTER TABLE `user_task_0` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_1
# ------------------------------------------------------------

CREATE TABLE `user_task_1` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_10
# ------------------------------------------------------------

CREATE TABLE `user_task_10` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_11
# ------------------------------------------------------------

CREATE TABLE `user_task_11` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_12
# ------------------------------------------------------------

CREATE TABLE `user_task_12` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_13
# ------------------------------------------------------------

CREATE TABLE `user_task_13` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_14
# ------------------------------------------------------------

CREATE TABLE `user_task_14` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_14` WRITE;
/*!40000 ALTER TABLE `user_task_14` DISABLE KEYS */;

INSERT INTO `user_task_14` (`uid`, `taskid`, `totaltimes`, `daytimes`, `addtime`, `modtime`)
VALUES
	(21000214,4,1,1,'2018-07-02 17:04:49','2018-07-02 17:04:49'),
	(21000214,10,1,1,'2018-07-02 17:04:49','2018-07-02 17:04:49');

/*!40000 ALTER TABLE `user_task_14` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_15
# ------------------------------------------------------------

CREATE TABLE `user_task_15` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_16
# ------------------------------------------------------------

CREATE TABLE `user_task_16` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_16` WRITE;
/*!40000 ALTER TABLE `user_task_16` DISABLE KEYS */;

INSERT INTO `user_task_16` (`uid`, `taskid`, `totaltimes`, `daytimes`, `addtime`, `modtime`)
VALUES
	(20000016,1,1,1,'2018-05-29 21:24:14','2018-05-29 21:24:14'),
	(21000216,2,1,1,'2018-06-14 18:54:02','2018-06-14 18:54:02'),
	(21000216,4,1,1,'2018-07-12 16:04:13','2018-07-12 16:04:13'),
	(21000216,7,1,1,'2018-06-23 13:30:54','2018-06-23 13:30:54'),
	(21000216,8,1,1,'2018-07-12 15:21:04','2018-07-12 15:21:04'),
	(21000216,9,4,4,'2018-07-03 11:35:14','2018-07-03 11:36:30'),
	(21000216,10,2,2,'2018-07-12 16:04:13','2018-07-12 19:38:03');

/*!40000 ALTER TABLE `user_task_16` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_17
# ------------------------------------------------------------

CREATE TABLE `user_task_17` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_17` WRITE;
/*!40000 ALTER TABLE `user_task_17` DISABLE KEYS */;

INSERT INTO `user_task_17` (`uid`, `taskid`, `totaltimes`, `daytimes`, `addtime`, `modtime`)
VALUES
	(20000017,1,1,1,'2018-05-30 10:20:10','2018-05-30 10:20:10'),
	(21000217,2,1,1,'2018-06-22 19:07:53','2018-06-22 19:07:53'),
	(21000217,4,1,1,'2018-06-21 16:09:15','2018-06-21 16:09:15'),
	(21000217,7,1,1,'2018-06-21 14:59:04','2018-06-21 14:59:04'),
	(21000217,9,27,2,'2018-06-29 19:30:28','2018-07-19 11:33:12'),
	(21000217,10,85,5,'2018-06-29 19:28:22','2018-07-16 11:45:26');

/*!40000 ALTER TABLE `user_task_17` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_18
# ------------------------------------------------------------

CREATE TABLE `user_task_18` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_18` WRITE;
/*!40000 ALTER TABLE `user_task_18` DISABLE KEYS */;

INSERT INTO `user_task_18` (`uid`, `taskid`, `totaltimes`, `daytimes`, `addtime`, `modtime`)
VALUES
	(20000018,1,1,1,'2018-05-30 11:05:57','2018-05-30 11:05:57'),
	(20000018,2,1,1,'2018-05-31 17:37:51','2018-05-31 17:37:51'),
	(21000218,4,1,1,'2018-06-14 17:30:18','2018-06-14 17:30:18'),
	(21000218,7,1,1,'2018-07-19 16:47:22','2018-07-19 16:47:22');

/*!40000 ALTER TABLE `user_task_18` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_19
# ------------------------------------------------------------

CREATE TABLE `user_task_19` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_19` WRITE;
/*!40000 ALTER TABLE `user_task_19` DISABLE KEYS */;

INSERT INTO `user_task_19` (`uid`, `taskid`, `totaltimes`, `daytimes`, `addtime`, `modtime`)
VALUES
	(20000019,1,1,1,'2018-06-22 18:12:21','2018-06-22 18:12:21'),
	(20000019,2,1,1,'2018-06-30 17:19:41','2018-06-30 17:19:41'),
	(20000019,4,1,1,'2018-06-30 17:23:02','2018-06-30 17:23:02'),
	(20000019,7,1,1,'2018-06-22 18:15:55','2018-06-22 18:15:55'),
	(20000019,8,3,3,'2018-06-30 17:01:28','2018-06-30 17:01:55'),
	(20000019,10,1,1,'2018-06-30 17:23:02','2018-06-30 17:23:02');

/*!40000 ALTER TABLE `user_task_19` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_2
# ------------------------------------------------------------

CREATE TABLE `user_task_2` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_2` WRITE;
/*!40000 ALTER TABLE `user_task_2` DISABLE KEYS */;

INSERT INTO `user_task_2` (`uid`, `taskid`, `totaltimes`, `daytimes`, `addtime`, `modtime`)
VALUES
	(21000202,2,1,1,'2018-06-22 20:08:25','2018-06-23 10:31:49'),
	(21000202,4,1,1,'2018-06-23 13:34:05','2018-06-23 13:34:05'),
	(21000202,7,1,1,'2018-06-22 20:10:08','2018-06-22 20:10:08'),
	(21000202,8,1,1,'2018-07-02 11:36:53','2018-07-02 11:36:53'),
	(21000202,9,2,2,'2018-07-19 16:50:19','2018-07-19 16:51:34'),
	(21000202,10,4,3,'2018-07-02 11:41:18','2018-07-12 18:53:05');

/*!40000 ALTER TABLE `user_task_2` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_20
# ------------------------------------------------------------

CREATE TABLE `user_task_20` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_20` WRITE;
/*!40000 ALTER TABLE `user_task_20` DISABLE KEYS */;

INSERT INTO `user_task_20` (`uid`, `taskid`, `totaltimes`, `daytimes`, `addtime`, `modtime`)
VALUES
	(20000020,1,1,1,'2018-06-30 17:15:20','2018-06-30 17:15:20'),
	(20000020,4,1,1,'2018-06-30 17:24:48','2018-06-30 17:24:48'),
	(20000020,7,1,1,'2018-06-30 17:25:58','2018-06-30 17:25:58'),
	(20000020,10,1,1,'2018-06-30 17:24:48','2018-06-30 17:24:48');

/*!40000 ALTER TABLE `user_task_20` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_21
# ------------------------------------------------------------

CREATE TABLE `user_task_21` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_21` WRITE;
/*!40000 ALTER TABLE `user_task_21` DISABLE KEYS */;

INSERT INTO `user_task_21` (`uid`, `taskid`, `totaltimes`, `daytimes`, `addtime`, `modtime`)
VALUES
	(20000021,1,1,1,'2018-07-01 09:55:57','2018-07-01 09:55:57');

/*!40000 ALTER TABLE `user_task_21` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_22
# ------------------------------------------------------------

CREATE TABLE `user_task_22` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_22` WRITE;
/*!40000 ALTER TABLE `user_task_22` DISABLE KEYS */;

INSERT INTO `user_task_22` (`uid`, `taskid`, `totaltimes`, `daytimes`, `addtime`, `modtime`)
VALUES
	(20000022,1,1,1,'2018-07-03 10:39:35','2018-07-03 10:39:35'),
	(20000022,4,1,1,'2018-07-04 15:26:15','2018-07-04 15:26:15'),
	(20000022,7,1,1,'2018-07-03 10:40:17','2018-07-03 10:40:17'),
	(20000022,10,4,1,'2018-07-04 15:26:15','2018-07-19 16:50:20');

/*!40000 ALTER TABLE `user_task_22` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_23
# ------------------------------------------------------------

CREATE TABLE `user_task_23` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_23` WRITE;
/*!40000 ALTER TABLE `user_task_23` DISABLE KEYS */;

INSERT INTO `user_task_23` (`uid`, `taskid`, `totaltimes`, `daytimes`, `addtime`, `modtime`)
VALUES
	(20000023,1,1,1,'2018-07-03 14:55:11','2018-07-03 14:55:11'),
	(20000023,4,1,1,'2018-07-03 14:57:12','2018-07-03 14:57:12'),
	(20000023,8,2,2,'2018-07-04 15:24:23','2018-07-04 15:24:25'),
	(20000023,9,2,1,'2018-07-04 15:25:45','2018-07-11 20:34:31'),
	(20000023,10,2,1,'2018-07-03 14:57:12','2018-07-12 18:50:53');

/*!40000 ALTER TABLE `user_task_23` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_24
# ------------------------------------------------------------

CREATE TABLE `user_task_24` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_24` WRITE;
/*!40000 ALTER TABLE `user_task_24` DISABLE KEYS */;

INSERT INTO `user_task_24` (`uid`, `taskid`, `totaltimes`, `daytimes`, `addtime`, `modtime`)
VALUES
	(20000024,1,1,1,'2018-07-03 19:28:23','2018-07-03 19:28:23'),
	(21000224,2,1,1,'2018-06-14 17:09:09','2018-06-14 17:09:09'),
	(21000224,4,1,1,'2018-06-23 13:53:04','2018-06-23 13:53:04'),
	(21000224,7,1,1,'2018-06-21 11:30:34','2018-06-21 11:30:34'),
	(21000224,8,2,2,'2018-07-07 16:14:10','2018-07-07 16:14:17'),
	(21000224,10,2,1,'2018-06-30 18:55:51','2018-07-06 15:05:43');

/*!40000 ALTER TABLE `user_task_24` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_25
# ------------------------------------------------------------

CREATE TABLE `user_task_25` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_25` WRITE;
/*!40000 ALTER TABLE `user_task_25` DISABLE KEYS */;

INSERT INTO `user_task_25` (`uid`, `taskid`, `totaltimes`, `daytimes`, `addtime`, `modtime`)
VALUES
	(20000025,1,1,1,'2018-07-03 19:36:28','2018-07-03 19:36:28'),
	(21000225,4,1,1,'2018-06-14 19:31:01','2018-06-14 19:31:01'),
	(21000225,7,1,1,'2018-06-20 20:46:20','2018-06-20 20:46:20'),
	(21000225,8,32,1,'2018-06-29 14:24:16','2018-07-02 15:45:19'),
	(21000225,9,27,1,'2018-07-09 11:28:48','2018-07-17 13:32:07'),
	(21000225,10,70,1,'2018-06-29 16:28:54','2018-09-26 16:23:08');

/*!40000 ALTER TABLE `user_task_25` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_26
# ------------------------------------------------------------

CREATE TABLE `user_task_26` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_26` WRITE;
/*!40000 ALTER TABLE `user_task_26` DISABLE KEYS */;

INSERT INTO `user_task_26` (`uid`, `taskid`, `totaltimes`, `daytimes`, `addtime`, `modtime`)
VALUES
	(20000026,1,1,1,'2018-07-03 19:37:55','2018-07-03 19:37:55');

/*!40000 ALTER TABLE `user_task_26` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_27
# ------------------------------------------------------------

CREATE TABLE `user_task_27` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_27` WRITE;
/*!40000 ALTER TABLE `user_task_27` DISABLE KEYS */;

INSERT INTO `user_task_27` (`uid`, `taskid`, `totaltimes`, `daytimes`, `addtime`, `modtime`)
VALUES
	(20000027,1,1,1,'2018-07-03 19:43:23','2018-07-03 19:43:23');

/*!40000 ALTER TABLE `user_task_27` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_28
# ------------------------------------------------------------

CREATE TABLE `user_task_28` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_28` WRITE;
/*!40000 ALTER TABLE `user_task_28` DISABLE KEYS */;

INSERT INTO `user_task_28` (`uid`, `taskid`, `totaltimes`, `daytimes`, `addtime`, `modtime`)
VALUES
	(20000028,1,1,1,'2018-07-03 19:50:57','2018-07-03 19:50:57');

/*!40000 ALTER TABLE `user_task_28` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_29
# ------------------------------------------------------------

CREATE TABLE `user_task_29` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_29` WRITE;
/*!40000 ALTER TABLE `user_task_29` DISABLE KEYS */;

INSERT INTO `user_task_29` (`uid`, `taskid`, `totaltimes`, `daytimes`, `addtime`, `modtime`)
VALUES
	(20000029,1,1,1,'2018-07-03 19:53:51','2018-07-03 19:53:51');

/*!40000 ALTER TABLE `user_task_29` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_3
# ------------------------------------------------------------

CREATE TABLE `user_task_3` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_3` WRITE;
/*!40000 ALTER TABLE `user_task_3` DISABLE KEYS */;

INSERT INTO `user_task_3` (`uid`, `taskid`, `totaltimes`, `daytimes`, `addtime`, `modtime`)
VALUES
	(21000203,2,1,1,'2018-06-20 18:00:56','2018-06-20 18:00:56'),
	(21000203,4,1,1,'2018-06-30 17:30:43','2018-06-30 17:30:43'),
	(21000203,7,1,1,'2018-06-21 10:20:48','2018-06-21 10:20:48'),
	(21000203,8,3,3,'2018-06-30 16:07:37','2018-06-30 16:07:46'),
	(21000203,10,40,5,'2018-06-30 17:30:43','2018-07-13 17:12:22');

/*!40000 ALTER TABLE `user_task_3` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_30
# ------------------------------------------------------------

CREATE TABLE `user_task_30` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_30` WRITE;
/*!40000 ALTER TABLE `user_task_30` DISABLE KEYS */;

INSERT INTO `user_task_30` (`uid`, `taskid`, `totaltimes`, `daytimes`, `addtime`, `modtime`)
VALUES
	(20000030,1,1,1,'2018-07-04 15:10:43','2018-07-04 15:10:43');

/*!40000 ALTER TABLE `user_task_30` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_31
# ------------------------------------------------------------

CREATE TABLE `user_task_31` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_31` WRITE;
/*!40000 ALTER TABLE `user_task_31` DISABLE KEYS */;

INSERT INTO `user_task_31` (`uid`, `taskid`, `totaltimes`, `daytimes`, `addtime`, `modtime`)
VALUES
	(20000031,1,1,1,'2018-07-04 15:41:19','2018-07-04 15:41:19'),
	(20000031,2,1,1,'2018-07-07 15:49:14','2018-07-07 15:49:14'),
	(20000031,4,1,1,'2018-07-05 17:40:19','2018-07-05 17:40:19'),
	(20000031,7,1,1,'2018-07-05 17:41:08','2018-07-05 17:41:08'),
	(20000031,8,1,1,'2018-07-19 16:39:21','2018-07-19 16:39:21'),
	(20000031,9,9,6,'2018-07-10 19:55:58','2018-07-19 16:50:34'),
	(20000031,10,74,2,'2018-07-05 17:40:19','2018-07-19 16:48:20');

/*!40000 ALTER TABLE `user_task_31` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_32
# ------------------------------------------------------------

CREATE TABLE `user_task_32` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_33
# ------------------------------------------------------------

CREATE TABLE `user_task_33` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_34
# ------------------------------------------------------------

CREATE TABLE `user_task_34` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_35
# ------------------------------------------------------------

CREATE TABLE `user_task_35` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_36
# ------------------------------------------------------------

CREATE TABLE `user_task_36` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_37
# ------------------------------------------------------------

CREATE TABLE `user_task_37` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_38
# ------------------------------------------------------------

CREATE TABLE `user_task_38` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_39
# ------------------------------------------------------------

CREATE TABLE `user_task_39` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_4
# ------------------------------------------------------------

CREATE TABLE `user_task_4` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_4` WRITE;
/*!40000 ALTER TABLE `user_task_4` DISABLE KEYS */;

INSERT INTO `user_task_4` (`uid`, `taskid`, `totaltimes`, `daytimes`, `addtime`, `modtime`)
VALUES
	(21000204,7,1,1,'2018-06-21 16:05:24','2018-06-21 16:05:24');

/*!40000 ALTER TABLE `user_task_4` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_40
# ------------------------------------------------------------

CREATE TABLE `user_task_40` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_41
# ------------------------------------------------------------

CREATE TABLE `user_task_41` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_42
# ------------------------------------------------------------

CREATE TABLE `user_task_42` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_43
# ------------------------------------------------------------

CREATE TABLE `user_task_43` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_44
# ------------------------------------------------------------

CREATE TABLE `user_task_44` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_45
# ------------------------------------------------------------

CREATE TABLE `user_task_45` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_45` WRITE;
/*!40000 ALTER TABLE `user_task_45` DISABLE KEYS */;

INSERT INTO `user_task_45` (`uid`, `taskid`, `totaltimes`, `daytimes`, `addtime`, `modtime`)
VALUES
	(21000345,2,1,1,'2018-05-09 14:25:28','2018-05-10 14:58:44'),
	(21000345,4,1,1,'2018-05-09 14:20:27','2018-05-09 14:20:27'),
	(21000345,6,1,1,'2018-05-12 15:50:08','2018-05-12 15:50:08');

/*!40000 ALTER TABLE `user_task_45` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_46
# ------------------------------------------------------------

CREATE TABLE `user_task_46` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_47
# ------------------------------------------------------------

CREATE TABLE `user_task_47` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_48
# ------------------------------------------------------------

CREATE TABLE `user_task_48` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_49
# ------------------------------------------------------------

CREATE TABLE `user_task_49` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_5
# ------------------------------------------------------------

CREATE TABLE `user_task_5` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_5` WRITE;
/*!40000 ALTER TABLE `user_task_5` DISABLE KEYS */;

INSERT INTO `user_task_5` (`uid`, `taskid`, `totaltimes`, `daytimes`, `addtime`, `modtime`)
VALUES
	(21000205,2,1,1,'2018-06-14 17:11:21','2018-06-14 17:11:21'),
	(21000205,4,1,1,'2018-06-14 20:05:32','2018-06-14 20:05:32'),
	(21000205,7,1,1,'2018-06-23 14:43:01','2018-06-23 14:43:01'),
	(21000205,8,7,1,'2018-06-29 14:40:31','2018-07-02 15:47:06'),
	(21000205,9,22,4,'2018-06-29 16:37:17','2018-07-03 14:19:25'),
	(21000205,10,10,9,'2018-06-29 16:49:27','2018-06-30 18:44:21');

/*!40000 ALTER TABLE `user_task_5` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_50
# ------------------------------------------------------------

CREATE TABLE `user_task_50` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_51
# ------------------------------------------------------------

CREATE TABLE `user_task_51` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_52
# ------------------------------------------------------------

CREATE TABLE `user_task_52` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_53
# ------------------------------------------------------------

CREATE TABLE `user_task_53` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_54
# ------------------------------------------------------------

CREATE TABLE `user_task_54` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_55
# ------------------------------------------------------------

CREATE TABLE `user_task_55` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_56
# ------------------------------------------------------------

CREATE TABLE `user_task_56` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_57
# ------------------------------------------------------------

CREATE TABLE `user_task_57` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_58
# ------------------------------------------------------------

CREATE TABLE `user_task_58` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_59
# ------------------------------------------------------------

CREATE TABLE `user_task_59` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_6
# ------------------------------------------------------------

CREATE TABLE `user_task_6` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_6` WRITE;
/*!40000 ALTER TABLE `user_task_6` DISABLE KEYS */;

INSERT INTO `user_task_6` (`uid`, `taskid`, `totaltimes`, `daytimes`, `addtime`, `modtime`)
VALUES
	(21000206,1,1,1,'2018-07-03 19:48:43','2018-07-03 19:48:43'),
	(21000206,2,2,1,'2018-06-14 18:59:27','2018-07-03 19:48:18'),
	(21000206,4,1,1,'2018-06-21 11:28:01','2018-06-21 11:28:01'),
	(21000206,8,4,1,'2018-06-30 13:37:13','2018-07-02 15:10:12'),
	(21000206,9,34,6,'2018-06-30 14:16:23','2018-07-17 14:10:22'),
	(21000206,10,36,3,'2018-06-30 10:36:02','2018-07-19 17:03:32');

/*!40000 ALTER TABLE `user_task_6` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_60
# ------------------------------------------------------------

CREATE TABLE `user_task_60` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_61
# ------------------------------------------------------------

CREATE TABLE `user_task_61` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_62
# ------------------------------------------------------------

CREATE TABLE `user_task_62` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_63
# ------------------------------------------------------------

CREATE TABLE `user_task_63` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_64
# ------------------------------------------------------------

CREATE TABLE `user_task_64` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_65
# ------------------------------------------------------------

CREATE TABLE `user_task_65` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_66
# ------------------------------------------------------------

CREATE TABLE `user_task_66` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_67
# ------------------------------------------------------------

CREATE TABLE `user_task_67` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_68
# ------------------------------------------------------------

CREATE TABLE `user_task_68` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_69
# ------------------------------------------------------------

CREATE TABLE `user_task_69` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_7
# ------------------------------------------------------------

CREATE TABLE `user_task_7` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_7` WRITE;
/*!40000 ALTER TABLE `user_task_7` DISABLE KEYS */;

INSERT INTO `user_task_7` (`uid`, `taskid`, `totaltimes`, `daytimes`, `addtime`, `modtime`)
VALUES
	(20000007,2,1,1,'2018-06-06 15:29:49','2018-06-06 15:29:49');

/*!40000 ALTER TABLE `user_task_7` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_70
# ------------------------------------------------------------

CREATE TABLE `user_task_70` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_71
# ------------------------------------------------------------

CREATE TABLE `user_task_71` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_72
# ------------------------------------------------------------

CREATE TABLE `user_task_72` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_73
# ------------------------------------------------------------

CREATE TABLE `user_task_73` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_74
# ------------------------------------------------------------

CREATE TABLE `user_task_74` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_75
# ------------------------------------------------------------

CREATE TABLE `user_task_75` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_76
# ------------------------------------------------------------

CREATE TABLE `user_task_76` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_77
# ------------------------------------------------------------

CREATE TABLE `user_task_77` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_78
# ------------------------------------------------------------

CREATE TABLE `user_task_78` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_79
# ------------------------------------------------------------

CREATE TABLE `user_task_79` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_8
# ------------------------------------------------------------

CREATE TABLE `user_task_8` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_80
# ------------------------------------------------------------

CREATE TABLE `user_task_80` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_81
# ------------------------------------------------------------

CREATE TABLE `user_task_81` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_82
# ------------------------------------------------------------

CREATE TABLE `user_task_82` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_83
# ------------------------------------------------------------

CREATE TABLE `user_task_83` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_84
# ------------------------------------------------------------

CREATE TABLE `user_task_84` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_85
# ------------------------------------------------------------

CREATE TABLE `user_task_85` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_86
# ------------------------------------------------------------

CREATE TABLE `user_task_86` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_87
# ------------------------------------------------------------

CREATE TABLE `user_task_87` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_88
# ------------------------------------------------------------

CREATE TABLE `user_task_88` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_89
# ------------------------------------------------------------

CREATE TABLE `user_task_89` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_9
# ------------------------------------------------------------

CREATE TABLE `user_task_9` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_9` WRITE;
/*!40000 ALTER TABLE `user_task_9` DISABLE KEYS */;

INSERT INTO `user_task_9` (`uid`, `taskid`, `totaltimes`, `daytimes`, `addtime`, `modtime`)
VALUES
	(20000009,2,1,1,'2018-05-17 11:45:22','2018-05-16 11:45:22'),
	(20000009,6,1,1,'2018-05-17 11:45:22','2018-05-17 11:45:22'),
	(21000209,2,2,1,'2018-06-22 18:15:26','2018-07-01 09:55:23'),
	(21000209,4,1,1,'2018-06-22 18:43:48','2018-06-22 18:43:48'),
	(21000209,7,1,1,'2018-06-22 18:12:13','2018-06-22 18:12:13'),
	(21000209,8,3,3,'2018-06-30 17:02:57','2018-06-30 17:03:01'),
	(21000209,9,5,1,'2018-06-30 17:22:50','2018-07-03 11:35:15'),
	(21000209,10,9,1,'2018-06-30 17:12:12','2018-07-03 11:32:49');

/*!40000 ALTER TABLE `user_task_9` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_90
# ------------------------------------------------------------

CREATE TABLE `user_task_90` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_91
# ------------------------------------------------------------

CREATE TABLE `user_task_91` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_92
# ------------------------------------------------------------

CREATE TABLE `user_task_92` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_93
# ------------------------------------------------------------

CREATE TABLE `user_task_93` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_94
# ------------------------------------------------------------

CREATE TABLE `user_task_94` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_95
# ------------------------------------------------------------

CREATE TABLE `user_task_95` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_96
# ------------------------------------------------------------

CREATE TABLE `user_task_96` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_97
# ------------------------------------------------------------

CREATE TABLE `user_task_97` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_98
# ------------------------------------------------------------

CREATE TABLE `user_task_98` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_99
# ------------------------------------------------------------

CREATE TABLE `user_task_99` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0',
  `totaltimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务累积次数',
  `daytimes` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '此任务当天累积次数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  UNIQUE KEY `uk_user_task` (`uid`,`taskid`),
  KEY `taskid` (`taskid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_0
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_0` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_logs_0` WRITE;
/*!40000 ALTER TABLE `user_task_logs_0` DISABLE KEYS */;

INSERT INTO `user_task_logs_0` (`id`, `uid`, `taskid`, `award`, `orderid`, `addtime`, `modtime`)
VALUES
	(1,10000000,9,'{\"exp\":3}',0,'2018-07-13 17:08:39','0000-00-00 00:00:00');

/*!40000 ALTER TABLE `user_task_logs_0` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_logs_1
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_1` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_10
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_10` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_11
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_11` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_12
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_12` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_13
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_13` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_14
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_14` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_logs_14` WRITE;
/*!40000 ALTER TABLE `user_task_logs_14` DISABLE KEYS */;

INSERT INTO `user_task_logs_14` (`id`, `uid`, `taskid`, `award`, `orderid`, `addtime`, `modtime`)
VALUES
	(1,21000214,10,'{\"exp\":1}',0,'2018-07-02 17:04:49','0000-00-00 00:00:00'),
	(2,21000214,4,'{\"grape\":10}',474724252977725440,'2018-07-02 17:04:49','2018-07-02 17:04:49');

/*!40000 ALTER TABLE `user_task_logs_14` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_logs_15
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_15` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_16
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_16` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_logs_16` WRITE;
/*!40000 ALTER TABLE `user_task_logs_16` DISABLE KEYS */;

INSERT INTO `user_task_logs_16` (`id`, `uid`, `taskid`, `award`, `orderid`, `addtime`, `modtime`)
VALUES
	(1,20000016,1,'{\"exp\":5}',0,'2018-05-29 21:24:14','0000-00-00 00:00:00'),
	(2,21000216,2,'{\"grape\":2}',468228758252290048,'2018-06-14 18:54:02','2018-06-14 18:54:02'),
	(3,21000216,7,'{\"grape\":10}',471408928106741760,'2018-06-23 13:30:54','2018-06-23 13:30:54'),
	(4,21000216,9,'{\"exp\":11}',0,'2018-07-03 11:35:14','0000-00-00 00:00:00'),
	(5,21000216,9,'{\"exp\":1}',0,'2018-07-03 11:35:26','0000-00-00 00:00:00'),
	(6,21000216,9,'{\"exp\":1}',0,'2018-07-03 11:36:22','0000-00-00 00:00:00'),
	(7,21000216,9,'{\"exp\":1}',0,'2018-07-03 11:36:30','0000-00-00 00:00:00'),
	(8,21000216,8,'{\"grape\":1}',478322021642534912,'2018-07-12 15:21:04','2018-07-12 15:21:04'),
	(9,21000216,10,'{\"exp\":0}',0,'2018-07-12 16:04:13','0000-00-00 00:00:00'),
	(10,21000216,4,'{\"grape\":10}',478332882343952384,'2018-07-12 16:04:13','2018-07-12 16:04:13'),
	(11,21000216,10,'{\"exp\":0}',0,'2018-07-12 19:38:03','0000-00-00 00:00:00');

/*!40000 ALTER TABLE `user_task_logs_16` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_logs_17
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_17` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_logs_17` WRITE;
/*!40000 ALTER TABLE `user_task_logs_17` DISABLE KEYS */;

INSERT INTO `user_task_logs_17` (`id`, `uid`, `taskid`, `award`, `orderid`, `addtime`, `modtime`)
VALUES
	(1,20000017,1,'{\"exp\":5}',0,'2018-05-30 10:20:10','0000-00-00 00:00:00'),
	(2,21000217,7,'{\"grape\":10}',470706341023842304,'2018-06-21 14:59:04','2018-06-21 14:59:04'),
	(3,21000217,4,'{\"exp\":5,\"grape\":10}',470724002621423616,'2018-06-21 16:09:15','2018-06-21 16:09:15'),
	(4,21000217,2,'{\"grape\":2}',471131346547245056,'2018-06-22 19:07:53','2018-06-22 19:07:53'),
	(5,21000217,10,'{\"exp\":2}',0,'2018-06-29 19:28:22','0000-00-00 00:00:00'),
	(6,21000217,9,'{\"exp\":10}',0,'2018-06-29 19:30:28','0000-00-00 00:00:00'),
	(7,21000217,10,'{\"exp\":0}',0,'2018-06-29 19:53:22','0000-00-00 00:00:00'),
	(8,21000217,9,'{\"exp\":1}',0,'2018-06-29 20:17:20','0000-00-00 00:00:00'),
	(9,21000217,9,'{\"exp\":1}',0,'2018-06-29 20:18:15','0000-00-00 00:00:00'),
	(10,21000217,9,'{\"exp\":1}',0,'2018-06-29 20:19:11','0000-00-00 00:00:00'),
	(11,21000217,10,'{\"exp\":2}',0,'2018-06-29 20:30:26','0000-00-00 00:00:00'),
	(12,21000217,10,'{\"exp\":0}',0,'2018-06-29 20:34:41','0000-00-00 00:00:00'),
	(13,21000217,10,'{\"exp\":0}',0,'2018-06-29 20:35:46','0000-00-00 00:00:00'),
	(14,21000217,9,'{\"exp\":1}',0,'2018-06-29 20:43:40','0000-00-00 00:00:00'),
	(15,21000217,10,'{\"exp\":0}',0,'2018-06-29 20:45:15','0000-00-00 00:00:00'),
	(16,21000217,10,'{\"exp\":0}',0,'2018-06-29 20:55:40','0000-00-00 00:00:00'),
	(17,21000217,10,'{\"exp\":0}',0,'2018-06-30 10:58:17','0000-00-00 00:00:00'),
	(18,21000217,10,'{\"exp\":0}',0,'2018-06-30 11:00:03','0000-00-00 00:00:00'),
	(19,21000217,10,'{\"exp\":0}',0,'2018-06-30 14:50:46','0000-00-00 00:00:00'),
	(20,21000217,10,'{\"exp\":2}',0,'2018-06-30 15:30:30','0000-00-00 00:00:00'),
	(21,21000217,10,'{\"exp\":2}',0,'2018-06-30 17:23:16','0000-00-00 00:00:00'),
	(22,21000217,10,'{\"exp\":0}',0,'2018-06-30 17:40:53','0000-00-00 00:00:00'),
	(23,21000217,10,'{\"exp\":0}',0,'2018-06-30 17:44:59','0000-00-00 00:00:00'),
	(24,21000217,10,'{\"exp\":0}',0,'2018-06-30 17:54:01','0000-00-00 00:00:00'),
	(25,21000217,10,'{\"exp\":0}',0,'2018-06-30 17:56:08','0000-00-00 00:00:00'),
	(26,21000217,10,'{\"exp\":0}',0,'2018-06-30 18:50:12','0000-00-00 00:00:00'),
	(27,21000217,10,'{\"exp\":0}',0,'2018-06-30 18:54:30','0000-00-00 00:00:00'),
	(28,21000217,10,'{\"exp\":0}',0,'2018-06-30 18:58:22','0000-00-00 00:00:00'),
	(29,21000217,10,'{\"exp\":0}',0,'2018-07-10 15:30:37','0000-00-00 00:00:00'),
	(30,21000217,10,'{\"exp\":0}',0,'2018-07-10 15:32:39','0000-00-00 00:00:00'),
	(31,21000217,10,'{\"exp\":0}',0,'2018-07-10 15:33:43','0000-00-00 00:00:00'),
	(32,21000217,10,'{\"exp\":0}',0,'2018-07-10 15:45:24','0000-00-00 00:00:00'),
	(33,21000217,10,'{\"exp\":0}',0,'2018-07-10 15:47:00','0000-00-00 00:00:00'),
	(34,21000217,10,'{\"exp\":0}',0,'2018-07-10 15:48:34','0000-00-00 00:00:00'),
	(35,21000217,10,'{\"exp\":0}',0,'2018-07-10 16:21:02','0000-00-00 00:00:00'),
	(36,21000217,10,'{\"exp\":0}',0,'2018-07-10 16:35:02','0000-00-00 00:00:00'),
	(37,21000217,10,'{\"exp\":0}',0,'2018-07-10 16:37:15','0000-00-00 00:00:00'),
	(38,21000217,10,'{\"exp\":0}',0,'2018-07-10 16:40:11','0000-00-00 00:00:00'),
	(39,21000217,10,'{\"exp\":0}',0,'2018-07-10 16:48:28','0000-00-00 00:00:00'),
	(40,21000217,10,'{\"exp\":0}',0,'2018-07-10 17:23:05','0000-00-00 00:00:00'),
	(41,21000217,10,'{\"exp\":0}',0,'2018-07-10 17:23:54','0000-00-00 00:00:00'),
	(42,21000217,10,'{\"exp\":0}',0,'2018-07-10 17:27:50','0000-00-00 00:00:00'),
	(43,21000217,10,'{\"exp\":0}',0,'2018-07-10 17:28:52','0000-00-00 00:00:00'),
	(44,21000217,10,'{\"exp\":0}',0,'2018-07-10 17:32:39','0000-00-00 00:00:00'),
	(45,21000217,10,'{\"exp\":0}',0,'2018-07-10 17:36:50','0000-00-00 00:00:00'),
	(46,21000217,10,'{\"exp\":0}',0,'2018-07-10 17:37:45','0000-00-00 00:00:00'),
	(47,21000217,9,'{\"exp\":0}',0,'2018-07-10 17:58:09','0000-00-00 00:00:00'),
	(48,21000217,9,'{\"exp\":1}',0,'2018-07-10 17:58:35','0000-00-00 00:00:00'),
	(49,21000217,9,'{\"exp\":2}',0,'2018-07-10 17:58:48','0000-00-00 00:00:00'),
	(50,21000217,10,'{\"exp\":0}',0,'2018-07-10 19:36:48','0000-00-00 00:00:00'),
	(51,21000217,10,'{\"exp\":0}',0,'2018-07-10 19:37:30','0000-00-00 00:00:00'),
	(52,21000217,10,'{\"exp\":0}',0,'2018-07-10 19:39:44','0000-00-00 00:00:00'),
	(53,21000217,10,'{\"exp\":0}',0,'2018-07-10 19:42:09','0000-00-00 00:00:00'),
	(54,21000217,9,'{\"exp\":0}',0,'2018-07-10 19:48:05','0000-00-00 00:00:00'),
	(55,21000217,9,'{\"exp\":37}',0,'2018-07-10 19:49:17','0000-00-00 00:00:00'),
	(56,21000217,9,'{\"exp\":1}',0,'2018-07-10 19:51:48','0000-00-00 00:00:00'),
	(57,21000217,9,'{\"exp\":5}',0,'2018-07-10 19:51:57','0000-00-00 00:00:00'),
	(58,21000217,9,'{\"exp\":1}',0,'2018-07-10 19:53:20','0000-00-00 00:00:00'),
	(59,21000217,9,'{\"exp\":1}',0,'2018-07-10 19:53:29','0000-00-00 00:00:00'),
	(60,21000217,9,'{\"exp\":0}',0,'2018-07-10 20:18:22','0000-00-00 00:00:00'),
	(61,21000217,9,'{\"exp\":1}',0,'2018-07-10 20:18:33','0000-00-00 00:00:00'),
	(62,21000217,9,'{\"exp\":1}',0,'2018-07-10 20:19:07','0000-00-00 00:00:00'),
	(63,21000217,10,'{\"exp\":0}',0,'2018-07-10 20:36:18','0000-00-00 00:00:00'),
	(64,21000217,10,'{\"exp\":0}',0,'2018-07-10 21:02:35','0000-00-00 00:00:00'),
	(65,21000217,10,'{\"exp\":0}',0,'2018-07-10 21:06:42','0000-00-00 00:00:00'),
	(66,21000217,10,'{\"exp\":0}',0,'2018-07-10 21:07:37','0000-00-00 00:00:00'),
	(67,21000217,10,'{\"exp\":0}',0,'2018-07-10 21:08:18','0000-00-00 00:00:00'),
	(68,21000217,10,'{\"exp\":0}',0,'2018-07-10 21:09:05','0000-00-00 00:00:00'),
	(69,21000217,10,'{\"exp\":0}',0,'2018-07-10 21:10:55','0000-00-00 00:00:00'),
	(70,21000217,10,'{\"exp\":0}',0,'2018-07-10 21:11:50','0000-00-00 00:00:00'),
	(71,21000217,10,'{\"exp\":0}',0,'2018-07-10 21:12:18','0000-00-00 00:00:00'),
	(72,21000217,10,'{\"exp\":0}',0,'2018-07-11 10:52:46','0000-00-00 00:00:00'),
	(73,21000217,10,'{\"exp\":0}',0,'2018-07-11 10:55:56','0000-00-00 00:00:00'),
	(74,21000217,10,'{\"exp\":0}',0,'2018-07-11 10:56:35','0000-00-00 00:00:00'),
	(75,21000217,10,'{\"exp\":0}',0,'2018-07-11 10:57:26','0000-00-00 00:00:00'),
	(76,21000217,10,'{\"exp\":0}',0,'2018-07-11 10:59:56','0000-00-00 00:00:00'),
	(77,21000217,10,'{\"exp\":0}',0,'2018-07-11 11:01:46','0000-00-00 00:00:00'),
	(78,21000217,10,'{\"exp\":0}',0,'2018-07-11 11:03:11','0000-00-00 00:00:00'),
	(79,21000217,10,'{\"exp\":0}',0,'2018-07-11 11:08:24','0000-00-00 00:00:00'),
	(80,21000217,10,'{\"exp\":0}',0,'2018-07-11 11:09:06','0000-00-00 00:00:00'),
	(81,21000217,10,'{\"exp\":0}',0,'2018-07-11 11:18:13','0000-00-00 00:00:00'),
	(82,21000217,10,'{\"exp\":0}',0,'2018-07-11 11:21:17','0000-00-00 00:00:00'),
	(83,21000217,10,'{\"exp\":0}',0,'2018-07-11 11:22:13','0000-00-00 00:00:00'),
	(84,21000217,10,'{\"exp\":0}',0,'2018-07-11 11:23:33','0000-00-00 00:00:00'),
	(85,21000217,10,'{\"exp\":0}',0,'2018-07-11 14:02:39','0000-00-00 00:00:00'),
	(86,21000217,10,'{\"exp\":0}',0,'2018-07-11 14:03:26','0000-00-00 00:00:00'),
	(87,21000217,10,'{\"exp\":0}',0,'2018-07-11 14:13:03','0000-00-00 00:00:00'),
	(88,21000217,10,'{\"exp\":0}',0,'2018-07-11 14:19:26','0000-00-00 00:00:00'),
	(89,21000217,10,'{\"exp\":0}',0,'2018-07-11 14:20:48','0000-00-00 00:00:00'),
	(90,21000217,10,'{\"exp\":0}',0,'2018-07-11 14:21:52','0000-00-00 00:00:00'),
	(91,21000217,10,'{\"exp\":0}',0,'2018-07-11 14:31:54','0000-00-00 00:00:00'),
	(92,21000217,10,'{\"exp\":0}',0,'2018-07-11 14:44:50','0000-00-00 00:00:00'),
	(93,21000217,10,'{\"exp\":0}',0,'2018-07-11 15:36:05','0000-00-00 00:00:00'),
	(94,21000217,10,'{\"exp\":0}',0,'2018-07-11 15:37:03','0000-00-00 00:00:00'),
	(95,21000217,10,'{\"exp\":0}',0,'2018-07-11 15:38:32','0000-00-00 00:00:00'),
	(96,21000217,10,'{\"exp\":0}',0,'2018-07-11 15:39:00','0000-00-00 00:00:00'),
	(97,21000217,10,'{\"exp\":0}',0,'2018-07-11 15:42:50','0000-00-00 00:00:00'),
	(98,21000217,10,'{\"exp\":0}',0,'2018-07-11 16:01:28','0000-00-00 00:00:00'),
	(99,21000217,10,'{\"exp\":0}',0,'2018-07-11 16:04:18','0000-00-00 00:00:00'),
	(100,21000217,10,'{\"exp\":0}',0,'2018-07-11 16:05:40','0000-00-00 00:00:00'),
	(101,21000217,10,'{\"exp\":0}',0,'2018-07-11 18:56:43','0000-00-00 00:00:00'),
	(102,21000217,10,'{\"exp\":5}',0,'2018-07-16 11:41:58','0000-00-00 00:00:00'),
	(103,21000217,10,'{\"exp\":0}',0,'2018-07-16 11:42:42','0000-00-00 00:00:00'),
	(104,21000217,10,'{\"exp\":5}',0,'2018-07-16 11:43:51','0000-00-00 00:00:00'),
	(105,21000217,10,'{\"exp\":0}',0,'2018-07-16 11:44:41','0000-00-00 00:00:00'),
	(106,21000217,10,'{\"exp\":0}',0,'2018-07-16 11:45:26','0000-00-00 00:00:00'),
	(107,21000217,9,'{\"exp\":0}',0,'2018-07-16 19:21:44','0000-00-00 00:00:00'),
	(108,21000217,9,'{\"exp\":0}',0,'2018-07-16 20:21:49','0000-00-00 00:00:00'),
	(109,21000217,9,'{\"exp\":10}',0,'2018-07-16 20:33:23','0000-00-00 00:00:00'),
	(110,21000217,9,'{\"exp\":0}',0,'2018-07-16 20:37:13','0000-00-00 00:00:00'),
	(111,21000217,9,'{\"exp\":0}',0,'2018-07-16 20:37:38','0000-00-00 00:00:00'),
	(112,21000217,9,'{\"exp\":8}',0,'2018-07-17 10:40:44','0000-00-00 00:00:00'),
	(113,21000217,9,'{\"exp\":10}',0,'2018-07-17 10:44:35','0000-00-00 00:00:00'),
	(114,21000217,9,'{\"exp\":10}',0,'2018-07-17 10:45:23','0000-00-00 00:00:00'),
	(115,21000217,9,'{\"exp\":1}',0,'2018-07-19 11:32:49','0000-00-00 00:00:00'),
	(116,21000217,9,'{\"exp\":10}',0,'2018-07-19 11:33:12','0000-00-00 00:00:00');

/*!40000 ALTER TABLE `user_task_logs_17` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_logs_18
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_18` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_logs_18` WRITE;
/*!40000 ALTER TABLE `user_task_logs_18` DISABLE KEYS */;

INSERT INTO `user_task_logs_18` (`id`, `uid`, `taskid`, `award`, `orderid`, `addtime`, `modtime`)
VALUES
	(1,20000018,1,'{\"exp\":5}',0,'2018-05-30 11:05:57','0000-00-00 00:00:00'),
	(2,20000018,2,'{\"grape\":2}',463136156406513664,'2018-05-31 17:37:52','2018-05-31 17:37:52'),
	(3,21000218,4,'{\"exp\":5,\"grape\":10}',468207683502080000,'2018-06-14 17:30:18','2018-06-14 17:30:18'),
	(4,21000218,7,'{\"grape\":10}',480880455659290624,'2018-07-19 16:47:22','2018-07-19 16:47:22');

/*!40000 ALTER TABLE `user_task_logs_18` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_logs_19
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_19` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_logs_19` WRITE;
/*!40000 ALTER TABLE `user_task_logs_19` DISABLE KEYS */;

INSERT INTO `user_task_logs_19` (`id`, `uid`, `taskid`, `award`, `orderid`, `addtime`, `modtime`)
VALUES
	(1,20000019,1,'{\"exp\":5}',0,'2018-06-22 18:12:21','0000-00-00 00:00:00'),
	(2,20000019,7,'{\"grape\":10}',471118268497657856,'2018-06-22 18:15:55','2018-06-22 18:15:55'),
	(3,20000019,8,'{\"grape\":1}',473998635932057600,'2018-06-30 17:01:28','2018-06-30 17:01:28'),
	(4,20000019,8,'{\"grape\":1}',473998696527167488,'2018-06-30 17:01:43','2018-06-30 17:01:43'),
	(5,20000019,8,'{\"grape\":1}',473998746363887616,'2018-06-30 17:01:55','2018-06-30 17:01:55'),
	(6,20000019,2,'{\"grape\":2}',474003217693278208,'2018-06-30 17:19:41','2018-06-30 17:19:41'),
	(7,20000019,10,'{\"exp\":5}',0,'2018-06-30 17:23:02','0000-00-00 00:00:00'),
	(8,20000019,4,'{\"grape\":10}',474004062325440512,'2018-06-30 17:23:02','2018-06-30 17:23:02');

/*!40000 ALTER TABLE `user_task_logs_19` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_logs_2
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_2` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_logs_2` WRITE;
/*!40000 ALTER TABLE `user_task_logs_2` DISABLE KEYS */;

INSERT INTO `user_task_logs_2` (`id`, `uid`, `taskid`, `award`, `orderid`, `addtime`, `modtime`)
VALUES
	(1,21000202,2,'{\"grape\":2}',471146579059802112,'2018-06-22 20:08:25','2018-06-22 20:08:25'),
	(2,21000202,7,'{\"grape\":10}',471147011811311616,'2018-06-22 20:10:08','2018-06-22 20:10:08'),
	(3,21000202,2,'{\"grape\":2}',471363858737397760,'2018-06-23 10:31:49','2018-06-23 10:31:49'),
	(4,21000202,4,'{\"exp\":5,\"grape\":10}',471409729764065280,'2018-06-23 13:34:05','2018-06-23 13:34:05'),
	(5,21000202,8,'{\"grape\":1}',474641725164355584,'2018-07-02 11:36:53','2018-07-02 11:36:53'),
	(6,21000202,10,'{\"exp\":0}',0,'2018-07-02 11:41:18','0000-00-00 00:00:00'),
	(7,21000202,10,'{\"exp\":0}',0,'2018-07-12 17:42:38','0000-00-00 00:00:00'),
	(8,21000202,10,'{\"exp\":0}',0,'2018-07-12 18:47:33','0000-00-00 00:00:00'),
	(9,21000202,10,'{\"exp\":0}',0,'2018-07-12 18:53:05','0000-00-00 00:00:00'),
	(10,21000202,9,'{\"exp\":0}',0,'2018-07-19 16:50:19','0000-00-00 00:00:00'),
	(11,21000202,9,'{\"exp\":0}',0,'2018-07-19 16:51:34','0000-00-00 00:00:00');

/*!40000 ALTER TABLE `user_task_logs_2` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_logs_20
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_20` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_logs_20` WRITE;
/*!40000 ALTER TABLE `user_task_logs_20` DISABLE KEYS */;

INSERT INTO `user_task_logs_20` (`id`, `uid`, `taskid`, `award`, `orderid`, `addtime`, `modtime`)
VALUES
	(1,20000020,1,'{\"exp\":5}',0,'2018-06-30 17:15:20','0000-00-00 00:00:00'),
	(2,20000020,10,'{\"exp\":5}',0,'2018-06-30 17:24:48','0000-00-00 00:00:00'),
	(3,20000020,4,'{\"grape\":10}',474004505894060032,'2018-06-30 17:24:48','2018-06-30 17:24:48'),
	(4,20000020,7,'{\"grape\":10}',474004798400626688,'2018-06-30 17:25:58','2018-06-30 17:25:58');

/*!40000 ALTER TABLE `user_task_logs_20` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_logs_21
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_21` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_logs_21` WRITE;
/*!40000 ALTER TABLE `user_task_logs_21` DISABLE KEYS */;

INSERT INTO `user_task_logs_21` (`id`, `uid`, `taskid`, `award`, `orderid`, `addtime`, `modtime`)
VALUES
	(1,20000021,1,'{\"exp\":5}',0,'2018-07-01 09:55:57','0000-00-00 00:00:00');

/*!40000 ALTER TABLE `user_task_logs_21` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_logs_22
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_22` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_logs_22` WRITE;
/*!40000 ALTER TABLE `user_task_logs_22` DISABLE KEYS */;

INSERT INTO `user_task_logs_22` (`id`, `uid`, `taskid`, `award`, `orderid`, `addtime`, `modtime`)
VALUES
	(1,20000022,1,'{\"exp\":5}',0,'2018-07-03 10:39:35','0000-00-00 00:00:00'),
	(2,20000022,7,'{\"grape\":10}',474989870595440640,'2018-07-03 10:40:17','2018-07-03 10:40:17'),
	(3,20000022,10,'{\"exp\":7}',0,'2018-07-04 15:26:15','0000-00-00 00:00:00'),
	(4,20000022,4,'{\"grape\":10}',475424224341655552,'2018-07-04 15:26:15','2018-07-04 15:26:15'),
	(5,20000022,10,'{\"exp\":90}',0,'2018-07-12 15:25:25','0000-00-00 00:00:00'),
	(6,20000022,10,'{\"exp\":100}',0,'2018-07-12 18:50:34','0000-00-00 00:00:00'),
	(7,20000022,10,'{\"exp\":5}',0,'2018-07-19 16:50:20','0000-00-00 00:00:00');

/*!40000 ALTER TABLE `user_task_logs_22` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_logs_23
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_23` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_logs_23` WRITE;
/*!40000 ALTER TABLE `user_task_logs_23` DISABLE KEYS */;

INSERT INTO `user_task_logs_23` (`id`, `uid`, `taskid`, `award`, `orderid`, `addtime`, `modtime`)
VALUES
	(1,20000023,1,'{\"exp\":5}',0,'2018-07-03 14:55:11','0000-00-00 00:00:00'),
	(2,20000023,10,'{\"exp\":9}',0,'2018-07-03 14:57:12','0000-00-00 00:00:00'),
	(3,20000023,4,'{\"grape\":10}',475054523610562560,'2018-07-03 14:57:12','2018-07-03 14:57:12'),
	(4,20000023,8,'{\"grape\":1}',475423753661054976,'2018-07-04 15:24:23','2018-07-04 15:24:23'),
	(5,20000023,8,'{\"grape\":1}',475423761005281280,'2018-07-04 15:24:25','2018-07-04 15:24:25'),
	(6,20000023,9,'{\"exp\":1}',0,'2018-07-04 15:25:45','0000-00-00 00:00:00'),
	(7,20000023,9,'{\"exp\":0}',0,'2018-07-11 20:34:31','0000-00-00 00:00:00'),
	(8,20000023,10,'{\"exp\":15}',0,'2018-07-12 18:50:53','0000-00-00 00:00:00');

/*!40000 ALTER TABLE `user_task_logs_23` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_logs_24
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_24` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_logs_24` WRITE;
/*!40000 ALTER TABLE `user_task_logs_24` DISABLE KEYS */;

INSERT INTO `user_task_logs_24` (`id`, `uid`, `taskid`, `award`, `orderid`, `addtime`, `modtime`)
VALUES
	(1,21000224,2,'{\"grape\":2}',468202361504923648,'2018-06-14 17:09:09','2018-06-14 17:09:09'),
	(2,21000224,7,'{\"grape\":10}',470653868015878144,'2018-06-21 11:30:34','2018-06-21 11:30:34'),
	(3,21000224,4,'{\"exp\":5,\"grape\":10}',471414507982290944,'2018-06-23 13:53:04','2018-06-23 13:53:04'),
	(4,21000224,10,'{\"exp\":0}',0,'2018-06-30 18:55:51','0000-00-00 00:00:00'),
	(5,20000024,1,'{\"exp\":5}',0,'2018-07-03 19:28:23','0000-00-00 00:00:00'),
	(6,21000224,10,'{\"exp\":22}',0,'2018-07-06 15:05:43','0000-00-00 00:00:00'),
	(7,21000224,8,'{\"grape\":1}',476523445547433984,'2018-07-07 16:14:10','2018-07-07 16:14:10'),
	(8,21000224,8,'{\"grape\":1}',476523476031635456,'2018-07-07 16:14:17','2018-07-07 16:14:17');

/*!40000 ALTER TABLE `user_task_logs_24` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_logs_25
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_25` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_logs_25` WRITE;
/*!40000 ALTER TABLE `user_task_logs_25` DISABLE KEYS */;

INSERT INTO `user_task_logs_25` (`id`, `uid`, `taskid`, `award`, `orderid`, `addtime`, `modtime`)
VALUES
	(1,21000225,4,'{\"exp\":5,\"grape\":10}',468238065396088832,'2018-06-14 19:31:01','2018-06-14 19:31:01'),
	(2,21000225,7,'{\"grape\":10}',470431345168875520,'2018-06-20 20:46:20','2018-06-20 20:46:20'),
	(3,21000225,8,'{\"grape\":1}',0,'2018-06-29 14:24:16','0000-00-00 00:00:00'),
	(4,21000225,8,'{\"award\":1}',0,'2018-06-29 14:40:51','0000-00-00 00:00:00'),
	(5,21000225,8,'{\"award\":1}',0,'2018-06-29 14:55:45','0000-00-00 00:00:00'),
	(6,21000225,8,'{\"award\":1}',0,'2018-06-29 14:56:40','0000-00-00 00:00:00'),
	(7,21000225,8,'{\"award\":1}',0,'2018-06-29 15:07:24','0000-00-00 00:00:00'),
	(8,21000225,8,'{\"award\":1}',0,'2018-06-29 15:07:55','0000-00-00 00:00:00'),
	(9,21000225,8,'{\"award\":1}',0,'2018-06-29 15:08:01','0000-00-00 00:00:00'),
	(10,21000225,8,'{\"award\":1}',0,'2018-06-29 15:08:34','0000-00-00 00:00:00'),
	(11,21000225,8,'{\"award\":1}',0,'2018-06-29 15:09:54','0000-00-00 00:00:00'),
	(12,21000225,8,'{\"award\":1}',0,'2018-06-29 15:10:13','0000-00-00 00:00:00'),
	(13,21000225,8,'{\"award\":1}',0,'2018-06-29 15:12:03','0000-00-00 00:00:00'),
	(14,21000225,8,'{\"award\":1}',0,'2018-06-29 15:12:21','0000-00-00 00:00:00'),
	(15,21000225,8,'{\"grape\":1}',0,'2018-06-29 15:14:43','0000-00-00 00:00:00'),
	(16,21000225,8,'{\"grape\":1}',0,'2018-06-29 15:14:55','0000-00-00 00:00:00'),
	(17,21000225,8,'{\"grape\":1}',473612928138870784,'2018-06-29 15:28:49','2018-06-29 15:28:49'),
	(18,21000225,8,'{\"grape\":1}',0,'2018-06-29 15:30:03','0000-00-00 00:00:00'),
	(19,21000225,8,'{\"grape\":1}',0,'2018-06-29 15:33:23','0000-00-00 00:00:00'),
	(20,21000225,8,'{\"grape\":1}',0,'2018-06-29 15:45:25','0000-00-00 00:00:00'),
	(21,21000225,8,'{\"grape\":1}',0,'2018-06-29 15:51:01','0000-00-00 00:00:00'),
	(22,21000225,8,'{\"grape\":1}',0,'2018-06-29 15:52:35','0000-00-00 00:00:00'),
	(23,21000225,8,'{\"grape\":1}',0,'2018-06-29 15:54:07','0000-00-00 00:00:00'),
	(24,21000225,8,'{\"grape\":1}',473619782046842880,'2018-06-29 15:56:03','2018-06-29 15:56:03'),
	(25,21000225,8,'{\"grape\":1}',0,'2018-06-29 15:56:06','0000-00-00 00:00:00'),
	(26,21000225,8,'{\"grape\":1}',473620069671239680,'2018-06-29 15:57:11','2018-06-29 15:57:11'),
	(27,21000225,10,'{\"exp\":0}',0,'2018-06-29 16:28:54','0000-00-00 00:00:00'),
	(28,21000225,10,'{\"exp\":0}',0,'2018-06-29 17:50:13','0000-00-00 00:00:00'),
	(29,21000225,8,'{\"grape\":1}',473674274553462784,'2018-06-29 19:32:35','2018-06-29 19:32:35'),
	(30,21000225,10,'{\"exp\":0}',0,'2018-06-30 10:40:48','0000-00-00 00:00:00'),
	(31,21000225,8,'{\"grape\":1}',473905289679077376,'2018-06-30 10:50:33','2018-06-30 10:50:33'),
	(32,21000225,8,'{\"grape\":1}',0,'2018-06-30 13:52:08','0000-00-00 00:00:00'),
	(33,21000225,8,'{\"grape\":1}',473951027947634688,'2018-06-30 13:52:18','2018-06-30 13:52:18'),
	(34,21000225,8,'{\"grape\":1}',473962964475445248,'2018-06-30 14:39:44','2018-06-30 14:39:44'),
	(35,21000225,8,'{\"grape\":1}',473963069857333248,'2018-06-30 14:40:09','2018-06-30 14:40:09'),
	(36,21000225,8,'{\"grape\":1}',473963689062432768,'2018-06-30 14:42:36','2018-06-30 14:42:36'),
	(37,21000225,10,'{\"exp\":0}',0,'2018-06-30 14:52:33','0000-00-00 00:00:00'),
	(38,21000225,10,'{\"exp\":0}',0,'2018-06-30 17:05:13','0000-00-00 00:00:00'),
	(39,21000225,10,'{\"exp\":0}',0,'2018-06-30 17:29:08','0000-00-00 00:00:00'),
	(40,21000225,10,'{\"exp\":0}',0,'2018-07-02 11:20:35','0000-00-00 00:00:00'),
	(41,21000225,8,'{\"grape\":1}',474704246646767616,'2018-07-02 15:45:19','2018-07-02 15:45:19'),
	(42,20000025,1,'{\"exp\":5}',0,'2018-07-03 19:36:28','0000-00-00 00:00:00'),
	(43,21000225,10,'{\"exp\":0}',0,'2018-07-04 20:37:46','0000-00-00 00:00:00'),
	(44,21000225,10,'{\"exp\":0}',0,'2018-07-07 10:32:27','0000-00-00 00:00:00'),
	(45,21000225,10,'{\"exp\":0}',0,'2018-07-07 10:34:08','0000-00-00 00:00:00'),
	(46,21000225,10,'{\"exp\":0}',0,'2018-07-07 10:36:09','0000-00-00 00:00:00'),
	(47,21000225,10,'{\"exp\":0}',0,'2018-07-07 11:13:53','0000-00-00 00:00:00'),
	(48,21000225,10,'{\"exp\":0}',0,'2018-07-07 11:18:40','0000-00-00 00:00:00'),
	(49,21000225,10,'{\"exp\":0}',0,'2018-07-07 11:27:55','0000-00-00 00:00:00'),
	(50,21000225,10,'{\"exp\":0}',0,'2018-07-07 11:30:33','0000-00-00 00:00:00'),
	(51,21000225,10,'{\"exp\":0}',0,'2018-07-07 11:31:42','0000-00-00 00:00:00'),
	(52,21000225,10,'{\"exp\":0}',0,'2018-07-07 11:34:11','0000-00-00 00:00:00'),
	(53,21000225,10,'{\"exp\":0}',0,'2018-07-07 11:38:01','0000-00-00 00:00:00'),
	(54,21000225,10,'{\"exp\":0}',0,'2018-07-07 14:05:04','0000-00-00 00:00:00'),
	(55,21000225,10,'{\"exp\":0}',0,'2018-07-07 14:06:49','0000-00-00 00:00:00'),
	(56,21000225,10,'{\"exp\":0}',0,'2018-07-07 14:20:42','0000-00-00 00:00:00'),
	(57,21000225,10,'{\"exp\":0}',0,'2018-07-07 14:21:51','0000-00-00 00:00:00'),
	(58,21000225,10,'{\"exp\":0}',0,'2018-07-07 14:23:40','0000-00-00 00:00:00'),
	(59,21000225,10,'{\"exp\":0}',0,'2018-07-07 14:29:28','0000-00-00 00:00:00'),
	(60,21000225,10,'{\"exp\":0}',0,'2018-07-07 14:38:58','0000-00-00 00:00:00'),
	(61,21000225,10,'{\"exp\":0}',0,'2018-07-07 14:40:50','0000-00-00 00:00:00'),
	(62,21000225,10,'{\"exp\":0}',0,'2018-07-07 14:42:29','0000-00-00 00:00:00'),
	(63,21000225,10,'{\"exp\":0}',0,'2018-07-07 14:45:12','0000-00-00 00:00:00'),
	(64,21000225,10,'{\"exp\":0}',0,'2018-07-07 14:46:06','0000-00-00 00:00:00'),
	(65,21000225,10,'{\"exp\":0}',0,'2018-07-07 14:47:32','0000-00-00 00:00:00'),
	(66,21000225,10,'{\"exp\":0}',0,'2018-07-07 14:48:41','0000-00-00 00:00:00'),
	(67,21000225,10,'{\"exp\":0}',0,'2018-07-07 14:52:47','0000-00-00 00:00:00'),
	(68,21000225,10,'{\"exp\":0}',0,'2018-07-07 15:04:25','0000-00-00 00:00:00'),
	(69,21000225,10,'{\"exp\":0}',0,'2018-07-07 15:16:08','0000-00-00 00:00:00'),
	(70,21000225,10,'{\"exp\":0}',0,'2018-07-07 15:26:16','0000-00-00 00:00:00'),
	(71,21000225,10,'{\"exp\":0}',0,'2018-07-07 15:32:03','0000-00-00 00:00:00'),
	(72,21000225,10,'{\"exp\":0}',0,'2018-07-07 15:37:01','0000-00-00 00:00:00'),
	(73,21000225,10,'{\"exp\":0}',0,'2018-07-07 15:40:55','0000-00-00 00:00:00'),
	(74,21000225,10,'{\"exp\":0}',0,'2018-07-07 17:08:14','0000-00-00 00:00:00'),
	(75,21000225,10,'{\"exp\":0}',0,'2018-07-07 17:16:50','0000-00-00 00:00:00'),
	(76,21000225,10,'{\"exp\":0}',0,'2018-07-07 17:17:48','0000-00-00 00:00:00'),
	(77,21000225,10,'{\"exp\":0}',0,'2018-07-07 17:20:13','0000-00-00 00:00:00'),
	(78,21000225,10,'{\"exp\":0}',0,'2018-07-07 17:21:20','0000-00-00 00:00:00'),
	(79,21000225,10,'{\"exp\":0}',0,'2018-07-07 17:29:53','0000-00-00 00:00:00'),
	(80,21000225,10,'{\"exp\":0}',0,'2018-07-07 17:31:59','0000-00-00 00:00:00'),
	(81,21000225,10,'{\"exp\":0}',0,'2018-07-07 18:02:03','0000-00-00 00:00:00'),
	(82,21000225,9,'{\"exp\":2}',0,'2018-07-09 11:28:48','0000-00-00 00:00:00'),
	(83,21000225,9,'{\"exp\":15}',0,'2018-07-09 11:29:42','0000-00-00 00:00:00'),
	(84,21000225,9,'{\"exp\":1}',0,'2018-07-09 11:31:17','0000-00-00 00:00:00'),
	(85,21000225,9,'{\"exp\":2}',0,'2018-07-09 11:33:26','0000-00-00 00:00:00'),
	(86,21000225,9,'{\"exp\":15}',0,'2018-07-09 11:35:20','0000-00-00 00:00:00'),
	(87,21000225,9,'{\"exp\":2}',0,'2018-07-09 11:37:44','0000-00-00 00:00:00'),
	(88,21000225,9,'{\"exp\":2}',0,'2018-07-09 14:40:55','0000-00-00 00:00:00'),
	(89,21000225,9,'{\"exp\":15}',0,'2018-07-09 14:43:03','0000-00-00 00:00:00'),
	(90,21000225,9,'{\"exp\":1}',0,'2018-07-09 14:46:19','0000-00-00 00:00:00'),
	(91,21000225,9,'{\"exp\":2}',0,'2018-07-09 14:50:28','0000-00-00 00:00:00'),
	(92,21000225,9,'{\"exp\":37}',0,'2018-07-09 14:51:50','0000-00-00 00:00:00'),
	(93,21000225,9,'{\"exp\":3}',0,'2018-07-09 14:58:55','0000-00-00 00:00:00'),
	(94,21000225,9,'{\"exp\":2}',0,'2018-07-09 15:08:21','0000-00-00 00:00:00'),
	(95,21000225,9,'{\"exp\":15}',0,'2018-07-09 15:09:12','0000-00-00 00:00:00'),
	(96,21000225,9,'{\"exp\":2}',0,'2018-07-09 15:10:44','0000-00-00 00:00:00'),
	(97,21000225,9,'{\"exp\":1}',0,'2018-07-09 15:13:35','0000-00-00 00:00:00'),
	(98,21000225,9,'{\"exp\":2}',0,'2018-07-09 15:15:30','0000-00-00 00:00:00'),
	(99,21000225,9,'{\"exp\":15}',0,'2018-07-09 15:15:53','0000-00-00 00:00:00'),
	(100,21000225,9,'{\"exp\":1}',0,'2018-07-09 15:17:11','0000-00-00 00:00:00'),
	(101,21000225,9,'{\"exp\":15}',0,'2018-07-09 15:58:50','0000-00-00 00:00:00'),
	(102,21000225,9,'{\"exp\":3}',0,'2018-07-09 15:59:58','0000-00-00 00:00:00'),
	(103,21000225,9,'{\"exp\":3}',0,'2018-07-09 16:05:44','0000-00-00 00:00:00'),
	(104,21000225,9,'{\"exp\":3}',0,'2018-07-09 16:07:28','0000-00-00 00:00:00'),
	(105,21000225,9,'{\"exp\":3}',0,'2018-07-09 16:08:42','0000-00-00 00:00:00'),
	(106,21000225,9,'{\"exp\":15}',0,'2018-07-09 16:12:55','0000-00-00 00:00:00'),
	(107,21000225,10,'{\"exp\":0}',0,'2018-07-10 15:03:04','0000-00-00 00:00:00'),
	(108,21000225,10,'{\"exp\":0}',0,'2018-07-10 15:04:22','0000-00-00 00:00:00'),
	(109,21000225,9,'{\"exp\":2}',0,'2018-07-10 15:14:29','0000-00-00 00:00:00'),
	(110,21000225,10,'{\"exp\":0}',0,'2018-07-10 16:26:20','0000-00-00 00:00:00'),
	(111,21000225,10,'{\"exp\":0}',0,'2018-07-10 16:38:57','0000-00-00 00:00:00'),
	(112,21000225,10,'{\"exp\":0}',0,'2018-07-10 16:52:42','0000-00-00 00:00:00'),
	(113,21000225,10,'{\"exp\":0}',0,'2018-07-10 17:24:44','0000-00-00 00:00:00'),
	(114,21000225,10,'{\"exp\":0}',0,'2018-07-10 17:32:02','0000-00-00 00:00:00'),
	(115,21000225,10,'{\"exp\":0}',0,'2018-07-11 10:55:18','0000-00-00 00:00:00'),
	(116,21000225,10,'{\"exp\":0}',0,'2018-07-11 10:56:41','0000-00-00 00:00:00'),
	(117,21000225,10,'{\"exp\":0}',0,'2018-07-11 10:57:17','0000-00-00 00:00:00'),
	(118,21000225,10,'{\"exp\":0}',0,'2018-07-11 11:02:26','0000-00-00 00:00:00'),
	(119,21000225,10,'{\"exp\":0}',0,'2018-07-11 11:05:55','0000-00-00 00:00:00'),
	(120,21000225,10,'{\"exp\":0}',0,'2018-07-11 11:06:45','0000-00-00 00:00:00'),
	(121,21000225,10,'{\"exp\":0}',0,'2018-07-11 11:08:10','0000-00-00 00:00:00'),
	(122,21000225,10,'{\"exp\":0}',0,'2018-07-11 11:08:49','0000-00-00 00:00:00'),
	(123,21000225,10,'{\"exp\":0}',0,'2018-07-11 11:18:47','0000-00-00 00:00:00'),
	(124,21000225,10,'{\"exp\":0}',0,'2018-07-11 11:32:15','0000-00-00 00:00:00'),
	(125,21000225,10,'{\"exp\":0}',0,'2018-07-11 11:32:57','0000-00-00 00:00:00'),
	(126,21000225,10,'{\"exp\":0}',0,'2018-07-11 11:35:33','0000-00-00 00:00:00'),
	(127,21000225,10,'{\"exp\":0}',0,'2018-07-11 11:36:22','0000-00-00 00:00:00'),
	(128,21000225,10,'{\"exp\":0}',0,'2018-07-11 15:37:23','0000-00-00 00:00:00'),
	(129,21000225,10,'{\"exp\":0}',0,'2018-07-11 15:37:50','0000-00-00 00:00:00'),
	(130,21000225,10,'{\"exp\":0}',0,'2018-07-11 15:38:28','0000-00-00 00:00:00'),
	(131,21000225,9,'{\"exp\":10}',0,'2018-07-17 13:32:07','0000-00-00 00:00:00'),
	(132,21000225,10,'{\"exp\":5}',0,'2018-09-26 16:23:08','0000-00-00 00:00:00');

/*!40000 ALTER TABLE `user_task_logs_25` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_logs_26
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_26` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_logs_26` WRITE;
/*!40000 ALTER TABLE `user_task_logs_26` DISABLE KEYS */;

INSERT INTO `user_task_logs_26` (`id`, `uid`, `taskid`, `award`, `orderid`, `addtime`, `modtime`)
VALUES
	(1,20000026,1,'{\"exp\":5}',0,'2018-07-03 19:37:55','0000-00-00 00:00:00');

/*!40000 ALTER TABLE `user_task_logs_26` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_logs_27
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_27` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_logs_27` WRITE;
/*!40000 ALTER TABLE `user_task_logs_27` DISABLE KEYS */;

INSERT INTO `user_task_logs_27` (`id`, `uid`, `taskid`, `award`, `orderid`, `addtime`, `modtime`)
VALUES
	(1,20000027,1,'{\"exp\":5}',0,'2018-07-03 19:43:23','0000-00-00 00:00:00');

/*!40000 ALTER TABLE `user_task_logs_27` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_logs_28
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_28` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_logs_28` WRITE;
/*!40000 ALTER TABLE `user_task_logs_28` DISABLE KEYS */;

INSERT INTO `user_task_logs_28` (`id`, `uid`, `taskid`, `award`, `orderid`, `addtime`, `modtime`)
VALUES
	(1,20000028,1,'{\"grape\":20}',475128451456565248,'2018-07-03 19:50:57','2018-07-03 19:50:58');

/*!40000 ALTER TABLE `user_task_logs_28` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_logs_29
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_29` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_logs_29` WRITE;
/*!40000 ALTER TABLE `user_task_logs_29` DISABLE KEYS */;

INSERT INTO `user_task_logs_29` (`id`, `uid`, `taskid`, `award`, `orderid`, `addtime`, `modtime`)
VALUES
	(1,20000029,1,'{\"exp\":5,\"grape\":20}',475129179839397888,'2018-07-03 19:53:51','2018-07-03 19:53:51');

/*!40000 ALTER TABLE `user_task_logs_29` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_logs_3
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_3` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_logs_3` WRITE;
/*!40000 ALTER TABLE `user_task_logs_3` DISABLE KEYS */;

INSERT INTO `user_task_logs_3` (`id`, `uid`, `taskid`, `award`, `orderid`, `addtime`, `modtime`)
VALUES
	(1,21000203,2,'{\"grape\":2}',470389721294438400,'2018-06-20 18:00:56','2018-06-20 18:00:56'),
	(2,21000203,7,'{\"grape\":10}',470636312483856384,'2018-06-21 10:20:48','2018-06-21 10:20:48'),
	(3,21000203,8,'{\"grape\":1}',473985083125006336,'2018-06-30 16:07:37','2018-06-30 16:07:37'),
	(4,21000203,8,'{\"grape\":1}',473985103375106048,'2018-06-30 16:07:42','2018-06-30 16:07:42'),
	(5,21000203,8,'{\"grape\":1}',473985118629789696,'2018-06-30 16:07:46','2018-06-30 16:07:46'),
	(6,21000203,10,'{\"exp\":5}',0,'2018-06-30 17:30:43','0000-00-00 00:00:00'),
	(7,21000203,4,'{\"grape\":10}',474005995144609792,'2018-06-30 17:30:43','2018-06-30 17:30:43'),
	(8,21000203,10,'{\"exp\":2}',0,'2018-06-30 17:54:46','0000-00-00 00:00:00'),
	(9,21000203,10,'{\"exp\":5}',0,'2018-06-30 17:57:48','0000-00-00 00:00:00'),
	(10,21000203,10,'{\"exp\":49}',0,'2018-06-30 17:59:18','0000-00-00 00:00:00'),
	(11,21000203,10,'{\"exp\":500}',0,'2018-06-30 18:05:18','0000-00-00 00:00:00'),
	(12,21000203,10,'{\"exp\":5}',0,'2018-07-02 15:38:06','0000-00-00 00:00:00'),
	(13,21000203,10,'{\"exp\":0}',0,'2018-07-07 18:10:50','0000-00-00 00:00:00'),
	(14,21000203,10,'{\"exp\":0}',0,'2018-07-10 15:11:10','0000-00-00 00:00:00'),
	(15,21000203,10,'{\"exp\":0}',0,'2018-07-10 16:36:22','0000-00-00 00:00:00'),
	(16,21000203,10,'{\"exp\":0}',0,'2018-07-10 16:53:42','0000-00-00 00:00:00'),
	(17,21000203,10,'{\"exp\":1}',0,'2018-07-10 17:24:46','0000-00-00 00:00:00'),
	(18,21000203,10,'{\"exp\":2}',0,'2018-07-10 17:27:08','0000-00-00 00:00:00'),
	(19,21000203,10,'{\"exp\":2}',0,'2018-07-10 17:28:30','0000-00-00 00:00:00'),
	(20,21000203,10,'{\"exp\":0}',0,'2018-07-10 17:34:22','0000-00-00 00:00:00'),
	(21,21000203,10,'{\"exp\":1}',0,'2018-07-10 17:37:41','0000-00-00 00:00:00'),
	(22,21000203,10,'{\"exp\":1}',0,'2018-07-10 19:38:36','0000-00-00 00:00:00'),
	(23,21000203,10,'{\"exp\":1}',0,'2018-07-10 19:41:18','0000-00-00 00:00:00'),
	(24,21000203,10,'{\"exp\":0}',0,'2018-07-10 20:22:29','0000-00-00 00:00:00'),
	(25,21000203,10,'{\"exp\":0}',0,'2018-07-10 20:37:31','0000-00-00 00:00:00'),
	(26,21000203,10,'{\"exp\":1}',0,'2018-07-10 20:38:14','0000-00-00 00:00:00'),
	(27,21000203,10,'{\"exp\":1}',0,'2018-07-10 21:08:51','0000-00-00 00:00:00'),
	(28,21000203,10,'{\"exp\":0}',0,'2018-07-10 21:09:57','0000-00-00 00:00:00'),
	(29,21000203,10,'{\"exp\":1}',0,'2018-07-10 21:10:47','0000-00-00 00:00:00'),
	(30,21000203,10,'{\"exp\":1}',0,'2018-07-10 21:12:48','0000-00-00 00:00:00'),
	(31,21000203,10,'{\"exp\":1}',0,'2018-07-11 11:03:18','0000-00-00 00:00:00'),
	(32,21000203,10,'{\"exp\":1}',0,'2018-07-11 11:04:08','0000-00-00 00:00:00'),
	(33,21000203,10,'{\"exp\":1}',0,'2018-07-11 11:04:56','0000-00-00 00:00:00'),
	(34,21000203,10,'{\"exp\":0}',0,'2018-07-11 11:08:37','0000-00-00 00:00:00'),
	(35,21000203,10,'{\"exp\":0}',0,'2018-07-11 11:19:08','0000-00-00 00:00:00'),
	(36,21000203,10,'{\"exp\":1}',0,'2018-07-11 11:21:17','0000-00-00 00:00:00'),
	(37,21000203,10,'{\"exp\":1}',0,'2018-07-11 11:23:14','0000-00-00 00:00:00'),
	(38,21000203,10,'{\"exp\":1}',0,'2018-07-11 14:01:10','0000-00-00 00:00:00'),
	(39,21000203,10,'{\"exp\":0}',0,'2018-07-11 15:38:44','0000-00-00 00:00:00'),
	(40,21000203,10,'{\"exp\":0}',0,'2018-07-12 10:32:19','0000-00-00 00:00:00'),
	(41,21000203,10,'{\"exp\":1}',0,'2018-07-12 10:38:14','0000-00-00 00:00:00'),
	(42,21000203,10,'{\"exp\":0}',0,'2018-07-13 10:08:56','0000-00-00 00:00:00'),
	(43,21000203,10,'{\"exp\":2}',0,'2018-07-13 10:11:34','0000-00-00 00:00:00'),
	(44,21000203,10,'{\"exp\":2}',0,'2018-07-13 10:19:28','0000-00-00 00:00:00'),
	(45,21000203,10,'{\"exp\":1}',0,'2018-07-13 17:07:53','0000-00-00 00:00:00'),
	(46,21000203,10,'{\"exp\":500}',0,'2018-07-13 17:12:22','0000-00-00 00:00:00');

/*!40000 ALTER TABLE `user_task_logs_3` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_logs_30
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_30` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_logs_30` WRITE;
/*!40000 ALTER TABLE `user_task_logs_30` DISABLE KEYS */;

INSERT INTO `user_task_logs_30` (`id`, `uid`, `taskid`, `award`, `orderid`, `addtime`, `modtime`)
VALUES
	(1,20000030,1,'{\"exp\":5,\"grape\":20}',475420315275493376,'2018-07-04 15:10:43','2018-07-04 15:10:43');

/*!40000 ALTER TABLE `user_task_logs_30` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_logs_31
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_31` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_logs_31` WRITE;
/*!40000 ALTER TABLE `user_task_logs_31` DISABLE KEYS */;

INSERT INTO `user_task_logs_31` (`id`, `uid`, `taskid`, `award`, `orderid`, `addtime`, `modtime`)
VALUES
	(1,20000031,1,'{\"exp\":5,\"grape\":30}',475428014507687936,'2018-07-04 15:41:19','2018-07-04 15:41:19'),
	(2,20000031,10,'{\"exp\":1}',0,'2018-07-05 17:40:19','0000-00-00 00:00:00'),
	(3,20000031,4,'{\"grape\":10}',475820350572068864,'2018-07-05 17:40:19','2018-07-05 17:40:19'),
	(4,20000031,7,'{\"grape\":10}',475820557565165568,'2018-07-05 17:41:08','2018-07-05 17:41:08'),
	(5,20000031,10,'{\"exp\":1}',0,'2018-07-06 17:13:02','0000-00-00 00:00:00'),
	(6,20000031,10,'{\"exp\":1}',0,'2018-07-06 17:23:01','0000-00-00 00:00:00'),
	(7,20000031,10,'{\"exp\":1}',0,'2018-07-06 17:24:49','0000-00-00 00:00:00'),
	(8,20000031,10,'{\"exp\":1}',0,'2018-07-06 17:28:29','0000-00-00 00:00:00'),
	(9,20000031,10,'{\"exp\":0}',0,'2018-07-06 19:42:46','0000-00-00 00:00:00'),
	(10,20000031,10,'{\"exp\":0}',0,'2018-07-06 20:01:15','0000-00-00 00:00:00'),
	(11,20000031,2,'{\"grape\":2}',476517172064026624,'2018-07-07 15:49:14','2018-07-07 15:49:14'),
	(12,20000031,10,'{\"exp\":0}',0,'2018-07-07 17:03:08','0000-00-00 00:00:00'),
	(13,20000031,10,'{\"exp\":0}',0,'2018-07-07 17:22:30','0000-00-00 00:00:00'),
	(14,20000031,10,'{\"exp\":0}',0,'2018-07-07 17:25:16','0000-00-00 00:00:00'),
	(15,20000031,10,'{\"exp\":0}',0,'2018-07-07 18:02:55','0000-00-00 00:00:00'),
	(16,20000031,10,'{\"exp\":0}',0,'2018-07-09 17:42:36','0000-00-00 00:00:00'),
	(17,20000031,10,'{\"exp\":0}',0,'2018-07-09 17:46:54','0000-00-00 00:00:00'),
	(18,20000031,10,'{\"exp\":0}',0,'2018-07-09 17:55:40','0000-00-00 00:00:00'),
	(19,20000031,10,'{\"exp\":0}',0,'2018-07-09 18:09:20','0000-00-00 00:00:00'),
	(20,20000031,10,'{\"exp\":0}',0,'2018-07-10 15:02:05','0000-00-00 00:00:00'),
	(21,20000031,10,'{\"exp\":0}',0,'2018-07-10 15:10:04','0000-00-00 00:00:00'),
	(22,20000031,10,'{\"exp\":0}',0,'2018-07-10 15:11:10','0000-00-00 00:00:00'),
	(23,20000031,10,'{\"exp\":0}',0,'2018-07-10 15:33:52','0000-00-00 00:00:00'),
	(24,20000031,10,'{\"exp\":0}',0,'2018-07-10 16:53:28','0000-00-00 00:00:00'),
	(25,20000031,10,'{\"exp\":0}',0,'2018-07-10 17:24:58','0000-00-00 00:00:00'),
	(26,20000031,10,'{\"exp\":0}',0,'2018-07-10 17:36:02','0000-00-00 00:00:00'),
	(27,20000031,10,'{\"exp\":0}',0,'2018-07-10 17:36:57','0000-00-00 00:00:00'),
	(28,20000031,10,'{\"exp\":0}',0,'2018-07-10 17:37:48','0000-00-00 00:00:00'),
	(29,20000031,10,'{\"exp\":0}',0,'2018-07-10 19:20:45','0000-00-00 00:00:00'),
	(30,20000031,10,'{\"exp\":0}',0,'2018-07-10 19:21:48','0000-00-00 00:00:00'),
	(31,20000031,10,'{\"exp\":0}',0,'2018-07-10 19:23:44','0000-00-00 00:00:00'),
	(32,20000031,10,'{\"exp\":0}',0,'2018-07-10 19:28:50','0000-00-00 00:00:00'),
	(33,20000031,10,'{\"exp\":0}',0,'2018-07-10 19:31:20','0000-00-00 00:00:00'),
	(34,20000031,10,'{\"exp\":0}',0,'2018-07-10 19:33:59','0000-00-00 00:00:00'),
	(35,20000031,10,'{\"exp\":0}',0,'2018-07-10 19:40:30','0000-00-00 00:00:00'),
	(36,20000031,10,'{\"exp\":0}',0,'2018-07-10 19:46:26','0000-00-00 00:00:00'),
	(37,20000031,10,'{\"exp\":0}',0,'2018-07-10 19:47:07','0000-00-00 00:00:00'),
	(38,20000031,9,'{\"exp\":1}',0,'2018-07-10 19:55:58','0000-00-00 00:00:00'),
	(39,20000031,10,'{\"exp\":0}',0,'2018-07-10 20:01:13','0000-00-00 00:00:00'),
	(40,20000031,10,'{\"exp\":0}',0,'2018-07-10 20:05:26','0000-00-00 00:00:00'),
	(41,20000031,10,'{\"exp\":0}',0,'2018-07-10 20:07:44','0000-00-00 00:00:00'),
	(42,20000031,10,'{\"exp\":0}',0,'2018-07-10 20:09:06','0000-00-00 00:00:00'),
	(43,20000031,10,'{\"exp\":0}',0,'2018-07-10 20:14:15','0000-00-00 00:00:00'),
	(44,20000031,10,'{\"exp\":0}',0,'2018-07-10 20:16:35','0000-00-00 00:00:00'),
	(45,20000031,10,'{\"exp\":0}',0,'2018-07-10 20:19:32','0000-00-00 00:00:00'),
	(46,20000031,10,'{\"exp\":0}',0,'2018-07-10 20:36:14','0000-00-00 00:00:00'),
	(47,20000031,10,'{\"exp\":0}',0,'2018-07-10 20:37:28','0000-00-00 00:00:00'),
	(48,20000031,10,'{\"exp\":0}',0,'2018-07-10 21:02:40','0000-00-00 00:00:00'),
	(49,20000031,10,'{\"exp\":0}',0,'2018-07-10 21:07:15','0000-00-00 00:00:00'),
	(50,20000031,10,'{\"exp\":0}',0,'2018-07-10 21:08:02','0000-00-00 00:00:00'),
	(51,20000031,10,'{\"exp\":0}',0,'2018-07-10 21:09:59','0000-00-00 00:00:00'),
	(52,20000031,10,'{\"exp\":0}',0,'2018-07-10 21:10:22','0000-00-00 00:00:00'),
	(53,20000031,10,'{\"exp\":0}',0,'2018-07-10 21:10:55','0000-00-00 00:00:00'),
	(54,20000031,10,'{\"exp\":0}',0,'2018-07-10 21:12:02','0000-00-00 00:00:00'),
	(55,20000031,10,'{\"exp\":0}',0,'2018-07-10 21:12:41','0000-00-00 00:00:00'),
	(56,20000031,10,'{\"exp\":0}',0,'2018-07-11 10:55:48','0000-00-00 00:00:00'),
	(57,20000031,10,'{\"exp\":0}',0,'2018-07-11 10:56:37','0000-00-00 00:00:00'),
	(58,20000031,10,'{\"exp\":0}',0,'2018-07-11 10:57:13','0000-00-00 00:00:00'),
	(59,20000031,10,'{\"exp\":0}',0,'2018-07-11 10:59:18','0000-00-00 00:00:00'),
	(60,20000031,10,'{\"exp\":0}',0,'2018-07-11 11:03:04','0000-00-00 00:00:00'),
	(61,20000031,10,'{\"exp\":0}',0,'2018-07-11 11:03:57','0000-00-00 00:00:00'),
	(62,20000031,10,'{\"exp\":0}',0,'2018-07-11 11:05:32','0000-00-00 00:00:00'),
	(63,20000031,10,'{\"exp\":0}',0,'2018-07-11 11:19:54','0000-00-00 00:00:00'),
	(64,20000031,10,'{\"exp\":0}',0,'2018-07-11 11:31:59','0000-00-00 00:00:00'),
	(65,20000031,10,'{\"exp\":0}',0,'2018-07-11 11:35:15','0000-00-00 00:00:00'),
	(66,20000031,10,'{\"exp\":0}',0,'2018-07-11 11:35:43','0000-00-00 00:00:00'),
	(67,20000031,10,'{\"exp\":0}',0,'2018-07-11 15:07:15','0000-00-00 00:00:00'),
	(68,20000031,10,'{\"exp\":0}',0,'2018-07-11 15:36:42','0000-00-00 00:00:00'),
	(69,20000031,10,'{\"exp\":0}',0,'2018-07-11 15:38:32','0000-00-00 00:00:00'),
	(70,20000031,10,'{\"exp\":0}',0,'2018-07-11 15:56:21','0000-00-00 00:00:00'),
	(71,20000031,10,'{\"exp\":0}',0,'2018-07-12 10:07:05','0000-00-00 00:00:00'),
	(72,20000031,10,'{\"exp\":0}',0,'2018-07-12 15:39:07','0000-00-00 00:00:00'),
	(73,20000031,10,'{\"exp\":0}',0,'2018-07-12 15:39:35','0000-00-00 00:00:00'),
	(74,20000031,10,'{\"exp\":0}',0,'2018-07-12 16:04:02','0000-00-00 00:00:00'),
	(75,20000031,10,'{\"exp\":0}',0,'2018-07-12 16:07:13','0000-00-00 00:00:00'),
	(76,20000031,10,'{\"exp\":0}',0,'2018-07-12 19:52:36','0000-00-00 00:00:00'),
	(77,20000031,10,'{\"exp\":0}',0,'2018-07-13 20:16:35','0000-00-00 00:00:00'),
	(78,20000031,9,'{\"exp\":0}',0,'2018-07-14 17:45:35','0000-00-00 00:00:00'),
	(79,20000031,9,'{\"exp\":4}',0,'2018-07-14 17:46:13','0000-00-00 00:00:00'),
	(80,20000031,9,'{\"exp\":0}',0,'2018-07-19 14:16:12','0000-00-00 00:00:00'),
	(81,20000031,10,'{\"exp\":0}',0,'2018-07-19 14:31:07','0000-00-00 00:00:00'),
	(82,20000031,9,'{\"exp\":0}',0,'2018-07-19 16:27:38','0000-00-00 00:00:00'),
	(83,20000031,9,'{\"exp\":0}',0,'2018-07-19 16:36:26','0000-00-00 00:00:00'),
	(84,20000031,8,'{\"grape\":1}',480878437314068480,'2018-07-19 16:39:21','2018-07-19 16:39:21'),
	(85,20000031,9,'{\"exp\":0}',0,'2018-07-19 16:44:16','0000-00-00 00:00:00'),
	(86,20000031,10,'{\"exp\":0}',0,'2018-07-19 16:48:20','0000-00-00 00:00:00'),
	(87,20000031,9,'{\"exp\":0}',0,'2018-07-19 16:50:05','0000-00-00 00:00:00'),
	(88,20000031,9,'{\"exp\":1}',0,'2018-07-19 16:50:34','0000-00-00 00:00:00');

/*!40000 ALTER TABLE `user_task_logs_31` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_logs_32
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_32` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_33
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_33` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_34
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_34` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_35
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_35` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_36
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_36` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_37
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_37` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_38
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_38` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_39
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_39` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_4
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_4` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_logs_4` WRITE;
/*!40000 ALTER TABLE `user_task_logs_4` DISABLE KEYS */;

INSERT INTO `user_task_logs_4` (`id`, `uid`, `taskid`, `award`, `orderid`, `addtime`, `modtime`)
VALUES
	(1,21000204,7,'{\"grape\":10}',470723035272314880,'2018-06-21 16:05:24','2018-06-21 16:05:24');

/*!40000 ALTER TABLE `user_task_logs_4` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_logs_40
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_40` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_41
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_41` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_42
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_42` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_43
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_43` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_44
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_44` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_45
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_45` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_logs_45` WRITE;
/*!40000 ALTER TABLE `user_task_logs_45` DISABLE KEYS */;

INSERT INTO `user_task_logs_45` (`id`, `uid`, `taskid`, `award`, `orderid`, `addtime`, `modtime`)
VALUES
	(7,21000345,4,'{\"exp\":5,\"grape\":10}',455113944126726144,'2018-05-09 14:20:27','2018-05-09 14:20:27'),
	(8,21000345,2,'{\"grape\":2}',455115204699627520,'2018-05-09 14:25:28','2018-05-09 14:25:28'),
	(9,21000345,2,'{\"grape\":2}',455115229408272384,'2018-05-09 14:25:33','2018-05-09 14:25:34'),
	(10,21000345,2,'{\"grape\":2}',455115244893642752,'2018-05-09 14:25:37','2018-05-09 14:25:37'),
	(11,21000345,2,'{\"grape\":2}',455129688390508544,'2018-05-09 15:23:01','2018-05-09 15:23:01'),
	(12,21000345,2,'{\"grape\":2}',455485966090506240,'2018-05-10 14:58:44','2018-05-10 14:58:44'),
	(13,21000345,6,'{\"grape\":8}',456223675218141184,'2018-05-12 15:50:08','2018-05-12 15:50:08');

/*!40000 ALTER TABLE `user_task_logs_45` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_logs_46
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_46` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_47
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_47` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_48
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_48` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_49
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_49` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_5
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_5` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_logs_5` WRITE;
/*!40000 ALTER TABLE `user_task_logs_5` DISABLE KEYS */;

INSERT INTO `user_task_logs_5` (`id`, `uid`, `taskid`, `award`, `orderid`, `addtime`, `modtime`)
VALUES
	(1,21000205,2,'{\"grape\":2}',468202916847550464,'2018-06-14 17:11:21','2018-06-14 17:11:21'),
	(2,21000205,4,'{\"exp\":5,\"grape\":10}',468246748632973312,'2018-06-14 20:05:32','2018-06-14 20:05:32'),
	(3,21000205,7,'{\"grape\":10}',471427077426380800,'2018-06-23 14:43:01','2018-06-23 14:43:01'),
	(4,21000205,8,'{\"award\":1}',0,'2018-06-29 14:40:31','0000-00-00 00:00:00'),
	(5,21000205,8,'{\"award\":1}',0,'2018-06-29 14:41:08','0000-00-00 00:00:00'),
	(6,21000205,8,'{\"award\":1}',0,'2018-06-29 14:53:03','0000-00-00 00:00:00'),
	(7,21000205,9,'{\"exp\":0}',0,'2018-06-29 16:37:17','0000-00-00 00:00:00'),
	(8,21000205,9,'{\"exp\":0}',0,'2018-06-29 16:41:40','0000-00-00 00:00:00'),
	(9,21000205,10,'{\"exp\":2}',0,'2018-06-29 16:49:27','0000-00-00 00:00:00'),
	(10,21000205,9,'{\"exp\":2}',0,'2018-06-29 17:31:43','0000-00-00 00:00:00'),
	(11,21000205,9,'{\"exp\":2}',0,'2018-06-29 17:38:12','0000-00-00 00:00:00'),
	(12,21000205,9,'{\"exp\":2}',0,'2018-06-29 17:40:00','0000-00-00 00:00:00'),
	(13,21000205,9,'{\"exp\":2}',0,'2018-06-29 17:42:28','0000-00-00 00:00:00'),
	(14,21000205,9,'{\"exp\":2}',0,'2018-06-29 17:47:35','0000-00-00 00:00:00'),
	(15,21000205,9,'{\"exp\":2}',0,'2018-06-29 17:57:03','0000-00-00 00:00:00'),
	(16,21000205,9,'{\"exp\":2}',0,'2018-06-29 18:01:05','0000-00-00 00:00:00'),
	(17,21000205,9,'{\"exp\":2}',0,'2018-06-29 18:08:16','0000-00-00 00:00:00'),
	(18,21000205,9,'{\"exp\":2}',0,'2018-06-29 18:10:20','0000-00-00 00:00:00'),
	(19,21000205,9,'{\"exp\":1}',0,'2018-06-29 19:02:59','0000-00-00 00:00:00'),
	(20,21000205,8,'{\"grape\":1}',473674182199083008,'2018-06-29 19:32:13','2018-06-29 19:32:13'),
	(21,21000205,9,'{\"exp\":1}',0,'2018-06-30 11:03:27','0000-00-00 00:00:00'),
	(22,21000205,10,'{\"exp\":1}',0,'2018-06-30 13:51:59','0000-00-00 00:00:00'),
	(23,21000205,10,'{\"exp\":4}',0,'2018-06-30 13:58:46','0000-00-00 00:00:00'),
	(24,21000205,8,'{\"grape\":1}',473953881533972480,'2018-06-30 14:03:38','2018-06-30 14:03:38'),
	(25,21000205,8,'{\"grape\":1}',473953923917414400,'2018-06-30 14:03:48','2018-06-30 14:03:48'),
	(26,21000205,9,'{\"exp\":3}',0,'2018-06-30 14:10:24','0000-00-00 00:00:00'),
	(27,21000205,9,'{\"exp\":3}',0,'2018-06-30 14:10:56','0000-00-00 00:00:00'),
	(28,21000205,9,'{\"exp\":3}',0,'2018-06-30 14:12:18','0000-00-00 00:00:00'),
	(29,21000205,9,'{\"exp\":3}',0,'2018-06-30 14:13:11','0000-00-00 00:00:00'),
	(30,21000205,10,'{\"exp\":1}',0,'2018-06-30 14:17:50','0000-00-00 00:00:00'),
	(31,21000205,10,'{\"exp\":1}',0,'2018-06-30 14:24:28','0000-00-00 00:00:00'),
	(32,21000205,9,'{\"exp\":3}',0,'2018-06-30 14:53:20','0000-00-00 00:00:00'),
	(33,21000205,10,'{\"exp\":1}',0,'2018-06-30 17:52:30','0000-00-00 00:00:00'),
	(34,21000205,10,'{\"exp\":4}',0,'2018-06-30 17:57:07','0000-00-00 00:00:00'),
	(35,21000205,10,'{\"exp\":500}',0,'2018-06-30 18:10:43','0000-00-00 00:00:00'),
	(36,21000205,10,'{\"exp\":2}',0,'2018-06-30 18:29:42','0000-00-00 00:00:00'),
	(37,21000205,10,'{\"exp\":5}',0,'2018-06-30 18:44:21','0000-00-00 00:00:00'),
	(38,21000205,8,'{\"grape\":1}',474704693541470208,'2018-07-02 15:47:06','2018-07-02 15:47:06'),
	(39,21000205,9,'{\"exp\":8}',0,'2018-07-03 10:33:18','0000-00-00 00:00:00'),
	(40,21000205,9,'{\"exp\":1}',0,'2018-07-03 10:33:27','0000-00-00 00:00:00'),
	(41,21000205,9,'{\"exp\":1}',0,'2018-07-03 11:40:24','0000-00-00 00:00:00'),
	(42,21000205,9,'{\"exp\":1}',0,'2018-07-03 14:19:25','0000-00-00 00:00:00');

/*!40000 ALTER TABLE `user_task_logs_5` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_logs_50
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_50` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_51
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_51` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_52
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_52` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_53
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_53` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_54
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_54` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_55
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_55` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_56
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_56` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_57
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_57` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_58
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_58` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_59
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_59` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_6
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_6` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_logs_6` WRITE;
/*!40000 ALTER TABLE `user_task_logs_6` DISABLE KEYS */;

INSERT INTO `user_task_logs_6` (`id`, `uid`, `taskid`, `award`, `orderid`, `addtime`, `modtime`)
VALUES
	(1,21000206,2,'{\"grape\":2}',468230121799548928,'2018-06-14 18:59:27','2018-06-14 18:59:28'),
	(2,21000206,4,'{\"exp\":5,\"grape\":10}',470653227205918720,'2018-06-21 11:28:01','2018-06-21 11:28:01'),
	(3,21000206,10,'{\"exp\":1}',0,'2018-06-30 10:36:02','0000-00-00 00:00:00'),
	(4,21000206,8,'{\"grape\":1}',0,'2018-06-30 13:37:13','0000-00-00 00:00:00'),
	(5,21000206,8,'{\"grape\":1}',0,'2018-06-30 13:43:38','0000-00-00 00:00:00'),
	(6,21000206,9,'{\"exp\":4}',0,'2018-06-30 14:16:23','0000-00-00 00:00:00'),
	(7,21000206,9,'{\"exp\":0}',0,'2018-06-30 14:17:06','0000-00-00 00:00:00'),
	(8,21000206,9,'{\"exp\":0}',0,'2018-06-30 14:26:53','0000-00-00 00:00:00'),
	(9,21000206,9,'{\"exp\":0}',0,'2018-06-30 14:27:02','0000-00-00 00:00:00'),
	(10,21000206,9,'{\"exp\":0}',0,'2018-06-30 14:27:15','0000-00-00 00:00:00'),
	(11,21000206,8,'{\"grape\":1}',0,'2018-06-30 14:44:24','0000-00-00 00:00:00'),
	(12,21000206,9,'{\"exp\":5}',0,'2018-06-30 14:50:52','0000-00-00 00:00:00'),
	(13,21000206,9,'{\"exp\":1}',0,'2018-06-30 15:36:39','0000-00-00 00:00:00'),
	(14,21000206,9,'{\"exp\":1}',0,'2018-06-30 15:45:54','0000-00-00 00:00:00'),
	(15,21000206,9,'{\"exp\":1}',0,'2018-06-30 15:53:40','0000-00-00 00:00:00'),
	(16,21000206,9,'{\"exp\":6}',0,'2018-06-30 15:58:01','0000-00-00 00:00:00'),
	(17,21000206,9,'{\"exp\":6}',0,'2018-06-30 16:01:01','0000-00-00 00:00:00'),
	(18,21000206,9,'{\"exp\":1}',0,'2018-06-30 16:02:57','0000-00-00 00:00:00'),
	(19,21000206,9,'{\"exp\":5}',0,'2018-06-30 16:05:48','0000-00-00 00:00:00'),
	(20,21000206,9,'{\"exp\":0}',0,'2018-06-30 16:08:14','0000-00-00 00:00:00'),
	(21,21000206,9,'{\"exp\":5}',0,'2018-06-30 16:11:28','0000-00-00 00:00:00'),
	(22,21000206,9,'{\"exp\":6}',0,'2018-06-30 17:06:30','0000-00-00 00:00:00'),
	(23,21000206,8,'{\"grape\":1}',0,'2018-07-02 15:10:12','0000-00-00 00:00:00'),
	(24,21000206,9,'{\"exp\":1}',0,'2018-07-02 15:56:30','0000-00-00 00:00:00'),
	(25,21000206,9,'{\"exp\":10}',0,'2018-07-02 16:24:13','0000-00-00 00:00:00'),
	(26,21000206,9,'{\"exp\":3}',0,'2018-07-02 18:08:17','0000-00-00 00:00:00'),
	(27,21000206,2,'{\"grape\":2}',475127782095978496,'2018-07-03 19:48:18','2018-07-03 19:48:18'),
	(28,21000206,1,'{\"exp\":5}',0,'2018-07-03 19:48:43','0000-00-00 00:00:00'),
	(29,21000206,10,'{\"exp\":5}',0,'2018-07-04 14:05:59','0000-00-00 00:00:00'),
	(30,21000206,9,'{\"exp\":11}',0,'2018-07-04 15:20:38','0000-00-00 00:00:00'),
	(31,21000206,10,'{\"exp\":2}',0,'2018-07-06 14:17:05','0000-00-00 00:00:00'),
	(32,21000206,10,'{\"exp\":2}',0,'2018-07-06 16:35:21','0000-00-00 00:00:00'),
	(33,21000206,10,'{\"exp\":0}',0,'2018-07-10 15:52:43','0000-00-00 00:00:00'),
	(34,21000206,10,'{\"exp\":0}',0,'2018-07-10 15:54:42','0000-00-00 00:00:00'),
	(35,21000206,9,'{\"exp\":1}',0,'2018-07-10 16:05:53','0000-00-00 00:00:00'),
	(36,21000206,10,'{\"exp\":0}',0,'2018-07-10 16:23:16','0000-00-00 00:00:00'),
	(37,21000206,10,'{\"exp\":0}',0,'2018-07-10 16:37:59','0000-00-00 00:00:00'),
	(38,21000206,10,'{\"exp\":0}',0,'2018-07-10 16:47:11','0000-00-00 00:00:00'),
	(39,21000206,10,'{\"exp\":0}',0,'2018-07-10 16:48:21','0000-00-00 00:00:00'),
	(40,21000206,10,'{\"exp\":0}',0,'2018-07-10 16:56:27','0000-00-00 00:00:00'),
	(41,21000206,10,'{\"exp\":0}',0,'2018-07-10 21:09:42','0000-00-00 00:00:00'),
	(42,21000206,10,'{\"exp\":0}',0,'2018-07-10 21:11:23','0000-00-00 00:00:00'),
	(43,21000206,10,'{\"exp\":5}',0,'2018-07-11 10:28:39','0000-00-00 00:00:00'),
	(44,21000206,9,'{\"exp\":10}',0,'2018-07-11 10:31:03','0000-00-00 00:00:00'),
	(45,21000206,10,'{\"exp\":0}',0,'2018-07-11 10:36:10','0000-00-00 00:00:00'),
	(46,21000206,10,'{\"exp\":0}',0,'2018-07-11 14:12:45','0000-00-00 00:00:00'),
	(47,21000206,10,'{\"exp\":0}',0,'2018-07-11 15:00:40','0000-00-00 00:00:00'),
	(48,21000206,9,'{\"exp\":0}',0,'2018-07-11 15:01:21','0000-00-00 00:00:00'),
	(49,21000206,10,'{\"exp\":0}',0,'2018-07-11 15:02:19','0000-00-00 00:00:00'),
	(50,21000206,10,'{\"exp\":0}',0,'2018-07-11 15:42:51','0000-00-00 00:00:00'),
	(51,21000206,10,'{\"exp\":0}',0,'2018-07-11 17:04:37','0000-00-00 00:00:00'),
	(52,21000206,10,'{\"exp\":0}',0,'2018-07-11 18:31:09','0000-00-00 00:00:00'),
	(53,21000206,10,'{\"exp\":0}',0,'2018-07-11 18:34:00','0000-00-00 00:00:00'),
	(54,21000206,10,'{\"exp\":0}',0,'2018-07-11 18:35:54','0000-00-00 00:00:00'),
	(55,21000206,10,'{\"exp\":0}',0,'2018-07-11 20:26:32','0000-00-00 00:00:00'),
	(56,21000206,10,'{\"exp\":0}',0,'2018-07-11 20:39:57','0000-00-00 00:00:00'),
	(57,21000206,10,'{\"exp\":2}',0,'2018-07-16 11:20:07','0000-00-00 00:00:00'),
	(58,21000206,9,'{\"exp\":8}',0,'2018-07-16 11:34:18','0000-00-00 00:00:00'),
	(59,21000206,10,'{\"exp\":2}',0,'2018-07-16 14:27:09','0000-00-00 00:00:00'),
	(60,21000206,9,'{\"exp\":1}',0,'2018-07-16 14:29:26','0000-00-00 00:00:00'),
	(61,21000206,10,'{\"exp\":2}',0,'2018-07-16 14:42:52','0000-00-00 00:00:00'),
	(62,21000206,9,'{\"exp\":1}',0,'2018-07-16 14:44:39','0000-00-00 00:00:00'),
	(63,21000206,10,'{\"exp\":5}',0,'2018-07-16 18:51:03','0000-00-00 00:00:00'),
	(64,21000206,9,'{\"exp\":8}',0,'2018-07-16 18:53:37','0000-00-00 00:00:00'),
	(65,21000206,10,'{\"exp\":5}',0,'2018-07-16 18:57:30','0000-00-00 00:00:00'),
	(66,21000206,9,'{\"exp\":1}',0,'2018-07-16 18:58:22','0000-00-00 00:00:00'),
	(67,21000206,10,'{\"exp\":5}',0,'2018-07-16 19:16:59','0000-00-00 00:00:00'),
	(68,21000206,10,'{\"exp\":5}',0,'2018-07-16 19:18:36','0000-00-00 00:00:00'),
	(69,21000206,9,'{\"exp\":10}',0,'2018-07-17 10:15:05','0000-00-00 00:00:00'),
	(70,21000206,10,'{\"exp\":5}',0,'2018-07-17 13:47:40','0000-00-00 00:00:00'),
	(71,21000206,9,'{\"exp\":11}',0,'2018-07-17 13:48:24','0000-00-00 00:00:00'),
	(72,21000206,9,'{\"exp\":1}',0,'2018-07-17 13:54:26','0000-00-00 00:00:00'),
	(73,21000206,9,'{\"exp\":10}',0,'2018-07-17 13:56:20','0000-00-00 00:00:00'),
	(74,21000206,9,'{\"exp\":8}',0,'2018-07-17 13:59:44','0000-00-00 00:00:00'),
	(75,21000206,9,'{\"exp\":10}',0,'2018-07-17 14:10:22','0000-00-00 00:00:00'),
	(76,21000206,10,'{\"exp\":5}',0,'2018-07-19 11:02:24','0000-00-00 00:00:00'),
	(77,21000206,10,'{\"exp\":5}',0,'2018-07-19 11:15:38','0000-00-00 00:00:00'),
	(78,21000206,10,'{\"exp\":5}',0,'2018-07-19 17:03:32','0000-00-00 00:00:00');

/*!40000 ALTER TABLE `user_task_logs_6` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_logs_60
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_60` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_61
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_61` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_62
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_62` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_63
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_63` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_64
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_64` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_65
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_65` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_66
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_66` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_67
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_67` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_68
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_68` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_69
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_69` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_7
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_7` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_logs_7` WRITE;
/*!40000 ALTER TABLE `user_task_logs_7` DISABLE KEYS */;

INSERT INTO `user_task_logs_7` (`id`, `uid`, `taskid`, `award`, `orderid`, `addtime`, `modtime`)
VALUES
	(1,20000007,2,'{\"grape\":2}',465278261698494464,'2018-06-06 15:29:49','2018-06-06 15:29:49');

/*!40000 ALTER TABLE `user_task_logs_7` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_logs_70
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_70` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_71
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_71` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_72
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_72` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_73
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_73` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_74
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_74` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_75
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_75` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_76
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_76` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_77
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_77` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_78
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_78` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_79
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_79` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_8
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_8` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_80
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_80` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_81
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_81` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_82
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_82` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_83
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_83` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_84
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_84` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_85
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_85` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_86
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_86` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_87
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_87` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_88
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_88` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_89
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_89` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_9
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_9` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

LOCK TABLES `user_task_logs_9` WRITE;
/*!40000 ALTER TABLE `user_task_logs_9` DISABLE KEYS */;

INSERT INTO `user_task_logs_9` (`id`, `uid`, `taskid`, `award`, `orderid`, `addtime`, `modtime`)
VALUES
	(1,20000009,6,'{\"grape\":15}',457974019270909952,'2018-05-17 11:45:22','2018-05-17 11:45:22'),
	(2,20000009,2,'{\"grape\":2}',457974020067827712,'2018-05-17 11:45:22','2018-05-17 11:45:22'),
	(3,21000209,7,'{\"grape\":10}',471117334027698176,'2018-06-22 18:12:13','2018-06-22 18:12:13'),
	(4,21000209,2,'{\"grape\":2}',471118143515787264,'2018-06-22 18:15:26','2018-06-22 18:15:26'),
	(5,21000209,4,'{\"exp\":5,\"grape\":10}',471125285341757440,'2018-06-22 18:43:48','2018-06-22 18:43:48'),
	(6,21000209,8,'{\"grape\":1}',473999008365281280,'2018-06-30 17:02:57','2018-06-30 17:02:57'),
	(7,21000209,8,'{\"grape\":1}',473999016607088640,'2018-06-30 17:02:59','2018-06-30 17:02:59'),
	(8,21000209,8,'{\"grape\":1}',473999023162785792,'2018-06-30 17:03:01','2018-06-30 17:03:01'),
	(9,21000209,10,'{\"exp\":0}',0,'2018-06-30 17:12:12','0000-00-00 00:00:00'),
	(10,21000209,9,'{\"exp\":1}',0,'2018-06-30 17:22:50','0000-00-00 00:00:00'),
	(11,21000209,10,'{\"exp\":0}',0,'2018-06-30 17:33:45','0000-00-00 00:00:00'),
	(12,21000209,10,'{\"exp\":0}',0,'2018-06-30 17:57:02','0000-00-00 00:00:00'),
	(13,21000209,10,'{\"exp\":0}',0,'2018-06-30 18:29:45','0000-00-00 00:00:00'),
	(14,21000209,10,'{\"exp\":0}',0,'2018-06-30 18:36:48','0000-00-00 00:00:00'),
	(15,21000209,9,'{\"exp\":0}',0,'2018-06-30 18:39:28','0000-00-00 00:00:00'),
	(16,21000209,10,'{\"exp\":0}',0,'2018-07-01 09:22:11','0000-00-00 00:00:00'),
	(17,21000209,9,'{\"exp\":0}',0,'2018-07-01 09:25:06','0000-00-00 00:00:00'),
	(18,21000209,2,'{\"grape\":2}',474253793702182912,'2018-07-01 09:55:23','2018-07-01 09:55:23'),
	(19,21000209,10,'{\"exp\":0}',0,'2018-07-01 16:16:02','0000-00-00 00:00:00'),
	(20,21000209,10,'{\"exp\":0}',0,'2018-07-02 19:13:30','0000-00-00 00:00:00'),
	(21,21000209,9,'{\"exp\":0}',0,'2018-07-02 19:14:11','0000-00-00 00:00:00'),
	(22,21000209,10,'{\"exp\":0}',0,'2018-07-03 11:32:49','0000-00-00 00:00:00'),
	(23,21000209,9,'{\"exp\":0}',0,'2018-07-03 11:35:15','0000-00-00 00:00:00');

/*!40000 ALTER TABLE `user_task_logs_9` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_task_logs_90
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_90` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_91
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_91` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_92
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_92` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_93
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_93` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_94
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_94` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_95
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_95` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_96
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_96` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_97
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_97` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_98
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_98` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table user_task_logs_99
# ------------------------------------------------------------

CREATE TABLE `user_task_logs_99` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'uid',
  `taskid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '任务id',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '当前任务奖励',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `idx_uid` (`uid`) USING BTREE,
  KEY `idx_taskid` (`taskid`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table userbind
# ------------------------------------------------------------

CREATE TABLE `userbind` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `rid` varchar(50) NOT NULL DEFAULT '' COMMENT '关联ID',
  `nickname` varchar(50) NOT NULL DEFAULT '',
  `avatar` varchar(255) NOT NULL DEFAULT '',
  `source` varchar(20) NOT NULL DEFAULT '' COMMENT '来源',
  `access_token` varchar(255) NOT NULL DEFAULT '' COMMENT 'oauth access token',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_rid_source` (`rid`,`source`),
  UNIQUE KEY `uk_uid_source` (`uid`,`source`),
  KEY `idx_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='第三方帐号绑定';

LOCK TABLES `userbind` WRITE;
/*!40000 ALTER TABLE `userbind` DISABLE KEYS */;

INSERT INTO `userbind` (`id`, `uid`, `rid`, `nickname`, `avatar`, `source`, `access_token`, `addtime`, `modtime`)
VALUES
	(5,20000032,'unionid1111','nickname1111','avatar111','xcx','openid1111','2018-07-10 20:37:45','2018-07-10 20:37:45'),
	(6,20000045,'021skVOu0Own7b1FxDRu09W0Pu0skVOB','1111','https://www.baidu.com','xcx','0a0KWxKlKClGi5VYJStxGA==','2018-07-27 16:48:33','2018-07-27 16:48:33'),
	(8,20000047,'oFE7x1OzH0yTT40Gw29CPdg2YrNo','好望角','https://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83equKcia8uaEFYfDc3alunJ4CKGBoEpePl43R15ias6PiaJ6K5FOzxSsuzQunfM8AwIhukgHKZTLTsdrw/132','xcx','oMN5V43wkb6CQr-rEj3bt8TyNodg','2018-07-27 20:14:31','2018-07-27 20:14:31'),
	(11,20000049,'oFE7x1A7rq2Bmls7nsVCjNZVESFQ','夏小兔','https://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83epibNpKIwo4zFRTw8HgMo66cJOjfklNREWuE3zM48KBYpKOdLoAgAgOgOu5Ye6g16uJv6o7k2IPnWg/132','xcx','oMN5V4xatxRxY3sor_MgI0h4rSCo','2018-07-28 10:24:42','2018-07-28 10:24:42'),
	(12,20000050,'oFE7x1GDT8uIY9ZdV2jmGx-iVukI','刘晨光','https://wx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTJLrmxaUI7a4htFRU5xtS4oia39ialfJ1ubpwwxyDbxxEErSmXhqsKySVficSm5eb1Ca8zQ5NevqicCsw/132','xcx','oMN5V4_53p6nhsaqKBXqNjQ6bNP4','2018-07-28 14:01:11','2018-07-28 14:01:11'),
	(15,20000060,'oFE7x1Bt2IOYDk6BxS4Ms5CMuccw','杨青','https://wx.qlogo.cn/mmopen/vi_32/YD1RqdHwfuPYCIvjkwx1Uc1exLpUiaib8f4NB0fhZ3PdZAlNN4ARksvLCiaXG77nmZsbg1jtdibI3j9EJbNPZF16cw/132','xcx','oMN5V4_JKpE3KeZqtF3pml7Of1IQ','2018-07-30 19:20:56','2018-07-30 19:20:56'),
	(16,20000061,'oFE7x1K9wLQxAQ--YsZp6sjk1vbU','Stanley Wang','https://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83epUQyuEcByZDZ9346zRhzfKIzDX1TAppAoO11jbhHOa8FZLphwzAXQTo7C7NoXPHxwVRHmazzJ5XQ/132','xcx','oMN5V4_kC_4WOjMaG9DvxdUe4mJU','2018-07-31 20:26:15','2018-07-31 20:26:15'),
	(17,20000062,'oFE7x1MVMAgdVrY6pUH8LNJtM2O0','張小贝💋','https://wx.qlogo.cn/mmopen/vi_32/PiajxSqBRaEKLhiaxdDbuia7g5f3bJVGNkNuex66XUTtBRQjdeN2ialcpbFyibCTicbM1KqiaAE7jhbKuNzKNdOugP2eQ/132','xcx','oMN5V439TO3EqGmLTwJKungXWQss','2018-07-31 20:34:33','2018-07-31 20:34:33'),
	(20,20000071,'oFE7x1ABeF9ULzaHsV6Bd2pxuYhs','夕阳照我去战斗','http://thirdwx.qlogo.cn/mmopen/vi_32/DYAIOgq83eqEmKJnflicx19sL2owDApxxUia0ameO0dlhd5X7lhs3ErTYUj3bykDFTiaHak6j1YNdyL1pFiaXzyqGw/132','wx','oXP8t1evchPm4ZEpl_KPKdvEaQWc','2018-08-02 11:16:14','2018-08-02 11:16:14'),
	(21,20000071,'oFE7x1ABeF9ULzaHsV6Bd2pxuYhs','夕阳照我去战斗','https://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83eoO0qppiaOI0ur4TicnBvjNKWBZqvyQ6IawQ7TYjrC4xLFHibpzRZ3BKLo3DaQKtYiacHFBASbqEujbhg/132','xcx','oMN5V44mh5MU-JPtVbtYt_SQJQuI','2018-08-06 11:44:40','2018-08-06 11:44:40'),
	(22,20000072,'oFE7x1PshuPRzt33fXkzAqK5hJ9M','王绩超','http://thirdwx.qlogo.cn/mmopen/vi_32/PiajxSqBRaELJq21ZTZdafkLk4l95GJicLUllCWaDhAIBPmDcRU0eNiaffEIxSvvDVCBZTObfuycoEqsx7lsKvQLA/132','wx','oXP8t1Uf6dG655Q_6AZ73yrHjmGM','2018-08-07 20:42:25','2018-08-07 20:42:25'),
	(23,20000073,'oFE7x1J_7qkM87KdxJHNIFTEO5FM','肖杰','http://thirdwx.qlogo.cn/mmopen/vi_32/kenDKAoibFRj7BJ7AVMHkScF9bicptWQoTHOI1pvbkkRYR5cV4rGbSg8HwRGdsba6g8Htr2IjEicibPaUljw3syibuw/132','wx','oXP8t1WwAbdfUytR84uYmybYHcRE','2018-08-07 20:55:47','2018-08-07 20:55:47'),
	(24,20000074,'oFE7x1BR4enA_AaNaJKC28YU0_nc','梁原','http://thirdwx.qlogo.cn/mmopen/vi_32/HY7jNvicCU0oiaX4WIeO0TPfyzUR7vibUBz69iaVbaibGHaeWsHNUpLky3HGVialXX76cUiacHEGm9OUeDD9zmphxQuxQ/132','wx','oXP8t1ek3-4Po1pb08EfSKoxgS1g','2018-08-07 22:31:31','2018-08-07 22:31:31'),
	(25,20000075,'oFE7x1BOO_2Tvpq0fOrLlLsQkrZ8','脸滚键盘','https://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83erahpokyosoS1EvmbQ7l879AjiaWWsBI02DkRROsicjNmwKzPBAGorPm2b7T19uOJl5Qg6CnBVkNNTA/132','xcx','oMN5V45Obz5Gk5nAGV-wY6MnqMOg','2018-08-16 11:13:05','2018-08-16 11:13:05'),
	(26,20000076,'oFE7x1Kkcj-Mi9xwh4Xn6Tv72Es4','曾强','https://wx.qlogo.cn/mmopen/vi_32/Q0j4TwGTfTI8k7Z3HGic6MDNav97LOyAibGxbEU6YBLfqsFp8DGnoZZDbKNAop8uIMpIXB0ooiciamdCGoa2sjc3dA/132','xcx','oMN5V45pTbtDip8fUTgtNyqzEi6M','2018-08-16 18:55:01','2018-08-16 18:55:01'),
	(27,20000077,'oFE7x1CVMnxVSS0icBsxW8eEF4gk','胡继波','https://wx.qlogo.cn/mmopen/vi_32/PiajxSqBRaEKDY3fOZsqcujuv7phmHnVQJcFDMX2Xcxo9RbOibEujicwV383GBJbM2W0ln1G3scgIQGTQP42Nu4kg/132','xcx','oMN5V4xx6DSaXJfWd0mncJfAzvG0','2018-08-16 19:04:13','2018-08-16 19:04:13'),
	(28,20000078,'oFE7x1BPJKNsv7yrIohVsEe3BZU4','孙晓萌','https://wx.qlogo.cn/mmopen/vi_32/DYAIOgq83ep9sRR1suQumHMbGkAtXEcicgGWqxfKEE4JbhtAGB6NBjFpIUDlDg70Fm88KyMIk0Kc8HqeY0QmkFw/132','xcx','oMN5V4wLd97vrRnhPmuAc-kKCBd8','2018-08-16 19:04:24','2018-08-16 19:04:24'),
	(29,20000079,'oFE7x1IVF2HtLHr8wK7qlzJeK_c4','茶花菇凉🌛','https://wx.qlogo.cn/mmopen/vi_32/V8oDkDQoExTc2a6YPmgESmkajQicDIBI2cKYxGKQT48iaR8oDsFjV1LpJV8ATLjB12CWLTLGhGzgJiaEat88EHaLg/132','xcx','oMN5V4xLoPYtpgmhGEGTXnl-gRJQ','2018-08-16 19:57:17','2018-08-16 19:57:17'),
	(30,20000062,'oFE7x1MVMAgdVrY6pUH8LNJtM2O0','張小贝💋','http://thirdwx.qlogo.cn/mmopen/vi_32/PiajxSqBRaEJ75patFktu0hJUpYYyGKSZhnLETyWxaCLRMSGrr440N0dUcGSPRcQVkQMib610PGDETYLdQ68S1kg/132','wx','oXP8t1XM9uRVKpOlPmnA4NqcFdlY','2018-08-20 10:08:43','2018-08-20 10:08:43'),
	(31,20000047,'oFE7x1OzH0yTT40Gw29CPdg2YrNo','好望角','http://thirdwx.qlogo.cn/mmopen/vi_32/DYAIOgq83eoBRyVJMv5DBpLMdRc6luwG9JQrNpIYIsZhjNa0ibQhNHEYfL8qn3dUatW4ESYHdBnIicicVnhY3ck0w/132','wx','oXP8t1fHLN2GoPL1KhEzXfS3wFXY','2018-08-21 15:46:54','2018-08-21 15:46:54');

/*!40000 ALTER TABLE `userbind` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
