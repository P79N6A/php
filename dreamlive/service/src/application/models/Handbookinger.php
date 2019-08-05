<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/10
 * Time: 10:26
 */
class Handbookinger
{

    const HORSES=array(1,2,3,4,5,6,7,8,9,10,11,12,13,14);
    //押注
    public static function stake($userid, $amount, $trackno)
    {
        //验证押注马匹号
        Interceptor::ensureNotFalse(in_array($trackno, self::HORSES), ERROR_SUMMIT_HANDBOOKINGER_TRACKNO_IS_NOT_EXIST, 'trackno');
        //获取游戏配置
        $game       = new DAOGame();
        $game_info  = $game->getGameByType(5);
        //验证押注项是否存在
        $round      = new DAOHandbookingerRound();
        $round_info = $round->getCurRound();
        $extend     = json_decode($round_info['extends'], true);
        Interceptor::ensureNotFalse(in_array($amount, $extend['stake_amount']), ERROR_SUMMIT_HANDBOOKINGER_STAKE_TIME_OUT);
        //验证游戏状态
        Interceptor::ensureNotFalse($round_info['status'] == 2, ERROR_SUMMIT_HANDBOOKINGER_STAKE_TIME_OUT);
        //验证用户账户是否冻结
        $dao_profile = new DAOProfile($userid);
        $profiles = $dao_profile->getUserProfiles();
        Interceptor::ensureNotFalse((!(isset($profiles['frozen']) && $profiles['frozen'] == 'Y')), ERROR_USER_FROZEN, "userid");
        //验证用户账户余额
        $account        = new DAOAccount($userid);
        $balance        = $account ->getBalance(Account::CURRENCY_DIAMOND);
        Interceptor::ensureNotFalse($balance>=$amount, ERROR_SUMMIT_HANDBOOKINGER_DIAMOND_BALANCE_DUE, 'amount');
        //扣款
        $account_extends = array(
            'uid'       => $userid,
            'name'      => '新版跑马',
            'type'      => '星钻押注',
            'amount'    => $amount,
            'roundid'   => $round_info['id']
        );
        AccountInterface::minus($userid, Account::ACTIVE_ACCOUNT9000, Account::TRADE_TYPE_GAME_HANDBOOKING, $amount, Account::CURRENCY_DIAMOND, "新版跑马押注扣款[uid:{$userid},金额:{$amount}]", $account_extends, $round_info['orderid']);
        //业务
        try{
            $stake      = new DAOHandbookingerStake();
            $stake ->insertStake($round_info['roundid'], $userid, $trackno, $amount, $round_info['orderid']);
        }catch (Exception $e){
            AccountInterface::plus($userid, Account::ACTIVE_ACCOUNT9000, Account::TRADE_TYPE_GAME_HANDBOOKING, $amount, Account::CURRENCY_DIAMOND, "新版跑马退款[uid:{$userid},金额:{$amount}]", $account_extends, $round_info['orderid']);
            throw new BizException(ERROR_BIZ_GAME_HORSERACING_BLANKER_FALSE, $e->getMessage());
        }
        //推送消息
        /*$extends        = json_encode($game_info['extends'],true);
        $bankerInfo = array(
            'roundid'               => $round_info['roundid'],
            'virtual_room_id'      => (string)$extends['virtual_room_id'],
        );
        HorseracingEngine::sendMsg($extend['virtual_room_id'],HorseracingEngine::MESSAGE_TYPE_ROOM,"押注",$bankerInfo,HorseracingEngine::MESSAGE_TAG_STAKE);
        */
        //返回结果
        return array(
            'userid'        => $userid,
            'balance'       => $account ->getBalance(Account::CURRENCY_DIAMOND)
        );
    }

    public static function getGameResult($userid)
    {
        $round      = new DAOHandbookingerRound();
        $round_info = $round->getCurRound();
        $round_log  = new DAOHandbookingerLog();
        $round_info_log = $round_log ->getResultByUid($userid, $round_info['roundid']);
        if($round_info_log) {
            foreach($round_info_log as $v){
                $rounds_info[]      = array(
                    'result_amount'     => $v['result_amount'],
                    'trackno'            => $v['trackno'],
                    'winno'             => $round_info['winno']
                );
            }
        }
        return $rounds_info;
    }

    public static function createNewRound($orderid,$extends)
    {
        $dao=new DAOHandbookingerRound();
        return $dao->createNewRound($orderid, $extends);
    }

    public static function curRound()
    {
        $dao=new DAOHandbookingerRound();
        return $dao->getCurRound();
    }
    public static function updateRound($roundid,$status,$winno=0,$url='')
    {
        $dao=new DAOHandbookingerRound();
        return $dao->modifyRound($roundid, $status, $winno, $url);
    }
    public static function idRound($roundid)
    {
        $dao=new DAOHandbookingerRound();
        return $dao->getRoundById($roundid);
    }
    public static function updateExtRound($roundid,array  $ext)
    {
        $dao=new DAOHandbookingerRound();
        return $dao->modifyExt($roundid, $ext);
    }


    public static function leastHorseStake($roundid)
    {
        $dao=new DAOHandbookingerStake();
        return $dao->getStakeLeastHorse($roundid);
    }

    public static function allStake($roundid)
    {
        $dao=new DAOHandbookingerStake();
        return $dao->getAllStake($roundid);
    }

    public static function refund($roundid)
    {
        $dao=new DAOHandbookingerStake();
        $stakes=$dao->getAllStake($roundid);
        if (empty($stakes)) { return;
        }
        $round=self::idRound($roundid);
        if (!$round) { return;
        }
        if ($round['status']==DAOHandbookingerRound::STATUS_SETTLE) { return;
        }
        $dao->startTrans();
        $account_model=new Account();
        try{
            foreach ($stakes as $i){
                $amount=$i['amount'];
                $orderid =$i['orderid'];
                $diamond = $account_model->getBalance(HandbookingerEngine::SYSTEM_ACCOUNT, Account::CURRENCY_DIAMOND);
                Interceptor::ensureNotEmpty($diamond, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
                Interceptor::ensureNotFalse($diamond>=$amount, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

                $extends=$i;
                //$extends=json_encode($extends);
                Interceptor::ensureNotFalse($account_model->decrease(HorseracingEngine::SYSTEM_ACCOUNT, HandbookingerEngine::SYSTEM_JOURNAL, $orderid, $amount,  Account::CURRENCY_DIAMOND, "handbookinger 扣减临时账户[".HandbookingerEngine::SYSTEM_ACCOUNT."]={$amount}钻", $extends),  ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
                Interceptor::ensureNotFalse($account_model->increase($i['uid'], HandbookingerEngine::SYSTEM_JOURNAL, $orderid, $amount,  Account::CURRENCY_DIAMOND, "handbookinger 增加账户[{$i['uid']}]={$amount}钻", $extends), ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

                print_r($i);
            }
            
            $dao->commit();

        }catch (Exception $e){
            $dao->rollback();
            throw  $e;
        }
    }
}