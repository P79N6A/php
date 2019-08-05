# ************************************************************
# Sequel Pro SQL dump
# Version 5425
#
# https://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.6.29-log)
# Database: grape_message
# Generation Time: 2019-01-18 06:50:10 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table message_0
# ------------------------------------------------------------

CREATE TABLE `message_0` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) NOT NULL DEFAULT '0' COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_1
# ------------------------------------------------------------

CREATE TABLE `message_1` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_10
# ------------------------------------------------------------

CREATE TABLE `message_10` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_11
# ------------------------------------------------------------

CREATE TABLE `message_11` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_12
# ------------------------------------------------------------

CREATE TABLE `message_12` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_13
# ------------------------------------------------------------

CREATE TABLE `message_13` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_14
# ------------------------------------------------------------

CREATE TABLE `message_14` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_15
# ------------------------------------------------------------

CREATE TABLE `message_15` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_16
# ------------------------------------------------------------

CREATE TABLE `message_16` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_17
# ------------------------------------------------------------

CREATE TABLE `message_17` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_18
# ------------------------------------------------------------

CREATE TABLE `message_18` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_19
# ------------------------------------------------------------

CREATE TABLE `message_19` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_2
# ------------------------------------------------------------

CREATE TABLE `message_2` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_20
# ------------------------------------------------------------

CREATE TABLE `message_20` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_21
# ------------------------------------------------------------

CREATE TABLE `message_21` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_22
# ------------------------------------------------------------

CREATE TABLE `message_22` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_23
# ------------------------------------------------------------

CREATE TABLE `message_23` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_24
# ------------------------------------------------------------

CREATE TABLE `message_24` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_25
# ------------------------------------------------------------

CREATE TABLE `message_25` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_26
# ------------------------------------------------------------

CREATE TABLE `message_26` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_27
# ------------------------------------------------------------

CREATE TABLE `message_27` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_28
# ------------------------------------------------------------

CREATE TABLE `message_28` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_29
# ------------------------------------------------------------

CREATE TABLE `message_29` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_3
# ------------------------------------------------------------

CREATE TABLE `message_3` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_30
# ------------------------------------------------------------

CREATE TABLE `message_30` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_31
# ------------------------------------------------------------

CREATE TABLE `message_31` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_32
# ------------------------------------------------------------

CREATE TABLE `message_32` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_33
# ------------------------------------------------------------

CREATE TABLE `message_33` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_34
# ------------------------------------------------------------

CREATE TABLE `message_34` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_35
# ------------------------------------------------------------

CREATE TABLE `message_35` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_36
# ------------------------------------------------------------

CREATE TABLE `message_36` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_37
# ------------------------------------------------------------

CREATE TABLE `message_37` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_38
# ------------------------------------------------------------

CREATE TABLE `message_38` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_39
# ------------------------------------------------------------

CREATE TABLE `message_39` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_4
# ------------------------------------------------------------

CREATE TABLE `message_4` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_40
# ------------------------------------------------------------

CREATE TABLE `message_40` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_41
# ------------------------------------------------------------

CREATE TABLE `message_41` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_42
# ------------------------------------------------------------

CREATE TABLE `message_42` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_43
# ------------------------------------------------------------

CREATE TABLE `message_43` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_44
# ------------------------------------------------------------

CREATE TABLE `message_44` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_45
# ------------------------------------------------------------

CREATE TABLE `message_45` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_46
# ------------------------------------------------------------

CREATE TABLE `message_46` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_47
# ------------------------------------------------------------

CREATE TABLE `message_47` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_48
# ------------------------------------------------------------

CREATE TABLE `message_48` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_49
# ------------------------------------------------------------

CREATE TABLE `message_49` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_5
# ------------------------------------------------------------

CREATE TABLE `message_5` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_50
# ------------------------------------------------------------

CREATE TABLE `message_50` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_51
# ------------------------------------------------------------

CREATE TABLE `message_51` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_52
# ------------------------------------------------------------

CREATE TABLE `message_52` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_53
# ------------------------------------------------------------

CREATE TABLE `message_53` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_54
# ------------------------------------------------------------

CREATE TABLE `message_54` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_55
# ------------------------------------------------------------

