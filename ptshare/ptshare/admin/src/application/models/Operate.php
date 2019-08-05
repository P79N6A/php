<?php
class Operate extends Table
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_ADMIN");
        $this->setTableName("operate");
        $this->setPrimary("operateid");
    }

    public function add($adminid, $name, $type, $relateid, $content, $context, $reason, $status, $uid=0)
    {
        $info = array(
            "adminid"=>$adminid,
            "name"=>$name,
            "type"=>$type,
            "relateid"=>$relateid,
            "content"=>json_encode($content),
            "context"=>json_encode($context),
            "reason"=>$reason,
            "status"=>$status,
            "uid"=>$uid,
            "addtime"=>date('Y-m-d H:i:s'),
        );
        
    	return $this->addRecord($info);
    }

    public function getOperateList($con,$start,$limit)
    {
        $params    = array();

        $condition .= ' status = ? ';
        $params[]  = $con['status']==2 ? 2 : 1;

        if($con['adminid']){
            $condition  .= " and adminid = ?";
            $params[]    = $con['adminid'];
        }

        if($con['type']){
            $condition  .= " and type = ?";
            $params[]    = $con['type'];
        }

        if($con['relateid']){
            $condition  .= " and relateid = ?";
            $params[]    = $con['relateid'];
        }

        if($con['uid']){
            $condition  .= " and uid = ?";
            $params[]    = $con['uid'];
        }


        if($con['name']){
            $condition  .= " and name = ?";
            $params[]    = $con['name'];
        }

        if($con['replateid']){
            $condition  .= " and replateid = ?";
            $params[]    = $con['replateid'];
        }

        if($con['start_time']){
            $condition  .= " AND addtime >= ? ";
            $params[]    = $con['start_time'];
        }
        if($con['end_time']){
            $condition  .= " AND addtime <= ? ";
            $params[]    = $con['end_time'];
        }
        $sql = "select * from {$this->getTableName()}";
        $sql.= $condition != "" ? " where " . $condition : "";
        $sql.= " order by operateid desc";
        $sql.= $limit > 0 ? " limit $start, $limit" : " limit 0,10 ";

        $total = $this->getOne("select count(*) from {$this->getTableName() }".($condition != "" ? " where " . $condition : ""),$params);

        $lists = array();

        if($total>0) $lists = $this->getAll($sql, $params);

        return array($total, $lists);
    }

    public function getOperateCount($name, $type, $stime, $etime)
    {
        $sql = "select count(*) from {$this->getTableName()} where name=? and type=? and addtime>=? and addtime<?";
        return $this->getOne($sql, array($name, $type, $stime, $etime));
    }

    public function getCountByOperator($name, $type, $stime, $etime){
        $sql = "select adminid, count(*) as count from operation where name=? and type=? and addtime>=? and addtime<? GROUP BY adminid";
        return $this->getAll($sql, array($name, $type, $stime, $etime));
    }
}
