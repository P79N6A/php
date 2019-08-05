<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/10
 * Time: 16:41
 * star service
 */
class Star
{
    const TYPE_TASK=1;//做任务
    const TYPE_GIFT=2;//送礼物
    const TYPE_HORSERACING=18;//跑马游戏星光场

    const DIRECT_IN='IN';
    const DIRECT_OUT='OUT';

    const CURRENCY_STAR_SHINE=4;
    const CURRENCY_STAR=5;


    /**
     * 做任务增加星光余额
     * $uid,用户ID
     * $amount double 金额
     */
    public function addStarShine($uid,$orderid,$amount,$extends=array())
    {

        $star_journal_dao=new DAOStarJournal($uid);
        $account_dao=new DAOAccount($uid);

        try{
            $star_journal_dao->startTrans();

            $account_dao->insert($uid, self::CURRENCY_STAR_SHINE, $amount);

            $star_journal_dao->add($orderid, self::TYPE_TASK, self::DIRECT_IN, self::CURRENCY_STAR_SHINE, $amount, '做任务增加星光', $extends?json_encode($extends):"");
           
            $star_journal_dao->commit();
        }catch (Exception $e){
            $star_journal_dao->rollback();
            throw  $e;
        }
    }

}