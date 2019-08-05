<?php
class RentController extends BaseController
{
    // 租借订单
    public function orderAction()
    {
        $userid    = Context::get("userid");
        $packageid = $this->getParam("packageid") ? intval($this->getParam("packageid")) : 0;
        $month     = $this->getParam("month")     ? intval($this->getParam("month"))     : 0;

        Interceptor::ensureNotFalse(is_numeric($userid) && $userid > 0, ERROR_PARAM_IS_EMPTY, "userid");
        Interceptor::ensureNotFalse(is_numeric($packageid) && $packageid > 0, ERROR_PARAM_IS_EMPTY, "packageid");
        Interceptor::ensureNotFalse((is_numeric($month) && 7 > $month && $month > 0), ERROR_BIZ_RANTING_MONTH_NOT_RANGE, "month");

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
        list($orderid, $relateid, $rentid, $ispay, $payment) = Rent::order($userid, $packageid, $month, $contact);

        $this->render(array('orderid' => (string)$orderid, 'relateid'=>$relateid, 'rentid'=>$rentid,'ispay' => $ispay, 'payment'=>$payment, 'addtime' => date("Y-m-d H:i:s")));
    }
}