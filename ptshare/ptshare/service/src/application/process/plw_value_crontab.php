<?php
/**
 * 中国体育彩票排列五开奖结果获取放到redis，每天晚上9点运行一次
 */
set_time_limit(0);
ini_set('memory_limit', '2G');
ini_set("display_errors", "On");
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

$ROOT_PATH = dirname(dirname(dirname(dirname(__FILE__))));
$LOAD_PATH = array(
		$ROOT_PATH."/src/www",
		$ROOT_PATH."/config",
		$ROOT_PATH."/src/application/controllers",
		$ROOT_PATH."/src/application/models",
		$ROOT_PATH."/src/application/models/libs",
		$ROOT_PATH."/src/application/models/dao"
);
set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $LOAD_PATH));

function __autoload($classname)
{
	$classname = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
	include_once "$classname.php";
}
require $ROOT_PATH . "/config/server_conf.php";

//地址https://www.caipiaokong.com/open/
//帐号xubaoguo密码xubaoguo1985
$name = 'plw';
$uid = '1030240';
$token = '5205353d8e96f473e443e9e44e914b2ad65cdfdc';

$data = file_get_contents("http://api.caipiaokong.com/lottery/?name=".$name."&format=json&uid=".$uid."&token=".$token."");

//$data缓存
$array = json_decode($data, true);

$first_val = reset($array);

$first_key = key($array);

if (empty($array) || empty($first_val) || empty($first_key)) {
	Logger::log("plw_value_crontab", "novalue", array("json" => json_encode($data)));
	exit;
}
$last_plw = [
		'dateline' => $first_key,
		'opentime' => $first_val['dateline'],
		'number'   => str_replace(',', '', $first_val['number']),
];
$cache = Cache::getInstance("REDIS_CONF_CACHE");
$china_tiyu_lottery_plw_key = "china_tiyu_lottery_plw_key";
$cache->hmset("china_tiyu_lottery_plw_key", $last_plw);

Logger::log("plw_value_crontab", "get", array("json" => json_encode($last_plw)));

var_dump("done");