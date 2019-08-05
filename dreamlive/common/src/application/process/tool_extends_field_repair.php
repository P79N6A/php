<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/5
 * Time: 19:01
 * 修复etends字段
 */

if (substr(php_sapi_name(), 0, 3) !== 'cli') { die("cli mode only");
}

set_time_limit(0);
ini_set('memory_limit', '2G');
ini_set("display_errors", "On");
error_reporting(E_ALL & ~E_NOTICE);
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
$params['do'] = $argv[1];
$params['done'] = $argv[2];

function println($msg)
{
    if ($msg) {
        echo $msg . "\n";
    } else {
        echo "\n";
    }
}

function mylog($msg, $star = false)
{
    $line = "";
    if (is_string($msg)) {
        $line = $msg;
    } elseif (is_array($msg)) {
        $line = json_encode($msg);
    }

    $f = "./extends_data.log";
    if ($star) {
        $f = "./extends_data_star.log";
    }
    if (!file_exists($f)) {
        touch($f);
    }
    file_put_contents($f, $line . "\n\n", FILE_APPEND);
}

/*====================================星钻=========================*/
//修复送礼
if ($params['do']) {
    foreach (range(1, 99) as $i) {
        $t = "journal_" . $i;
        println($t);
        $journal_dao = new DAOCom($t);
        $journal_all = $journal_dao->getAll("select * from " . $t . " where type=?", ['type' => 3]);
        $giftlog = new DAOGiftLog();
        foreach ($journal_all as $j) {
            print_r($j);
            $giftloginfo = $giftlog->getInfo($j['orderid']);
            if (empty($giftloginfo)) { continue;
            }
            if ($giftloginfo) {
                $extends = array(
                    "sender" => $giftloginfo['sender'],
                    "receiver" => $giftloginfo['receiver'],
                    "giftid" => $giftloginfo['giftid'],
                    "price" => $giftloginfo['price'],
                    'ticket' => $giftloginfo['ticket'],
                    'num' => $giftloginfo['num'],
                    'liveid' => $giftloginfo['liveid'],
                );
                $update = $journal_dao->update($t, ['extends' => json_encode($extends)], "id=?", $j['id']);
                //print_r($update,$giftloginfo);
                if (!$update) { mylog(["table" => $t, 'type' => $j['type'], "ext" => $extends]);
                }
            }
        }
    }
}
//飞屏幕
//守护
//跑马
//门票
//做任务
/*====================================星光=========================*/
if ($params['done']) {
    foreach (range(0, 99) as $i) {
        $t = "star_journal_" . $i;
        println($t);
        $journal_dao = new DAOCom($t);
        $journal_all = $journal_dao->getAll("select * from " . $t . " where type=2");
        foreach ($journal_all as $j) {
            $giftstarlog = new DAOGiftStarLog();
            $info = $giftstarlog->getRow("select * from giftstarlog where orderid=?", ['orderid' => $j['orderid']]);
            if (empty($info)) { continue;
            }
            if ($info) {
                $gd = new DAOGift();
                $gift = $gd->getInfo($info['giftid']);
                $extends = array(
                    'sender' => $info['sender'],
                    'receiver' => $info['receiver'],
                    'giftid' => $info['giftid'],
                    'price' => $info['price'],
                    'ticket' => $info['ticket'],
                    'num' => $info['num'],
                    'liveid' => $info['liveid'],
                    'gift_name' => $gift ? $gift['name'] : "",
                );
                $update = $journal_dao->update($t, ['extends' => json_encode($extends)], "id=?", $j['id']);
                if (!$update) { mylog(["table" => $t, 'type' => $j['type'], "ext" => $extends], true);
                }
            }
        }
        //usleep(100);
    }
}
//送礼
//做任务
//跑马