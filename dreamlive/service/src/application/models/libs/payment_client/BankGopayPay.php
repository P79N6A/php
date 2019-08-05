<?php
class BankGopayPay
{
    const GOPAY_SERVICE = "https://gateway.gopay.com.cn/Trans/MobileClientAction.do";
    const GOPAY_NOTIFY_URL = "/deposit/notify_bankGopay";
    const GOPAY_RETURN_URL = "/pay_complete.php";
    const GOPAY_PUBLIC_KEY    = 'Dreamlive2017yjj';

    public function __contrust()
    {
        
    }
    
    public static function prepare($uid, $source, $currency, $amount)
    {
        global $API_DOMAIN;
        global $HTML5_DOMAIN;
        
        $orderid = Account::getOrderId($uid);
        
        if ($_REQUEST['type'] == 'js') {
            $version  = '2.2';
            $tranCode = '8888';
            $merchantID = '0000070677';


            $merOrderNum = $orderid;
            $tranAmt     = $amount;
            $feeAmt      = '0.00';
            $tranDateTime = date("YmdHis", time());
            $frontMerUrl  = $HTML5_DOMAIN . self::GOPAY_RETURN_URL;
            $backgroundMerUrl = $API_DOMAIN . self::GOPAY_NOTIFY_URL;
            $orderId = '';
            $gopayOutOrderId = '';
            $tranIP = '127.0.0.1';
            $respCode = '';
            $gopayServerTime = '';
            $VerficationCode = self::GOPAY_PUBLIC_KEY;
            $web             = self::GOPAY_SERVICE;

            $arr = array();
            $arr[] = 'version=[' . $version . ']';
            $arr[] = 'tranCode=[' . $tranCode . ']';
            $arr[] = 'merchantID=[' . $merchantID . ']';
            $arr[] = 'merOrderNum=[' . $merOrderNum . ']';
            $arr[] = 'tranAmt=[' . $tranAmt . ']';
            $arr[] = 'feeAmt=[' . $feeAmt . ']';
            $arr[] = 'tranDateTime=[' . $tranDateTime . ']';
            $arr[] = 'frontMerUrl=[' . $frontMerUrl . ']';
            $arr[] = 'backgroundMerUrl=[' . $backgroundMerUrl . ']';
            $arr[] = 'orderId=[' . $orderId . ']';
            $arr[] = 'gopayOutOrderId=[' . $gopayOutOrderId . ']';
            $arr[] = 'tranIP=[' . $tranIP . ']';
            $arr[] = 'respCode=[' . $respCode . ']';
            $arr[] = 'gopayServerTime=[' . $gopayServerTime . ']';
            $arr[] = 'VerficationCode=[' . $VerficationCode . ']';


            $signValue = md5(implode("", $arr));

            $form = "<form action='$web' method='POST' id='bankgopaysubmit'><input type='hidden' name='version' size='100'  value='$version'><input type='hidden' name='charset' value='2'><input type='hidden' name='language' value='1'><input type='hidden' name='signType' value='1'><input type='hidden' name='tranCode' value='$tranCode'><input type='hidden' name='merchantID' value='$merchantID'><input type='hidden' name='merOrderNum' value='$merOrderNum'><input type='hidden' name='tranAmt' value='$tranAmt'><input type='hidden' name='feeAmt'  value='0.00'><input type='hidden' name='currencyType'  value='156'><input type='hidden' name='frontMerUrl'  value='$frontMerUrl'><input type='hidden' name='backgroundMerUrl'  value='$backgroundMerUrl'><input type='hidden' name='tranDateTime' value='$tranDateTime'><input type='hidden' name='virCardNoIn' size='100'  value='0000000002000009937'><input type='hidden' name='tranIP' value='$tranIP'><input type='hidden' name='isRepeatSubmit' value='1'><input type='hidden' name='goodsName' value=''><input type='hidden' name='goodsDetail' value=''><input type='hidden' name='buyerName' value='MWEB'><input type='hidden' name='buyerContact' value=''><input type='hidden' name='bankCode' value=''><input type='hidden' name='userType' value=''><input type='hidden' name='merRemark1' value='merRemark1'><input type='hidden' name='merRemark2' value='merRemark1'><input type='hidden' name='signValue' value='$signValue'></form><script>document.getElementById('bankgopaysubmit').submit();</script>";

            $dao_deposit = new DAODeposit();
            
            $dao_deposit->prepare($uid, $merOrderNum, $source, $currency, $amount, '');
            
            
            $return = array('form'=>$form);
            
        }
        
        return $return;
    }
    
