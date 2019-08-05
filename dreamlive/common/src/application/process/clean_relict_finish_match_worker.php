<?php
// crontab 死循环 删除结束和流局的pk
// php /home/dream/codebase/service/src/application/process/clean_relict_finish_match_worker.php
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


$dao_match             = new DAOMatchs();
$dao_match_member     = new DAOMatchMember();
$dao_match_prison   = new DAOMatchPrison();
$living_user_key       = "User_living_cache";


$cache                 = Cache::getInstance("REDIS_CONF_CACHE");

while (true) {
    
    $config     = new Config();
    $results     = $config->getConfig("china", "pk_match_config", "server", '1000000000000');
    $json_value         = json_decode($results['value'], true);
    $watting_time         = $json_value['wating_duration'];
    $watting_time         = $watting_time * 60;
    
    $dao_match_prison->delOverDueData();
    
    $all_matchs = $dao_match->getAllNewMatch();
    
    foreach ($all_matchs as $item) {
        
        $now = time();
        $matchid = $item['matchid'];
        
        $one_redis_key = "pk_match_file_". $matchid . "_". $item['status'];
        
        if ($cache->get($one_redis_key) > 5) {
            $cache->del($one_redis_key);
        }
        
        if ($cache->INCR($one_redis_key) > 1) {
            continue;
        }
        
        $cache->expire($one_redis_key, 1000);

        if ($item['status'] == 0) {
            $addtime = strtotime($item['addtime']);
            
            if (($now - $addtime) > $watting_time && $item['startime'] == '0000-00-00 00:00:00') {
                Logger::log("pk_match_worker_log", "notaccept", array("matchid" => $item['matchid']));
                $dao_match->modTimeoutMatch($item['matchid']);//pk超时
            }
            
        } elseif ($item['status'] == 1) {
            
            $duration = $item['duration'] * 60;
            
            $startime = strtotime($item['startime']);
            
            $endtime = ($startime + $duration);
            
            $now = time();
            if ($now > $endtime) {
                Logger::log("pk_match_worker_log", "getMatch", array("matchid" => $item['matchid']));
                $config_json = json_decode($item['config'], true);
                $prison_line = 0;
                $winner_line = 0;
                $prison_day     = 0;
                $free_time     = 0;
                foreach ($config_json['time_select'] as $ck) {
                    if ($item['duration'] == $ck['duration']) {
                        $prison_line = $ck['prison_line'];
                        $winner_line = $ck['winner_line'];
                        $prison_day  = $ck['prison_day'];
                        $free_time   = $ck['free_time'];
                        break;
                    }
                }
                
                try {
                    $dao_match->modFinishMatch($item['matchid']);//pk结束
                    $inviter = $item['inviter'];
                    $invitee = $item['invitee'];
                    $matchid = $item['matchid'];
                    $invitee_score =  Counter::get(Counter::COUNTER_TYPE_MATCH_RECEIVE_GIFT, $invitee.'_' .$matchid);
                    $inviter_score =  Counter::get(Counter::COUNTER_TYPE_MATCH_RECEIVE_GIFT, $inviter.'_' .$matchid);
                    
                    $dao_match_member->modFinishMatch($matchid, $invitee, $invitee_score);
                    $dao_match_member->modFinishMatch($matchid, $inviter, $inviter_score);
                    
                    
                    
                    $free_prison_redis_key = "prison_free_time_" . date("Ymd") . "_". $inviter;
                    
                    if (!empty($prison_line) && $inviter_score <= $prison_line) {
                        
                        if (empty($free_time)) {
                            
                            $dao_match_prison->addPrisonWorker($inviter, $prison_day, 'MATCH', $matchid, 0);
                            Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $inviter, "由于您在pk中未达到标准，暂时被禁赛。时间为{$prison_day}天", "由于您在pk中未达到标准，暂时被禁赛。时间为{$prison_day}天", 0);
                        
                        } else {
                            if ($cache->get($free_prison_redis_key) >= $free_time) {
                                
                                $dao_match_prison->addPrisonWorker($inviter, $prison_day, 'MATCH', $matchid, 0);
                                Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $inviter, "由于您在pk中未达到标准，暂时被禁赛。时间为{$prison_day}天", "由于您在pk中未达到标准，暂时被禁赛。时间为{$prison_day}天", 0);
                                
                                
                            } else {
                                $cache->INCR($free_prison_redis_key);
                                $cache->expire($free_prison_redis_key, 86400);
                            }
                        }
                    }
                    
                    $free_prison_redis_key_invitee = "prison_free_time_" . date("Ymd") . "_" . $invitee;
                    
                    if (!empty($prison_line) && $invitee_score <= $prison_line) {
                        
                        if (empty($free_time)) {
                            
                            $dao_match_prison->addPrisonWorker($invitee, $prison_day, 'MATCH', $matchid, 0);
                            Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $invitee, "由于您在pk中未达到标准，暂时被禁赛。时间为{$prison_day}天", "由于您在pk中未达到标准，暂时被禁赛。时间为{$prison_day}天", 0);
                            
                        } else {
                            if ($cache->get($free_prison_redis_key_invitee) >= $free_time) {
                                
                                $dao_match_prison->addPrisonWorker($invitee, $prison_day, 'MATCH', $matchid, 0);
                                Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $invitee, "由于您在pk中未达到标准，暂时被禁赛。时间为{$prison_day}天", "由于您在pk中收入未达到标准，暂时被禁赛。时间为{$prison_day}天", 0);
                                
                                
                            } else {
                                $cache->INCR($free_prison_redis_key_invitee);
                                $cache->expire($free_prison_redis_key_invitee, 86400);
                            }
                        }
                    }
                    
                    
                    if (!empty($winner_line) && $invitee_score < $winner_line) {
                        
                    }
                    
                    if (!empty($winner_line) && $inviter_score < $winner_line) {
                        
                    }
                    if ($invitee_score > $winner_line || $inviter_score > $winner_line) {
                        if ($invitee_score > $inviter_score) {
                            $winner = $invitee;
                            $dao_match->modResultMatch($matchid, 'F');
                        } else if ($invitee_score < $inviter_score) {
                            $winner = $inviter;
                            $dao_match->modResultMatch($matchid, 'W');
                        } else {
                            $winner = 0;
                            $dao_match->modResultMatch($matchid, 'F');
                        }
                    } else {
                        if ($inviter_score < $winner_line && $inviter_score > $prison_line) {
                            $dao_match->modResultMatch($matchid, 'NW');
                        } elseif ($inviter_score < $prison_line) {
                            $dao_match->modResultMatch($matchid, 'NB');
                        }
                        
                        $winner = 0;
                        
                    }
                    
                    
                } catch (Exception $e) {
                    Logger::log("pk_match_worker_log", "modfinishexception1", array("matchid" => $item['matchid'],"code"=>$e->getCode(),"msg"=>$e->getMessage()));
                }
                
                try{
                    $userinfo_inviter = User::getUserInfo($inviter);
                    $userinfo_invitee = User::getUserInfo($invitee);
                    
                    $liveid = $cache->zScore($living_user_key, $inviter);
                    if (!empty($liveid)) {
                        if ($winner == $inviter) {
                               $message = "恭喜 " . $userinfo_inviter['nickname'] . " 在粉丝的全力协助下，打败了 " . $userinfo_invitee['nickname'];
                               Match::sendMatch($inviter, $inviter_score);
                        } else {
                            $message = "再接再厉!";
                        }
                        Messenger::sendLiveMatchFinished($liveid, $matchid, $inviter, $invitee, $inviter_score, $invitee_score, $winner, $message);
                        $live = new Live();
                        $live->_reload($liveid);
                    } else {
                        Logger::log("pk_match_worker_log", "getLiveId_r", array("matchid" => $item['matchid'], "inviter" => $inviter));
                    }
                    
                    $liveid = $cache->zScore($living_user_key, $invitee);
                    if (!empty($liveid)) {
                        if ($winner == $invitee) {
                            $message = "恭喜 " . $userinfo_invitee['nickname'] . " 在粉丝的全力协助下，打败了 " . $userinfo_inviter['nickname'];
                            Match::sendMatch($invitee, $invitee_score);
                        } else {
                            $message = "再接再厉!";
                        }
                        Messenger::sendLiveMatchFinished($liveid, $matchid, $inviter, $invitee, $inviter_score, $invitee_score, $winner, $message);
                        $live = new Live();
                        $live->_reload($liveid);
                    } else {
                        Logger::log("pk_match_worker_log", "getLiveId_e", array("matchid" => $item['matchid'], "invitee" => $invitee));
                    }
                
                } catch ( Exception $e) {
                    Logger::log("pk_match_worker_log", "modfinishexception2", array("matchid" => $item['matchid'],"code"=>$e->getCode(),"msg"=>$e->getMessage()));
                }
                
                try{
                    MatchMessage::addPkBreakRank($matchid, $invitee);
                    MatchMessage::addPkBreakRank($matchid, $inviter);
                    MatchMessage::remMatchMemberRedis($matchid);
                    
                    UserMedal::delUserMedal($inviter, UserMedal::KIND_PK);
                    UserMedal::delUserMedal($invitee, UserMedal::KIND_PK);
                    User::reload($inviter);
                    User::reload($invitee);
                    
                    Logger::log("pk_match_worker_log", "okdone", array("matchid" => $item['matchid']));
                } catch ( Exception $e) {
                    Logger::log("pk_match_worker_log", "modfinishexception3", array("matchid" => $item['matchid'],"code"=>$e->getCode(),"msg"=>$e->getMessage()));
                }
            }
            
        }
        
        $cache->del($one_redis_key);
        
    }
    
    
    usleep(rand(50, 100));
}