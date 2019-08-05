ALTER TABLE `partner` ADD INDEX `uid`(`uid`) USING BTREE;
ALTER TABLE `partner` ADD INDEX `act_id`(`act_id`) USING BTREE;
ALTER TABLE `partner_bank` ADD INDEX `state`(`state`) USING BTREE;
ALTER TABLE `partner_relation` ADD INDEX `uid`(`uid`) USING BTREE;
ALTER TABLE `partner_relation` ADD INDEX `relateid`(`relateid`) USING BTREE;
ALTER TABLE `partner_relation` ADD INDEX `state`(`state`) USING BTREE;
ALTER TABLE `partner_relation` ADD INDEX `act_id`(`act_id`) USING BTREE;
ALTER TABLE `partner_withdraw` ADD INDEX `uid`(`uid`) USING BTREE;
ALTER TABLE `partner_withdraw` ADD INDEX `act_id`(`act_id`) USING BTREE;

ALTER TABLE `partner` ADD COLUMN `qr_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '二维码路径' AFTER `addtime`;
ALTER TABLE `partner_relation` ADD COLUMN `finance_syn_time` datetime(0) NULL DEFAULT NULL COMMENT '同步给财务中台的时间' AFTER `finance_no`;

ALTER TABLE `partner_bank` ADD COLUMN `branch_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '银行编号' AFTER `uid`;

ALTER TABLE `partner_bank` ADD COLUMN `branch_id` int(11) NULL DEFAULT NULL AFTER `branch_code`;


ALTER TABLE `partner_relation` MODIFY COLUMN `state` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '状态：0.是合伙人，但是未签约，1正常不足30天，2违规禁用，3.已过30天可提现，4.提现中，5已打款完成，6打款失败退回，可再次提现，7审核失败（不足30天）' AFTER `contract_time`;

ALTER TABLE `partner_relation` MODIFY COLUMN `result` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '原因' AFTER `withdraw_time`;
