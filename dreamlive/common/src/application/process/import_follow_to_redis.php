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

class Counter extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_COUNTER");
        $this->setTableName("counter");
    }

    public function getList($index, $type)
    {
        $sql = "select relateid from counter_{$index} where type = ? ";
        return $this->getAll($sql, $type);
    }
}

$counter_cache = Cache::getInstance("REDIS_CONF_COUNTER");
$cache = Cache::getInstance("REDIS_CONF_CACHE");

$i = 0;
$counter = new Counter();
while ($i < 100){
    
    $followers = $counter->getList($i, 'followers');
    foreach ($followers as $flo) {
        $num = $counter_cache->get("dreamlive_followers_".$flo['relateid']);
        $cache->zAdd('follower_ranking', $num, $flo['relateid']);
        
        var_dump("counter_". $i. "followers:" . $flo['relateid']);
    }
    
    $following = $counter->getList($i, 'followings');
    foreach ($following as $flow) {
        $num1 = $counter_cache->get("dreamlive_followings_".$flow['relateid']);
        $cache->zAdd('following_ranking', $num1, $flow['relateid']);
    
        var_dump("counter_". $i. "following:" . $flow['relateid']);
    }
    $i++;
}
var_dump("done!");