CREATE TABLE `message_55` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_56
# ------------------------------------------------------------

CREATE TABLE `message_56` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_57
# ------------------------------------------------------------

CREATE TABLE `message_57` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_58
# ------------------------------------------------------------

CREATE TABLE `message_58` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_59
# ------------------------------------------------------------

CREATE TABLE `message_59` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_6
# ------------------------------------------------------------

CREATE TABLE `message_6` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_60
# ------------------------------------------------------------

CREATE TABLE `message_60` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_61
# ------------------------------------------------------------

CREATE TABLE `message_61` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_62
# ------------------------------------------------------------

CREATE TABLE `message_62` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_63
# ------------------------------------------------------------

CREATE TABLE `message_63` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_64
# ------------------------------------------------------------

CREATE TABLE `message_64` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_65
# ------------------------------------------------------------

CREATE TABLE `message_65` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_66
# ------------------------------------------------------------

CREATE TABLE `message_66` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_67
# ------------------------------------------------------------

CREATE TABLE `message_67` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_68
# ------------------------------------------------------------

CREATE TABLE `message_68` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_69
# ------------------------------------------------------------

CREATE TABLE `message_69` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_7
# ------------------------------------------------------------

CREATE TABLE `message_7` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_70
# ------------------------------------------------------------

CREATE TABLE `message_70` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_71
# ------------------------------------------------------------

CREATE TABLE `message_71` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_72
# ------------------------------------------------------------

CREATE TABLE `message_72` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_73
# ------------------------------------------------------------

CREATE TABLE `message_73` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_74
# ------------------------------------------------------------

CREATE TABLE `message_74` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_75
# ------------------------------------------------------------

CREATE TABLE `message_75` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_76
# ------------------------------------------------------------

CREATE TABLE `message_76` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_77
# ------------------------------------------------------------

CREATE TABLE `message_77` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_78
# ------------------------------------------------------------

CREATE TABLE `message_78` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_79
# ------------------------------------------------------------

CREATE TABLE `message_79` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_8
# ------------------------------------------------------------

CREATE TABLE `message_8` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_80
# ------------------------------------------------------------

CREATE TABLE `message_80` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_81
# ------------------------------------------------------------

CREATE TABLE `message_81` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_82
# ------------------------------------------------------------

CREATE TABLE `message_82` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_83
# ------------------------------------------------------------

CREATE TABLE `message_83` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_84
# ------------------------------------------------------------

CREATE TABLE `message_84` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_85
# ------------------------------------------------------------

CREATE TABLE `message_85` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_86
# ------------------------------------------------------------

CREATE TABLE `message_86` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_87
# ------------------------------------------------------------

CREATE TABLE `message_87` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_88
# ------------------------------------------------------------

CREATE TABLE `message_88` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_89
# ------------------------------------------------------------

CREATE TABLE `message_89` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_9
# ------------------------------------------------------------

CREATE TABLE `message_9` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_90
# ------------------------------------------------------------

CREATE TABLE `message_90` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_91
# ------------------------------------------------------------

CREATE TABLE `message_91` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_92
# ------------------------------------------------------------

CREATE TABLE `message_92` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_93
# ------------------------------------------------------------

CREATE TABLE `message_93` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_94
# ------------------------------------------------------------

CREATE TABLE `message_94` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_95
# ------------------------------------------------------------

CREATE TABLE `message_95` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_96
# ------------------------------------------------------------

CREATE TABLE `message_96` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_97
# ------------------------------------------------------------

CREATE TABLE `message_97` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_98
# ------------------------------------------------------------

CREATE TABLE `message_98` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table message_99
# ------------------------------------------------------------

CREATE TABLE `message_99` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` smallint(5) DEFAULT NULL COMMENT '类型',
  `read` smallint(6) NOT NULL DEFAULT '0' COMMENT '0:未读；1:已读',
  `receiver` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '收信人',
  `content` varchar(1000) NOT NULL DEFAULT '' COMMENT '收信内容',
  `ext` char(200) NOT NULL DEFAULT '' COMMENT '扩展字段，存储参数',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '收信时间',
  PRIMARY KEY (`id`),
  KEY `receiver` (`receiver`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
