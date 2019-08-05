<?php
class DAOLottery extends DAOProxy
{

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_MALL");
        $this->setTableName("lottery");
    }

    public function addQXC($uid, $sellid, $issue, $ptIssue, $number)
    {
        $info = array(
            "uid"     => $uid,
            "sellid"  => $sellid,
            "issue"   => $issue,
            "pt_issue" => $ptIssue,
            "number"  => $number,
            "addtime" => date('Y-m-d H:i:s'),
            "modtime" => date('Y-m-d H:i:s')
        );

        return $this->insert($this->getTableName(), $info);
    }

    public function getUserLottery($uid)
    {
        $sql = "select issue, pt_issue, number, number_format, result, status from " . $this->getTableName() . " where uid =? order by id desc limit 5000";
        
        return $this->getAll($sql, $uid);
    }

    public function getUserLotteryById($id)
    {
        $sql = "select uid, issue, pt_issue, number, number_format, result, status from " . $this->getTableName() . " where id =? ";
        
        return $this->getRow($sql, $id);
    }

    public function getAllUserLotterys($issue)
    {
        $sql = "select id, number from " . $this->getTableName() . " where issue =? and result = 0";
        
        return $this->getAll($sql, $issue);
    }

    public function setUserResult($id, $result, $format, $status)
    {
        $info = array(
            "modtime" => date('Y-m-d H:i:s')
        );
        if($result){
            $info['result'] = $result;
        }
        if($format){
            $info['number_format'] = $format;
        }
        if($status){
            $info['status'] = $status;
        }

        return $this->update($this->getTableName(), $info, "id=?", $id);
    }

    public function setUserOrderid($id, $orderid, $status)
    {
        $info = array(
            "modtime" => date('Y-m-d H:i:s')
        );
        if($orderid){
            $info['orderid'] = $orderid;
        }
        if($status){
            $info['status'] = $status;
        }

        return $this->update($this->getTableName(), $info, "id=?", $id);
    }

    public function isSellidUsed($sellid)
    {
        $sql = " select count(0) as cnt from ".$this->getTableName()." where sellid=?";

        return $this->getOne($sql, $sellid) > 0;
    }
}
?>
