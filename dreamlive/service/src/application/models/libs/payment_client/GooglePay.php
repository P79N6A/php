<?php
class GooglePay
{
    
    public function prepare($uid, $source, $currency, $amount)
    {
        global $API_DOMAIN;
        /*
        安卓端：
        原星钻不变    原人民币    人民币修改成美元
        60    6    0.99
        300    30    4.99
        980    98    14.99 
        2980    298    49.99
        5880    588    99.99
        15980    1598    249.99      Diamonds
        */
        $product = array('0.99'=>array('6', 'product01', 'com.dreamer.tv01', "60"),
                        '4.99'=>array('30', 'product02', 'com.dreamer.tv02', "300"),
                        '14.99'=>array('98', 'product03', 'com.dreamer.tv03', "980"),
                        '49.99'=>array('298', 'product04', 'com.dreamer.tv04', "2980"),
                        '99.99'=>array('588', 'product05', 'com.dreamer.tv05', "5880"),
                        '249.99'=>array('1598', 'product06', 'com.dreamer.tv06', "15980"),
                );

        $orderid = Account::getOrderId($uid);
        $dao_deposit = new DAODeposit();
        $result = $dao_deposit->prepare($uid, $orderid, $source, $currency, $amount, 0);

        return array(
            "product_id"   => $product[$amount][2],
            "product_name" => $product[$amount][0],
            "product_photo" => $product[$amount][4],
            "money_rmb" => $product[$amount][0],
            "money_dollar" => $amount,
            "start_diamond" => $product[$amount][3],
            "orderid" => $orderid,
            "notify_url" => $API_DOMAIN . "/deposit/notify_google",
            "payload"=>self::getNonceStr());
    }
    
    
    
    public function notify()
    {
        $signture_data = $_REQUEST['signature_data'];
        $signature      = str_replace(' ', '+', $_REQUEST['signature']);
        /*$str='{"signature":"GpR17nxFZXQvoQt\/9e2Wyg6j7LWawkiZgpxcsfEqvQCAIrXp2X6vKVEtx32GtClz1oLYbF OzkqNWWRPggUWVKBU\/6lWTy79pKkjrXHKUVP8wCRLqx1KWTC1U 9SW14IhAi9wZ1gOwNKQFcEyizvc06dqQ71D bCHZQYk Fw2DWudu9fLpZxYC925WDigZKXYzMCPNYEouSYCAwv98GRL4i075c45gt4at il6FWcTUZfKU IulV8R3hF2quENmq1np2sziUY1sSbWzkAsEWkKHomFblDYcy2ULc04AiTsNsAwG0NkC0SZEYhMwtKBhvJWOBVi\/0GfhToNQ6s22 Og==","signature_data":"{\"packageName\":\"com.dreamer.tv\",\"productId\":\"com.dreamer.tv01\",\"purchaseTime\":1493880703637,\"purchaseState\":0,\"developerPayload\":\"321038208303378432\",\"purchaseToken\":\"hflelgphemdhfnkaeinlcphd.AO-J1OykP1j0b-wefI9Kc7QBFRojFLe1Sf-4ZtLnMHXoLalkwjWjlSJ9hVoIWrthzyUi7NF5Yosvip1gLqLbQ4QcHxhOLEDrwHAPne7j1s4Mn1Jree9pWVzk-7Bev3ipqummVNPpOwof\"}","time":"1493880708219","netspeed":"0","guid":"ee43d9aa763f07f28e58668ba54c84cd","region":"china","deviceid":"cfc498cf7612046e739fd8894b400c81","lng":"116.452592","userid":"10000025","network":"wifi","platform":"android","brand":"vivo","version":"2.2.3","rand":"1997","lat":"39.932543","channel":"default_channel","model":"vivo X9"}';
        $dd=json_decode($str,true);
        $signture_data=$dd['signature_data'];

        $signature=str_replace(' ','+' , $dd['signature']);*/
        //var_dump($signature);
        
        //google app 提供的public key
        $public_key = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAs7WvrFJH5OJ+M2Mli9NWSFgMussvF5lvErQYbx2UWaSOP4IeDP/uxjH+R/N30HNRUzSYgTccNoFawpLzlGAyJ/8oXC7xmIWkCuLnlScgGMgY4cK1I4Dyp8KuD3R1JqKV0SQ7n6EdZMlDoniwKuW+B8rmhaIyCPHSLe3zcrL0+J3ZXso2nnuJqw0yAhlV1v3sjvb5c6gilwFsdwATQgR2EmwMOIR7M+KyBjijTqd0jUdAk3bChldisCu8J9y781N31x3rqxaoHVCmj0pFdZziOKEgyMhqB39SSlr0AoF3FC78qxy5e8MuLC2Wd0lcfmT5okYT8F8IzA3Q7QR/oT0MLwIDAQAB";

        $public_key = "-----BEGIN PUBLIC KEY-----\n" . chunk_split($public_key, 64, "\n") . "-----END PUBLIC KEY-----";

        $public_key_handle = openssl_get_publickey($public_key);
        $google_result = openssl_verify($signture_data, base64_decode($signature), $public_key_handle, OPENSSL_ALGO_SHA1);


        if ($google_result == 1) {
            $google_notify = json_decode($signture_data, true);

            $orderid = $google_notify['developerPayload'];

            //存第三方数据用作验证用
            $transaction_id = $google_notify['orderId'];

            $dao_deposit = new DAODeposit();
            $deposit     = $dao_deposit->verify($transaction_id);
            if (!isset($deposit['id'])) {
                $deposit = $dao_deposit->modDeposit($orderid, array('tradeid'=>$transaction_id, 'extends'=>json_encode(array('signture_data'=>$signture_data, 'signature'=>$signature))), $transaction_id);
            }
            
            $deposit_info = $dao_deposit->getDepositInfo($orderid);

            // |===========================================================================================
            // | 处理流程
            // | 1. 检测交易是否成功， 成功不做任何处理
            // | 2. 检测交易不成功, 2.1 如果成功则更新成成功
            // | 2.2. 增加用户的票
            // | 2.3. 增加一条交易.
            // | 3. 如果交易不成功, 则更新成不成功.
            if (in_array($deposit_info['status'], array('P', 'N', 'C'))) { // | 2. 不成功, 如果成功则更新成成功或失败
                if ($google_result == 1) { //更新成成功
                    Deposit::complete($orderid, $signture_data['orderId'], Deposit::DESOSIT_GOOGLE);

                    $return = true;
                } elseif ($google_result == 0) { //更新成失败
                    $dao_deposit = new DAODeposit();
                    $dao_deposit->setDepositStatus($orderid, 'N', $reason = '签名验证失败'); //$wx_notify

                    $return = true;
                } else {
                    $return = "ugly, error checking signature";
                }
            } else {
                //1. 检测交易是否成功， 成功不做任何处理
                $return = true;
            }
        }

        return $return;
    
    }


