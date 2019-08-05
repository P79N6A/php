<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2017/12/18
 * Time: 16:01
 */
class DAOLoginGiftLog extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("login_gift_log");
    }
    public function addLoginGift($uid, $giftid, $num, $regin)
    {
        $info   = array(
            'uid'   => $uid,
            'giftid'=> $giftid,
            'num'   => $num,
            'regin' => $regin,
            'addtime'=> date("Y-m-d H:i:s")
        );

        return $this ->insert($this->getTableName(), $info);
    }
    public function isSendLog($uid, $stime, $etime)
    {
        return $this->getOne("select count(*) from {$this->getTableName()} where uid=? and addtime>? and addtime<=?", [$uid,$stime,$etime]);
    }
}
