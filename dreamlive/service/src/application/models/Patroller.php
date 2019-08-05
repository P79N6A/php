<?php
class Patroller
{
    
    const PATROLLER_MAX_LIMIT = 3;
    
    /**
     * 是否是主播的场控场控
     *
     * @param int $uid      用户
     *                 
     * @param  int $relateid 主播
     * @param  int $liveid   直播id
     * @return boolean
     */
    public function isPatroller($uid, $relateid, $liveid)
    {
        if (empty($relateid) && !empty($liveid)) {
            $live = new Live();
            $liveinfo = $live->getLiveInfo($liveid);
            
            $relateid = $liveinfo['uid'];
        }
        $redis_cache = Cache::getInstance('REDIS_CONF_CACHE');
        
        $score  = $redis_cache->zScore("Redis_patroller_author_" . $relateid, $uid);
        
        if (!empty($score)) {
            return true;
        } else {
            return false;
        }
        //         $dao_patroller = new DAOPatroller();
        //         return $dao_patroller->isPatroller($relateid, $uid);
    }
    
    public function addPatroller($author, $relateid, $liveid)
    {
        $dao_patroller = new DAOPatroller();
        
        $total = $dao_patroller->getTotal($author);
         
        if (!empty($liveid)) {
            $live = new Live();
            $liveinfo = $live->getLiveInfo($liveid);
    
            Interceptor::ensureNotFalse($author == $liveinfo['uid'], ERROR_BIZ_CHATROOM_NO_AUTHORITY_PATROLLER);
        }
    
        Interceptor::ensureNotFalse(!($author == $relateid), ERROR_BIZ_CHATROOM_PATROLLER_CANNOT_SELF);
         
        Interceptor::ensureNotFalse(!($total >= Patroller::PATROLLER_MAX_LIMIT), ERROR_BIZ_CHATROOM_PATROLLER_LIMIT);
        $patroller = new Patroller();
        Interceptor::ensureNotFalse(!$patroller->isPatroller($relateid, $author, $liveid), ERROR_BIZ_CHATROOM_PATROLLER_EXSIT);

        try {
            $result = $dao_patroller->addPatroller($author, $relateid);
            
            if ($result) {
                $redis_cache = Cache::getInstance('REDIS_CONF_CACHE');
                $redis_cache->zAdd("Redis_patroller_author_" . $author, time(), $relateid);
            }
        } catch (Exception $e) {
            Interceptor::ensureFalse(true, ERROR_PATROLLER_ADD, $author);
        }
        
    
        if ($liveid) {
            $watches          = Counter::get(Counter::COUNTER_TYPE_LIVE_WATCHES, $liveid);
            $userinfo = User::getUserInfo($relateid);
            $opratorinfo  = User::getUserInfo($author);
            
            $user_guard = UserGuard::getUserGuardRedis($relateid, $author);
            
            Messenger::sendLiveAddPatroller($liveid, "主播把".$userinfo['nickname'] . "设为场控", $relateid, $author, $userinfo['nickname'], $userinfo['avatar'], $userinfo['exp'], $userinfo['level'], $watches, $opratorinfo, intval($user_guard));
        }
    
        return true;
    }
    
    public function delPatroller($author, $relateid, $liveid)
    {
        if ($liveid) {
            $live = new Live();
            $liveinfo = $live->getLiveInfo($liveid);
    
            Interceptor::ensureNotFalse($author == $liveinfo['uid'], ERROR_BIZ_CHATROOM_NO_AUTHORITY_PATROLLER);
        }
         
        $dao_patroller = new DAOPatroller();
        //Interceptor::ensureNotFalse($dao_patroller->isPatroller($author, $relateid), ERROR_BIZ_CHATROOM_NO_AUTHORITY_PATROLLER);
        try {
            
            $result = $dao_patroller->delPatroller($author, $relateid);
            
            if ($result) {
                $redis_cache = Cache::getInstance('REDIS_CONF_CACHE');
                $redis_cache->zRem("Redis_patroller_author_" . $author, $relateid);
            }
            $dao_patroller_log = new DAOPatrollerLog();
            $dao_patroller_log->addPatrollerLog($author, $relateid, 'N', 'DELPAT', $liveid);
            
        } catch (Exception $e) {
            Interceptor::ensureFalse(true, ERROR_PATROLLER_DEL, $author);
        }
        
        if ($liveid) {
            $watches          = Counter::get(Counter::COUNTER_TYPE_LIVE_WATCHES, $liveid);
            $userinfo = User::getUserInfo($relateid);
            $opratorinfo  = User::getUserInfo($author);
            
            $user_guard = UserGuard::getUserGuardRedis($relateid, $author);
            
            Messenger::sendLiveDelPatroller($liveid, "主播把".$userinfo['nickname'] . "设为场控", $relateid, $author, $userinfo['nickname'], $userinfo['avatar'], $userinfo['exp'], $userinfo['level'], $watches, $opratorinfo, intval($user_guard));
        }
    
        return true;
    }
    
    public function getPatrollers($author)
    {
        
        $redis_cache = Cache::getInstance('REDIS_CONF_CACHE');
        $patroller_list = $redis_cache->zRevRange("Redis_patroller_author_" . $author, 0, -1);
        
        if (empty($patroller_list)) {
            return array();
        }
        //         $dao_patroller  = new DAOPatroller();
        //         $patroller_list = $dao_patroller->getPatrollers($author);
        $userid = Context::get('userid');
        
        $user = new User();
    
        $patrollers = array();
        foreach($patroller_list as $relateid) {
            
            $userinfo = $user->getUserInfo($relateid);
            
            $isforbidden = Forbidden::isForbidden($relateid);//是否被封禁
            if ($isforbidden) {//如果被封禁则删除该场控
                $dao_patroller = new DAOPatroller();
                $result = $dao_patroller->delPatroller($author, $relateid);
                
                if ($result) {
                    $redis_cache = Cache::getInstance('REDIS_CONF_CACHE');
                    $redis_cache->zRem("Redis_patroller_author_" . $author, $relateid);
                }
                $dao_patroller_log = new DAOPatrollerLog();
                $dao_patroller_log->addPatrollerLog($author, $relateid, 'N', 'DELPAT', 0);
                continue;
            }
            
            if(!empty($userinfo)) {
                $userinfo['isGuard'] = (int) UserGuard::getUserGuardRedis($userinfo['uid'], $author);
                $patrollers[] = $userinfo;
            }
        }
    
        return $patrollers;
    }
}

?>