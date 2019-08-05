<?php
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class PaypalPay
{
    const PAYPAL_CLIENT_ID         = "Ab_r5a77061EylVEEoVLM5cf8h2lsb8xiAd7qT6IKVF90zG0LFz1-HVEYbAL9O_lygGEgBQkHyt6_Nuk";//正式
    const PAYPAL_SECRET            = "EMTqjBlsjJWrdlKWy1OzzHskxXmEy2M_42kB4Klk6wmLPPNzL5Pay5Ne5HAF6WlDTqhC6FLtQKA4TUvO";//正式

    const PAYPAL_CLIENT_ID_TEST = "ATC5TCbtnZw3vHCmhS4igleEC4kvhzFfiUfP7vzI3VN44w6TdpB81uud_iFqpDLSGfBvH3BkLCM3qGKR";//测试
    const PAYPAL_SECRET_TEST     = "EESMfOiT7j2YyEULlLjVuETAVJuEPsxlCBrec4DqzXgkeEBPEAcHMG8hjjC5l18NHDBkM46xCg1VIcYr";//测试
    
    public static function prepare($uid, $source, $currency, $amount)
    {
        global $API_DOMAIN;
        //流程: 准备=>
        $orderid = Account::getOrderId($uid);
        
        $dao_deposit = new DAODeposit();
        $result = $dao_deposit->prepare($uid, $orderid, $source, $currency, $amount, '');
        
        return array("orderid" => $orderid, "amount" => $amount, "notify_url" => $API_DOMAIN . "/deposit/notify_paypal");
    }
    
    public static function notify()
    {
        //参考接入网址1: https://github.com/paypal/PayPal-Android-SDK/blob/master/docs/single_payment.md
        //参考接入网址2: https://developer.paypal.com/webapps/developer/docs/integration/mobile/verify-mobile-payment/
        //如果能传入订单id, 就不需要走订单唯一对应表. 如果不难传入就得参考ios处理方法, 待sdk确认

        try {
            $payment_client = json_decode($_REQUEST['data'], true);
            $paymentId = $payment_client['response']['id'];//客户端支付的id，paypal生成的
            $orderid   = $_REQUEST['orderid'];
            if (!$paymentId) {
                if ($_REQUEST['status'] == 'cancel') {
                    $dao_deposit = new DAODeposit();
                    $dao_deposit->setDepositStatus($_REQUEST['orderid'], 'C', $reason = 'APP回报取消');
                    return false;
                }
            }

            //$apiContext = getApiContext(self::PAYPAL_CLIENT_ID_TEST, self::PAYPAL_SECRET_TEST);
            $apiContext = getApiContext(self::PAYPAL_CLIENT_ID, self::PAYPAL_SECRET);
            // Gettin payment details by making call to paypal rest api
            $payment = \PayPal\Api\Payment::get($paymentId, $apiContext);//去paypal服务器查询

            // Verifying the state approved
            if ($payment->getState() != 'approved') {
                $return["error"] = true;
                $return["message"] = "支付未通过 " . $payment->getState();
                return;
            }

            // Paypal transactions
            $transaction = $payment->getTransactions()[0];
            // Amount on server side
            $amount_server = $transaction->getAmount()->getTotal();//paypal服务器给的支付金额
            // Currency on server side
            $currency_server = $transaction->getAmount()->getCurrency();//paypal服务器给的支付币种
            $sale_state = $transaction->getRelatedResources()[0]->getSale()->getState();


            if ($sale_state != 'completed') {
                $return["error"] = true;
                $return["message"] = "Sale not completed";
                return;
            } else {
                $transaction_id = $paymentId;
                $dao_deposit = new DAODeposit();
                $deposit     = $dao_deposit->verify($transaction_id);
                if (!isset($deposit['id'])) {
                    $deposit = $dao_deposit->modDeposit($orderid, array('tradeid'=>$transaction_id, 'extends'=>$_REQUEST['data']), $transaction_id);
                }
                //通过transaction_id得到订单id
                $deposit = $dao_deposit->verify($transaction_id);
                if ($deposit['orderid'] == $orderid && $deposit['amount'] == (int)$amount_server && $deposit['currency'] == $currency_server) {
                    if (in_array($deposit['status'], array('P', 'N', 'C'))) { // | 2. 不成功, 如果成功则更新成成功或失败
                        Deposit::complete($orderid, $transaction_id, Deposit::DEPOSIT_PAYPAL);
                    }
                    $return = true;
                } else {
                    if ($_REQUEST['status'] == 'cancel') {
                        $dao_deposit->setDepositStatus($orderid, 'C', $reason = 'APP回报取消');
                    }
                    $return = false;
                }
            }
        } catch (Exception $exc) {
            $return["error"] = true;
            $return["message"] = "Unknown error occurred!" . $exc->getMessage();
        }
        return $return;
        
    }
    
    public function verify($orderid) 
    {
        $dao_deposit  = new DAODeposit();
        $deposit_info = $dao_deposit->getDepositInfo($orderid);
        if (in_array($deposit_info['status'], array('P', 'N'))) { //查询没有充成功
            $payment_client = json_decode($deposit_info['extends'], true);
            $paymentId = $payment_client['response']['id'];//客户端支付的id，paypal生成的


            $apiContext = getApiContext(self::PAYPAL_CLIENT_ID_TEST, self::PAYPAL_SECRET_TEST);
            // Gettin payment details by making call to paypal rest api
            $payment = \PayPal\Api\Payment::get($paymentId, $apiContext);//去paypal服务器查询

            // Verifying the state approved
            if ($payment->getState() != 'approved') {
                $return["error"] = true;
                $return["message"] = "支付未通过 " . $payment->getState();
                return;
            }

            // Paypal transactions
            $transaction = $payment->getTransactions()[0];
            // Amount on server side
            $amount_server = $transaction->getAmount()->getTotal();//paypal服务器给的支付金额
            // Currency on server side
            $currency_server = $transaction->getAmount()->getCurrency();//paypal服务器给的支付币种
            $sale_state = $transaction->getRelatedResources()[0]->getSale()->getState();

            if ($sale_state != 'completed') {
                $return["error"] = true;
                $return["message"] = "Sale not completed";
                return;
            } else {
                $transaction_id = $paymentId;
                $dao_deposit = new DAODeposit();
                $deposit     = $dao_deposit->verify($transaction_id);
                if ($deposit['orderid'] == $orderid && $deposit['amount'] == (int)$amount_server && $deposit['currency'] == $currency_server) {
                    if (in_array($deposit['status'], array('P', 'N'))) { // | 2. 不成功, 如果成功则更新成成功或失败
                        Deposit::complete($orderid, $transaction_id, Deposit::DEPOSIT_PAYPAL);
                    }
                    $return = true;
                } else {
                    $return = false;
                }
            }
        } else {
            $return = true;
        }

        return $return;
        
    }
}

