<?php
class UserExp{
    private static $level_list = array(
        1 => 0,
        2 => 100,
        3 => 300,
        4 => 600,
        5 => 1000,
        6 => 1500,
        7 => 2100,
        8 => 2800,
        9 => 3600,
        10 => 4500,
        11 => 5500,
        12 => 6600,
        13 => 7800,
        14 => 9100,
        15 => 10500,
    );

    /**
     * 获取用户经验
     * @param int $userid
     * @return number
     */
    public static function getUserExp($userid)
    {
        $dao_user_exp = new DAOUserExp($userid);
        $user_exp_info = $dao_user_exp->getUserExp();
        return (int) $user_exp_info['exp'];
    }

    /**
     * 增加用户经验
     * @param unknown $userid
     * @param unknown $exp
     * @return boolean
     */
    public static function addUserExp($userid, $exp)
    {
        $dao_user_exp = new DAOUserExp($userid);
        $effected_rows = $dao_user_exp->incUserExp($exp);
        if (! $effected_rows) {
            $dao_user_exp->addUserExp($userid, $exp);
        }
        return true;
    }

    /**
     * 获取用户等级
     * @param int $userid
     * @return number|unknown
     */
    public static function getUserLevel($userid)
    {
        $exp = self::getUserExp($userid);
        return self::getLevelByExp($exp);
    }

    /**
     * 根据经验获取用户等级
     * @param int $exp
     * @return number|unknown
     */
    public static function getLevelByExp($exp)
    {
        if ($exp <= 0) {
            return 1;
        }
        $level_list = self::$level_list;
        $level_list = array_reverse($level_list, true);
        foreach ($level_list as $k => $v) {
            if ($exp >= $v) {
                return $k;
            }
        }
        return 1;
    }

    /**
     * 根据经验回去用户等级
     * @param int $level
     * @return number
     */
    public static function getExpByLevel($level)
    {
        $level_list = self::$level_list;
        return $level_list[$level] ? $level_list[$level] : 0;
    }

    public static function getExpbar($exp)
    {
        $level = self::getLevelByExp($exp);
        $level_list = self::$level_list;
        if($level > count($level_list)){
            return 100;
        }

        return ceil(100 * ($exp-$level_list[$level])/($level_list[$level+1]-$level_list[$level]));
    }
}
?>