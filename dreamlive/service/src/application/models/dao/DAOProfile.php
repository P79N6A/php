<?php
class DAOProfile extends DAOProxy
{
    public static $PROFILE_KEY = array(
        "pushid" => "",
        "frozen" => "N",
        "timezone" => "",
        "option_dnd" => "N",
        "option_follow" => "Y",
        "option_reply" => "Y", // 有人评论了你
        "option_notice" => "Y", // 赞、转发通知
        "option_message" => "Y", // 官方消息
        "option_sixin" => "Y",
        "option_mishu" => "Y",
        "option_kefu" => "Y",
        "option_followed_sixin" => "N",
        "option_autosaveimage" => "Y",
        "option_student" => "Y",
        "option_check_device" => "N",
        "option_stranger_message"=> "Y",//是否接收陌生人消息 Y代表接收, N代表不接收
        "option_city_hidden" => "N", //同城隐藏 Y 隐藏 N不隐藏
    );

    public function __construct($userid = 0)
    {
        /* {{{ */
        $this->setDBConf("MYSQL_CONF_PASSPORT");
        $this->setShardId($userid);
        $this->setTableName("profile");
    }/* }}} */
    public function modProfile($arr_info)
    {
        /* {{{ */
        $arr_info["uid"] = $this->getShardId();
        $arr_info["addtime"] = date("Y-m-d H:i:s");
        $arr_info["modtime"] = date("Y-m-d H:i:s");
        return $this->replace($this->getTableName(), $arr_info);
    }/* }}} */
    public function getUserProfiles()
    {
        /* {{{ */
        $sql = "select " . $this->_getFields() . " from " . $this->getTableName() . " where uid=?";
        $list = $this->getAll($sql, $this->getShardId());
        
        $profiles = array();
        if ($list) {
            foreach ($list as $k => $v) {
                $profiles[$v["item"]] = $v["value"];
            }
        }
        
        $profiles = array_merge(self::$PROFILE_KEY, $profiles);
        
        return $profiles;
    }/* }}} */
    public function getUserProfile($item)
    {
        /* {{{ */
        $sql = "select value from " . $this->getTableName() . " where uid=? and item=?";
        $val = $this->getOne(
            $sql, array(
            $this->getShardId(),
            $item
            )
        );
        
        return $val ? $val : self::$PROFILE_KEY[$item];
    }/* }}} */
    
    public function getProfile($item)
    {
        /* {{{ */
        $sql = "select value from " . $this->getTableName() . " where uid=? and item=?";
        $val = $this->getOne(
            $sql, array(
            $this->getShardId(),
            $item
            )
        );
        
        return $val ? $val : self::$PROFILE_KEY[$item];
    }/* }}} */
    
    private function _getFields()
    {
        /* {{{ */
        return "uid, item, value";
    } /* }}} */
}
