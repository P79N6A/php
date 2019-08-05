<?php
if (! defined('SERVER_CONF_COMMON')) {
    define('SERVER_CONF_COMMON', true);

    date_default_timezone_set('Asia/Shanghai');

    // ------------------ 错误码 ------------------
    // 成功
    define("OK", 0);

    // 公共错误码(参数、用户、api和app、系统) 1001-1099
    define("ERROR_PARAM_IS_EMPTY", 1001);
    define("ERROR_PARAM_NOT_EXIST", 1002);
    define("ERROR_PARAM_INVALID_SIGN", 1003);
    define("ERROR_PARAM_FLOOD_REQUEST", 1004);
    define("ERROR_PARAM_INVALID_FORMAT", 1005);
    define("ERROR_API_NOT_ALLOWED", 1006);
    define("ERROR_IP_NOT_ALLOWED", 1007);
    define("ERROR_METHOD_NOT_ALLOWED", 1008);
    define("ERROR_PARAM_REQUEST_RESTRICT", 1010);
    define("ERROR_PARAM_NOT_SMALL_ZERO", 1011);
    define("ERROR_PARAM_DATA_NOT_EXIST", 1012);
    define("ERROR_PARAM_PLATFORM_INVALID", 1013);
    define("ERROR_PARAM_SIGN_INVALID", 1014);
    define("ERROR_PARAM_NOT_HAS_POINT", 1015);
    define("ERROR_SYS_DB_SQL", 1016);
    define("ERROR_SYS_REDIS", 1017);
    define("ERROR_SYS_CONTEXT_KEY_EXISTS", 1018);
    define("ERROR_SYS_CONTEXT_KEY_NOT_EXISTS", 1019);
    define("ERROR_SYS_NEEDPOST", 1020);
    define("ERROR_SYS_UNKNOWN", 1021);
    define("ERROR_SYS_DB_UID", 1022);


    // 用户级别 1101-1199
    define("ERROR_USER_NOT_LOGIN", 1101);
    define("ERROR_LOGINUSER_NOT_EXIST", 1102);
    define("ERROR_USER_NOT_EXIST", 1103);
    define("ERROR_USER_ERR_TOKEN", 1104);
    define("ERROR_USER_ERR_BLACK", 1105);
    define("ERROR_USER_NAME_EXISTS", 1106);
    define("ERROR_QUEUE_UID_MASTER_ISNULL", 1107);


    //cos
    define("ERROR_COS_CONNECT_FAILD", 1501);
    define("ERROR_COS_PUT_FAILD", 1502);
    define("ERROR_COS_GET_FAILD", 1503);
    define("ERROR_COS_DELETE_FAILD", 1504);

    // 任务 2001-2099
    define("ERROR_BIZ_TASK_NOT_EXIST", 2001);
    define("ERROR_BIZ_TASK_UNSTART", 2002);
    define("ERROR_BIZ_TASK_EXPIRE", 2003);
    define("ERROR_BIZ_TASK_CLOSED", 2004);
    define("ERROR_BIZ_TASK_OVER_TIMES", 2005);
    define("ERROR_BIZ_TASK_PAYMENT", 2006);
    define("ERROR_BIZ_TASK_IS_SIGN", 2017);
    define("ERROR_BIZ_TASK_SIGN_ERROR", 2018);
    define("ERROR_BIZ_TASK_AWARD_EMPTY", 2019);
    define("ERROR_BIZ_TASK_NOT_COMPLETE", 2010);


    // 商城模块 3001-3199
    define("ERROR_MALL_SELL_ADD", 3001);
    define("ERROR_MALL_SELL_AUDIT", 3002);
    define("ERROR_MALL_SELL_REFUSE", 3003);
    define("ERROR_MALL_SELL_ACCEPT", 3004);
    define("ERROR_MALL_PACKAGE_FAVORITE", 3005);
    define("ERROR_MALL_PACKAGE_ADD", 3006);
    define("ERROR_MALL_GOODS_UPDATE", 3007);
    define("ERROR_MALL_GOODS_STATUS", 3008);
    define("ERROR_MALL_SELL_UPDATE_CONTACT", 3009);
    define("ERROR_MALL_SELL_CANCEL", 3010);
    define("ERROR_MALL_SELL_STATUS", 3011);
    define("ERROR_MALL_PREVIEW", 3012);
    define("ERROR_MALL_SELL_SUCCESS", 3013);


    define("ERROR_BIZ_PACKET_RCEIVE_EMPTY", 4001);
    define("ERROR_BIZ_PACKET_RCEIVE_FINISH", 4002);
    define("ERROR_BIZ_PACKET_RCEIVE_DAYTIMES", 4003);
    define("ERROR_BIZ_PACKET_IS_EXPIRE", 4004);


    // packet
    define("ERROR_BIZ_PACKET_NOT_EXIST", 40010);
    define("ERROR_BIZ_PACKET_NOT_ONLINE", 40020);
    define("ERROR_BIZ_PACKET_NOT_EXPIRE", 40030);
    define("ERROR_BIZ_PACKET_SELL_TYPE", 40040);
    define("ERROR_BIZ_PACKET_VIP_NOT_BUYR_OR_ENT", 40041);


    // 租用
    define("ERROR_BIZ_RANTING_MONTH_NOT_RANGE", 5001);
    define("ERROR_BIZ_RANTING_DATA_NOT_EXIST", 5002);
    define("ERROR_BIZ_RANTING_MONTH_EEPIRE", 5003);
    define("ERROR_BIZ_RANTING_NOT_ALLOW_CANCEL", 5004);
    define("ERROR_BIZ_RANTING_REPEAT_ORDER", 5005);
    define("ERROR_BIZ_RANTING_NO_CANCEL", 5006);

    // 经济系统
    define("ERROR_BIZ_PAYMENT_ACCOUNT_BALANCE_LACK", 9001);
    define("ERROR_BIZ_PAYMENT_ACCOUNT_INVALID",      9002);
    define("ERROR_BIZ_PAYMENT_TICKET_BALANCE_DUE", 9003);
    define("ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE", 9004);
    define("ERROR_BIZ_PAYMENT_VIRTUAL_BALANCE_DUE", 9005);
    define("ERROR_BIZ_PAYMENT_CASH_BALANCE_DUE", 9006);
    define("ERROR_BIZ_PAYMENT_STAR_BALANCE_DUE", 9007);
    define("ERROR_BIZ_PAYMENT_STAR_DIAMOND_LIMIT", 9008);
    define("ERROR_BIZ_PAYMENT_PAYCOMP_FALSE", 9009);
    define("ERROR_BIZ_PAYMENT_NEED_PAY", 9010);

    // 订单系统
    define("ERROR_BIZ_ORDER_STATUS_UNVALID",         11000); //订单状态不合法
    define("ERROR_BIZ_ORDER_PAY_STATUS_UNVALID",     11001); //订单支付状态不合法
    define("ERROR_BIZ_ORDER_EXPRESS_STATUS_UNVALID", 11002); //订单快递状态不合法
    define("ERROR_BIZ_ORDER_CREATE_FAIL",            11003); //订单快递状态不合法
    define("ERROR_BIZ_ORDER_UPDATE_FAIL",            11004); //订单快递状态不合法
    define("ERROR_BIZ_ORDER_NOT_EXIST",              11005); //订单不存在

    define("ERROR_QUEUE_DELIVERYID_ISNULL",          11101); //JD运单id不存在
    
    // 小卡片
    define("ERROR_BIZ_CARD_NOT_EXIST",           12000); //卡片不存在
    define("ERROR_BIZ_CARD_NOT_SELF",           12001); // 非本人操作

    //七星彩
    define("ERROR_LOTTERY_DISABLE",              200001); //七星彩 关闭
    define("ERROR_LOTTERY_STATUS_FAIL",          200004); //开奖状态错误
    define("ERROR_LOTTERY_EDIT_NUMBER_ALREADY",  200005);
    define("ERROR_LOTTERY_STATUS_NOT_WAIT",      200006);
    define("ERROR_LOTTERY_EDIT_LT_ENDTIME",      200007);
    define("ERROR_LOTTERY_EXIST",                200008);

    define("ERROR_BIZ_IS_NOT_PATRON",            200002);//不在发放关注奖励列表
    define("ERROR_BIZ_IS_FOLLOWED",              200003); //之前关注已经发放过奖励

    // 一元夺宝
    define("ERROR_BIZ_ZEIZING_SELL_OUT",         210001); //一元夺宝已抢完
    define("ERROR_BIZ_ZEIZING_REPEAT_ORDER",     210002); //重复抢购
    define("ERROR_BIZ_ZEIZING_CANCEL",           210003); //cancel

    //组队抽奖
    define("ERROR_BIZ_TRAVEL_LOTTERY_FINISHED",		220001);//活动已结束
    define("ERROR_BIZ_TRAVEL_GROUP_FINISHED",		220002);//组队已结束
    define("ERROR_BIZ_TRAVEL_GROUP_NOT_EXIST",		220003);//组队不存在
    define("ERROR_BIZ_TRAVEL_LOTTERY_NOT_EXIST",	220004);//组队抽奖不存在
    define("ERROR_BIZ_TRAVEL_GROUP_MEMBER_EXIST",	220005);//已参加过该团队
    define("ERROR_BIZ_TRAVEL_GROUP_ADD_ERROR",		220006);//创建组队报错
    define("ERROR_BIZ_TRAVEL_GROUP_JOIN_ERROR",		220007);//参与组队报错
}

