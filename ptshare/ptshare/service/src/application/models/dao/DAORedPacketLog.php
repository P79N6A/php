<?php
class DAORedPacketLog extends DAOProxy
{
    public function __construct($userid)
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setShardId($userid);
        $this->setTableName("red_packet_log");
    }

    public function addRedPacketLog($uid, $relateid, $redid, $type, $amount, $flags)
    {
        $option = array(
            'uid'      => $uid,
            'relateid' => $relateid,
            'redid'    => $redid,
            'type'     => $type,
            'flags'    => $flags,
            'amount'   => $amount,
            'addtime'  => date("Y-m-d H:i:s")
        );
        return $this->insert($this->getTableName(), $option);
    }

    public function updateRedPacketLog($logid, $orderid){
        $condition = ' id=? ';
        $params = array(
            'id' => $logid
        );
        $option = array(
            'orderid' => $orderid,
            'modtime' => date("Y-m-d H:i:s")
        );
        return $this->update($this->getTableName(), $option, $condition, $params);
    }

    /**
     * 获取领取红包详情
     * @param int $userid
     * @param int $relateid
     * @param int $redid
     * @param string $type
     * @return array
     */
    public function getRedPacketLogInfo($userid, $relateid, $redid, $type){
        $sql = " SELECT * FROM ".$this->getTableName()." WHERE uid=? and relateid=? and redid=? and type=? order by id desc limit 1";
     	$sql1 = " SELECT * FROM ".$this->getTableName()." WHERE uid='{$userid}' and relateid='{$relateid}' and redid='{$redid}' and type='{$type}' order by id desc limit 1";
//         //echo $sql;
     	Logger::log("account", "sql :", array("uid" => $uid, "userid" => $userid, "redid" => $redid, "sql" => $sql1));

//         return $this->getRow($sql, false,false);
        return $this->getRow($sql, array('uid'=>$userid, 'relateid'=>$relateid, 'redid'=>$redid, 'type'=>$type));
    }

    /**
     * 红包列表
     * @param int $uid
     * @param string $type
     * @param int $num
     * @param int $offset
     */
    public function getUserTaskLogsList($uid, $type, $num, $offset){
        $where = "";
        if ($offset > 0) {
            $where .= " and id<" . $offset . " ";
        }
        $sql = " SELECT * FROM " . $this->getTableName() . " WHERE uid=? and type=? ";
        $sql .= $where;
        $sql .= " ORDER BY id DESC LIMIT " . $num;
        return $this->getAll($sql, array('uid' => $uid, 'type'=>$type));
    }

    /**
     * 总数
     * @param int $uid
     * @param string $type
     */
    public function getUserTaskLogsTotal($uid, $type){
        $sql = " SELECT count(*) as cnt FROM " . $this->getTableName() . " WHERE uid=? and type=?  ";
        return $this->getOne($sql, array('uid' => $uid, 'type'=>$type));
    }

    /**
     * 是否有下一页数据
     * @param int $uid
     * @param string $type
     * @param int $offset
     */
    public function getMoreUserTaskLogs($uid, $type, $offset){
        $where = "";
        if ($offset > 0) {
            $where .= " and id<" . $offset . " ";
        }
        $sql = " SELECT count(id) as cnt FROM " . $this->getTableName() . " WHERE uid=? and type=? ";
        $sql .= $where;
        return $this->getOne($sql, array('uid' => $uid, 'type'=>$type));
    }
}
?>
