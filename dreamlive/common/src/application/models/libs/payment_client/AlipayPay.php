<?php
class AlipayPay
{
    const ALIPAY_PARTNER = "2016122004460499";
    const ALIPAY_PRIVATE_KEY = "MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBALkJdI5XyYGAXTMNaO9VS/2TIBOE7A8NjJdjosQDz6+IVERjmxdDJRf8k7p+fqPhh/MwKVcUxQCUe64UiowS3OrsbvWgBB1sn7END5MzfKaMlLgKpIC4iL0Yv5CUj32iS9ZXc6ZtJD7vfFk9J3gOaoezGaaqgY+xOsVVZF6FVSbpAgMBAAECgYBEfW15KqFZn+vfc709qXLhP04GK5M81yUM/EJJD6gWLv4R+lRzvdTDFiiQRTYW7unlaBRFOVjaKBvHia7mviyq3wwCMuZ8zC1id1pGrcHz3Jrk07lypVczOENNyQTV8yHDhCJnYg6ekEXshAE8f9udGk8jppw67GjjhHrpMapFqQJBANu4wys3/7Fl/82H9SAaLueVcU8YS1LFyo5xzcCo0OrUrZESD/sVg4oIRjEarZmR+ntB5Fszn+kiQTsRBSrE0UcCQQDXlqE7/h7XzTLQFHXeGvZ3nEA5rUdJlgbp46iRY1x9zkI6+Vka+Owp8wANUafkxaweaDDYywSw/Hdh3zQoe95PAkA4QP6e8xBgz9eFPJjSpkF6AzXmZTbrsz4f6B0ghVgvt1HUwYYb568syN+HtOfbWJeDtSQNAZOgcae3wqzK/WcDAkEA1ihNtlizrs+qIEWS6LWEDEFtE15sKE8eQwzhkLtRT6+q3wZ/W2nWv70iWhi5XWp3liUOEO1rlZzVwqWFHIofxQJBAJpcEMLdtizOjf9VPZupl1Y4YnkH+5HAxwV2xeb8CTvJkoGkpIV47SHt/digcdkEGLbdySZJMNcAAEkUXkRn6Xc=";
    const ALIPAY_SERVICE = "alipay.trade.app.pay";
    const ALIPAY_SIGN_TYPE = "RSA";
    const ALIPAY_NOTIFY_URL = $API_DOMAIN_NAME . "/deposit/notify_alipay";
    const ALIPAY_RETURN_URL = $HTML5_DOMAIN_NAME . "/pay_complete.php";
    const ALIPAY_SELLER_EMAIL = "2088521164070853";//3501146437@qq.com
    const ALIPAY_PUBLIC_KEY    = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDDI6d306Q8fIfCOaTXyiUeJHkrIvYISRcc73s3vF1ZT7XN8RNPwJxo8pWaJMmvyTn9N4HQ632qJBVHf8sxHi/fEsraprwCtzvzQETrNRwVxLO5jVmRGi60j8Ue1efIlzPXV9je9mkjzOmdssymZkh2QhUrCmZYI/FCEa3/cNMW0QIDAQAB';
    public function __contrust()
    {
        
    }
    
