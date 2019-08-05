<?php
class DAOWithdrawCorporate extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("withdraw_corporate");
    }

    public function replaceinto($familyid,$author_percent,$pay_percent,$three_pay_percent,$is_receipt,$is_receipt_real, $rate, $settlement)
    {
        $info = array(
            "familyid"          => $familyid,
        "is_receipt"        => $is_receipt?$is_receipt:0,
            "is_receipt_real"   => $is_receipt_real?$is_receipt_real:0,
            "family_percent"    => $author_percent?$author_percent:0,
            "pay_percent"       => $pay_percent?$pay_percent:0,
            "three_pay_percent" => $three_pay_percent?$three_pay_percent:0,
        "rate"              => $rate?$rate:'3',
        "settlement"        => $settlement?$settlement:'1',
            "addtime"           => date("Y-m-d H:i:s"),
        "modtime"           => date("Y-m-d H:i:s")
        );
        $sql = "REPLACE INTO {$this->getTableName()} SET familyid=?, is_receipt=?, is_receipt_real=?, family_percent=?, pay_percent=?, three_pay_percent=?, rate=?, settlement=?, addtime=?, modtime=?";
        
        return $this->Execute($sql, $info);
    }

    public function get($familyid)
    {
        $sql = "select * from {$this->getTableName()} where familyid =? limit 1";

        return $this->getRow($sql, $familyid);
    }

    
}
?>