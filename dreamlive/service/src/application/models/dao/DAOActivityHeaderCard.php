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
    public function addCard($uid, $num=1)
    {
        $info   = array(
            'uid'   => $uid,
            'num'   => $num,
            'addtime' => date("Y-m-d H:i:s")
        );
        return $this->insert($this->getTableName(), $info);
    }
    /*
     * 修改复活卡数量
     * */
    public function modCard($uid, $num = 1)
    {
        $this -> Execute("update {$this->getTableName()} set num=num+? where uid=? limit 1", [$num,$uid]);
    }
    /*
     * 复活卡减1
     * */
    public function minusCard($uid)
    {
        $this -> Execute("update {$this->getTableName()} set num=num-? where uid=? limit 1", [1,$uid]);
    }
    /*
     *
     * */
    public function getCardById($uid)
    {
        $this -> getOne("select * from {$this->getTableName()} where uid=?", $uid);
    }
    public function modCardByUid($uid,$num)
    {
        $this -> Execute("update {$this->getTableName()} set num=? where uid=? limit 1", [$num,$uid]);
    }
}
