<?php
class Order
{
    //获取订单号
    public static function getOrderId()
    {
        return SnowFlake::nextId();
    }

    //创建分享订单 $receiver是json,按照微信小程序原义构造即可
    public static function addSellOrder($userid, $orderid, $sellid, $grape, $contact)
    {
        $type           = DAOOrders::ORDER_TYPE_SELL;
        $pay_type       = DAOOrders::ORDER_PAY_TYPE_GRAPE;
        $deposit_price  = 0;
        $service_price  = 0;
        $express_price  = 0;
        $pay_price      = 0;
        $pay_coupon     = 0;
        $status         = DAOOrders::ORDER_STATUS_INITIAL;
        $pay_status     = DAOOrders::ORDER_PAY_STATUS_INITIAL;

        $dao_order = new DAOOrders();
        return $dao_order->addOrder($orderid, $userid, $type, $sellid, $grape, $deposit_price, $service_price, $express_price, $status, $pay_status, $pay_type, $pay_price, $pay_coupon, $contact);
    }

    // 订单详情
    public static function getOrderInfo($orderid){
        $DAOOrders = new DAOOrders();
        return $DAOOrders->getOrderInfo($orderid);
    }

    // 葡萄夺宝订单
    public static function addSeizingOrder($orderid, $uid, $relateid, $service_price, $express_price, $status, $pay_status, $pay_type, $pay_price, $pay_coupon, $contact)
    {
        $DAOOrders = new DAOOrders();
        return $DAOOrders->addOrder($orderid, $uid, DAOOrders::ORDER_TYPE_SEIZING, $relateid, 0, 0, $service_price, $express_price, $status, $pay_status, $pay_type, $pay_price, $pay_coupon, $contact);
    }

    // 创建购买订单
    public static function addBuyingOrder($orderid, $relateid, $grape, $deposit_price, $service_price, $express_price, $status, $pay_status, $pay_type, $pay_price, $pay_coupon, $contact, $vip){
        $dao_order = new DAOOrders();
        return $dao_order->addOrder($orderid, Context::get("userid"), DAOOrders::ORDER_TYPE_BUYING,$relateid, $grape, $deposit_price, $service_price, $express_price, $status, $pay_status, $pay_type, $pay_price, $pay_coupon, $contact,$vip);
    }

    // 创建租借订单
    public static function addRentOrder($orderid, $relateid, $grape, $deposit_price, $service_price, $express_price, $status, $pay_status, $pay_type, $pay_price, $pay_coupon, $contact, $vip)
    {
        $dao_order = new DAOOrders();
        return $dao_order->addOrder($orderid, Context::get("userid"), DAOOrders::ORDER_TYPE_RENT,$relateid, $grape, $deposit_price, $service_price, $express_price, $status, $pay_status, $pay_type, $pay_price, $pay_coupon, $contact, $vip);
    }

    // 创建续租订单
    public static function addReletOrder($orderid, $relateid, $grape, $deposit_price, $service_price, $express_price, $status, $pay_status, $pay_type, $pay_price, $pay_coupon, $contact)
    {
        $dao_order = new DAOOrders();
        return $dao_order->addOrder($orderid, Context::get("userid"), DAOOrders::ORDER_TYPE_RELET,$relateid, $grape, $deposit_price, $service_price, $express_price, $status, $pay_status, $pay_type, $pay_price, $pay_coupon, $contact);
    }

    // 修改收货地址
    public static function updateOrderAddress($orderid, $contact){
        $DAOOrders = new DAOOrders();
        $orderInfo = $DAOOrders->getOrderInfo($orderid);

        Interceptor::ensureNotFalse($orderInfo['uid'] == trim(Context::get("userid")), ERROR_BIZ_ORDER_NOT_EXIST, 'update');
        $DAOOrders->updateOrderAddress($orderid, $contact);

        $DAOOrdersLog = new DAOOrdersLog();
        $DAOOrdersLog->addOrderLog($orderid, json_encode($orderInfo), "修改订单收货地址", Context::get("userid"));

        return true;
    }

