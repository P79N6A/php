<?php

require "TmallPay.php";

class TmallDeposit
{
    const CoopId = '3533994298';
    const AppKey = '24718853';
    const AppSecret = 'ef6290d65b11904a2ad6a8b1203e4ad6';

    public function query($coop_id, $tb_order_no, $version)
    {
        $source = 'tmall';
        $this->addDepositRequestLog('query', $tb_order_no);

        $validate_sign = $this->validateSign();
        if (!$validate_sign) {
            $failed_code = '0102';
            $failed_reason = 'validate sign fail';

            return $this->queryFailSignXml($tb_order_no, $failed_code, $failed_reason);
        }

        $dao_deposit = new DAODeposit();
        $deposit_info = $dao_deposit->getDepositInfoBySourceTradeid($source, $tb_order_no);

        if (!$deposit_info) {
            // 未充值
            $failed_code = '0104';
            $failed_reason = 'do not have the order';
            $result = $this->queryNoorderXml($tb_order_no, $failed_code, $failed_reason);

        } else {
            $status = $deposit_info['status'];
            $coop_order_no = $deposit_info['orderid'];

            if ($status == 'Y') {
                // 充值成功
                $coop_order_success_time = date('YmdHis', strtotime($deposit_info['modtime']));
                $extends = json_decode($deposit_info['extends'], true);
                $coop_order_snap = implode('|', $extends['snap']);
                $result = $this->querySuccessXml($tb_order_no, $coop_order_no, $coop_order_success_time, $coop_order_snap);

            } elseif ($status == 'N') {
                $failed_code = '0502';
                $failed_reason = 'customer error';
                $result = $this->queryFailXml($tb_order_no, $coop_order_no, $failed_code, $failed_reason);

            } elseif ($status == 'P') {
                // 充值中
                return $this->queryUnderwayXml($tb_order_no);
            }
        }

        return $result;
    }

    public function queryFailSignXml($tb_order_no, $failed_code, $failed_reason)
    {
        $result = "<gamezctopquery>
<tbOrderNo>{$tb_order_no}</tbOrderNo>
<coopOrderSuccessTime></coopOrderSuccessTime>
<coopOrderStatus>GENERAL_ERROR</coopOrderStatus>
<failedReason>{$failed_reason}</failedReason>
<coopOrderNo></coopOrderNo>
<failedCode>{$failed_code}</failedCode>
<coopOrderSnap></coopOrderSnap>
</gamezctopquery>
";
        $this->addDepositResponseLog('query_failsign_resp', $tb_order_no, $result);

        return $result;
    }

    public function queryNoorderXml($tb_order_no, $failed_code, $failed_reason)
    {
        $result = "<gamezctopquery>
<tbOrderNo>{$tb_order_no}</tbOrderNo>
<coopOrderSuccessTime></coopOrderSuccessTime>
<coopOrderStatus>REQUEST_FAILED</coopOrderStatus>
<failedReason>{$failed_reason}</failedReason>
<coopOrderNo></coopOrderNo>
<failedCode>{$failed_code}</failedCode>
<coopOrderSnap></coopOrderSnap>
</gamezctopquery>
";
        $this->addDepositResponseLog('query_noorder_resp', $tb_order_no, $result);

        return $result;
    }

    public function querySuccessXml($tb_order_no, $coop_order_no, $coop_order_success_time, $coop_order_snap)
    {
        $result = "<gamezctopquery>
<tbOrderNo>{$tb_order_no}</tbOrderNo>
<coopOrderSuccessTime>{$coop_order_success_time}</coopOrderSuccessTime>
<coopOrderStatus>SUCCESS</coopOrderStatus>
<failedReason></failedReason>
<coopOrderNo>{$coop_order_no}</coopOrderNo>
<failedCode></failedCode>
<coopOrderSnap>{$coop_order_snap}</coopOrderSnap>
</gamezctopquery>
";

        $this->addDepositResponseLog('query_success_resp', $tb_order_no, $result);

        return $result;
    }

    private function queryFailXml($tb_order_no, $coop_order_no, $failed_code, $failed_reason)
    {
        $result = "<gamezctopquery>
<tbOrderNo>{$tb_order_no}</tbOrderNo>
<coopOrderSuccessTime></coopOrderSuccessTime>
<coopOrderStatus>FAILED</coopOrderStatus>
<failedReason>{$failed_reason}</failedReason>
<coopOrderNo>{$coop_order_no}</coopOrderNo>
<failedCode>{$failed_code}</failedCode>
<coopOrderSnap></coopOrderSnap>
</gamezctopquery>
";
        $this->addDepositResponseLog('query_fail_resp', $tb_order_no, $result);

        return $result;
    }