    public function verify($orderid)
    {
        $dao_deposit  = new DAODeposit();
        $deposit_info = $dao_deposit->getDepositInfo($orderid);
        if (in_array($deposit_info['status'], array('P', 'N', 'C'))) { //查询没有充成功
            $dao_deposit_log  = new DAODepositLog();
            $deposit_log_info = $dao_deposit_log->getDepositLogInfo($orderid);
            if($deposit_log_info['content']) {
                $tmp = explode("**", $deposit_log_info['content']);
                if($tmp[1]) {
                    $deposit_log_json = json_decode($tmp[1], true);
                    $signture_data    = $deposit_log_json['signature_data'];
                    $signature        = str_replace(' ', '+', $deposit_log_json['signature']);
                    $public_key = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAs7WvrFJH5OJ+M2Mli9NWSFgMussvF5lvErQYbx2UWaSOP4IeDP/uxjH+R/N30HNRUzSYgTccNoFawpLzlGAyJ/8oXC7xmIWkCuLnlScgGMgY4cK1I4Dyp8KuD3R1JqKV0SQ7n6EdZMlDoniwKuW+B8rmhaIyCPHSLe3zcrL0+J3ZXso2nnuJqw0yAhlV1v3sjvb5c6gilwFsdwATQgR2EmwMOIR7M+KyBjijTqd0jUdAk3bChldisCu8J9y781N31x3rqxaoHVCmj0pFdZziOKEgyMhqB39SSlr0AoF3FC78qxy5e8MuLC2Wd0lcfmT5okYT8F8IzA3Q7QR/oT0MLwIDAQAB";

                    $public_key = "-----BEGIN PUBLIC KEY-----\n" . chunk_split($public_key, 64, "\n") . "-----END PUBLIC KEY-----";

                    $public_key_handle = openssl_get_publickey($public_key);
                    $google_result = openssl_verify($signture_data, base64_decode($signature), $public_key_handle, OPENSSL_ALGO_SHA1);
                    
                    if ($google_result == 1) {
                           $google_notify = json_decode($signture_data, true);

                           $orderid = $google_notify['developerPayload'];
                        
                           //存第三方数据用作验证用
                           $transaction_id = $google_notify['orderId'];

                           $dao_deposit = new DAODeposit();
                           $deposit     = $dao_deposit->verify($transaction_id);
                        if (!isset($deposit['id'])) {
                            $deposit = $dao_deposit->modDeposit($orderid, array('tradeid'=>$transaction_id), $transaction_id);
                        }
                        
                           $deposit_info = $dao_deposit->getDepositInfo($orderid);

                           // |===========================================================================================
                           // | 处理流程
                           // | 1. 检测交易是否成功， 成功不做任何处理
                           // | 2. 检测交易不成功, 2.1 如果成功则更新成成功
                           // | 2.2. 增加用户的票
                           // | 2.3. 增加一条交易.
                           // | 3. 如果交易不成功, 则更新成不成功.
                        if (in_array($deposit_info['status'], array('P', 'N', 'C'))) { // | 2. 不成功, 如果成功则更新成成功或失败
                            if ($google_result == 1) { //更新成成功
                                Deposit::complete($orderid, $transaction_id, Deposit::DESOSIT_GOOGLE);
                                $return = true;
                            } elseif ($google_result == 0) { //更新成失败
                                $dao_deposit = new DAODeposit();
                                $dao_deposit->setDepositStatus($orderid, 'N', $reason = '签名验证失败'); //$wx_notify

                                $return = true;
                            } else {
                                $return = "ugly, error checking signature";
                            }
                        } else {
                            //1. 检测交易是否成功， 成功不做任何处理
                            $return = true;
                        }
                    }
                } else {
                    $return = false;
                }
            } else {
                $return = false;
            }
        } else {
            $return = true;
        }
        Interceptor::ensureFalse($return!=true, ERROR_BIZ_PAYMENT_DEPOSIT_FALSE);

        return $return;
    
    }
    /**
     * 产生随机字符串，不长于32位
     *
     * @param  int $length
     * @return 产生的随机字符串
     */
    public static function getNonceStr($length = 32) 
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
        $str ="";
        for ( $i = 0; $i < $length; $i++ )  {  
            $str .= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
        } 
        return $str;
    }
}

?>