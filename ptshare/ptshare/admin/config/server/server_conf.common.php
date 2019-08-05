<?php
if(!defined('SERVER_CONF_COMMON')) {
    define("ROOT_PATH", dirname(dirname(dirname(__FILE__))));
    define("VIEW_PATH", ROOT_PATH . "/src/application/views");
    define('SERVER_CONF_COMMON', true);
    define('SERVER_NAME', 'putaofenxiang.com');
    define('STATIC_SERVER_NAME', 'http://static.'.SERVER_NAME);
    define('COOKIE_NAME', 'putaofenxiang.com');

	$EXCEPTION = false;

	date_default_timezone_set('Asia/Shanghai');

	//------------------ 错误码 ------------------
	//成功
	define("OK",                           0);

	//公共错误码(参数、用户、api和app、系统)
	define("ERROR_PARAM_IS_EMPTY",         1001);
	define("ERROR_PARAM_NOT_EXIST",        1002);
	define("ERROR_PARAM_INVALID_SIGN",     1003);
	define("ERROR_PARAM_FLOOD_REQUEST",    1004);
	define("ERROR_PARAM_INVALID_FORMAT",   1005);
	define("ERROR_PARAM_NOT_SMALL_ZERO",   1006);
	define("ERROR_PARAM_KEY_EXISTS",       1009);
	define("ERROR_PARAM_REQUEST_RESTRICT", 1010);

    define("ERROR_RETURN_IS_EMPTY", 1201);
    define("ERROR_VARIABLE_UNDEFINED",     1202);

	define("ERROR_SYS_DB_SQL",             1301);
	define("ERROR_SYS_REDIS",              1302);
	define("ERROR_SYS_DB_DRIVER",          1303);
	define("ERROR_SYS_DB_METHOD",          1304);
	define("ERROR_SYS_UNKNOWN",		       1305);
	define("ERROR_SYS_NEEDPOST",           1306);

	define("ERROR_BIZ_UPLOAD_FAILD",       1401);
    define("ERROR_BIZ_TIME_OUT_OF_RANGE",  1402);

	define("ERROR_LIVE_NOT_EXISTS",        2001);
	define("ERROR_ADMIN_NOT_EXISTS",       2002);
	define("ERROR_INVALID_CAPTCHA",        2003);
	define("ERROR_INVALID_TOKEN",          2004);
	define("ERROR_INVALID_PASS",           2005);
	define("ERROR_ADMIN_FORBIDDEN",        2006);
	define("ERROR_SYS_URLGETERROR",        2007);

	//商城-sell
    define("ERROR_SELL_STATUS",            3001);
    define("ERROR_SELL_EMPTY",             3002);


    //资源错误
    define("RESOURCE_USER_IS_NULL",		    4601);
    define("RESOURCE_USER_NAME_REPEAT",     4602);
    define("RESOURCE_USER_NOT_MATCH",       4603);
    //认证错误
    define("ERROR_VERIFIED_APPLY",          4801);
    define("ERROR_VERIFIED_NO_APPLY",       4802);
    define("ERROR_VERIFIED",       4803);

    define("ERROR_WHITE_DEL_FAIL",          7101);
    define("ERROR_USER_NOT_EXISTS",         7102);
	define("ERROR_USER_NO_AUTH",            9301);
	define("ERROR_CURL_REQUEST",            9302);
	define("ERROR_SERVER_HOST_IS_DENIED",   9303);
    define("ERROR_CUSTOM",  93004);

    //运营币
    define("ERROR_INTERNALECONOMIC_AMOUNT_LIMIT",  80001);
    define("ERROR_INTERNALECONOMIC_AUDIT_LIMIT",  80002);
    define("ERROR_INTERNALECONOMIC_TRADE_FAIL",  80003);

    define("PARTNER_ADMIN", "admin");//运营后台
}