    public static function prepare($uid, $source, $currency, $amount)
    {
        $orderid = Account::getOrderId($uid);
        
        if ($_REQUEST['type'] == 'js') {        
            include_once "alipay/aop/AopClient.php";
            include_once "alipay/aop/request/AlipayTradeService.php";
            include_once "alipay/aop/request/AlipayTradeWapPayRequest.php";
            include_once "alipay/aop/request/AlipayTradeWapPayContentBuilder.php";
            //订单名称，必填
            $subject = '追梦直播充值';
            //商品描述，可空
            $body = '支付宝公众号充值';
            
            //超时时间
            $timeout_express="1m";
            
            $payRequestBuilder = new AlipayTradeWapPayContentBuilder();
            $payRequestBuilder->setBody($body);
            $payRequestBuilder->setSubject($subject);
            $payRequestBuilder->setOutTradeNo($orderid);
            $payRequestBuilder->setTotalAmount($amount);
            $payRequestBuilder->setTimeExpress($timeout_express);
            $config = array(
                'gatewayUrl' => 'https://openapi.alipay.com/gateway.do',
                'app_id'        => self::ALIPAY_PARTNER, 
                "merchant_private_key" => self::ALIPAY_PRIVATE_KEY, 
                'alipay_public_key'=> self::ALIPAY_PUBLIC_KEY, 
                'charset'        => 'UTF-8', 
                'sign_type'    => self::ALIPAY_SIGN_TYPE, 
                'return_url' => self::ALIPAY_RETURN_URL, 
                'notify_url' => self::ALIPAY_NOTIFY_URL
            );
            $payResponse = new AlipayTradeService($config);

            $form=$payResponse->wapPay($payRequestBuilder, $config['return_url'], $config['notify_url']);

            $dao_deposit = new DAODeposit();
            
            $dao_deposit->prepare($uid, $orderid, $source, $currency, $amount, '');
            
            $return = array('form'=>$form);
            
        }  elseif($_REQUEST['type'] == 'native') {
            include_once "alipay/aop/AopClient.php";
            include_once "alipay/aop/request/AlipayTradePrecreateRequest.php";
            include_once "alipay/f2fpay/model/builder/AlipayTradePrecreateContentBuilder.php";
            include_once 'alipay/f2fpay/service/AlipayTradeService.php';
            include_once "alipay/aop/SignData.php";

            

            $outTradeNo = $orderid;

            // (必填) 订单标题，粗略描述用户的支付目的。如“xxx品牌xxx门店当面付扫码消费”
            $subject = '追梦直播充值';

            // (必填) 订单总金额，单位为元，不能超过1亿元
            // 如果同时传入了【打折金额】,【不可打折金额】,【订单总金额】三者,则必须满足如下条件:【订单总金额】=【打折金额】+【不可打折金额】
            $totalAmount = $amount;

            // 订单描述，可以对交易或商品进行一个详细地描述，比如填写"购买商品2件共15.00元"
            $body = "支付宝官网充值";

            //商户操作员编号，添加此参数可以为商户操作员做销售统计
            $operatorId = "operator_id_007";

            

            // 业务扩展参数，目前可添加由支付宝分配的系统商编号(通过setSysServiceProviderId方法)，系统商开发使用,详情请咨询支付宝技术支持
            //$providerId = ""; //系统商pid,作为系统商返佣数据提取的依据
            //$extendParams = new ExtendParams();
            //$extendParams->setSysServiceProviderId($providerId);
            //$extendParamsArr = $extendParams->getExtendParams();

            // 支付超时，线下扫码交易定义为5分钟
            $timeExpress = "5m";

            // 商品明细列表，需填写购买商品详细信息，
            $goodsDetailList = array();

            // 创建一个商品信息，参数含义分别为商品id（使用国标）、名称、单价（单位为分）、数量，如果需要添加商品类别，详见GoodsDetail
            $goods1 = new GoodsDetail();
            $goods1->setGoodsId("diamond-01");
            $goods1->setGoodsName("充值币");
            $goods1->setPrice($amount);
            $goods1->setQuantity(1);
            //得到商品1明细数组
            $goods1Arr = $goods1->getGoodsDetail();

            

            $goodsDetailList = array($goods1Arr,$goods2Arr);

            //第三方应用授权令牌,商户授权系统商开发模式下使用
            //$appAuthToken = "";//根据真实值填写

            // 创建请求builder，设置请求参数
            $qrPayRequestBuilder = new AlipayTradePrecreateContentBuilder();
            $qrPayRequestBuilder->setOutTradeNo($outTradeNo);
            $qrPayRequestBuilder->setTotalAmount($totalAmount);
            $qrPayRequestBuilder->setTimeExpress($timeExpress);
            $qrPayRequestBuilder->setSubject($subject);
            $qrPayRequestBuilder->setBody($body);
            $qrPayRequestBuilder->setUndiscountableAmount($undiscountableAmount);
            $qrPayRequestBuilder->setExtendParams($extendParamsArr);
            $qrPayRequestBuilder->setGoodsDetailList($goodsDetailList);
            $qrPayRequestBuilder->setStoreId($storeId);
            $qrPayRequestBuilder->setOperatorId($operatorId);
            $qrPayRequestBuilder->setAlipayStoreId($alipayStoreId);

            //$qrPayRequestBuilder->setAppAuthToken($appAuthToken);


            $config = array(
            'gatewayUrl' => 'https://openapi.alipay.com/gateway.do',
            'app_id'        => self::ALIPAY_PARTNER, 
            "merchant_private_key" => self::ALIPAY_PRIVATE_KEY, 
            'alipay_public_key'=> self::ALIPAY_PUBLIC_KEY, 
            'charset'        => 'UTF-8', 
            'sign_type'    => self::ALIPAY_SIGN_TYPE, 
            'MaxQueryRetry' => 10,
            'QueryDuration'=> 3,
            'return_url' => self::ALIPAY_RETURN_URL, 
            'notify_url' => self::ALIPAY_NOTIFY_URL
            );



            // 调用qrPay方法获取当面付应答
            $qrPay = new AlipayTradeService($config);
            $qrPayResult = $qrPay->qrPay($qrPayRequestBuilder);

            //    根据状态值进行业务处理
            switch ($qrPayResult->getTradeStatus()){
            case "SUCCESS":
                $dao_deposit = new DAODeposit();
                $result = $dao_deposit->prepare($uid, $orderid, $source, $currency, $amount, '', array('app'=>'web'));
                $response = $qrPayResult->getResponse();
                $return = array("orderid" => $orderid, "response" => $response);
                break;
            case "FAILED":
                if(!empty($qrPayResult->getResponse())) {
                    print_r($qrPayResult->getResponse());
                }
                break;
            case "UNKNOWN":
                if(!empty($qrPayResult->getResponse())) {
                    print_r($qrPayResult->getResponse());
                }
                break;
            default:
                echo "不支持的返回状态，创建订单二维码返回异常!!!";
                break;
            }


        } else {
            include_once "alipay/aop/AopClient.php";
            include_once "alipay/aop/request/AlipayTradeAppPayRequest.php";
            $request =  new AlipayTradeAppPayRequest();
            $request->setNotifyUrl(self::ALIPAY_NOTIFY_URL);
            $request->setBizContent("{\"timeout_express\":\"90m\",\"product_code\":\"QUICK_MSECURITY_PAY\",\"total_amount\":\"{$amount}\",\"subject\":\"充值\",\"body\":\"充值\",\"out_trade_no\":\"{$orderid}\"}");
            $aopClient = new AopClient();
            $data = $aopClient->sdkExecute($request);
            $dao_deposit = new DAODeposit();
            $result = $dao_deposit->prepare($uid, $orderid, $source, $currency, $amount, '');
            $return = array("orderid" => $orderid, "alipayStr" => $data);
            
        }
        
        return $return;
    }
    
