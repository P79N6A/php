<?php
class DAOHorseracingStat extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_GAME");
        $this->setTableName("horseracing_stat");
    }

    public function addData($total_num,$stake_num,$round_num,$stake_amount,$stake_income,$banker_num,$banker_income,$robots_income,$total_split,$total_income,$day,$robots_deposit)
    {
        $data=[
        'total_num'=>$total_num,
        'stake_num'=>$stake_num,
        'round_num'=>$round_num,
        'stake_income'=>$stake_income,
        'stake_amount'=>$stake_amount,
        'banker_num'=>$banker_num,
        'banker_income'=>$banker_income,
        'robots_income'=>$robots_income,
        'total_split'=>$total_split,
        'total_income'=>$total_income,
        'day'=>$day,
        'robots_deposit'=>$robots_deposit,
        'addtime'=>date("Y-m-d H:i:s"),
        ];
        return $this->insert($this->getTableName(), $data);
    }
}
?>
