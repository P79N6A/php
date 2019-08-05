CREATE TABLE `partner` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0',
  `channel_id` int(11) DEFAULT NULL,
  `act_id` int(11) NOT NULL DEFAULT '0',
  `addtime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `act_id` (`act_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `activity_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '蛋壳用户id',
  `openid` varchar(50) NOT NULL DEFAULT '' COMMENT '微信token',
  `nickname` varchar(100) NOT NULL DEFAULT '' COMMENT '昵称',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  `modtime` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `index` (`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `partner_bank` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bank_no` varchar(255) NOT NULL COMMENT '银行卡号',
  `bank_name` varchar(255) NOT NULL COMMENT '银行名称',
  `city` varchar(255) NOT NULL COMMENT '开户城市',
  `branch` varchar(255) NOT NULL COMMENT '支行名',
  `name` varchar(255) NOT NULL COMMENT '开户人姓名',
  `id_card_no` varchar(255) NOT NULL COMMENT '身份证号',
  `mobile` varchar(255) NOT NULL COMMENT '开户手机号',
  `state` smallint(6) NOT NULL DEFAULT '1' COMMENT '1绑定 2解绑',
  `addtime` datetime DEFAULT NULL COMMENT '绑定时间',
  `unbind_time` datetime DEFAULT NULL COMMENT '解绑时间',
  `uid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `state` (`state`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='合伙人银行卡';

CREATE TABLE `partner_channel` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '渠道名称',
  `url` varchar(255) NOT NULL,
  `modtime` datetime DEFAULT NULL,
  `addtime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='合伙人渠道表';

CREATE TABLE `partner_relation` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) unsigned NOT NULL COMMENT '上级id',
  `relateid` bigint(20) unsigned NOT NULL COMMENT '下级id',
  `contract_no` varchar(20) DEFAULT '' COMMENT '合同号',
  `contract_time` datetime DEFAULT NULL COMMENT '签约时间',
  `state` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态：0.是合伙人，但是未签约，1正常不足30天，2违规禁用，3.已过30天可提现，4.提现中，5已打款完成，6打款失败退回，可再次提现，7审核失败（不足30天）',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  `modtime` datetime NOT NULL COMMENT '修改时间',
  `extends` text COMMENT 'json,每次合同修改，都会再extends存老的合同好和时间',
  `act_id` int(11) NOT NULL DEFAULT '0',
  `charge` decimal(10,0) DEFAULT '0' COMMENT '佣金',
  `aduit_time` datetime DEFAULT NULL COMMENT '审核通过时间',
  `contract_pay_time` datetime DEFAULT NULL COMMENT '第一笔账单支付成功时间',
  `pay_branch` varchar(255) DEFAULT NULL COMMENT '所属子公司',
  `pay_time` datetime DEFAULT NULL COMMENT '打款时间',
  `pay_bank_id` int(11) DEFAULT NULL COMMENT '绑定银行id',
  `withdraw_id` int(11) DEFAULT NULL COMMENT '提现id',
  `withdraw_time` datetime DEFAULT NULL COMMENT '体现时间',
  `result` varchar(255) DEFAULT '' COMMENT '原因',
  `suggestion` varchar(255) DEFAULT NULL COMMENT '建议',
  `finance_no` varchar(255) DEFAULT NULL COMMENT 'mis返回的单号',
  `finance_syn_time` datetime DEFAULT NULL COMMENT '同步给财务中台的时间',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `relateid` (`relateid`),
  KEY `state` (`state`),
  KEY `act_id` (`act_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='合伙人关系表';

CREATE TABLE `partner_withdraw` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '提现uid',
  `amount` int(11) NOT NULL DEFAULT '0' COMMENT '提现金额',
  `detail` text COMMENT 'json relation ids',
  `create_time` datetime DEFAULT NULL COMMENT '提现时间',
  `act_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `act_id` (`act_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='合伙人提现申请表';

CREATE TABLE `starling_apply` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '蛋壳用户id',
  `nickname` varchar(255) NOT NULL DEFAULT '' COMMENT '用户昵称',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
  `activityid` int(11) NOT NULL DEFAULT '0' COMMENT '活动类型',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  `modtime` datetime NOT NULL COMMENT '修改时间',
  `total` int(11) NOT NULL DEFAULT '0' COMMENT '目前下级数量',
  PRIMARY KEY (`id`),
  KEY `index` (`activityid`,`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='万元礼包申请者表';

CREATE TABLE `starling_config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `reason` varchar(255) DEFAULT NULL COMMENT '未通过原因',
  `addtime` datetime DEFAULT NULL COMMENT '创建时间',
  `modtime` datetime DEFAULT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `starling_relate` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL DEFAULT '0' COMMENT '报名id',
  `act_userid` int(11) NOT NULL DEFAULT '0' COMMENT '成员id',
  `username` varchar(255) NOT NULL DEFAULT '' COMMENT '成员名称',
  `openid` varchar(100) NOT NULL DEFAULT '' COMMENT '第三方(微信id)',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  `modtime` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `index` (`group_id`,`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='万元礼包助力表';

CREATE TABLE `starling_task` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户id',
  `number` varchar(50) NOT NULL DEFAULT '' COMMENT '学籍查询码',
  `taskid` bigint(20) NOT NULL COMMENT '交易号',
  `query` varchar(50) NOT NULL DEFAULT '' COMMENT '查询码',
  `addtime` datetime NOT NULL COMMENT '添加时间',
  `modtime` datetime NOT NULL COMMENT '修改时间',
  `extends` text COMMENT '备注',
  `status` enum('NOT','Y','EXIST','OTHER','PASS') NOT NULL DEFAULT 'Y' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `index` (`uid`,`number`,`query`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='爬虫任务';

CREATE TABLE `starling_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '蛋壳用户id',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `exam_number` varchar(100) DEFAULT '' COMMENT '学籍码',
  `idcard` varchar(20) NOT NULL DEFAULT '' COMMENT '身份证',
  `mobile` varchar(15) NOT NULL DEFAULT '' COMMENT '手机号',
  `gender` enum('男','女') NOT NULL DEFAULT '男' COMMENT '性别',
  `school` varchar(100) NOT NULL DEFAULT '' COMMENT '学校名称',
  `department` varchar(100) NOT NULL DEFAULT '' COMMENT '院系',
  `major` varchar(100) NOT NULL DEFAULT '' COMMENT '专业',
  `graduation_date` varchar(20) NOT NULL DEFAULT '' COMMENT '毕业日期',
  `auto` enum('手填','抓取') NOT NULL DEFAULT '手填' COMMENT '是否是海外',
  `channel` varchar(20) NOT NULL DEFAULT '官网线上' COMMENT '渠道',
  `attachment` varchar(255) NOT NULL DEFAULT '' COMMENT '认证图片',
  `status` enum('已审核','未审核') NOT NULL DEFAULT '未审核' COMMENT '状态',
  `addtime` datetime NOT NULL COMMENT '创建时间',
  `modtime` datetime NOT NULL COMMENT '修改时间',
  `audit_result` enum('通过','未通过','待审核') NOT NULL DEFAULT '待审核' COMMENT '审核结果,1通过，2审核失败',
  `passtime` datetime DEFAULT NULL COMMENT '认证通过时间',
  `reason` varchar(255) NOT NULL DEFAULT '' COMMENT '未通过原因',
  `sign_status` enum('已签约','未签约') NOT NULL DEFAULT '未签约' COMMENT '签约状态',
  `sign_date` varchar(20) NOT NULL DEFAULT '' COMMENT '签约时间',
  `contract_no` varchar(100) NOT NULL DEFAULT '' COMMENT '合同号码',
  `contract_city` varchar(20) NOT NULL DEFAULT '' COMMENT '合同城市',
  `contract_status` enum('未生效','有效','待签约','签约待确认','已签约','期满','甲方违约终止','乙方违约终止','双方协商终止','未签约') NOT NULL DEFAULT '未签约' COMMENT '合同状态',
  `send_coupon` enum('已发送','未发送','发送失败') NOT NULL DEFAULT '未发送' COMMENT '是否发送优惠券',
  `note` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `sent_time` datetime DEFAULT NULL COMMENT '发送时间',
  `user_status` enum('启用','禁用') NOT NULL DEFAULT '启用' COMMENT '用户状态',
  `extends` text COMMENT '备注',
  `human_id` int(11) NOT NULL DEFAULT '0' COMMENT 'humans表id',
  PRIMARY KEY (`id`),
  KEY `index` (`uid`,`idcard`,`mobile`),
  KEY `sign` (`sign_date`),
  KEY `humanid` (`human_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='椋鸟计划表';
