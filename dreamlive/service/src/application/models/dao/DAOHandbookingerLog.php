<?php
class DAOHandbookingerLog extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_GAME");
        $this->setTableName("handbookinger_log");
    }

    public function record($roundid,$orderid,$uid,$amount,$result_amount,$trackno,$status='Y',$extends=array())
    {
        $d=[
        'roundid'=>$roundid,
        'orderid'=>$orderid,
        'uid'=>$uid,
        'amount'=>$amount,
        'result_amount'=>$result_amount,
        'trackno'=>$trackno,
        'status'=>$status,
        'extends'=>is_array($extends)?json_encode($extends):json_encode([]),
        'addtime'=>date("Y-m-d H:i:s"),
        ];
        return $this->insert($this->getTableName(), $d);
    }
    public function getResultByUid($userid, $roundid)
    {
        return $this->getAll("select * from {$this->getTableName()} where uid=? and roundid=?", [$userid, $roundid]);
    }
}
?>