$ERROR_LIST = array(
OK=>"操作成功",
//参数
ERROR_PARAM_IS_EMPTY=>"参数%s不能为空!",
ERROR_PARAM_NOT_EXIST=>"参数%s不存在",
ERROR_PARAM_INVALID_SIGN=>"签名校验失败",
ERROR_PARAM_FLOOD_REQUEST=>"不能重复请求",
ERROR_PARAM_INVALID_FORMAT=>"参数%s格式错误",
ERROR_PARAM_NOT_SMALL_ZERO=>"参数%s不能小于0",
ERROR_PARAM_KEY_EXISTS=>"KEY已存在",
ERROR_PARAM_REQUEST_RESTRICT=>"不能重复请求",

//返回
ERROR_RETURN_IS_EMPTY=>"获取数据失败",
ERROR_VARIABLE_UNDEFINED=>"变量未定义, %s",

//系统
ERROR_SYS_DB_SQL=>"数据库SQL操作失败:%s",
ERROR_SYS_REDIS=>"REDIS错误",
ERROR_SYS_DB_DRIVER=>"数据驱动不存在",
ERROR_SYS_DB_METHOD=>"数据驱动方法不存在:method:%s",
ERROR_SYS_UNKNOWN=>"系统未知错误",
ERROR_SYS_NEEDPOST=>"需要使用POST方法",

ERROR_BIZ_UPLOAD_FAILD=>"图片上传异常：%s",

ERROR_SYS_URLGETERROR=>"第三方资源请求失败",
ERROR_USER_NO_AUTH=>"用户没有权限",
ERROR_LIVE_NOT_EXISTS=>"直播不存在",
ERROR_ADMIN_NOT_EXISTS=>"管理员不存在",
ERROR_INVALID_CAPTCHA=>"验证码错误",
ERROR_INVALID_TOKEN=>"动态密码错误",
ERROR_INVALID_PASS=>"密码错误",
ERROR_ADMIN_FORBIDDEN=>"用户受限",
ERROR_USER_NOT_EXISTS=>"用户%s不存在",
ERROR_CURL_REQUEST=>"CURL请求失败",
ERROR_SERVER_HOST_IS_DENIED=>"HOST %s DENIED",
ERROR_CUSTOM=>"自定义错误%s",

//用户
RESOURCE_USER_IS_NULL=>"用户不存在",
RESOURCE_USER_NAME_REPEAT=>"用户昵称已存在",
RESOURCE_USER_NOT_MATCH=>"用户信息不匹配",
//认证
ERROR_VERIFIED_NO_APPLY=>"用户没有申请认证",
ERROR_VERIFIED_APPLY=>"认证审核失败",
ERROR_VERIFIED=>"用户已经认证",

//商城-sell
ERROR_SELL_STATUS       => '状态异常: %s',
ERROR_SELL_EMPTY        => '信息不存在: %s',

//运营币
ERROR_INTERNALECONOMIC_AMOUNT_LIMIT => "金额受限",
ERROR_INTERNALECONOMIC_AUDIT_LIMIT => "操作受限",
ERROR_INTERNALECONOMIC_TRADE_FAIL => "入账失败",

);


$LOG_PATH = "/usr/local/nginx/logs/";
$LOG = array(
'level'			=> 0x07,		//fatal, warning, notice
'logfile'		=> $LOG_PATH."admin.ptshare.com.log",	//test.log.wf will be the wf log file
'split'         => 1,          //0 not split, 1 split by day, 2 split by hour
'others'       	=> array(
    )
);

$OPERATE_RELATE_TYPE = array(
    0=>'系统功能',
    1=>'用户发布',
    2=>'运营打包',
    3=>'宝贝租用',
);

$OPERATE_TYPE = array(
    'image_del'=> '[图片]删除',
);

$MENU_LIST = array(
    "商城管理"    => array(
        "用户分享" => '/sell/index',
        "宝贝列表" => "/goods/index",
        "添加宝贝" => "/goods/addGoods",
    ),


    "租用管理"    => array(
        "打包列表" => "/package/list",
        "租用列表" => "/Renting/list",
        "购买列表" => "/buy/list",
        "葡萄夺宝" => "/seizing/index",
    ),

    "经济系统"    => array(
        "系统转账" => "/account/list",
    	"订单管理" => "/orders/list?pay_status=2",
        "支付管理" => "/pay/list",
        "物流管理" => "/Express/list",
    ),

    "用户管理"    => array(
        "用户列表" => "/user/list",
    ),

    "活动管理"    => array(
        "七星彩" 	=> "/lottery/qxc",
    	"组队抽奖" 	=> "/lotteryTravel/list",
    ),
    "消息模块"    => array(
        "模板消息"  => "/Message/list",
    ),
    "系统管理"    => array(
        "我的帐号" => array(
            "个人信息" => "/admin/profile",
            "修改密码" => "/admin/password",
        ),
        "系统权限" => array(
            "管理员管理" => array(
                "添加管理员" => "/admin/add",
                "管理员列表" => "/admin/",
            ),
            "角色管理" => array(
                "添加角色" => "/role/add",
                "角色列表" => "/role/",
            ),
            "权限管理" => array(
                "添加权限" => "/auth/add",
                "权限列表" => "/auth/",
                "权限分配" => "/auth/assign"
            )
        ),
        "操作日志" => "/operate/",
        "配置管理" => "/AppConfig/list",
    ),

);

$STATIC_DOMAIN = "https://static.putaofenxiang.com";
