<?php
class DAOHandbookingerRound extends DAOProxy
{
    const STATUS_PREPARE=1;
    const STATUS_STAKE=2;
    const STATUS_VIDEO=3;
    const STATUS_RUN=4;
    const STATUS_SETTLE=5;//分账和结束

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_GAME");
        $this->setTableName("handbookinger_round");
    }
    
    public function createNewRound($orderid,$extends=array())
    {
        $now=date("Y-m-d H:i:s");
        $d=[
        'winno'=>0,
        'status'=>self::STATUS_PREPARE,
        'orderid'=>$orderid,
        'url'=>'',
        'extends'=>is_array($extends)?json_encode($extends):$extends,
        'addtime'=>$now,
        'modtime'=>$now,
        ];
        return $this->insert($this->getTableName(), $d);
    }

    public function getCurRound()
    {
        return $this->getRow("select * from ".$this->getTableName()." order by roundid desc limit 1");
    }

    public function modifyRound($roundid,$staus=0,$winno=0,$url='')
    {
        $d=[];
        if ($staus) {
            $d['status']=$staus;
        }

        if ($winno) {
            $d['winno']=$winno;
        }

        if ($url) {
            $d['url']=$url;
        }
        if (empty($d)) { return;
        }
        $d['modtime']=date("Y-m-d H:i:s");
        return $this->update($this->getTableName(), $d, "roundid=?", $roundid);
    }

    public function modifyExt($roundid,$ext=array())
    {
        if (empty($ext)) { return;
        }
        $round=$this->getRoundById($roundid);
        if (!$round) { return;
        }
        $_ext=json_decode($round['extends'], true);
        $_ext=$_ext?$_ext:[];
        $result=array_merge($_ext, $ext);
        $d['extends']=json_encode($result);
        $d['modtime']=date("Y-m-d H:i:s");
        return $this->update($this->getTableName(), $d, "roundid=?", $roundid);
    }

    public function getRoundById($roundid)
    {
        return $this->getRow("select * from ".$this->getTableName()." where roundid=?", $roundid);
    }
}
?>
