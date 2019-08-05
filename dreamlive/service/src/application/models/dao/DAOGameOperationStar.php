<?php

class DAOGameOperationStar extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_GAME");
        $this->setTableName("star_game_operation");
    }
    
    public function getList()
    {
        $sql = "select * from " . $this->getTableName();
        return $this->getAll($sql);
    }

    /**
     * 判断是否为运营账号
     */
    public function isRunAccount($uid)
    {
        return $this->getRow("select * from " . $this->getTableName() . " where uid=?", ['uid' => $uid]);
    }

    public function addOperation($uid)
    {
        $info = [
            'uid' => $uid,
            'addtime' => date('Y-m-d H:i:s'),
        ];

        return $this->insert($this->getTableName(), $info);
    }

    public function deleteOperation($uid)
    {
        return $this->delete($this->getTableName(), 'uid=?', $uid);
    }
}

