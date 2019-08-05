<?php
// crontab 每分钟检测
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

$_time      = time() - 90;
$startTime  = date("Y-m-d H:i:s", $_time);
$endTime    = date("Y-m-d H:i:s");

$table      = "loginlog" . "_" .date("Ym", $_time);

$db = new DAOLoginLog();
$sql = "SELECT uid,platform FROM ". $table. " WHERE addtime > ? AND addtime < ?";
$sth = $db->query($sql, array($startTime, $endTime));

while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
    addDau($row["uid"], $row["platform"]);
}

echo $startTime ."|" .  (microtime(true)-$start) . "|ok\n";

function addDau($uid,$platform)
{
    // 新活跃用户及ios写入
    //        $redis_activeuser = Cache::getInstance('ACTIVEUSER_REDIS_CONF_NEW');
    $redis_activeuser = Cache::getInstance('REDIS_CONF_CACHE');
    $key_active = "stats_dream_liveuser_in_today_" . date("Ymd");
    $redis_activeuser->sAdd($key_active, $uid);
    $redis_activeuser->setTimeout($key_active, 86400 * 8);

    // 活跃用户预写入
    $key_active_today = "dream_activeuser_day_prewrite_" . date("Ymd");
    $key_active_1day = "dream_activeuser_day_prewrite_" . date("Ymd", strtotime("+1 day"));
    $key_active_2day = "dream_activeuser_day_prewrite_" . date("Ymd", strtotime("+2 day"));
    $key_active_3day = "dream_activeuser_day_prewrite_" . date("Ymd", strtotime("+3 day"));
    $key_active_4day = "dream_activeuser_day_prewrite_" . date("Ymd", strtotime("+4 day"));
    $key_active_5day = "dream_activeuser_day_prewrite_" . date("Ymd", strtotime("+5 day"));
    $key_active_6day = "dream_activeuser_day_prewrite_" . date("Ymd", strtotime("+6 day"));

    $redis_activeuser->sAdd($key_active_today, $uid);
    $redis_activeuser->sAdd($key_active_1day, $uid);
    $redis_activeuser->sAdd($key_active_2day, $uid);
    $redis_activeuser->sAdd($key_active_3day, $uid);
    $redis_activeuser->sAdd($key_active_4day, $uid);
    $redis_activeuser->sAdd($key_active_5day, $uid);
    $redis_activeuser->sAdd($key_active_6day, $uid);

    $redis_activeuser->setTimeout($key_active_today, 86400 * 8);
    $redis_activeuser->setTimeout($key_active_1day, 86400 * 8);
    $redis_activeuser->setTimeout($key_active_2day, 86400 * 8);
    $redis_activeuser->setTimeout($key_active_3day, 86400 * 8);
    $redis_activeuser->setTimeout($key_active_4day, 86400 * 8);
    $redis_activeuser->setTimeout($key_active_5day, 86400 * 8);
    $redis_activeuser->setTimeout($key_active_6day, 86400 * 8);

    // 新活跃用户zset写入
    $key_active = "zset_stats_dream_liveuser_" . date("Ymd");
    $redis_activeuser->zAdd($key_active, time(), $uid);
    $redis_activeuser->setTimeout($key_active, 86400 * 8);

    // ios
    if ("ios" == $platform) {
        $key_ios_active = "stats_dream_liveuser_ios_in_today_" . date("Ymd");
        $redis_activeuser->sAdd($key_ios_active, $uid);
        $redis_activeuser->setTimeout($key_ios_active, 86400 * 8);
    }

}

