<?php
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

class GiftLog extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("giftlog");
    }

    public function getReceiveGiftList()
    {
        $sql = "SELECT SUM(num*price) as sum,receiver from giftlog where consume='DIAMOND' group by receiver ORDER BY sum DESC";
        return $this->getAll($sql);
    }
    
    public function getSendGiftList()
    {
        $sql = "SELECT SUM(num*price) as sum,sender from giftlog where consume='DIAMOND' group by sender ORDER BY sum DESC";
        return $this->getAll($sql);
    }
}


$cache = Cache::getInstance("REDIS_CONF_CACHE");
$counter_cache = Cache::getInstance("REDIS_CONF_COUNTER");

$gift_log = new GiftLog();

$receive_gifts = $gift_log->getReceiveGiftList();
var_dump("=====".count($receive_gifts) . "=====");
$i= 0;
foreach ($receive_gifts as $receive_gift) {
    $receive_score = $counter_cache->get('dreamlive_receive_gift_'.$receive_gift['receiver']);
    if ($receive_score != $receive_gift['sum']) {
        $cache->zAdd('receivegift_ranking', $receive_score, $receive_gift['receiver']);
        
        file_put_contents('/tmp/receive_gift2.log', $receive_gift['receiver']. '===' . $receive_gift['sum'] . "====" . $receive_score. "\n", FILE_APPEND);
        var_dump('====='.$receive_gift['receiver'].'----'. $receive_gift['sum'] . "=====");
        $i++;
    } else {
        $cache->zAdd('receivegift_ranking', $receive_gift['sum'], $receive_gift['receiver']);
        //var_dump('===========');
    }
    
}
var_dump("diff===={$i}");
usleep(100);exit;
$sender_gifts = $gift_log->getSendGiftList();
var_dump("=====".count($sender_gifts) . "=====");
$j = 0;
foreach ($sender_gifts as $sender_gift) {
    $send_score = $cache->get('dreamlive_send_gift_'.$sender_gift['sender']);
    if ($send_score != $sender_gift['num']) {
        //$cache->zAdd('sendgift_ranking', $sender_gift['sum'], $sender_gift['sender']);
        file_put_contents('/tmp/sendgit_gift.log', $sender_gift['sender'] . "\n", FILE_APPEND);
        var_dump('===='.$sender_gift['sender'].'----'. $sender_gift['sum']. '=====');
        $j++;
    } else {
        $cache->zAdd('sendgift_ranking', $sender_gift['sum'], $sender_gift['sender']);
        var_dump('===='.$sender_gift['sender'].'----'. $sender_gift['sum']. '=====');
        var_dump('===========');
    }
}
var_dump("diff===={$j}");
var_dump("done!");