<?php
class DAOTaskLogPay extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("tasklog");
    }

    public function checkUniq($uid,$awardid)
    {
        $sql="select * from ".$this->getTableName()." where uid=? and awardid=? and status='Y'";
        $result=$this->getRow($sql, [$uid,$awardid]);
        if (!empty($result)) {
            return true;//重复
        }else{
            return false;
        }
    }

    public function addTaskLog($uid,$awardid,$orderid,$status="Y")
    {
        $sql="select * from ".$this->getTableName()." where uid=? and awardid=?";
        $result=$this->getRow($sql, [$uid,$awardid]);
        if (!empty($result)) {
            return true;//重复
        }
        $info=array(
        "orderid"=>$orderid,
        "awardid"=>$awardid,
        "uid"=>$uid,
        "status"=>$status,
        "addtime"=>date("Y-m-d H:i:s"),
        );
        return $this->insert($this->getTableName(), $info);
    }

    public function modifyTaskLog($uid,$awardid,$orderid,$status="Y")
    {
        $info['status']=$status;
        return $this->update($this->getTableName(), $info, "uid=? and awardid=? and orderid=?", [$uid,$awardid,$orderid]);
    }
}
?>
