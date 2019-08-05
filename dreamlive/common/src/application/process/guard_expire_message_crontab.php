<?php
// crontab 每分钟检测
ini_set('memory_limit', '2G');
ini_set("display_errors", "On");
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

$ROOT_PATH = dirname(dirname(dirname(dirname(__FILE__))));
$LOAD_PATH = array(
    $ROOT_PATH.'/src/www',
    $ROOT_PATH.'/config',
    $ROOT_PATH.'/src/application/controllers',
    $ROOT_PATH.'/src/application/models',
    $ROOT_PATH.'/src/application/models/libs',
    $ROOT_PATH.'/src/application/models/dao'
);
set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $LOAD_PATH));

function __autoload($classname)
{
    $classname = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
    include_once "$classname.php";
}

require $ROOT_PATH . '/config/server_conf.php';

$start_time = date('Y-m-d H:i:s');
$end_time = date('Y-m-d H:i:s', strtotime("$start_time +3 day"));

$db = new DAOUserGuard();
$sql = "SELECT * FROM user_guard WHERE msg_status = 0 AND endtime BETWEEN '$start_time' AND '$end_time' LIMIT 100";
$ret = $db->query($sql, array(), false);

$user = new User();
while($row = $ret->fetch(PDO::FETCH_ASSOC)){
    
    $curtime = time();
    $type = ($row['type'] == 10) ? '年' : '月';
    $etime = strtotime($row['endtime']); //结束时间转换时间戳
    $userinfo = $user->getUserInfo($row['relateid']); //获取主播信息
    
    $day_1 = 86400;
    $day_2 = 2 * 86400;
    $day_3 = 3 * 86400;
    $st = $etime - $curtime;
    
    //一天提醒
    if($st > 0 && $st <= $day_1) {
        $content = "您在【{$userinfo['nickname']}】房间购买的{$type}守护，还有一天就到期啦，赶紧续费吧！"; 
        
        //发送提醒消息
        if(Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $row['uid'], '守护', $content, 0)) {
            $sql = "UPDATE user_guard SET msg_status = 1 WHERE id = '{$row['id']}'";
            $db->query($sql, array(), false);
        }
    }
    
    //三天提醒
    if($st > $day_2 && $st <= $day_3) {
        $content = "您在【{$userinfo['nickname']}】房间购买的{$type}守护，还有三天就到期啦，赶紧续费吧！";
        
        //发送提醒消息
        if(Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $row['uid'], '守护', $content, 0)) {
            $sql = "UPDATE user_guard SET msg_status = 1 WHERE id = '{$row['id']}'";
            $db->query($sql, array(), false);
        }
    }
    

}
