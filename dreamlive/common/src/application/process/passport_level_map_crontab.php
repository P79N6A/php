<?php
// crontab 每天检测
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

$dao_user_level = new DAOUserLevelMap();
$maxLevel = 701;
for($level = 1;$level<$maxLevel;$level++){
    $startexp = UserExp::getExpByLevel($level);
    $endexp = UserExp::getExpByLevel($level+1);
    $count = 0;
    for($i = 0;$i<100;$i++){
        $dao_user_exp = new DAOUserExp($i);
        $result = $dao_user_exp->getCountUserBetweenExp($startexp, $endexp);

        $count += $result['count'];
    }
    $info = array(
        "level"=>$level,
        "headcount"=>$count,
    );

    $dao_user_level->replace("user_level_map", $info);
}

?>
