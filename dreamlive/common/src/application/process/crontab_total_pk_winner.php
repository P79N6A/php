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

$rank   = new Rank();
$match  = new DAOMatchs();
$member = new DAOMatchMember();
$list   = $match->getAllMatchList();
if($list) {
    foreach($list as $val){
        $config     = json_decode($val['config'], true);
        $winner     = $config[$val['duration']]['winner_line'];
        $member_info    = $member -> getMaxscore($val['matchid']);
        if($member_info['score']>$winner) {
            $arr = array(21000475,21000480,21000468,21000369);
            $member_info['uid'] = array_rand($arr);
            $rank->setRank('matchwinnernum', 'increase', $member_info['uid'], 1);
        }
    }
}
