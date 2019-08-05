<?php
class DAOPay extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("pay");
    }

    /**
     * 支付prepare
     * @param int $uid
     * @param string $orderid
     * @param string $source
     * @param string $currency
     * @param float $amount
     * @param string $tradeid
     * @param array $extends
     */
    public function prepare($uid, $orderid, $source, $currency, $amount, $tradeid, $coupon = 0, $extends = [], $type = '')
    {
        $option = array(
            "uid"      => $uid,
            "orderid"  => $orderid,
            "source"   => $source,
            "tradeid"  => $tradeid,
            "currency" => $currency,
            "amount"   => $amount,
        	"coupon"   => $coupon,
            "addtime"  => date("Y-m-d H:i:s"),
            "modtime"  => date("Y-m-d H:i:s"),
            "extends"  => is_array($extends) ? json_encode($extends) : json_encode([])
        );
        
        if (!empty($type)) {
        	$option['type'] = $type;
        }
        return $this->insert($this->getTableName(), $option);
    }

    public function modify($uid, $orderid, $source, $currency, $amount, $tradeid, $coupon, $extends = [])
    {
    	$condition = ' orderid=? ';
    	$params = [ 'orderid' => $orderid];

    	$option = array(
    			"uid"      => $uid,
    			"source"   => $source,
    			"tradeid"  => $tradeid,
    			"currency" => $currency,
    			"amount"   => $amount,
    			"coupon"   => $coupon,
    			"addtime"  => date("Y-m-d H:i:s"),
    			"modtime"  => date("Y-m-d H:i:s"),
    			"extends"  => is_array($extends) ? json_encode($extends) : json_encode([])
    	);

    	return $this->update($this->getTableName(), $option, $condition, $params);
    }

    /**
     * 设置支付状态
     * @param string $orderid
     * @param int $status
     * @param string $tradeid
     * @return boolean
     */
    public function setPayStatus($orderid, $status, $tradeid = 0)
    {
    	$update= array();
        $update["status"] = $status;
        if ($tradeid) {
        	$update["transaction_id"] = $tradeid;
        }

        if ($status == 'R') {
        	$update['refund_time'] = date("Y-m-d H:i:s");
        }

        $update["modtime"] = date("Y-m-d H:i:s");
        return $this->update($this->getTableName(), $update, "orderid=?", $orderid) ? true : false;
    }

    
    public function setPayCoupon($orderid, $coupon)
    {
    	$update= array();

    	$update["coupon"] = $coupon;

    	
    	$update["modtime"] = date("Y-m-d H:i:s");
    	return $this->update($this->getTableName(), $update, "orderid=?", $orderid) ? true : false;
    }
    
    /**
     * 获取支付详情
     * @param string $orderid
     * @return array
     */
    public function getPayInfo($orderid)
    {
        $sql = "select * from {$this->getTableName()} where orderid=? limit 1";
        return $this->getRow($sql, $orderid);
    }
}