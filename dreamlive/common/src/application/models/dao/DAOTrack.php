<?php

class DAOTrack extends DAOProxy
{
    const TRACK_TYPE_GIFT=1;
    const TRACK_TYPE_GUARD=2;
    const TRACK_TYPE_LOTTO=3;
    const TRACK_TYPE_DEFAULT=4;

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("track");
    }

    public function getTrackEffectByType($type,$amount=0)
    {
        $where=' where type=? ';
        if ($amount>0) {
            $where.=' and min_amount<='.$amount.' order by min_amount desc ';
        }
        $where.=' limit 1';
        return $this->getRow(
            "select * from ".$this->getTableName().$where,
            ['type'=>$type]
        );
    }
}
