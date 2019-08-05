<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/10
 * Time: 16:42
 * 星光任务service
 */
class StarTask
{

    /**
     * @param  $uid
     * @param  $taskid
     * @param  array  $award
     * @return bool
     * 做任务增加星钻星光（星光或星钻，星光和星钻）
     */
    public static function increase($uid,$taskid,array $award,$awardid=0)
    {
        $orderid=Account::getOrderId($uid);
        $result=null;

        $task_log_pay_dao=new DAOTaskLogPay();
        if ($task_log_pay_dao->checkUniq($uid, $awardid)) { return true;
        }
        Interceptor::ensureNotFalse($task_log_pay_dao->addTaskLog($uid, $awardid, $orderid, "N"), ERROR_CUSTOM, "插入日志失败");


        $account_dao=new DAOAccount($uid);
        $account_dao->startTrans();
        try{
            Interceptor::ensureNotEmpty($uid, ERROR_CUSTOM, 'uid 为空');
            Interceptor::ensureNotEmpty($taskid, ERROR_CUSTOM, 'task id 为空');
            $starlight=isset($award['starlight'])&&$award['starlight']>0?$award['starlight']:0;
            $diamond_amount=isset($award['diamonds'])&&$award['diamonds']>0?$award['diamonds']:0;
            Interceptor::ensureFalse($starlight<=0&&$diamond_amount<=0, ERROR_CUSTOM, "星光星钻数量不能同时为零");

            if ($diamond_amount) {
                $account=new Account();
                $system_account=Account::ACTIVE_ACCOUNT5;//只能是5号账户做任务送钻

                $diamond = $account->getBalance($system_account, ACCOUNT::CURRENCY_DIAMOND);
                Interceptor::ensureNotEmpty($diamond, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
                Interceptor::ensureNotFalse($diamond>=$diamond_amount, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
                Interceptor::ensureNotFalse($account->decrease($system_account, ACCOUNT::TRADE_TYPE_DO_TASK, $orderid, $diamond_amount, ACCOUNT::CURRENCY_DIAMOND, "做送钻任务而扣减系统账户", ['taskid'=>$taskid]), ERROR_BIZ_PAYMENT_DIAMOND_OUT_ACCOUNTED);

                Interceptor::ensureNotFalse($account->increase($uid, ACCOUNT::TRADE_TYPE_DO_TASK, $orderid, $diamond_amount, Account::CURRENCY_DIAMOND, "做送钻任务而增加用户钻石余额", ['taskid'=>$taskid]), ERROR_BIZ_PAYMENT_TICKET_ACCOUNTED_FOR);

            }
            if($starlight) {
                /*$star_system_account=Account::ACTIVE_ACCOUNT2000;
                $account=new Account();
                $star_amount=$account->getBalance($star_system_account,Account::CURRENCY_COIN );
                Interceptor::ensureNotEmpty($star_amount, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
                Interceptor::ensureNotFalse($star_amount>=$starlight, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

                Interceptor::ensureNotFalse($account->decrease($star_system_account, Star::TYPE_TASK, $orderid, $starlight, ACCOUNT::CURRENCY_COIN, "做送星光[".$starlight."]任务而扣减系统账户", ['taskid'=>$taskid]), ERROR_BIZ_PAYMENT_DIAMOND_OUT_ACCOUNTED);
                Interceptor::ensureNotFalse($account->increase($uid, Star::TYPE_TASK, $orderid, $starlight, Account::CURRENCY_COIN, "做送星光任务而增加用户钻石余额", ['taskid'=>$taskid]), ERROR_BIZ_PAYMENT_TICKET_ACCOUNTED_FOR);*/

                $star_journal_dao=new DAOStarJournal($uid);
                $account_dao=new DAOAccount($uid);

                $account_dao->insert($uid, Star::CURRENCY_STAR_SHINE, $starlight);

                $star_journal_dao->add($orderid, Star::TYPE_TASK, Star::DIRECT_IN, Star::CURRENCY_STAR_SHINE, $starlight, '做任务增加星光', json_encode(['taskid'=>$taskid]));
            }

            $account_dao->commit();
            //return true;
            $result=true;
        }catch (Exception $e){
            $account_dao->rollback();
            //throw  $e;
            //return false;
            $result=false;
        }
        if ($result==true) {
            $task_log_pay_dao->modifyTaskLog($uid, $awardid, $orderid, "Y");
        }

        return $result;
    }

    /**
     * 做任务增加星光或星钻
     * $uid
     * $type 2星钻|4星光
     *
     * $amount 金额
     * $taskid 任务id
     * 废弃
     */
    public static function addBalance($uid,$type,$amount,$taskid)
    {
        //
        $orderid=Account::getOrderId($uid);
        if ($type==Account::CURRENCY_DIAMOND) {//2星钻
            try{
                $account_dao=new DAOAccount($uid);
                $account=new Account();
                $account_dao->startTrans();
                $system_account=Account::ACTIVE_ACCOUNT5;//只能是5号账户做任务送钻

                $diamond = $account->getBalance($system_account, ACCOUNT::CURRENCY_DIAMOND);
                Interceptor::ensureNotEmpty($diamond, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
                Interceptor::ensureNotFalse($diamond>=$amount, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

                Interceptor::ensureNotFalse($account->decrease($system_account, ACCOUNT::TRADE_TYPE_GIFT, $orderid, $amount, ACCOUNT::CURRENCY_DIAMOND, "做送钻任务而扣减系统账户", ['taskid'=>$taskid]), ERROR_BIZ_PAYMENT_DIAMOND_OUT_ACCOUNTED);

                Interceptor::ensureNotFalse($account->increase($uid, ACCOUNT::TRADE_TYPE_GIFT, $orderid, $amount, Account::CURRENCY_DIAMOND, "做送钻任务而增加用户钻石余额", ['taskid'=>$taskid]), ERROR_BIZ_PAYMENT_TICKET_ACCOUNTED_FOR);

                $account_dao->commit();
                return true;
            }catch (Exception $e){
                $account_dao->rollback();
                //throw  $e;
                return false;
            }
        }elseif ($type==Account::CURRENCY_COIN) {//4星光
            try{
                $star_journal_model=new Star();
                //暂时没有系统账户扣减
                $star_journal_model->addStarShine($uid, $orderid, $amount, array('taskid'=>$taskid));
                return true;
            }catch (Exception $e){
                return false;
            }
        }else{
            return false;
        }
    }
}