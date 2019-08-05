<?php
class DAOStarDiamondCount extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("star_diamond_count");
    }

    
    public function insert($uid, $amount, $limit)
    {
        $sql = "INSERT INTO star_diamond_count (uid, date, amount) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE  amount= IF(amount+? > ?, amount , amount+?)";

        return $this->Execute($sql, array($uid, date("Y-m-d"), $amount, $amount, $limit, $amount))? true : false;
    }

    public function del($uid)
    {
        $sql = "DELETE from star_diamond_count where uid=? and date!=?";

        return $this->Execute($sql, array($uid, date("Y-m-d")));
    }
}
?>
