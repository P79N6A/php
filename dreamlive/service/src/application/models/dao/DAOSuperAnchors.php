<?php

class DAOSuperAnchors extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PASSPORT");
        $this->setTableName("super_anchors");
    }

    public function search($nickname)
    {
        $sql = "SELECT * from {$this->getTableName()} WHERE nickname like ? ";
        
        return $this->getAll($sql, "%{$nickname}%");
    }

    public function importAnchors($data)
    {
        $rows = 0;
        foreach($data as $v){
            $rows += $this->addAnchor($v['uid'], $v['nickname']);
        }
        
        return $rows;
    }

    public function addAnchor($uid, $nickname)
    {
        $info = array(
            'uid' => $uid,
            'nickname' => $nickname,
        );
        
        return $this->replace($this->getTableName(), $info);
    }
}
