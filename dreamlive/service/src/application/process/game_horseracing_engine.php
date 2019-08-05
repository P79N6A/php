<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/5
 * Time: 18:49
 * 跑马游戏引擎
 */
set_time_limit(0);
ini_set('memory_limit', '1G');
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

require $ROOT_PATH."/config/server_conf.php";


class GameEngine
{
   

    private $debug=false;

    public function __construct()
    {
        $this->debug=isset($argv[1])&&$argv[1]=='debug'?true:false;
    }

    public function run()
    {
        try{
            //插入一场游戏

        }catch (Exception $e){

        }finally{

        }
    }


}


$counter_cache= Cache::getInstance("REDIS_CONF_COUNTER");
$receive_gift_keys=$counter_cache->keys("dreamlive_receive_gift_*");
if (file_exists("./ticket.log")) {
    unlink("./ticket.log");
}
print_r("starting ...\n");
foreach ($receive_gift_keys as $i){
    $uid=str_replace("dreamlive_receive_gift_", "", $i);
    $uid=is_numeric($uid)?$uid:0;
    if($uid) {
        $journal_dao=new DAOJournal($uid);
        $num=$journal_dao->getReceivedTicketsByUid($uid);
        $num=$num==null?0:$num;
        $cache_num=$counter_cache->get($i);
        if ($num!=$cache_num) {
            print_r($i."##");
            if ($debug) {
                print_r("db=".$num.';ca='.$cache_num." \n");
                continue;
            }
            if ($num>=0) {
                if($num>$cache_num) {
                    $r=$counter_cache->set($i, $num);
                    print_r($r);
                }else{
                    file_put_contents("./ticket.log", $i."##db=".$num.';ca='.$cache_num." \n", FILE_APPEND);
                }

            }
            print_r("\n");
        }

    }

}
print_r("over .. \n");

