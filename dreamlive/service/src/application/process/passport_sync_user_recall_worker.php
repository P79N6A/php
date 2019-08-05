<?php
ini_set("display_errors", "On");
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
class UserRecallWorker
{
    public static function execute($value)
    {
        $userid   = $value["params"]["uid"];
        $deviceid = $value["params"]["deviceid"];
        $rid      = $value["params"]["rid"];

        $startime = '2018-03-03';
        $diamond = 10;
        $oldDiamond = 10;

        try{
            //1 访问过分享
            if(!$userid) {
                return true;
            }
            if($rid) {
                if(!Counter::get("user_recall", $rid)) {
                    return true;
                }
            }else{
                if(!Counter::get("user_recall", $userid)) {
                    return true;
                }
            }
            
            //2 deviceid uid 未领过奖金
            $daoRecall = new DAOUserRecall();
            if($daoRecall->isUsedUid($userid)||$daoRecall->isUsedDeviceid($deviceid)) {
                Logger::log("recall_err", "worker", array("uid" => $userid,"deviceid"=>$deviceid,"errmsg" => "已经领过奖金"));
                return true;
            }
            //3 发奖
            if($rid) {
                //手机号登录 如果存在Invitee 即使未领奖 也算失败
                if($daoRecall->existsInvitee($userid)) {
                    Logger::log("recall_err", "worker", array("uid" => $userid,"deviceid"=>$deviceid,"errmsg" => "Invitee已存在"));
                    return true;
                }else{
                    $daoRecall->adddInvitee($userid, $rid);
                }
            }
            $sql = "select inviter from user_recall where invitee=?";
            $inviter = $daoRecall->getOne($sql, $userid);
            if(!$inviter) {
                Logger::log("recall_err", "worker", array("uid" => $userid,"deviceid"=>$deviceid,"errmsg" => "Invitee不存在"));
                return true;
            }

            $dao_user = new DAOUser();
            $sql = "select addtime from user where uid=?";
            $addtime = $dao_user->getRow($sql, $userid);
            //老用户 注册时间 小于活动开始时间 为老用户  2月13日后无登录记录 
            if(strtotime($addtime) < strtotime($startime)) {
                $daoRecall->modUserRecalType($userid, 0, $deviceid);
                //老用户判断登录记录 2,3月份
                $daoLoginLog = new DAOLoginLog();
                $sql = "SELECT count(*) FROM `loginlog_201802` where uid = ? and platform != 'server' and addtime > '2018-02-13'";
                if($daoLoginLog->getOne($sql, array($userid)) > 0) {
                    Logger::log("recall_err", "worker", array("uid" => $userid,"deviceid"=>$deviceid,"errmsg" => "2月份登录过"));
                    return true;
                }
                $sql = "SELECT count(*) FROM `loginlog_201803` where uid = ? and platform != 'server' ";
                if($daoLoginLog->getOne($sql, array($userid)) > 1) {
                    Logger::log("recall_err", "worker", array("uid" => $userid,"deviceid"=>$deviceid,"errmsg" => "3月份登录过"));
                    return true;
                }
                
                AccountInterface::recall($userid);
                $daoRecall->modUserRecalDiamondOld($userid, $oldDiamond);

                $content = "欢迎老用户回归，赠送{$diamond}星钻给你，快去找自己喜欢的主播吧";
                Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $userid, $content, $content, 0);
            }else{
                $daoRecall->modUserRecalType($userid, 1, $deviceid);
            }
            //邀请人 发奖
            AccountInterface::recall($inviter);
            $daoRecall->modUserRecalDiamond($userid, $diamond);

            Logger::log("recall_err", "success", array("uid" => $userid,"deviceid"=>$deviceid,"errmsg" => "success"));
        }catch (Exception $e) {
            Logger::log("recall_err", "worker", array("uid" => $userid,"deviceid"=>$deviceid,"errno" => $e->getCode(),"errmsg" => $e->getMessage()));
        }
        return true;
    }
}

$ROOT_PATH = dirname(dirname(dirname(dirname(__FILE__))));
$LOAD_PATH = array(
    $ROOT_PATH . "/src/www",
    $ROOT_PATH."/config",
    $ROOT_PATH."/src/application/controllers",
    $ROOT_PATH."/src/application/models",
    $ROOT_PATH."/src/application/models/libs",
    $ROOT_PATH."/src/application/models/dao",
    $ROOT_PATH."/src/application/models/task"
);
set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $LOAD_PATH));

function __autoload($classname)
{
    $classname = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
    include_once "$classname.php";
}
require $ROOT_PATH."/config/server_conf.php";

require_once 'process_client/ProcessClient.php';

try{
    $process = new ProcessClient("dream");

    $process->addWorker("passport_sync_user_recall",  array("UserRecallWorker", "execute"), 2, 2000);

    $process->run();
} catch (Exception $e) {
    ;
}