    private function queryUnderwayXml($tb_order_no)
    {
        $result = "<gamezctopquery>
<tbOrderNo>{$tb_order_no}</tbOrderNo>
<coopOrderSuccessTime></coopOrderSuccessTime>
<coopOrderStatus>UNDERWAY</coopOrderStatus>
<failedReason></failedReason>
<coopOrderNo></coopOrderNo>
<failedCode></failedCode>
<coopOrderSnap></coopOrderSnap>
</gamezctopquery>
";

        $this->addDepositResponseLog('query_underway_resp', $tb_order_no, $result);

        return $result;
    }

    /**
     * 充值, 采用Underway方式
     */
    public function order($coop_id, $tb_order_no, $card_id, $card_num, $customer, $sum, $gameid, $section1, $section2, $tb_order_snap, $notify_url, $version)
    {
        $source = 'tmall';
        $this->addDepositRequestLog('order', $tb_order_no);

        $validate_sign = $this->validateSign();
        if (!$validate_sign) {
            $failed_code = '0102';
            $failed_reason = 'validate sign fail';

            return $this->orderFailSignXml($tb_order_no, $failed_code, $failed_reason);
        }

        $card_info = $this->getCard($card_id);

        if (!$card_info) {
            $failed_code = '0305';
            $failed_reason = 'no cardinfo';
            return $this->orderOrderfailXml($tb_order_no, $failed_code, $failed_reason);
        }

        $coop_order_snap_arr = [
            'tsc' => $card_info['tsc'],
            'name' => $card_info['name'],
            'cash' => $card_info['cash'],
            'diamond' => $card_info['diamond'],
        ];

        $extends = [
            'parameter' => [
                'coopId' => $coop_id,
                'tbOrderNo' => $tb_order_no,
                'cardId' => $card_id,
                'cardNum' => $card_num,
                'customer' => $customer,
                'sum' => $sum,
                'gameId' => $gameid,
                'section1' => $section1,
                'section2' => $section2,
                'tbOrderSnap' => $tb_order_snap,
                'notifyUrl' => $notify_url,
                'version' => $version,
            ],
            'snap' => $coop_order_snap_arr,
        ];

        $dao_deposit = new DAODeposit();

        // 判断订单重复
        $key = "tmalldeposit:lock:{$tb_order_no}";
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        if ($cache->get($key)) {
            $failed_code = '0301';
            $failed_reason = 'order repeat';
            $result = $this->orderRepeatXml($tb_order_no, $failed_code, $failed_reason);

            return $result;
        } else {
            $deposit_info = $dao_deposit->getDepositInfoBySourceTradeid($source, $tb_order_no);
            if ($deposit_info) {
                $failed_code = '0301';
                $failed_reason = 'order repeat';
                $result = $this->orderRepeatXml($tb_order_no, $failed_code, $failed_reason);

                return $result;
            }
        }

        $orderid = Account::getOrderId();

        try {
            $dao_deposit->startTrans();

            $dao_deposit->prepare($customer, $orderid, $source, 'CNY', $card_info['cash'], $tb_order_no, $extends);

            $dao_deposit->commit();

            // 加锁
            $cache->add($key, '1', 1 * 60 * 60);

            $result = $this->orderUnderwayXml($tb_order_no, $orderid);

        } catch (MySQLException $e) {
            $dao_deposit->rollback();

            // 订单创建失败
            $failed_code = '0304';
            $failed_reason = "ERROR_SYS_DB_SQL";
            $result = $this->orderOrderfailXml($tb_order_no, $failed_code, $failed_reason);
        }

        return $result;
    }

    public function orderTest($coop_id, $tb_order_no, $card_id, $card_num, $customer, $sum, $gameid, $section1, $section2, $tb_order_snap, $notify_url, $version)
    {
        $source = 'tmall';
        $this->addDepositRequestLog('order', $tb_order_no);

        $validate_sign = $this->validateSign();
        if (!$validate_sign) {
            $failed_code = '0102';
            $failed_reason = 'validate sign fail';

            return $this->orderFailSignXml($tb_order_no, $failed_code, $failed_reason);
        }

        $result = "
<gamezctoporder>
<tbOrderNo>1232131321</tbOrderNo>
<coopOrderSuccessTime>20150729135912</coopOrderSuccessTime>
<coopOrderStatus>SUCCESS</coopOrderStatus>
<failedReason>the order is succeed</failedReason>
<coopOrderNo>123242423</coopOrderNo>
<failedCode>0701</failedCode>
<coopOrderSnap>taobao|game|order</coopOrderSnap>
</gamezctoporder>        
        ";

        return $result;
    }