$ERROR_LIST = array(
    OK => "操作成功",
    // 参数
    ERROR_PARAM_IS_EMPTY                    => "参数%s不能为空!",
    ERROR_PARAM_NOT_EXIST                   => "参数%s不存在",
    ERROR_PARAM_INVALID_SIGN                => "签名校验失败",
    ERROR_PARAM_FLOOD_REQUEST               => "不能重复请求",
    ERROR_PARAM_INVALID_FORMAT              => "参数%s格式错误",
    ERROR_METHOD_NOT_ALLOWED                => "方法未授权%s",
    ERROR_PARAM_REQUEST_RESTRICT            => "异常重复请求",
    ERROR_PARAM_NOT_SMALL_ZERO              => "参数%s不能小于0",
    ERROR_PARAM_DATA_NOT_EXIST              => '参数%s数据不存在',
    ERROR_PARAM_PLATFORM_INVALID            => '非法平台访问',
    ERROR_PARAM_SIGN_INVALID                => '签名校验失败',
	ERROR_PARAM_NOT_HAS_POINT               => "参数%s不能是小数",

    ERROR_API_NOT_ALLOWED                   => "API未授权",
    // 用户
    ERROR_USER_NOT_LOGIN                    => "登录失败, 请重试",
    ERROR_LOGINUSER_NOT_EXIST               => "当前登录用户不存在",
    ERROR_USER_NOT_EXIST                    => "用户不存在",
    ERROR_USER_ERR_TOKEN                    => "登录状态异常，请您重新登录。",
    ERROR_QUEUE_UID_MASTER_ISNULL           => "用户ID不存在",

    ERROR_COS_CONNECT_FAILD                 => "cos超时",
    ERROR_COS_PUT_FAILD                     => "文件上传失败",
    ERROR_COS_GET_FAILD                     => "文件下载失败",
    ERROR_COS_DELETE_FAILD                  => "文件删除失败",
    // 任务
    ERROR_BIZ_TASK_NOT_EXIST                => "任务不存在",
    ERROR_BIZ_TASK_UNSTART                  => "任务未到开始时间",
    ERROR_BIZ_TASK_EXPIRE                   => "任务已过期",
    ERROR_BIZ_TASK_CLOSED                   => "任务未开启",
    ERROR_BIZ_TASK_OVER_TIMES               => "任务次数超限",
    ERROR_BIZ_TASK_PAYMENT                  => "支付系统出错",
    ERROR_BIZ_TASK_IS_SIGN                  => "已经签到",
    ERROR_BIZ_TASK_SIGN_ERROR               => "签到错误",
    ERROR_BIZ_TASK_AWARD_EMPTY              => "奖励不能为空",
    ERROR_BIZ_TASK_NOT_COMPLETE             => "任务未完成",

    // 红包
    ERROR_BIZ_PACKET_RCEIVE_EMPTY           => "红包已经发完了",
    ERROR_BIZ_PACKET_RCEIVE_FINISH          => "已经领取过该红包",
    ERROR_BIZ_PACKET_RCEIVE_DAYTIMES        => "今天次数已用尽",
    ERROR_BIZ_PACKET_IS_EXPIRE              => "红包已过期",

    // packet
    ERROR_BIZ_PACKET_NOT_EXIST              => "packet不存在",
    ERROR_BIZ_PACKET_NOT_ONLINE             => "包裹已售罄",
    ERROR_BIZ_PACKET_NOT_EXPIRE             => "packet已过期",
    ERROR_BIZ_PACKET_SELL_TYPE              => "包裹销售类型错误",
    ERROR_BIZ_PACKET_VIP_NOT_BUYR_OR_ENT    => "非vip用户不允许租买",


    //租用
    ERROR_BIZ_RANTING_MONTH_NOT_RANGE       => "亲，宝贝最多能租6个月哦~",
    ERROR_BIZ_RANTING_DATA_NOT_EXIST        => "租用信息不存在",
    ERROR_BIZ_RANTING_MONTH_EEPIRE          => "租用到期不允许续租",
	ERROR_BIZ_RANTING_NOT_ALLOW_CANCEL  	=> "订单不允许取消",
    ERROR_BIZ_RANTING_REPEAT_ORDER          => "已有租用订单，不允许重复租用",
    ERROR_BIZ_RANTING_NO_CANCEL             => "非本人不允许取消订单",

    // 经济
    ERROR_BIZ_PAYMENT_ACCOUNT_BALANCE_LACK  => "账户余额不足",
    ERROR_BIZ_PAYMENT_ACCOUNT_INVALID       => "无效的账户",
	ERROR_BIZ_PAYMENT_TICKET_BALANCE_DUE=>'星票余额不足',
	ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE=>'葡萄余额不足',
	ERROR_BIZ_PAYMENT_VIRTUAL_BALANCE_DUE=>'虚拟币余额不足',
	ERROR_BIZ_PAYMENT_CASH_BALANCE_DUE=>'现金不足',
	ERROR_BIZ_PAYMENT_STAR_BALANCE_DUE=>'星星余额不足%s',
	ERROR_BIZ_PAYMENT_STAR_DIAMOND_LIMIT=>'今日星钻兑换数量已用完(100)，明天再来吧',
	ERROR_BIZ_PAYMENT_PAYCOMP_FALSE		=> '支付回调失败',
	ERROR_BIZ_PAYMENT_NEED_PAY	=> "需要支付",
    // 系统
    ERROR_SYS_DB_SQL                        => "系统有问题，请稍后重试",
    ERROR_SYS_REDIS                         => "REDIS错误",
    ERROR_SYS_CONTEXT_KEY_EXISTS            => "Context变量已定义:%s",
    ERROR_SYS_CONTEXT_KEY_NOT_EXISTS        => "Context变量未定义:%s",
    ERROR_SYS_NEEDPOST                      => "需要使用POST方法",
    ERROR_SYS_UNKNOWN                       => "操作失败",
    ERROR_SYS_DB_UID                        => "UID生成异常",

    // 商城
    ERROR_MALL_SELL_ADD                     => '发布失败',
    ERROR_MALL_SELL_AUDIT                   => '审核失败 [ %s ]',
    ERROR_MALL_SELL_REFUSE                  => '拒绝失败 [ %s ]',
    ERROR_MALL_SELL_ACCEPT                  => '同意失败 [ %s ]',
    ERROR_MALL_SELL_UPDATE_CONTACT          => '更新地址失败 [ %s ]',
    ERROR_MALL_PACKAGE_FAVORITE             => '操作失败 [ %s ]',
    ERROR_MALL_PACKAGE_ADD                  => '添加失败 [ %s ]',
    ERROR_MALL_SELL_CANCEL                  => '取消失败 [ %s ]',
    ERROR_MALL_SELL_STATUS                  => '状态异常 [ %s ]',
    ERROR_MALL_PREVIEW                      => '操作图资源失败 [ %s ]',
    ERROR_MALL_SELL_SUCCESS                 => '更新失败 [ %s ]',

    //
    ERROR_BIZ_ORDER_STATUS_UNVALID          => "订单状态不合法[%s]",
    ERROR_BIZ_ORDER_PAY_STATUS_UNVALID      => "订单支付状态不合法[%s]",
    ERROR_BIZ_ORDER_EXPRESS_STATUS_UNVALID  => "订单快递状态不合法[%s]",
    ERROR_BIZ_ORDER_CREATE_FAIL             => "订单创建失败[%s]",
	ERROR_BIZ_ORDER_UPDATE_FAIL				=> "订单修改失败[%s]",
    ERROR_BIZ_ORDER_NOT_EXIST               => "订单不存在[%s]",

    ERROR_QUEUE_DELIVERYID_ISNULL           => "快递运单id不存在",

    ERROR_MALL_GOODS_UPDATE                 => '宝贝更新失败 [ %s ]',
    ERROR_MALL_GOODS_STATUS                 => '宝贝状态异常 [ %s ]',

	ERROR_BIZ_CARD_NOT_EXIST				=> '卡片不存在',
    ERROR_BIZ_CARD_NOT_SELF					=> '非本人操作',

    ERROR_LOTTERY_DISABLE                   => '本期彩票未开启',
    ERROR_LOTTERY_STATUS_FAIL               => '彩票状态错误',
    ERROR_LOTTERY_EDIT_NUMBER_ALREADY       => '已设置开奖号码',
    ERROR_LOTTERY_STATUS_NOT_WAIT           => '彩票已开奖',
    ERROR_LOTTERY_EDIT_LT_ENDTIME           => '彩票未结束',
    ERROR_LOTTERY_EXIST                     => '彩票已存在 [ %s ]',

    ERROR_BIZ_IS_FOLLOWED                   => '之前关注已经发放过奖励',
    ERROR_BIZ_IS_NOT_PATRON                 => '不在发放关注奖励列表',

    // 一元夺宝
    ERROR_BIZ_ZEIZING_SELL_OUT              => '已抢完',
    ERROR_BIZ_ZEIZING_REPEAT_ORDER          => '已抢过该包裹，不允许重复抢购',
    ERROR_BIZ_ZEIZING_CANCEL                => '夺宝订单不可手动取消哦~',

    //组队抽奖
	ERROR_BIZ_TRAVEL_LOTTERY_FINISHED       => '活动已结束',
	ERROR_BIZ_TRAVEL_GROUP_FINISHED         => '队伍已经组队完毕',
	ERROR_BIZ_TRAVEL_GROUP_NOT_EXIST    	=> "组队不存在",
	ERROR_BIZ_TRAVEL_LOTTERY_NOT_EXIST 		=> "抽奖活动不存在",
	ERROR_BIZ_TRAVEL_GROUP_MEMBER_EXIST 	=> "已参加过该团队",
	ERROR_BIZ_TRAVEL_GROUP_ADD_ERROR		=> "创建组队报错",
	ERROR_BIZ_TRAVEL_GROUP_JOIN_ERROR		=> "参与组队报错",
);

