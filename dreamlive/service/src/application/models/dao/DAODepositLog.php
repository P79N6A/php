<?php
class DAODepositLog extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("depositlog");
    }

    public function add($source, $content)
    {
        $log_info = array(
            "source"  => $source,
            "content" => $content,
            "addtime" => date("Y-m-d H:i:s")
        );

        return $this->insert($this->getTableName(), $log_info);
    }

    public function getDepositLogInfo($orderid)
    {
        $sql = "select * from {$this->getTableName()} where content like '%$orderid%' order by id asc limit ?";
        return $this->getRow($sql, 1);
    }
}
