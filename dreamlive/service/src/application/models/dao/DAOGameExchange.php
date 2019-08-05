<?php
class DAOGameExchange extends DAOProxy
{
    const DIRECT_IN=1;
    const DIRECT_OUT=2;

    const STATUS_PREPARE=0;
    const STATUS_SUCCESS=1;
    const STATUS_FAIL=2;


    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("game_exchange");
    }

    public function add($uid,$orderid,$diamond_amount,$game_amount,$game,$direct,$other_orderid,$remark)
    {
        $now=date("Y-m-d H:i:s");
        $d=array(
        'uid'=>$uid,
        'orderid'=>$orderid,
        'diamond_amount'=>$diamond_amount,
        'game_amount'=>$game_amount,
        'game'=>$game,
        'direct'=>$direct,
        'status'=>self::STATUS_PREPARE,
        'other_orderid'=>$other_orderid,
        'remark'=>$remark,
        'modtime'=>$now,
        'addtime'=>$now,
        );
        return $this->insert($this->getTableName(), $d);
    }

    public function status($orderid,$status)
    {
        if (!in_array($status, array(self::STATUS_SUCCESS,self::STATUS_FAIL))) { throw new Exception("status error");
        }
        $r=$this->info($orderid);
        if (empty($r)) { throw new Exception("orderid error ");
        }
        $d=array(
        'status'=>$status,
        );
        return $this->update($this->getTableName(), $d, 'orderid=?', array('orderid'=>$orderid));
    }

    public function info($orderid)
    {
        return $this->getRow("select * from ".$this->getTableName()." where orderid=?", array('orderid'=>$orderid));
    }

    public function byId($id)
    {
        return $this->getRow("select * from ".$this->getTableName()." where id=?", array('id'=>$id));
    }

    public function byUid($uid)
    {

    }

    public function byGame($game)
    {

    }
}
?>
