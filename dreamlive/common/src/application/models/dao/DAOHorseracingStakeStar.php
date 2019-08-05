<?php
class DAOHorseracingStakeStar extends DAOProxy
{


    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_GAME");
        $this->setTableName("star_horseracing_stake");
    }
    public function insertStake($roundid,$userid,$liveid,$trackno,$amount,$orderid)
    {
        $param      = array(
            'roundid'       => $roundid,
            'uid'           => $userid,
            'liveid'        => $liveid,
            'trackno'       => $trackno,
            'amount'        => $amount,
            'orderid'       => $orderid,
            'addtime'       => date("Y-m-d H:i:s")
        );
        return $this -> insert($this->getTableName(), $param);
    }

    /**
     * 获取该局游戏押注最多赛道
     */
    public function getMostAmountTrack($roundid)
    {
        $sql="select trackno,sum(amount) as total from ".$this->getTableName().' where roundid=? group by trackno order by total desc limit 1';
        return $this->getRow($sql, ['roundid'=>$roundid]);
    }

    /**
     * 获取该局游戏的所有直播间,以及每个直播间押注金额
     */
    public function getAllLiveOfRound($roundid)
    {
        return $this->getAll("select liveid,sum(amount) as num from ".$this->getTableName()." where roundid=? group by liveid", ['roundid'=>$roundid]);
    }

    /**
     * 获取该局游戏的所有参与者
     */
    public function getAllStakerOfRound($roundid)
    {
        return $this->getAll("select uid,amount,trackno from ".$this->getTableName()." where roundid=?", ['roundid'=>$roundid]);
    }

    /**
     * 获取该局游戏的押注总金额
     */
    public function getStakeTotal($roundid)
    {
        return $this->getRow("select sum(amount) as num from ".$this->getTableName()." where roundid=?", ['roundid'=>$roundid]);
    }

    /**
     * 获取该局游戏，所有获胜的押注者或者输的
     */
    public function getAllStakeOfWin($roundid,$trackno)
    {
        return $this->getAll(
            "select uid,amount,liveid,trackno from ".$this->getTableName()." where roundid=? and trackno=?",
            ['roundid'=>$roundid,"trackno"=>$trackno]
        );
    }
 
    /**
     * 获取直播间押注前三
     */
    public function getTopThreeOfLive($roundid,$liveid)
    {
        return $this->getAll(
            "select uid,amount from ".$this->getTableName()." where roundid=? and liveid=? order by amount desc limit 3",
            ['roundid'=>$roundid,'liveid'=>$liveid]
        );
    }

    /**
     * 获取贡献前三
     */
    public function getTopThreeOfContribution($roundid,$liveid,$trackno)
    {
        return $this->getAll(
            "select uid , amount from ".$this->getTableName()." where roundid=? and liveid=? and trackno=? order by amount desc limit 3",
            ['roundid'=>$roundid,'liveid'=>$liveid,'trackno'=>$trackno]
        );
    }

    /**
     * 获取盈利前三
     */
    public function getWinTop3($roundid,$liveid,$trackno)
    {
        return $this->getAll(
            "select uid ,liveid,sum(amount) as num from ".$this->getTableName()." where roundid=? and liveid=? and trackno=? group by uid  order by num desc limit 3",
            ['roundid'=>$roundid,'liveid'=>$liveid,'trackno'=>$trackno]
        );
    }

    /**
     * 判断该局游戏，是否有人押注
     */
    public function getStakeNum($roundid)
    {
        return $this->getRow("select count(id) as num from ".$this->getTableName()." where roundid=?", ['roundid'=>$roundid]);
    }
    /**
     * 获取每个用户每场每个赛道押注中金额
     */
    public function getTracknoAmountNum($roundid,$uid,$trackno)
    {
        return $this->getOne("select sum(amount) from ".$this->getTableName()." where roundid=? and uid=? and trackno=?", array($roundid,$uid,$trackno));
    }
    /**
     * 获取押注获胜，奖励超过某个设定阈值的用户
     */
    public function getStakeAmountMax($roundid,$winno,$threshold)
    {
        return $this->getAll("select uid,sum(amount) as num ,liveid from ".$this->getTableName()." where roundid=? and trackno=?  group by uid having num >= ?", ['roundid'=>$roundid,'trackno'=>$winno,'num'=>$threshold]);
    }

    /**
     * 获取所有押注者，按uid分组的，按输赢分开的
     */
    public function getAllStakeGroupByUser($roundid,$trackno)
    {
        return $this->getAll("select uid ,sum(amount) as num from ".$this->getTableName()." where roundid=? and trackno=? group by uid", ['roundid'=>$roundid,'trackno'=>$trackno]);
    }

    /**
     * 获取全局top3榜单
     */
    public function getGlobalTop3($roundid,$winno)
    {
        return $this->getAll(
            "select uid,sum(amount) as num from ".$this->getTableName()." where roundid=? and trackno=? group by uid order by num desc limit 3",
            ['roundid'=>$roundid,'trackno'=>$winno]
        );
    }
    /**
     * 获取押注人数
     */
    public function getStakePeple($roundid)
    {
        $sql = "select count(DISTINCT (uid)) as num from {$this->getTableName()} where roundid=?";

        return $this->getRow($sql, $roundid);
    }
}
