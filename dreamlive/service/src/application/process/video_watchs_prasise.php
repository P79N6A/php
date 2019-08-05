<?php
// crontab 初始化机器人
// php /home/dream/codebase/service/src/application/process/clean_relict_live_worker.php
// insert into robots(uid,addtime) select uid,addtime from user limit 200000 ;
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


$cache = Cache::getInstance("REDIS_CONF_USER");
$dao_video = new DAOVideo();

$all_video = $dao_video->getAllVideo();
var_dump(count($all_video));

$i = 0;
foreach ($all_video as $video) {
    $videoid = $video['videoid'];
    $praises = Counter::get(Counter::COUNTER_TYPE_VIDEO_PRAISES, $videoid);
    $watches = Counter::get(Counter::COUNTER_TYPE_VIDEO_WATCHES, $videoid);
    $con = $videoid. ',' . $watches. ',' . $praises . "\n";
    file_put_contents('/tmp/video/video_waatch_praise1.txt', $con, FILE_APPEND);
    var_dump($i);
    $i++;

}

exit;