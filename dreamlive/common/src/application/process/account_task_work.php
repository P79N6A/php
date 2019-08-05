<?php
/**
 * php /home/dream/codebase/dreamlive/service/src/application/process/account_task_work.php -d restart
 *
 * @author yangqing
 */
 
class AccountTaskWork
{

    public static function execute($value)
    {
        $uid     = $value["params"]["uid"];
        $taskid  = $value["params"]["task"];
        $award   = $value["params"]["award"];
        $awardId = $value["params"]["awardId"];
        
        //用户信息
        $user = new User();
        $userInfo = $user->getUserInfo($uid);
        //任务信息
        $task = new Task();
        $taskInfo  = $task->getTaskInfo($taskid);
        $extend = json_decode($taskInfo['extend'], true);
        
        //送星光，星钻
        if(isset($award['starlight']) && $award['starlight']>0 || isset($award['diamonds']) && $award['diamonds']>0) {
            try {
                $result = StarTask::increase($uid, $taskid, $award, $awardId);
                if($result) {
                    $result = UserTaskAward::update($uid, $awardId, 'Y');
                }
            } catch (Exception $e) {
                Logger::log("account_task_work_ride_gift", "diamonds|starlight", array("uid" => $uid,"taskid"=>$taskid,"award"=>json_encode($award),'e'=>print_r($e, true)));
            }
        }
        //送座驾
        if(!empty($award['ride']) && isset($award['ride']['id']) && isset($award['ride']['expire'])) {
            try {
                //$result = Bag::putRide($uid, $award['ride']['id'], $award['ride']['expire']);
                $product = new Product();
                $result = $product->sendRideByTask($uid, $award['ride']['id'], $award['ride']['expire'], '', $taskInfo);
            } catch (Exception $e) {
                Logger::log("account_task_work_ride_gift", "ride", array("uid" => $uid,"taskid"=>$taskid,"award"=>json_encode($award),'e'=>print_r($e, true)));
            }
        }
        //送礼物
        if(!empty($award['gift']) && isset($award['gift']['id']) && isset($award['gift']['num'])) {
            try {
                $result = Bag::putGift($uid, $award['gift']['id'], $award['gift']['num']);
            } catch (Exception $e) {
                Logger::log("account_task_work_ride_gift", "gift", array("uid" => $uid,"taskid"=>$taskid,"award"=>json_encode($award),'e'=>print_r($e, true)));
            }
        }

        
        //发礼物、看播、开播、评论、分享、飞屏不发消息
        if (!in_array($taskid, array(Task::TASK_ID_COMMENT,Task::TASK_ID_SEND_GIFT,Task::TASK_ID_PROP,Task::TASK_ID_SHARE,Task::TASK_ID_LIVE_WATCH,Task::TASK_ID_LIVE_START))) {
            
            //拼接奖励
            $language_config = Context::getConfig("SYSTERM_COMMON_LANGUAGE");
            $user_region = $userInfo['region'];
            $award_value_title = '';
            foreach ($award as $key => $val) {
                //经验、星光、星钻
                if(in_array($key, array('exp','starlight','diamonds'))) {
                    if (isset($language_config['task_message'][$user_region]['value'][$key]) && !empty($val)) {
                        $award_value_title .= sprintf($language_config['task_message'][$user_region]['value'][$key], $val). ',';
                    }
                }
                //座驾
                if($key=='ride') {
                    $product=new Product();
                    $product_info=$product->getOne($award['ride']['id']);
                    Logger::log("account_task_work_ride_gift", "message_ride_info", array("uid" => $uid,"taskid"=>$taskid,"product"=>json_encode($product_info)));
                    if(!empty($product_info)) {
                        $award['ride']['name'] = $product_info['name'];
                        $award['ride']['num'] = 1;
                        if (isset($language_config['task_message'][$user_region]['value']['ride']) && !empty($award['ride']['name'])) {
                            $award_value_title .= sprintf($language_config['task_message'][$user_region]['value']['ride'], $award['ride']['name'], $award['ride']['expire']/(3600*24)). ',';
                            Logger::log("account_task_work_ride_gift", "message_ride", array("uid" => $uid,"taskid"=>$taskid,"content"=>$award_value_title));
                        }
                    }
                }
                //礼物
                if($key=='gift') {
                    $gift=new Gift();
                    $gift_info=$gift->getGiftInfo($award['gift']['id']);
                    if(!empty($gift_info)) {
                        $award['gift']['name'] = $gift_info['name'];
                        if (isset($language_config['task_message'][$user_region]['value']['gift']) && !empty($award['gift']['name'])) {
                            $award_value_title .= sprintf($language_config['task_message'][$user_region]['value']['gift'], $award['gift']['name'], $award['gift']['num']). ',';
                        }
                    }
                }
            }
            Logger::log("account_task_work_ride_gift", "message1", array("uid" => $uid,"taskid"=>$taskid,"content"=>$award_value_title));
            $award_value_title = trim($award_value_title, ',');
            $message_content = '';
            if ($taskInfo['type'] == Task::TASK_TYPE_DAILY) {
                $message_content = sprintf($language_config['task_message'][$user_region]['content'][$taskid], $extend['condition']['total'], $award_value_title);
            } elseif ($taskInfo['type'] == Task::TASK_TYPE_DEPOSIT_REPEAT || $taskInfo['type'] == Task::TASK_TYPE_DEPOSIT_ONCE) {
                $message_content = sprintf($language_config['task_message'][$user_region]['content'][99], $taskInfo['name'], $award_value_title);
            } else {
                $message_content = sprintf($language_config['task_message'][$user_region]['content'][$taskid], $award_value_title);
            }
            
            Logger::log("account_task_work_ride_gift", "message2", array("uid" => $uid,"taskid"=>$taskid,"content"=>$message_content));
            if (!empty($message_content)) {
                Messenger::sendTaskSystemMessage(Messenger::MESSAGE_TYPE_BROADCAST_TASK, $uid,  $taskInfo['name'], $message_content, $taskid, $award);
            }
        }
    }
}

set_time_limit(0);
ini_set('memory_limit', '1G');
ini_set("display_errors", "On");
error_reporting(E_ALL & ~ E_NOTICE & ~ E_WARNING);
$ROOT_PATH = dirname(dirname(dirname(dirname(__FILE__))));
$LOAD_PATH = array(
    $ROOT_PATH . "/src/www",
    $ROOT_PATH . "/config",
    $ROOT_PATH . "/src/application/controllers",
    $ROOT_PATH . "/src/application/models",
    $ROOT_PATH . "/src/application/models/libs",
    $ROOT_PATH . "/src/application/models/dao"
);
set_include_path(get_include_path() . PATH_SEPARATOR . implode(PATH_SEPARATOR, $LOAD_PATH));

function __autoload($classname)
{
    $classname = str_replace('\\', DIRECTORY_SEPARATOR, $classname);
    include_once "$classname.php";
}

require $ROOT_PATH . "/config/server_conf.php";
require_once 'process_client/ProcessClient.php';

try {
    $process = new ProcessClient("dream");
    $process->addWorker("account_task_work", array("AccountTaskWork","execute"), 50, 20000);
    $process->run();
} catch (Exception $e) {
    ;
}
