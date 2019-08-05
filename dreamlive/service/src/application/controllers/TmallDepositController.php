<?php

class TmallDepositController extends BaseController
{
    public function __construct()
    {
        Consume::start();
    }

    public function queryAction()
    {
        // 商家编号
        $coop_id = trim(strip_tags($this->gbkToUtf8($this->getParam('coopId', ''))));
        // tmall 订单号
        $tb_order_no = trim(strip_tags($this->gbkToUtf8($this->getParam('tbOrderNo', ''))));
        $version = trim(strip_tags($this->gbkToUtf8($this->getParam('version', ''))));

        $tmall_deposit = new TmallDeposit();
        $result = $tmall_deposit->query($coop_id, $tb_order_no, $version);

        $this->renderXml($result);
    }

    public function queryTestAction()
    {
        // 商家编号
        $coop_id = trim(strip_tags($this->gbkToUtf8($this->getParam('coopId', ''))));
        // tmall 订单号
        $tb_order_no = trim(strip_tags($this->gbkToUtf8($this->getParam('tbOrderNo', ''))));
        $version = trim(strip_tags($this->gbkToUtf8($this->getParam('version', ''))));

        $tmall_deposit = new TmallDeposit();
        $result = $tmall_deposit->query($coop_id, $tb_order_no, $version);

        $this->renderXml($result);
    }

    public function orderAction()
    {
        $coop_id = trim(strip_tags($this->gbkToUtf8($this->getParam('coopId', ''))));
        $tb_order_no = trim(strip_tags($this->gbkToUtf8($this->getParam('tbOrderNo', ''))));
        $card_id = trim(strip_tags($this->gbkToUtf8($this->getParam('cardId', '')))); // 在商品发布页中编辑TSC编码
        $card_num = trim(strip_tags($this->gbkToUtf8($this->getParam('cardNum', ''))));   // 充值卡数量
        $customer = (int)trim(strip_tags($this->gbkToUtf8($this->getParam('customer', ''))));  // 被充值帐号
        $sum = trim(strip_tags($this->gbkToUtf8($this->getParam('sum', ''))));    // 用户支付金额, 元

        $gameid = trim(strip_tags($this->gbkToUtf8($this->getParam('gameId', ''))));
        $section1 = trim(strip_tags($this->gbkToUtf8($this->getParam('section1', ''))));
        $section2 = trim(strip_tags($this->gbkToUtf8($this->getParam('section2', ''))));
        $tb_order_snap = trim(strip_tags($this->gbkToUtf8($this->getParam('tbOrderSnap', ''))));
        $notify_url = trim(strip_tags($this->gbkToUtf8($this->getParam('notifyUrl', ''))));
        $version = trim(strip_tags($this->gbkToUtf8($this->getParam('version', ''))));

        $tmall_deposit = new TmallDeposit();
        $result = $tmall_deposit->order($coop_id, $tb_order_no, $card_id, $card_num, $customer, $sum, $gameid, $section1, $section2, $tb_order_snap, $notify_url, $version);

        $this->renderXml($result);
    }

    public function orderTestAction()
    {
        $coop_id = trim(strip_tags($this->gbkToUtf8($this->getParam('coopId', ''))));
        $tb_order_no = trim(strip_tags($this->gbkToUtf8($this->getParam('tbOrderNo', ''))));
        $card_id = trim(strip_tags($this->gbkToUtf8($this->getParam('cardId', '')))); // 在商品发布页中编辑TSC编码
        $card_num = trim(strip_tags($this->gbkToUtf8($this->getParam('cardNum', ''))));   // 充值卡数量
        $customer = (int)trim(strip_tags($this->gbkToUtf8($this->getParam('customer', ''))));  // 被充值帐号
        $sum = trim(strip_tags($this->gbkToUtf8($this->getParam('sum', ''))));    // 用户支付金额, 元

        $gameid = trim(strip_tags($this->gbkToUtf8($this->getParam('gameId', ''))));
        $section1 = trim(strip_tags($this->gbkToUtf8($this->getParam('section1', ''))));
        $section2 = trim(strip_tags($this->gbkToUtf8($this->getParam('section2', ''))));
        $tb_order_snap = trim(strip_tags($this->gbkToUtf8($this->getParam('tbOrderSnap', ''))));
        $notify_url = trim(strip_tags($this->gbkToUtf8($this->getParam('notifyUrl', ''))));
        $version = trim(strip_tags($this->gbkToUtf8($this->getParam('version', ''))));

        $tmall_deposit = new TmallDeposit();
        $result = $tmall_deposit->orderTest($coop_id, $tb_order_no, $card_id, $card_num, $customer, $sum, $gameid, $section1, $section2, $tb_order_snap, $notify_url, $version);

        $this->renderXml($result);
    }

    public function cancelAction()
    {
        $coop_id = trim(strip_tags($this->gbkToUtf8($this->getParam('coopId', ''))));
        $tb_order_no = trim(strip_tags($this->gbkToUtf8($this->getParam('tbOrderNo', ''))));
        $version = trim(strip_tags($this->gbkToUtf8($this->getParam('version', ''))));

        $tmall_deposit = new TmallDeposit();
        $result = $tmall_deposit->cancel($coop_id, $tb_order_no, $version);

        $this->renderXml($result);
    }

    public function cancelTestAction()
    {
        $coop_id = trim(strip_tags($this->gbkToUtf8($this->getParam('coopId', ''))));
        $tb_order_no = trim(strip_tags($this->gbkToUtf8($this->getParam('tbOrderNo', ''))));
        $version = trim(strip_tags($this->gbkToUtf8($this->getParam('version', ''))));

        $tmall_deposit = new TmallDeposit();
        $result = $tmall_deposit->cancel($coop_id, $tb_order_no, $version);

        $this->renderXml($result);
    }

    private function renderXml($xml)
    {
        header("Content-type:text/xml;charset=GBK");
        header("Server: nginx/1.2.3");

        $content = $xml;

        Logger::notice($content, [], '0');

        print iconv('UTF-8', 'GBK', $content);;
        exit();
    }

    private function gbkToUtf8($str)
    {
        return iconv('GBK', 'UTF-8', $str);
    }
}
