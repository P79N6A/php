<?php
class BuyController extends BaseController
{
	public function listAction()
	{
		$uid		= trim($this->getParam('uid', ''));
		$orderid	= trim($this->getParam('orderid', ''));
		$status 	= trim($this->getParam('status', '-1'));
		$end_time 	= trim($this->getParam('end_time', ''));
		$page       = $this->getParam("page") ? intval($this->getParam("page")) : 1;
		$num        = $this->getParam("num") ? intval($this->getParam("num")) : 20;
		$start  = ($page - 1) * $num;

		$data = array();

		$buy = new Buy();
		list($data, $total) = $buy->getList($start, $num, $uid, $orderid, $status);
		$packageidList = array();
		foreach ($data as $val) {
			$packageidList[] = $val["packageid"];
		}
		$package = new Package();
		$packageList = $package->getCoverByPackageids($packageidList);
		$packageList = array_column($packageList, 'cover', 'packageid');
		foreach($data as &$val){
			$val['packageCover'] = $packageList[$val['packageid']];

			if (strpos($val['packageCover'], 'http') === false) {
				$val['packageCover'] = 'https://static.putaofenxiang.com' . $val['packageCover'];
			}
		}

		$mutipage = $this->mutipage($total, $page, $num, http_build_query(array()), "/buy/list");

		$this->assign("page", $mutipage);
		$this->assign("list", $data);
		$this->assign("total", $total);
		$this->assign("statusData", Buy::getPayStatusData());

		$this->display("include/header.html", "buy/index.html", "include/footer.html");
	}

	public function viewAction()
    {

        $buyid = $this->getParam('buyid');

        if (empty($buyid)){
            Util::jumpMsg("buyid不能为空!", "/buy/list");
        }
        $buy = new Buy();
        $buyDetail = $buy->getInfo($buyid);
        if (empty($buyDetail)) {
            Util::jumpMsg("订单信息不存在!", "/buy/list");
        }

        $this->assign('buyDetail', $buyDetail);
        $this->assign("statusData", Buy::getPayStatusData());
        $user = new User();
        $userDetail = $user->getOneById($buyDetail['uid']);
        $this->assign('userDetail', $userDetail);

        $this->display("include/header.html", "buy/detail.html", "include/footer.html");

    }

    public function cancelAction()
    {
        $orderid = trim($this->getParam('orderid', ''));
        Interceptor::ensureNotEmpty($orderid, ERROR_PARAM_IS_EMPTY, "orderid");

        try{
            ShareClient::adminBuyRevoke($orderid);
            //日志
            $operate = new Operate();
            $operate_content = array(
                'orderid'    => $orderid,
            );
            $operate->add($this->adminid, 'buy_cancel', 0, 0, $operate_content, '', '', 1);

            Util::jumpMsg("修改成功!", "/buy/list");
        }catch (Exception $e){
            Util::jumpMsg("修改失败:{$e->getCode()}:{$e->getMessage()}", "/buy/list", 3);
        }
    }
}