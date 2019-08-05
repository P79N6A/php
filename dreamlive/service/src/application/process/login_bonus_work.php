<?php
/**
 * 答题活动，通过分享码送10元现金
 * User: User
 * Date: 2018/1/30
 * Time: 11:00
 */
class loginBonusWorker
{
    static public $amount   = 10;
    static public $channel  = array('hd001_ios','hd001');
    static public function execute($value)
    {
        $deviceid       = $value["params"]['deviceid'];
        $channel        = $value["params"]['channel'];
        $uid            = $value['params']['uid'];
        Logger::log("login_bonus_log", "false", array("uid" => $uid,'deviceid'=>$deviceid,'channel'=> $channel));
        if(!in_array($channel, self::$channel)) { return true;
        }
        $cache          = Cache::getInstance('REDIS_CONF_CACHE');
        $key            = "login_bonus_deviceid";
        //验证设备号
        if(!$cache->sIsmember($key, $deviceid)) {
            //验证渠道号

            //账变
            $systemid = Account::ANSWER_ACTIVE_ACCOUNT;
            $account        = new DAOAccount($systemid);
            $balance        = $account ->getBalance(Account::CURRENCY_CASH);
            if($balance<10) {
                Logger::log("login_bonus_log", "false", array("uid" => $uid,'systemid'=>$systemid,'balance'=> $balance));
            }
            $dao_gift_log = new DAOGiftLog();
            if(Counter::increase('login_bonus', $uid)>1) { return true;
            }
            try {
                $dao_gift_log->startTrans();
                $orderid  = Account::getOrderId($systemid);
                $type     = Account::TRADE_TYPE_ANSWER_ACTIVE;
                $currency = Account::CURRENCY_CASH;
                $remark   = "通过答题赠送10元";
                $extends  = array('uid'=>$uid,'amount'=>self::$amount);
                Interceptor::ensureNotFalse(Account::decrease($systemid, $type, $orderid, self::$amount, $currency, $remark, $extends),  ERROR_BIZ_PAYMENT_CASH_BALANCE_DUE);
                Interceptor::ensureNotFalse(Account::increase($uid, $type, $orderid, self::$amount, $currency, $remark, $extends), ERROR_BIZ_PAYMENT_CASH_BALANCE_DUE);
                Logger::log("login_bonus_log", "true", array("uid" => $uid,'systemid'=>$systemid,'balance'=> $balance));
                $dao_gift_log->commit();
            } catch (Exception $e) {
                $dao_gift_log->rollback();
                throw $e;
            }
            $cache->sAdd($key, $deviceid);
        }else{
            Logger::log("login_bonus_log", "deviceid is excit false", array("uid" => $uid,'deviceid'=>$deviceid,));
            return true;
        }
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
    $process->addWorker("login_bonus_worker",  array("loginBonusWorker", "execute"),  50, 20000);
    $process->run();
} catch (Exception $e) {
    ;
}
