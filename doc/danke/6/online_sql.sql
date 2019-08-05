/home/web/bin/crontab
0 0 11,13,16 * * ?
/usr/bin/php "$1" price_reduction_push
0 13 * *  *
0 0 13 * * ?

/home/web/bin/crontab
0 0 7 * * ?
/usr/bin/php "$1" entrust_email

SELECT *
FROM Laputa.supple_config_items
WHERE supple_config_id IN
    (SELECT prepare_id
     FROM Laputa.purchasing_tasks
     WHERE TYPE = '新风'
       AND suite_id = 121413)

SELECT *
FROM Laputa.humans
WHERE id IN
    (SELECT customer_id
     FROM Laputa.contract_with_customers
     WHERE status = '已签约'
       AND room_id IN(48083,347853,360243))

SELECT *
FROM Laputa.supple_config_items
WHERE supple_config_id IN
    (SELECT prepare_id
     FROM Laputa.purchasing_tasks
     WHERE TYPE = '新风'
       AND suite_id IN
         (SELECT suite_id
          FROM Laputa.rooms
          WHERE status = '已出租'
            AND suite_id IN
              (SELECT suite_id
               FROM Laputa.purchasing_tasks
               WHERE TYPE = '新风')) )


SELECT prepare_id
FROM Laputa.purchasing_tasks
WHERE TYPE = '新风'
  AND suite_id IN
    (SELECT suite_id
     FROM Laputa.rooms
     WHERE status = '已出租'
       AND suite_id IN
         (SELECT suite_id
          FROM Laputa.purchasing_tasks
          WHERE TYPE = '新风' and suite_id =  121413 ))


SELECT mobile
FROM Laputa.humans
WHERE id IN
    (SELECT customer_id
     FROM Laputa.contract_with_customers
     WHERE status = '已签约'
       AND room_id IN
         (SELECT id
          FROM Laputa.rooms
          WHERE suite_id = 122149))

/u-xian/lock/temp-door-lock-pass

SELECT mobile
FROM Laputa.humans
WHERE id IN
    (SELECT customer_id
     FROM Laputa.contract_with_customers
     WHERE status = '已签约'
       AND room_id IN
         (SELECT id
          FROM Laputa.rooms
          WHERE status = '已出租'
            AND suite_id IN
              (SELECT suite_id
               FROM Laputa.purchasing_tasks
               WHERE TYPE = '新风')))

select * from Laputa.humans where id =  911558 ;
select * from Laputa.humans where id in(SELECT customer_id FROM Laputa.contract_with_customers WHERE status = '已签约' and room_id in(347855,348355,348356,348357,349302,349308,349548,349936,349938,349930))
SELECT * FROM Laputa.rooms WHERE id =  349548 ;
SELECT customer_id FROM Laputa.contract_with_customers WHERE status = '已签约' and room_id in(347855,348355,348356,348357,349302,349308,349548,349936,349938,349930);
SELECT * FROM Laputa.contract_with_customers WHERE status = '已签约' and customer_id =  843643 ;

SELECT *
FROM Laputa.supple_config_items
WHERE supple_config_id IN (SELECT prepare_id
          FROM Laputa.purchasing_tasks
          WHERE TYPE = '新风' and suite_id =  121413)

select mobile from Laputa.humans where id in(SELECT customer_id FROM Laputa.contract_with_customers WHERE status = '已签约' and room_id in (SELECT suite_id,city_name
FROM Laputa.rooms
WHERE status = '已出租'
  AND suite_id IN
    (SELECT suite_id
     FROM Laputa.purchasing_tasks
     WHERE TYPE = '新风')))

SELECT customer_id FROM Laputa.contract_with_customers WHERE status = '已签约' and room_id in (SELECT suite_id,city_name
FROM Laputa.rooms
WHERE status = '已出租'
  AND suite_id IN
    (SELECT suite_id
     FROM Laputa.purchasing_tasks
     WHERE TYPE = '新风'));

SELECT suite_id,city_name
FROM Laputa.rooms
WHERE status = '已出租'
  AND suite_id IN
    (SELECT suite_id
     FROM Laputa.purchasing_tasks
     WHERE TYPE = '新风');

