<?php

class MycardPay
{

    private static function w($msg)
    {
        $content['data']=$msg;
        $content['time']=date("Y-m-d H:i:s");
        $log=sys_get_temp_dir().'/mycar.log';
        if (!file_exists($log)) {
            touch($log);
        }
        file_put_contents($log, json_encode($content)."\n\n", FILE_APPEND);
    }

    public static function prepare($uid, $source, $currency, $amount)
    {
        //self::w(func_get_args());
        $FacserviceID = 'weiy104';
        $hash_key = 'VuTPHORBrXk3o69HlzdXfgZJztQ4nU4r';
        $Prepare_test_url = "https://test.b2b.mycard520.com.tw/MyBillingPay/api/AuthGlobal";
        $Prepare_url = "https://b2b.mycard520.com.tw/MyBillingPay/api/AuthGlobal";

        $test_pay_url = "http://test.mycard520.com.tw/MyCardPay";
        $pay_url = "http://www.mycard520.com.tw/MyCardPay";


        //流程: 准备1
        $orderid = Account::getOrderId($uid);
        $opts = array(
            'FacServiceId' => $FacserviceID,
            'FacTradeSeq' => $orderid,
            'TradeType' => '2',
            'CustomerId' => $uid,
            'ProductName' => '實體卡',
            'Amount' => $amount,
            'Currency' => $currency,
            'SandBoxMode' => 'true',
        );
        $data = $opts['FacServiceId'] . $opts['FacTradeSeq'] . $opts['TradeType'] . $opts['CustomerId'] . $opts['ProductName'] . $opts['Amount'] . $opts['Currency'] . $opts['SandBoxMode'] . $hash_key;
        $data_urlencode = urlencode($data);
        preg_match_all("/%([\w|\d]{2})/", $data_urlencode, $regs);
        if (is_array($regs[0])) {
            foreach ($regs[0] as $key => $value) {
                $data_urlencode = str_replace($value, strtolower($value), $data_urlencode);
            }
        }
        $opts['Hash'] = hash("sha256", $data_urlencode);
        //$context = json_encode($opts);
        $result = self::post($Prepare_url, $opts);//调取mycard获取令牌
        //self::w($result);
        $payment = json_decode($result, true);
        //self::w($payment);
        if ($payment['ReturnCode'] == 1) {
            $dao_deposit = new DAODeposit();
            $deposit = $dao_deposit->verify($payment['TradeSeq']);//检查是否有这个订单
            if (!isset($deposit['id'])) {
                $dao_deposit->prepare($uid, $orderid, $source, $currency, $amount, $payment['TradeSeq'], ['AuthCode'=>$payment['AuthCode']]);//插入一条订单
                $result = array("orderid" => $orderid, "amount" => $amount, 'AuthCode' => $payment['AuthCode'], "TradeSeq" => $payment['TradeSeq'], "notify_url" => "http://api.dreamlive.com/deposit/notify_mycard", "mycard_url" => $pay_url);
            }else{//重复订单

            }

        } else {
            $result = $payment;
        }

        return $result;
    }

    public static function notify()
    {
        $test_query_url = "http://test.b2b.mycard520.com.tw/MyBillingPay/api/TradeQuery";
        $query_url = "http://b2b.mycard520.com.tw/MyBillingPay/api/TradeQuery";
        self::w($_REQUEST);
        $orderid = $_REQUEST['FacTradeSeq'];

        $dao_deposit = new DAODeposit();
        $deposit_info = $dao_deposit->getDepositInfo($orderid);

        if (in_array($deposit_info['status'], array('P', 'N'))) {
            $extends=json_decode($deposit_info['extends'], true);
            $auth_code = $extends['AuthCode'];
            $query = self::post($query_url, array('AuthCode' => $auth_code));
            self::w($query);
            $query = json_decode($query, true);
            self::w($query);

            if ($query['ReturnCode'] == 1 && $query['PayResult'] == 3 && $query['FacTradeSeq'] == $orderid) { //成功
                Deposit::complete($orderid, $deposit_info['tradeid'], 'mycard');
                $return = true;
            } else { //失败
                $dao_deposit = new DAODeposit();
                $dao_deposit->setDepositStatus($orderid, 'N', $reason = ''); //$wx_notify

                $return = true;
            }
        } else {
            $return = true;
        }

        return $return;

    }

    public function verify($orderid)
    {
        $test_query_url = "http://test.b2b.mycard520.com.tw/MyBillingPay/api/TradeQuery";
        $query_url = "http://b2b.mycard520.com.tw/MyBillingPay/api/TradeQuery";

        $dao_deposit = new DAODeposit();
        $deposit_info = $dao_deposit->getDepositInfo($orderid);

        if (in_array($deposit_info['status'], array('P', 'N', 'C'))) {
            $extends=json_decode($deposit_info['extends'], true);
            $auth_code = $extends['AuthCode'];
            $query = self::post($test_query_url, array('AuthCode' => $auth_code));
            $query = json_decode($query, true);

            if ($query['ReturnCode'] == 1 && $query['PayResult'] == 3 && $query['FacTradeSeq'] == $orderid) { //更新成成功
                Deposit::complete($orderid, $deposit_info['tradeid'], 'TWD');
                $return = true;
            } else { //更新成失败
                $dao_deposit = new DAODeposit();
                $dao_deposit->setDepositStatus($orderid, 'N', $reason = ''); //$wx_notify

                $return = true;
            }
        } else {
            $return = true;
        }

        return $return;


    }

    public static function post($url, $post_data = '', $timeout = 5)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        //curl_setopt($ch, CURLOPT_PORT,443 );
        curl_setopt($ch, CURLOPT_POST, 1);
        if ($post_data != '') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $file_contents = curl_exec($ch);
        curl_close($ch);
        return $file_contents;
    }
}

?>