<?php

class DAOGameRobots extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_GAME");
        $this->setTableName("robots");
    }

    /**
     * 获取机器人列表
     */
    public function getGameRobotsAll()
    {
        $sql = "select " . $this->getFiled() . " from " . $this->getTableName() . "";

        return $this->getAll($sql);
    }

    /**
     * 随机得到一个机器人
     */
    public function getGameRobotsOne($base=2000)
    {
        //$base=2000;//最低倍率，基本不改，写死有利性能
        $all=$this->getAll("select uid from ".$this->getTableName());
        if (empty($all)) { throw new Exception("no robots account");
        }
        $result=[];
        foreach ($all as $i){
            $account_dao=new DAOAccount($i['uid']);
            $balance=$account_dao->getBalance(Account::CURRENCY_DIAMOND);
            if (!$balance||$balance<$base) { continue;
            }
            $result[]=$i;
        }
        if (empty($result)) { $result=$all;
        }
        $index=array_rand($result, 1);
        return $result[$index];
    }

    public function getFiled()
    {
        return 'id,uid';
    }

    /**
     * 判断是否为运营账号机器人
     */
    public function isRunRobot($uid)
    {
        return $this->getRow("select * from " . $this->getTableName() . " where uid=?", ['uid' => $uid]);
    }

    public function addRobot($uid)
    {
        $info = [
            'uid' => $uid,
            'addtime' => date('Y-m-d H:i:s'),
        ];

        return $this->insert($this->getTableName(), $info);
    }

    public function deleteRobot($uid)
    {
        return $this->delete($this->getTableName(), 'uid=?', $uid);
    }
}
