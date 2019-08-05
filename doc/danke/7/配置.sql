CREATE TABLE `danz_decoration_config_bills` (

  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  `suite_id` int(10) unsigned NOT NULL COMMENT '公寓ID',

  `decoration_id` int(10) unsigned NOT NULL COMMENT '量房ID',

  `status` varchar(10) DEFAULT NULL COMMENT '状态',

  `type` varchar(10) DEFAULT '配置' COMMENT '类型',

  `areas` varchar(80) DEFAULT NULL COMMENT '区域',

  `founder_by` int(10) unsigned DEFAULT NULL COMMENT '修改人',

  `checked_id` int(10) unsigned DEFAULT NULL COMMENT '审核ID',

  `checked_at` datetime DEFAULT NULL COMMENT '审核时间',

  `deco_cost` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '硬装成本',

  `prepare_cost` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '配置成本',

  `total_cost` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '总成本',

  `info` text,

  `check_info` text COMMENT '描述',

  `special_product` text COMMENT '订制品说明',

  `reject_info` text,

  `assigned_tag` varchar(50) NOT NULL DEFAULT '' COMMENT '已分派类型',

  `unassigned_tag` varchar(50) NOT NULL DEFAULT '' COMMENT '未分派类型',

  `updated_at` datetime NOT NULL COMMENT '项目信息',

  `created_at` datetime NOT NULL,

  PRIMARY KEY (`id`),

  KEY `danz_decoration_config_bills_suite_id_index` (`suite_id`),

  KEY `danz_decoration_config_bills_decoration_id_index` (`decoration_id`),

  KEY `danz_decoration_config_bills_status_index` (`status`),

  KEY `danz_decoration_config_bills_type_index` (`type`)

) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COMMENT='配置单表';
CREATE TABLE `danz_decoration_config_areas` (

  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  `suite_id` int(10) unsigned NOT NULL COMMENT '公寓ID',

  `decoration_id` int(10) unsigned NOT NULL COMMENT '量房ID',

  `config_id` int(10) unsigned NOT NULL COMMENT '配置单ID',

  `area` varchar(30) DEFAULT NULL COMMENT '区域',

  `space_id` int(10) unsigned DEFAULT NULL COMMENT '区域ID',

  `plan_id` int(10) unsigned DEFAULT NULL COMMENT '套餐ID',

  `deco_cost` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '硬装成本',

  `prepare_cost` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '配置成本',

  `total_cost` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '区域总成本',

  `info` text,

  `updated_at` datetime NOT NULL COMMENT '描述',

  `created_at` datetime NOT NULL COMMENT '创建时间',

  PRIMARY KEY (`id`),

  KEY `danz_decoration_config_areas_suite_id_index` (`suite_id`),

  KEY `danz_decoration_config_areas_decoration_id_index` (`decoration_id`),

  KEY `danz_decoration_config_areas_config_id_index` (`config_id`),

  KEY `danz_decoration_config_areas_plan_id_index` (`plan_id`)

) ENGINE=InnoDB AUTO_INCREMENT=564 DEFAULT CHARSET=utf8mb4 COMMENT='配置单区域表';
CREATE TABLE `danz_decoration_config_area_details` (

  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,

  `suite_id` int(10) unsigned NOT NULL COMMENT '公寓ID',

  `decoration_id` int(10) unsigned NOT NULL COMMENT '量房ID',

  `config_id` int(10) unsigned NOT NULL COMMENT '配置单ID',

  `subject` varchar(10) NOT NULL DEFAULT '' COMMENT '项目',

  `area_id` int(10) unsigned NOT NULL COMMENT '区域ID',

  `plan_id` int(10) unsigned DEFAULT NULL COMMENT '套餐ID',

  `package_id` int(10) unsigned DEFAULT NULL COMMENT '包ID',

  `products_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '产品ID',

  `plan_type` varchar(10) NOT NULL DEFAULT '' COMMENT '套餐类型',

  `goods_id` int(10) unsigned DEFAULT NULL COMMENT '物料ID',

  `cost_type` varchar(10) DEFAULT NULL COMMENT '类型',

  `package_num` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '包数量',

  `products_num` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '产品数量',

  `products_price` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '产品价格',

  `goods_num` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '物料数量',

  `goods_price` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '物料价格',

  `package_price` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '包价格',

  `total` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '合计',

  `products_rate` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '产品比例',

  `goods_rate` decimal(8,2) NOT NULL DEFAULT '0.00' COMMENT '物料比例',

  `is_necessary` varchar(10) DEFAULT NULL COMMENT '是否必选',

  `is_products_select` varchar(10) NOT NULL DEFAULT '否' COMMENT '是否选择',

  `is_goods_select` varchar(10) NOT NULL DEFAULT '否' COMMENT '是否物料选择',

  `is_pack_select` varchar(10) NOT NULL DEFAULT '否' COMMENT '是否包选择',

  `order` tinyint(4) NOT NULL DEFAULT '1' COMMENT '排序',

  `pck_products_necessary` varchar(10) NOT NULL DEFAULT '' COMMENT '是否必要',

  `pck_goods_necessary` varchar(10) DEFAULT NULL COMMENT '包物料是否必选',

  `task_id` int(10) unsigned NOT NULL COMMENT '任务ID',

  `task_detail_id` int(10) unsigned DEFAULT NULL COMMENT '任务详情ID',

  `updated_at` datetime NOT NULL,

  `created_at` datetime NOT NULL,

  PRIMARY KEY (`id`),

  KEY `danz_decoration_config_area_details_suite_id_index` (`suite_id`),

  KEY `danz_decoration_config_area_details_decoration_id_index` (`decoration_id`),

  KEY `danz_decoration_config_area_details_config_id_index` (`config_id`),

  KEY `danz_decoration_config_area_details_area_id_index` (`area_id`),

  KEY `danz_decoration_config_area_details_order_index` (`order`)

) ENGINE=InnoDB AUTO_INCREMENT=45066 DEFAULT CHARSET=utf8mb4 COMMENT='配置单区域明细表';