/usr/bin/php "$1" view_push
CREATE TABLE `price_reduction_push` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `activity_id` int(11) NOT NULL DEFAULT '0' COMMENT '活动id',
  `room_id` int(11) NOT NULL DEFAULT '0' COMMENT '房源id',
  `rent_type` varchar(20) NOT NULL DEFAULT '' COMMENT '类型',
  `send_date` char(8) NOT NULL DEFAULT '' COMMENT '发送日期',
  `collect_user` longtext COMMENT '关注用户id',
  `view_user` longtext COMMENT '浏览用户id',
  `status` enum('未发送','已发送') NOT NULL DEFAULT '未发送' COMMENT '发送状态',
  `addtime` datetime NOT NULL COMMENT '添加时间',
  `modtime` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `index_date` (`send_date`),
  KEY `index_actid` (`activity_id`),
  KEY `index_room_id` (`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='降价push日志表';

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
  KEY `index_sale_room` (`sale_id`,`room_id`),
  KEY `user_index` (`uid`,`room_id`,`status`),
  KEY `index_addtime` (`addtime`)
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
  KEY `index_sale_id` (`sale_id`),
  KEY `index_endtime` (`endtime`)
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
  KEY `index` (`uid`,`room_id`),
  KEY `index_addtime` (`addtime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='极速带看日志';

CREATE TABLE `room_views_0` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `room_id` int(11) NOT NULL DEFAULT '0' COMMENT '房间id',
  `public_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '可公开id',
  `rent_type` varchar(200) NOT NULL DEFAULT '0' COMMENT '租房形式',
  `price` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `created_at` datetime NOT NULL COMMENT '创建时间',
  `updated_at` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index` (`user_id`,`room_id`),
  KEY `index_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户浏览历史日志表';

CREATE TABLE `room_views_1` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `room_id` int(11) NOT NULL DEFAULT '0' COMMENT '房间id',
  `public_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '可公开id',
  `rent_type` varchar(200) NOT NULL DEFAULT '0' COMMENT '租房形式',
  `price` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `created_at` datetime NOT NULL COMMENT '创建时间',
  `updated_at` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index` (`user_id`,`room_id`),
  KEY `index_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户浏览历史日志表';

CREATE TABLE `room_views_2` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `room_id` int(11) NOT NULL DEFAULT '0' COMMENT '房间id',
  `public_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '可公开id',
  `rent_type` varchar(200) NOT NULL DEFAULT '0' COMMENT '租房形式',
  `price` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `created_at` datetime NOT NULL COMMENT '创建时间',
  `updated_at` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index` (`user_id`,`room_id`),
  KEY `index_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户浏览历史日志表';

CREATE TABLE `room_views_3` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `room_id` int(11) NOT NULL DEFAULT '0' COMMENT '房间id',
  `public_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '可公开id',
  `rent_type` varchar(200) NOT NULL DEFAULT '0' COMMENT '租房形式',
  `price` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `created_at` datetime NOT NULL COMMENT '创建时间',
  `updated_at` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index` (`user_id`,`room_id`),
  KEY `index_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户浏览历史日志表';

CREATE TABLE `room_views_4` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `room_id` int(11) NOT NULL DEFAULT '0' COMMENT '房间id',
  `public_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '可公开id',
  `rent_type` varchar(200) NOT NULL DEFAULT '0' COMMENT '租房形式',
  `price` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `created_at` datetime NOT NULL COMMENT '创建时间',
  `updated_at` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index` (`user_id`,`room_id`),
  KEY `index_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户浏览历史日志表';

CREATE TABLE `room_views_5` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `room_id` int(11) NOT NULL DEFAULT '0' COMMENT '房间id',
  `public_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '可公开id',
  `rent_type` varchar(200) NOT NULL DEFAULT '0' COMMENT '租房形式',
  `price` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `created_at` datetime NOT NULL COMMENT '创建时间',
  `updated_at` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index` (`user_id`,`room_id`),
  KEY `index_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户浏览历史日志表';

CREATE TABLE `room_views_6` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `room_id` int(11) NOT NULL DEFAULT '0' COMMENT '房间id',
  `public_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '可公开id',
  `rent_type` varchar(200) NOT NULL DEFAULT '0' COMMENT '租房形式',
  `price` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `created_at` datetime NOT NULL COMMENT '创建时间',
  `updated_at` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index` (`user_id`,`room_id`),
  KEY `index_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户浏览历史日志表';

CREATE TABLE `room_views_7` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `room_id` int(11) NOT NULL DEFAULT '0' COMMENT '房间id',
  `public_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '可公开id',
  `rent_type` varchar(200) NOT NULL DEFAULT '0' COMMENT '租房形式',
  `price` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `created_at` datetime NOT NULL COMMENT '创建时间',
  `updated_at` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index` (`user_id`,`room_id`),
  KEY `index_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户浏览历史日志表';

CREATE TABLE `room_views_8` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `room_id` int(11) NOT NULL DEFAULT '0' COMMENT '房间id',
  `public_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '可公开id',
  `rent_type` varchar(200) NOT NULL DEFAULT '0' COMMENT '租房形式',
  `price` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `created_at` datetime NOT NULL COMMENT '创建时间',
  `updated_at` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index` (`user_id`,`room_id`),
  KEY `index_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户浏览历史日志表';

CREATE TABLE `room_views_9` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `room_id` int(11) NOT NULL DEFAULT '0' COMMENT '房间id',
  `public_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '可公开id',
  `rent_type` varchar(200) NOT NULL DEFAULT '0' COMMENT '租房形式',
  `price` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
  `created_at` datetime NOT NULL COMMENT '创建时间',
  `updated_at` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `index` (`user_id`,`room_id`),
  KEY `index_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户浏览历史日志表';


ALTER  TABLE  `room_collect_0`  ADD  INDEX index_created_at (`created_at`);
ALTER  TABLE  `room_collect_1`  ADD  INDEX index_created_at (`created_at`);
ALTER  TABLE  `room_collect_2`  ADD  INDEX index_created_at (`created_at`);
ALTER  TABLE  `room_collect_3`  ADD  INDEX index_created_at (`created_at`);
ALTER  TABLE  `room_collect_4`  ADD  INDEX index_created_at (`created_at`);
ALTER  TABLE  `room_collect_5`  ADD  INDEX index_created_at (`created_at`);
ALTER  TABLE  `room_collect_6`  ADD  INDEX index_created_at (`created_at`);
ALTER  TABLE  `room_collect_7`  ADD  INDEX index_created_at (`created_at`);
ALTER  TABLE  `room_collect_8`  ADD  INDEX index_created_at (`created_at`);
ALTER  TABLE  `room_collect_9`  ADD  INDEX index_created_at (`created_at`);

ALTER  TABLE  `room_collect_0`  modify column rent_type varchar(100) COMMENT '租房类型';
ALTER  TABLE  `room_collect_1`  modify column rent_type varchar(100) COMMENT '租房类型';
ALTER  TABLE  `room_collect_2`  modify column rent_type varchar(100) COMMENT '租房类型';
ALTER  TABLE  `room_collect_3`  modify column rent_type varchar(100) COMMENT '租房类型';
ALTER  TABLE  `room_collect_4`  modify column rent_type varchar(100) COMMENT '租房类型';
ALTER  TABLE  `room_collect_5`  modify column rent_type varchar(100) COMMENT '租房类型';
ALTER  TABLE  `room_collect_6`  modify column rent_type varchar(100) COMMENT '租房类型';
ALTER  TABLE  `room_collect_7`  modify column rent_type varchar(100) COMMENT '租房类型';
ALTER  TABLE  `room_collect_8`  modify column rent_type varchar(100) COMMENT '租房类型';
ALTER  TABLE  `room_collect_9`  modify column rent_type varchar(100) COMMENT '租房类型';

ALTER  TABLE  `room_views_0`  modify column rent_type varchar(100) COMMENT '租房类型';
ALTER  TABLE  `room_views_1`  modify column rent_type varchar(100) COMMENT '租房类型';
ALTER  TABLE  `room_views_2`  modify column rent_type varchar(100) COMMENT '租房类型';
ALTER  TABLE  `room_views_3`  modify column rent_type varchar(100) COMMENT '租房类型';
ALTER  TABLE  `room_views_4`  modify column rent_type varchar(100) COMMENT '租房类型';
ALTER  TABLE  `room_views_5`  modify column rent_type varchar(100) COMMENT '租房类型';
ALTER  TABLE  `room_views_6`  modify column rent_type varchar(100) COMMENT '租房类型';
ALTER  TABLE  `room_views_7`  modify column rent_type varchar(100) COMMENT '租房类型';
ALTER  TABLE  `room_views_8`  modify column rent_type varchar(100) COMMENT '租房类型';
ALTER  TABLE  `room_views_9`  modify column rent_type varchar(100) COMMENT '租房类型';

ALTER  TABLE  `user_collect_0`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_1`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_2`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_3`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_4`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_5`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_6`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_7`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_8`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_9`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_10`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_11`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_12`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_13`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_14`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_15`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_16`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_17`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_18`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_19`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_20`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_21`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_22`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_23`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_24`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_25`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_26`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_27`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_28`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_29`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_30`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_31`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_32`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_33`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_34`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_35`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_36`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_37`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_38`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_39`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_40`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_41`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_42`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_43`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_44`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_45`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_46`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_47`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_48`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_49`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_50`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_51`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_52`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_53`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_54`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_55`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_56`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_57`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_58`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_59`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_60`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_61`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_62`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_63`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_64`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_65`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_66`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_67`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_68`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_69`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_70`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_71`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_72`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_73`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_74`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_75`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_76`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_77`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_78`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_79`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_80`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_81`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_82`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_83`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_84`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_85`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_86`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_87`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_88`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_89`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_90`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_91`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_92`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_93`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_94`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_95`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_96`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_97`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_98`  modify column rent_type varchar(100);
ALTER  TABLE  `user_collect_99`  modify column rent_type varchar(100);
