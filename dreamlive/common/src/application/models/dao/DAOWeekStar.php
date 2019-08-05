<?php
class DAOWeekStar extends DAOProxy
{

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("weekstar");
    }

    public function getGiftByWeek($week='')
    {
        if (!$week) {
            $time=time();
            $week=date("Y", $time).'-'.date("W", $time);
        }
        return $this->getRow("select * from ".$this->getTableName()." where week=?", ['week'=>$week]);
    }

    public static function parseGift($gifts)
    {
        if (empty($gifts)) { return [];
        }
        return explode(',', $gifts);
    }
}
?>
