<?php
class RecoveryController extends BaseController
{
    public function punishmentAction()
    {
        /*{{{回收钻 出票到公司帐户钻*/
        $num = $this->getParam('amount');
        $uid = $this->getParam('uid');
        $remark = $this->getParam('remark');
        $operater    = $this->getParam('adminid');
        $key = $this->getParam('key');

        Interceptor::ensureNotEmpty($num, ERROR_PARAM_IS_EMPTY, 'num');
        Interceptor::ensureFalse($key != 'yjjertert3sdf45krtwerwertwert', ERROR_PARAM_INVALID_FORMAT, "key");
        Interceptor::ensureNotEmpty($operater, ERROR_PARAM_IS_EMPTY, 'operater');
        Interceptor::ensureNotEmpty($uid, ERROR_PARAM_IS_EMPTY, 'uid');
        Interceptor::ensureNotFalse($uid > 10000, ERROR_PARAM_INVALID_FORMAT, "uid");
        Interceptor::ensureNotEmpty($num>0, ERROR_PARAM_IS_EMPTY, 'num');
        Interceptor::ensureNotEmpty($remark, ERROR_PARAM_IS_EMPTY, 'remark');

        $user = new User();
        $userinfo = $user->getUserInfo($uid);
        Interceptor::ensureNotFalse(isset($userinfo['uid']), ERROR_USER_NOT_EXIST);


        $account = new Account();
        $dao_gift_log = new DAOGiftLog();
        try {
            $dao_gift_log->startTrans();

            $ticket = $account->getBalance($uid, ACCOUNT::CURRENCY_TICKET);
            Interceptor::ensureNotEmpty($ticket, ERROR_BIZ_PAYMENT_TICKET_BALANCE_DUE);
            Interceptor::ensureNotFalse($ticket>=$num, ERROR_BIZ_PAYMENT_TICKET_BALANCE_DUE);


            $orderid = Account::getOrderId($uid);
            Interceptor::ensureNotFalse($account->decrease($uid, ACCOUNT::TRADE_TYPE_RECOVERY, $orderid, $num, ACCOUNT::CURRENCY_TICKET, "扣票(被回收人:$uid.原因:$remark.回收数量:$num.回收人:$operater)", array()),  ERROR_BIZ_PAYMENT_TICKET_BALANCE_DUE);
            Interceptor::ensureNotFalse($account->increase(ACCOUNT::RECOVERY_ACCOUNT, ACCOUNT::TRADE_TYPE_RECOVERY, $orderid, $num, ACCOUNT::CURRENCY_TICKET, "扣票(被回收人:$uid.原因:$remark.回收数量:$num.回收人:$operater)", array()), ERROR_BIZ_PAYMENT_TICKET_BALANCE_DUE);

            $dao_gift_log->commit();
        } catch (Exception $e) {
            $dao_gift_log->rollback();
            throw $e;
        }

        Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $uid, "扣票:$num.(原因:$remark.)", "扣票:$num.(原因:$remark.)", 0);

