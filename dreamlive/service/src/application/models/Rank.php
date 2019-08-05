<?php
class Rank
{

    private static $hidden_users = array(
        10152017,
        10148246,
        10026461,
        21258419,
        11196354,
        20178083,
        10628843,
        10096650,
        20752159,
        21260578,
        21258310,
        21258986,
        10055750,
        20232826,
        20751356,
        20132451,
        10155140,
        10155596,
        20751923,
        20153015,
        20082112,
        10065174,
        11161710,
        20132451,
        10009936,
        10833731,
        10835157,
        10148246,
        10026461,
        20752159,
        21258310,
        21258986,
        21260578,
        21258419,
        11196354,
        20178083,
        20752153,
        20752180,
        20028634
    );
    //private static $hidden_users = array(10152017);

    public function getRankConfig($name)
    {
        return array("total"=>50);
    }

    public function setRank($type, $action, $element, $score, $relateid = 0, $sender = 0, $liveid = 0)
    {
        /*{{{ 更新排行榜逻辑 */

        switch($type) {
        case "following"://关注榜
            $this->setRankFollowing($action, $element, $score);
            break;
        case "followers"://粉丝榜
            $this->setRankFollowers($action, $element, $score);
            break;
        case "protect"://守护榜
            $this->setRankProtect($action, $element, $score, $relateid);
            break;
        case "audience"://观众榜 包括机器人
            $this->setRankAudience($action, $element, $score, $relateid);
            break;
        case "praise"://点赞榜
            $this->setRankPraise($action, $element, $score);
            break;
        case "sendgift"://送礼榜
            $this->setRankSendGift($action, $element, $score);
            break;
        case "receivegift"://收礼榜
            $this->setRankReceiveGift($action, $element, $score, $sender, $liveid);
            break;
        case "realaudience"://真实观众榜 不包括机器人
            $name = "realaudience_" . $relateid;
            $this->_sync($name, $action, $element, $score);
            break;
        case "userguard"://守护icon
            $name = "userguard_" . $relateid;
            $this->_sync($name, $action, $element, $score);
            break;
        case "liverobots"://守护icon
            $name = "liverobots_" . $relateid;
            $this->_sync($name, $action, $element, $score);
            break;
        case "matchbreak"://pk爆发榜
            $name = "pk_sender_break";
            $this->_sync($name, $action, $element, $score);
            break;
        case "matchreceiver"://pk主播榜
            $this->setRankReceiveMatch($action, $element, $score);
            break;
        case "matchsender"://pk送礼榜
            $this->setRankSenderMatch($action, $element, $score);
            break;
        case "matchwinnernum"://pk胜利场数榜
            $name    = 'pk_match_winner_num';
            $this->_sync($name, $action, $element, $score);
            break;
        case "matchuserrank"://pk土豪榜
            $name    = 'pk_match_user_rank';
            $this->_sync($name, $action, $element, $score);
            break;
        default:
            break;
        }
        return true;
    }/*}}}*/


    private function setRankReceiveMatch($action, $element, $score)
    {
        $key_week   = "pk_receiver_week_".date('W');//日榜
        $this->_sync($key_week, $action, $element, $score);
    }

    private function setRankSenderMatch($action, $element, $score)
    {
        $key_week   = "pk_sender_week_".date('W');//日榜
        $startime   = strtotime("2018-01-23 18:00:00");
        $endtime    = strtotime("2018-01-31 00:00:00");
        $now         = time();
        if ($now > $startime && $now < $endtime) {
            $key_huodong = "pk_sender_activity";
            $this->_sync($key_huodong, $action, $element, $score);
        }

        $this->_sync($key_week, $action, $element, $score);
    }


