<?php
// nohup php /home/yangqing/work/ptshare/service/src/application/process/user_renting_expire_crontab.php > user_renting_expire_crontab.log 2>&1 &
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

$startime = date('Y-m-d H:i:s');
$endtime  = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . " +4 day"));

echo "脚本执行开始:" . date("Y-m-d H:i:s") . "\n";

$message = new Message();
$cache   = Cache::getInstance("REDIS_CONF_CACHE");
$DAOUserRenting = new DAOUserRenting();
$list = $DAOUserRenting->getUserRentingExpireFinish($startime, $endtime);
foreach ($list as $item) {
    $key = "user_renting_send_massage_" . $item['uid'] . "_" . $item['packageid'];
    if (! $cache->EXISTS($key)) {
        $result = $message->sendMessage(DAOMessage::TYPE_ORDER_RENT_SOON_FINISH);
        $cache->add($key, $item['packageid'], 86400 * 7);
    }
}

echo "脚本执行结束:" . date("Y-m-d H:i:s");
echo "\n\n\n\n\n\n\n\n\n\n\n";


