# ************************************************************
# Sequel Pro SQL dump
# Version 5425
#
# https://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.6.29-log)
# Database: grape_mall
# Generation Time: 2019-01-18 06:47:43 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table buying
# ------------------------------------------------------------

CREATE TABLE `buying` (
  `buyid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户号',
  `packageid` varchar(40) NOT NULL DEFAULT '' COMMENT 'packageid',
  `type` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '100，待收货400，完成',
  `status` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '101待支付，102代发货，103已发货，401待评价，402已评价，403已取消，404已撤销',
  `sn` varchar(40) NOT NULL COMMENT 'serial number',
  `num` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '商品数',
  `grape` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '实付葡萄数',
  `pay_price` double(20,2) DEFAULT '0.00' COMMENT '支付现金',
  `pay_coupon` double(20,2) DEFAULT '0.00' COMMENT '支付代金券',
  `orderid` bigint(20) NOT NULL DEFAULT '0' COMMENT 'orderid',
  `result` tinyint(4) NOT NULL DEFAULT '0' COMMENT '保证与订单数据一致性，0，订单处理未完成，1，订单处理完成',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `modtime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`buyid`),
  KEY `packageid` (`packageid`),
  KEY `uid_type` (`uid`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户购买的package';



# Dump of table buying_log
# ------------------------------------------------------------

CREATE TABLE `buying_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(10) NOT NULL COMMENT '用户号',
  `orderid` bigint(20) NOT NULL DEFAULT '0' COMMENT '订单号',
  `buyid` bigint(20) NOT NULL DEFAULT '0' COMMENT 'buyid',
  `packageid` varchar(40) NOT NULL DEFAULT '' COMMENT 'packageid',
  `sn` varchar(40) NOT NULL COMMENT 'serial number',
  `extends` varchar(1024) NOT NULL DEFAULT '' COMMENT '日志',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `result` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '结果，0，初始化，1，处理完成',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `modtime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='购买流转记录';



# Dump of table category
# ------------------------------------------------------------

CREATE TABLE `category` (
  `cid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '分类名称',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父id',
  `size` char(2) NOT NULL DEFAULT '' COMMENT '规格组',
  `grape` int(11) NOT NULL DEFAULT '0' COMMENT '葡萄',
  `sort` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='类目';



# Dump of table config
# ------------------------------------------------------------

CREATE TABLE `config` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `region` enum('china','abroad') NOT NULL DEFAULT 'china',
  `name` varchar(100) NOT NULL COMMENT '配置项',
  `platform` varchar(10) NOT NULL COMMENT '平台',
  `min_version` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '最小版本',
  `max_version` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '最大版本',
  `value` text NOT NULL COMMENT '配置值',
  `expire` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '缓存有效时间',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '最后修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table favorite
# ------------------------------------------------------------

CREATE TABLE `favorite` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `packageid` int(10) unsigned NOT NULL COMMENT '包 id',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `u_p_unique` (`uid`,`packageid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='收藏记录';



# Dump of table goods
# ------------------------------------------------------------

CREATE TABLE `goods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `good_sn` varchar(20) NOT NULL DEFAULT ' ' COMMENT '商品唯一 id',
  `sellid` int(11) unsigned NOT NULL DEFAULT '0',
  `status` int(11) unsigned NOT NULL DEFAULT '100',
  `show_grape` int(11) unsigned NOT NULL DEFAULT '0',
  `worth_grape` int(11) unsigned NOT NULL DEFAULT '0',
  `labels` text NOT NULL COMMENT '标签信息',
  `description` text NOT NULL COMMENT '描述',
  `categoryid` int(11) NOT NULL DEFAULT '0' COMMENT '分类id',
  `type` char(20) NOT NULL DEFAULT '' COMMENT '类型：图片，视频',
  `file` char(200) NOT NULL DEFAULT '' COMMENT '文件地址',
  `extends` text NOT NULL COMMENT '扩展信息，包含尺寸、新旧程度等信息',
  `is_packed` smallint(6) NOT NULL DEFAULT '0' COMMENT '是否已打包，默认0;1未打包；2:已打包',
  `remark` varchar(50) NOT NULL DEFAULT ' ' COMMENT '备注',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `refuse_reason` varchar(255) NOT NULL DEFAULT '' COMMENT '拒绝原因',
  PRIMARY KEY (`id`),
  UNIQUE KEY `good_sn` (`good_sn`),
  KEY `sellid` (`sellid`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table invite
# ------------------------------------------------------------

CREATE TABLE `invite` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '被邀请用户uid',
  `inviter` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '邀请人uid',
  `status` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT 'Y 已使用 N 未使用',
  `extend` varchar(255) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `expiretime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid` (`uid`),
  KEY `idx_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table lottery
# ------------------------------------------------------------

CREATE TABLE `lottery` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户',
  `sellid` varchar(50) NOT NULL DEFAULT '' COMMENT 'sellid',
  `issue` int(11) NOT NULL DEFAULT '0' COMMENT '开奖期号',
  `pt_issue` int(11) NOT NULL DEFAULT '0' COMMENT '葡萄期号',
  `number` varchar(7) NOT NULL DEFAULT '' COMMENT '彩票号码',
  `number_format` varchar(7) NOT NULL DEFAULT '',
  `result` tinyint(6) NOT NULL DEFAULT '0' COMMENT '开奖结果 0 未开奖 1 一等奖 2 二等奖 99 未中奖',
  `status` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '发放状态 N 未发放 Y已发放',
  `orderid` bigint(20) NOT NULL DEFAULT '0' COMMENT '订单id',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `modtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_sellid` (`sellid`),
  KEY `idx_uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='彩票';



# Dump of table lottery_config
# ------------------------------------------------------------

CREATE TABLE `lottery_config` (
  `issue` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '开奖期号',
  `pt_issue` int(11) NOT NULL DEFAULT '0' COMMENT '葡萄期号',
  `number` char(7) NOT NULL DEFAULT '' COMMENT '中奖号码',
  `status` enum('Y','N','P','R') NOT NULL DEFAULT 'N' COMMENT '开奖状态 N 未开 Y已开 P 准备开奖 R开奖中',
  `startime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '开始时间',
  `endtime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '结束时间',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `modtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`issue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='彩票';



# Dump of table mini_card
# ------------------------------------------------------------

CREATE TABLE `mini_card` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `cover` varchar(255) NOT NULL DEFAULT '' COMMENT 'url地址',
  `text` varchar(1000) NOT NULL DEFAULT '' COMMENT '文本',
  `type` enum('IMAGE','VIDEO') NOT NULL DEFAULT 'IMAGE' COMMENT '卡片类型',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='小广告';



# Dump of table operate
# ------------------------------------------------------------

CREATE TABLE `operate` (
  `logid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(3) unsigned NOT NULL COMMENT '类型',
  `relateid` int(10) unsigned NOT NULL COMMENT '相关ID',
  `uid` int(10) unsigned NOT NULL COMMENT '处理人',
  `remark` varchar(1024) NOT NULL DEFAULT '' COMMENT '备注',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`logid`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table package
# ------------------------------------------------------------

CREATE TABLE `package` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sell_user_id` int(11) NOT NULL COMMENT '分享者的 ID',
  `packageid` varchar(50) NOT NULL DEFAULT '',
  `sn` varchar(40) NOT NULL COMMENT '追踪码',
  `categoryid` int(11) NOT NULL DEFAULT '0' COMMENT '分类',
  `source` int(11) NOT NULL DEFAULT '0' COMMENT '来源',
  `source_id` int(11) NOT NULL DEFAULT '0' COMMENT '来源 ID',
  `source_uid` int(11) NOT NULL COMMENT '根据来源获取上一个用户的 id',
  `orderid` bigint(20) NOT NULL DEFAULT '0' COMMENT '最后一次订单orderid',
  `cover` varchar(255) NOT NULL DEFAULT '' COMMENT '封面',
  `cover_type` varchar(20) NOT NULL DEFAULT '' COMMENT '封面类型',
  `contact` text NOT NULL COMMENT '地址信息',
  `num` tinyint(3) NOT NULL DEFAULT '0' COMMENT '商品数',
  `location` varchar(255) NOT NULL DEFAULT '' COMMENT '所在地',
  `deposit_price` int(10) NOT NULL DEFAULT '0' COMMENT '押金',
  `rent_price` int(10) NOT NULL DEFAULT '0' COMMENT '租金',
  `description` varchar(1024) NOT NULL DEFAULT '' COMMENT '描述',
  `online` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '上架状态',
  `status` enum('PREPARE','ONLINE','OFFLINE','SELLOUT','DELETE') NOT NULL DEFAULT 'PREPARE' COMMENT 'PREPARE准备中，ONLIN上架中OFFLINE下架,SELLOUT已售罄',
  `favorite_num` int(10) NOT NULL DEFAULT '0' COMMENT '收藏数',
  `view_num` int(10) NOT NULL DEFAULT '0' COMMENT '查看数',
  `extends` text NOT NULL COMMENT '扩展信息',
  `endtime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '预计发货时间',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '上架时间',
  `modtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '下架时间',
  `cardid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '小卡片id',
  `grape_forward` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1自己用，2吸粉使用，3捐给平台',
  `sales_type` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '销售方式,0一口价租售，1一元夺宝',
  `vip` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否是vip 0否，1是',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1代表只买，2代表只租',
  PRIMARY KEY (`id`),
  KEY `source_id` (`source_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table package_goods
# ------------------------------------------------------------

CREATE TABLE `package_goods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL,
  `goods_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='package 与 goods 的关联关系表';



# Dump of table package_previews
# ------------------------------------------------------------

CREATE TABLE `package_previews` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `package_id` int(11) NOT NULL,
  `preview_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='package 与 封面图 的关联关系表';



# Dump of table penalty
# ------------------------------------------------------------

CREATE TABLE `penalty` (
  `penaltyid` bigint(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(10) NOT NULL COMMENT '用户ID',
  `type` tinyint(3) NOT NULL COMMENT '处罚类型',
  `relateid` int(10) NOT NULL COMMENT '相关ID',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `grape` int(10) NOT NULL COMMENT '罚金',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  PRIMARY KEY (`penaltyid`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='处罚记录';



# Dump of table preview_resource
# ------------------------------------------------------------

CREATE TABLE `preview_resource` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(10) NOT NULL DEFAULT '' COMMENT '类型：image/vedio',
  `sellid` int(11) NOT NULL,
  `is_cover` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否封面',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT 'file 的 url 地址',
  `status` int(4) NOT NULL DEFAULT '200' COMMENT '状态，有不展示状态',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `sellid` (`sellid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商品预览图包含封面';



# Dump of table renting
# ------------------------------------------------------------

CREATE TABLE `renting` (
  `rentid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `relateid` bigint(10) NOT NULL COMMENT 'user_renting的id',
  `uid` bigint(10) NOT NULL COMMENT '用户号',
  `orderid` bigint(20) NOT NULL COMMENT '订单号',
  `packageid` varchar(40) NOT NULL DEFAULT '' COMMENT 'packageid',
  `sn` varchar(40) NOT NULL COMMENT 'serial number',
  `num` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '商品数',
  `month` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '租期(整月)',
  `rent_type` enum('RENT','RELET') NOT NULL DEFAULT 'RENT' COMMENT '订单类型',
  `express_type` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '快递类型，1，公司-用户，2，用户-用户,3 续租',
  `deposit_price` double(20,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '押金',
  `rent_price` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '租金',
  `pay_price` double(20,2) DEFAULT '0.00' COMMENT '支付现金',
  `pay_coupon` varchar(30) DEFAULT '' COMMENT '支付代金券',
  `service_price` double(20,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '服务费',
  `express_price` double(20,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '快递费',
  `type` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '100，待收货，200，借用中，300，待传递，400，完成',
  `status` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '101待支付，102代发货，103已发货，200借用中，300带传递，401待评价，402已评价',
  `result` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '保证与订单数据一致性，0，订单处理未完成，1，订单处理完成',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `modtime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`rentid`),
  KEY `relateid` (`relateid`),
  KEY `orderid` (`orderid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='租借流水';



# Dump of table renting_log
# ------------------------------------------------------------

CREATE TABLE `renting_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(10) NOT NULL COMMENT '用户号',
  `orderid` bigint(20) NOT NULL DEFAULT '0' COMMENT '订单号',
  `relateid` bigint(20) NOT NULL DEFAULT '0' COMMENT 'user_renting id',
  `rentid` bigint(20) NOT NULL DEFAULT '0' COMMENT 'rentid',
  `packageid` varchar(40) NOT NULL DEFAULT '' COMMENT 'packageid',
  `sn` varchar(40) NOT NULL COMMENT 'serial number',
  `extends` varchar(1024) NOT NULL DEFAULT '' COMMENT '流转记录',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `result` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '结果，0，初始化，1，处理完成',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `modtime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='租借流转记录';



# Dump of table seizing
# ------------------------------------------------------------

CREATE TABLE `seizing` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户号',
  `packageid` varchar(40) NOT NULL DEFAULT '' COMMENT 'packageid',
  `number` int(10) NOT NULL DEFAULT '200000' COMMENT 'number',
  `type` enum('N','Y') NOT NULL DEFAULT 'N' COMMENT 'N未开奖，Y已开奖',
  `win_number` int(10) NOT NULL DEFAULT '0' COMMENT '获奖号',
  `grape` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '实付葡萄数',
  `orderid` bigint(20) NOT NULL DEFAULT '0' COMMENT 'orderid',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `msec` int(10) NOT NULL DEFAULT '0' COMMENT '毫秒',
  `modtime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `packageid` (`packageid`),
  KEY `uid_type` (`uid`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='一元夺宝';



# Dump of table sell
# ------------------------------------------------------------

CREATE TABLE `sell` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `categoryid` int(11) NOT NULL DEFAULT '0' COMMENT '所属分类',
  `sell_num` varchar(50) NOT NULL DEFAULT '' COMMENT '出售编号',
  `sn` char(40) NOT NULL COMMENT '序列号',
  `uid` int(11) NOT NULL COMMENT '用户',
  `cover` varchar(255) NOT NULL DEFAULT '' COMMENT '封面',
  `cover_type` varchar(20) NOT NULL DEFAULT '' COMMENT '封面类型',
  `description` varchar(1024) NOT NULL DEFAULT '' COMMENT '描述信息',
  `status` smallint(6) NOT NULL DEFAULT '200' COMMENT '状态信息，根据业务补全',
  `num` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '商品数量',
  `free` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '免费赠送',
  `contact` varchar(1024) NOT NULL DEFAULT '' COMMENT '联系方式',
  `orderid` bigint(20) NOT NULL DEFAULT '0' COMMENT '订单号',
  `remark` varchar(50) NOT NULL DEFAULT ' ' COMMENT '备注，拒绝原因',
  `extends` text NOT NULL COMMENT '扩展信息',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `modtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `cardid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '小卡片id',
  `grape_forward` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '1自己用，2吸粉使用，3捐给平台',
  `sales_type` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '销售方式,0一口价租售，1一元夺宝',
  `vip` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否是vip 0否，1是',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1代表只买，2代表只租',
  PRIMARY KEY (`id`),
  UNIQUE KEY `sn` (`sn`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='出售';



# Dump of table spec
# ------------------------------------------------------------

CREATE TABLE `spec` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `size` char(2) NOT NULL DEFAULT '' COMMENT '类型',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='规格';



# Dump of table tag_history
# ------------------------------------------------------------

CREATE TABLE `tag_history` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0',
  `tagid` varchar(10) NOT NULL DEFAULT '',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid_tagid` (`uid`,`tagid`),
  KEY `idx_uid_addtime` (`uid`,`addtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table tags
# ------------------------------------------------------------

CREATE TABLE `tags` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tagid` varchar(10) NOT NULL COMMENT 'tagid',
  `tagname` varchar(20) NOT NULL DEFAULT '' COMMENT '名称',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_tagid` (`tagid`),
  UNIQUE KEY `uk_tagname` (`tagname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Tag';



# Dump of table travel_group
# ------------------------------------------------------------

CREATE TABLE `travel_group` (
  `id` bigint(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `travel_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动id',
  `code` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '抽奖码',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  `members` text COMMENT '成员json',
  `finish_num` int(11) NOT NULL DEFAULT '0' COMMENT '完成人数',
  `num` int(11) NOT NULL DEFAULT '0' COMMENT '要求人数',
  `isfinish` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '组队是否完成Y已完成,N未完成',
  `finish_time` varchar(30) NOT NULL DEFAULT '' COMMENT '完成时间',
  PRIMARY KEY (`id`),
  KEY `code` (`code`),
  KEY `uid` (`uid`),
  KEY `travel_id` (`travel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='组队抽奖创建者表';



# Dump of table travel_lottery
# ------------------------------------------------------------

CREATE TABLE `travel_lottery` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '组队抽奖名称',
  `award` varchar(2000) NOT NULL DEFAULT '' COMMENT '奖品名称',
  `num` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '组队人数,2,3,4三种',
  `total` int(11) NOT NULL DEFAULT '0' COMMENT '组队份数',
  `list_cover` varchar(255) NOT NULL DEFAULT '' COMMENT '列表封面',
  `startime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '开始时间',
  `endtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '结束时间',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  `status` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '是否开奖',
  `wincode` int(11) NOT NULL DEFAULT '0' COMMENT '中奖码',
  `finish_total` int(11) NOT NULL DEFAULT '0' COMMENT '组队成功份数',
  `isfinish` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '组队是否达标',
  `remark` text COMMENT '备注',
  `detail_cover` varchar(255) NOT NULL DEFAULT '' COMMENT '详情图',
  `share_cover` varchar(255) NOT NULL DEFAULT '' COMMENT '分享图',
  `gate_cover` varchar(255) NOT NULL DEFAULT '' COMMENT '入口图',
  `isshow` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `refund_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '退款时间',
  PRIMARY KEY (`id`),
  KEY `wincode` (`wincode`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='组队抽奖活动';



# Dump of table travel_lottery_log
# ------------------------------------------------------------

CREATE TABLE `travel_lottery_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `travel_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动id',
  `type` enum('M','T') NOT NULL DEFAULT 'M' COMMENT 'M代表最后是个用户的时间，T代表体彩排列5的开奖号码',
  `hisx` bigint(20) NOT NULL DEFAULT '0' COMMENT '数值',
  `finish_time` varchar(30) NOT NULL DEFAULT '' COMMENT '结束时间',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `travel_id` (`travel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



# Dump of table travel_member
# ------------------------------------------------------------

CREATE TABLE `travel_member` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `travel_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动id',
  `relateid` bigint(11) NOT NULL DEFAULT '0' COMMENT '分享商品id',
  `groupid` bigint(11) NOT NULL DEFAULT '0' COMMENT '被邀请记录表',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `addtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
  `modtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '修改时间',
  `status` enum('ON','OFF') NOT NULL DEFAULT 'ON' COMMENT '活动是否已结束',
  `micro_sec` int(11) NOT NULL DEFAULT '0' COMMENT '加入毫秒时间',
  `type` enum('INVITER','INVITEE') NOT NULL DEFAULT 'INVITER' COMMENT 'INVITER邀请者,INVITEE被邀请者',
  `iswin` enum('Y','N') NOT NULL DEFAULT 'N' COMMENT '是否中奖',
  `from` varchar(20) NOT NULL DEFAULT '' COMMENT '动作来源',
  `isshow` enum('Y','N') NOT NULL DEFAULT 'Y' COMMENT '是否显示',
  PRIMARY KEY (`id`),
  UNIQUE KEY `groupid&&uid` (`groupid`,`uid`),
  KEY `travel_id` (`travel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='组队抽奖参与记录表';



# Dump of table user_renting
# ------------------------------------------------------------

CREATE TABLE `user_renting` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户号',
  `packageid` varchar(40) NOT NULL DEFAULT '' COMMENT 'packageid',
  `type` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '100，待收货，200，借用中，300，待传递，400，完成',
  `status` int(4) unsigned NOT NULL DEFAULT '0' COMMENT '101待支付，102代发货，103已发货，200借用中，300带传递，401待评价，402已评价',
  `sn` varchar(40) NOT NULL COMMENT 'serial number',
  `num` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '商品数',
  `month` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '租期(整月)',
  `grape` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '实付葡萄数(租金*租期)',
  `rentid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '最后一次租赁/续租的rentid',
  `orderid` bigint(20) NOT NULL DEFAULT '0' COMMENT '最后一次租赁/续租的orderid',
  `startime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '开始时间',
  `endtime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '到期时间',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '添加时间',
  `modtime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `packageid` (`packageid`),
  KEY `uid_type` (`uid`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户租借的package';



# Dump of table users_donate
# ------------------------------------------------------------

CREATE TABLE `users_donate` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户',
  `contact` text NOT NULL COMMENT '联系地址',
  `status` int(11) NOT NULL DEFAULT '100' COMMENT '默认待确认',
  `express_id` int(11) NOT NULL DEFAULT '0' COMMENT '快递 ID',
  `remark` char(200) NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户捐赠信息';




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
