<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018/1/17
 * Time: 10:54
 */
class DAOActivityHeaderLog extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_GAME");
        $this->setTableName("activity_header_card");
    }
    public function addHeaderLog($uid, $amount, $addtime)
    {
        $info   = array(
            'uid'       => $uid,
            'amount'    => $amount,
            'addtime'   =>$addtime,
        );
        $this->insert($this->getTableName(), $info);
    }
}
