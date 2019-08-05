<?php
set_time_limit(0);

$ROOT_PATH = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
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
require_once "process_client/ProcessClient.php";
require_once "dream_client/DreamClient.php";
function getOperationType($taskid)
{
    if($taskid%2==0) {
        return "b";
    }

    return "a";
}

echo " +++++++++++++++++++++++++++++++++++++ ", date("Y-m-d H:i:s"), " START ", "\n";

//cache
$_TASK         = "dreamlive_same_city_task";
$_TASK_LOCK    = "dreamlive_same_city_task_lock_"; //dreamlive_same_city_task_lock_1

$_CITY_LIST = ['北京','天津','河北','山西','内蒙古','辽宁','吉林','黑龙江','上海','江苏','浙江','安徽','福建','江西','山东','河南','湖北','湖南','广东','广西','海南','重庆','四川','贵州','云南','西藏','陕西','甘肃','青海','宁夏','新疆','台湾','香港','澳门','其他'];

$_BUCKET_NAME_BASIC = 'same_city';
$_MAX_EXEC_TIME = 60; //执行时间60s

$cache = Cache::getInstance("REDIS_CONF_COUNTER");
//任务id
$taskid = $cache->get($_TASK);
echo "last task id：", $taskid, "\n";
if(!$taskid) {
    $taskid = $cache->incr($_TASK);
}

echo "判断 上条任务状态 ";
if($cache->get($_TASK_LOCK.$taskid) != "success") {
    $ttl = $cache->ttl($_TASK_LOCK.$taskid);
    if(86400 - $ttl < $_MAX_EXEC_TIME) {
        echo "task id：{$taskid} 未完成", "\n";
        exit;
    }
}

$taskid = $cache->incr($_TASK);
$_LOCK = $_TASK_LOCK.$taskid;

$cache->setex($_LOCK, 86400, 0);
echo "创建新任务id：", $taskid, "\n";

$online_count = Cache::getInstance("REDIS_CONF_COUNTER")->ZCARD("dreamlive_online_users_redis_key");
if(empty($online_count)) {
    echo "取在线用户 online_count is null \n";
    exit;
}
//判断当前 ab桶 奇数 a 偶数b
$operation_type = $taskid%2 == 0 ? 'b': 'a';

echo "本次 type {$operation_type } \n";
//取城市列表 清空相应 的城市 ab桶
// $city_list = $cache->smembers($_CITY_LIST);
$bucket_element = new BucketElement();
if($_CITY_LIST) {
    foreach($_CITY_LIST as $city){
        $bucket_element->deleteAll($_BUCKET_NAME_BASIC.'_'.$city.'_'.$operation_type);
    }
}
echo "clean end \n";

//分发任务
echo "分发任务 \n";
$page = 0;
$limit = 3000;
$page_total = ceil($online_count/$limit);

while($page<$page_total){
    $data = array(
        'min_limit' =>$page*$limit,
        'max_limit' =>$page*$limit+$limit,
        'taskid' => $taskid,
        'type' => $operation_type
    );
    echo json_encode($data)," \n";
    $process = new ProcessClient("dream");
    $process->addTask("same_city_slave", $data);
    $page++;
}

//完成任务 扳动开关
$taskRatio = 0.5;
$worker_execute_time = 0;
while(true){
    $over_count = Cache::getInstance("REDIS_CONF_COUNTER")->get($_LOCK);
    echo "统计：", $over_count ," \n";
    if($page_total==$over_count || ($worker_execute_time>=50)) {
        if($over_count/$page_total > $taskRatio) {
            echo "setForward"," \n";
            foreach($_CITY_LIST as $city){
                try{
                    $bucket = new Bucket();
                    $bucket->setForward($_BUCKET_NAME_BASIC.'_'.$city, $_BUCKET_NAME_BASIC.'_'.$city.'_'.$operation_type);
                }catch (Exception $e){
                    sleep(2);
                    $bucket = new Bucket();
                    $bucket->setForward($_BUCKET_NAME_BASIC.'_'.$city, $_BUCKET_NAME_BASIC.'_'.$city.'_'.$operation_type);
                }
            }

            Cache::getInstance("REDIS_CONF_COUNTER")->set($_LOCK, "success");

            echo "任务结束"," \n";
            break;
        }else{
            echo "setForward 完成度不足50% 不切换"," \n";
            echo "任务结束"," \n";
            exit;
        }
        
    }
    sleep(5);
    $worker_execute_time = $worker_execute_time+5;
}

echo "刷新城市列表"," \n";
foreach($_CITY_LIST as $city){
    if($city == '其他') {
        continue;
    }
    $param[] = "'".$_BUCKET_NAME_BASIC.'_'.$city.'_'.$operation_type."'";
}
$str = implode(',', $param);
// $sql = "SELECT bucket_name,count(*) as total FROM `bucket_element` where bucket_name in({$str}) and score>99 group by bucket_name;";
$sql = "SELECT bucket_name,count(CASE WHEN score>99 THEN score END ) as total FROM `bucket_element` where bucket_name in({$str}) group by bucket_name";
// echo $sql, "\n";
$dao_bucket_element = new DAOBucketElement();
$totals = $dao_bucket_element->getAll($sql, null, false);

$others = $notOthers = $importData = array();
$cityconfig = array(
    'hot' => array(),
    'other' => array(),
);

foreach($totals as $v){
    $cityname = substr($v['bucket_name'], 0, strlen($v['bucket_name'])-2);
    $cityname = substr($cityname, 10);
    
    if($v['total']>=2) {
        $cityconfig['hot'][] = $cityname;
        $notOthers[] = $cityname;
    }elseif($v['total']>=0) {
        $cityconfig['other'][] = $cityname;
        $notOthers[] = $cityname;
    }
}
$cityconfig['other'][] = "其他";

echo json_encode($cityconfig), "\n";
echo "通知后台 刷新城市列表"," \n";
$process = new ProcessClient("dream");
$process->addTask("same_city_refresh_list", $cityconfig);

foreach($_CITY_LIST as $city){
    if($city == '其他') {
        continue;
    }
    if(!in_array($city, $notOthers)) {
        $others[] = $city;
    }
}

if($others) {//其他
    foreach ($others as $k => $v) {
        $next = 0;
        $num = 3000;
        while(true){
            $bucketElement = new BucketElement();
            list($list, $next) = $bucketElement->fetch($_BUCKET_NAME_BASIC.'_'.$v.'_'.$operation_type, $next, $num, DAOBucketElement::PADING_LIMIT);
            if(!$list) {
                break;
            }
            foreach ($list as $k => $v) {
                $importData[] = array(
                    'relateid' => $v['relateid'],
                    'type' => 5,
                    'score' => $v['score'],
                    'extends' => json_encode($v['extends']),
                );
            }
            $bucketElement->import($_BUCKET_NAME_BASIC.'_其他_'.$operation_type, $importData);
        }
    }
}


echo " +++++++++++++++++++++++++++++++++++++ ", date("Y-m-d H:i:s"), " END ", "\n";