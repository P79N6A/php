<?php
class OrdersController extends BaseController
{
    public function listAction()
    {

        $status		= trim($this->getParam('status', '-1'));
        $pay_status = trim($this->getParam('pay_status', '2'));
        $express_status= trim($this->getParam('express_status', '-1'));
        $type   	= trim($this->getParam('type', ''));
        $orderid       = trim($this->getParam('orderid', ''));
        $page       = $this->getParam("page") ? intval($this->getParam("page")) : 1;
        $num        = $this->getParam("num") ? intval($this->getParam("num")) : 20;
        $create_start_time        = $this->getParam("create_start_time", 0);
        $create_end_time        = $this->getParam("create_end_time", 0);

        $pay_start_time        = $this->getParam("pay_start_time", 0);
        $pay_end_time        = $this->getParam("pay_end_time", 0);

        $start  = ($page - 1) * $num;

        $data = array();

        $orders = new Orders();
        list($data, $total) = $orders->getList($start, $num, $status, $type, $pay_status, $express_status, $orderid, $create_start_time, $create_end_time, $pay_start_time, $pay_end_time);


        $mutipage = $this->mutipage($total, $page, $num, http_build_query(array("pay_status" => $pay_status, "status" => $status, "type" => $type, "express_status" => $express_status, "create_start_time" => $create_start_time, "create_end_time" => $create_end_time)), "/orders/list");

        $this->assign("page", $mutipage);
        $this->assign("list", $data);
        $this->assign("total", $total);
        $this->assign("statusData", Orders::statusData());
        $this->assign("paytypeData", Orders::payMethodData());
        $this->assign("payStatusData", Orders::payStatusData());
        $this->assign("expressStatusData", Orders::expressStatusData());
        $this->display("include/header.html", "orders/index.html", "include/footer.html");
    }

    public function detailAction()
    {

        $orderid = $this->getParam('orderid');

        if (empty($orderid)){
            Util::jumpMsg("orderid不能为空!", "/orders/list");
        }
        $orders = new Orders();
        $orderDetail = $orders->getInfo($orderid);
        if (empty($orderDetail)) {
            Util::jumpMsg("订单信息不存在!", "/orders/list");
        }

        $this->assign('orderDetail', $orderDetail);
        $this->assign("statusData", Orders::statusData());
        $this->assign("payStatusData", Orders::payStatusData());
        $this->assign("expressStatusData", Orders::expressStatusData());
        $this->assign("payMethodData", Orders::payMethodData());
        $user = new User();
        $userDetail = $user->getOneById($orderDetail['uid']);
        $this->assign('userDetail', $userDetail);

        $this->display("include/header.html", "orders/detail.html", "include/footer.html");

    }

    public function updateAction()
    {

        $params = [
            'id'                         => $this->getParam("id"),
            'contact'                    => [
                'name'      => $this->getParam("address_name"),
                'phone'     => $this->getParam("address_phone"),
                'address'   => $this->getParam("address_address"),
            ],
            'description'                => $this->getParam("description"),
            'show_grape'                 => $this->getParam("show_grape"),
            'good_draft_status'          => $this->getParam("good_draft_status"),
            'worth_grape'                => $this->getParam("worth_grape"),
            'status'                     => $this->getParam("status"),
            'detailids'                  => $this->getParam("detailids"),
        ];

        $this->modelSell->updateSellRecord($params);

        Util::jumpMsg("修改成功!", "/sell/detail?id=".$params['id']);

    }


}