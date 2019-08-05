<?php
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


$dao = new DAOSell();


$clear_data = "truncate table travel_group ";

$bool = $dao->execute($clear_data);

$clear_data = "truncate table travel_member ";

$bool = $dao->execute($clear_data);


$cache = Cache::getInstance("REDIS_CONF_CACHE");

$keys = $cache->keys("lottery_group_finished*");
var_dump("lottery_group_finished");
foreach ($keys as $key) {
	$bool = $cache->del($key);
	
	var_dump($key . "=>>" . $bool);
}

$keys = $cache->keys("travel_lotter_code_*");
var_dump("travel_lotter_code_");
foreach ($keys as $key) {
	$bool = $cache->del($key);
	
	var_dump($key . "=>>" . $bool);
}

var_dump("hmset_travel_lottery_info_");
$keys = $cache->keys("hmset_travel_lottery_info_*");

foreach ($keys as $key) {
	$bool = $cache->del($key);
	
	var_dump($key . "=>>" . $bool);
}

var_dump("lottery_finished_");
$keys = $cache->keys("lottery_finished_*");

foreach ($keys as $key) {
	$bool = $cache->del($key);
	
	var_dump($key . "=>>" . $bool);
}


var_dump("hmset_travel_group_info_");

$keys = $cache->keys("hmset_travel_group_info_*");

foreach ($keys as $key) {
	$bool = $cache->del($key);
	
	var_dump($key . "=>>" . $bool);
}