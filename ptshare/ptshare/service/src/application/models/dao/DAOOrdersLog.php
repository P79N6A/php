<?php
class DAOOrdersLog extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("orderslog");
    }

    public function addOrderLog($orderid, $content, $remark, $operator)
    {
        $info = array(
          "orderid"=>$orderid,
          "content"=>$content,
          "remark"=>$remark,
          "operator"=>$operator
        );

        return $this->insert($this->getTableName(), $info);
    }
}
?>