function getApiContext($clientId, $clientSecret)
{

    // #### SDK configuration
    // Register the sdk_config.ini file in current directory
    // as the configuration source.
    /*
    if(!defined("PP_CONFIG_PATH")) {
        define("PP_CONFIG_PATH", __DIR__);
    }
    */


    // ### Api context
    // Use an ApiContext object to authenticate
    // API calls. The clientId and clientSecret for the
    // OAuthTokenCredential class can be retrieved from
    // developer.paypal.com

    $apiContext = new ApiContext(
        new OAuthTokenCredential(
            $clientId,
            $clientSecret
        )
    );

    // Comment this line out and uncomment the PP_CONFIG_PATH
    // 'define' block if you want to use static file
    // based configuration

    $apiContext->setConfig(
        array(
            'mode' => 'sandbox',
            'log.LogEnabled' => true,
            'log.FileName' => '/usr/local/nginx/logs/PayPal.log',
            'log.LogLevel' => 'DEBUG', // PLEASE USE `INFO` LEVEL FOR LOGGING IN LIVE ENVIRONMENTS
            'cache.enabled' => true,
            // 'http.CURLOPT_CONNECTTIMEOUT' => 30
            // 'http.headers.PayPal-Partner-Attribution-Id' => '123123123'
            //'log.AdapterFactory' => '\PayPal\Log\DefaultLogFactory' // Factory class implementing \PayPal\Log\PayPalLogFactory
        )
    );

    // Partner Attribution Id
    // Use this header if you are a PayPal partner. Specify a unique BN Code to receive revenue attribution.
    // To learn more or to request a BN Code, contact your Partner Manager or visit the PayPal Partner Portal
    // $apiContext->addRequestHeader('PayPal-Partner-Attribution-Id', '123123123');

    return $apiContext;
}
?>