<?php
class DAOHorseracingRound extends DAOProxy
{

    const TRACK_ONE=1;//1号赛道
    const TRACK_TWO=2;//2号赛道

    const GAME_STATUS_BANKER=1;//抢庄中
    const GAME_STATUS_STAKE=2;//押注中
    const GAME_STATUS_RUN=3;//跑马中
    const GAME_STATUS_DIVIDED=4;//已分账
    const GAME_STATUS_NO_STAKE_END=5;//无人压住结束

    const GAME_ANCHOR_DIVIDE_RATE=0.05;//5% 分成比例
    const GAME_SYSTEM_DIVIDE_REATE=0.05;//5%

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_GAME");
        $this->setTableName("horseracing_round");
    }

    public function addRound($bankerid,$amount,$winno,$status,$orderid,$extends,$modtime,$starttime,$endtime)
    {
        $data=[
            'bankerid'=>$bankerid,
            'amount'=>$amount,
            'winno'=>$winno,
            'status'=>$status,
            'orderid'=>$orderid,
            'extends'=>is_array($extends)?json_encode($extends):"",
            'addtime'=>date('Y-m-d H:i:s'),
            'modtime'=>$modtime,
            'starttime'=>$starttime,
            'endtime'=>$endtime,
        ];
        return $this->insert($this->getTableName(), $data);
    }

    public function updateRound($roundid,$bankerid,$amount,$winno,$status,$orderid,$extends,$endtime)
    {
        if ($bankerid) { $data['bankerid']=$bankerid;
        }
        if ($amount) { $data['amount']=$amount;
        }
        if($winno) { $data['winno']=$winno;
        }
        if($status) { $data['status']=$status;
        }
        if ($orderid) { $data['orderid']=$orderid;
        }
        if (!empty($extends)&&is_array($extends)) {
            $data['extends']=json_encode($extends);
        }
        if ($endtime) {
            $data['endtime']=$endtime;
        }
        $data['modtime']=date("Y-m-d H:i:s");
        return $this->update($this->getTableName(), $data, "roundid=?", $roundid);
    }

    public function getRoundById($roundid)
    {
        return $this->getRow("select * from ".$this->getTableName()." where roundid=?", ['roundid'=>$roundid]);
    }

    /**
     * 得到获胜赛道
     */
    public function getWinTrack($roundid)
    {
        return $this->getRow("select winno from ".$this->getTableName()." where roundid=?", ['roundid'=>$roundid]);
    }

    /*
     * 获取当下跑马游戏的状态
     * @param bool $locked 是否加锁
     * */
    public function getNewestInfo($locked=false)
    {
        $sql    = "select roundid,status,bankerid,winno,amount,extends,orderid from ".$this->getTableName()." order by roundid desc limit 1";
        $sql    .= $locked?' for update':'';

        return $this -> getRow($sql);
    }
    public function getCurrentRoundId()
    {
        $sql    = "select roundid from ".$this->getTableName()." order by addtime desc limit 1";
        return $this -> getOne($sql);
    }
    /**
     * 修改场次表中的庄家信息
     *
     * @param  int $userid  庄家id
     * @param  int $amount  抢庄金额
     * @param  int $roundid 场次id
     * @return mixed
     */
    public function updateBanker($userid,$amount,$roundid)
    {
        $param      = array(
            'bankerid'      => $userid,
            'amount'        => $amount
        );

        return $this->update($this->getTableName(), $param, "roundid=? and amount < ?", array($roundid,$amount));
    }
}
