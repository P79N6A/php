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
        /* {{{ */
        $sql = "select sum(amount) as sum from journal_$mod where uid=? and direct='IN' AND currency=1  and addtime>='$today 00:00:00' ";
        $sum = $this->getRow($sql, $uid);
        $ranges = array(1000, 3000, 6000, 10000, 30000, 60000, 100000, 300000);
        $i = 0;
        foreach ($ranges as $key1 =>$value1) {
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
