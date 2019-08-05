<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2017/6/15
 * Time: 11:27
 */
set_time_limit(0);
ini_set('memory_limit', '1G');
ini_set("display_errors", "On");
error_reporting(E_ALL & ~ E_NOTICE & ~ E_WARNING);
$ROOT_PATH = dirname(dirname(dirname(dirname(__FILE__))));
$LOAD_PATH = array(
    $ROOT_PATH . "/src/www",
    $ROOT_PATH . "/config",
    $ROOT_PATH . "/src/application/controllers",
    $ROOT_PATH . "/src/application/models",
    $ROOT_PATH . "/src/application/models/libs",
    $ROOT_PATH . "/src/application/models/dao"
);
set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $LOAD_PATH));

function __autoload($classname)
{
    $classname = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
    include_once "$classname.php";
}

require $ROOT_PATH . "/config/server_conf.php";
require_once 'process_client/ProcessClient.php';


$arr = array(20042060,
11098934,
20133278,
10938886,
20237187,
20139074,
11166882,
11181926,
20103310,
11175108,
11110854,
11114890,
20267956,
10260084,
10029232,
11149507,
20098499,
20056990,
10026460,
11157759,
20112546,
11052983,
20064496,
20060997,
20314952,
20104506
);
foreach($arr as $v){
    $journal    = new DAOJournal($v);
    $id         = $v%100;
    $sql    = "SELECT SUM(amount) FROM `journal_{$id}` WHERE uid=? AND currency=1 AND direct='IN'";
    $num    = $journal -> getOne($sql, $v);
    $redis_num  = Counter::gets('receive_gift', (array)$v);
    if($redis_num!=$num) {
        file_put_contents(dirname(__FILE__)."/redisticket.txt", "num:{$num}-redis:{$redis_num[$v]}-UID:{$v}\r\n", FILE_APPEND);
    }
}

