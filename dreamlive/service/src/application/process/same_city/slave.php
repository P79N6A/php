<?
class SameCitySlaveWork
{
    public static function execute($value)
    {
        $min_limit = $value["params"]["min_limit"];
        $max_limit = $value["params"]["max_limit"];
        $taskid    = $value["params"]["taskid"];
        $operation_type = $value["params"]["type"];

        $bucket_name_basic = 'same_city';
        $login_positon_key = 'user_login_position_';

        $data = $arrKey = $position_list = $lng = $lat = array();

        $cache = Cache::getInstance("REDIS_CONF_COUNTER");
        $tmp_elements = $cache->zRevRangeByScore("dreamlive_online_users_redis_key", PHP_INT_MAX, 0, ['withscores' => true, 'limit' => [$min_limit, $max_limit]]);

        $elements = self::delXs($tmp_elements);

        $online_users = array_keys($elements);
        foreach($online_users as $uid){
            array_push($arrKey, $login_positon_key.$uid);
        }

        //用户位置获取
        $list = Cache::getInstance("REDIS_CONF_USER")->mget($arrKey);
        foreach($list as $val){
            if($val){
                $v = json_decode($val, true);
                if($v['lng'] && $v['lat']){
                    $position_list[$v['uid']] = $v['province'];
                }else{
                    $position_list[$v['uid']] = "其他";
                }

                $lng[$v['uid']] = $v['lng'];
                $lat[$v['uid']] = $v['lat'];
            }
        }

        $bucket_score_list = self::getElementScore($elements);//分数计算

        foreach($online_users as $uid){
            //判断隐藏用户
            if(Cache::getInstance("REDIS_CONF_USER")->sIsMember("dreamlive_users_city_hidden", $uid)){
                continue;
            }
            if(empty($position_list[$uid])){
                $position_list[$uid] = '其他';
            }
            $user_city = $position_list[$uid];
            $data[$user_city][] = array(
                'relateid' => $uid,
                'type' => Feeds::FEEDS_USER,
                'score' => $bucket_score_list[$uid],
                'extends' => json_encode(array('liveid'=>$elements[$uid], "lng"=>$lng[$uid], "lat"=>$lat[$uid])),
            );
        }

        $city_list = array_unique($position_list);
        foreach($city_list as $city){
            try{
                self::bucketOperation($bucket_name_basic.'_'.$city);
                self::bucketOperation($bucket_name_basic.'_'.$city.'_'.$operation_type, $data[$city]);
            }catch(Exception $e){
                sleep(5);
                self::bucketOperation($bucket_name_basic.'_'.$city);
                self::bucketOperation($bucket_name_basic.'_'.$city.'_'.$operation_type, $data[$city]);
            }
        }

        Cache::getInstance("REDIS_CONF_COUNTER")->incr("dreamlive_same_city_task_lock_{$taskid}");
        $ttl = Cache::getInstance("REDIS_CONF_COUNTER")->ttl("dreamlive_same_city_task_lock_{$taskid}");
        if($ttl<0){
            Cache::getInstance("REDIS_CONF_COUNTER")->del("dreamlive_same_city_task_lock_{$taskid}");
        }

        return true;
    }

    public static function getElementScore($onlineUsers)
    {
        $addiences = array_keys($onlineUsers);
        $liveids   = array_values($onlineUsers);

        $user = new User();
        $userinfos = $user->getUserInfos($addiences);
        $liveinfos = self::getLiveInfoByIds($liveids);

        $score_list = [];
        foreach ($onlineUsers as $key => $liveid) {
            $score_list[$key] = 0;

            $liveinfo = !empty($liveinfos[$liveid]) ? $liveinfos[$liveid] : array();
            $userinfo = $userinfos[$key];

            if ($key == $liveinfo['uid']) {
                $score_list[$key] += 100;
            }

            if(!empty($userinfo['medal'])){
                foreach($userinfo['medal'] as $medal){
                    if($medal['kind']=='vip'){
                        $score_list[$key] += $medal['medal'];
                    }
                }
            }
        }

        return $score_list;
    }

    public static function getLiveInfoByIds($liveids)
    {
        if (! $liveids) {
            return array();
        }

        if (! is_array($liveids)) {
            $liveids= array(
                $liveids
            );
        }

        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        foreach ($liveids as $id) {
            $keys[] = "L2_cache_live_".$id;
        }
        $results = $cache->mget($keys);

        $liveinfos = [];
        foreach ($results as $row) {
            if ($row) {
                $liveinfo = json_decode($row, true);
                $liveinfo["L2_cached"] = true;

                if (strpos($liveinfo['cover'], 'http://') === false) {
                    $liveinfo['cover'] = STATIC_DOMAIN_NAME. $liveinfo['cover'];
                }

                $liveinfo['watches'] = Counter::get(Counter::COUNTER_TYPE_LIVE_WATCHES, $liveinfo['liveid']);
                $liveinfos[$liveinfo['liveid']] = $liveinfo;
            }
        }

        return $liveinfos;
    }

    public static function bucketOperation($bucket_name, $data=array())
    {
        $bucket = new Bucket();
        $bucket_element = new BucketElement();

        try{
            $bucket_info = $bucket->getBucket($bucket_name);
            if(empty($bucket_info)){
                $bucket->setBucket($bucket_name, 0, 'Y', '');
            }
            if(!empty($data)){
                $bucket_element->import($bucket_name, $data);
            }

            return true;
        }catch(Exception $e){
            sleep(5);
            //TODO 报警
            try{
                $bucket_info = $bucket->getBucket($bucket_name);
                if(empty($bucket_info)){
                    $bucket->setBucket($bucket_name, 0, 'Y', '');
                }
                if(!empty($data)){
                    $bucket_element->import($bucket_name, $data);
                }

                return true;
            }catch (Exception $e){
                echo $e->getMessage();
            }
        }
    }

    public static function delXs($elements)
    {
        $feed = new Feeds();
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $big_live_key_set = "big_liver_keys_set";

        $liveids = $tmp_liveids = array();
        foreach($elements as $key=>$val){
            $tmp_liveids[] = $val;
        }
        $liveids = array_unique($tmp_liveids);

        $arrKey = array();
        foreach ($liveids as $item) {
            if(!empty($item)){
                $key = "L2_cache_live_" . $item;
                array_push($arrKey, $key);
            }
        }
        //批量读取redis数据
        $cache = Cache::getInstance("REDIS_CONF_CACHE", 0);
        $feed_list  = $cache->mget($arrKey);

        $xs_liveids = array();
        foreach($feed_list as $val){
            $live_info = json_decode($val, true);
            if($cache->zScore($big_live_key_set, $live_info['uid'])){
                $xs_liveids[] = $live_info['liveid'];
            }
        }
        foreach($elements as $key=>$val){
            if(in_array($val, $xs_liveids)){
                unset($elements[$key]);
            }
        }

        return $elements;
    }

}

set_time_limit(0);
ini_set('memory_limit', '1G');
ini_set("display_errors", "On");
error_reporting(E_ALL & ~ E_NOTICE & ~ E_WARNING);
$ROOT_PATH = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
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
require_once ('process_client/ProcessClient.php');
try {
    $process = new ProcessClient("dream");
    $process->addWorker("same_city_slave", array("SameCitySlaveWork","execute"), 2, 20000);
    $process->run();
} catch (Exception $e) {
    ;
}


// $value["params"]["min_limit"] = 0;
// $value["params"]["max_limit"] = 100;
// $value["params"]["taskid"] = 8;
// $value["params"]["type"] = 'a';

// SameCitySlaveWork::execute($value);
