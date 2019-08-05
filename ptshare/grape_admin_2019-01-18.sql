# ************************************************************
# Sequel Pro SQL dump
# Version 5425
#
# https://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.6.29-log)
# Database: grape_admin
# Generation Time: 2019-01-18 06:50:24 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table account
# ------------------------------------------------------------

CREATE TABLE `account` (
  `uid` int(11) NOT NULL,
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '申请理由'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='帐户转入申请表.';



# Dump of table account_apply
# ------------------------------------------------------------

CREATE TABLE `account_apply` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '申请理由',
  `sourceid` int(11) NOT NULL,
  `targetid` int(11) NOT NULL,
  `amount` bigint(20) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '申请状态.',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `adminid` int(11) NOT NULL COMMENT '操作人',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='帐户转入申请表.';



# Dump of table admin
# ------------------------------------------------------------

CREATE TABLE `admin` (
  `adminid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(50) DEFAULT '',
  `realname` varchar(50) NOT NULL,
  `mobile` varchar(255) NOT NULL DEFAULT '',
  `super` enum('Y','N') NOT NULL DEFAULT 'N',
  `active` enum('Y','N') DEFAULT 'Y',
  `roleid` varchar(255) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`adminid`),
  UNIQUE KEY `idx_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='管理员';



# Dump of table auth
# ------------------------------------------------------------

CREATE TABLE `auth` (
  `authid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) NOT NULL DEFAULT '0',
  `code` char(50) NOT NULL DEFAULT '' COMMENT '权限编码',
  `type` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:模块 2:权限',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`authid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='权限';



# Dump of table category
# ------------------------------------------------------------

CREATE TABLE `category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT '父类 id',
  `name` char(20) NOT NULL DEFAULT '' COMMENT '名称',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table config
# ------------------------------------------------------------

CREATE TABLE `config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `region` enum('china','abroad') NOT NULL DEFAULT 'china',
  `name` varchar(100) NOT NULL COMMENT '配置项',
  `platform` varchar(10) NOT NULL COMMENT '平台',
  `minversion` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '最小版本',
  `maxversion` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '最大版本',
  `value` text NOT NULL COMMENT '配置值',
  `expire` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '缓存有效时间',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `uptime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '最后修改时间',
  `remark` text NOT NULL COMMENT '备注',
  `tag` varchar(20) NOT NULL COMMENT '标签 分类',
  `jsonschema` varchar(3000) NOT NULL COMMENT '描述',
  `adduser` varchar(100) NOT NULL DEFAULT '' COMMENT '添加人',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='云控配置表';



# Dump of table config_history
# ------------------------------------------------------------

CREATE TABLE `config_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `config_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '配置项ID',
  `region` enum('china','abroad') NOT NULL DEFAULT 'china',
  `name` varchar(100) NOT NULL COMMENT '配置项',
  `platform` varchar(10) NOT NULL COMMENT '平台',
  `minversion` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '最小版本',
  `maxversion` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '最大版本',
  `value` text NOT NULL COMMENT '配置值',
  `expire` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '缓存有效时间',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `remark` text NOT NULL COMMENT '备注',
  `jsonschema` varchar(3000) NOT NULL COMMENT 'json描述',
  `operator` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '操作人id',
  PRIMARY KEY (`id`),
  KEY `config_id` (`config_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='云控配置历史表';



# Dump of table frozen_log
# ------------------------------------------------------------

CREATE TABLE `frozen_log` (
  `uid` bigint(20) NOT NULL DEFAULT '0',
  `reason` varchar(500) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='冻结日志表';



# Dump of table notice
# ------------------------------------------------------------

CREATE TABLE `notice` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `content` varchar(255) NOT NULL DEFAULT '' COMMENT '内容',
  `addadmin` int(11) NOT NULL DEFAULT '0' COMMENT '添加管理员',
  `sendadmin` int(11) NOT NULL DEFAULT '0' COMMENT '发送管理员',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  `sendtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '发送时间',
  `type` tinyint(2) NOT NULL DEFAULT '0' COMMENT '消息类型',
  `is_send` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '是否发送',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table operate
# ------------------------------------------------------------

CREATE TABLE `operate` (
  `operateid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `adminid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'adminid',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '日志属性',
  `type` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '操作资源类型',
  `uid` bigint(20) unsigned NOT NULL COMMENT '资源所属用户id',
  `relateid` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '资源id',
  `content` text NOT NULL COMMENT '日志参数',
  `context` text NOT NULL COMMENT '返回参数',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `reason` varchar(255) NOT NULL DEFAULT '' COMMENT '操作理由',
  `status` smallint(6) unsigned NOT NULL DEFAULT '1' COMMENT '是否操作成功',
  PRIMARY KEY (`operateid`),
  KEY `adminid` (`adminid`),
  KEY `type` (`type`,`relateid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='日志列表';



# Dump of table role
# ------------------------------------------------------------

CREATE TABLE `role` (
  `roleid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '角色名称',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `auth` text COMMENT '权限id',
  PRIMARY KEY (`roleid`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='角色';




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
