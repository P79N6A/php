`id`  int(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
`address`  varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
`status`  enum('交割中','待装修','硬装中','配置中','现房','已下架') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
`series`  varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL ,
`xiaoqu_id`  int(10) UNSIGNED NULL DEFAULT NULL ,
`type`  varchar(5) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL ,
`sign_type`  varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '蛋壳' ,
`source`  varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '蛋壳' ,
`suite_property`  varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '房屋产权属性' ,
`is_verify`  enum('是','否') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '是' ,
`is_month`  enum('是','否') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '否' ,
`city_name`  varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL ,
`floor`  smallint(5) UNSIGNED NULL DEFAULT NULL ,
`total_floor`  smallint(5) UNSIGNED NULL DEFAULT NULL ,
`built_years`  smallint(5) UNSIGNED NULL DEFAULT NULL ,
`usable_area`  int(10) UNSIGNED NULL DEFAULT NULL COMMENT '套内使用面积' ,
`area`  smallint(5) UNSIGNED NULL DEFAULT NULL ,
`bedroom_num`  tinyint(3) UNSIGNED NOT NULL ,
`toilet_num`  tinyint(3) UNSIGNED NOT NULL ,
`public_space_num`  tinyint(3) UNSIGNED NULL DEFAULT NULL ,
`kitchen_num`  tinyint(3) UNSIGNED NULL DEFAULT NULL ,
`has_lift`  enum('有','无') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL ,
`is_gas_opened`  enum('是','否') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL ,
`heating`  varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL ,
`hot_water_type`  varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL ,
`pub_space_areas`  varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL ,
`landlord_id`  int(10) UNSIGNED NULL DEFAULT NULL ,
`dealer_id`  int(10) UNSIGNED NULL DEFAULT NULL ,
`housekeeper_id`  int(10) UNSIGNED NULL DEFAULT NULL ,
`zhuangxiu_designer_id`  int(10) NULL DEFAULT NULL ,
`clean_day`  smallint(5) UNSIGNED NOT NULL ,
`rent_begin_date`  date NULL DEFAULT NULL ,
`rent_end_date`  date NULL DEFAULT NULL ,
`zhuangxiu_begin_date`  date NULL DEFAULT NULL ,
`zhuangxiu_end_date`  date NULL DEFAULT NULL ,
`equip_end_date`  date NULL DEFAULT NULL ,
`network_end_date`  date NULL DEFAULT NULL ,
`clean_end_date`  date NULL DEFAULT NULL ,
`acceptance_date`  date NULL DEFAULT NULL ,
`ready_for_rent_date`  date NULL DEFAULT NULL ,
`delay_equip_end_date`  date NULL DEFAULT NULL ,
`delay_clean_end_date`  date NULL DEFAULT NULL ,
`delay_acceptance_date`  date NULL DEFAULT NULL ,
`delay_ready_for_rent_date`  date NULL DEFAULT NULL ,
`intro`  text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL ,
`images`  text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL ,
`documents`  text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL ,
`bandwidth`  int(10) UNSIGNED NULL DEFAULT NULL ,
`created_at`  datetime NOT NULL ,
`updated_at`  datetime NOT NULL ,
`sale_tag`  enum('研发房','工作站','生产房') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '生产房' ,
`private_bathroom_num`  int(11) NOT NULL COMMENT '独立卫生间数' ,
`shower_room_num`  int(11) NOT NULL COMMENT '淋浴房数' ,
`guest_room_num`  int(11) NOT NULL COMMENT '客厅数' ,
`isz_house_status`  varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '房源状态：INVALID：无效、VALID：有效、DEFERMENT：暂缓、OTHER_RENT：他租、ME_RENT：我租、ME_RENT_GENERAL：我租(普)' ,
`isz_house_id`  varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '爱上租来源ID' ,