CREATE TABLE `sale_bigdate_count` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `city_id` int(11) NOT NULL DEFAULT '0' COMMENT '城市id',
  `city_name` varchar(20) NOT NULL DEFAULT '' COMMENT '城市',
  `sale_id` int(11) NOT NULL DEFAULT '0' COMMENT '销售id',
  `sale_name` varchar(100) NOT NULL DEFAULT '' COMMENT '销售名称',
  `week_look` int(11) DEFAULT '0' COMMENT '周带看量',
  `week_deal` int(11) DEFAULT '0' COMMENT '周成交量',
  `block_id` int(11) NOT NULL DEFAULT '0' COMMENT '商圈id',
  `block_name` varchar(255) DEFAULT NULL COMMENT '商圈名称',
  `addtime` datetime DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `index` (`city_id`,`block_id`,`sale_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='销售商圈统计';

CREATE TABLE `sale_passenger` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT '用户id',
  `mobile` bigint(20) DEFAULT '0' COMMENT '手机号',
  `sale_id` int(11) DEFAULT '0' COMMENT '销售id',
  `sale_name` varchar(255) DEFAULT NULL COMMENT '销售名称',
  `status` enum('分配中','已分配','已联系','已取消','已带看','无效','已结束') DEFAULT '分配中' COMMENT '状态',
  `room_id` int(11) DEFAULT NULL,
  `rent_type` tinyint(1) DEFAULT '0' COMMENT '租房类型1整租，2合租,3月租',
  `addtime` datetime DEFAULT NULL COMMENT '添加时间',
  `modtime` datetime DEFAULT NULL COMMENT '修改时间',
  `allowed_time` varchar(25) DEFAULT NULL COMMENT '规定时间（单位：分钟）',
  `assignment_time` datetime DEFAULT NULL COMMENT '分配时间',
  `sub_status` enum('规定时间内','超时','严重超时','已到达附近') DEFAULT '规定时间内' COMMENT '已联系的子状态   “超时”，“严重超时”，“已到达附近”',
  `passenger_id` int(11) DEFAULT NULL COMMENT '产生线索的id ，分配成功产生passenger_id',
  PRIMARY KEY (`id`),
  KEY `sale_index` (`sale_id`,`room_id`),
  KEY `user_index` (`uid`,`room_id`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='极速带看表';

CREATE TABLE `sale_punish` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sale_id` int(11) NOT NULL DEFAULT '0' COMMENT '销售id',
  `room_id` int(11) NOT NULL DEFAULT '0' COMMENT '房源id',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '看房用户',
  `addtime` datetime NOT NULL COMMENT '添加时间',
  `modtime` datetime NOT NULL COMMENT '修改时间',
  `endtime` int(11) DEFAULT '0' COMMENT '过期时间戳',
  `deleted` enum('N','Y') DEFAULT 'N' COMMENT '删除状态',
  PRIMARY KEY (`id`),
  KEY `sale_id` (`sale_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='销售惩罚表';

CREATE TABLE `sale_user_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT '0' COMMENT '用户id',
  `room_id` int(11) DEFAULT '0' COMMENT '房源id',
  `lng` double DEFAULT '0' COMMENT '用户经度',
  `lat` double DEFAULT '0' COMMENT '用户纬度',
  `x_lng` double DEFAULT '0' COMMENT '小区经度',
  `x_lat` double DEFAULT '0' COMMENT '小区纬度',
  `sales` text COMMENT '附近销售',
  `addtime` datetime DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `index` (`uid`,`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='极速带看日志表';

CREATE TABLE `room_views_0` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `room_id` int(11) NOT NULL DEFAULT '0' COMMENT '房间id',
  `public_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '可公开id',
  `rent_type` varchar(16) NOT NULL DEFAULT '' COMMENT '租房形式',
  `price` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `created_at` datetime NOT NULL COMMENT '创建时间',
  `updated_at` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index` (`user_id`,`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户浏览房源表';

CREATE TABLE `room_views_1` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `room_id` int(11) NOT NULL DEFAULT '0' COMMENT '房间id',
  `public_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '可公开id',
  `rent_type` varchar(16) NOT NULL DEFAULT '' COMMENT '租房形式',
  `price` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `created_at` datetime NOT NULL COMMENT '创建时间',
  `updated_at` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index` (`user_id`,`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户浏览房源表';

CREATE TABLE `room_views_2` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `room_id` int(11) NOT NULL DEFAULT '0' COMMENT '房间id',
  `public_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '可公开id',
  `rent_type` varchar(16) NOT NULL DEFAULT '' COMMENT '租房形式',
  `price` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `created_at` datetime NOT NULL COMMENT '创建时间',
  `updated_at` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index` (`user_id`,`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户浏览房源表';

CREATE TABLE `room_views_3` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `room_id` int(11) NOT NULL DEFAULT '0' COMMENT '房间id',
  `public_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '可公开id',
  `rent_type` varchar(16) NOT NULL DEFAULT '' COMMENT '租房形式',
  `price` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `created_at` datetime NOT NULL COMMENT '创建时间',
  `updated_at` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index` (`user_id`,`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户浏览房源表';

CREATE TABLE `room_views_4` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `room_id` int(11) NOT NULL DEFAULT '0' COMMENT '房间id',
  `public_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '可公开id',
  `rent_type` varchar(16) NOT NULL DEFAULT '' COMMENT '租房形式',
  `price` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `created_at` datetime NOT NULL COMMENT '创建时间',
  `updated_at` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index` (`user_id`,`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户浏览房源表';

CREATE TABLE `room_views_5` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `room_id` int(11) NOT NULL DEFAULT '0' COMMENT '房间id',
  `public_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '可公开id',
  `rent_type` varchar(16) NOT NULL DEFAULT '' COMMENT '租房形式',
  `price` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `created_at` datetime NOT NULL COMMENT '创建时间',
  `updated_at` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index` (`user_id`,`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户浏览房源表';

CREATE TABLE `room_views_6` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `room_id` int(11) NOT NULL DEFAULT '0' COMMENT '房间id',
  `public_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '可公开id',
  `rent_type` varchar(16) NOT NULL DEFAULT '' COMMENT '租房形式',
  `price` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `created_at` datetime NOT NULL COMMENT '创建时间',
  `updated_at` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index` (`user_id`,`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户浏览房源表';

CREATE TABLE `room_views_7` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `room_id` int(11) NOT NULL DEFAULT '0' COMMENT '房间id',
  `public_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '可公开id',
  `rent_type` varchar(16) NOT NULL DEFAULT '' COMMENT '租房形式',
  `price` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `created_at` datetime NOT NULL COMMENT '创建时间',
  `updated_at` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index` (`user_id`,`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户浏览房源表';

CREATE TABLE `room_views_8` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `room_id` int(11) NOT NULL DEFAULT '0' COMMENT '房间id',
  `public_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '可公开id',
  `rent_type` varchar(16) NOT NULL DEFAULT '' COMMENT '租房形式',
  `price` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `created_at` datetime NOT NULL COMMENT '创建时间',
  `updated_at` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index` (`user_id`,`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户浏览房源表';

CREATE TABLE `room_views_9` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `room_id` int(11) NOT NULL DEFAULT '0' COMMENT '房间id',
  `public_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '可公开id',
  `rent_type` varchar(16) NOT NULL DEFAULT '' COMMENT '租房形式',
  `price` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `created_at` datetime NOT NULL COMMENT '创建时间',
  `updated_at` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index` (`user_id`,`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户浏览房源表';

CREATE TABLE `price_reduction_push` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `activity_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动id',
  `room_id` int(11) NOT NULL DEFAULT '0' COMMENT '房源id',
  `rent_type` varchar(20) NOT NULL DEFAULT '' COMMENT '类型',
  `send_date` char(8) NOT NULL DEFAULT '' COMMENT '发送日期',
  `collect_user` longtext,
  `view_user` longtext,
  `status` enum('未发送','已发送') NOT NULL DEFAULT '未发送' COMMENT '发送状态',
  `addtime` datetime NOT NULL COMMENT '添加时间',
  `modtime` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `date` (`send_date`),
  KEY `actid` (`activity_id`),
  KEY `room_id` (`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='降价push日志';
alter table `Mapi`.`price_reduction_push` add `collect_user` longtext,add `view_user` longtext after `send_date`;

alter table `Mapi`.`price_reduction_push` add `collect_user` longtext COMMENT '关注用户id' after `send_date` ,add `view_user` longtext  COMMENT '浏览用户id' after `collect_user`;

