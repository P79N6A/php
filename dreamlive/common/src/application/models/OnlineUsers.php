<?php
/**
 * 在线用户列表model
 *
 * @author xubaoguo
 */
class OnlineUsers
{
    
    /**
     * 获取我关注的在线用户列表
     */
    public function getMyFollowedUserList($userid, $offset = 0, $num = 20)
    {
        $userFollowings = Follow::getUserFollowings($userid, $offset, 3000);
        
        
        if (empty($userFollowings)) {
            return $this->getRecommendUserList($userid, 1, $offset, $num);
        }
        
        $onlineUsers = [];
        $cache  = Cache::getInstance("REDIS_CONF_COUNTER");
        
        
        $elements              = $cache->zRevRangeByScore("dreamlive_online_users_redis_key", PHP_INT_MAX, 0, ['withscores' => true, 'limit' => [0, -1]]);
        $online_users         = array_keys($elements);
        
        $i =  0;
        foreach ($userFollowings as $following) {
            $uid = $following['uid'];
            if ($i >= $num) {
                break;
            }
            
            if (in_array($uid, $online_users)) {
                $roomid = $elements[$uid];
                if (isset($roomid) && !empty($roomid)) {
                    $onlineUsers[$uid] = $roomid;
                    $i++;
                }
            }
        }
        
        if (empty($onlineUsers)) {
            return $this->getRecommendUserList($userid, 2, $offset, $num);
        }
        
        $returnUsers = self::genUserInfos($onlineUsers, $userid, 1);
        
        return array('from' => 0,"offset" => ($offset+$num), 'feeds' => $returnUsers);
    }
    
    
    static public function genUserInfos($onlineUsers, $userid, $isfocus = 0)
    {
        $addiences     = array_keys($onlineUsers);
        $liveids    = array_values($onlineUsers);
        
        $user = new User();
        $userinfos = $user->getUserInfos($addiences);
        $liveinfos = self::getLiveInfoByIds($liveids);
        if (! $isfocus) {
            $followeds = Follow::isFollowed($userid, $addiences);
        }
        
        $i = 0;
        $returnUsers = [];
        foreach ($onlineUsers as $key => $liveid) {
            
            if ($isfocus == 0) {
                if ($i >= 20) {
                    break;
                }
            }
            
            $liveinfo = $liveinfos[$liveid];
            
            if (empty($liveinfo)) {
                continue;
            }
            
            if (isset($userinfos[$key]) && !empty($userinfos[$key])) {
                $userinfo = $userinfos[$key];
                if ($key == $liveinfo['uid']) {
                    $onliveing = 1;
                } else {
                    $onliveing = 0;
                }
                
                
                if ($onliveing == 1) {
                    $title = $liveinfo['author']['nickname'] . "直播中";
                } else {
                    $title = $liveinfo['author']['nickname'] . "的直播间";
                }
                
                $item = array(
                "uid"         => $key,
                "nickname"     => $userinfo['nickname'],
                "level"        => intval($userinfo['level']),
                "exp"        => intval($userinfo['exp']),
                "gender"    => $userinfo['gender'],
                "avatar"    => $userinfo['avatar'],
                "vip"        => (int) $userinfo['vip'],
                "onliveing" => $onliveing,
                "title"        => $title,
                "liveid"    => $liveid,
                "medal"     => $userinfo['medal'],
                "watches"   => empty($liveinfo['watches']) ? 0 : $liveinfo['watches'],
                );
                
                $replay   = ($liveinfo['record'] == 'Y') ? true : false;
                $stream   = new Stream();
                $flv      = $stream->getFLVUrl($liveinfo['sn'], ($liveinfo['partner']), $liveinfo['region'], $replay);
                //istudio合作
                if (!empty($liveinfo['pullurl'])) {
                    $flv = $liveinfo['pullurl'];
                }
                
                $feedInfo = array(
                'type'      => Feeds::FEEDS_LIVE,
                'cover'        => ($liveinfo['cover'] != '') ? $liveinfo['cover'] : $liveinfo['avatar'],
                'relateid'    => $liveinfo['liveid'],
                'addtime'    => $liveinfo['addtime'],
                'endtime'    => $liveinfo['endtime'],
                'title'        => $liveinfo['title'],
                'width'        => $liveinfo['width'],
                'height'    => $liveinfo['height'],
                'city'        => empty($liveinfo['city']) ? '火星' : $liveinfo['city'],
                'extends'    => $liveinfo['extends'],
                'virtual'    => $liveinfo['virtual'] == 'Y' ? true : false,
                'region'    => $liveinfo['region'],
                'flv'        => empty($flv) ? "" : $flv,
                        
                );
                
                if ($isfocus == 1) {
                    $item['followed'] = true;
                } else {
                    $item['followed'] = $followeds[$key];
                }
                
                $privacy = Privacy::getPrivacyInfoByLiveInfo($liveinfo['privacy']);
                if (!empty($privacy) && isset($privacy['privacyid'])) {
                    continue;
                    //过滤私密直播间
                    $feedInfo['watches'] = Counter::get(Counter::COUNTER_TYPE_LIVE_PRIVACY_WATCHES, $privacy['privacyid']);
                    if (empty($feedInfo['watches'])) {
                        $feedInfo['watches'] = "0";
                    }
                    $feedInfo['privacy'] = ! empty($privacy) ? true : false;
                    $feedInfo['privacys'] = $privacy;
                    $feedInfo['privacyid'] = $privacy['privacyid'];
                }
                
                $ritem = array();
                $ritem['feed']         = $feedInfo;
                $ritem['author']     = $liveinfo['author'];
                $ritem['online']     = $item;
                $returnUsers[]         = $ritem;
                $i++;
            } else {
                continue;
            }
        }
        
        return $returnUsers;
    }
    
    
    static public function getLiveInfoByIds($liveids)
    {
        if (! $liveids) {
            return array();
        }
        
        if (! is_array($liveids)) {
            $liveids= array(
            $liveids
            );
        }
        
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        foreach ($liveids as $id) {
            $keys[] = "L2_cache_live_".$id;
        }
        $results = $cache->mget($keys);
        
        $liveinfos = [];
        foreach ($results as $row) {
            if ($row) {
                $liveinfo = json_decode($row, true);
                $liveinfo["L2_cached"] = true;
                if (strpos($liveinfo['cover'], 'http://') === false) {
                    $liveinfo['cover'] = STATIC_DOMAIN_NAME. $liveinfo['cover'];
                }
                $liveinfo['watches'] = Counter::get(Counter::COUNTER_TYPE_LIVE_WATCHES, $liveinfo['liveid']);
                $liveinfos[$liveinfo['liveid']] = $liveinfo;
            }
        }
        
        return $liveinfos;
    }
    
    
    /**
     * 获取推荐的在线用户列表
     */
    public function getRecommendUserList($userid, $from = 1, $offset = 0, $num = 20)
    {
        $cache             = Cache::getInstance("REDIS_CONF_COUNTER");
        $cache_common   = Cache::getInstance("REDIS_CONF_CACHE");
        $key_week_receivegift   = "receivegift_ranking_week_".date('W');//周榜
        $key_week_sendgift       = "sendgift_ranking_week_".date('W');//周榜
        
        $nums = $num + 250;
        $receive_elements     = $cache_common->zRevRange($key_week_receivegift, $offset, $nums);
        $send_elements         = $cache_common->zRevRange($key_week_sendgift, $offset, $nums);
        $elements              = $cache->zRevRangeByScore("dreamlive_online_users_redis_key", PHP_INT_MAX, 0, ['withscores' => true, 'limit' => [0, -1]]);
        $online_users         = array_keys($elements);
        
        $bigLiveUserList = $cache_common->zRevRange("big_liver_keys_set", 0, -1);
        
        $fetch_time = Consume::getTime();
        $onlineUsers = [];
        $max_count = (($num/2) + 5);
        
        $i = 1;
        foreach ($receive_elements as $element) {
            
            if (in_array($element, $bigLiveUserList)) {
                continue;
            }
            
            if ($i >= $max_count) {
                break;
            }
            
            if (in_array($element, $online_users)) {
                $roomid = $elements[$element];
                if (isset($roomid) && !empty($roomid)) {
                    $onlineUsers[$element] = $roomid;
                    $i++;
                }
            }
        }
        
        $i = 1;
        foreach ($send_elements as $element) {
            
            if (in_array($element, $bigLiveUserList)) {
                continue;
            }
            
            if ($i >= $max_count) {
                break;
            }
            
            if (in_array($element, $online_users)) {
                $roomid = $elements[$element];
                if (isset($roomid) && !empty($roomid)) {
                    $onlineUsers[$element] = $roomid;
                    $i++;
                }
            }
            
        }
        
        $zhengli_time = Consume::getTime();
        $returnUsers = [];
        
        if (!empty($onlineUsers)) {
            $returnUsers = self::genUserInfos($onlineUsers, $userid);
        }
        $deal_time = Consume::getTime();
        
        $timeArr = array(
        "fetch_time"     => $fetch_time,
        "zhengli_time"    => $zhengli_time,
        "deal_time"        => $deal_time,
        "count"            => count($returnUsers),
        );
        
        return array('consume_time' => $timeArr, 'from' => $from,"offset" => ($offset+$num), 'feeds' => $returnUsers);
    }
    
}