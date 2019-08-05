<?php
class DAOKing extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("king");
    }

   
    public function getlist($uid)
    {
        /* {{{ */
        $month = date("Y-m", time());
        $sql = "select * from " . $this->getTableName() . " where uid=? and redate like '$month%'";
        
        return $this->getAll($sql, $uid);
    }
    /* }}} */

    public function getTodayLevel($uid)
    {    
        $mod   = (int)substr($uid, -2);
        
        $today = date("Y-m-d", time());
        $hour = date("H", time());
        /* {{{ */
        if($hour < 5) { //早上时， 统计昨天5点
            $yesterday = date("Y-m-d", time()-24*60*60);
            $sql = "select sum(amount) as sum from journal_$mod where uid=? and direct='IN' AND currency=1  and addtime>='$yesterday 05:00:00' ";
            $sum = $this->getRow($sql, $uid);

            $sql = "select sum(amount) as sum from journal_$mod where uid=? and direct='IN' AND currency=1  and addtime>='$yesterday 05:00:00' and (type=30 or type=31) ";
            $sum_door = $this->getRow($sql, $uid);

        } else{

            $sql = "select sum(amount) as sum from journal_$mod where uid=? and direct='IN' AND currency=1  and addtime>='$today 05:00:00' ";
            $sum = $this->getRow($sql, $uid);

            $sql = "select sum(amount) as sum from journal_$mod where uid=? and direct='IN' AND currency=1  and addtime>='$today 05:00:00' and (type=30 or type=31) ";
            $sum_door = $this->getRow($sql, $uid);

        }
        

        $config = new Config();
        $ranges = $config->getConfig('china', "ladder_of_money", "ios", '1.0.0.0');
        $ranges = json_decode($ranges['value'], true);

        $tmp = array();
        foreach ($ranges as $key =>$value) {
            if($value['max']) {
                $tmp[] = $value['max'];
            }
        }
        $ranges = $tmp;

        $i = 0;
        foreach ($ranges as $key1 =>$value1) {
            $sum['sum'] = $sum['sum'] + $sum_door['sum'];
            if($sum['sum'] >= $value1) {
                $i++;
            }
        }
        $level = $i;
        if($i) {
            $level = 9-$i;
        }

        return $level;
    }
    /* }}} */
    
}