    /**
     * 关注榜
     *
     * @parshoum string $action
     * @param    string $element
     * @param    int    $score
     */
    private function setRankFollowing($action, $element, $score)
    {
        $key_total  = "following_ranking";//总榜
        $key_date   = "following_ranking_date_".date("Ymd");//日榜
        $key_week   = "following_ranking_week_".date('W');//日榜
        $key_month  = "following_ranking_month_".date("Ym");//日榜

        $this->_sync($key_total, $action, $element, $score);
        $this->_sync($key_date, $action, $element, $score);
        $this->_sync($key_week, $action, $element, $score);
        $this->_sync($key_month, $action, $element, $score);

        return  true;
    }

    /**
     * 粉丝榜
     *
     * @param string $action
     * @param string $element
     * @param int    $score
     */
    private function setRankFollowers($action, $element, $score)
    {
        $key_total  = "follower_ranking";//总榜

        if (intval(date('YmdH')) < intval(date("Ymd") . '05')) {
            $key_date   = "follower_ranking_date_".date("Ymd", strtotime("-1 day ")) . '05';//日榜
        } else {
            $key_date   = "follower_ranking_date_".date("Ymd") . '05';//日榜
        }
        //$key_date   = "follower_ranking_date_".date("Ymd");//日榜
        $key_week   = "follower_ranking_week_".date('W');//日榜
        $key_month  = "follower_ranking_month_".date("Ym");//日榜

        $this->_sync($key_total, $action, $element, $score);
        $this->_sync($key_date, $action, $element, $score);
        $this->_sync($key_week, $action, $element, $score);
        $this->_sync($key_month, $action, $element, $score);

        return  true;
    }

    /**
     * 守护榜
     *
     * @param string $action
     * @param string $element
     * @param int    $score
     */
    private function setRankProtect($action, $element, $score, $relateid)
    {
        $key_total  = "protect_".$relateid;//总榜
        if (intval(date('YmdH')) < intval(date("Ymd") . '05')) {
            $key_date   = "protect_ranking_".$relateid."_date_".date("Ymd", strtotime("-1 day ")) . '05';//日榜
        } else {
            $key_date   = "protect_ranking_".$relateid."_date_".date("Ymd") . '05';//日榜
        }

        //$key_date   = "protect_ranking_".$relateid."_date_".date("Ymd");//日榜
        $key_week   = "protect_ranking_".$relateid."_week_".date('W');//日榜
        $key_month  = "protect_ranking_".$relateid."_month_".date("Ym");//日榜

        $this->_sync($key_total, $action, $element, $score);
        $this->_sync($key_date, $action, $element, $score);
        $this->_sync($key_week, $action, $element, $score);
        $this->_sync($key_month, $action, $element, $score);

        return  true;
    }

    /**
     * 观众榜
     *
     * @param string $action
     * @param string $element
     * @param int    $score
     */
    private function setRankAudience($action, $element, $score, $relateid)
    {
        $key_total  = "audience_".$relateid;//总榜
        $key_date   = "audience_ranking_".$relateid."_date_".date("Ymd");//日榜
        $key_week   = "audience_ranking_".$relateid."_week_".date('W');//日榜
        $key_month  = "audience_ranking_".$relateid."_month_".date("Ym");//日榜

        $this->_sync($key_total, $action, $element, $score);
        $this->_sync($key_date, $action, $element, $score);
        $this->_sync($key_week, $action, $element, $score);
        $this->_sync($key_month, $action, $element, $score);

        return  true;
    }

    /**
     * 点赞榜
     *
     * @param string $action
     * @param string $element
     * @param int    $score
     */
    private function setRankPraise($action, $element, $score)
    {
        $key_total  = "praise_ranking";//总榜

        if (intval(date('YmdH')) < intval(date("Ymd") . '05')) {
            $key_date   = "praise_ranking_date_".date("Ymd", strtotime("-1 day ")) . '05';//日榜
        } else {
            $key_date   = "praise_ranking_date_".date("Ymd") . '05';//日榜
        }

        //$key_date   = "praise_ranking_date_".date("Ymd");//日榜
        $key_week   = "praise_ranking_week_".date('W');//日榜
        $key_month  = "praise_ranking_month_".date("Ym");//日榜

        $this->_sync($key_total, $action, $element, $score);
        $this->_sync($key_date, $action, $element, $score);
        $this->_sync($key_week, $action, $element, $score);
        $this->_sync($key_month, $action, $element, $score);

        return  true;
    }

