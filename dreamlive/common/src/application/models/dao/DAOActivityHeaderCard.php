<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018/1/12
 * Time: 10:33
 */
class DAOActivityHeaderCard extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_GAME");
        $this->setTableName("activity_header_card");
    }
    /*
     * 添加复活卡记录
     * */
    public function addCard($uid)
    {
        $info   = array(
            'uid'   => $uid,
            'num'   => 1,
            'addtime' => date("Y-m-d H:i:s")
        );
        return $this->insert($this->getTableName(), $info);
    }
    /*
     * 修改复活卡数量
     * */
    public function modCard($uid)
    {
        $this -> Execute("update {$this->getTableName()} set num=num+? where uid=? limit 1", [1,$uid]);
    }
    /*
     * 复活卡减1
     * */
    public function minusCard($uid)
    {
        $this -> Execute("update {$this->getTableName()} set num=num-? where uid=? limit 1", [1,$uid]);
    }
}
