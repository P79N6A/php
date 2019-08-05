<?php
if (! defined('SERVER_CONF_COMMON')) {
    define('SERVER_CONF_COMMON', true);
    define("VIEW_PATH", $ROOT_PATH . "/src/application/views");
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
);



?>
