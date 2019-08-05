<?php
class UserMedal
{
    const KIND_TU_HAO      = "tuhao";
    const KIND_FOUNDER     = "founder";
    const KIND_V           = "v"; //red yellow blue purple  红 黄 蓝 紫
    const KIND_ACTIVITY    = "activity";
    const KIND_STUDENT     = "student";
    const KIND_ARTIST      = "artist";
    const KIND_GAME        = "game";
    const KIND_VIP         = "vip";
    const KIND_SKIN_COVER  = "skin_cover"; //封面
    const KIND_SKIN_FACEU  = "skin_faceu"; //faceu
    const KIND_SKIN_FOLLOW = "skin_follow"; //关注按钮
    const KIND_KING        = "king"; //王者
    const KIND_PK          = "pk";
    const KIND_RIDE="ride";//座驾
    const KIND_ANCHOR_TOKEN='anchor_token';//主播标识

    public static $_MEDAL_V = array(
        "1" => "yellow",
        "2" => "blue",
        "3" => "red",
        "4" => "purple",
    );

    public static $_MEDAL_TU_HAO = array(
        "1" => 20000,//消费20000钻 土豪 1
        "2" => 50000,
        "3" => 100000,
        "4" => 200000,
        "5" => 500000,
        "6" => 1000000,
        "7" => 5000000,
        "8" => 10000000,
        "9" => 30000000,
        "10" => 100000000,
    );

    public static function getUserMedal($userid, $kind)
    {
        /* {{{ */
        $dao_user_medal = new DAOUserMedal($userid);
        $user_medal_info = $dao_user_medal->getUserMedal($kind);

        return $user_medal_info['medal'];
    }
    /* }}} */
    public static function getUserMedals($userid)
    {
        /* {{{ */
        $dao_user_medal = new DAOUserMedal($userid);
        return $dao_user_medal->getUserMedals();
    }
    /* }}} */
    public static function addUserMedal($userid, $kind, $medal)
    {
        /* {{{ */
        $dao_user_medal = new DAOUserMedal($userid);
        return $dao_user_medal->addUserMedal($kind, $medal);
    } /* }}} */

    public static function delUserMedal($userid, $kind)
    {
        $dao_user_medal = new DAOUserMedal($userid);
        return $dao_user_medal->delete($userid, $kind);
    }
    public static function getTuhaoLevelByConsume($consume)
    {
        if($consume<20000) {
            return 0;
        }
        foreach(self::$_MEDAL_TU_HAO as $k=>$v){
            if($consume>=$v) {
                return $k;
            }
        }
        return 1;
    }
    public static function setTuhaoLevel($userid, $consume)
    {
        if($consume<20000) {
            return false;
        }
        $newLevel = 1;
        foreach(self::$_MEDAL_TU_HAO as $k=>$v){
            if($consume >= $v) {
                $newLevel = $k;
            }
        }
        $user_info = User::getUserInfo($userid);
        foreach($user_info['medal'] as $k=>$v){
            if($v['kind'] == self::KIND_TU_HAO) {
                $level = $v['medal'];
            }
        }
        if($newLevel != $level) {
            self::addUserMedal($userid, self::KIND_TU_HAO, $newLevel);
        }
    }

    public static function addV($userid, $kind, $type)
    {
        /* {{{ */
        $dao_user_medal = new DAOUserMedal($userid);
        return $dao_user_medal->addUserMedal($kind, self::$_MEDAL_V[$type]);
    } /* }}} */

    public static function delV($userid, $kind)
    {
        /* {{{ */
        $dao_user_medal = new DAOUserMedal($userid);
        return $dao_user_medal->delete($userid, $kind);
    } /* }}} */
}
