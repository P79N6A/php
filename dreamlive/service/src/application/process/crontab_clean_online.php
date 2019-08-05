<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/5
 * Time: 19:01
 * 修复游戏土豪
 */

if (substr(php_sapi_name(), 0, 3) !== 'cli') { die("cli mode only");
}

set_time_limit(0);
ini_set('memory_limit', '2G');
ini_set("display_errors", "On");
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
$ROOT_PATH = dirname(dirname(dirname(dirname(__FILE__))));

$LOAD_PATH = array(
    $ROOT_PATH . "/src/www",
    $ROOT_PATH . "/config",
    $ROOT_PATH . "/src/application/controllers",
    $ROOT_PATH . "/src/application/models",
    $ROOT_PATH . "/src/application/models/libs",
    $ROOT_PATH . "/src/application/models/libs/stream_client",
    $ROOT_PATH . "/src/application/models/dao",
    $ROOT_PATH . "/../",
);
set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $LOAD_PATH));

function __autoload($classname)
{
    $classname = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
    include_once "$classname.php";
}

require $ROOT_PATH . "/config/server_conf.php";

try{
    $cache  = Cache::getInstance("REDIS_CONF_COUNTER");
    $elements = $cache->zRevRangeByScore("dreamlive_online_users_redis_key", PHP_INT_MAX, 0, ['withscores' => true, 'limit' => [0, -1]]);
    $dao_live = new DAOLive();
    foreach ($elements as $uid=>$liveid){
        if (!$dao_live->isLiveRunning($liveid)) {
            $online_user_key = "dreamlive_online_users_redis_key";
            $cache->ZREM($online_user_key, $uid);
        }
    }
}catch (Exception $e){
    throw  $e;
}


