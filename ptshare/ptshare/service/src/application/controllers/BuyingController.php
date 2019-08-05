<?php
class BuyingController extends BaseController
{
    // 购买列表
    public function getListAction()
    {
        $userid = Context::get("userid");
        $offset = $this->getParam("offset") ? intval($this->getParam("offset")) : 0;
        $num    = $this->getParam("num")    ? intval($this->getParam("num"))    : 20;
        $type   = $this->getParam("type");

        Interceptor::ensureNotFalse(is_numeric($userid) && $userid > 0, ERROR_PARAM_IS_EMPTY, "userid");
        Interceptor::ensureNotFalse(is_numeric($type) && $type > 0, ERROR_PARAM_IS_EMPTY, "type");

        list($list,$total,$offset,$more) = Buying::getList($userid, $type, $num, $offset);

        $this->render(array('list' => $list, 'total' => $total, 'offset'=>$offset, 'more'=>$more, 'type'=>$type, 'userid'=>$userid));
    }

    // 购买详情
    public function detailsAction()
    {
        $userid = Context::get("userid");
        $buyid  = $this->getParam("buyid") ? intval($this->getParam("buyid")) : 0;

        Interceptor::ensureNotFalse(is_numeric($userid) && $userid > 0, ERROR_PARAM_IS_EMPTY, "userid");
        Interceptor::ensureNotFalse(is_numeric($buyid) && $buyid > 0, ERROR_PARAM_IS_EMPTY, "relateid");

        $userid   = Context::get("userid");
        $userInfo = array();
        if($userid){
            $userInfo = User::getUserInfo($userid);
        }
        list($buyingInfo, $orderInfo, $packageInfo, $pay) = Buying::getDetails($buyid);

        $this->render(array('buyingInfo' => $buyingInfo, 'userInfo'=>(object)$userInfo, 'orderInfo' => $orderInfo, 'packageInfo'=>$packageInfo, 'pay'=>$pay));
    }

    // 购买下单
    public function orderAction()
    {
        $userid    = Context::get("userid");
        $packageid = $this->getParam("packageid") ? intval($this->getParam("packageid")) : 0;

        Interceptor::ensureNotFalse(is_numeric($userid) && $userid > 0, ERROR_PARAM_IS_EMPTY, "userid");
        Interceptor::ensureNotFalse(is_numeric($packageid) && $packageid > 0, ERROR_PARAM_IS_EMPTY, "packageid");

        $contact = array(
            "contact_name"     => trim(strip_tags($this->getParam('contact_name'))),
            "contact_zipcode"  => trim(strip_tags($this->getParam('contact_zipcode'))),
            "contact_province" => trim(strip_tags($this->getParam('contact_province'))),
            "contact_city"     => trim(strip_tags($this->getParam('contact_city'))),
            "contact_county"   => trim(strip_tags($this->getParam('contact_county'))),
            "contact_address"  => trim(strip_tags($this->getParam('contact_address'))),
            "contact_national" => trim(strip_tags($this->getParam('contact_national'))),
            "contact_phone"    => trim(strip_tags($this->getParam('contact_phone')))
        );
        list($orderid, $buyid, $ispay, $payment) = Buying::order($userid, $packageid, $contact);

        $this->render(array('orderid' => (string)$orderid, 'buyid'=>$buyid, 'ispay' => $ispay, 'payment'=>$payment, 'addtime' => date("Y-m-d H:i:s")));
    }
    // 购买撤销
    public function adminRevokeAction()
    {
    	$orderid  = $this->getParam("orderid") ? intval($this->getParam("orderid")) : 0;

    	Interceptor::ensureNotFalse(is_numeric($orderid) && $orderid > 0, ERROR_PARAM_IS_EMPTY, "packageid");

    	try {
    		Buying::revoker($orderid);
    	} catch (Exception $e) {
    		Logger::log("buying", "revoker", array("code" => $e->getCode(),"msg" => $e->getMessage()));
    		throw new BizException($e->getMessage());
    	}
    	$this->render();
    }
}
