<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/5
 * Time: 19:01
 * 修复游戏土豪
 */

if (substr(php_sapi_name(), 0, 3) !== 'cli') { die("cli mode only");
}

set_time_limit(0);
ini_set('memory_limit', '2G');
ini_set("display_errors", "On");
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
$ROOT_PATH = dirname(dirname(dirname(dirname(__FILE__))));

$LOAD_PATH = array(
    $ROOT_PATH . "/src/www",
    $ROOT_PATH . "/config",
    $ROOT_PATH . "/src/application/controllers",
    $ROOT_PATH . "/src/application/models",
    $ROOT_PATH . "/src/application/models/libs",
    $ROOT_PATH . "/src/application/models/dao",
    $ROOT_PATH . "/../",
);
set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $LOAD_PATH));

function __autoload($classname)
{
    $classname = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
    include_once "$classname.php";
}

require $ROOT_PATH . "/config/server_conf.php";

if (!isset($argv[1])) { die("need a param");
}
$do = $argv[1];

function println($msg)
{
    if ($msg) {
        echo $msg . "\n";
    } else {
        echo "\n";
    }
}

function mylog($msg, $log = '/tmp/tool.log')
{
    $line = "";
    if (is_string($msg)) {
        $line = $msg;
    } elseif (is_array($msg) || is_object($msg)) {
        $line = json_encode($msg);
    }
    $line = date("[Ymd/H:i:s]") . $line;
    if (!file_exists($log)) {
        touch($log);
    }
    file_put_contents($log, $line . "\n\n", FILE_APPEND);
}

function logResult($uid)
{
    $f = '/tmp/tuhao2.log';
    if (!file_exists($f)) { touch($f);
    }
    $c = file_get_contents($f);
    if (!$c) {
        $cjson = [];
    } else {
        $cjson = json_decode($c, true);
    }

    if (in_array($uid, $cjson)) { throw new Exception("uid=" . $uid . " repeat");
    }
    array_push($cjson, $uid);
    file_put_contents($f, json_encode($cjson));
}

function getResult()
{
    $f = '/tmp/tuhao2.log';
    if (file_exists($f)) {
        $c=file_get_contents($f);
        if ($c) {
            $json=json_decode($c, true);
            $total=count($json);
            print_r("\n".json_encode(array('total'=>$total))."\n");
        }
    }
}

if ($do == 'do') {
    println("starting ...");
    $user_medal_dao = new DAOUserMedal();
    $rich = $user_medal_dao->getALL("select uid from user_medal where kind=?", ['kind'=>'tuhao']);
    foreach ($rich as $i) {
        try {
            $account_dao = new DAOJournal($i['uid']);
            $index = $i['uid'] % 100;
            $journal = $account_dao->getRow("select count(amount) as num from journal_" . $index . " where direct='OUT' and currency=2 and type=18 and uid=? and UNIX_TIMESTAMP(addtime)<UNIX_TIMESTAMP('2017-05-25')", ['uid'=>$i['uid']]);
            if (!$journal || $journal['num'] <= 0) { continue;
            }
            $num=Counter::get(Counter::COUNTER_TYPE_CONSUME_MONEY, $i['uid']);
            if (!$num||$num<=$journal['num']) { continue;
            }
            Counter::decrease(Counter::COUNTER_TYPE_CONSUME_MONEY, $i['uid'], $journal['num']);
            logResult($i['uid']);
        } catch (Exception $e) {
            mylog(
                [
                'uid'=>$i['uid'],
                'e'=>$e,
                ]
            );
        }

    }
    println("ending ... ");
    getResult();
}
