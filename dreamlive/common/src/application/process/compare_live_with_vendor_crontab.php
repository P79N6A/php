<?php
//nohup php /home/yangqing/work/dreamlive/service/src/application/process/import_sendgift_to_redis.php > import_task_log.log 2>&1 &
//禁止测试环境、开发机执行
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

echo  "脚本执行开始:".date('Y-m-d H:i:s');echo "\n";

$str = "";
$WsLive   = new WsLive();
$result = Wcs::getOnlineLive();
$count  = $result['count'];
$watchLiveStatistic = new watchLiveStatistic();
foreach($result['dataValue'] as $item){
    $path = str_replace(array('cn01.flv.dreamlive.tv','cn01.flv.dreamlive.tv','cn01.hls.dreamlive.tv','cn01.hls.dreamlive.tv'), '', $item['prog']);
    $rtmp = 'rtmp://cn01.push.dreamlive.tv' . $path;
    $sn = str_replace(array('cn01.flv.dreamlive.tv/live/','cn01.flv.dreamlive.tv/replay/','cn01.hls.dreamlive.tv/live/','cn01.hls.dreamlive.tv/replay/'), '', $item['prog']);

    $zid = $WsLive->isExist($sn, 'ws');
    if (! $zid || $item['value'] > $count * 0.9) {
        $ret = Wcs::stopOnlineLive($rtmp);
        if($ret['code']=='00') {
            echo 'stop success!  sn='.$sn.'    rtmp='.$rtmp.'  uid='.$zid;echo "\n";
        }else{
            echo 'stop error!  sn='.$sn.'    rtmp='.$rtmp.'  uid='.$zid.'  result='.json_encode($ret);echo "\n";
        }
        continue;
    }

    $str .= "('" . date("Y-m-d H:i").":00" . "'," . $zid. "," . $item['value'] . "),";
}
$str = substr($str, 0, strlen($str) - 1);
$watchLiveStatistic->addBatch($str);

echo  "脚本执行结束:".date('Y-m-d H:i:s');echo "\n\n\n\n\n\n\n";




