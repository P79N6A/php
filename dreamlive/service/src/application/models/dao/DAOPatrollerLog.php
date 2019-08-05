<?php
class DAOPatrollerLog extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_MESSAGE");
        $this->setTableName("patroller_log");
    }
    
    public function addPatrollerLog($uid, $relateid, $ispatroller, $action, $liveid)
    {
        $info = array(
          "uid"            => $uid,
          "relateid"    => $relateid,
          "action"        => $action,
          "liveid"        => $liveid,
          "ispatroller" => $ispatroller,
          "addtime"        => date("Y-m-d H:i:s")
        );
        
        return $this->replace($this->getTableName(), $info);
    }

}
?>