# ************************************************************
# Sequel Pro SQL dump
# Version 5425
#
# https://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.6.29-log)
# Database: grape_payment
# Generation Time: 2019-01-18 06:49:45 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table account_0
# ------------------------------------------------------------

CREATE TABLE `account_0` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '货币类型： 1 葡萄 2 冻结 3 现金',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_1
# ------------------------------------------------------------

CREATE TABLE `account_1` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_10
# ------------------------------------------------------------

CREATE TABLE `account_10` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_11
# ------------------------------------------------------------

CREATE TABLE `account_11` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_12
# ------------------------------------------------------------

CREATE TABLE `account_12` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_13
# ------------------------------------------------------------

CREATE TABLE `account_13` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_14
# ------------------------------------------------------------

CREATE TABLE `account_14` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_15
# ------------------------------------------------------------

CREATE TABLE `account_15` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_16
# ------------------------------------------------------------

CREATE TABLE `account_16` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_17
# ------------------------------------------------------------

CREATE TABLE `account_17` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_18
# ------------------------------------------------------------

CREATE TABLE `account_18` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_19
# ------------------------------------------------------------

CREATE TABLE `account_19` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_2
# ------------------------------------------------------------

CREATE TABLE `account_2` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_20
# ------------------------------------------------------------

CREATE TABLE `account_20` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_21
# ------------------------------------------------------------

CREATE TABLE `account_21` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_22
# ------------------------------------------------------------

CREATE TABLE `account_22` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_23
# ------------------------------------------------------------

CREATE TABLE `account_23` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_24
# ------------------------------------------------------------

CREATE TABLE `account_24` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_25
# ------------------------------------------------------------

CREATE TABLE `account_25` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_26
# ------------------------------------------------------------

CREATE TABLE `account_26` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_27
# ------------------------------------------------------------

CREATE TABLE `account_27` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_28
# ------------------------------------------------------------

CREATE TABLE `account_28` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_29
# ------------------------------------------------------------

CREATE TABLE `account_29` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_3
# ------------------------------------------------------------

CREATE TABLE `account_3` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_30
# ------------------------------------------------------------

CREATE TABLE `account_30` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_31
# ------------------------------------------------------------

CREATE TABLE `account_31` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_32
# ------------------------------------------------------------

CREATE TABLE `account_32` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_33
# ------------------------------------------------------------

CREATE TABLE `account_33` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_34
# ------------------------------------------------------------

CREATE TABLE `account_34` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_35
# ------------------------------------------------------------

CREATE TABLE `account_35` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_36
# ------------------------------------------------------------

CREATE TABLE `account_36` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_37
# ------------------------------------------------------------

CREATE TABLE `account_37` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_38
# ------------------------------------------------------------

CREATE TABLE `account_38` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_39
# ------------------------------------------------------------

CREATE TABLE `account_39` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_4
# ------------------------------------------------------------

CREATE TABLE `account_4` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_40
# ------------------------------------------------------------

CREATE TABLE `account_40` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_41
# ------------------------------------------------------------

CREATE TABLE `account_41` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_42
# ------------------------------------------------------------

CREATE TABLE `account_42` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_43
# ------------------------------------------------------------

CREATE TABLE `account_43` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_44
# ------------------------------------------------------------

CREATE TABLE `account_44` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_45
# ------------------------------------------------------------

CREATE TABLE `account_45` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_46
# ------------------------------------------------------------

CREATE TABLE `account_46` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_47
# ------------------------------------------------------------

CREATE TABLE `account_47` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_48
# ------------------------------------------------------------

CREATE TABLE `account_48` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_49
# ------------------------------------------------------------

CREATE TABLE `account_49` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_5
# ------------------------------------------------------------

CREATE TABLE `account_5` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_50
# ------------------------------------------------------------

CREATE TABLE `account_50` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_51
# ------------------------------------------------------------

CREATE TABLE `account_51` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_52
# ------------------------------------------------------------