    public function orderFailSignXml($tb_order_no, $failed_code, $failed_reason)
    {
        $result = "<gamezctoporder>
<tbOrderNo>{$tb_order_no}</tbOrderNo>
<coopOrderSuccessTime></coopOrderSuccessTime>
<coopOrderStatus>GENERAL_ERROR</coopOrderStatus>
<failedReason>{$failed_reason}</failedReason>
<coopOrderNo></coopOrderNo>
<failedCode>{$failed_code}</failedCode>
<coopOrderSnap></coopOrderSnap>
</gamezctoporder>
";

        $this->addDepositResponseLog('order_failsign_resp', $tb_order_no, $result);

        return $result;
    }

    public function orderRepeatXml($tb_order_no, $failed_code, $failed_reason)
    {
        $result = "<gamezctoporder>
<tbOrderNo>{$tb_order_no}</tbOrderNo>
<coopOrderSuccessTime></coopOrderSuccessTime>
<coopOrderStatus>GENERAL_ERROR</coopOrderStatus>
<failedReason>{$failed_reason}</failedReason>
<coopOrderNo></coopOrderNo>
<failedCode>{$failed_code}</failedCode>
<coopOrderSnap></coopOrderSnap>
</gamezctoporder>
";

        $this->addDepositResponseLog('order_repeat_resp', $tb_order_no, $result);

        return $result;
    }

    private function orderUnderwayXml($tb_order_no, $coop_order_no)
    {
        $result = "<gamezctoporder>
<tbOrderNo>{$tb_order_no}</tbOrderNo>
<coopOrderSuccessTime></coopOrderSuccessTime>
<coopOrderStatus>UNDERWAY</coopOrderStatus>
<failedReason></failedReason>
<coopOrderNo></coopOrderNo>
<failedCode></failedCode>
<coopOrderSnap></coopOrderSnap>
</gamezctoporder>
";
        $this->addDepositResponseLog('order_underway_resp', $tb_order_no, $result);

        return $result;
    }

    private function orderOrderfailXml($tb_order_no, $failed_code, $failed_reason)
    {
        $result = "<gamezctoporder>
<tbOrderNo>{$tb_order_no}</tbOrderNo>
<coopOrderSuccessTime></coopOrderSuccessTime>
<coopOrderStatus>ORDER_FAILED</coopOrderStatus>
<failedReason>{$failed_reason}</failedReason>
<coopOrderNo></coopOrderNo>
<failedCode>{$failed_code}</failedCode>
<coopOrderSnap></coopOrderSnap>
</gamezctoporder>
";
        $a = "
<gamezctoporder>
<tbOrderNo>1232131321</tbOrderNo>
<coopOrderSuccessTime>20150729135912</coopOrderSuccessTime>
<coopOrderStatus>SUCCESS</coopOrderStatus>
<failedReason>the order is succeed</failedReason>
<coopOrderNo>123242423</coopOrderNo>
<failedCode>0701</failedCode>
<coopOrderSnap>taobao|game|order</coopOrderSnap>
</gamezctoporder>        
        ";

        $this->addDepositResponseLog('order_fail_resp', $tb_order_no, $result);

        return $result;
    }

    public function cancel($coop_id, $tb_order_no, $version)
    {
        $source = 'tmall';
        $this->addDepositRequestLog('cancel', $tb_order_no);

        $validate_sign = $this->validateSign();
        if (!$validate_sign) {
            $failed_code = '0102';
            $failed_reason = 'validate sign fail';

            return $this->cancelFailSignXml($tb_order_no, $failed_code, $failed_reason);
        }

        $result = $this->channelUnderwayXml($tb_order_no);

        return $result;
    }

    public function cancelFailSignXml($tb_order_no, $failed_code, $failed_reason)
    {
        $result = "<gamezctopcancel>
<tbOrderNo>{$tb_order_no}</tbOrderNo>
<coopOrderNo></coopOrderNo>
<coopOrderStatus>GENERAL_ERROR</coopOrderStatus>
<coopOrderSnap></coopOrderSnap>
<coopOrderSuccessTime></coopOrderSuccessTime>
<failedCode>{$failed_code}</failedCode>
<failedReason>{$failed_reason}</failedReason>
</gamezctopcancel>";

        return $result;
    }

