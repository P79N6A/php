<?php
class DAOLotteryConfig extends DAOProxy
{

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_MALL");
        $this->setTableName("lottery_config");
    }

    public function getCurrentLottery()
    {
        $time = time();

        $sql = "select issue,pt_issue,status,number,endtime from " . $this->getTableName() . " where startime<=? and endtime >?";
        
        return $this->getRow($sql, [$time, $time]);
    }
    
    public function addLotteryConfig($issue)
    {
        $info = array(
            "issue"   => $issue,
           
            "addtime" => date('Y-m-d H:i:s'),
            "modtime" => date('Y-m-d H:i:s')
        );

        return $this->insert($this->getTableName(), $info);
    }

    public function addQxcConfig($issue, $ptIssue, $startime, $endtime)
    {
        $info = array(
            "issue"    => $issue,
            "pt_issue" => $ptIssue,
            "startime" => $startime,
            "endtime"  => $endtime,
           
            "addtime" => date('Y-m-d H:i:s'),
            "modtime" => date('Y-m-d H:i:s')
        );

        return $this->insert($this->getTableName(), $info);
    }

    public function setLotteryConfig($issue, $status, $number)
    {
        $info = array(
            "modtime" => date('Y-m-d H:i:s')
        );
        if($status){
            $info['status'] = $status;
        }
        if($number){
            $info['number'] = $number;
        }

        return $this->update($this->getTableName(), $info, "issue=?", array($issue));
    }

    public function getLotteryNumber($issue)
    {
        $sql = "select number from " . $this->getTableName() . " where issue=?";
        
        return $this->getOne($sql, [$issue]);
    }

    public function getLotteryStatus($issue)
    {
        $sql = "select status from " . $this->getTableName() . " where issue=?";
        
        return $this->getOne($sql, [$issue]);
    }

    public function getLotteryInfo($issue)
    {
        $sql = "select * from " . $this->getTableName() . " where issue=?";
        
        return $this->getRow($sql, [$issue]);
    }

    public function existTimeLottery($time)
    {

        $sql = "select count(*) as cnt from " . $this->getTableName() . " where startime<=? and endtime >?";
    
        return $this->getOne($sql, [$time, $time]) > 0;
    }

    public function existPtIssueLottery($ptIssue)
    {

        $sql = "select count(*) as cnt from " . $this->getTableName() . " where pt_issue=?";
    
        return $this->getOne($sql, [$ptIssue]) > 0;
    }

    public function getWinNumberByIssue($issue)
    {
        $winNumbers = array();

        $sql = "select number,issue from {$this->getTableName()} where issue in (" . implode(",", $issue) . ")";
        $list = $this->getAll($sql, false);
        foreach ($list as $v) {
            $winNumbers[$v["issue"]] = $v['number'];
        }

        return $winNumbers;
    }
}
?>
