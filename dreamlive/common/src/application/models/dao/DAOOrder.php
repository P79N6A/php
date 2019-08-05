<?php
class DAOOrder extends DAOProxy
{
    const ORDER_TYPE_COMMON=1;//正常单
    const ORDER_TYPE_ACTIVE=2;//运营单

    public function __construct($userid)
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setShardId($userid);
        $this->setTableName("order");
    }

    public function add($productid, $orderid, $uid,$type, $price, $currency, $num, $amount, $paystate, $extends)
    {
        $trade_info = array(
            "productid" => $productid,
            "orderid"   => $orderid,
            "uid"       => $uid,
            "type"      =>$type?$type:1,
            "price"     => $price,
            "currency"  => $currency,
            "num"       => $num,
            "amount"    => $amount,
            "paystate"  => $paystate,
            "extends"   => $extends ? (is_string($extends) ? $extends : json_encode($extends)) : '',
            "addtime"   => date('Y-m-d H:i:s'),
        );

        return $this->insert($this->getTableName(), $trade_info);
    }

    private function _getSelectFields()
    {
        /*{{{*/
        return "id, productid, orderid, uid, price, currency, num, amount, paystate";
    }/*}}}*/

    private function _getInsertFields()
    {
        return "productid, orderid, uid, price, currency, num, amount, paystate, addtime, extends";
    }

    private function _getFieldsPlaceholder($field)
    {
        return $field ? implode(',', array_fill(0, 1+substr_count($field, ','), '?')) : '';
    }

}
?>
