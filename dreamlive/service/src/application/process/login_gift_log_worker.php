<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018/1/4
 * Time: 11:07
 */
class loginGiftLogWorker
{
    static public function execute($value)
    {
        $uid            = $value["params"];
        $gift           = new DAOGift();
        $login_gift     = new DAOLoginGift();
        $gifts          = $login_gift->getAllGifts();
        if($gifts) {
            foreach($gifts as $v){
                $gift_info  = $gift->getInfo($v['giftid']);
                $send_gift[$v['day']]    = array(
                    'giftid'    => $v['giftid'],
                    'num'       => $v['num'],
                    'name'      => $gift_info['name']
                );
                unset($gift_info);
            }
        }
        Bag::loginSendGift($uid, $send_gift);
        return true;
    }
}

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
require_once 'process_client/ProcessClient.php';

try {
    $process = new ProcessClient("dream");
    $process->addWorker("login_gift_log_worker",  array("loginGiftLogWorker", "execute"),  50, 20000);
    $process->run();
} catch (Exception $e) {
    ;
}
