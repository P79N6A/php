<?php
class filterNicknameWorker
{
    
    static public function execute($value)
    {
        if (empty($value)) {
            return true;
        }
        
        $uid                   = $value["params"]["uid"];
        $nickname            = $value["params"]["nickname"];
        $signatue            = $value["params"]["signature"];
        
        if (empty($uid)) {
            return true;
        }
        
        try{
            
            if (!empty($nickname)) {
                $word_content = FilterKeyword::keyword_replace($nickname);
            }
            if (!empty($signatue)) {
                $sign_content = FilterKeyword::keyword_replace($signatue);
            }
            
            
            
            if (!empty($word_content['replace_word']) || !empty($sign_content['replace_word'])) {
                
                
                $dao_user = new DAOUser();
                
                $con = $word_content['content'];
                $dao_user->setUserInfo($uid, $con, $sign_content['content']);
                $user_model = new User();
                $user_model->reload($uid);
                Logger::log("filter_nickname_log", "get", array("uid" => $uid, 'replace_nickanme'=> $con, 'nickname' => $word_content['content'] ,'sign' => $sign_content['content']));
                $process = new ProcessClient("dream");
                $process->addTask("filter_word_log", array("uid" => $uid, "nickname" => $nickname, 'replace_word' => implode('#', $word_content['replace_word']) .'#' . implode('#', $sign_content['replace_word']), 'sign' => $sign_content['content']));
            } else {
                //Logger::log("filter_nickname_log", "all", array("uid" => $uid, 'not_replace'=> $word_content['content']));
            }
        } catch (Exception $e) {
            Logger::log("process_error", "filter_nickname_worker1", array("errorcode" => $e->getCode(), "errormsg" => json_encode($e->getMessage())));
        }
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
    $process->addWorker("filter_nickname_worker",  array("filterNicknameWorker", "execute"),  100, 200000);
    $process->run();
} catch (Exception $e) {
    Logger::log("process_error", "filter_nickname_worker", array("errorcode" => $e->getCode(), "errormsg" => json_encode($e->getMessage())));
}
?>
