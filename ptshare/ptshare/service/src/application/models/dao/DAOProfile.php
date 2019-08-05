<?php
class DAOProfile extends DAOProxy
{
    public static $PROFILE_KEY = array(
        "social"    => "", // 社交账号
        "height"    => "",
        "weight"    => "",
        "shoe_size" => "",
        "tag"       => "",
        "signature" => "",
    );

    public function __construct($userid = 0)
    {
        $this->setDBConf("MYSQL_CONF_PASSPORT");
        $this->setShardId($userid);
        $this->setTableName("profile");
    }
    public function modProfile($arr_info)
    {
        $arr_info["uid"] = $this->getShardId();
        $arr_info["addtime"] = date("Y-m-d H:i:s");
        $arr_info["modtime"] = date("Y-m-d H:i:s");
        return $this->replace($this->getTableName(), $arr_info);
    }
    public function getUserProfiles()
    {
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
    }
    public function getUserProfile($item)
    {
        $sql = "select value from " . $this->getTableName() . " where uid=? and item=?";
        $val = $this->getOne($sql, array(
            $this->getShardId(),
            $item
        ));
        
        return $val ? $val : self::$PROFILE_KEY[$item];
    }
    
    public function getProfile($item)
    {
        $sql = "select value from " . $this->getTableName() . " where uid=? and item=?";
        $val = $this->getOne($sql, array(
                $this->getShardId(),
                $item
        ));
        
        return $val ? $val : self::$PROFILE_KEY[$item];
    }
    
    private function _getFields()
    {
        return "uid, item, value";
    } 
}