CREATE TABLE `account_52` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_53
# ------------------------------------------------------------

CREATE TABLE `account_53` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_54
# ------------------------------------------------------------

CREATE TABLE `account_54` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_55
# ------------------------------------------------------------

CREATE TABLE `account_55` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_56
# ------------------------------------------------------------

CREATE TABLE `account_56` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_57
# ------------------------------------------------------------

CREATE TABLE `account_57` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_58
# ------------------------------------------------------------

CREATE TABLE `account_58` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_59
# ------------------------------------------------------------

CREATE TABLE `account_59` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_6
# ------------------------------------------------------------

CREATE TABLE `account_6` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_60
# ------------------------------------------------------------

CREATE TABLE `account_60` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_61
# ------------------------------------------------------------

CREATE TABLE `account_61` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_62
# ------------------------------------------------------------

CREATE TABLE `account_62` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_63
# ------------------------------------------------------------

CREATE TABLE `account_63` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_64
# ------------------------------------------------------------

CREATE TABLE `account_64` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_65
# ------------------------------------------------------------

CREATE TABLE `account_65` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_66
# ------------------------------------------------------------

CREATE TABLE `account_66` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_67
# ------------------------------------------------------------

CREATE TABLE `account_67` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_68
# ------------------------------------------------------------

CREATE TABLE `account_68` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_69
# ------------------------------------------------------------

CREATE TABLE `account_69` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_7
# ------------------------------------------------------------

CREATE TABLE `account_7` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_70
# ------------------------------------------------------------

CREATE TABLE `account_70` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_71
# ------------------------------------------------------------

CREATE TABLE `account_71` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_72
# ------------------------------------------------------------

CREATE TABLE `account_72` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_73
# ------------------------------------------------------------

CREATE TABLE `account_73` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_74
# ------------------------------------------------------------

CREATE TABLE `account_74` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_75
# ------------------------------------------------------------

CREATE TABLE `account_75` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_76
# ------------------------------------------------------------

CREATE TABLE `account_76` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_77
# ------------------------------------------------------------

CREATE TABLE `account_77` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_78
# ------------------------------------------------------------

CREATE TABLE `account_78` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_79
# ------------------------------------------------------------

CREATE TABLE `account_79` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_8
# ------------------------------------------------------------

CREATE TABLE `account_8` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_80
# ------------------------------------------------------------

CREATE TABLE `account_80` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_81
# ------------------------------------------------------------

CREATE TABLE `account_81` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_82
# ------------------------------------------------------------

CREATE TABLE `account_82` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_83
# ------------------------------------------------------------

CREATE TABLE `account_83` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_84
# ------------------------------------------------------------

CREATE TABLE `account_84` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_85
# ------------------------------------------------------------

CREATE TABLE `account_85` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_86
# ------------------------------------------------------------

CREATE TABLE `account_86` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_87
# ------------------------------------------------------------

CREATE TABLE `account_87` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_88
# ------------------------------------------------------------

CREATE TABLE `account_88` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_89
# ------------------------------------------------------------

CREATE TABLE `account_89` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_9
# ------------------------------------------------------------

CREATE TABLE `account_9` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_90
# ------------------------------------------------------------

CREATE TABLE `account_90` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_91
# ------------------------------------------------------------

CREATE TABLE `account_91` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_92
# ------------------------------------------------------------

CREATE TABLE `account_92` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_93
# ------------------------------------------------------------

CREATE TABLE `account_93` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_94
# ------------------------------------------------------------

CREATE TABLE `account_94` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_95
# ------------------------------------------------------------

CREATE TABLE `account_95` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_96
# ------------------------------------------------------------

CREATE TABLE `account_96` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_97
# ------------------------------------------------------------

CREATE TABLE `account_97` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_98
# ------------------------------------------------------------

CREATE TABLE `account_98` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table account_99
# ------------------------------------------------------------