    private function channelUnderwayXml($tb_order_no)
    {
        $result = "<gamezctopcancel>
<tbOrderNo>{$tb_order_no}</tbOrderNo>
<coopOrderNo></coopOrderNo>
<coopOrderStatus>UNDERWAY</coopOrderStatus>
<coopOrderSnap></coopOrderSnap>
<coopOrderSuccessTime></coopOrderSuccessTime>
<failedCode></failedCode>
<failedReason></failedReason>
</gamezctopcancel>";

        return $result;
    }

    public function orderCrontab()
    {
        $dao_deposit = new DAODeposit();
        $data = $dao_deposit->getDepositInfoBySourceStatus('tmall', 'P');

        foreach ($data as $value) {
            // 异步通知天猫

            $orderid = $value['orderid'];
            // 查询该用户是否存在
            $userinfo = User::getUserInfo($value['uid']);

            if ($userinfo['uid']) {

                // 查询该第三方订单是否有过成功充值记录
                $success_deposit_info = $dao_deposit->getAllDepositInfoBySourceTradeidStatus('tmall', $value['tradeid'], 'Y');
                if ($success_deposit_info) {
                    // 第三方订单号重复, 失败订单
                    try {
                        $dao_deposit->startTrans();

                        $dao_deposit->setDepositStatus($orderid, 'N');

                        $dao_deposit->commit();
                    } catch (MySQLException $e) {

                        $dao_deposit->rollback();

                    }
                } else {

                    // 创建正常订单, 并充值
                    try {
                        // 充值
                        Deposit::complete($value['orderid'], $value['tradeid'], Deposit::DEPOSIT_TMALL);

                        $extends = json_decode($value['extends'], true);
                        $coop_order_snap = implode('|', $extends['snap']);
                        $deposit_info = $dao_deposit->getDepositInfoBySourceTradeid('tmall', $value['tradeid']);
                        $coop_order_success_time = date('YmdHis', strtotime($deposit_info['modtime']));
                        $this->updateSupplierorder(self::CoopId, $value['tradeid'], $value['orderid'], 'SUCCESS', $coop_order_snap, $coop_order_success_time, '', '');

                    } catch (Exception $e) {
                        var_dump($e->getCode(), $e->getMessage());
                    }
                }

            } else {
                // 被充值帐号错误, 创建失败订单
                try {
                    $dao_deposit->startTrans();

                    $dao_deposit->setDepositStatus($orderid, 'N');

                    $dao_deposit->commit();

                    $failed_code = '0502';
                    $failed_reason = "customer error";

                    // 我方成功后主动调用 淘宝游戏厂商订单更新 接口
                    // http://open.taobao.com/docs/api.htm?apiId=25363
                    $this->updateSupplierorder(self::CoopId, $value['tradeid'], $value['orderid'], 'FAILED', '', '', $failed_code, $failed_reason);

                } catch (MySQLException $e) {

                    $dao_deposit->rollback();

                }

            }

        }
        unset($value);
    }

    public function updateSupplierorder($coop_id, $tb_order_no, $coop_order_no, $coop_order_status, $coop_order_snap, $coop_order_success_time, $failed_code, $failed_reason)
    {
        $coop_id = sprintf('%s', $coop_id);
        $tb_order_no = sprintf('%s', $tb_order_no);
        $coop_order_no = sprintf('%s', $coop_order_no);
        $coop_order_snap = sprintf('%s', $coop_order_snap);
        $coop_order_success_time = sprintf('%s', $coop_order_success_time);
        $failed_code = sprintf('%s', $failed_code);
        $failed_reason = sprintf('%s', $failed_reason);

        $content = [
            'coop_id' => $coop_id,
            'tb_order_no' => $tb_order_no,
            'coop_order_no' => $coop_order_no,
            'coop_order_status' => $coop_order_status,
            'coop_order_snap' => $coop_order_snap,
            'coop_order_success_time' => $coop_order_success_time,
            'failed_code' => $failed_code,
            'failed_reason' => $failed_reason,
        ];
        $content = json_encode($content);
        $this->addDepositResponseLog('taobao.game.charge.zc.updatesupplierorder', $tb_order_no, $content);

        $c = new TopClient;
        $c->appkey = self::AppKey;
        $c->secretKey = self::AppSecret;
        $c->format = 'json';
        $req = new GameChargeZcUpdatesupplierorderRequest;
        $req->setCoopId($coop_id);
        $req->setTbOrderNo($tb_order_no);
        $req->setCoopOrderNo($coop_order_no);
        $req->setCoopOrderStatus($coop_order_status);
        $req->setCoopOrderSnap($coop_order_snap);
        $req->setCoopOrderSuccessTime($coop_order_success_time);
        $req->setFailedCode($failed_code);
        $req->setFailedReason($failed_reason);
        $req->setVersion("1.0.0");
        $resp = $c->execute($req);

        var_dump($resp);

        $result = false;
        $xml = $resp->update_result;

        $a = simplexml_load_string($xml);
        if ((string)$a->tbOrderSuccess === 'T') {
            $result = true;
        }

        $this->addDepositResponseLog('taobao.game.charge.zc.updatesupplierorder_resp', $tb_order_no, $xml);

        return $result;
    }