$LOG_PATH = "/usr/local/nginx/logs/";
$LOG = array(
    'level'     => 0x07, // fatal, warning, notice
    'logfile'   => $LOG_PATH . "api.ptshare.com.log", // test.log.wf will be the wf log file
    'split'     => 0, // 0 not split, 1 split by day, 2 split by hour
    'others'    => array(
        'oauth' 				=> $LOG_PATH . "oauth.log",
    	'account' 				=> $LOG_PATH . "account.log",
    	'mall_package'  		=> $LOG_PATH . "mall_package.log",
        'storage_get_err' 		=> $LOG_PATH . 'storage_get_err.log',
        'storage_set_err' 		=> $LOG_PATH . 'storage_set_err.log',
        'storage_connect_err' 	=> $LOG_PATH . 'storage_connect_err.log',
        'pay_notify_log'		=> $LOG_PATH . 'pay_notify_log.log',
        'express_notify' 		=> $LOG_PATH . 'express_notify.log',
        'express_notify_error'	=> $LOG_PATH . 'express_notify_error.log',
        'express_eorder' 		=> $LOG_PATH.  'express_eorder.log',
        'express_eorder_err' 	=> $LOG_PATH.  'express_eorder_err.log',
        'express_err'        	=> $LOG_PATH.  'express_err.log',
    	'pay_refund_log'		=> $LOG_PATH . 'pay_refund_log.log',
    	'rent_cancel'			=> $LOG_PATH . 'rent_cancel.log',
    	'pay_prepare'			=> $LOG_PATH . 'pay_prepare.log',
        'user_renting_revoke'   => $LOG_PATH . 'user_renting_revoke.log',
        'user_renting'          => $LOG_PATH . 'user_renting.log',
        'user_renting_order'    => $LOG_PATH . 'user_renting_order.log',
        'sql_query'             => $LOG_PATH . 'query_logs/'.date('Ymd').'.log',
        'request_api'   		=> $LOG_PATH . "request_api/".date('Ymd').".log",
        'wechat_log'   		    => $LOG_PATH . "wechat_log/".date('Ymd').".log",
        'WxMessage'   		    => $LOG_PATH . "WxMessage.log",
        'avatar_save' 		    => $LOG_PATH . "avatar_save.log",
        'buying'                => $LOG_PATH . "buying.log",
    	'sell_log'              => $LOG_PATH . "sell_log.log",
    	'task_log'              => $LOG_PATH . "task_log.log",
    	'travel_lottery_log'    => $LOG_PATH . "travel_lottery_log.log",
    	'lottery_crontab_log'   => $LOG_PATH . "lottery_crontab_log.log",
    	'add_sell_params'   	=> $LOG_PATH . "add_sell_params.log",
    	'plw_value_crontab'   	=> $LOG_PATH . "plw_value_crontab.log",
    )
);