CREATE TABLE `account_99` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '用户ID',
  `currency` tinyint(1) unsigned NOT NULL COMMENT '货币类型',
  `balance` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '余额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_type` (`uid`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户表';



# Dump of table express
# ------------------------------------------------------------

CREATE TABLE `express` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) NOT NULL COMMENT '订单号',
  `company` varchar(50) NOT NULL DEFAULT '' COMMENT '物流公司编号',
  `number` varchar(50) NOT NULL DEFAULT '' COMMENT '物流单号',
  `source` varchar(20) NOT NULL DEFAULT '',
  `status` smallint(6) NOT NULL DEFAULT '0' COMMENT '快递单当前的状态;\\0:待发货; \\1:已下单;\\2：已揽件；\\3：在途；\\4:派件 正在投递; \\10：妥投,签收；\\20：拒收； \\-1:下单失败;',
  `content` text NOT NULL COMMENT '物流信息',
  `eordercontent` text NOT NULL COMMENT '电子面单结果',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modtime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `recName` varchar(40) NOT NULL DEFAULT '' COMMENT '收件人姓名',
  `recPrintAddr` varchar(255) NOT NULL DEFAULT '' COMMENT '收件人地址',
  `recMobile` varchar(40) NOT NULL DEFAULT '' COMMENT '收件人手机',
  `recTel` varchar(40) NOT NULL DEFAULT '' COMMENT '收件人电话',
  `sendName` varchar(40) NOT NULL DEFAULT '' COMMENT '发件人姓名',
  `sendPrintAddr` varchar(288) NOT NULL DEFAULT '' COMMENT '发件人地址',
  `sendMobile` varchar(40) NOT NULL DEFAULT '' COMMENT '发几人手机',
  `sendTel` varchar(20) NOT NULL DEFAULT '' COMMENT '发件人电话',
  `weight` int(11) NOT NULL DEFAULT '0',
  `count` int(11) NOT NULL DEFAULT '0',
  `productName` varchar(50) NOT NULL DEFAULT '' COMMENT '产品名称',
  `desp` varchar(50) NOT NULL DEFAULT '' COMMENT '商品描述',
  `remark` varchar(50) NOT NULL DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`id`,`orderid`),
  UNIQUE KEY `uk_orderid` (`orderid`),
  KEY `idx_com_num` (`company`,`number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='物流信息记录';



# Dump of table express_worker
# ------------------------------------------------------------

CREATE TABLE `express_worker` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `source` varchar(20) NOT NULL DEFAULT '',
  `company` varchar(20) NOT NULL DEFAULT '',
  `orderid` bigint(20) unsigned NOT NULL COMMENT '订单号',
  `recName` varchar(40) NOT NULL DEFAULT '' COMMENT '收件人姓名',
  `recPrintAddr` varchar(255) NOT NULL DEFAULT '' COMMENT '收件人地址',
  `recMobile` varchar(40) NOT NULL DEFAULT '' COMMENT '收件人手机',
  `recTel` varchar(40) NOT NULL DEFAULT '' COMMENT '收件人电话',
  `sendName` varchar(40) NOT NULL DEFAULT '' COMMENT '发件人姓名',
  `sendPrintAddr` varchar(288) NOT NULL DEFAULT '' COMMENT '发件人地址',
  `sendMobile` varchar(40) NOT NULL DEFAULT '' COMMENT '发几人手机',
  `sendTel` varchar(20) NOT NULL DEFAULT '' COMMENT '发件人电话',
  `weight` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  `result` enum('N','Y') NOT NULL DEFAULT 'N' COMMENT '是否发送',
  `addtime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_orderid` (`orderid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table journal_0
# ------------------------------------------------------------

CREATE TABLE `journal_0` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_1
# ------------------------------------------------------------

CREATE TABLE `journal_1` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_10
# ------------------------------------------------------------

CREATE TABLE `journal_10` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_11
# ------------------------------------------------------------

CREATE TABLE `journal_11` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_12
# ------------------------------------------------------------

CREATE TABLE `journal_12` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_13
# ------------------------------------------------------------

CREATE TABLE `journal_13` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_14
# ------------------------------------------------------------

CREATE TABLE `journal_14` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_15
# ------------------------------------------------------------

CREATE TABLE `journal_15` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_16
# ------------------------------------------------------------

CREATE TABLE `journal_16` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_17
# ------------------------------------------------------------

CREATE TABLE `journal_17` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_18
# ------------------------------------------------------------

CREATE TABLE `journal_18` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_19
# ------------------------------------------------------------

CREATE TABLE `journal_19` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_2
# ------------------------------------------------------------

CREATE TABLE `journal_2` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_20
# ------------------------------------------------------------

CREATE TABLE `journal_20` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_21
# ------------------------------------------------------------

CREATE TABLE `journal_21` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_22
# ------------------------------------------------------------

CREATE TABLE `journal_22` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_23
# ------------------------------------------------------------

CREATE TABLE `journal_23` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_24
# ------------------------------------------------------------

CREATE TABLE `journal_24` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_25
# ------------------------------------------------------------

CREATE TABLE `journal_25` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_26
# ------------------------------------------------------------

CREATE TABLE `journal_26` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_27
# ------------------------------------------------------------

CREATE TABLE `journal_27` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_28
# ------------------------------------------------------------

CREATE TABLE `journal_28` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_29
# ------------------------------------------------------------

CREATE TABLE `journal_29` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_3
# ------------------------------------------------------------

CREATE TABLE `journal_3` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_30
# ------------------------------------------------------------

CREATE TABLE `journal_30` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_31
# ------------------------------------------------------------

CREATE TABLE `journal_31` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_32
# ------------------------------------------------------------

CREATE TABLE `journal_32` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_33
# ------------------------------------------------------------

CREATE TABLE `journal_33` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_34
# ------------------------------------------------------------

CREATE TABLE `journal_34` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_35
# ------------------------------------------------------------

CREATE TABLE `journal_35` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_36
# ------------------------------------------------------------

CREATE TABLE `journal_36` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_37
# ------------------------------------------------------------

CREATE TABLE `journal_37` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_38
# ------------------------------------------------------------

CREATE TABLE `journal_38` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_39
# ------------------------------------------------------------

CREATE TABLE `journal_39` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_4
# ------------------------------------------------------------

CREATE TABLE `journal_4` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_40
# ------------------------------------------------------------

CREATE TABLE `journal_40` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_41
# ------------------------------------------------------------

CREATE TABLE `journal_41` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_42
# ------------------------------------------------------------

CREATE TABLE `journal_42` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_43
# ------------------------------------------------------------

CREATE TABLE `journal_43` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_44
# ------------------------------------------------------------

CREATE TABLE `journal_44` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_45
# ------------------------------------------------------------

CREATE TABLE `journal_45` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_46
# ------------------------------------------------------------

CREATE TABLE `journal_46` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_47
# ------------------------------------------------------------

CREATE TABLE `journal_47` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_48
# ------------------------------------------------------------

CREATE TABLE `journal_48` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_49
# ------------------------------------------------------------

CREATE TABLE `journal_49` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_5
# ------------------------------------------------------------

CREATE TABLE `journal_5` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_50
# ------------------------------------------------------------

CREATE TABLE `journal_50` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_51
# ------------------------------------------------------------

CREATE TABLE `journal_51` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_52
# ------------------------------------------------------------

CREATE TABLE `journal_52` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`) USING BTREE,
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_53
# ------------------------------------------------------------

CREATE TABLE `journal_53` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_54
# ------------------------------------------------------------

CREATE TABLE `journal_54` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_55
# ------------------------------------------------------------

CREATE TABLE `journal_55` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_56
# ------------------------------------------------------------

CREATE TABLE `journal_56` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_57
# ------------------------------------------------------------

CREATE TABLE `journal_57` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_58
# ------------------------------------------------------------

CREATE TABLE `journal_58` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_59
# ------------------------------------------------------------

CREATE TABLE `journal_59` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_6
# ------------------------------------------------------------

CREATE TABLE `journal_6` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_60
# ------------------------------------------------------------

CREATE TABLE `journal_60` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_61
# ------------------------------------------------------------

CREATE TABLE `journal_61` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_62
# ------------------------------------------------------------

CREATE TABLE `journal_62` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_63
# ------------------------------------------------------------

CREATE TABLE `journal_63` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_64
# ------------------------------------------------------------

CREATE TABLE `journal_64` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_65
# ------------------------------------------------------------

CREATE TABLE `journal_65` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_66
# ------------------------------------------------------------

CREATE TABLE `journal_66` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_67
# ------------------------------------------------------------

CREATE TABLE `journal_67` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_68
# ------------------------------------------------------------

CREATE TABLE `journal_68` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_69
# ------------------------------------------------------------

CREATE TABLE `journal_69` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_7
# ------------------------------------------------------------

CREATE TABLE `journal_7` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_70
# ------------------------------------------------------------

CREATE TABLE `journal_70` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_71
# ------------------------------------------------------------

CREATE TABLE `journal_71` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_72
# ------------------------------------------------------------

CREATE TABLE `journal_72` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_73
# ------------------------------------------------------------

CREATE TABLE `journal_73` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_74
# ------------------------------------------------------------

CREATE TABLE `journal_74` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_75
# ------------------------------------------------------------

CREATE TABLE `journal_75` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_76
# ------------------------------------------------------------

CREATE TABLE `journal_76` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_77
# ------------------------------------------------------------

CREATE TABLE `journal_77` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_78
# ------------------------------------------------------------

CREATE TABLE `journal_78` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_79
# ------------------------------------------------------------

CREATE TABLE `journal_79` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_8
# ------------------------------------------------------------

CREATE TABLE `journal_8` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_80
# ------------------------------------------------------------

CREATE TABLE `journal_80` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_81
# ------------------------------------------------------------

CREATE TABLE `journal_81` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_82
# ------------------------------------------------------------

CREATE TABLE `journal_82` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_83
# ------------------------------------------------------------

CREATE TABLE `journal_83` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_84
# ------------------------------------------------------------

CREATE TABLE `journal_84` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_85
# ------------------------------------------------------------

CREATE TABLE `journal_85` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_86
# ------------------------------------------------------------

CREATE TABLE `journal_86` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_87
# ------------------------------------------------------------

CREATE TABLE `journal_87` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_88
# ------------------------------------------------------------

CREATE TABLE `journal_88` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_89
# ------------------------------------------------------------

CREATE TABLE `journal_89` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_9
# ------------------------------------------------------------

CREATE TABLE `journal_9` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_90
# ------------------------------------------------------------

CREATE TABLE `journal_90` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_91
# ------------------------------------------------------------

CREATE TABLE `journal_91` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_92
# ------------------------------------------------------------

CREATE TABLE `journal_92` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_93
# ------------------------------------------------------------

CREATE TABLE `journal_93` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_94
# ------------------------------------------------------------

CREATE TABLE `journal_94` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_95
# ------------------------------------------------------------

CREATE TABLE `journal_95` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_96
# ------------------------------------------------------------

CREATE TABLE `journal_96` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_97
# ------------------------------------------------------------

CREATE TABLE `journal_97` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_98
# ------------------------------------------------------------

CREATE TABLE `journal_98` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table journal_99
# ------------------------------------------------------------

CREATE TABLE `journal_99` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `product` char(20) NOT NULL DEFAULT 'ptshare' COMMENT '产品',
  `type` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '交易类型:1葡萄转账2任务奖励3现金奖励4财务现金转账5冻结葡萄6解冻葡萄7财务出账8财务回收9红包奖励10租金扣除11代金券扣除12商品丢失扣款13租赁订单取消',
  `direct` enum('IN','OUT') NOT NULL DEFAULT 'IN' COMMENT '方向:支出OUT,收入IN',
  `currency` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '币种',
  `amount` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '金额',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `extends` text NOT NULL COMMENT '扩展:记录分账比例,总金额',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `orderid` (`orderid`),
  KEY `uid` (`uid`,`product`,`id`,`currency`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='账户变动记录';



# Dump of table orders
# ------------------------------------------------------------

CREATE TABLE `orders` (
  `orderid` bigint(20) unsigned NOT NULL COMMENT '订单号',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '订单发起人',
  `type` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '1代表分享订单，2租用订单，3续租订单，4购买订单，5夺宝订单',
  `status` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '订单状态。0，待确认；1，已确认；2，已取消',
  `grape` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '葡萄数',
  `deposit_price` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '押金',
  `service_price` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '服务费',
  `relateid` bigint(20) NOT NULL DEFAULT '0' COMMENT '包ID',
  `remark` varchar(50) NOT NULL DEFAULT '' COMMENT '备注，用于后台查看',
  `pay_no` char(60) NOT NULL DEFAULT '' COMMENT '交易号',
  `pay_status` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '支付状态；0，未付款；1，已付款待确认；2，确认付款',
  `pay_type` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '支付方式，0.葡萄+代金券 1.葡萄+微信 2. 葡萄+代金券+微信',
  `pay_price` double(20,4) NOT NULL DEFAULT '0.0000' COMMENT '支付现金',
  `pay_coupon` double(20,4) NOT NULL DEFAULT '0.0000' COMMENT '支付代金券',
  `pay_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '支付时间',
  `express_status` tinyint(4) unsigned NOT NULL DEFAULT '0' COMMENT '商品配送情况，0，未发货；1，已发货；2，已收货；',
  `express_price` double(20,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '快递费',
  `express_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '签发时间',
  `receive_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '签收时间',
  `contact_name` varchar(20) DEFAULT '' COMMENT '联系人姓名',
  `contact_zipcode` varchar(10) DEFAULT '' COMMENT '联系人邮编',
  `contact_province` varchar(40) DEFAULT '' COMMENT '联系人省份',
  `contact_city` varchar(40) DEFAULT '' COMMENT '联系人地区',
  `contact_county` varchar(40) DEFAULT '' COMMENT '联系人城市',
  `contact_address` varchar(255) DEFAULT '' COMMENT '联系人地址',
  `contact_national` varchar(10) DEFAULT '' COMMENT '联系人国家',
  `contact_phone` varchar(20) DEFAULT '' COMMENT '联系人电话',
  `addtime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `vip` tinyint(3) unsigned DEFAULT '0' COMMENT '是否vip,0否，1是',
  PRIMARY KEY (`orderid`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table orderslog
# ------------------------------------------------------------

CREATE TABLE `orderslog` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '流水号',
  `orderid` bigint(20) unsigned NOT NULL COMMENT '订单号',
  `content` text COMMENT '订单快照',
  `remark` text COMMENT '订单备注',
  `operator` int(10) DEFAULT NULL COMMENT '操作人',
  `addtime` datetime DEFAULT NULL COMMENT '操作时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table pay
# ------------------------------------------------------------

CREATE TABLE `pay` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '订单号',
  `uid` bigint(20) NOT NULL DEFAULT '0' COMMENT '所属用户id',
  `amount` double(20,4) NOT NULL DEFAULT '0.0000' COMMENT '支付金额',
  `coupon` double(20,4) NOT NULL DEFAULT '0.0000' COMMENT '代金券金额',
  `currency` enum('USD','CNY','TWD') NOT NULL DEFAULT 'CNY' COMMENT '支付币种:美元,人民币, 台湾币',
  `source` varchar(50) NOT NULL DEFAULT '' COMMENT '支付来源',
  `tradeid` varchar(150) NOT NULL DEFAULT '' COMMENT '第三方订单号',
  `extends` text COMMENT '扩展字段',
  `status` enum('P','Y','N','C','R') NOT NULL DEFAULT 'P' COMMENT '是否成功, P准备, Y, 成功, N, 失败， C取消, R已退款',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  `transaction_id` char(60) NOT NULL DEFAULT '' COMMENT '支付交易号',
  `refund_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '退款时间',
  `type` enum('ORDER','MEMBER','VIP') NOT NULL DEFAULT 'ORDER' COMMENT '支付类型，ORDER购买或租赁MEMBER会员充值',
  PRIMARY KEY (`id`),
  KEY `sn` (`orderid`),
  KEY `uid` (`uid`),
  KEY `tradid` (`transaction_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='支付';



# Dump of table paylog
# ------------------------------------------------------------

CREATE TABLE `paylog` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `source` char(10) DEFAULT NULL COMMENT '源',
  `content` text COMMENT '接收内容(request, put)',
  `addtime` datetime DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `source` (`source`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='回调日志表, 所有的允许空， 以方便接收一切回调.';



# Dump of table red_packet
# ------------------------------------------------------------

CREATE TABLE `red_packet` (
  `redid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包数量',
  `type` tinyint(3) unsigned NOT NULL COMMENT '类型',
  `status` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '状态: Y分享成功N未分享',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包总金额',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`redid`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_0
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_0` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_1
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_1` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_10
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_10` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_11
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_11` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_12
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_12` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_13
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_13` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_14
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_14` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_15
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_15` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_16
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_16` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_17
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_17` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_18
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_18` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_19
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_19` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_2
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_2` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_20
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_20` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_21
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_21` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_22
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_22` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_23
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_23` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_24
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_24` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_25
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_25` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_26
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_26` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_27
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_27` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_28
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_28` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_29
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_29` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_3
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_3` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_30
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_30` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_31
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_31` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_32
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_32` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_33
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_33` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_34
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_34` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_35
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_35` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_36
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_36` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_37
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_37` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_38
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_38` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_39
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_39` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_4
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_4` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_40
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_40` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_41
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_41` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_42
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_42` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_43
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_43` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_44
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_44` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_45
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_45` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_46
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_46` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_47
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_47` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_48
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_48` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_49
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_49` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_5
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_5` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_50
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_50` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_51
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_51` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_52
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_52` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_53
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_53` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_54
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_54` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_55
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_55` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_56
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_56` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_57
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_57` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_58
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_58` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_59
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_59` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_6
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_6` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_60
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_60` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_61
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_61` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_62
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_62` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_63
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_63` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_64
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_64` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_65
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_65` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_66
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_66` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_67
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_67` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_68
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_68` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_69
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_69` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_7
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_7` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_70
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_70` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_71
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_71` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_72
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_72` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_73
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_73` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_74
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_74` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_75
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_75` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_76
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_76` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_77
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_77` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_78
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_78` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_79
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_79` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_8
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_8` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_80
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_80` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_81
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_81` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_82
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_82` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_83
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_83` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_84
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_84` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_85
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_85` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_86
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_86` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_87
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_87` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_88
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_88` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_89
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_89` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_9
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_9` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_90
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_90` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_91
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_91` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_92
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_92` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_93
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_93` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_94
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_94` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_95
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_95` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_96
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_96` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_97
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_97` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_98
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_98` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table red_packet_log_99
# ------------------------------------------------------------

CREATE TABLE `red_packet_log_99` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  `relateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'relateid',
  `redid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '红包id',
  `type` enum('send','receive') NOT NULL DEFAULT 'receive' COMMENT '类型，send:发，receive:领取',
  `flags` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '标识:0,普通红包，1.新用户，2，手气最佳',
  `amount` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '领取金额',
  `orderid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '完成奖励的id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