    public function verify($orderid)
    {
        $dao_deposit  = new DAODeposit();
        $deposit_info = $dao_deposit->getDepositInfo($orderid);
        if (in_array($deposit_info['status'], array('P', 'N', 'C'))  && $deposit_info['source'] == Deposit::DEPOSIT_BANK_GOPAY) { //查询没有充成功
            //|================================================================================================|
            //| 根据orderid查询第三方数据
            //| 1. 如果成功, 就完成操作.
            $post_data = array();
            $post_data['Version']         = '1.1';
            $post_data['TranCode']        = 'BQ01';
            $post_data['MerId']           = '0000070677';
            $post_data['MerAcctId']       = '0000000002000009937';
            $post_data['Charset']         = 2;
            $post_data['SignType']        = 1;
            $post_data['GopayTxnTmStart'] = date("YmdHis", time()-100*24*1*3600);
            $post_data['GopayTxnTmEnd']   = date("YmdHis", time()+24*1*3600);
            $post_data['PageNum']         = 1;
            $post_data['QryTranCode']     = '11';
            $post_data['QryMerOrderId']   = $orderid;
            $post_data['QryGopayOrderId']   = '';
            $post_data['TxnStat']         = 'A';
            $post_data['BatchNum']        = '';

            $arr = array();
            $arr[] = 'Version=[' . $post_data['Version'] . ']';
            $arr[] = 'TranCode=[' . $post_data['TranCode'] . ']';
            $arr[] = 'MerId=[' . $post_data['MerId'] . ']';
            $arr[] = 'MerAcctId=[' . $post_data['MerAcctId'] . ']';
            $arr[] = 'QryGopayOrderId=[' . $post_data['QryGopayOrderId'] . ']';
            $arr[] = 'QryTranCode=[' . $post_data['QryTranCode'] . ']';
            $arr[] = 'GopayTxnTmStart=[' . $post_data['GopayTxnTmStart'] . ']';
            $arr[] = 'GopayTxnTmEnd=[' . $post_data['GopayTxnTmEnd'] . ']';
            $arr[] = 'PageNum=[' . $post_data['PageNum'] . ']';
            $arr[] = 'VerficationCode=[' . self::GOPAY_PUBLIC_KEY . ']';
            $signValue = md5(implode("", $arr));
            $post_data['SignValue'] = $signValue;
            $info = self::getReceiptData($post_data, 'https://gateway.gopay.com.cn/Trans/WebClientAction.do');
            $xml = simplexml_load_string($info);
            $data = json_decode(json_encode($xml), true);


            $arr = array();
            $arr[] = 'SysRtnCd=[' . $data['SysRtnInf']['SysRtnCd'] . ']';
            $arr[] = 'SysRtnTm=[' . $data['SysRtnInf']['SysRtnTm'] . ']';
            $arr[] = 'MerId=[' . $data['ReqInf']['MerId'] . ']';
            $arr[] = 'MerAcctId=[' . $data['ReqInf']['MerAcctId'] . ']';
            $arr[] = 'QryGopayOrderId=[' . $data['ReqInf']['QryGopayOrderId'][0] . ']';
            $arr[] = 'QryTranCode=[' . $data['ReqInf']['QryTranCode'] . ']';
            $arr[] = 'GopayTxnTmStart=[' . $data['ReqInf']['GopayTxnTmStart'] . ']';
            $arr[] = 'GopayTxnTmEnd=[' . $data['ReqInf']['GopayTxnTmEnd'] . ']';
            $arr[] = 'PageNum=[' . $data['ReqInf']['PageNum'] . ']';

            $arr[] = 'VerficationCode=[' . self::GOPAY_PUBLIC_KEY . ']';
            $signValue = md5(implode("", $arr));
            if($signValue == $data['SgnInf']['SignValue']) {
                if($data['BizInf']['TxnSet']['TxnInf']['BizStsCd'] == 'S') {
                    Deposit::complete($orderid, $data['BizInf']['TxnSet']['TxnInf']['GopayOrderId'], Deposit::DEPOSIT_BANK_GOPAY);
                    return true;
                } else if($data['BizInf']['TxnSet']['TxnInf']['BizStsCd'] == 'P') {
                    Interceptor::ensureNotFalse(false, ERROR_BIZ_PAYMENT_DEPOSIT_HANDLING);
                } else if($data['BizInf']['TxnSet']['TxnInf']['BizStsCd'] == 'F') {
                    $dao_deposit = new DAODeposit();
                    $dao_deposit->setDepositStatus($orderid, 'N', $reason = '国付宝回报失败');
                    return false;
                }
            } else {
                return false;
                //echo "error signValue";
            }
        } else {
            if($deposit_info['status'] == 'Y') {
                return true;
            } else {
                return false;
            }
        }
    }
    
    
    