    //确认订单
    public static function confirm($orderid, $grape = 0, $remark = "")
    {
        $dao_order = new DAOOrders();

        $orderinfo = $dao_order->getOrderInfo($orderid);

        Logger::log("pay_notify_log", "orderconfirm", array("data" => json_encode($orderinfo)));
        Interceptor::ensureNotFalse($orderinfo["status"] == DAOOrders::ORDER_STATUS_INITIAL, ERROR_BIZ_ORDER_STATUS_UNVALID, $orderid);

        Interceptor::ensureNotFalse($dao_order->confirm($orderid, $grape, $remark) > 0, ERROR_BIZ_ORDER_UPDATE_FAIL, 'update');

        $dao_order_log = new DAOOrdersLog();
        $dao_order_log->addOrderLog($orderid, json_encode($orderinfo), "确认订单", Context::get("userid"));

        return true;
    }

    //撤销订单
    public static function revoke($orderid)
    {
        $dao_order = new DAOOrders();
        $orderinfo = $dao_order->getOrderInfo($orderid);

        Interceptor::ensureNotFalse($orderinfo["status"] == DAOOrders::ORDER_STATUS_CONFIRM, ERROR_BIZ_ORDER_STATUS_UNVALID, $orderid);

        Interceptor::ensureNotFalse($dao_order->revoke($orderid) > 0, ERROR_BIZ_ORDER_UPDATE_FAIL, 'update');

        $dao_order_log = new DAOOrdersLog();
        $dao_order_log->addOrderLog($orderid, json_encode($orderinfo), "撤销订单", Context::get("userid"));

        return true;
    }

    //取消订单
    public static function cancel($orderid)
    {
        $dao_order = new DAOOrders();

        $orderinfo = $dao_order->getOrderInfo($orderid);

        Interceptor::ensureNotFalse(in_array($orderinfo["status"],[DAOOrders::ORDER_STATUS_INITIAL,DAOOrders::ORDER_STATUS_CONFIRM]), ERROR_BIZ_ORDER_STATUS_UNVALID, $orderid);

        Interceptor::ensureNotFalse($dao_order->cancel($orderid) > 0,  ERROR_BIZ_ORDER_UPDATE_FAIL, 'update');

        $dao_order_log = new DAOOrdersLog();
        $dao_order_log->addOrderLog($orderid, json_encode($orderinfo), "取消订单", Context::get("userid"));

        return true;
    }

    //用户支付确认
    public static function defray($orderid, $pay_no, $pay_price, $pay_coupon)
    {
        $dao_order = new DAOOrders();

        $orderinfo = $dao_order->getOrderInfo($orderid);

        Interceptor::ensureNotFalse(in_array($orderinfo["pay_status"],[DAOOrders::ORDER_PAY_STATUS_INITIAL, DAOOrders::ORDER_PAY_STATUS_DEFRAY]), ERROR_BIZ_ORDER_PAY_STATUS_UNVALID, $orderid);

        Interceptor::ensureNotFalse($dao_order->defray($orderid, $pay_no, $pay_price, $pay_coupon) > 0, ERROR_BIZ_ORDER_UPDATE_FAIL, 'update');

        $dao_order_log = new DAOOrdersLog();
        $dao_order_log->addOrderLog($orderid, json_encode($orderinfo), "支付确认", Context::get("userid"));

        return true;
    }

    //微信回调支付确认
    public static function payoff($orderid, $pay_no, $pay_type, $pay_coupon, $pay_price)
    {
        $dao_order = new DAOOrders();

        $orderinfo = $dao_order->getOrderInfo($orderid);
        Logger::log("pay_notify_log", "orderpayoff", array("data" => json_encode($orderinfo)));
        Interceptor::ensureNotFalse(in_array($orderinfo["pay_status"],[DAOOrders::ORDER_PAY_STATUS_INITIAL, DAOOrders::ORDER_PAY_STATUS_DEFRAY]), ERROR_BIZ_ORDER_PAY_STATUS_UNVALID, $orderid);

        Interceptor::ensureNotFalse($dao_order->payoff($orderid, $pay_no, $pay_type, $pay_coupon, $pay_price) > 0, ERROR_BIZ_ORDER_UPDATE_FAIL, 'update');

        $dao_order_log = new DAOOrdersLog();
        $dao_order_log->addOrderLog($orderid, json_encode($orderinfo), "确认支付", Context::get("userid"));

        return true;
    }

