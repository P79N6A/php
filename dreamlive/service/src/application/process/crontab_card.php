<?php

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

$card       = new DAOActivityHeaderCard();
$cache      = Cache::getInstance("REDIS_CONF_COUNTER");
$keys       = $cache->keys('dreamlive_'.Counter::COUNTER_TYPE_ROUND_NUM.'_*');
if($keys) {
    foreach($keys as $val)
    {
        $num    = $cache->get($val);
        $uid    = end(explode('-', $val));
        if($card->getCardById($uid)) {
            $card->modCardByUid($uid, $num);
        }else{
            $card->modCardByUid($uid, $num);
        }
    }
}
