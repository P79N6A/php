<?php
/**
 * 天猫店充值
 *
 * 异步结果接口（商家必需使用）
 */

ini_set("display_errors", "On");
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

$ROOT_PATH = dirname(dirname(dirname(dirname(__FILE__))));
$LOAD_PATH = [
    $ROOT_PATH . "/src/www",
    $ROOT_PATH . "/config",
    $ROOT_PATH . "/src/application/controllers",
    $ROOT_PATH . "/src/application/models",
    $ROOT_PATH . "/src/application/models/libs",
    $ROOT_PATH . "/src/application/models/dao",
    $ROOT_PATH . "/src/application/models/libs/payment_client",
];
set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $LOAD_PATH));

function __autoload($classname)
{
    $classname = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
    include_once "$classname.php";
}

require $ROOT_PATH . "/config/server_conf.php";

$tmall_deposit = new TmallDeposit();
$tmall_deposit->orderCrontab();
