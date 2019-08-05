<?php
class PayController extends BaseController
{
	public function listAction()
	{
		$uid		= trim($this->getParam('uid', ''));
		$orderid	= trim($this->getParam('orderid', ''));
		$status 	= trim($this->getParam('status', '-1'));
		$type 		= trim($this->getParam('type', '-1'));
		$page       = $this->getParam("page") ? intval($this->getParam("page")) : 1;
		$num        = $this->getParam("num") ? intval($this->getParam("num")) : 20;
		$start  = ($page - 1) * $num;

		$data = array();

		$pay = new Pay();
		list($data, $total) = $pay->getList($start, $num, $uid, $orderid, $status, $type);


		$mutipage = $this->mutipage($total, $page, $num, http_build_query(array("type" => $type, "status" => $status)), "/pay/list");

		$this->assign("page", $mutipage);
		$this->assign("list", $data);
		$this->assign("total", $total);
		$this->assign("statusData", Pay::getPayStatusData());
		$this->assign("typeData", Pay::getPayTypeData());
		$this->assign("paymentData", Pay::getPayMethodData());

		$this->display("include/header.html", "pay/index.html", "include/footer.html");
	}
}