    public function verify($orderid)
    {
        $dao_deposit  = new DAODeposit();
        $deposit_info = $dao_deposit->getDepositInfo($orderid);
        if (in_array($deposit_info['status'], array('P', 'N', 'C')) && !empty($deposit_info['tradeid']) && $deposit_info['source'] == Deposit::DEPOSIT_ALIPAY) { //查询没有充成功
            $alipay_trade_id = $deposit_info['tradeid'];
            
            include_once "alipay/aop/AopClient.php";
            include_once "alipay/aop/SignData.php";
            include_once "alipay/aop/request/AlipayTradeQueryRequest.php";
            $aop = new AopClient();
            $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
            $aop->appId = self::ALIPAY_PARTNER;
            $aop->rsaPrivateKey = self::ALIPAY_PRIVATE_KEY;
            $aop->alipayrsaPublicKey= self::ALIPAY_PUBLIC_KEY;
            $aop->apiVersion = '1.0';
            $aop->signType = 'RSA';
            $aop->postCharset='UTF-8';
            $aop->format='json';
            $request = new AlipayTradeQueryRequest();
            $request->setBizContent(
                "{" .
                "    \"out_trade_no\":\"\"," .
                "    \"trade_no\":\"{$alipay_trade_id}\"" .
                "  }"
            );
            $result = $aop->execute($request);

            $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
            $resultCode = $result->$responseNode->code;
            if(!empty($resultCode) && $resultCode == 10000) {
                if ($result->alipay_trade_query_response->trade_status == 'TRADE_SUCCESS' && $result->alipay_trade_query_response->total_amount == $deposit_info['amount']) {
                    Deposit::complete($orderid, $alipay_trade_id, Deposit::DEPOSIT_ALIPAY);
                    return true;
                } else {
                    $dao_deposit = new DAODeposit();
                    $dao_deposit->setDepositStatus($orderid, 'N', $reason = '系统回报失败刷钻用户');
                    return false;
                }
                
            } else {
                $dao_deposit = new DAODeposit();
                $dao_deposit->setDepositStatus($orderid, 'N', $reason = '系统回报失败');
                return false;
            }
        } else {
            if($deposit_info['status'] == 'Y') {
                return true;
            } else {
                return false;
            }
        }
    }
    
