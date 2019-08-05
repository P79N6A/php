<?php
class WechatGopayPay
{
    const GOPAY_NOTIFY_URL = "/deposit/notify_wechatGopay";
    const GOPAY_PUBLIC_KEY    = 'Dreamlive2017yjj';
    public static function prepare($uid, $source, $currency, $amount)
    {
        global $API_DOMAIN;
        $orderid = Account::getOrderId($uid);

        //|================================================================================================|
        //| 根据orderid查询第三方数据
        //| 1. 如果成功, 就完成操作.
        $post_data = array();
        $post_data['version']         = '2.1';
        $post_data['charset']        = 2;
        $post_data['language']           = '1';
        $post_data['signType']       = '1';
        $post_data['tranCode']         = 'SC01';
        $post_data['callType']        = 'WX_APP';
        $post_data['merchantID']        = '0000070677';
        $post_data['merOrderNum']        = $orderid;
        $post_data['tranAmt']        = $amount;
        $post_data['currencyType']        = '156';
        $post_data['backgroundMerUrl'] = $API_DOMAIN . self::GOPAY_NOTIFY_URL;
        $post_data['tranDateTime']     = date("YmdHis", time());
        $post_data['gopayServerTime']  = date("YmdHis", time());
        $post_data['virCardNoIn']      = '0000000002000009937';
        $post_data['tranIP']        = '127.0.0.1';
        $post_data['goodsName']       = '追梦直播充值' . $amount;
        $post_data['goodsDetail']       = "追梦直播充值$uid:" . $amount;
        $post_data['isRepeatSubmit']       = 1;
        $post_data['merRemark']       = '';
        $post_data['goodsTag']       = '';
        $post_data['productId']       = '';
        $post_data['deviceInfo']       = '';

        $post_data['appId']       = 'wxfc014246ac3d2bca';


        $arr = array();
        $arr[] = 'version=[' . $post_data['version'] . ']';
        $arr[] = 'tranCode=[' . $post_data['tranCode'] . ']';
        $arr[] = 'merchantID=[' . $post_data['merchantID'] . ']';
        $arr[] = 'merOrderNum=[' . $post_data['merOrderNum'] . ']';
        $arr[] = 'tranAmt=[' . $post_data['tranAmt'] . ']';
        $arr[] = 'tranDateTime=[' . $post_data['tranDateTime'] . ']';
        $arr[] = 'backgroundMerUrl=[' . $post_data['backgroundMerUrl'] . ']';
        $arr[] = 'gopayServerTime=[' . $post_data['gopayServerTime'] . ']';
        $arr[] = 'tranIP=[' . $post_data['tranIP'] . ']';
        $arr[] = 'callType=[' . $post_data['callType'] . ']';
        $arr[] = 'goodsTag=[' . $post_data['goodsTag'] . ']';
        $arr[] = 'productId=[' . $post_data['productId'] . ']';
        $arr[] = 'VerficationCode=[' . self::GOPAY_PUBLIC_KEY . ']';
        $signValue = md5(implode("", $arr));
        $post_data['signValue'] = $signValue;



        $info = self::getReceiptData($post_data, 'https://gateway.gopay.com.cn/Trans/APIClientAction.do');


        $xml = simplexml_load_string($info);
        $data = json_decode(json_encode($xml), true);
        

        $arr = array();
        $arr[] = 'version=[' . $data['version'] . ']';
        $arr[] = 'tranCode=[' . $data['tranCode'] . ']';
        $arr[] = 'merchantID=[' . $data['merchantID'] . ']';
        $arr[] = 'merOrderNum=[' . $data['merOrderNum'] . ']';
        $arr[] = 'tranAmt=[' . $data['tranAmt'] . ']';
        $arr[] = 'tranDateTime=[' . $data['tranDateTime'] . ']';
        $arr[] = 'respCode=[' . $data['respCode'] . ']';
        $arr[] = 'gopayOrderId=[' . $data['gopayOrderId'] . ']';
        $arr[] = 'expireTime=[]';
        $arr[] = 'callType=[' . $data['callType'] . ']';
        $arr[] = 'result=[]';
        $arr[] = 'VerficationCode=[' . self::GOPAY_PUBLIC_KEY . ']';

        //version=[]tranCode=[]merchantID=[]merOrderNum=[]tranAmt=[]tranDateTime=[]respCode=[]gopayOutOrderId=[]expireTime=[]callType=[]result=[]VerficationCode=[]
        //version=[2.1]tranCode=[SC01]merchantID=[0000001502]merOrderNum=[401171115907096576]tranAmt=[1.00]tranDateTime=[20171211175055]respCode=[0000]gopayOutOrderId=[2017121103746042]expireTime=[20171211175141]callType=[WX_APP]result=[19c4bf0cc24e13717151900d53810f702]VerficationCode=[11111aaaaa]
        $signValue = md5(implode("", $arr));
        if($signValue == $data['signValue']) {
            $dao_deposit = new DAODeposit();
            
            $dao_deposit->prepare($uid, $orderid, $source, $currency, $amount, '');
            $return = array();
            $return['appid']     = $post_data['appId'];
            $return['signValue'] = $data['signValue'];
            $return['orderid']   = $orderid;
            $return['tokenid']   = $data['result'];
        } else {
            echo "error signValue";
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
        if (in_array($deposit_info['status'], array('P', 'N', 'C'))  && $deposit_info['source'] == Deposit::DEPOSIT_BANK_GOPAY) { //查询没有充成功
            //|================================================================================================|
            //| 根据orderid查询第三方数据
            //| 1. 如果成功, 就完成操作.
            $post_data = array();
            $post_data['Version']         = '1.1';
            $post_data['TranCode']        = 'BQ01';
            $post_data['MerId']           = '0000001502';
            $post_data['MerAcctId']       = '0000000002000000273';
            $post_data['Charset']         = 2;
            $post_data['SignType']        = 1;
            $post_data['GopayTxnTmStart'] = date("YmdHis", time()-100*24*1*3600);
            $post_data['GopayTxnTmEnd']   = date("YmdHis", time()+24*1*3600);
            $post_data['PageNum']         = 1;
            $post_data['QryTranCode']     = '11';
            $post_data['QryMerOrderId']   = '401109015855767552';
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
            $info = self::getReceiptData($post_data, 'https://gatewaymer.gopay.com.cn/Trans/WebClientAction.do');
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
                    return '处理中';
                } else if($data['BizInf']['TxnSet']['TxnInf']['BizStsCd'] == 'F') {
                    return '失败';
                }
            } else {
                echo "error signValue";
            }
        } else {
            if($deposit_info['status'] == 'Y') {
                return true;
            } else {
                return false;
            }
        }
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