    public static function notify() 
    {
        //$_REQUEST = json_decode('{"feeAmt":"0.05","respCode":"0000","signType":"1","buyerRealName":"","buyerContact":"","version":"2.2","merOrderNum":"1512532487360177","tranFinishTime":"20171206115549","goodsDetail":"--","msgExt":"","orderId":"2017120603745555","buyerRealMobile":"","tranAmt":"10","merchantID":"0000001502","charset":"2","buyerRealBankAcctNum":"","tranIP":"127.0.0.1","signValue":"2c86c00c981afe648bf7c061469ef87b","buyerName":"MWEB","tranDateTime":"20081107094626","bankCode":"TBANK","authID":"","merRemark2":"merRemark1","tranCode":"8888","gopayOutOrderId":"2017120603745555","backgroundMerUrl":"http:\/\/html5.dreamlive.com\/app_h5\/testyjj.php","merRemark1":"merRemark1","frontMerUrl":"http:\/\/html5.dreamlive.com\/pay_complete.php","language":"1","buyerRealCertNo":"","goodsName":""}', true);

        $signValue = $_REQUEST["signValue"];
        if(is_array($_REQUEST)) {
            foreach ($_REQUEST as $key=>$value) {
                $$key = $value;
            }
        }

        //注意md5加密串需要重新拼装加密后，与获取到的密文串进行验签
        $signValue2='version=['.$version.']tranCode=['.$tranCode.']merchantID=['.$merchantID.']merOrderNum=['.$merOrderNum.']tranAmt=['.$tranAmt.']feeAmt=['.$feeAmt.']tranDateTime=['.$tranDateTime.']frontMerUrl=['.$frontMerUrl.']backgroundMerUrl=['.$backgroundMerUrl.']orderId=['.$orderId.']gopayOutOrderId=['.$gopayOutOrderId.']tranIP=['.$tranIP.']respCode=['.$respCode.']gopayServerTime=[]VerficationCode=[' . self::GOPAY_PUBLIC_KEY . ']';
        //VerficationCode是商户识别码为用户重要信息请妥善保存
        //注意调试生产环境时需要修改这个值为生产参数



        $signValue2 = md5($signValue2);

        if($signValue==$signValue2) {
            if($respCode=='0000') {
                $out_trade_no = $merOrderNum;
                $dao_deposit  = new DAODeposit();
                $deposit_info = $dao_deposit->getDepositInfo($out_trade_no);



                if(in_array($deposit_info['status'], array('P', 'N', 'C')) && $deposit_info['source'] == Deposit::DEPOSIT_BANK_GOPAY) {
                    Deposit::complete($out_trade_no, $orderId, Deposit::DEPOSIT_BANK_GOPAY);
                    echo "success";
                } else {
                    echo "fail";
                }
            } elseif($respCode=='9999') {
                echo "ing...";
            } else {
                echo "no...";
            }
        } else {
            echo "sign fail";//验签
        }
        die();
    }
    static public function getReceiptData($receipt,$endpoint)
    {
        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $receipt);
        $response = curl_exec($ch);
        $errno = curl_errno($ch);
        $errmsg = curl_error($ch);
        curl_close($ch);
        $msg = $response.' - '.$errno.' - '.$errmsg;
        return $response;
    }

}
?>