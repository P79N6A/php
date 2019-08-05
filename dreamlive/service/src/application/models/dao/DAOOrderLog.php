<?php
class DAOOrderLog extends DAOProxy
{

    const SOURCE_BUY=1;
    const SOURCE_TASK=2;
    const SOURCE_ACTIVE=3;
    const SOURCE_VIP=4;
    const SOURCE_LOTTO=5;

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("order_log");
    }


    public function add($productid,$orderid,$uid,$type,$price,$currency,$num,$amount,$paystate,$source=1,$extends=array())
    {
        $d=[
        'productid'=>$productid,
        'orderid'=>$orderid,
        'uid'=>$uid,
        'type'=>$type,
        'price'=>$price,
        'currency'=>$currency,
        'num'=>$num,
        'amount'=>$amount,
        'paystate'=>$paystate,
        'source'=>$source,
        'extends'=>json_encode($extends),
        'addtime'=>date("Y-m-d H:i:s"),
        ];
        return $this->insert($this->getTableName(), $d);
    }

}
?>
