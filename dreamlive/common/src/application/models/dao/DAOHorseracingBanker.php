<?php
class DAOHorseracingBanker extends DAOProxy
{
    const BANKER_RATE_ONE=1;//1倍 抢庄倍率
    const BANKER_RATE_THREE=3;//3倍
    const BANKER_RATE_FIVE=5;//5倍

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_GAME");
        $this->setTableName("horseracing_banker");
    }
    /**
     * 添加抢庄记录
     *
     * @param  int    $userid  用户id
     * @param  int    $roundid 场次id
     * @param  int    $liveid  直播id
     * @param  int    $amount  金额
     * @param  string $data    添加时间
     * @param  int    $orderid 订单id
     * @return bool;
     */
    public function insertBlanker($userid,$roundid,$liveid,$amount,$date,$orderid)
    {
        $param      = array(
                'uid'       => $userid,
                'roundid'   => $roundid,
                'liveid'    => $liveid,
                'amount'    => $amount,
                'addtime'   => $date,
                'modtime'   => $date,
                'orderid'   => $orderid
        );

        return $this->insert($this->getTableName(), $param);

    }
    public function updateBlanker($isrefunds,$blanker,$roundid)
    {
        $param  = array(
            'isrefunds'     => $isrefunds
        );

        return $this->update($this->getTableName(), $param, "roundid=? and uid = ?", array($roundid,$blanker));
    }

    /**
     * 获取庄家所在直播间
     */
    public function getLiveOfBanker($roundid,$bankerid)
    {
        return $this->getRow("select liveid from ".$this->getTableName()." where roundid=? and uid=? order by addtime limit 1", array('roundid'=>$roundid,'uid'=>$bankerid));
    }
}
