<?php
require_once dirname(__FILE__).DIRECTORY_SEPARATOR. "wechat". DIRECTORY_SEPARATOR. "WxPay.Api.php";

class WechatPay
{
    public static function prepare($uid, $source, $currency, $amount)
    {
        $orderid = Account::getOrderId($uid);

        if ($_REQUEST['type'] == 'js') {
            $openid  = $_REQUEST['openid'];
            $wx = new WxPayApi();
            $input = new WxPayUnifiedOrder();

            $input->SetBody("充值-js-$orderid");
            $input->SetAttach($orderid);
            $input->SetOut_trade_no($orderid);
            $input->SetTotal_fee($amount*100);
            $input->SetTime_start(date("YmdHis"));
            $input->SetTime_expire(date("YmdHis", time() + 600));
            $input->SetOpenid($openid);
            $input->SetNotify_url($API_DOMAIN_NAME . "/deposit/notify_wechat_js"); //js
            $input->SetTrade_type("JSAPI");
            $order = WxPayApi::unifiedOrder($input);

            Interceptor::ensureNotFalse($order['return_code'] != 'FAIL', ERROR_CUSTOM, $order['return_msg']);

            $dao_deposit = new DAODeposit();
            $result = $dao_deposit->prepare($uid, $orderid, $source, $currency, $amount, $order['prepay_id']);
            $jsapi = new WxPayJsApiPay();
            $jsapi->SetAppid($order["appid"]);
            $timeStamp = time();
            $jsapi->SetTimeStamp("$timeStamp");
            $jsapi->SetNonceStr(WxPayApi::getNonceStr());
            $jsapi->SetPackage("prepay_id=" . $order['prepay_id']);
            $jsapi->SetSignType("MD5");
            $jsapi->SetPaySign($jsapi->MakeSign());
            $return = $jsapi->GetValues();
        } elseif ($_REQUEST['type'] == 'native') {
            $wx = new WxPayApi();
            $input = new WxPayUnifiedOrder();

            $input->SetBody("充值-$orderid");
            $input->SetAttach($orderid);
            $input->SetOut_trade_no($orderid);
            $input->SetTotal_fee($amount*100);
            $input->SetTime_start(date("YmdHis"));
            $input->SetTime_expire(date("YmdHis", time() + 600));
            $input->SetNotify_url($API_DOMAIN_NAME . "/deposit/notify_wechat");
            $input->SetTrade_type("NATIVE");
            $input->SetProduct_id($orderid);
            $order = WxPayApi::unifiedOrder($input);

            Interceptor::ensureNotFalse($order['return_code'] != 'FAIL', ERROR_CUSTOM, $order['return_msg']);

            $dao_deposit = new DAODeposit();
            $result = $dao_deposit->prepare($uid, $orderid, $source, $currency, $amount, $order['prepay_id'], array('app'=>'web'));

            $return = array();
            $return['appid'] = $order['appid'];
            $return['noncestr'] = WxPayApi::getNonceStr();
            $return['package'] = "Sign=WXPay";
            $return['partnerid'] = $order['mch_id'];
            $return['prepayid'] = $order['prepay_id'];
            $return['timestamp'] = time();
            //签名步骤一：按字典序排序参数
            ksort($return);
            foreach ($return as $k => $v) {
                if ($k != "sign" && $v != "" && !is_array($v)) {
                    $buff .= $k . "=" . $v . "&";
                }
            }

            $string = trim($buff, "&");
            //签名步骤二：在string后加入KEY
            $string = $string . "&key=" . WxPayConfig::KEY;
            $return['s'] = $string;
            //签名步骤三：MD5加密
            $string = md5($string);
            //签名步骤四：所有字符转为大写
            $sign_string = strtolower($string);
            $return['sign'] = $sign_string;
            $return['orderid'] = $orderid;
            $return['code_url'] = $order['code_url'];
        } else {
            $wx = new WxPayApi();
            $input = new WxPayUnifiedOrder();

            $input->SetBody("充值-$orderid");
            $input->SetAttach($orderid);
            $input->SetOut_trade_no($orderid);
            $input->SetTotal_fee($amount*100);
            $input->SetTime_start(date("YmdHis"));
            $input->SetTime_expire(date("YmdHis", time() + 600));
            $input->SetNotify_url($API_DOMAIN_NAME . "/deposit/notify_wechat");
            $input->SetTrade_type("APP");
            $order = WxPayApi::unifiedOrder($input);

            Interceptor::ensureNotFalse($order['return_code'] != 'FAIL', ERROR_CUSTOM, $order['return_msg']);

            $dao_deposit = new DAODeposit();
            $result = $dao_deposit->prepare($uid, $orderid, $source, $currency, $amount, $order['prepay_id']);

            $return = array();
            $return['appid'] = $order['appid'];
            $return['noncestr'] = WxPayApi::getNonceStr();
            $return['package'] = "Sign=WXPay";
            $return['partnerid'] = $order['mch_id'];
            $return['prepayid'] = $order['prepay_id'];
            $return['timestamp'] = time();
            //签名步骤一：按字典序排序参数
            ksort($return);
            foreach ($return as $k => $v) {
                if ($k != "sign" && $v != "" && !is_array($v)) {
                    $buff .= $k . "=" . $v . "&";
                }
            }

            $string = trim($buff, "&");
            //签名步骤二：在string后加入KEY
            $string = $string . "&key=" . WxPayConfig::KEY;
            $return['s'] = $string;
            //签名步骤三：MD5加密
            $string = md5($string);
            //签名步骤四：所有字符转为大写
            $sign_string = strtolower($string);
            $return['sign'] = $sign_string;
            $return['orderid'] = $orderid;
        }

        return $return;
    }

