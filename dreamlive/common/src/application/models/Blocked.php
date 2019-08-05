<?php
class Blocked
{
    const BLOCK_LIMIT = 10000;

    const HASHKEY_BLOCKED_KEY = 'hashkey:blocked:%d';

    public static function addBlocked($uid, $bid, $liveid)
    {
        $key = self::getBlockedKey($uid);
        
        $userinfo = User::getUserInfo($bid);  
        
        $userinfos = User::getUserInfo($uid);
        Messenger::sendUserBlocked($bid, $userinfo['nickname']."被{$userinfos['nickname']}拉黑了", $uid, $userinfo['nickname'], $userinfo['avatar'], $userinfo['exp'], $userinfo['level']);
        
        $dao_blocked = new DAOBlocked();
        if ($dao_blocked->addBlocked($uid, $bid)) {
            self::reload($uid);
            return true;
        }
        
        return false;
    }

    public function getBlockedUserInfo($userid, $offset, $num)
    {
        $dao_blocked = new DAOBlocked();
        $list = $dao_blocked->getBlockeds($userid, $offset, $num);
        
        $users = array();
        if ($list) {
            foreach ($list as $k => $v) {
                $user = new User();
                $userinfo = $user->getUserInfo($v["bid"]);
                if (! $userinfo) {
                    continue;
                }
                $userinfo["followed"] = false;
                $userinfo["blocked"] = true;
                $users[] = $userinfo;
            }
        }
        
        return $users;
    }
    
    public function getBlockedIds($userid)
    {
        $dao_blocked = new DAOBlocked();
        $list = $dao_blocked->getBlockeds($userid);
        
        $bids = array();
        foreach ($list as $v) {
            $bids[] = (string) $v["bid"];
        }
        return $bids;
    }
    
    public function getBlockedTotal($userid)
    {
        $dao_blocked = new DAOBlocked();
        return $dao_blocked->getTotal($userid);
    }

    public static function delBlocked($uid, $bid, $liveid)
    {
        $key = self::getBlockedKey($uid);
        
        $userinfo = User::getUserInfo($bid);
        $userinfos = User::getUserInfo($uid);
        Messenger::sendCancelUserBlocked($bid, $userinfo['nickname']."被{$userinfos['nickname']}取消拉黑了", $uid, $userinfo['nickname'], $userinfo['avatar'], $userinfo['exp'], $userinfo['level'], $liveid);
        
        $dao_blocked = new DAOBlocked();
        if ($dao_blocked->delBlocked($uid, $bid)) {
            self::reload($uid);
            return true;
        }
        
        return false;
    }

    public static function exists($uid, $bid)
    {
        $key = self::getBlockedKey($uid);
        
        $cache = Cache::getInstance("REDIS_CONF_CACHE", $uid);
        if ($cache->exists($key)) {
            return $cache->hget($key, $bid) ? true : false;
        } else {
            $dao_blocked = new DAOBlocked();
            $exists = $dao_blocked->exists($uid, $bid);
            
            self::reload($uid);
            
            return $exists ? true : false;
        }
        
        return false;
    }

    public static function reload($uid)
    {
        $key = self::getBlockedKey($uid);
        
        $dao_blocked = new DAOBlocked();
        $list = $dao_blocked->getBlockeds($uid, 0, self::BLOCK_LIMIT, true);
        
        $cache = Cache::getInstance("REDIS_CONF_CACHE", $uid);
        $cache->del($key);
        
        if ($list) {
            foreach ($list as $k => $v) {
                $bids[$v["bid"]] = 1;
            }
            $cache->hmset($key, $bids);
        } else {
            $cache->hset($key, $uid, 0);
        }
        
        return true;
    }

    public static function getBlockedKey($uid)
    {
        return sprintf(self::HASHKEY_BLOCKED_KEY, $uid);
    }
}
?>