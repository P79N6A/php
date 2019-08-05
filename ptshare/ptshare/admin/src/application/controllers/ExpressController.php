<?php
class ExpressController extends BaseController
{
    public $status = array(
        '0' => '待发货',
        '1' => '已下单',
        '2' => '已揽件',
        '3' => '在途',
        '4' => '派件',
        '5' => '疑难',
        '6' => '退回',
        '7' => '转单',
        '10' => '签收',
        '20' => '拒收',
        '-1' => '失败',
    );

    public function listAction()
    {
        $orderid   = trim($this->getParam('orderid', ''));
        $status      = trim($this->getParam('status', ''));
        $page        = $this->getParam("page") ? intval($this->getParam("page")): 1;
        $num         = $this->getParam("num") ? intval($this->getParam("num"))  : 20;
        $start       = ($page - 1) * $num;

        $express = new Express();
        list($list, $total) = $express->getList($start, $num, $orderid, $status);

        $param = [
            'orderid' => $orderid,
            'status'    => $status,
        ];
        $mutipage = $this->mutipage($total, $page, $num, http_build_query($param), "/express/list");

        $data = array();
        $data['data']     = $list;
        $data['total']    = $total;
        $data['mutipage'] = $mutipage;

        $this->assign("param", $param);
        $this->assign("data", $data);

        $this->display("include/header.html", "express/index.html", "include/footer.html");
    }

    public function detailAction()
    {
        $orderid = trim($this->getParam('orderid', ''));

        $express = new Express();
        $data= $express->getExpressDetail($orderid);

        if($data['content']){
            $data['content'] = json_decode($data['content'], true);
        }

        $this->assign("data", $data);

        $this->display("include/header.html", "express/detail.html", "include/footer.html");
    }

    public function deliverAction()
    {
        $orderid = trim($this->getParam('orderid', ''));
        Interceptor::ensureNotEmpty($orderid, ERROR_PARAM_IS_EMPTY, "orderid");

        try{
            ShareClient::adminSetDeliver($orderid);
            //日志
            $operate = new Operate();
            $operate_content = array(
                'orderid'    => $orderid,
            );
            $operate->add($this->adminid, 'express_deliver', 0, 0, $operate_content, '', '', 1);

            Util::jumpMsg("修改成功!", "");
        }catch (Exception $e){
            Util::jumpMsg("修改失败:{$e->getCode()}:{$e->getMessage()}", "", 3);
        }

    }
    public function receiveAction()
    {
        $orderid = trim($this->getParam('orderid', ''));
        Interceptor::ensureNotEmpty($orderid, ERROR_PARAM_IS_EMPTY, "orderid");

        try{
            ShareClient::adminSetReceive($orderid);
            //日志
            $operate = new Operate();
            $operate_content = array(
                'orderid'    => $orderid,
            );
            $operate->add($this->adminid, 'express_receive', 0, 0, $operate_content, '', '', 1);

            Util::jumpMsg("修改成功!", "");
        }catch (Exception $e){
            Util::jumpMsg("修改失败:{$e->getCode()}:{$e->getMessage()}", "", 3);
        }

    }

    public function sentdownAction()
    {
        $orderid = trim($this->getParam('orderid', ''));
        $number  = trim($this->getParam('number', ''));
        $company  = trim($this->getParam('company', ''));
        Interceptor::ensureNotEmpty($orderid, ERROR_PARAM_IS_EMPTY, "orderid");
        Interceptor::ensureNotEmpty($number, ERROR_PARAM_IS_EMPTY, "number");
        Interceptor::ensureNotEmpty($company, ERROR_PARAM_IS_EMPTY, "company");

        try{
            ShareClient::adminSetSentdown($orderid, $company, $number);
            //日志
            $operate = new Operate();
            $operate_content = array(
                'orderid'    => $orderid,
                'company'    => $company,
                'number'     => $number,
            );
            $operate->add($this->adminid, 'express_sentdown', 0, 0, $operate_content, '', '', 1);

            Util::jumpMsg("修改成功!", "");
        }catch (Exception $e){
            Util::jumpMsg("修改失败:{$e->getCode()}:{$e->getMessage()}", "", 3);
        }

    }
    public function queryAction()
    {
        $orderid = trim($this->getParam('orderid', ''));
        Interceptor::ensureNotEmpty($orderid, ERROR_PARAM_IS_EMPTY, "orderid");

        try{
            ShareClient::adminExpressQuery($orderid);
            //日志
            $operate = new Operate();
            $operate_content = array(
                'orderid'    => $orderid,
            );
            $operate->add($this->adminid, 'express_update', 0, 0, $operate_content, '', '', 1);

            Util::jumpMsg("修改成功!", "");
        }catch (Exception $e){
            Util::jumpMsg("修改失败:{$e->getCode()}:{$e->getMessage()}", "", 3);
        }

    }
}