<?php
class DAOShareCallback extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_LIVE");
        $this->setTableName('share_callback');
    }

    public function add($uid, $shareid, $type, $relateid, $target)
    {
        $data = array(
        'uid'         => $uid,
        'shareid'     => $shareid,
            'relateid'     => $relateid,
            'type'         => $type,
        'target'     => $target,
            'addtime'     => date('Y-m-d H:i:s'),
        );
        return $this->insert($this->getTableName(), $data);
    }
    
    public function getCount($type, $relateid) 
    {
        $sql = "select count(*) from {$this->getTableName()} where relateid=? and type = ?";
        
        return $this->getOne($sql, array($relateid, $type));
    }

    public function getUserShare($uid, $type, $liveid, $begintime)
    {
        $sql = "SELECT * FROM {$this->getTableName()} WHERE uid=? AND type=? AND relateid=? AND addtime>?";

        return $this->getAll($sql, [$uid, $type, $liveid, $begintime]);
    }
    public function getCountShare($uid)
    {
        $sql = "select count(*) from {$this->getTableName()} where uid=?";

        return $this->getOne($sql, [$uid]);
    }
}