    /**
     * stdClass Object
   
       [alipay_trade_query_response] => stdClass Object
           (
               [code] => 10000
               [msg] => Success
               [buyer_logon_id] => www***@hotmail.com
               [buyer_pay_amount] => 0.00
               [buyer_user_id] => 2088102911594743
               [invoice_amount] => 0.00
               [open_id] => 20881009001791330622278711018574
               [out_trade_no] => 17032716592499916836
               [point_amount] => 0.00
               [receipt_amount] => 0.00
               [send_pay_date] => 2017-03-27 16:59:32
               [total_amount] => 1.00
               [trade_no] => 2017032721001004740238580192
               [trade_status] => TRADE_SUCCESS
           )
          [sign] => HvNUi6cKonr2UWLuYBisv9TE1p7T+hCb45/I3fEuDmc7hm30jTX5xhRxkMs/lU5fcu5Umoqe7IIg2/9ZqLZZfJxU4g+xDMcf6xchCdhZhaM/hE7YsWa4AG5orbonNNNNg0GqxtR5am8dalmmozBGSlq+Tc17EOcI3xqbUhvmy3w=
   
     * @param  unknown $alipay_trade_id
     * @param  unknown $amount
     * @return boolean
     */
    static public function server_verify($alipay_trade_id, $amount)
    {

        include_once "alipay/aop/AopClient.php";
        include_once "alipay/aop/SignData.php";
        include_once "alipay/aop/request/AlipayTradeQueryRequest.php";
        $aop = new AopClient();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = self::ALIPAY_PARTNER;
        $aop->rsaPrivateKey = self::ALIPAY_PRIVATE_KEY;
        $aop->alipayrsaPublicKey= self::ALIPAY_PUBLIC_KEY;
        $aop->apiVersion = '1.0';
        $aop->signType = 'RSA';
        $aop->postCharset='UTF-8';
        $aop->format='json';
        $request = new AlipayTradeQueryRequest();
        $request->setBizContent(
            "{" .
            "    \"out_trade_no\":\"\"," .
            "    \"trade_no\":\"{$alipay_trade_id}\"" .
            "  }"
        );
        $result = $aop->execute($request);

        $responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $resultCode = $result->$responseNode->code;
        if(!empty($resultCode) && $resultCode == 10000) {
            if ($result->alipay_trade_query_response->trade_status == 'TRADE_SUCCESS' && $result->alipay_trade_query_response->total_amount == $amount) {
                return true;
            } else {
                return false;
            }
            
        } else {
            return false;
        }
    }
    
