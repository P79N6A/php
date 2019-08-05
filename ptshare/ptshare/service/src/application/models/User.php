<?php
class User
{

    const CONF_SESSION_DRIVER       = "REDIS_CONF_USER";
    const CONF_PRE_USER_CACHE       = "USER_CACHE_";
    const CONF_PRE_SESSION_USER_KEY = "USER:SESSIONKEY:";

    public function active($source, $rid, $iv, $encryptedData, $inviter, $type, $taskid)
    {
        Interceptor::ensureNotFalse(in_array($source, array('xcx', 'wx')), ERROR_PARAM_INVALID_FORMAT, 'source:$source');

        $oauth_user_info = '';
        try{
            $oauth_user_info = OAuth::getUserInfo($source, $rid, $iv, $encryptedData);
        } catch (OAuthException $e) {
            Logger::log('oauth', null, array('rid' => $rid, 'source' => $source, 'iv' => $iv, 'errno' => $e->getCode(), 'errmsg' => $e->getMessage()));
        }

        Interceptor::ensureNotEmpty($oauth_user_info, ERROR_USER_NOT_LOGIN);

        $dao_user = new DAOUser();
        $bindinfo = $dao_user->getUserInfoByRid($oauth_user_info['rid']);

        if(!empty($bindinfo)) {
            $uid = $bindinfo['uid'];
            $userinfo = self::getUserInfo($uid);
        } else {
            $rid       = $oauth_user_info['rid'];
            $openid    = $oauth_user_info['openid'];
            $nickname  = $oauth_user_info['nickname'] ? $oauth_user_info['nickname'] : '';
            $avatar    = $oauth_user_info['avatar'] ? $oauth_user_info['avatar'] : '';
            $gender    = $oauth_user_info['gender'] ? $oauth_user_info['gender'] : '';
            $city      = $oauth_user_info['city'] ? $oauth_user_info['city'] : '';
            $province  = $oauth_user_info['province'] ? $oauth_user_info['province'] : '';
            $country   = $oauth_user_info['country'] ? $oauth_user_info['country'] : '';

            $dao_user = new DAOUser();

            $user_persist = new UserPersist();
            $uid = $user_persist->getUserId();

            $salt = rand(100000, 999999);
            $addtime = date('Y-m-d H:i:s');
            $modtime = date('Y-m-d H:i:s');

            try {
                $dao_user = new DAOUser();
                $dao_user->startTrans();

                $dao_user->addUser($uid, $rid, $nickname, $avatar, $salt, $gender, $city, $province, $country, $addtime, $modtime, $openid);

                if($inviter){
                    $dao_inviter = new DAOUserInviter();
                    $dao_inviter->addUserInviter($uid, $inviter, $type, '', $addtime);
                }

                $dao_user->commit();
            } catch (Exception $e) {
                Logger::log('active_error', null, array('source' => $source, 'rid' => $rid, 'errno' => $e->getCode(), 'errmsg' => $e->getMessage()));
                $dao_user->rollback();

                throw $e;
            }

            $userinfo = self::getUserInfo($uid);
            $userinfo['isnew'] = true;
        }

        $userinfo['token'] = Session::getToken($uid);

        if($source == 'xcx' && $oauth_user_info['session_key']){
            self::setSessionKey($uid, $oauth_user_info['session_key']);
        }

        return $userinfo;
    }

    public static function reload($uid)
    {
        $cache = Cache::getInstance(self::CONF_SESSION_DRIVER);
        $cache->delete(self::CONF_PRE_USER_CACHE.$uid);

        self::getUserInfo($uid);

        return true;
    }

    public static function getUserInfo($uid)
    {
        $cache = Cache::getInstance(self::CONF_SESSION_DRIVER);
        $key = "USER_CACHE_$uid";
        if (!($userinfo = $cache->get($key))) {
            $dao_user = new DAOUser();
            $userinfo = $dao_user->getUserInfo($uid);

            if ($userinfo) {
                $exp = UserExp::getUserExp($uid);
                $userinfo = array(
                    'uid'      => (int)$userinfo['uid'],
                    'nickname' => (string)$userinfo['nickname'],
                    'avatar'   => (string)Util::joinStaticDomain($userinfo['avatar']?$userinfo['avatar']: ""),
                    'openid'   => (string)$userinfo['openid'],
                    'gender'   => (string)$userinfo['gender']?$userinfo['gender']: 'N',
                    'exp'      => (int)$exp,
                    'expbar'   => (int)UserExp::getExpbar($exp),
                    'level'    => (int)UserExp::getLevelByExp($exp),
                    'verified' => false,
                    'verifiedinfo' => "",
                    'phone'    => (string)$userinfo['phone'],
                    'channel'  => (string)$userinfo['channel'],
                    'addtime'  => (int)strtotime($userinfo['addtime']),
                    'vip_expiration_date' => 0, // vip 过期时间
                );
                if($uid == 10000000){
                    $userinfo["verified"] = true;
                    $userinfo["verifiedinfo"] = "葡萄分享官方账号";
                }

                $medals = UserMedal::getUserMedals($uid);
                
                if($medals){ // vip
                    foreach ($medals AS $v) {
                        if ($v['kind'] == 'vip') {
                            $userinfo['vip_expiration_date'] = strtotime($v['medal']);
                            break;
                        }
                    }
                }

                $cache->set($key, json_encode($userinfo));
            }
        } else {
            $userinfo = json_decode($userinfo, true);
            $userinfo['L2_cached'] = true;
        }

        $userinfo['vip'] = (bool) ($userinfo['vip_expiration_date'] > time());

        return $userinfo;
    }