        $this->render();
    }/*}}}*/

    public function recoveryAction()
    {
        /*{{{回收钻 出票到公司帐户钻*/
        $num = $this->getParam('amount');
        $uid = $this->getParam('uid');
        $remark = $this->getParam('remark');
        $operater    = $this->getParam('adminid');
        $key = $this->getParam('key');

        Interceptor::ensureNotEmpty($num, ERROR_PARAM_IS_EMPTY, 'num');
        Interceptor::ensureFalse($key != 'yjjertert345krtwerwertwert', ERROR_PARAM_INVALID_FORMAT, "key");
        Interceptor::ensureNotEmpty($operater, ERROR_PARAM_IS_EMPTY, 'operater');
        Interceptor::ensureNotEmpty($uid, ERROR_PARAM_IS_EMPTY, 'uid');
        Interceptor::ensureNotFalse($uid > 10000, ERROR_PARAM_INVALID_FORMAT, "uid");
        Interceptor::ensureNotEmpty($num>0, ERROR_PARAM_IS_EMPTY, 'num');
        Interceptor::ensureNotEmpty($remark, ERROR_PARAM_IS_EMPTY, 'remark');

        $user = new User();
        $userinfo = $user->getUserInfo($uid);
        Interceptor::ensureNotFalse(isset($userinfo['uid']), ERROR_USER_NOT_EXIST);


        $account = new Account();
        $dao_gift_log = new DAOGiftLog();
        try {
            $dao_gift_log->startTrans();

            $ticket = $account->getBalance($uid, ACCOUNT::CURRENCY_TICKET);
            Interceptor::ensureNotEmpty($ticket, ERROR_BIZ_PAYMENT_TICKET_BALANCE_DUE);
            Interceptor::ensureNotFalse($ticket>=$num, ERROR_BIZ_PAYMENT_TICKET_BALANCE_DUE);


            $orderid = Account::getOrderId($uid);
            Interceptor::ensureNotFalse($account->decrease($uid, ACCOUNT::TRADE_TYPE_RECOVERY, $orderid, $num, ACCOUNT::CURRENCY_TICKET, "票回收(被回收人:$uid.原因:$remark.回收数量:$num.回收人:$operater)", array()),  ERROR_BIZ_PAYMENT_TICKET_BALANCE_DUE);
            Interceptor::ensureNotFalse($account->increase(ACCOUNT::COMAPNY_ACCOUNT, ACCOUNT::TRADE_TYPE_RECOVERY, $orderid, $num, ACCOUNT::CURRENCY_DIAMOND, "票回收(被回收人:$uid.原因:$remark.回收数量:$num.回收人:$operater)", array()), ERROR_BIZ_PAYMENT_TICKET_BALANCE_DUE);

            $dao_gift_log->commit();
        } catch (Exception $e) {
            $dao_gift_log->rollback();
            throw $e;
        }

        $this->render();
    }/*}}}*/


    public function recoveryByUidToUidAction()
    {
        /*{{{退钻 从一个用户到另一个用户退钻*/
        $num = $this->getParam('amount');
        $sender   = $this->getParam('sender');
        $receiver = $this->getParam('receiver');
        $remark = $this->getParam('remark');
        $operater    = $this->getParam('adminid');
        $key = $this->getParam('key');

        Interceptor::ensureNotEmpty($num, ERROR_PARAM_IS_EMPTY, 'num');
        Interceptor::ensureFalse($key != 'yjjer112jtyuktyk556456tert345krtwersf', ERROR_PARAM_INVALID_FORMAT, "key");
        Interceptor::ensureNotEmpty($operater, ERROR_PARAM_IS_EMPTY, 'operater');
        Interceptor::ensureNotEmpty($sender, ERROR_PARAM_IS_EMPTY, 'sender');
        Interceptor::ensureNotEmpty($receiver, ERROR_PARAM_IS_EMPTY, 'receiver');
        Interceptor::ensureNotFalse($sender > 10000, ERROR_PARAM_INVALID_FORMAT, "sender");
        Interceptor::ensureNotFalse($receiver > 10000, ERROR_PARAM_INVALID_FORMAT, "receiver");
        Interceptor::ensureNotEmpty($num>0, ERROR_PARAM_IS_EMPTY, 'num');
        Interceptor::ensureNotEmpty($remark, ERROR_PARAM_IS_EMPTY, 'remark');

        $user = new User();
        $userinfo = $user->getUserInfo($sender);
        Interceptor::ensureNotFalse(isset($userinfo['uid']), ERROR_USER_NOT_EXIST);
        $userinfo = $user->getUserInfo($receiver);
        Interceptor::ensureNotFalse(isset($userinfo['uid']), ERROR_USER_NOT_EXIST);


        $account = new Account();
        $dao_gift_log = new DAOGiftLog();
        try {
            $dao_gift_log->startTrans();

            $ticket = $account->getBalance($sender, ACCOUNT::CURRENCY_TICKET);
            Interceptor::ensureNotEmpty($ticket, ERROR_BIZ_PAYMENT_TICKET_BALANCE_DUE);
            Interceptor::ensureNotFalse($ticket>=$num, ERROR_BIZ_PAYMENT_TICKET_BALANCE_DUE);


            $orderid = Account::getOrderId($sender);
            Interceptor::ensureNotFalse($account->decrease($sender, ACCOUNT::TRADE_TYPE_RECOVERY, $orderid, $num, ACCOUNT::CURRENCY_TICKET, "退钻(退票:$sender.收钻:$receiver.原因:{$remark}.回收数量:$num.回收人:$operater)", array()),  ERROR_BIZ_PAYMENT_TICKET_BALANCE_DUE);
            Interceptor::ensureNotFalse($account->increase($receiver, ACCOUNT::TRADE_TYPE_RECOVERY, $orderid, $num, ACCOUNT::CURRENCY_DIAMOND, "退钻(退票:$sender.收钻:$receiver.原因:{$remark}.回收数量:$num.回收人:$operater)", array()), ERROR_BIZ_PAYMENT_TICKET_BALANCE_DUE);

            $dao_gift_log->commit();
        } catch (Exception $e) {
            $dao_gift_log->rollback();
            throw $e;
        }

        $this->render();
    }/*}}}*/


    public function recoveryDiamondAction()
    {
        /*{{{回收钻 出钻到公司帐户钻*/
        $num = $this->getParam('amount');
        $uid = $this->getParam('uid');
        $remark = $this->getParam('remark');
        $operater    = $this->getParam('adminid');
        $key = $this->getParam('key');

        Interceptor::ensureNotEmpty($num, ERROR_PARAM_IS_EMPTY, 'num');
        Interceptor::ensureFalse($key != 'yjjertert345krtwerwertwert', ERROR_PARAM_INVALID_FORMAT, "key");
        Interceptor::ensureNotEmpty($operater, ERROR_PARAM_IS_EMPTY, 'operater');
        Interceptor::ensureNotEmpty($uid, ERROR_PARAM_IS_EMPTY, 'uid');
        Interceptor::ensureNotFalse($uid > 10000, ERROR_PARAM_INVALID_FORMAT, "uid");
        Interceptor::ensureNotEmpty($num>0, ERROR_PARAM_IS_EMPTY, 'num');
        Interceptor::ensureNotEmpty($remark, ERROR_PARAM_IS_EMPTY, 'remark');

        $user = new User();
        $userinfo = $user->getUserInfo($uid);
        Interceptor::ensureNotFalse(isset($userinfo['uid']), ERROR_USER_NOT_EXIST);


        $account = new Account();
        $dao_gift_log = new DAOGiftLog();
        try {
            $dao_gift_log->startTrans();

            $ticket = $account->getBalance($uid, ACCOUNT::CURRENCY_DIAMOND);
            Interceptor::ensureNotEmpty($ticket, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            Interceptor::ensureNotFalse($ticket>=$num, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);


            $orderid = Account::getOrderId($uid);
            Interceptor::ensureNotFalse($account->decrease($uid, ACCOUNT::TRADE_TYPE_RECOVERY, $orderid, $num, ACCOUNT::CURRENCY_DIAMOND, "钻回收(被回收人:$uid.原因:$remark.回收数量:$num.回收人:$operater)", array()),  ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            Interceptor::ensureNotFalse($account->increase(ACCOUNT::COMAPNY_ACCOUNT, ACCOUNT::TRADE_TYPE_RECOVERY, $orderid, $num, ACCOUNT::CURRENCY_DIAMOND, "钻回收(被回收人:$uid.原因:$remark.回收数量:$num.回收人:$operater)", array()), ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

            $dao_gift_log->commit();
        } catch (Exception $e) {
            $dao_gift_log->rollback();
            throw $e;
        }

        $this->render();
    }/*}}}*/


    public function refundAction()
    {
        /*{{{门票退还 扣票uid到receiver钻*/
        $num = $this->getParam('amount');
        $uid = $this->getParam('uid');
        $receiver = $this->getParam('receiver');
        $remark = $this->getParam('remark');
        $operater    = $this->getParam('adminid');
        $key = $this->getParam('key');

        Interceptor::ensureNotEmpty($num, ERROR_PARAM_IS_EMPTY, 'num');
        Interceptor::ensureFalse($key != 'yjjertert345krtwerwerttdfdwert', ERROR_PARAM_INVALID_FORMAT, "key");
        Interceptor::ensureNotEmpty($operater, ERROR_PARAM_IS_EMPTY, 'operater');
        Interceptor::ensureNotEmpty($uid, ERROR_PARAM_IS_EMPTY, 'uid');
        Interceptor::ensureNotFalse($uid > 10000, ERROR_PARAM_INVALID_FORMAT, "uid");
        Interceptor::ensureNotEmpty($receiver, ERROR_PARAM_IS_EMPTY, 'receiver');
        Interceptor::ensureNotEmpty($num>0, ERROR_PARAM_IS_EMPTY, 'num');
        Interceptor::ensureNotEmpty($remark, ERROR_PARAM_IS_EMPTY, 'remark');

        $user = new User();
        $userinfo = $user->getUserInfo($uid);
        Interceptor::ensureNotFalse(isset($userinfo['uid']), ERROR_USER_NOT_EXIST);

        $user = new User();
        $userinfo = $user->getUserInfo($receiver);
        Interceptor::ensureNotFalse(isset($userinfo['uid']), ERROR_USER_NOT_EXIST);


        $account = new Account();
        $dao_gift_log = new DAOGiftLog();
        try {
            $dao_gift_log->startTrans();

            $ticket = $account->getBalance($uid, ACCOUNT::CURRENCY_TICKET);
            Interceptor::ensureNotEmpty($ticket, ERROR_BIZ_PAYMENT_TICKET_BALANCE_DUE);
            Interceptor::ensureNotFalse($ticket>=$num, ERROR_BIZ_PAYMENT_TICKET_BALANCE_DUE);


            $orderid = Account::getOrderId($uid);
            Interceptor::ensureNotFalse($account->decrease($uid, ACCOUNT::TRADE_TYPE_RECOVERY, $orderid, $num, ACCOUNT::CURRENCY_TICKET, "退还(被回收人:$uid. 接收人:$receiver.原因:$remark.回收数量:$num.回收人:$operater)", array()),  ERROR_BIZ_PAYMENT_TICKET_BALANCE_DUE);
            Interceptor::ensureNotFalse($account->increase($receiver, ACCOUNT::TRADE_TYPE_RECOVERY, $orderid, $num, ACCOUNT::CURRENCY_DIAMOND, "退还(被回收人:$uid. 接收人:$receiver.原因:$remark.回收数量:$num.回收人:$operater)", array()), ERROR_BIZ_PAYMENT_TICKET_BALANCE_DUE);

            $dao_gift_log->commit();
        } catch (Exception $e) {
            $dao_gift_log->rollback();
            throw $e;
        }

        Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $uid, "$remark", "$remark", $uid);
        Messenger::sendSystemPublish(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $receiver, "$remark", "$remark", $receiver);

        $this->render();
    }/*}}}*/

    public function activeAction()
    {
        //活动一键回收1025
        $activeid = $this->getParam('activeid');
        $operater    = $this->getParam('adminid');
        $key = $this->getParam('key');

        Interceptor::ensureNotEmpty($activeid>0, ERROR_PARAM_IS_EMPTY, 'activeid');
        Interceptor::ensureFalse($key != 'yjjertert345krtwerwertt11dfdwert', ERROR_PARAM_INVALID_FORMAT, "key");
        Interceptor::ensureNotEmpty($operater, ERROR_PARAM_IS_EMPTY, 'operater');

        $dao_active = new DAOActive();
        $actives_info = $dao_active->getInfo($activeid);

        $DAOOperations = new DAOOperations();
        $active_member_info = $DAOOperations->getDataByActiveid($activeid);

        //|================================================================================================|
        //| 回收计算. 回收活动开始时间后的钻总数((总数-送的票总数(送礼人钻)) + 送给人票数总计(活动开始后的票数统计))
        //| 只统计送礼人. 
        //| 1. 统计入钻总数 回收活动开始时间后的钻总数
        //| 2. 先算送给人的票
        //| 3. 后算应扣人的钻(总数-送礼数)

        

        $receiver_system = Account::ACTIVE_ACCOUNT25; //回收人
        $receiver = array();
        $remark   = '活动回收';

        $account = new Account();

        if(is_array($active_member_info)) {
            foreach ($active_member_info as $key => $value){
                if($value['type'] == 2) { //送礼人
                    $uid         = $value['uid'];
                    $dao_journal = new DAOJournal($uid);
                    
                    $diamond = $account->getBalance($uid, Account::CURRENCY_DIAMOND); //送礼人的钻
                    $list    = $dao_journal->getJournalListByActive($uid, 'OUT', ACCOUNT::CURRENCY_DIAMOND, ACCOUNT::TRADE_TYPE_GIFT, $actives_info['starttime'], $actives_info['endtime']);
                    
                    $sum_sub = 0; //收礼人

                    if(is_array($list)) {
                        foreach ($list as $key1 => $value1){
                            $arr = json_decode($value1['extend'], true);
                            $receiver[$arr['receiver']] += $arr['price'] * $arr['num']; //收礼人的票回收总数计数
                            $sum_sub += $arr['price'] * $arr['num'];
                        }
                    }

                    $account = new Account();
                    $dao_gift_log = new DAOGiftLog();
                    try {
                        $dao_gift_log->startTrans();
                        if($diamond>0) {
                               $orderid = Account::getOrderId($uid);
                               Interceptor::ensureNotFalse($account->decrease($uid, ACCOUNT::TRADE_TYPE_RECOVERY, $orderid, $diamond, ACCOUNT::CURRENCY_DIAMOND, "活动回收(被回收人:$uid. 接收人:$receiver_system.原因:$remark.回收数量:$diamond.回收人:$operater)", array('uid'=>$uid, 'orderid'=>$orderid, 'activeid'=>$activeid)),  ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
                               Interceptor::ensureNotFalse($account->increase($receiver_system, ACCOUNT::TRADE_TYPE_RECOVERY, $orderid, $diamond, ACCOUNT::CURRENCY_DIAMOND, "活动回收(被回收人:$uid. 接收人:$receiver_system.原因:$remark.回收数量:$diamond.回收人:$operater)", array('uid'=>$uid, 'orderid'=>$orderid, 'activeid'=>$activeid)), ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
                        }
                        $dao_gift_log->commit();
                    } catch (Exception $e) {
                        $dao_gift_log->rollback();
                        throw $e;
                    }
                    $active_member_info[$key]['diamond'] = $diamond;
                } 
            }
        }
        if(is_array($receiver)) {
            $account = new Account();
            $dao_gift_log = new DAOGiftLog();
            foreach ($receiver as $key => $value){
                $ticket = $value;
                $uid = $key;
                try {
                    $dao_gift_log->startTrans();
                    if($ticket>0) {
                        $orderid = Account::getOrderId($uid);
                        Interceptor::ensureNotFalse($account->decrease($uid, ACCOUNT::TRADE_TYPE_RECOVERY, $orderid, $ticket, ACCOUNT::CURRENCY_TICKET, "活动回收(被回收人:$uid. 接收人:$receiver_system.原因:$remark.回收数量:$ticket.回收人:$operater)", array('uid'=>$uid, 'orderid'=>$orderid, 'activeid'=>$activeid)),  ERROR_BIZ_PAYMENT_TICKET_BALANCE_DUE);
                        Interceptor::ensureNotFalse($account->increase($receiver_system, ACCOUNT::TRADE_TYPE_RECOVERY, $orderid, $ticket, ACCOUNT::CURRENCY_TICKET, "活动回收(被回收人:$uid. 接收人:$receiver_system.原因:$remark.回收数量:$ticket.回收人:$operater)", array('uid'=>$uid, 'orderid'=>$orderid, 'activeid'=>$activeid)), ERROR_BIZ_PAYMENT_TICKET_BALANCE_DUE);
                    }
                    $dao_gift_log->commit();
                } catch (Exception $e) {
                    $dao_gift_log->rollback();
                    throw $e;
                }
            }
            $active_member_info['receiver'] = $receiver;
        }

        $this->render(array('data'=>$active_member_info));
    }


    public function activebyuidAction()
    {
        //活动一键回收1025
        $uid = $this->getParam('uid');
        $operater    = $this->getParam('adminid');
        $key = $this->getParam('key');

        Interceptor::ensureNotEmpty($uid>0, ERROR_PARAM_IS_EMPTY, 'uid');
        Interceptor::ensureFalse($key != 'yjjertert345krtwerwertt11dfdwert', ERROR_PARAM_INVALID_FORMAT, "key");
        Interceptor::ensureNotEmpty($operater, ERROR_PARAM_IS_EMPTY, 'operater');

        $receiver = Account::ACTIVE_ACCOUNT25; //回收人

        $account = new Account();
        $dao_gift_log = new DAOGiftLog();
        try {
            $dao_gift_log->startTrans();

            $ticket = $account->getBalance($uid, ACCOUNT::CURRENCY_TICKET);
            if($ticket>0) {
                $orderid = Account::getOrderId($uid);
                Interceptor::ensureNotFalse($account->decrease($uid, ACCOUNT::TRADE_TYPE_RECOVERY, $orderid, $ticket, ACCOUNT::CURRENCY_TICKET, "活动回收(被回收人:$uid. 接收人:$receiver.原因:$remark.回收数量:$ticket.回收人:$operater)", array()),  ERROR_BIZ_PAYMENT_TICKET_BALANCE_DUE);
                Interceptor::ensureNotFalse($account->increase($receiver, ACCOUNT::TRADE_TYPE_RECOVERY, $orderid, $ticket, ACCOUNT::CURRENCY_DIAMOND, "活动回收(被回收人:$uid. 接收人:$receiver.原因:$remark.回收数量:$ticket.回收人:$operater)", array()), ERROR_BIZ_PAYMENT_TICKET_BALANCE_DUE);
            }
            $dao_gift_log->commit();
        } catch (Exception $e) {
            $dao_gift_log->rollback();
            throw $e;
        }

        $this->render();
    }


    
}
?>