    /**
     * 送礼榜
     *
     * @param string $action
     * @param string $element
     * @param int    $score
     */
    private function setRankSendGift($action, $element, $score)
    {
        $key_total  = "sendgift_ranking";//总榜

        if (intval(date('YmdH')) < intval(date("Ymd") . '05')) {
            $key_date   = "sendgift_ranking_date_".date("Ymd", strtotime("-1 day ")) . '05';//日榜
        } else {
            $key_date   = "sendgift_ranking_date_".date("Ymd") . '05';//日榜
        }
        //$key_date   = "sendgift_ranking_date_".date("Ymd");//日榜
        $key_week   = "sendgift_ranking_week_".date('W');//日榜
        $key_month  = "sendgift_ranking_month_".date("Ym");//日榜

        $groupid = 0;//TODO
        if (!empty($groupid)) {
            $key_group_week   = "sendgift_ranking_group_week_".date('W');//社团周榜
            $key_group_week_total   = "sendgift_ranking_group_total_week";//社团总榜
            $this->_sync($key_group_week, $action, $groupid, $score);
            $this->_sync($key_group_week_total, $action, $groupid, $score);
            $key_group_week_user   = "sendgift_ranking_group_week_" .$groupid .'_' .date('W');//社团成员周榜
            $key_group_week_user_total   = "sendgift_ranking_group_total_week_" .$groupid;//社团成员总榜
            $this->_sync($key_group_week_user, $action, $element, $score);
            $this->_sync($key_group_week_user_total, $action, $element, $score);
        }

        $this->_sync($key_total, $action, $element, $score);
        $this->_sync($key_date, $action, $element, $score);
        $this->_sync($key_week, $action, $element, $score);
        $this->_sync($key_month, $action, $element, $score);

        return  true;
    }

    /**
     * 收礼榜
     *
     * @param string $action
     * @param string $element
     * @param int    $score
     */
    private function setRankReceiveGift($action, $element, $score, $sender, $liveid)
    {
        $key_total  = "receivegift_ranking";//总榜

        if (intval(date('YmdH')) < intval(date("Ymd") . '05')) {
            $key_date   = "receivegift_ranking_date_".date("Ymd", strtotime("-1 day ")) . '05';//日榜
        } else {
            $key_date   = "receivegift_ranking_date_".date("Ymd") . '05';//日榜
        }

        //$key_date   = "receivegift_ranking_date_".date("Ymd");//日榜
        $key_week   = "receivegift_ranking_week_".date('W');//日榜
        $key_month  = "receivegift_ranking_month_".date("Ym");//日榜

        $this->_sync($key_total, $action, $element, $score);
        $this->_sync($key_date, $action, $element, $score);

        $this->_sync($key_week, $action, $element, $score);
        $this->_sync($key_month, $action, $element, $score);
        $this->checkDiamond($key_date, $element, $sender, $liveid);

        return  true;
    }



    private function _sync($name, $action, $element, $score)
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");

        switch ($action) {
        case "increase":
            $bool = $cache->zIncrBy($name, $score, $element);
            break;
        case "decrease":
            $cache->zIncrBy($name, 0 - $score, $element);
            break;
        case "set":
            $cache->zAdd($name, $score, $element);
            break;
        case "delete":
            $cache->zRem($name, $element);
            break;
        case "destroy":
            $cache->del($name);
            break;
        }

