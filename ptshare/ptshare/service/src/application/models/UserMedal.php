<?php
class UserMedal
{
    const KIND_VIP = "vip";

    public static function getUserMedal($userid, $kind)
    { /* {{{ */
        $dao_user_medal = new DAOUserMedal($userid);
        $user_medal_info = $dao_user_medal->getUserMedal($kind);

        return $user_medal_info['medal'];
    }
 /* }}} */
    public static function getUserMedals($userid)
    { /* {{{ */
        $dao_user_medal = new DAOUserMedal($userid);
        return $dao_user_medal->getUserMedals();
    }
 /* }}} */
    public static function addUserMedal($userid, $kind, $medal)
    { /* {{{ */
        $dao_user_medal = new DAOUserMedal($userid);
        return $dao_user_medal->addUserMedal($kind, $medal);
    } /* }}} */

    public static function delUserMedal($userid, $kind)
    {
        $dao_user_medal = new DAOUserMedal($userid);
        return $dao_user_medal->delete($userid, $kind);
    }

    public function addVip($userid)
    {
        $dao = new DAOUserMedal($userid);
        $medal = $dao->getUserMedal(self::KIND_VIP);
        if($medal){
            $startime = time();
            if(strtotime($medal) > $startime){
                $startime = strtotime($medal);
            }
            $endtime = date("Y-m-d H:i:s", strtotime("+1 year", $startime));
        }else{
            $endtime = date("Y-m-d H:i:s", strtotime("+1 year"));
        }
        $dao->addUserMedal(self::KIND_VIP, $endtime);

        User::reload($userid);
        
        return true;
    }

}
