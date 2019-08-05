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

require_once 'message_client/RongCloudClient.php';
$rongcloud_client = new RongCloudClient();

$dao_live = new DAOLive();
$sql = "select distinct(uid) as uid from live where  (UNIX_TIMESTAMP(endtime) - UNIX_TIMESTAMP(addtime) ) >= 3600 order by uid asc ";
$all_uids = $dao_live->getAll($sql);
$i = 1;
foreach ($all_uids as $info) {
    
    $userid = $info['uid'];
    $content = "亲爱的主播，今晚开始是三天的周末，根据过去经验，我们平台新增人数会翻倍，同时人流量会增加一倍，也代表你的收入可能翻倍，追梦邀请你今天开始周五、周六、周日三天按时播出，保障晚上播出时长，这样你的收入也会很客观，会等比增加，我们一起努力，增加我们的粉丝和收入吧。";
    Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $userid, $content, $content, 0);
    
    var_dump($userid. "----" . $i);
    $i++;
    
}


?>