        return true;
    }

    public function getRanking($name, $offset, $num)
    {
        $num = ($num < 20) ? 20 : $num;
        $num = 200;
        $config = $this->getRankConfig($name);

        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $total = $cache->zCard($name);

        if($offset >= $total) {
            return array($offset, array(), $offset, false);
        }

        if (strpos($name, 'audience_') !== false) {
            $liveid = str_replace('audience_', '', $name);
            $live = new Live();
            $liveinfo = $live->getLiveInfo($liveid);
        }

        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $cache_info = $cache->get("big_liver_keys");
        $userList = explode(',', $cache_info);

        $elements = $cache->zRevRange($name, $offset, $offset + $num-1);
        $users = $uids = array();
        //批量获取是否关注
        foreach($elements as $element){
            array_push($uids, $element);
        }
        $isFolloweds =  Follow::isFollowed(Context::get("userid"), $uids);

        $dao_feeds = new DAOFeeds();
        foreach($elements as $element) {
            if(in_array($element, $userList) && !(strpos($name, 'protect_') !== false)) {
                continue;
            }
            if(!Forbidden::isForbidden($element) && !in_array($element, self::$hidden_users)) {
                $info = User::getUserInfo($element);
                $info['liveid']     = Live::isUserLive($info['uid']);
                if (!empty($info)) {
                    //$info['followed'] = !! current(Follow::isFollowed(Context::get("userid"), $info['uid']));
                    $info['followed'] = !! $isFolloweds[$element];
                    $score  = $cache->zScore($name, $info['uid']);
                    $info['score']  = intval($score);

                    //$DAOLive        = new DAOLive();
                    //$info['isLive'] = $DAOLive->isLive($info['uid']);
                    $info['isLive'] = (Live::isUserLive($info['uid'])) ? true : false;
                    if (!empty($liveinfo)) {
                        $patroller = new Patroller();
                        $is_patroller = $patroller->isPatroller($info['uid'], $liveinfo['uid'], $liveid);
                        $info['isPatroller'] = (int) $is_patroller;
                        $info['isGuard'] = (int) UserGuard::getUserGuardRedis($info['uid'], $liveinfo['uid']);

                        $key   = "string_join_live_".$liveinfo['liveid']."_".$element;
                        $time = $cache->get($key);
                        if(empty($time)) {
                            $time = time();
                        }
                        $info['time'] = $time;

                    }

                    //svn diff$info['living'] = $dao_feeds->hasActivingLive($info['uid']);
                    $users[] = $info;
                } else {
                    $cache->zRem($name, $element);
                }
            }
        }

        $offset += $num;//下一页的起始值
        $offset = $offset>$total ? $total : $offset;
        $next_elements = $cache->zRevRange($name, $offset, $offset + $num);

        //判断是否有下一页
        $more = false;
        if (!empty($next_elements)) {
            $more = true;
        }

        return array($total, $users, $offset, $more);
    }

    /**
     * 观众列表专用接口
     *
     * @param  string $name
     * @param  int    $num
     * @return array|number[]|unknown[][]|number[][]
     */
    public function getAudienceList($name, $num)
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");

        try{
            $elements  = $cache->zRevRangeByScore($name, PHP_INT_MAX, 0, ['withscores' => true, 'limit' => [0, $num]]);
        } catch (Exception $e) {
            return [];
        }

        $rank_time = Consume::getTime();

        $addiences = array_keys($elements);

        //被封禁用户列表
        $forbiddens = Forbidden::isForbiddenUsers($addiences);


        $addiences = array_diff($addiences, $forbiddens);

        $forbid_time = Consume::getTime();

        $user = new User();
        $userinfos = $user->getUserInfos($addiences);
        $user_time = Consume::getTime();

        $users = array();
        //array("rank_time"=>$rank_time, "forbid_time"=>$forbid_time, "user_time"=>$user_time);
        foreach($addiences as $addience) {
            $userinfo = $userinfos[$addience];
            if (!empty($userinfo)) {
                $score = $elements[$addience];
                if (strpos($score, '.') !== false) {
                    $score = number_format($score, 3, ".", "");

                    $is_guard     = substr($score, -3, 1);//守护
                    $is_patroller = substr($score, -2, 1);//场控
                    $is_pre_buyed = substr($score, -1, 1);//预付费

                    $userinfo['isPatroller'] = (int) $is_patroller;

                    if (!empty($is_guard)) {
                        $userinfo['isGuard'] = ($is_guard == 2) ? 10 : 5;
                    } else {
                        $userinfo['isGuard'] = 0;
                    }

                    if ($is_pre_buyed) {
                        $userinfo['nickname'] .= "(预付费)";
                    }
                } else {
                    $userinfo['isGuard'] = 0;
                    $userinfo['isPatroller'] = 0;
                }


                $users[] = $userinfo;
            }
        }

        return $users;
    }

    public function getRankValueByRankName($rank_name, $userid)
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        if ($rank_name == 'receivegift_ranking_date') {
            if (intval(date('YmdH')) < intval(date("Ymd") . '05')) {
                $key_date   = "receivegift_ranking_date_".date("Ymd", strtotime("-1 day ")) . '05';//日榜
            } else {
                $key_date   = "receivegift_ranking_date_".date("Ymd") . '05';//日榜
            }
            //var_dump($key_date);
            $elements = $cache->zRevRange($key_date, 0, 1000);
        } else {
            $elements = $cache->zRevRange($rank_name, 0, 1000);
        }

        if (!empty($elements)) {
            $cache_info = $cache->get("big_liver_keys");
            $userList = explode(',', $cache_info);
            $users = array();
            $i = 1;
            foreach ($elements as $element) {
                if (in_array($element, $userList)) {
                    continue;
                }

                if (!Forbidden::isForbidden($element) && !in_array($element, self::$hidden_users)) {

                    $users[] = $element;
                    $i++;
                }

                if ($i > 100) {
                    break;
                }
            }

            $rank = 101;//默认值

            foreach ($users as $k => $val) {

                if ($val == $userid) {
                    $rank = $k+1;
                    break;
                }
            }

            return $rank;
        }

        return 1;
    }

    public function getRankTotal($name)
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $total = $cache->zCard($name);

        return $total;
    }

    public function getRankingElement($name,$element)
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        return $cache->ZSCORE($name, $element);
    }

    /**
     * 段位检查发消息
     *
     * @param 榜单名    $name
     * @param 榜单元素 $elements
     * @param 发送者    $sender
     * @param 直播间id  $liveid
     */
    public function checkDiamond($name, $element, $sender, $liveid)
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        //Logger::log("rank_log", "step1", array("uid" => $element,"sender"=>$sender,"liveid"=>$liveid));
        $today_score = $cache->ZSCORE($name, $element);

        $config = new Config();
        $results = $config->getConfig("china", "ladder_of_money", "server", '1000000000000');
        $ladder_of_money = json_decode($results['value'], true);

        $notice_score     = 0;
        $next_name         = '';
        $step             = 0;
        $j                 = 0;
        if ($today_score < 700) {
            return true;
        }

        $ladder_of_money = array_reverse($ladder_of_money);

        foreach ($ladder_of_money as $values) {
            if ($today_score >= $values['notice'] && $today_score < $values['max'] && !empty($values['max_name'])) {
                $next_name = $values['max_name'];
                $notice_score     = ($values['max'] - $today_score);
                $step = $values['key'];
                break;
            }
            $j++;
        }


        //Logger::log("rank_log", "step3", array("notice_score" => $notice_score,"step" => $step,"next_name"=>$next_name,"liveid"=>$liveid, "score" => $today_score));
        if (!empty($step) && !empty($notice_score) && !empty($next_name)) {
            $_had_send_key = "fnsend_last_message_" . date("Ymd") ."_". $step ."_" .$element;


            Logger::log("rank_log", "step4", array("key" => $_had_send_key, "redis" => $cache->get($_had_send_key),"notice_score" => $notice_score,"step" => $step,"next_name"=>$next_name,"liveid"=>$liveid, "score" => $today_score));

            //$cache->delete($_had_send_key);
            $bool = $cache->get($_had_send_key);
            if (! $bool) {
                Messenger::sendUserReachNextLevelLast($element, $notice_score, $next_name);
                Logger::log("rank_log", "step4", array("ok" => 'done',"uid" => $element,"sender"=>$sender,"liveid"=>$liveid, "score" => $today_score));
            }
            $cache->INCR($_had_send_key);
        }

        $complete_name  = '';
        $i                 = 0;
        $complete_step    = 0;
        $key_level        = 0;


        foreach ($ladder_of_money as $values) {
            if ($today_score >= $values['min'] && $today_score < $values['max'] && !empty($values['min_name'])) {
                $complete_name = $values['min_name'];
                $complete_step = $i;
                $key_level       = $values['key'];
                break;
            }
            $i++;
        }


        //Logger::log("rank_log", "step5", array('f' => json_encode($ladder_of_money),"complete_step" => $complete_step,"completename"=>$complete_name,"liveid"=>$liveid, "score" => $today_score));
        if (!empty($complete_step) && !empty($complete_name)) {
            $_reach_had_send_key = "fnsend_reach_message_" . date("Ymd") ."_". md5($complete_name)."_" .$element;

            Logger::log("rank_log", "step5", array("key" => $_reach_had_send_key, "redis" => $cache->get($_reach_had_send_key),"complete_step" => $complete_step,"completename"=>$complete_name,"liveid"=>$liveid, "score" => $today_score));

            //$cache->delete($_reach_had_send_key);
            $bool = $cache->get($_reach_had_send_key);
            if (! $bool) {
                $user_guard = UserGuard::getUserGuardRedis($sender, $element);
                Messenger::sendLiveReachTicketLevel($liveid, $complete_name, $sender, $user_guard, $key_level);
                Logger::log("rank_log", "step6", array("ok" => 'done',"uid" => $element,"sender"=>$sender,"liveid"=>$liveid, "score" => $today_score));
            }
            $cache->INCR($_reach_had_send_key);
        }

    }
    /*
     * 直播时长排行
     * */
    public function liveTimeRank($anchorid)
    {
        $cache      = Cache::getInstance("REDIS_CONF_CACHE");
        $times      = json_decode($cache->get('live_time_active_time'), true);
        $stime      = $times['stime'];
        $etime      = $times['etime'];
        if(time()<strtotime($stime)) { $status = 1;
        } elseif(time()>strtotime($etime)) { $status = 3;
        } else { $status = 2;
        }


        $live_time_rank = json_decode($cache  -> get("live_time_rank"), true);
        $live_stream_uids = json_decode($cache->get('live_time_stream_uids'), true);

        $rank = [];
        if($live_time_rank) {
            foreach($live_time_rank as $k=>$val){
                if($val['rank'] == 51) { break;
                }
                $userinfo    = User::getUserInfo($k);
                $rank[] = array(
                    'uid'        => $userinfo['uid'],
                    'gender'    => $userinfo['gender'],
                    'avatar'    => $userinfo['avatar'],
                    'king'        => Match::getMedalKing($userinfo),
                    'nickname'    => $userinfo['nickname'],
                    'is_live'    => Live::isUserLive($userinfo['uid'])?true:false,
                    'livetime'    => $val['sroce'],
                'rank'        => $val['rank']
                );
            }
        }
        if(in_array($anchorid, $live_stream_uids)) {
            $anchor_userinfo    = User::getUserInfo($anchorid);
            $anchor_info    = array(
                'uid'        => $anchor_userinfo['uid'],
                'gender'    => $anchor_userinfo['gender'],
                'avatar'    => $anchor_userinfo['avatar'],
                'king'        => Match::getMedalKing($anchor_userinfo),
                'nickname'    => $anchor_userinfo['nickname'],
                'is_live'    => Live::isUserLive($anchor_userinfo['uid'])?true:false,
                'livetime'    => $live_time_rank[$anchorid]['sroce']?$live_time_rank[$anchorid]['sroce']:0,
                'rank'        => $live_time_rank[$anchorid]['rank']?$live_time_rank[$anchorid]['rank']:0,
            );
        }

        return array(
        'rank_list'    => $rank,
             'anchor_info' => $anchor_info,
             'status'       => $status
         );
    }
}
?>
