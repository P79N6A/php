<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/5
 * Time: 14:11
 */
class GiftStarLog
{

    const CONSUME_DIAMOND='DIAMOND';
    const CONSUME_STAR='STAR';

    /**
     * @return mixed
     */
    public function addGiftStarLog($sender,$receiver,$liveid,$giftid,$price,$ticket,$num)
    {
        $account_model=new Account();
        $orderid=$account_model->getOrderId($sender);
        $consume=self::CONSUME_STAR;

        $gift_star_log_dao=new DAOGiftStarLog();
        return  $gift_star_log_dao->add($orderid, $sender, $receiver, $liveid, $giftid, $consume, $price, $ticket, $num);
    }
}