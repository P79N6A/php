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

// $dao = new DAOUser();

// $sql1 = "show tables";

// $re = $dao->getAll($sql1);

// foreach($re as $v){
//     if($v["Tables_in_grape_passport"] != 'task'){
//         $sql = 'truncate '.$v["Tables_in_grape_passport"];

//         echo $sql,"\n";
//         $re = $dao->getAll($sql);
//     }
// }

$cache = Cache::getInstance('REDIS_CONF_USER');

// $list2 = $cache->keys("user:session*");
// foreach($list2 as $v){
//     $cache->delete($v);
// }

$list3 = $cache->keys("USER_CACHE_*");
foreach($list3 as $v){
    $cache->delete($v);
}

// $list4 = $cache->keys("USER:SESSIONKEY*");
// foreach($list4 as $v){
//     $cache->delete($v);
// }


?>