    public static function notify()
    {
        $xml = file_get_contents("php://input");
        if ($xml) {
            $WxPayResults = new WxPayResults();
            $wx_notify = $WxPayResults->Init($xml);
            $orderid = $wx_notify['out_trade_no'];
        }

        if (!$orderid) {
            $orderid = $_REQUEST['orderid'];
            $status  = $_REQUEST['status'];
        }

        $dao_deposit = new DAODeposit();
        $deposit_info = $dao_deposit->getDepositInfo($orderid);

        // |===========================================================================================
        // | 处理流程
        // | 1. 检测交易是否成功， 成功不做任何处理
        // | 2. 检测交易不成功, 2.1 如果成功则更新成成功
        // | 2.2. 增加用户的票
        // | 2.3. 增加一条交易.
        // | 3. 如果交易不成功, 则更新成不成功.
        if ($deposit_info['status'] == 'Y') { //成功不做任何处理
            $return = true;
        } else {
            if ($wx_notify['result_code'] == 'SUCCESS') {
                $input    = new WxPayOrderQuery();
                $input->SetTransaction_id($wx_notify['transaction_id']);
                
                $wxPayApi = new WxPayApi();
                $order    = $wxPayApi->orderQuery($input);
                
                $total_fee = $order['total_fee']/100;
                $fee_type  = $order['fee_type'];
                if($order['result_code'] == 'SUCCESS' && $order['trade_state'] == 'SUCCESS' && $fee_type==$deposit_info['currency'] && $total_fee == intval($deposit_info['amount'])) {
                    Deposit::complete($orderid, $wx_notify['transaction_id'], Deposit::DEPOSIT_WECHAT);
                    
                    $return = true;
                }
                return false;
            } else {
                if ($status == 'cancel') {
                    $dao_deposit = new DAODeposit();
                    $dao_deposit->setDepositStatus($orderid, 'C', $reason = 'APP回报取消');
                    $return = false;
                } else {
                    $dao_deposit = new DAODeposit();
                    $dao_deposit->setDepositStatus($orderid, 'N', $reason = '系统回报失败');
                    $return = false;
                }
            }
            
        }
        if($return) { //微信报警功能给出的回复
            echo 'SUCCESS';
        } else {
            echo 'FAIL';
        }
        exit;

        return $return;
    }

    public function verify($orderid)
    {
        $dao_deposit  = new DAODeposit();
        $deposit_info = $dao_deposit->getDepositInfo($orderid);
        if (in_array($deposit_info['status'], array('P', 'N', 'C'))) { //查询没有充成功
            $wx = new WxPayApi();
            $input = new WxPayOrderQuery();
            $input->SetOut_trade_no($orderid);
            $order = WxPayApi::orderQuery($input);
            
            $total_fee = $order['total_fee']/100;
            $fee_type  = $order['fee_type'];
            if ($order['result_code'] == 'SUCCESS' && $order['trade_state'] == 'SUCCESS' && $deposit_info['currency'] == $fee_type && $total_fee == intval($deposit_info['amount'])) {
                Deposit::complete($orderid, $order['transaction_id'], Deposit::DEPOSIT_WECHAT);
                $return = true;
            } else {
                $dao_deposit = new DAODeposit();
                $dao_deposit->setDepositStatus($orderid, 'N', $reason = '系统回报失败');
                $return = false;
            }
        } else {
            $return = true;
        }

        return $return;
    }

    public function bill($bill_date)
    {
        $bill_type = 'ALL';

        if($_REQUEST['type'] == 'js' ) {
            $file_name = sprintf('%s_%s_%s_%s.%s', 'I', 'wechat5', date('Ymd', strtotime($bill_date)), '1', 'csv');
        } else {
            $file_name = sprintf('%s_%s_%s_%s.%s', 'I', 'wechat', date('Ymd', strtotime($bill_date)), '1', 'csv');
        }
        $file_name = strtoupper($file_name);

        $input = new WxPayDownloadBill();
        $input->SetBill_date($bill_date);
        $input->SetBill_type($bill_type);
        $file_content = WxPayApi::downloadBill($input);
        $file_path = "/tmp/balance/{$file_name}";
        file_put_contents($file_path, $file_content);

        return $file_path;
    }
}