<?php
/**
 * Class DAOHorseracingLog
 * 记录每一局游戏的所有参与者，的资金变动
 */
class DAOHorseracingLogStar extends DAOProxy
{

    const TYPE_BANKER=1;//庄
    const TYPE_STAKE=2;//押注
    const TYPE_ANCHOR=3;//主播
    const TYPE_SYSTEM=4;//系统

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_GAME");
        $this->setTableName("star_horseracing_log");
    }

    /**
     * 添加日志
     */
    public function addLog($roundid,$uid,$orderid,$type,$liveid=0,$amount=0,$result_amount=0,$trackno=0,$settled="Y",$extends=[])
    {
        if (!$roundid) { throw new Exception("addLog: roundid is null");
        }
        if (!$uid) { throw new Exception("addLog: uid is null");
        }
        if (!$orderid) { throw new Exception("addLog: orderid is null");
        }
        if (!$type) { throw new Exception("addLog: type is null");
        }
        $data=[
            'roundid'=>$roundid,
            'uid'=>$uid,
            'orderid'=>$orderid,
            'type'=>$type,
            'liveid'=>empty($liveid)?0:$liveid,
            'amount'=>empty($amount)?0:$amount,
            //'result_amount'=>$result_amount?$result_amount:0,
            'result_amount'=>!$result_amount?0:$result_amount,
            'trackno'=>$trackno,
            'settled'=>$settled,
            'extends'=>json_encode($extends),
            'addtime'=>date('Y-m-d H:i:s'),
        ];
        return $this->insert($this->getTableName(), $data);
    }
    public function getWinAmount($userid,$roundid)
    {
        $sql = "SELECT sum(result_amount - amount) AS num FROM {$this->getTableName()} WHERE roundid=? AND uid=? AND amount>0 limit 1";

        return $this->getAll($sql, array($roundid,$userid));
    }
    public function getLiveSumRunningWaters($start_time,$end_time)
    {
        $sql    = "select a.liveid,sum(a.amount)*0.01 as num,count(distinct a.uid) as people from {$this->getTableName()} a 
where a.liveid!=0 and a.type in (1,2) and liveid!=0 and a.addtime > ? and a.addtime < ? group by a.liveid";

        return $this -> getAll($sql, array(date("Y-m-d H:i:s", $start_time),date("Y-m-d H:i:s", $end_time)));
    }
}
