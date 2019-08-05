<?php
class Follow
{
    const KEY_PREFIX = "f-";

    private static function _addFollow($uid, $fids, $reason = "")
    {
        /* {{{ */
        $followed = array();

        $cache = Cache::getInstance("REDIS_CONF_FOLLOW");

        $cache->delete(Follow::KEY_PREFIX . $uid);

        try {
            $dao_proxy = new DAOProxy();

            $dao_following = new DAOFollowing($uid);
            $dao_follower = new DAOFollower($uid);
            $dao_followlog = new DAOFollowlog();

            $dao_following->startTrans();

            $friends = array();
            foreach ($fids as $fid) {
                $followed[$fid] = false;

                if ($uid == $fid || $dao_following->exists($fid)) {
                    continue;
                }

                $isfriend = false;
                if ($dao_follower->exists($fid)) {
                    $isfriend = true;
                    $hisdao_following = new DAOFollowing($fid);
                    $hisdao_following->modFollowing(
                        $uid, array(
                        "friend" => "Y"
                        )
                    );
                }

                $dao_following->addFollowing($fid, $isfriend);

                $hisdao_follower = new DAOFollower($fid);
                $hisdao_follower->addFollower($uid);
                $dao_followlog->addFollowlog($uid, $fid, DAOFollowlog::ACTION_ADD, $reason);

                $followed[$fid] = true;
            }

            $dao_following->commit();
        } catch (Exception $e) {
            $dao_following->rollback();

            throw $e;
        }

        $cache_vals = array();
        $follows = $dao_following->getFollowings(0, 3000, false, true);
        foreach ($follows as $k => $v) {
            $cache_vals[$v["fid"]] = 1;
        }

        $cache_vals && $cache->hmset(Follow::KEY_PREFIX . $uid, $cache_vals);

        return $followed;
    }/* }}} */
    public static function addFollow($uid, $fids, $liveid)
    {
        /* {{{ */
        if (! is_array($fids)) {
            $fids = array(
                $fids
            );
        }

        $followed = self::_addFollow($uid, $fids, $liveid ? "room" : "");

        include_once "process_client/ProcessClient.php";

        // worker 排行榜
        ProcessClient::getInstance("dream")->addTask("live_rank_generate", array("type" => "following", "action" => "increase", "userid" => $uid, "score" => 1, "relateid" => 0));
        $userinfo = User::getUserInfo($uid);
        $live_author = 0;
        if ($liveid) {
            $live_model = new Live();
            $liveinfo = $live_model->getLiveInfo($liveid);
            $live_author = $liveinfo['uid'];
        }
        
        foreach ($followed as $fid => $v) {
            if ($v) {
                $num = Counter::increase(Counter::COUNTER_TYPE_FOLLOWERS, $fid);
                if (in_array($live_author, array($uid, $fid)) && $liveid) {//主播关注观众或观众关注主播发群消息
                    if ($live_author == $uid) {
                        $audience = User::getUserInfo($fid);
                        Messenger::sendLiveFollowing($liveid, "关注了", $uid, $fid, $audience['nickname'], $audience['avatar'], Messenger::MESSAGE_TYPE_FOLLOW_AUDIENCE, $audience['exp'], $audience['level'], 0, $audience['vip']);
                    } else {
                        $user_guard = UserGuard::getUserGuardRedis($uid, $live_author);
                        
                        Messenger::sendLiveFollowing($liveid, "关注了主播", $uid, $uid, $userinfo['nickname'], $userinfo['avatar'], Messenger::MESSAGE_TYPE_FOLLOW_BROADCAST, $userinfo['exp'], $userinfo['level'], $user_guard, $userinfo['vip']);
                    }
                    
                } else {
                    //Messenger::sendFollow($fid, "有人关注了你", $uid, $userinfo['nickname'], $userinfo['avatar'], $userinfo['exp'], $userinfo['level']);
                }
                
                // worker 排行榜
                ProcessClient::getInstance("dream")->addTask("live_rank_generate", array("type" => "followers", "action" => "increase", "userid" => $fid, "score"=> 1));

            }
        }

        $total_followings = Follow::countFollowings($uid, true);
        Counter::set(Counter::COUNTER_TYPE_FOLLOWINGS, $uid, $total_followings);


        return $followed;
    }/* }}} */
    private static function _cancelFollow($uid, $fid, $reason = "")
    {
        /* {{{取消关注 */
        $dao_following = new DAOFollowing($uid);
        $following_info = $dao_following->getFollowingInfo($fid);

        if (! empty($following_info)) {
            $cache = Cache::getInstance("REDIS_CONF_FOLLOW");

            $cache->del(Follow::KEY_PREFIX . $uid);

            try {
                $dao_following->startTrans();

                $dao_following->delFollowing($fid);

                $dao_follower = new DAOFollower($fid);
                $dao_follower->delFollower($uid);

                if ("Y" == $following_info["friend"]) {
                    $hisdao_following = new DAOFollowing($fid);
                    $hisdao_following->modFollowing(
                        $uid, array(
                        "friend" => "N"
                        )
                    );
                }

                $dao_followlog = new DAOFollowlog();
                $dao_followlog->addFollowlog($uid, $fid, DAOFollowlog::ACTION_CANCEL, $reason);

                $dao_following->commit();
            } catch (Exception $e) {
                $dao_following->rollback();

                throw $e;
            }

            $cache_vals = array();
            $follows = $dao_following->getFollowings(0, 3000, false, true);
            foreach ($follows as $k => $v) {
                $cache_vals[$v["fid"]] = 1;
            }
            $cache_vals && $cache->hmset(Follow::KEY_PREFIX . $uid, $cache_vals);

            return true;
        }

        return false;
    }/* }}} */
    public static function cancelFollow($uid, $fid, $reason = "")
    {
        /* {{{ */
        $canceled = self::_cancelFollow($uid, $fid, $reason);

        if ($canceled) {
            $total_followings = Follow::countFollowings($uid, true);
            Counter::set(Counter::COUNTER_TYPE_FOLLOWINGS, $uid, $total_followings);

            $num = 0;
            $result = Counter::decrease(Counter::COUNTER_TYPE_FOLLOWERS, $fid);

            include_once "process_client/ProcessClient.php";
            // worker 排行榜
            ProcessClient::getInstance("dream")->addTask("live_rank_generate", array("type" => "following","action" => "decrease","userid" => $uid,"score"    => 1));
            ProcessClient::getInstance("dream")->addTask("live_rank_generate", array("type" => "followers","action" => "decrease","userid" => $fid,"score"    => 1));
            
            //注释newsfeeds by yangqing
            //$bool = ProcessClient::getInstance("dream")->addTask("followings_decrease_newsfeeds", array("uid"=> $uid, "fid"=> $fid, "time"=> date("Y-m-d H:i:s")));
        }

        return $canceled;
    }/* }}} */
    public static function setOptionNotice($uid, $fid, $notice)
    {
        /* {{{ */
        try {
            $dao_following = new DAOFollowing($uid);
            $dao_follower = new DAOFollower($fid);

            $dao_following->startTrans();

            $dao_following->modFollowing(
                $fid, array(
                "notice" => $notice
                )
            );
            $dao_follower->modFollower(
                $uid, array(
                "notice" => $notice
                )
            );

            $dao_following->commit();
        } catch (Exception $e) {
            $dao_following->rollback();

            throw $e;
        }

        return true;
    }/* }}} */
    public static function countFollowers($uid)
    {
        /* {{{ */
        $dao_follower = new DAOFollower($uid);
        return $dao_follower->countFollowers();
    }/* }}} */
    public static function countFollowings($uid, $forceMaster = false)
    {
        /* {{{ */
        $dao_following = new DAOFollowing($uid);
        return $dao_following->countFollowings($forceMaster);
    }/* }}} */
    public static function isFollowed($uid, $fids)
    {
        /* {{{ fids 是否为 uid 的关注 */
        if (! $fids) {
            return array();
        }

        if (! is_array($fids)) {
            $fids = array(
                $fids
            );
        }

        $followed = array();

        $cache = Cache::getInstance("REDIS_CONF_FOLLOW");
        if ($cache->exists(Follow::KEY_PREFIX . $uid)) {
            $followed_list = $cache->hmget(Follow::KEY_PREFIX . $uid, $fids);
        } else {
            $dao_following = new DAOFollowing($uid);
            $followed_list = $dao_following->isFollowed($fids);
        }

        foreach ($fids as $fid) {
            if ($uid == $fid) {
                $followed[$fid] = true;
            } else {
                $followed[$fid] = ! ! $followed_list[$fid];
            }
        }

        return $followed;
    }/* }}} */
    public static function isFollower($uid, $fids)
    {
        /* {{{fids 是否为 uid 的粉丝 */
        if (! $fids) {
            return array();
        }

        if (! is_array($fids)) {
            $fids = array(
                $fids
            );
        }

        $follower = array();

        foreach ($fids as $fid) {
            if ($uid == $fid) {
                $follower[$fid] = true;
            } else {
                $followed = self::isFollowed($fid, $uid);
                $follower[$fid] = $followed[$uid];
            }
        }

        return $follower;
    }/* }}} */
    public static function isFriend($uid, $fids)
    {
        /* {{{ */
        if (! $fids) {
            return array();
        }

        if (! is_array($fids)) {
            $fids = array(
                $fids
            );
        }

        $friend = array();

        $cache = Cache::getInstance("REDIS_CONF_FOLLOW");
        if ($cache->exists(Follow::KEY_PREFIX . $uid)) {
            $followed_list = $cache->hmget(Follow::KEY_PREFIX . $uid, $fids);

            $friend_list = array();
            foreach ($followed_list as $k => $v) {
                $followed = Follow::isFollowed(
                    $k, array(
                    $uid
                    )
                );
                if ($v && $followed[$uid]) {
                    $friend_list[$k] = true;
                }
            }
        } else {
            $dao_following = new DAOFollowing($uid);
            $friend_list = $dao_following->isFriend($fids);
        }

        foreach ($fids as $fid) {
            if ($uid == $fid) {
                $friend[$fid] = true;
            } else {
                $friend[$fid] = ! ! $friend_list[$fid];
            }
        }

        return $friend;
    }/* }}} */

