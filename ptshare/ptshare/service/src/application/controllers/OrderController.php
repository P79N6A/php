<?php
class OrderController extends BaseController
{
    // 取消订单
    public function cancelAction()
    {
        $userid   = Context::get("userid");
        $orderid  = $this->getParam("orderid") ? intval($this->getParam("orderid")) : "";

        Interceptor::ensureNotFalse(is_numeric($userid) && $userid > 0, ERROR_PARAM_IS_EMPTY, "userid");
        Interceptor::ensureNotFalse(! empty($orderid), ERROR_PARAM_IS_EMPTY, "orderid");

        $orderInfo = Order::getOrderInfo($orderid);
        Interceptor::ensureNotEmpty($orderInfo, ERROR_BIZ_ORDER_NOT_EXIST,$orderid);
        Interceptor::ensureNotFalse(in_array($orderInfo["status"],array(DAOOrders::ORDER_STATUS_INITIAL, DAOOrders::ORDER_STATUS_CONFIRM)), ERROR_BIZ_ORDER_STATUS_UNVALID, $orderid);
        // 夺宝订单
        if ($orderInfo['type'] == DAOOrders::ORDER_TYPE_SEIZING) {
            Interceptor::ensureNotFalse(false, ERROR_BIZ_ZEIZING_CANCEL, $orderid);
        }

        // 微信消息
        $packageInfo = Package::getPackageInfoByOrderid($orderid);
        $option = array(
            'orderid' => $orderid,
            'title'   => $packageInfo['description'],
            'payment' => intval($orderInfo['grape']) . "葡萄"
        );
        $args  = array($orderInfo['grape'], ceil($orderInfo['grape'] * 0.2));


        try {
            // 租赁
            if ($orderInfo['type'] == DAOOrders::ORDER_TYPE_RENT) {

            }
            // 续租
            if ($orderInfo['type'] == DAOOrders::ORDER_TYPE_RELET) {

            }
            // 购买
            if ($orderInfo['type'] == DAOOrders::ORDER_TYPE_BUYING) {
                $result = Buying::cancel($orderid);

                // 微信消息
                $buyingInfo  = Buying::getBuyingInfoByOrderid($orderid);
                $param       = array("buyid" => $buyingInfo['buyid']);
                WxMessage::cancelBuySuccess($userid, $option, $param, $args);
            }
        } catch (Exception $e) {
            Logger::log("orderController", "cancelAction", array("uid" => $userid,"orderid" => $orderid,"code" => $e->getCode(),"msg" => $e->getMessage(),"line" => __LINE__));
            throw new BizException($e->getMessage());
        }

        $this->render();
    }

    // 确认收货
    public function receiveAction()
    {
        $userid   = Context::get("userid");
        $orderid  = $this->getParam("orderid") ? intval($this->getParam("orderid")) : "";

        Interceptor::ensureNotFalse(is_numeric($userid) && $userid > 0, ERROR_PARAM_IS_EMPTY, "userid");
        Interceptor::ensureNotFalse(! empty($orderid), ERROR_PARAM_IS_EMPTY, "orderid");

        $orderInfo = Order::getOrderInfo($orderid);
        Interceptor::ensureNotEmpty($orderInfo, ERROR_BIZ_ORDER_NOT_EXIST,$orderid);
        Interceptor::ensureNotFalse(in_array($orderInfo["status"],array(DAOOrders::ORDER_STATUS_INITIAL, DAOOrders::ORDER_STATUS_CONFIRM)), ERROR_BIZ_ORDER_STATUS_UNVALID, $orderid);

        // 微信消息
        $packageInfo = Package::getPackageInfoByOrderid($orderid);
        $option = array(
            'orderid' => $orderid,
            'title'   => $packageInfo['description'],
            'payment' => intval($orderInfo['grape']) . "葡萄"
        );
        $args  = array($orderInfo['grape'], ceil($orderInfo['grape'] * 0.2));
        try {
            // 租赁
            if ($orderInfo['type'] == DAOOrders::ORDER_TYPE_RENT) {

            }
            // 续租
            if ($orderInfo['type'] == DAOOrders::ORDER_TYPE_RELET) {

            }
            // 购买
            if ($orderInfo['type'] == DAOOrders::ORDER_TYPE_BUYING) {
                $result = Buying::receiveConfirm($orderid);

                // 微信消息
                $buyingInfo  = Buying::getBuyingInfoByOrderid($orderid);
                $param       = array("buyid" => $buyingInfo['buyid']);
                WxMessage::receiveBuySuccess($userid, $option, $param, $args);
            }
        } catch (Exception $e) {
            Logger::log("orderController", "receiveAction", array("uid" => $userid,"orderid" => $orderid,"code" => $e->getCode(),"msg" => $e->getMessage(),"line" => __LINE__));
            throw new BizException($e->getMessage());
        }

        $this->render();
    }