    public static function notify() 
    {
        //$json = '{"total_amount":"0.10","buyer_id":"2088402171021990","trade_no":"2017011221001004990276080387","body":"\u5145\u503c","notify_time":"2017-01-12 17:30:12","subject":"\u5145\u503c","sign_type":"RSA","buyer_logon_id":"tia***@163.com","auth_app_id":"2016122004460499","charset":"UTF-8","notify_type":"trade_status_sync","invoice_amount":"0.10","out_trade_no":"17011217300000077309","trade_status":"TRADE_SUCCESS","gmt_payment":"2017-01-12 17:30:12","version":"1.0","point_amount":"0.00","sign":"uzW\/gwkpZBlwh+JKF9q4Lbm1tQqTZTCcC90IaG4f4w68tfd+rpa7HstqiRPEgDufladxcgdOwCEwVRXQ90NtXI2D5PRVMEGQcN3T7pKCRzwK+8J3a0PIwq\/hSFF0y77aPoJNBROMBfBUfhrebhqMmYrTyAfQgyTLc2oC3sTJQGU=","gmt_create":"2017-01-12 17:30:11","buyer_pay_amount":"0.10","receipt_amount":"0.10","fund_bill_list":"[{\"amount\":\"0.10\",\"fundChannel\":\"ALIPAYACCOUNT\"}]","app_id":"2016122004460499","seller_id":"2088521164070853","notify_id":"e712526558e89072d22e5c91d3d48dfnn2","seller_email":"3501146437@qq.com"}';
        //$json = '{"total_amount":"0.01","buyer_id":"2088502763333813","trade_no":"2017012021001004810258947644","body":"\u5145\u503c","notify_time":"2017-01-20 12:57:36","subject":"\u5145\u503c","sign_type":"RSA","buyer_logon_id":"159***@qq.com","auth_app_id":"2016122004460499","charset":"UTF-8","notify_type":"trade_status_sync","invoice_amount":"0.01","out_trade_no":"17012012393400014758","trade_status":"TRADE_SUCCESS","gmt_payment":"2017-01-20 12:39:47","version":"1.0","point_amount":"0.00","sign":"WrSIEjz\/8924zwp6tN7xCdmvKQ\/9PFBQhezwoLuGXj8LJIB576BWwuEO\/75w0Ggy1YkpBhLzmaFRNTAv9E654nA\/E5yweLzkvjqiswKRxQ8FohYIH1htvCnmtzE9q1UNvGk13NC7FzsElaXT+hWmN9PwIlEnXQZXvGX\/WEUCipA=","gmt_create":"2017-01-20 12:39:46","buyer_pay_amount":"0.01","receipt_amount":"0.01","fund_bill_list":"[{\"amount\":\"0.01\",\"fundChannel\":\"ALIPAYACCOUNT\"}]","app_id":"2016122004460499","seller_id":"2088521164070853","notify_id":"603bdd282f6c69d5db8c96801fcf1dcm92","seller_email":"3501146437@qq.com"}';
        $json = $_REQUEST;
        $request = $json;
        if (empty($request['out_trade_no'])) {
            if ($_REQUEST['status'] == 'cancel' && $_REQUEST['orderid']) {
                $dao_deposit = new DAODeposit();
                $deposit_info = $dao_deposit->getDepositInfo($_REQUEST['orderid']);
                if ($deposit_info['status'] == 'Y') { //成功不做任何处理
                    return true;
                } else {
                    $dao_deposit->setDepositStatus($_REQUEST['orderid'], 'C', $reason = 'APP回报取消');
                    return false;
                }
            }
        }
        include_once "alipay/aop/AopClient.php";
        $aopClient = new AopClient();
        if ($aopClient->rsaCheckV1($request, '', 'RSA')) {
            $out_trade_no = $request['out_trade_no'];//追梦充值交易号
            
            //支付宝交易号
            $trade_no = $request['trade_no'];
            if (!empty($out_trade_no) && !empty($trade_no)) {
                $dao_deposit  = new DAODeposit();
                $deposit_info = $dao_deposit->getDepositInfo($out_trade_no);
                if (in_array($deposit_info['status'], array('P', 'N', 'C')) && $deposit_info['source'] == Deposit::DEPOSIT_ALIPAY) { //查询没有充成功
                    if ($request['trade_status'] == 'TRADE_SUCCESS') {
                        if (self::server_verify($trade_no, $deposit_info['amount'])) {
                               Deposit::complete($out_trade_no, $trade_no, Deposit::DEPOSIT_ALIPAY);
                               echo "success";
                        } else {
                            echo "fail";
                        }
                    } else {
                        echo "fail";
                    }
                } else {
                    echo "success";//系统已经处理成功
                }
            } else {
                echo "param error";//解析参数错误
            }
        } else {
            echo "sign fail";//验签
        }
        die();
    }

    public static function bill($bill_date)
    {
        include_once "alipay/aop/AopClient.php";
        include_once "alipay/aop/SignData.php";
        include_once "alipay/aop/request/AlipayTradeQueryRequest.php";
        include_once "alipay/aop/request/AlipayDataDataserviceBillDownloadurlQueryRequest.php";
        $aop = new AopClient();
        $aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
        $aop->appId = self::ALIPAY_PARTNER;
        $aop->rsaPrivateKey = self::ALIPAY_PRIVATE_KEY;
        $aop->alipayrsaPublicKey= self::ALIPAY_PUBLIC_KEY;
        $aop->apiVersion = '1.0';
        $aop->signType = 'RSA';
        $aop->postCharset='UTF-8';
        $aop->format='json';
        $request = new alipaydatadataservicebilldownloadurlqueryRequest();
        $request_data = [
         'bill_type' => 'trade',
            'bill_date' => $bill_date,
        ];
        $request_data = json_encode($request_data);
        $request->setBizContent($request_data);
        $result = $aop->execute($request);

        $response_node = str_replace(".", "_", $request->getApiMethodName()) . "_response";
        $result_code = $result->$response_node->code;
        if(!empty($result_code) && $result_code == 10000) {
            $file_path = '';
            $file_content = file_get_contents($result->$response_node->bill_download_url); // 支付宝提供的zip包文件

            if($file_content) {
                $zip_file_name = '';
                $bill_download_url = parse_url($result->$response_node->bill_download_url);
                $bill_download_url = $bill_download_url['query'];
                $bill_download_url = explode('&', $bill_download_url);
                foreach ($bill_download_url as $value) {
                    $d = explode('=', $value);
                    if($d[0] == 'downloadFileName') {
                        $zip_file_name = $d[1];
                        break;
                    }
                }
                unset($value);

                $file_path = "/tmp/balance/{$zip_file_name}";
                file_put_contents($file_path, $file_content);
            }

            return $file_path;
        } else {
            return false;
        }
    }
}
?>