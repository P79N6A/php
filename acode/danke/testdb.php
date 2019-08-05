<?php
//write 100 tables
for ($i = 0; $i < 20; $i++) {
	$index = $i;
	$table =  "CREATE TABLE `user_foot_{$index}` (`id` int(11) unsigned NOT NULL AUTO_INCREMENT,`user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',`room_id` int(11) NOT NULL DEFAULT '0' COMMENT '房间id',`public_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '可公开id',`rent_type` varchar(16) COLLATE utf8mb4_bin NOT NULL DEFAULT '' COMMENT '租房形式',`price` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',`created_at` datetime NOT NULL COMMENT '创建时间',`updated_at` datetime NOT NULL COMMENT '修改时间',PRIMARY KEY (`id`),KEY `userid&rent_type&room_id` (`user_id`,`rent_type`,`room_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;";
//echo $table;
	$table =  "CREATE TABLE `device_foot_{$index}` (`id` int(11) unsigned NOT NULL AUTO_INCREMENT,`device_id` varchar(100) COLLATE utf8mb4_bin NOT NULL DEFAULT '' COMMENT '设备id',`room_id` int(11) NOT NULL DEFAULT '0' COMMENT '房间id',`public_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '可公开id',`rent_type` varchar(16) COLLATE utf8mb4_bin NOT NULL DEFAULT '' COMMENT '租房形式',`price` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',`plateform` varchar(10) COLLATE utf8mb4_bin NOT NULL DEFAULT '' COMMENT '平台',`version` varchar(20) COLLATE utf8mb4_bin NOT NULL DEFAULT '' COMMENT '版本',`created_at` datetime NOT NULL COMMENT '创建时间',`updated_at` datetime NOT NULL COMMENT '修改时间',PRIMARY KEY (`id`),KEY `device_id&rent_type&room_id` (`device_id`,`rent_type`,`room_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;";
	echo $table;

	$table =  "CREATE TABLE `user_collect_{$index}` (`id` int(11) unsigned NOT NULL AUTO_INCREMENT,`user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',`room_id` int(11) NOT NULL DEFAULT '0' COMMENT '房间id',`public_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '可公开id',`rent_type` varchar(16) COLLATE utf8mb4_bin NOT NULL DEFAULT '' COMMENT '租房形式',`price` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',`created_at` datetime NOT NULL COMMENT '创建时间',`updated_at` datetime NOT NULL COMMENT '修改时间',PRIMARY KEY (`id`),KEY `userid&rent_type&room_id` (`user_id`,`rent_type`,`room_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;";

	//echo $table;
	$table =  "CREATE TABLE `room_collect_{$index}` (`id` int(11) unsigned NOT NULL AUTO_INCREMENT,`user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',`room_id` int(11) NOT NULL DEFAULT '0' COMMENT '房间id',`public_id` bigint(20) NOT NULL DEFAULT '0' COMMENT '可公开id',`rent_type` varchar(16) COLLATE utf8mb4_bin NOT NULL DEFAULT '' COMMENT '租房形式',`price` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',`created_at` datetime NOT NULL COMMENT '创建时间',`updated_at` datetime NOT NULL COMMENT '修改时间',PRIMARY KEY (`id`),KEY `room_id&rent_type&user_id` (`room_id`,`rent_type`,`user_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;";

	//echo $table;

	$table = "CREATE TABLE `user_search_{$index}` (`id` int(11) unsigned NOT NULL AUTO_INCREMENT,`user_id` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',`text_word` varchar(255) COLLATE utf8mb4_bin NOT NULL DEFAULT '' COMMENT '用户搜索词',`is_del` tinyint(4) NOT NULL DEFAULT '1' COMMENT '是否删除',`created_at` datetime NOT NULL COMMENT '创建时间',`updated_at` datetime NOT NULL COMMENT '修改时间',PRIMARY KEY (`id`),KEY `user_id` (`user_id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;";

//echo $table;
}