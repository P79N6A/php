<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/4
 * Time: 17:56
 */
class HorseracingStar
{
    const TRACKNOS = array(DAOHorseracingRound::TRACK_ONE,DAOHorseracingRound::TRACK_TWO);

    /**
     * 抢庄
     *
     * @param  int $gameid 游戏id
     * @param  int $amount 金额
     * @param  int $liveid 直播id
     * @param  int $userid 抢庄id
     * @param  int $robot  机器人标记
     *                     1是0否
     * @return bool
     */
    public static function banker($gameid,$amount,$liveid,$userid,$robot=0)
    {
        self::checkTime(1);
        $gameone        = Game::getGameInfo($gameid, 0);
        if($robot != 1) {
            $liveInfo       = Game::getLiveInfo($liveid);
        }
        $userInfo       = self::getUserInfo($userid);
        $istrue         = self::checkamount($amount, $userid, $gameone);
        $round          = new DAOHorseracingRound();
        $roundInfo      = $round -> getCurrentInfo(true);//添加行锁
        if($roundInfo['amount']>0&&$robot==1) { return true;//已有人抢庄，机器人抢庄失效
        }
        Interceptor::ensureNotFalse($roundInfo['amount']<$amount, ERROR_BIZ_GAME_HORSERACING_AMOUNT_MIN, 'amount');

        $blanker    = new DAOHorseracingBanker();
        $round      = new DAOHorseracingRound();

        //进行前一次抢庄退款，该次抢庄扣款

        if($roundInfo['amount']>0) {
            $orderid = AccountInterface::plus($roundInfo['bankerid'], Account::ACTIVE_ACCOUNT1900, Account::TRADE_TYPE_GAME_RUN, $roundInfo['amount'], Account::CURRENCY_DIAMOND, '退还抢庄失败金额', $roundInfo, $roundInfo['orderid']);
            if($orderid<=0) {
                $blanker->updateBlanker(2, $roundInfo['bankerid'], $roundInfo['roundid']);
            }else{
                $blanker->updateBlanker(1, $roundInfo['bankerid'], $roundInfo['roundid']);
            }
            $extends    = json_decode($roundInfo['extends'], true);
            $bankerInfo     = array(
                'uid'      => $roundInfo['bankerid'],
                'virtual_room_id'   => (string)$extends['virtual_room_id'],
                'balance' => self::checkBalance('', $roundInfo['bankerid'], 1)
            );
            HorseracingEngine::sendMsg($roundInfo['bankerid'], HorseracingEngine::MESSAGE_TYPE_PRIVATE, "抢庄退款", $bankerInfo, HorseracingEngine::MESSAGE_TAG_BANKER_REFUND);
        }
        $extends = array(
            'uid'       => $userid,
            'amount'    => $amount,
            'time'      => time()
        );
        $orderidminus = AccountInterface::minus($userid, Account::ACTIVE_ACCOUNT1900, Account::TRADE_TYPE_GAME_RUN, $amount, Account::CURRENCY_DIAMOND, '扣除抢庄成功金额', $extends, $roundInfo['orderid']);
        Interceptor::ensureNotFalse($orderidminus>0, ERROR_BIZ_GAME_HORSERACING_BLANKER_FALSE);
        if($orderid<=0) { $orderid     = $orderidminus;
        }

        try{
            $blanker -> startTrans();
            $blanker -> insertBlanker($userid, $roundInfo['roundid'], $liveid, $amount, date("Y-m-d H:i:s", time()), $orderid);
            $round   -> updateBanker($userid, $amount, $roundInfo['roundid']);
            $blanker -> commit();
        }catch (Exception $e){
            $log    = array(
                'bankid'    => $userid,//本次抢庄id
                'bankAmount'=> $amount,//本次抢庄金额
                'roundid'   => $roundInfo['roundid'],//场次
                'oldId'     => $roundInfo['bankerid'],//上次庄主
                'orderid'   => $roundInfo['orderid'],//订单id
                'type'      => '抢庄',
                'oldAmount' => $roundInfo['amount']//上次庄主金额
            );
            Logger::log("banker_message", 'bankinfo ', $log);
            $blanker -> rollback();
            throw new BizException(ERROR_BIZ_GAME_HORSERACING_BLANKER_FALSE, $e->getMessage());
        }
        //发送私信
        $roundInfo  = $round -> getCurrentInfo();
        $use        = new DAOUser();
        $userinfo   = $use->getUserInfo($roundInfo['bankerid']);

        $extends    = json_decode($roundInfo['extends'], true);
        $bankerInfo = array(
            'bankerid'      => $roundInfo['bankerid'],
            'amount'        => $roundInfo['amount'],
            'roundid'       => $roundInfo['roundid'],
            'virtual_room_id' => (string)$extends['virtual_room_id'],
            'nickname'      => $userinfo['nickname'],
            'avatar'        => $userinfo['avatar'],
            'balance'       => self::checkBalance('', $roundInfo['bankerid'], 1)
        );

        //file_put_contents(dirname(__FILE__).'/log.txt',json_encode($bankerInfo),FILE_APPEND);
        //var_dump($bankerInfo,$extends['virtual_room_id']);die();
        HorseracingEngine::sendMsg($extends['virtual_room_id'], HorseracingEngine::MESSAGE_TYPE_ROOM, "抢庄", $bankerInfo, HorseracingEngine::MESSAGE_TAG_BANKER);
        return array('balance'=>self::checkBalance('', $roundInfo['bankerid'], 1));
    }
    /**
     * 押注接口
     *
     * @param  int $gameid  游戏id
     * @param  int $amount  押注金额
     * @param  int $liveid  直播id
     * @param  int $userid  押注用户id
     * @param  int $trackno 押注赛道号
     * @return bool
     */
    public static function stake($gameid,$amount,$liveid,$userid,$trackno)
    {
        /*error_reporting(E_ALL);
        ini_set('display_errors', '1');*/
        self::checkTime(2);
        $gameone        = Game::getGameInfo($gameid, 0);
        $liveInfo       = Game::getLiveInfo($liveid);
        $userInfo       = self::getUserInfo($userid);
        $istrue         = self::checkStakeAmount($amount, $userid, $gameone);
        Interceptor::ensureNotFalse(in_array($trackno, self::TRACKNOS), ERROR_BIZ_GAME_HORSERACING_TRACKNO_NOT_EXIST, 'trackno');

        //押注赛道计数器
        $round      = new DAOHorseracingRound();
        $oneinfo    = $round->getCurrentInfo();
        $oldtotal   = Counter::get(Counter::COUNTER_TYPE_STAKE_MONEY, $trackno."_".$oneinfo['roundid']);
        Interceptor::ensureNotFalse($oldtotal<=$oneinfo['amount'], ERROR_BIZ_GAME_HORSERACING_TRACKNO_FULL, 'trackno');
        $total      = Counter::increase(Counter::COUNTER_TYPE_STAKE_MONEY, $trackno."_".$oneinfo['roundid'], $amount);
        if($total>$oneinfo['amount']) { Counter::decrease(Counter::COUNTER_TYPE_STAKE_MONEY, $trackno."_".$oneinfo['roundid'], $amount);
        }
        Interceptor::ensureNotFalse($total<=$oneinfo['amount'], ERROR_BIZ_GAME_HORSERACING_TRACKNO_FULL, 'trackno');

        $orderidminus = AccountInterface::minus($userid, Account::ACTIVE_ACCOUNT1900, Account::TRADE_TYPE_GAME_RUN, $amount, Account::CURRENCY_DIAMOND, '扣除押注成功金额', $oneinfo, $oneinfo['orderid']);
        Interceptor::ensureNotFalse($orderidminus>0, ERROR_BIZ_GAME_HORSERACING_BLANKER_FALSE);

        try{
            $stake      = new DAOHorseracingStake();
            $stake -> insertStake($oneinfo['roundid'], $userid, $liveid, $trackno, $amount, $orderidminus);
        }catch (Exception $e){
            $log    = array(
                'type'      => '押注',
                'amount'    => $amount,
                'stakeId'   => $userid,
                'orderid'   => $orderidminus,
                'roundid'   => $oneinfo['roundid']
            );
            Logger::log("banker_message", 'stakeinfo ', $log);
            throw new BizException(ERROR_BIZ_GAME_HORSERACING_BLANKER_FALSE, $e->getMessage());
        }
        //用户押注信息
        $extends        = json_decode($oneinfo['extends'], true);
        $bankerInfo     = array(
            'virtual_room_id'       => (string)$extends['virtual_room_id'],
            'uid'                    => $userid,
            'balance'               => self::checkBalance("", $userid, 1),
            'trackno1'              => self::getTracknoAmount(1, $oneinfo['roundid'], $userid),
            'trackno2'              => self::getTracknoAmount(2, $oneinfo['roundid'], $userid)
        );

        //HorseracingEngine::sendMsg($extends['virtual_room_id'],HorseracingEngine::MESSAGE_TYPE_ROOM,"押注",$bankerInfo,HorseracingEngine::MESSAGE_TAG_STAKE);
        return $bankerInfo;
    }
    /**
     * 获取每条赛道押注总金额
     *
     * @param  int $trackno 赛道号
     * @param  int $roundid 场次
     * @param  int $userid  用户id
     * @return mixed
     */
    public static function getTracknoAmount($trackno,$roundid,$userid)
    {
        $horseracingStake       = new DAOHorseracingStake();
        return $horseracingStake -> getTracknoAmountNum($roundid, $userid, $trackno);
    }
    /**
     * 获取用户登录信息
     *
     * @param  int $userid 用户id
     * @return bool
     */
    public static function getUserInfo($userid)
    {
        $use            = new DAOUser();
        $userid         = $use->getUserInfo($userid);
        Interceptor::ensureNotFalse(!empty($userid), ERROR_LOGINUSER_NOT_EXIST, 'userid');
        return true;
    }
    /**
     * 验证押注金额
     *
     * @param int $amount 金额
     * @param int $userid 用户金额
     */
    public static function checkStakeAmount($amount,$userid,$gameone)
    {
        self::checkBalance($amount, $userid);
        //查看押注金额是否在(  这个后期需要改)
        $extends     = json_decode($gameone['extends'], true);
        Interceptor::ensureNotFalse(!empty($extends), ERROR_BIZ_GAME_HORSERACING_AMOUNT_STAKE_NOT_EXIST, 'amount');
        if($extends['stake_amount']) {
            Interceptor::ensureNotFalse(in_array($amount, $extends['stake_amount']), ERROR_BIZ_GAME_HORSERACING_AMOUNT_STAKE_NOT_EXIST, 'amount');
        }
        return true;
    }
    /**
     * 检验抢庄金额
     *
     * @param  int   $amount  金额
     * @param  int   $userid  追梦ID
     * @param  array $gameone 游戏详情
     * @return bool
     */
    public static function checkamount($amount,$userid,$gameone)
    {
        //跑马游戏结算单位为星钻
        self::checkBalance($amount, $userid);
        $extends     = json_decode($gameone['extends'], true);
        //Interceptor::ensureNotFalse(!empty($extends), ERROR_BIZ_GAME_HORSERACING_AMOUNT_NOT_EXIST, 'amount');
        if($extends['banker_times']&&$extends['banker_base']) {
            foreach($extends['banker_times'] as $v){
                $amounts[]    = $v*$extends['banker_base'];
            }
        }
        Interceptor::ensureNotFalse(in_array($amount, $amounts), ERROR_BIZ_GAME_HORSERACING_AMOUNT_NOT_EXIST, 'amount');
        return true;
    }
    /**
     * 验证用户余额
     *
     * @param  int $amount 抢庄/押注金额
     * @param  int $userid 用户id
     * @return bool
     */
    public static function checkBalance($amount,$userid,$type=0)
    {
        $account        = new DAOAccount($userid);
        $balance        = $account ->getBalance(Account::CURRENCY_DIAMOND);
        if($type == 0) {
            Interceptor::ensureNotFalse($balance>=$amount, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE, 'amount');
            return true;
        }else{
            return $balance;
        }

    }
    /*
     * 验证时间
     * */
    public static function checkTime($type)
    {
        $round      = new DAOHorseracingRound();
        $info       = $round  -> getCurrentInfo(true);
        $extend     = json_decode($info['extends'], true);
        $time       = time();
        if($type == 1) {
            $stime      = strtotime($extend['timeline_option']['start_time']);
            $etime      = $stime + $extend['timeline_option']['banker_time_span'] + $extend['timeline_option']['banker_to_stake_span'];
            Interceptor::ensureNotFalse(($stime<$time&&$time<$etime), ERROR_BIZ_GAME_HORSERACING_BLANKER_TIME_OUT, '');

        }elseif($type == 2) {
            $stime      = strtotime($extend['timeline_option']['start_time']) + $extend['timeline_option']['banker_time_span'] + $extend['timeline_option']['banker_to_stake_span'];
            $etime      = $stime + $extend['timeline_option']['stake_time_span'] + $extend['timeline_option']['stake_to_run_span'];
            Interceptor::ensureNotFalse(($stime<$time&&$time<$etime), ERROR_BIZ_GAME_HORSERACING_TRACKNO_TIME_OUT, '');
        }
        return false;
    }
}