define("AUTH_CHECK_LOGIN",   1); //登录用户可用
define("AUTH_CHECK_POST",    2); //POST方式提交
define("AUTH_CHECK_FORBID",  3); //校验用户封禁
define("AUTH_CHECK_SERVER",  4); //内部系统调用

$API_AUTH_CONFIG = array(
    "/user/active"              => array(),
    "/user/getMyUserInfo"       => array(AUTH_CHECK_LOGIN),
    "/user/sync"                => array(AUTH_CHECK_LOGIN),
    "/user/fastLogin"           => array(AUTH_CHECK_LOGIN),
    "/profile/getProfile"       => array(AUTH_CHECK_LOGIN),
    "/profile/sync"             => array(AUTH_CHECK_LOGIN),
    "/follow/getFollowings"     => array(AUTH_CHECK_LOGIN),
    "/follow/getFollowers"      => array(AUTH_CHECK_LOGIN),
    "/follow/add"               => array(AUTH_CHECK_LOGIN),
    "/follow/cancel"            => array(AUTH_CHECK_LOGIN),
    "/follow/getUserFollowings" => array(),
    "/follow/getUserFollowers"  => array(),

    "/wallet/getWalletInfo"     => array(AUTH_CHECK_LOGIN),
    "/wallet/distribute"        => array(AUTH_CHECK_SERVER),
    "/wallet/recycle"           => array(AUTH_CHECK_SERVER),
    "/wallet/freeze"            => array(AUTH_CHECK_SERVER),
    "/wallet/unfreeze"          => array(AUTH_CHECK_SERVER),
    "/tag/addTag"               => array(AUTH_CHECK_LOGIN),
    "/tag/getUserHistory"       => array(AUTH_CHECK_LOGIN),

    //-----------------------任务---------------------------//
    "/task/execute"                   => array(),
    "/task/getUserTaskList"           => array(AUTH_CHECK_LOGIN),

    //-----------------------红包---------------------------//
    "/redPacket/receiveGroup"            => array(AUTH_CHECK_LOGIN),
    "/redPacket/getRedPacketList"        => array(AUTH_CHECK_LOGIN),
    "/redPacket/getUserRedPacketLogList" => array(AUTH_CHECK_LOGIN),
    "/redPacket/groupRedPacketSuccess"   => array(AUTH_CHECK_LOGIN),
    "/redPacket/getUserRedPacketLogInfo" => array(AUTH_CHECK_LOGIN),

    //-----------------------分享---------------------------//
    "/share/getShareUrl"                 => array(AUTH_CHECK_LOGIN),
    "/share/inviteNewUserSuccess"        => array(AUTH_CHECK_LOGIN),

    //-----------------------经济---------------------------//
    "/Journal/getGrapeInList"            => array(AUTH_CHECK_LOGIN),


    //-----------------------SELL---------------------------//
    "/sell/add"                         => [AUTH_CHECK_LOGIN],
    "/sell/getSellList"                 => [AUTH_CHECK_LOGIN],
//    "/sell/setOnlineAuditSuccess"       => [AUTH_CHECK_SERVER],
//    "/sell/setOnlineAuditRefuse"        => [AUTH_CHECK_SERVER],
//    "/sell/updateContact"               => [AUTH_CHECK_SERVER],
//    "/sell/setSellStatus"               => [AUTH_CHECK_SERVER],
    "/sell/setUserCancel"               => [AUTH_CHECK_LOGIN],
    "/sell/setUserAccept"               => [AUTH_CHECK_LOGIN],
    "/sell/addUsersDonate"              => [AUTH_CHECK_LOGIN],

    "/sell/setOnlineAuditSuccess"       => [],
    "/sell/setOnlineAuditRefuse"        => [],
    "/sell/updateContact"               => [],
    "/sell/setSellStatus"               => [],

    //-----------------------GOODS---------------------------//
//    "/goods/updateStatus"       => [AUTH_CHECK_SERVER],

    //-----------------------douban---------------------------//
	"/bookstore/isbn"       	=> [AUTH_CHECK_LOGIN],
	"/bookstore/search"       	=> [AUTH_CHECK_LOGIN],
    //-----------------------PACKAGE---------------------------//
    "/package/recommend"        => [AUTH_CHECK_LOGIN],
//    "/package/getPackageDetails"=> [AUTH_CHECK_LOGIN],
    "/package/favorite"         => [AUTH_CHECK_LOGIN],
    "/package/getUserFavoriteList" => [AUTH_CHECK_LOGIN],

    //-----------------------RENT---------------------------//
    "/rent/order"                      => [AUTH_CHECK_LOGIN],
    "/userRenting/order"               => [AUTH_CHECK_LOGIN],
    "/userRenting/getRentingList"      => [AUTH_CHECK_LOGIN],
    "/userRenting/cancel"              => [AUTH_CHECK_LOGIN],
    "/userRenting/receive"             => [AUTH_CHECK_LOGIN],
    "/userRenting/getExpress"          => [AUTH_CHECK_LOGIN],
    "/userRenting/details"             => [AUTH_CHECK_LOGIN],
    "/userRenting/cancelPay"           => [AUTH_CHECK_LOGIN],
	"/userRenting/paySuccess"          => [AUTH_CHECK_LOGIN],

    //-----------------------MESSAGE---------------------------//
    "/message/getMessageList"   => [AUTH_CHECK_LOGIN],
	//-----------------------支付---------------------------//
	"/pay/prepare"   			=> [AUTH_CHECK_LOGIN],
	"/pay/refund"   			=> [AUTH_CHECK_LOGIN],
	//-----------------------小卡片---------------------------//
	"/card/getList"   			=> [AUTH_CHECK_LOGIN],
	"/card/add"   				=> [AUTH_CHECK_LOGIN],
    "/card/del"   				=> [AUTH_CHECK_LOGIN],
    //-----------------------七星彩---------------------------//
    "/lottery/getQXC"           => array(AUTH_CHECK_LOGIN),
    "/lottery/addQXC"           => array(AUTH_CHECK_LOGIN),

    "/poster/getPoster"         => array(AUTH_CHECK_LOGIN),
    //----------------------组队抽奖---------------------------//
	"/lotteryTravel/mylist"     => array(AUTH_CHECK_LOGIN),
	"/lotteryTravel/add"        => array(AUTH_CHECK_LOGIN),
	//"/message/addFormid"        => array(AUTH_CHECK_LOGIN),
	"/vip/createGroup"        	=> array(AUTH_CHECK_LOGIN),
    "/vip/groupDetail"        	=> array(AUTH_CHECK_LOGIN),
    "/vip/getGroupId"           => array(AUTH_CHECK_LOGIN),
    "/invite/join"        	    => array(AUTH_CHECK_LOGIN),
);

$API_ACCESS_CONFIG = array(
    //运营后台
    "admin"=>array(
        'hosts' => array(
            '192.168.1',
            '127.0.0.1',
        )
    ),
	"internal"=>array(
		'hosts' => array(
			'192.168.1',
			'127.0.0.1',
			'123.207.137.156',
		)
	),
);

$STATIC_DOMAIN = "https://static.putaofenxiang.com";

$WX_ACCOUNT_CONFIG = [
    'base_url'              => 'https://api.weixin.qq.com',
    'app_id'                => 'wx0998703598fcc712',
    'secret'                => '68ea49d3f492d04b3e14883489449e25',
    'access_token_key'      => 'wx_program_access_token_key',
    'js_api_ticket_key'     => 'wx_program_js_api_ticket_key',
    'token_expire_time'     => 3600,
    'token_cache_driver'    => 'REDIS_CONF_WXTOKEN',

];


?>
