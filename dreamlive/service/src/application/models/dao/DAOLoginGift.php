<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2018/1/4
 * Time: 11:27
 */
class DAOLoginGift extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("login_gift");
    }
    /*
     * 获取登录送的礼物
     * */
    public function getAllGifts()
    {
        return $this -> getAll("select * from {$this->getTableName()}");
    }
}