    public function setUserInfo($uid, $source, $code, $iv, $encryptedData)
    {
        try{
            $oauth_user_info = OAuth::getUserInfo($source, $code, $iv, $encryptedData);
        } catch (OAuthException $e) {
            Logger::log('oauth', 'setUserInfo', array('code' => $code, 'source' => $source, 'iv' => $iv, 'errno' => $e->getCode(), 'errmsg' => $e->getMessage()));
        }

        if($source == 'xcx' && $oauth_user_info['session_key']){
            self::setSessionKey($uid, $oauth_user_info['session_key']);
        }

        Interceptor::ensureNotEmpty($oauth_user_info, ERROR_USER_NOT_LOGIN);

        $nickname = $oauth_user_info['nickname'] ? $oauth_user_info['nickname']: '';
        $avatar   = $oauth_user_info['avatar'] ? $oauth_user_info['avatar']    : '';
        $gender   = $oauth_user_info['gender'] ? $oauth_user_info['gender']    : '';
        $city     = $oauth_user_info['city'] ? $oauth_user_info['city']        : '';
        $province = $oauth_user_info['province'] ? $oauth_user_info['province']: '';
        $country  = $oauth_user_info['country'] ? $oauth_user_info['country']  : '';
        $phone    = $oauth_user_info['phone'] ? $oauth_user_info['phone']      : '';

        $dao_user = new DAOUser();
        $dao_user->setUserInfo($uid, $nickname, $avatar, $gender, $city, $province, $country, $phone);

        self::reload($uid);
        $userinfo = array();

        if($phone){
            $userinfo['phone'] = $phone;
        }
        if($nickname){
            $userinfo['nickname'] = $nickname;
        }
        if($avatar){
            $userinfo['avatar'] = $avatar;
        }

        return $userinfo;
    }

    public static function setSessionKey($uid, $sessionKey)
    {
        $cache = Cache::getInstance(self::CONF_SESSION_DRIVER);
        $key = self::CONF_PRE_SESSION_USER_KEY.$uid;

        return $cache->set($key, $sessionKey);
    }

    public static function getSessionKey($uid)
    {
        $cache = Cache::getInstance(self::CONF_SESSION_DRIVER);
        $key = self::CONF_PRE_SESSION_USER_KEY.$uid;

        return $cache->get($key);
    }

    public static function getUserInfos($uids)
    {
        if (! $uids) {
            return array();
        }

        if (! is_array($uids)) {
            $uids = array(
                $uids
            );
        }

        $cache = Cache::getInstance(self::CONF_SESSION_DRIVER);
        foreach ($uids as $uid) {
            $keys[] = self::CONF_PRE_USER_CACHE.$uid;
        }
        $results = $cache->mget($keys);

        $userinfos = array();
        foreach ($results as $row) {
            if ($row) {
                $v = json_decode($row, true);
                $user_info = self::format($v);

                $userinfos[$v['uid']] = $user_info;
            }
        }
        if(count($uids) != count($userinfos)){
            $rids = [];
            foreach($userinfos as $v){
                $rids[] = $v['uid'];
            }
            $diff = array_diff($uids, $rids);
            foreach($diff as $uid){
                $userinfo = self::getUserInfo($uid);
                if($userinfo){
                    $userinfos[$uid] = $userinfo;
                }
            }

        }

        return $userinfos;
    }

    public static function isNewUser($uid)
    {
        $userinfo = self::getUserInfo($uid);

        return (time() < $userinfo['addtime']+30)? true : false;
    }


    public static function getFollowUserInfos($uids)
    {
        if (! $uids) {
            return array();
        }

        if (! is_array($uids)) {
            $uids = array(
                $uids
            );
        }
        $userinfos = self::getUserInfos($uids);
        $followers = Counter::getBatchCount(Counter::COUNTER_TYPE_FOLLOWERS, $uids);
        $followings = Counter::getBatchCount(Counter::COUNTER_TYPE_FOLLOWINGS, $uids);

        $return = [];
        foreach($userinfos as $k=>$v){
            $v['followers'] = (int) $followers[$v['uid']];
            $v['followings'] = (int) $followings[$v['uid']];
            $return[$v['uid']] = $v;
        }

        return $return;
    }

    public static function format($userinfo)
    {
        if(!$userinfo){
            return [];
        }
        $data = array(
            'uid'          => (int)$userinfo['uid'],
            'nickname'     => (string)$userinfo['nickname'],
            'avatar'       => (string)$userinfo['avatar'],
            'gender'       => (string)$userinfo['gender'],
            'exp'          => (int)$userinfo['exp'],
            'expbar'       => (int)$userinfo['expbar'],
            'level'        => (int)$userinfo['level'],
            'verified'     => (bool)$userinfo['verified'],
            'verifiedinfo' => (string)$userinfo['verifiedinfo'],
            'channel'      => (string)$userinfo['channel'],
            'addtime'      => (int)$userinfo['addtime'],
            'vip'          => (bool)$userinfo['vip'],
            'vip_expiration_date' => (int)$userinfo['vip_expiration_date'],
        );

        return $data;
    }

    public static function refreshCode($uid, $code)
    {
        $sessionKey = OAuth::getSessionKey('xcx', $code);
        if($sessionKey){
            return self::setSessionKey($uid, $sessionKey);
        }

        return false;
    }

}
?>