    private function getCard($card_id)
    {
        $card = [
            'dreamlivecard1' => ['name' => '追梦星钻60钻', 'cash' => '6', 'diamond' => '60', 'tsc' => 'dreamlivecard1'],
            'dreamlivecard2' => ['name' => '追梦星钻300钻', 'cash' => '30', 'diamond' => '300', 'tsc' => 'dreamlivecard2'],
            'dreamlivecard3' => ['name' => '追梦星钻580钻', 'cash' => '58', 'diamond' => '580', 'tsc' => 'dreamlivecard3'],
            'dreamlivecard4' => ['name' => '追梦星钻980钻', 'cash' => '98', 'diamond' => '980', 'tsc' => 'dreamlivecard4'],
            'dreamlivecard5' => ['name' => '追梦星钻2990钻', 'cash' => '299', 'diamond' => '2990', 'tsc' => 'dreamlivecard5'],
            'dreamlivecard6' => ['name' => '追梦星钻9980钻', 'cash' => '998', 'diamond' => '9980', 'tsc' => 'dreamlivecard6'],
            'dreamlivecard7' => ['name' => '追梦星钻29990钻', 'cash' => '2999', 'diamond' => '29990', 'tsc' => 'dreamlivecard7'],
            'dreamlivecard8' => ['name' => '追梦星钻99980钻', 'cash' => '9998', 'diamond' => '99980', 'tsc' => 'dreamlivecard8'],
            'dreamlivecard9' => ['name' => '追梦星钻299990钻', 'cash' => '29999', 'diamond' => '299990', 'tsc' => 'dreamlivecard9'],
            'dreamlivecard10' => ['name' => '追梦星钻588880钻', 'cash' => '58888', 'diamond' => '588880', 'tsc' => 'dreamlivecard10'],
            'dreamlivecard11' => ['name' => '追梦星钻888880钻', 'cash' => '88888', 'diamond' => '888880', 'tsc' => 'dreamlivecard11'],
            'dreamlivecard12' => ['name' => '追梦星钻1999990钻', 'cash' => '199999', 'diamond' => '1999990', 'tsc' => 'dreamlivecard12'],
        ];

        if ($card_id) {
            if (array_key_exists($card_id, $card)) {
                return $card[$card_id];
            } else {
                return '';
            }

        } else {
            return $card;
        }
    }

    private function addDepositRequestLog($api, $tradeid)
    {
        $data = [];
        foreach ($_GET as $key => $value) {
            $data[$key] = iconv('GBK', 'UTF-8', $value);
        }
        unset($value);

        $content = $data;
        $content = json_encode($content);

        Logger::log("payment", null, ['source' => 'tmall', 'api' => $api, 'tradeid' => $tradeid, 'data' => $content]);

        $depositlog_tmall = new DAODepositLogTmall();
        $depositlog_tmall->add($tradeid, $api, $content);
    }

    private function addDepositResponseLog($api, $tradeid, $content)
    {
        Logger::log("payment", null, ['source' => 'tmall', 'api' => $api, 'tradeid' => $tradeid, 'data' => $content]);

        $depositlog_tmall = new DAODepositLogTmall();
        $depositlog_tmall->add($tradeid, $api, $content);
    }

    private function validateSign()
    {
        $body = file_get_contents('php://input');
        return SpiUtils::checkSign4TextRequest($body, self::AppSecret);
    }
}