    // 快递信息
    public function expressAction(){
        $orderid  = $this->getParam("orderid") ? intval($this->getParam("orderid")) : "";
        Interceptor::ensureNotFalse(! empty($orderid), ERROR_PARAM_IS_EMPTY, "orderid");

        $orderInfo = Order::getOrderInfo($orderid);
        Interceptor::ensureNotEmpty($orderInfo, ERROR_BIZ_ORDER_NOT_EXIST,$orderid);

        $packageInfo = Package::getPackageInfoByPackageid($orderInfo['relateid']);
        $expressList = Express::getExpressList($orderid);
        $expressList = array_values($expressList);
        $express = isset($expressList[0]) ? $expressList[0] : array();

        $this->render(array('packageInfo'=>$packageInfo,'express' => $express));
    }

    // 撤销订单
    public function revokeAction(){
        $orderid  = $this->getParam("orderid") ? intval($this->getParam("orderid")) : "";
        Interceptor::ensureNotFalse(! empty($orderid), ERROR_PARAM_IS_EMPTY, "orderid");

        $orderInfo = Order::getOrderInfo($orderid);
        Interceptor::ensureNotEmpty($orderInfo, ERROR_BIZ_ORDER_NOT_EXIST,$orderid);

        try {
            // 租赁
            if ($orderInfo['type'] == DAOOrders::ORDER_TYPE_RENT) {

            }
            // 续租
            if ($orderInfo['type'] == DAOOrders::ORDER_TYPE_RELET) {

            }
            // 购买
            if ($orderInfo['type'] == DAOOrders::ORDER_TYPE_BUYING) {
                $result = Buying::revoker($orderid);
            }
        } catch (Exception $e) {
            Logger::log("orderController", "revokeAction", array("uid" => $userid,"orderid" => $orderid,"code" => $e->getCode(),"msg" => $e->getMessage(),"line" => __LINE__));
            throw new BizException($e->getMessage());
        }

        $this->render();
    }

    // 修改地址
    public function addressAction(){
        $orderid  = $this->getParam("orderid") ? trim($this->getParam("orderid")) : "";
        $contact  = $this->getParam("contact") ? trim($this->getParam("contact")) : "";

        Interceptor::ensureNotFalse(! empty($orderid), ERROR_PARAM_IS_EMPTY, "orderid");
        Interceptor::ensureNotFalse(! empty($contact), ERROR_PARAM_IS_EMPTY, "contact");

        $contact = json_decode($contact, true);
        $contact = array(
            "contact_name"     => $contact['contact_name'],
            "contact_zipcode"  => $contact['contact_zipcode'],
            "contact_province" => $contact['contact_province'],
            "contact_city"     => $contact['contact_city'],
            "contact_county"   => $contact['contact_county'],
            "contact_address"  => $contact['contact_address'],
            "contact_national" => $contact['contact_national'],
            "contact_phone"    => $contact['contact_phone'],
        );

        $result = Order::updateOrderAddress($orderid, $contact);

        $this->render();
    }
}