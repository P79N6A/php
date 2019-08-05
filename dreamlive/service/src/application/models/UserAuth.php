<?php
class UserAuth
{
    public static function getUserAuth($uid,$auth=0)
    {
        $daoUserAuth=new DAOUserAuth();
        return $daoUserAuth->getUserAuth($uid, $auth);
    }

    public static function setUserAuth($uid,$auth,$switch)
    {
        $daoUserAuth=new DAOUserAuth();
        return $daoUserAuth->setUserAuth($uid, $auth, $switch);
    }

    public static function hasAuth($uid,$auth)
    {
        $r=self::getUserAuth($uid, $auth);
        if (!empty($r)) { return 'N';
        }
        return 'Y';
    }

    public static function openUserLiveAuth($uid)
    {
        return self::setUserAuth($uid, DAOUserAuth::AUTH_LIVE, DAOUserAuth::SWITCH_OFF);
    }

    public static function closeUserLiveAuth($uid)
    {
        return self::setUserAuth($uid, DAOUserAuth::AUTH_LIVE, DAOUserAuth::SWITCH_ON);
    }

    public static function hasLiveAuth($uid)
    {
        return self::hasAuth($uid, DAOUserAuth::AUTH_LIVE);
    }
}