<?php
class DAOOrders extends DAOProxy
{
    //订单类型
    const ORDER_TYPE_SELL          = 1; //分享订单
    const ORDER_TYPE_RENT          = 2; //租用订单
    const ORDER_TYPE_RELET         = 3; //续租订单
    const ORDER_TYPE_BUYING        = 4; //购买订单
    const ORDER_TYPE_SEIZING       = 5; //葡萄夺宝订单

    //订单状态
    const ORDER_STATUS_INITIAL     = 0; //订单发起
    const ORDER_STATUS_CONFIRM     = 1; //订单确认
    const ORDER_STATUS_CANCEL      = 2; //订单取消
    const ORDER_STATUS_REVOKE      = 3; //撤销订单

    //订单支付状态
    const ORDER_PAY_STATUS_INITIAL = 0; //未付款
    const ORDER_PAY_STATUS_DEFRAY  = 1; //支付待确认
    const ORDER_PAY_STATUS_PAYOFF  = 2; //确认支付

    //支付方式
    const ORDER_PAY_TYPE_GRAPE     = 0; //葡萄支付
    const ORDER_PAY_TYPE_COUPON    = 1; //代金券支付
    const ORDER_PAY_TYPE_CASH      = 2; //现金支付
    const ORDER_PAY_TYPE_MIXED     = 3; //现金 + 代金券

    //订单快递状态
    const ORDER_EXPRESS_STATUS_INITIAL = 0; //未发货
    const ORDER_EXPRESS_STATUS_DELIVER = 1; //已发货
    const ORDER_EXPRESS_STATUS_RECEIVE = 2; //已收货

	public function __construct()
	{
	    parent::__construct();

		$this->setDBConf("MYSQL_CONF_PAYMENT");
		$this->setTableName("orders");
	}

	public function addOrder($orderid, $uid, $type, $relateid, $grape, $deposit_price, $service_price, $express_price, $status, $pay_status, $pay_type, $pay_price, $pay_coupon, $contact, $vip=0)
    {
	    $info = array(
	        "orderid"           => $orderid,
	        "uid"               => $uid,
	        "type"              => $type,
	        "status"            => $status,
	        "grape"             => $grape,
            "deposit_price"     => $deposit_price,
            "service_price"     => $service_price,
            "relateid"          => $relateid,
	        "pay_status"        => $pay_status,
            "pay_type"          => $pay_type,
	        "pay_price"         => $pay_price,
	        "pay_coupon"        => $pay_coupon,
	        "express_price"     => $express_price,
	        "contact_name"      => $contact["contact_name"],
	        "contact_zipcode"   => $contact["contact_zipcode"],
	        "contact_province"  => $contact["contact_province"],
	        "contact_city"      => $contact["contact_city"],
	        "contact_county"    => $contact["contact_county"],
	        "contact_address"   => $contact["contact_address"],
	        "contact_national"  => $contact["contact_national"],
	        "contact_phone"     => $contact["contact_phone"],
	        "addtime"           => Util::dbNow(),
	        "modtime"           => Util::dbNow(),
	        "vip"               => $vip,
	    );

//	    print_r($info);die;

        $this->insert($this->getTableName(), $info);

        return true;
    }

    public function confirm($orderid, $grape, $remark)
    {
        $info = array(
            "status"    => self::ORDER_STATUS_CONFIRM,
            "modtime"   => Util::dbNow()
        );

        if($grape > 0) {
            $info["grape"] = $grape;
        }

        if($remark != "") {
            $info["remark"] = $remark;
        }

        return $this->update($this->getTableName(), $info, "orderid=? and status=?", array($orderid, self::ORDER_STATUS_INITIAL));
    }

    public function cancel($orderid)
    {
        $info = array(
            "status"        => self::ORDER_STATUS_CANCEL,
            "modtime"       => Util::dbNow()
        );

        return $this->update($this->getTableName(), $info, "orderid=? and (status =? OR status = ?)", array($orderid, self::ORDER_STATUS_INITIAL, self::ORDER_STATUS_CONFIRM));
    }

    public function revoke($orderid)
    {
        $info = array(
            "status"=>self::ORDER_STATUS_REVOKE,
            "modtime"=>date("Y-m-d H:i:s")
        );

        return $this->update($this->getTableName(), $info, "orderid=? and status=?", array($orderid, self::ORDER_STATUS_CONFIRM));
    }