    //支付确认
    public static function payConfirm($orderid)
    {
        $dao_order = new DAOOrders();

        $orderinfo = $dao_order->getOrderInfo($orderid);
        Logger::log("pay_notify_log", "orderpayoff", array("data" => json_encode($orderinfo)));

        Interceptor::ensureNotFalse($dao_order->payConfirm($orderid) > 0, ERROR_BIZ_ORDER_UPDATE_FAIL, 'update');

        $dao_order_log = new DAOOrdersLog();
        $dao_order_log->addOrderLog($orderid, json_encode($orderinfo), "确认支付", Context::get("userid"));

        return true;
    }

    //快递签发
    public static function deliver($orderid)
    {
        $dao_order = new DAOOrders();

        $orderinfo = $dao_order->getOrderInfo($orderid);

        Interceptor::ensureNotFalse($orderinfo["express_status"] == DAOOrders::ORDER_EXPRESS_STATUS_INITIAL, ERROR_BIZ_ORDER_EXPRESS_STATUS_UNVALID, $orderid);

        $result = $dao_order->deliver($orderid);

        Interceptor::ensureNotFalse($result > 0, ERROR_BIZ_ORDER_UPDATE_FAIL, 'update');

        $dao_order_log = new DAOOrdersLog();
        $dao_order_log->addOrderLog($orderid, json_encode($orderinfo), "快递签发", Context::get("userid"));

        return true;
    }

    //快递签收
    public static function receive($orderid)
    {
        $dao_order = new DAOOrders();

        $orderinfo = $dao_order->getOrderInfo($orderid);
        //Interceptor::ensureNotFalse($orderinfo["express_status"] == DAOOrders::ORDER_EXPRESS_STATUS_DELIVER, ERROR_BIZ_ORDER_EXPRESS_STATUS_UNVALID, $orderid);

        Interceptor::ensureNotFalse($dao_order->receive($orderid) > 0, ERROR_BIZ_ORDER_UPDATE_FAIL, 'update');

        $dao_order_log = new DAOOrdersLog();
        $dao_order_log->addOrderLog($orderid, json_encode($orderinfo), "快递签收", Context::get("userid"));

        return true;
    }

    // 已发货
    public static function updateExpressStatusDeliverByOrderId($orderid)
    {
        $orderInfo = self::getOrderInfo($orderid);
        Interceptor::ensureNotEmpty($orderInfo, $orderid, 'empty orderid');

        $modelPackage = new Package();
        $modelPackage->doExpressSendOutByPackageId($orderInfo['relateid']);

        if ($orderInfo['type'] == DAOOrders::ORDER_TYPE_RENT) {
            Rent::expressDeliver($orderid);
        }
        if ($orderInfo['type'] == DAOOrders::ORDER_TYPE_BUYING) {
            Buying::expressDeliver($orderid);
        }
    }

    // 已收货
    public static function updateExpressStatusReceiveByOrderId($orderid)
    {
        $orderInfo = self::getOrderInfo($orderid);
        Interceptor::ensureNotEmpty($orderInfo, $orderid, 'empty orderid');

        $modelPackage = new Package();
        $updateResult = $modelPackage->doExpressReceiveByPackageId($orderInfo['relateid']);

        if ($orderInfo['type'] == DAOOrders::ORDER_TYPE_RENT) {
            Rent::expressReceive($orderid);
        }
        if ($orderInfo['type'] == DAOOrders::ORDER_TYPE_BUYING) {
            Buying::expressReceive($orderid);
        }
    }
}
?>