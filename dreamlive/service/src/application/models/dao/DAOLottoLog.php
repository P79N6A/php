<?php
class DAOLottoLog extends DAOProxy
{
    const LOG_STATUS_GET=1;//已领取
    const LOG_STATUS_NO_GET=2;//没领取

    const LOG_TYPE_FREE=1;//免费抽
    const LOG_TYPE_SINGLE=2;//收费单抽
    const LOG_TYPE_MUTI=3;//收费连抽

    const LOG_LIST_NUM_PAGE=50;

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_GAME");
        $this->setTableName("lotto_log");
    }

    public function add($uid,$type,$amount,$liveid,array $extends=[])
    {
        $now=date("Y-m-d H:i:s");
        $d=[
            'uid'=>$uid,
            'type'=>$type,
            'amount'=>$amount,
            'liveid'=>$liveid,
            'modtime'=>$now,
            'addtime'=>$now,
            'extends'=>json_encode($extends),
        ];

        return $this->insert($this->getTableName(), $d);
    }

    public function isUptoWeekLimit($prizeid,$limit)
    {
        if ($limit==0) { return true;
        }
        $now=date("Y-m-d");
        $w=date("w", strtotime($now));
        $dn=$w?$w-1:6;

        $start=date("Y-m-d", strtotime($now." - ".$dn." days"));
        $start.=" 00:00:00";

        $end=date("Y-m-d", strtotime($start." + 6 days"));
        $end.=" 23:59:59";

        $re=$this->getOne("select count(*) as num from ".$this->getTableName()." where prizeid=? and addtime between '".$start."' and '".$end."'", ['prizeid'=>$prizeid]);
        if ($re>$limit) { return true;
        }
        return false;
    }

    public function isInThreeMinitues($uid,$liveid,$timeLimit=180)
    {
        $re=$this->getRow("select id from ".$this->getTableName()." where uid=? and liveid=? and UNIX_TIMESTAMP(NOW())-UNIX_TIMESTAMP(addtime) < ".$timeLimit, ['uid'=>$uid,'liveid'=>$liveid]);
        if ($re) { return true;
        }
        return false;
    }

    public function modGameLotto($id, $extends)
    {
        return $this ->update($this->getTableName(), array('extends'=>$extends), 'id=?', [$id]);
    }

    //每天免费抽一次
    public function isFree($uid)
    {
        $re=$this->getRow("select id from ".$this->getTableName()." where uid=? and type=1 and date(addtime)=date(now())", ['uid'=>$uid]);
        if ($re) { return false;
        }
        return true;
    }

    public function getList($uid,$page=1,$num=50)
    {
        $page=$page<=0?1:$page;
        $num=!$num?self::LOG_LIST_NUM_PAGE:$num;
        $start=($page-1)*$num;
        $re=$this->getAll("select * from ".$this->getTableName()." where uid=? order by addtime desc limit ".$start.",".$num, ['uid'=>$uid]);
        return array('page'=>$page,'num'=>$num,'list'=>$re);
    }

    public function getInfoById($id)
    {
        return $this->getOne("select extends from {$this->getTableName()} where id=? limit 1", $id);
    }

    public function updateStatusGet($id,$index)
    {
        $log=$this->getRow("select * from ".$this->getTableName()." where id=?", ['id'=>$id]);
        if ($log) {
            $ext=json_decode($log['extends'], true);
            if (!$ext) { return;
            }
            foreach ($ext as &$i){
                if ($index==$i['i']) {
                    $i['status']=self::LOG_STATUS_GET;
                    break;
                }
            }
            $extends=json_encode($ext);
            return $this ->update($this->getTableName(), array('extends'=>$extends), 'id=?', [$id]);
        }
    }
}
?>
