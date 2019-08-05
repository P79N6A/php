<?php
// king王者活动计算
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


//$_REQUEST['flag'] = 1; //测试时可以打开此两项， 即删除本月的, 统计本月的. 但实际的测试需要模拟
//$_REQUEST['num'] = 1;//测试时可以打开此两项， 即统计本月一次达到的最高级别. 但实际的测试需要模拟

class kingToUserMedal extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("king");
        $this->setDebug(true);
    }

    public function getList($regdate)
    {
        $sql = "SELECT * FROM " . $this->getTableName() . " where redate like '$regdate%' " ;
        return $this->getAll($sql, $regdate, false);
    }

    public function getListPreMonth($regdate)
    {
        $sql = "SELECT * FROM " . $this->getTableName() . " where redate like '$regdate%' " ;
        return $this->getAll($sql, $regdate, false);
    }
}

$king_medal = new kingToUserMedal();

if($_REQUEST['flag']) {
    //清除指定月的上个月的, 上个月
    $pre_month     = date('Y-m', time()); //当前月
    $current_month = date('Y-m', time()); //当前月
} else {
    //清除指定月的上个月的, 上个月
    $current_month = date('Y-m', strtotime('-1 month')); //上一个月
    $pre_month     = date('Y-m', strtotime('-2 month')); //上上个月
}

$list = $king_medal->getListPreMonth($pre_month); //上个月的数据
//删除上月用户的所有的king
if(is_array($list)) {
    foreach($list as $item){
        UserMedal::delUserMedal($item['uid'], UserMedal::KIND_KING);
        User::reload($item['uid']);
    }
}

$num = 20; //改变此数可以
if($_REQUEST['num']) {
    $num = $_REQUEST['num'];
}


$list = $king_medal->getList($current_month); //当月的数据
//添加指定月的数据
if(is_array($list)) {
    $arr = array();
    foreach($list as $item){
        $arr[$item['uid']][] = $item['level'];
    }
    if(is_array($arr)) {
        $king = array();
        foreach($arr as $key => $item){
            $ret = array_count_values($item);
            $result = array();
            $s = 0;
            for($i=1; $i<9; $i++){
                $s += $ret[$i];
                $result[$i] = $s;
            }
            foreach($result as $key1 => $item1){
                if($item1 >= $num) {
                    $king[$key] = $key1;
                    break;
                }
            }
        }
    }
    print "<pre>";
    print_r($king);
    print "</pre>";
}

$cache = Cache::getInstance('REDIS_CONF_CACHE');
$key  = "king_medal";
$cache->set($key, json_encode($king));

if(is_array($king)) {
    foreach($king as $key => $value){
        UserMedal::addUserMedal($key, UserMedal::KIND_KING, $value);
        User::reload($key);
    }
}
?>
