<?php
/**
 * 检查blocked 数据库和redis是否存在不一致
 */
set_time_limit(0);

$start = microtime(true);
ini_set('memory_limit', '2G');
$ROOT_PATH = dirname(dirname(dirname(dirname(__FILE__))));

set_include_path(get_include_path() . PATH_SEPARATOR . $ROOT_PATH . "/config" . PATH_SEPARATOR . $ROOT_PATH . "/src/www");

require $ROOT_PATH . "/src/www/autoload.php";
require $ROOT_PATH . "/config/server_conf.php";

$stime = date("Y-m-d H:i:s", time() - 600);
$etime = date("Y-m-d H:i:s");

$db = new DAOBlocked();
$sql = "SELECT * FROM blocked WHERE addtime > ? AND addtime < ?";
$sth = $db->query(
    $sql, array(
    $stime,
    $etime
    )
);

while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
    $uid = $row["uid"];
    checkBlocked($db, $uid);
}

function checkBlocked($db, $uid)
{
    $sql = "SELECT bid FROM blocked WHERE uid = ?";
    $list = $db->getAll($sql, $uid);
    $bids = array();
    foreach ($list as $k => $v) {
        $bids[] = $v["bid"];
    }

    $cache = Cache::getInstance("REDIS_CONF_CACHE", $uid);
    
    $_bids = array_keys($cache->hgetall(Blocked::getBlockedKey($uid)));
    if (($bids || $_bids) && array_diff($bids, $_bids)) {
        $cache->del(Blocked::getBlockedKey($uid));
        Logger::log("blocked_err", "cache diff {$uid} bids = " . json_encode($bids) . "\t _bids = " . json_encode($_bids));
    }
}

echo date("Y-m-d H:i:s") . "ok\n";
