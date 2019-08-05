<?php
class SeizingController extends BaseController
{
    // 购买下单
    public function orderAction()
    {
        $userid = Context::get("userid");
        $packageid = $this->getParam("packageid") ? intval($this->getParam("packageid")) : "";

        Interceptor::ensureNotFalse(is_numeric($userid) && $userid > 0, ERROR_PARAM_IS_EMPTY, "userid");
        Interceptor::ensureNotFalse(is_numeric($packageid) && $packageid > 0, ERROR_PARAM_IS_EMPTY, "packageid");

        list($orderid, $number,$addtime) = Seizing::order($userid, $packageid);

        $this->render(array('orderid' => $orderid, 'number' => $number, 'addtime' => $addtime));
    }

    // 夺宝列表
    public function getListAction()
    {
        $userid    = Context::get("userid");
        $offset    = $this->getParam("offset")    ? intval($this->getParam("offset"))    : 0;
        $num       = $this->getParam("num")       ? intval($this->getParam("num"))       : 20;
        $packageid = $this->getParam("packageid") ? intval($this->getParam("packageid")) : "";

        //Interceptor::ensureNotFalse(is_numeric($userid) && $userid > 0, ERROR_PARAM_IS_EMPTY, "userid");
        Interceptor::ensureNotFalse(is_numeric($packageid) && $packageid > 0, ERROR_PARAM_IS_EMPTY, "packageid");

        list($list, $total, $offset, $more) = Seizing::getList($packageid, $num, $offset);

        $this->render(array('list' => $list, 'total' => $total, 'offset' => $offset, 'more' => $more));
    }

    // 用户夺宝记录
    public function getUserListAction()
    {
        $userid    = Context::get("userid");
        $offset    = $this->getParam("offset")    ? intval($this->getParam("offset"))    : 0;
        $num       = $this->getParam("num")       ? intval($this->getParam("num"))       : 20;
        $type      = $this->getParam("type")      ? $this->getParam("type")              : "N";

        Interceptor::ensureNotFalse(is_numeric($userid) && $userid > 0, ERROR_PARAM_IS_EMPTY, "userid");

        list($list, $total, $offset, $more) = Seizing::getUserList($userid, $type, $num, $offset);

        $this->render(array('list' => $list, 'total' => $total, 'offset' => $offset, 'more' => $more));
    }
}