    public function defray($orderid, $pay_no, $pay_price, $pay_coupon = 0)
    {
        $info = array(
            "pay_no"	=> $pay_no,
            "pay_price"	=> $pay_price,
        	"pay_coupon"=> $pay_coupon,
            "pay_status"=> self::ORDER_PAY_STATUS_DEFRAY,
            "modtime"	=> date("Y-m-d H:i:s")
        );

        return $this->update($this->getTableName(), $info, "orderid=? and (pay_status=? or pay_status=? )", array($orderid, self::ORDER_PAY_STATUS_INITIAL, self::ORDER_PAY_STATUS_DEFRAY));
    }

    public function payoff($orderid, $pay_no, $pay_type, $pay_coupon, $pay_price)
    {
        $info = array(
            "pay_status"=>self::ORDER_PAY_STATUS_PAYOFF,
        	"pay_no"	=> $pay_no,
        	"pay_coupon"=> $pay_coupon,
        	"pay_type"  => $pay_type,
        	"pay_price" => $pay_price,
            "pay_time"=>date("Y-m-d H:i:s"),
            "modtime"=>date("Y-m-d H:i:s")
        );

        return $this->update($this->getTableName(), $info, "orderid=? and pay_status=?", array($orderid, self::ORDER_PAY_STATUS_DEFRAY));
    }

    public function payConfirm($orderid)
    {
        $info = array(
            "pay_status"=>self::ORDER_PAY_STATUS_PAYOFF,
            "pay_time"=>date("Y-m-d H:i:s"),
            "modtime"=>date("Y-m-d H:i:s")
        );
        return $this->update($this->getTableName(), $info, " orderid=? ", $orderid);
    }

    public function deliver($orderid)
    {
        $info = array(
            "express_status"=>self::ORDER_EXPRESS_STATUS_DELIVER,
            "express_time"=>date("Y-m-d H:i:s"),
            "modtime"=>date("Y-m-d H:i:s")
        );

        return $this->update($this->getTableName(), $info, "orderid=? and express_status=?", array($orderid, self::ORDER_EXPRESS_STATUS_INITIAL));
    }

    public function receive($orderid)
    {
        $info = array(
            "express_status"=>self::ORDER_EXPRESS_STATUS_RECEIVE,
            "receive_time"=>date("Y-m-d H:i:s"),
            "modtime"=>date("Y-m-d H:i:s")
        );

        return $this->update($this->getTableName(), $info, "orderid=? and express_status=?", array($orderid, self::ORDER_EXPRESS_STATUS_DELIVER));
    }

    // 获取订单详情
    public function getOrderInfo($orderid)
    {
        $sql = "select * from {$this->getTableName()} where orderid=?";
        return $this->getRow($sql, $orderid);
    }

    // 获取租用订单
    public function getRentOrderInfoByRelateid($relateid){
        $sql = "select * from {$this->getTableName()} where relateid=? and type='".self::ORDER_TYPE_RENT."' ";
        return $this->getRow($sql, $relateid);
    }

    // 获取未支付的订单列表
    public function getOrderListByNonPayment($startime, $endtime){
        $sql = " SELECT * FROM " . $this->getTableName() . " WHERE  pay_status in (".self::ORDER_PAY_STATUS_INITIAL.",".self::ORDER_PAY_STATUS_DEFRAY.") and status=".self::ORDER_STATUS_INITIAL." and addtime >= '" . $startime . "' and addtime <  '" . $endtime . "' ";
        return $this->getAll($sql);
    }
    
    // 获取葡萄夺宝未支付的订单
    public function getSeizingOrderListByNonPayment($startime, $endtime){
        $sql = " SELECT * FROM " . $this->getTableName() . " WHERE  pay_status in (".self::ORDER_PAY_STATUS_INITIAL.",".self::ORDER_PAY_STATUS_DEFRAY.") and type=".self::ORDER_TYPE_SEIZING."  and status=".self::ORDER_STATUS_INITIAL." and addtime >= '" . $startime . "' and addtime <  '" . $endtime . "' ";
        return $this->getAll($sql);
    }

    // 修改收货地址
    public function updateOrderAddress($orderid, $contact){
        $info = array(
            "contact_name"     => $contact['contact_name'],
            "contact_zipcode"  => $contact['contact_zipcode'],
            "contact_province" => $contact['contact_province'],
            "contact_city"     => $contact['contact_city'],
            "contact_county"   => $contact['contact_county'],
            "contact_address"  => $contact['contact_address'],
            "contact_national" => $contact['contact_national'],
            "contact_phone"    => $contact['contact_phone'],
        );
        return $this->update($this->getTableName(), $contact, "orderid=?", array($orderid));
    }
}
?>