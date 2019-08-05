CREATE TABLE `starling_other_count` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `day` char(10) NOT NULL DEFAULT '' COMMENT '日期',
  `call_out_total` int(11) NOT NULL DEFAULT '0' COMMENT '呼出量',
  `daikan_total` int(11) NOT NULL DEFAULT '0' COMMENT '带看量',
  `addtime` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `day` (`day`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `starling_user_count` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `city_name` varchar(20) NOT NULL DEFAULT '' COMMENT '城市',
  `channel` varchar(20) NOT NULL DEFAULT '' COMMENT '渠道',
  `day` char(10) NOT NULL DEFAULT '' COMMENT '日期',
  `reg_total` int(11) NOT NULL DEFAULT '0' COMMENT '注册量',
  `sign_total` int(11) NOT NULL DEFAULT '0' COMMENT '签约量',
  `auth_total` int(11) NOT NULL DEFAULT '0' COMMENT '认证量',
  `auth_pass_total` int(11) NOT NULL DEFAULT '0' COMMENT '认证通过量',
  `auto_auth` int(11) NOT NULL DEFAULT '0' COMMENT '学信网认证量',
  `auto_auth_pass` int(11) NOT NULL DEFAULT '0' COMMENT '学信网认证通过量',
  `abroad_auth` int(11) NOT NULL DEFAULT '0' COMMENT '海外认证量',
  `abroad_auth_pass` int(11) NOT NULL DEFAULT '0' COMMENT '海外认证通过量',
  `addtime` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `city` (`city_name`),
  KEY `day` (`day`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `starling_apply_count` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `day` char(10) NOT NULL DEFAULT '' COMMENT '日期',
  `apply_total` int(11) NOT NULL DEFAULT '0' COMMENT '发起人量',
  `help_total` int(11) NOT NULL DEFAULT '0' COMMENT '助力人量',
  `finish_total` int(11) NOT NULL DEFAULT '0' COMMENT '完成助力量',
  `addtime` datetime NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`id`),
  KEY `day` (`day`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `starling_click` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '用户id',
  `city_name` varchar(50) NOT NULL DEFAULT '' COMMENT '城市名',
  `channel` varchar(20) NOT NULL DEFAULT '' COMMENT '渠道',
  `addtime` datetime NOT NULL COMMENT '添加时间',
  `modtime` datetime NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `city` (`city_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

