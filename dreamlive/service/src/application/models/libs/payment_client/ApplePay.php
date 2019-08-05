<?php
class ApplePay
{
    public function prepare($uid, $source, $currency, $amount)
    {
        global $API_DOMAIN;
        $orderid = Account::getOrderId($uid);
        $dao_deposit = new DAODeposit();
        $result = $dao_deposit->prepare($uid, $orderid, $source, $currency, $amount, 0);
        return array(
            "orderid" => $orderid,
            "notify_url" => $API_DOMAIN . "/deposit/notify_apple",
        );
    }
    
    public function verify($orderid)
    {
        $product = array('com.dreamer.tv01'=>array('6', 'product01'),
                        'com.dreamer.tv02'=>array('30', 'product02'),
                        'com.dreamer.tv03'=>array('98', 'product03'),
                        'com.dreamer.tv04'=>array('298', 'product04'),
                        'com.dreamer.tv05'=>array('588', 'product05'),
                        'com.dreamer.tv06'=>array('1598', 'product06'),
         'com.dreamer.young01'=>array('6', 'product01'),
                        'com.dreamer.young02'=>array('30', 'product02'),
                        'com.dreamer.young03'=>array('98', 'product03'),
                        'com.dreamer.young04'=>array('298', 'product04'),
                        'com.dreamer.young05'=>array('588', 'product05'),
                        'com.dreamer.young06'=>array('1598', 'product06'),
                );
        $dao_deposit  = new DAODeposit();
        $deposit_info = $dao_deposit->getDepositInfo($orderid);
        if (in_array($deposit_info['status'], array('P', 'N', 'C'))) { //查询没有充成功
            $post_data    = $deposit_info['extends'];
            $info         = self::getReceiptData($post_data, 'https://buy.itunes.apple.com/verifyReceipt');
            $receipt_data = json_decode($info, true);
            if ($receipt_data['status'] == 0) {
                foreach ($receipt_data['receipt']['in_app'] as $key => $value) {
                    $transaction_id = $value['transaction_id'];

                    $dao_deposit = new DAODeposit();
                    $deposit     = $dao_deposit->verify($transaction_id);
                    if (!isset($deposit['id'])) {
                        $deposit = $dao_deposit->modDeposit($orderid, array('tradeid'=>$transaction_id, 'extends'=>$post_data), $transaction_id);
                    }

                    //通过transaction_id得到订单id
                    $deposit = $dao_deposit->verify($transaction_id);

                    if ($deposit['orderid'] == $orderid && $deposit['amount'] == $product[$value['product_id']][0]) {
                        if (in_array($deposit['status'], array('P', 'N', 'C'))) { // | 2. 不成功, 如果成功则更新成成功或失败
                            Deposit::complete($orderid, $transaction_id, Deposit::DEPOSIT_APPLE);
                        }
                        $return = true;
                    } else {
                        $dao_deposit = new DAODeposit();
                        $dao_deposit->setDepositStatus($orderid, 'N', $reason = '系统回报失败');
                        $return = false;
                    }
                }
            } else {
                $return = $receipt_data;
            }
        } else {
            $return = true;
        }

        return $return;
    }
    
    public function notify()
    {
        // |===========================================================================================
        // | ios通知逻辑：
        // | 1. 验证票据。 接收到第三方id
        // | 2. 查看票据是否存在
        // | 2.1  如果不存在, 保存票据, 保持票据与订单的映射, 第三方订单的唯一性
        // | 2.2. 保存成功. 提取数据, 解提取的订单数据与票据进行数据验证， 看是否是充的对应值. 
        // | 2.3. 如果成功， 就进行完成操作
        $receipt_data = json_decode($_REQUEST['receipt-data'], true);
        $post_data = json_encode(array("receipt-data"=>$receipt_data['receipt-data']));
        $orderid   = $receipt_data['orderid'];
        if (!$orderid) {
            if ($_REQUEST['status'] == 'cancel') {
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

        $product = array('com.dreamer.tv01'=>array('6', 'product01'),
                        'com.dreamer.tv02'=>array('30', 'product02'),
                        'com.dreamer.tv03'=>array('98', 'product03'),
                        'com.dreamer.tv04'=>array('298', 'product04'),
                        'com.dreamer.tv05'=>array('588', 'product05'),
                        'com.dreamer.tv06'=>array('1598', 'product06'),
         'com.dreamer.young01'=>array('6', 'product01'),
                        'com.dreamer.young02'=>array('30', 'product02'),
                        'com.dreamer.young03'=>array('98', 'product03'),
                        'com.dreamer.young04'=>array('298', 'product04'),
                        'com.dreamer.young05'=>array('588', 'product05'),
                        'com.dreamer.young06'=>array('1598', 'product06'),
                );

        $info         = self::getReceiptData($post_data, 'https://buy.itunes.apple.com/verifyReceipt');
        $receipt_data = json_decode($info, true);
        if ($receipt_data['status'] == 0) {
            foreach ($receipt_data['receipt']['in_app'] as $key => $value) {
                $transaction_id = $value['transaction_id'];

                $dao_deposit = new DAODeposit();
                $deposit     = $dao_deposit->verify($transaction_id);
                if (!isset($deposit['id'])) {
                    $deposit = $dao_deposit->modDeposit($orderid, array('tradeid'=>$transaction_id, 'extends'=>$post_data), $transaction_id);
                }

                //通过transaction_id得到订单id
                $deposit = $dao_deposit->verify($transaction_id);

                if ($deposit['orderid'] == $orderid && $deposit['amount'] == $product[$value['product_id']][0]) {
                    if (in_array($deposit['status'], array('P', 'N', 'C'))) { // | 2. 不成功, 如果成功则更新成成功或失败
                        Deposit::complete($orderid, $transaction_id, Deposit::DEPOSIT_APPLE);
                    }
                    $return = true;
                } else {
                    $dao_deposit = new DAODeposit();
                    $dao_deposit->setDepositStatus($orderid, 'N', $reason = '系统回报失败');
                    $return = false;
                }
            }
        } else {
            $return = $receipt_data;
        }
        return $return;
    }
    
    static public function getReceiptData($receipt,$endpoint)
    {
        //$endpoint = "https://buy.itunes.apple.com/verifyReceipt";//正式
        //$endpoint = 'https://sandbox.itunes.apple.com/verifyReceipt';//测试    
        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $receipt);
        $response = curl_exec($ch);
        $errno = curl_errno($ch);
        $errmsg = curl_error($ch);
        curl_close($ch);
        $msg = $response.' - '.$errno.' - '.$errmsg;
        if ($response == '{"status":21007}') {
            return self::getReceiptData($receipt, 'https://sandbox.itunes.apple.com/verifyReceipt');
        }
        return $response;
    }
}

?>