    public static function relation($uid, $fid)
    {
        /* {{{ 获取uid 和 fid 的用户关系 四种:关注,粉丝,好友,陌生人*/
        $relation = '';

        $followed = self::isFollowed($uid, $fid);//fid是否是uid的关注
        if ($followed[$fid]) {
            $relation = 'following';//关注
        }

        $follower = self::isFollower($uid, $fid);//fid是否是uid的粉丝
        if ($follower[$fid]) {
            $relation = "follower";//粉丝
        }

        $friend = self::isFriend($uid, $fid);//是否是好友
        if ($friend[$fid]) {
            $relation = 'friend';
        }

        return empty($relation) ? 'stranger' : $relation;
    }/* }}} */

    public static function getUserFollowings($uid, $offset, $num, $noticed_only = "N")
    {
        /* {{{ */
        $dao_following = new DAOFollowing($uid);
        $list = $dao_following->getFollowings($offset, $num, $noticed_only == "Y");

        $users = array();
        if ($list) {
            foreach ($list as $k => $v) {
                $users[] = array(
                    "uid" => $v["fid"],
                    "notice" => "Y" == $v["notice"]
                );
            }
        }

        return $users;
    }/* }}} */
    public static function getUserFollowers($uid, $offset, $num, $noticed_only = "N")
    {
        /* {{{ */
        $dao_follower = new DAOFollower($uid);
        $list = $dao_follower->getFollowers($offset, $num, $noticed_only == "Y");

        $users = array();
        if ($list) {
            foreach ($list as $k => $v) {
                $users[] = array(
                    "id" => $v["id"],
                    "uid" => $v["fid"],
                    "notice" => "Y" == $v["notice"]
                );
            }
        }

        return $users;
    }/* }}} */
    public static function getUserFriends($uid, $offset, $num)
    {
        /* {{{ */
        $dao_following = new DAOFollowing($uid);
        $list = $dao_following->getFriends($offset, $num);

        $users = array();
        if ($list) {
            foreach ($list as $k => $v) {
                $users[] = array(
                    "uid" => $v["fid"]
                );
            }
        }

        return $users;
    } /* }}} */
}
?>
