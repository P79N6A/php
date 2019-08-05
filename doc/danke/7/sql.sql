CREATE TABLE `entrust_apply` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `date` char(8) NOT NULL DEFAULT '',
  `mobile` bigint(20) NOT NULL DEFAULT '0' COMMENT '电话',
  `province` varchar(100) NOT NULL DEFAULT '' COMMENT '省份',
  `city` varchar(100) NOT NULL DEFAULT '' COMMENT '城市',
  `district` varchar(100) NOT NULL DEFAULT '' COMMENT '市县',
  `block` varchar(100) NOT NULL DEFAULT '' COMMENT '商圈',
  `xiaoqu` varchar(255) NOT NULL DEFAULT '' COMMENT '小区',
  `source` varchar(100) NOT NULL DEFAULT '' COMMENT '来源',
  `addtime` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mobile_date` (`date`,`mobile`),
  KEY `addtime` (`addtime`),
  KEY `source` (`source`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='一键委托';


alter table `Mapiactivity`.`trainee_user` add `rent_month` varchar(50) COMMENT '租期' after `audit_result`;