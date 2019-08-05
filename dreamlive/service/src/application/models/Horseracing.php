<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/4
 * Time: 17:56
 */
class Horseracing
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
        /*error_reporting(E_ALL);
        ini_set('display_errors', '1');*/
        $time       = time();
        $round      = new DAOHorseracingRound();
        $round_info = $round -> getNewestInfo();
        $extend     = json_decode($round_info['extends'], true);
        $stime      = strtotime($extend['timeline_option']['start_time']);
        $etime      = $stime + $extend['timeline_option']['banker_time_span'] + $extend['timeline_option']['banker_to_stake_span'];
        Interceptor::ensureNotFalse(($stime<=$time&&$time<=$etime), ERROR_BIZ_GAME_HORSERACING_BLANKER_TIME_OUT, '');
        Interceptor::ensureNotFalse(($round_info['status'] == 1), ERROR_BIZ_GAME_HORSERACING_BLANKER_TIME_OUT, '');

        $dao_profile = new DAOProfile($userid);
        $profiles = $dao_profile->getUserProfiles();
        Interceptor::ensureNotFalse((!(isset($profiles['frozen']) && $profiles['frozen'] == 'Y')), ERROR_USER_FROZEN, "uid");


        $game       = new DAOGame();
        $game_info  = $game->getGameInfo($gameid);
        Interceptor::ensureNotFalse(!empty($game_info), ERROR_BIZ_GAME_NOT_EXIST, "gameid");

        if($robot != 1) {
            $live       = new DAOLive();
            Interceptor::ensureNotFalse(!empty($live -> getLiveInfo($liveid)), ERROR_BIZ_LIVE_NOT_EXIST, "liveid");
        }
        if($round_info['amount']>0&&$robot==1) { return true;//已有人抢庄，机器人抢庄失效
        }

        $user_info      = User::getUserInfo($userid);
        Interceptor::ensureNotFalse(!empty($user_info), ERROR_LOGINUSER_NOT_EXIST);

        $account        = new DAOAccount($userid);
        $balance        = $account ->getBalance(Account::CURRENCY_DIAMOND);
        if($robot!=1) {
            Interceptor::ensureNotFalse($balance>=$amount, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE, 'amount');
        }else{
            if($balance<$amount) { return true;
            }
        }
        $extends     = json_decode($game_info['extends'], true);
        Interceptor::ensureNotFalse(!empty($extends), ERROR_BIZ_GAME_HORSERACING_AMOUNT_NOT_EXIST, 'amount');
        if($extends['banker_times']&&$extends['banker_base']) {
            foreach($extends['banker_times'] as $v){
                $amounts[]    = $v*$extends['banker_base'];
            }
        }
        Interceptor::ensureNotFalse(in_array($amount, $amounts), ERROR_BIZ_GAME_HORSERACING_AMOUNT_NOT_EXIST, 'amount');

        $blanker        = new DAOHorseracingBanker();
        $round_info     = $round->getNewestInfo(true);//添加锁
        if($robot!=1) {
            Interceptor::ensureNotFalse($round_info['amount']<$amount, ERROR_BIZ_GAME_HORSERACING_AMOUNT_MIN, 'amount');
        }else{
            if($round_info['amount']>=$amount) { return true;
            }
        }


        //扣款与退款处理
        $round ->startTrans();
        try{
            $account_extends        = array(
                'uid'       => $round_info['bankerid'],
                'name'      => '跑马',
                'type'      => '星钻抢庄',
                'amount'    => $round_info['amount'],
                'roundid'   => $round_info['id']
            );
            if($round_info['amount']>0) {
                //退款
                Interceptor::ensureNotFalse(Account::decrease(Account::ACTIVE_ACCOUNT1900, Account::TRADE_TYPE_GAME_RUN, $round_info['orderid'], $round_info['amount'], Account::CURRENCY_DIAMOND, "跑马星钻抢庄退款[uid:{$round_info['bankerid']};金额:{$round_info['amount']}]", $extends),  ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
                Interceptor::ensureNotFalse(Account::increase($round_info['bankerid'], Account::TRADE_TYPE_GAME_RUN, $round_info['orderid'], $round_info['amount'],  Account::CURRENCY_DIAMOND, "跑马星钻抢庄退款[uid:{$round_info['bankerid']};金额:{$round_info['amount']}]", $account_extends), ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
                $account_banker        = new DAOAccount($round_info['bankerid']);
                $old_balance           = $account_banker ->getBalance(Account::CURRENCY_DIAMOND);
            }
            //扣款
            $account_extends['uid']     = $userid;
            $account_extends['amount']  = $amount;
            Interceptor::ensureNotFalse(Account::decrease($userid, Account::TRADE_TYPE_GAME_RUN, $round_info['orderid'], $amount, Account::CURRENCY_DIAMOND, "跑马星钻抢庄扣款[uid:{$userid};金额:{$amount}]", $account_extends),  ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            Interceptor::ensureNotFalse(Account::increase(Account::ACTIVE_ACCOUNT1900, Account::TRADE_TYPE_GAME_RUN, $round_info['orderid'], $amount, Account::CURRENCY_DIAMOND, "跑马星钻抢庄扣款[uid:{$userid};金额:{$amount}]", $extends), ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

            $round -> commit();
        }catch (Exception $e){
            $round ->rollback();
            throw $e;
        }
        //业务
        $blanker -> startTrans();
        try{
            $blanker ->updateBlanker(1, $round_info['bankerid'], $round_info['roundid']);
            if($round   -> updateBanker($userid, $amount, $round_info['roundid'])) {
                $blanker -> insertBlanker($userid, $round_info['roundid'], $liveid, $amount, date("Y-m-d H:i:s", time()), $round_info['orderid']);
            }else{
                $round_info     = $round->getNewestInfo();
                Interceptor::ensureNotFalse(Account::decrease(Account::ACTIVE_ACCOUNT1900, Account::TRADE_TYPE_GAME_RUN, $round_info['orderid'], $round_info['amount'], Account::CURRENCY_DIAMOND, "跑马星钻抢庄退款[uid:{$round_info['bankerid']};金额:{$round_info['amount']}]", $extends),  ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
                Interceptor::ensureNotFalse(Account::increase($userid, Account::TRADE_TYPE_GAME_RUN, $round_info['orderid'], $amount,  Account::CURRENCY_DIAMOND, "跑马星钻抢庄退款[uid:{$userid};金额:{$round_info['amount']}]", $account_extends), ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
                if($robot!=1) {
                    Interceptor::ensureNotFalse($round_info['amount']<$amount, ERROR_BIZ_GAME_HORSERACING_AMOUNT_MIN, 'amount');
                }else{
                    if($round_info['amount']>=$amount) { return true;
                    }
                }
            }
            $round_info     = $round->getNewestInfo();
            $balance    = $account ->getBalance(Account::CURRENCY_DIAMOND);
            $blanker -> commit();
        }catch (Exception $e){
            $log    = array(
                'bankid'    => $userid,//本次抢庄id
                'bankAmount'=> $amount,//本次抢庄金额
                'roundid'   => $round_info['roundid'],//场次
                'oldId'     => $round_info['bankerid'],//上次庄主
                'orderid'   => $round_info['orderid'],//订单id
                'type'      => '抢庄',
                'oldAmount' => $round_info['amount']//上次庄主金额
            );
            Logger::log("banker_message", 'bankinfo ', $log);
            $blanker -> rollback();
            throw new BizException(ERROR_BIZ_GAME_HORSERACING_BLANKER_FALSE, $e->getMessage());
        }
        //修改场次中的抢庄缓存
        $cache          = Cache::getInstance("REDIS_CONF_CACHE");
        $infos          = json_decode($cache ->get('horseracing_round_'.$gameid), true);
        $infos['bankerid']  = $userid;
        $infos['amount']    = $amount;
        $cache -> set('horseracing_round_'.$gameid, json_encode($infos));
        //发送消息
        if($round_info['amount']>0) {
            $extends    = json_decode($round_info['extends'], true);
            $bankerInfo     = array(
                'uid'      => $round_info['bankerid'],
                'virtual_room_id'   => (string)$extends['virtual_room_id'],
                'balance' => $old_balance
            );
            if($round_info['bankerid']!=$userid) {//如果是同一个人的话无法控制消息与接口谁先到
                HorseracingEngine::sendMsg($round_info['bankerid'], HorseracingEngine::MESSAGE_TYPE_PRIVATE, "抢庄退款", $bankerInfo, HorseracingEngine::MESSAGE_TAG_BANKER_REFUND);
            }
        }
        $round_info     = $round->getNewestInfo(true);//添加锁
        $userinfo       = User::getUserInfo($round_info['bankerid']);

        $extends    = json_decode($round_info['extends'], true);
        $bankerInfo = array(
            'bankerid'      => $round_info['bankerid'],
            'amount'        => $round_info['amount'],
            'roundid'       => $round_info['roundid'],
            'virtual_room_id' => (string)$extends['virtual_room_id'],
            'nickname'      => $userinfo['nickname'],
            'avatar'        => $userinfo['avatar'],
            'balance'       => $balance
        );
        HorseracingEngine::sendMsg($extends['virtual_room_id'], HorseracingEngine::MESSAGE_TYPE_ROOM, "抢庄", $bankerInfo, HorseracingEngine::MESSAGE_TAG_BANKER);
        return array('balance'=>$balance);
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
        //file_put_contents('/tmp/game.txt',"start-{$gameid}-{$amount}-{$userid}"."\n\n",FILE_APPEND );
        //检测参数
        $time       = time();
        $round      = new DAOHorseracingRound();
        $round_info = $round -> getNewestInfo();
        $extend     = json_decode($round_info['extends'], true);
        $stime      = strtotime($extend['timeline_option']['start_time']) + $extend['timeline_option']['banker_time_span'] + $extend['timeline_option']['banker_to_stake_span'];
        $etime      = $stime + $extend['timeline_option']['stake_time_span'] + $extend['timeline_option']['stake_to_run_span']-2;
        Interceptor::ensureNotFalse(($stime<=$time&&$time<=$etime), ERROR_BIZ_GAME_HORSERACING_TRACKNO_TIME_OUT, '');
        Interceptor::ensureNotFalse(($round_info['status'] == 2), ERROR_BIZ_GAME_HORSERACING_TRACKNO_TIME_OUT, '');
        Interceptor::ensureNotFalse($userid != $round_info['bankerid'], ERROR_BIZ_GAME_NOT_BANKER);
        Interceptor::ensureNotFalse($round_info['bankerid']>0, ERROR_SYS_DB_SQL);

        $dao_profile = new DAOProfile($userid);
        $profiles = $dao_profile->getUserProfiles();
        Interceptor::ensureNotFalse((!(isset($profiles['frozen']) && $profiles['frozen'] == 'Y')), ERROR_USER_FROZEN, "uid");


        $game       = new DAOGame();
        //$game_info  = $game->getGameInfo($gameid);
        $game_info  = $game->getGameByType(DAOGame::TYPE_HORSERACING);
        Interceptor::ensureNotFalse(!empty($game_info), ERROR_BIZ_GAME_NOT_EXIST, "gameid");

        $live       = new DAOLive();
        Interceptor::ensureNotFalse(!empty($live -> getLiveInfo($liveid)), ERROR_BIZ_LIVE_NOT_EXIST, "liveid");

        $user_info      = User::getUserInfo($userid);
        Interceptor::ensureNotFalse(!empty($user_info), ERROR_LOGINUSER_NOT_EXIST);

        $account        = new DAOAccount($userid);
        $balance        = $account ->getBalance(Account::CURRENCY_DIAMOND);
        Interceptor::ensureNotFalse($balance>=$amount, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE, 'amount');
        $extends     = json_decode($game_info['extends'], true);
        Interceptor::ensureNotFalse(!empty($extends), ERROR_BIZ_GAME_HORSERACING_AMOUNT_STAKE_NOT_EXIST, 'amount');
        Interceptor::ensureNotFalse(in_array($amount, $extends['stake_amount']), ERROR_BIZ_GAME_HORSERACING_AMOUNT_STAKE_NOT_EXIST, 'amount');
        Interceptor::ensureNotFalse(in_array($trackno, self::TRACKNOS), ERROR_BIZ_GAME_HORSERACING_TRACKNO_NOT_EXIST, 'trackno');

        //处理并发
        $round_info     = $round->getNewestInfo();
        $total          = Counter::get(Counter::COUNTER_TYPE_STAKE_MONEY, $trackno."_".$round_info['roundid']);
        Interceptor::ensureNotFalse(($total+$amount)<=$round_info['amount'], ERROR_BIZ_GAME_HORSERACING_TRACKNO_FULL, 'trackno');
        Counter::increase(Counter::COUNTER_TYPE_STAKE_MONEY, $trackno."_".$round_info['roundid'], $amount);
        $new_total      = Counter::get(Counter::COUNTER_TYPE_STAKE_MONEY, $trackno."_".$round_info['roundid']);
        if($new_total>$round_info['amount']) {
            Counter::decrease(Counter::COUNTER_TYPE_STAKE_MONEY, $trackno."_".$round_info['roundid'], $amount);
            Interceptor::ensureNotFalse($new_total<=$round_info['amount'], ERROR_BIZ_GAME_HORSERACING_TRACKNO_FULL, 'trackno');
        }
        //file_put_contents('/tmp/game.txt',"扣款"."\n\n",FILE_APPEND );
        //扣款
        $account_extends        = array(
            'uid'       => $userid,
            'name'      => '跑马',
            'type'      => '星钻押注',
            'amount'    => $amount,
            'roundid'   => $round_info['id']
        );
        AccountInterface::minus($userid, Account::ACTIVE_ACCOUNT1900, Account::TRADE_TYPE_GAME_RUN, $amount, Account::CURRENCY_DIAMOND, "押注扣款[uid:{$userid},金额:{$amount}]", $account_extends, $round_info['orderid']);
        //file_put_contents('/tmp/game.txt',"押注"."\n\n",FILE_APPEND );
        //业务
        try{
            $stake      = new DAOHorseracingStake();
            $stake -> insertStake($round_info['roundid'], $userid, $liveid, $trackno, $amount, $round_info['orderid']);
        }catch (Exception $e){
            $log    = array(
                'type'      => '押注',
                'amount'    => $amount,
                'stakeId'   => $userid,
                'orderid'   => $round_info['orderid'],
                'roundid'   => $round_info['roundid']
            );
            Logger::log("banker_message", 'stakeinfo ', $log);
            throw new BizException(ERROR_BIZ_GAME_HORSERACING_BLANKER_FALSE, $e->getMessage());
        }
        //file_put_contents('/tmp/game.txt',"押注"."\n\n",FILE_APPEND );
        //发送消息
        $stake              = new DAOHorseracingStake();
        $taotal_peoples     = $stake ->getStakePeple($round_info['roundid']);
        if(!empty($taotal_peoples)) {
            $total_people   = $taotal_peoples['num'] + 1;
        }
        $bankerInfo = array(
            'roundid'               => $round_info['roundid'],
            'virtual_room_id'      => (string)$extend['virtual_room_id'],
            'total_people'         => $total_people>1?$total_people:1
        );
        HorseracingEngine::sendMsg($extend['virtual_room_id'], HorseracingEngine::MESSAGE_TYPE_ROOM, "押注", $bankerInfo, HorseracingEngine::MESSAGE_TAG_STAKE);

        //file_put_contents('/tmp/game.txt',"发消息"."\n\n",FILE_APPEND );
        //用户押注信息
        $bankerInfo     = array(
            'virtual_room_id'       => (string)$extend['virtual_room_id'],
            'uid'                    => $userid,
            'balance'               => $account ->getBalance(Account::CURRENCY_DIAMOND),
            'trackno1'              => $stake -> getTracknoAmountNum($round_info['roundid'], $userid, 1),
            'trackno2'              => $stake -> getTracknoAmountNum($round_info['roundid'], $userid, 2),
        );
        //file_put_contents('/tmp/game.txt',"end"."\n\n",FILE_APPEND );

        return $bankerInfo;
    }
    /**
     * 获取本局游戏获利前三用户与登录用户该打赏的礼物
     */
    public static function getGameResult($userid)
    {
        $user_info      = User::getUserInfo($userid);
        Interceptor::ensureNotFalse(!empty($user_info), ERROR_LOGINUSER_NOT_EXIST);

        $round          = new DAOHorseracingRound();
        $round_info     = $round ->getNewestInfo();
        $cache          = Cache::getInstance("REDIS_CONF_CACHE");
        $top3_info      = $cache ->get('horseracing_top3_'.$round_info['roundid']);
        if($top3_info) { $top3_info      = json_decode($top3_info, true);
        }
        //获取打赏礼物
        $log            = new DAOHorseracingLog();
        $reward         = new DAORewardConfig();
        $stat           = new DAOHorseracingStake();
        $gift           = new DAOGift();
        //用户输赢情况
        $win            = $stat->getTracknoAmountNum($round_info['roundid'], $userid, $round_info['winno']);
        $winamount      = empty($win)?0:$win;
        $lose_trackno   = $round_info['winno'] == 1?2:1;
        $lose           = $stat->getTracknoAmountNum($round_info['roundid'], $userid, $lose_trackno);
        $loseamount     = empty($lose)?0:$lose;
        $total_amount   = floor($winamount*0.9) - $loseamount;
        //$win_amount     = $log->getWinAmount($userid,$round_info['roundid']);
        //if($total_amount!=0){
            //$total_amount   = array_sum(array_column($win_amount, 'num'));
            $gift_ids          = $reward ->getRewardInfos($total_amount);
        if($gift_ids) {
            foreach ($gift_ids as $value){
                $gift_info  = $gift->getInfo($value['giftid']);
                $gifts[]    = array(
                    'giftid'    => $value['giftid'],
                    'giftname'  => $gift_info['name'],
                    'amount'    => $gift_info['price'],
                    'giftimg'   => Util::joinStaticDomain($gift_info['image']),
                    'type'      => $gift_info['type'],
                    'consume'   => $gift_info['consume']
                );
            }
        }
        //}

        return array('top'=>$top3_info,'amount'=>$total_amount,'gift'=>$gifts,'roundid'=>$round_info['roundid']);
    }
    //-----------------------星光----------------------------------------//

    //星光抢庄
    public static function starBanker($gameid,$amount,$liveid,$userid,$robot=0)
    {
        /*error_reporting(E_ALL);
        ini_set('display_errors', '1');*/
        $time       = time();
        $round      = new DAOHorseracingRoundStar();
        $round_info = $round -> getNewestInfo();
        $extend     = json_decode($round_info['extends'], true);
        $stime      = strtotime($extend['timeline_option']['start_time']);
        $etime      = $stime + $extend['timeline_option']['banker_time_span'] + $extend['timeline_option']['banker_to_stake_span'];
        Interceptor::ensureNotFalse(($round_info['status'] == 1), ERROR_BIZ_GAME_HORSERACING_BLANKER_TIME_OUT, '');
        Interceptor::ensureNotFalse(($stime<=$time&&$time<=$etime), ERROR_BIZ_GAME_HORSERACING_BLANKER_TIME_OUT, '');

        $game       = new DAOGame();
        $game_info  = $game->getGameInfo($gameid);
        Interceptor::ensureNotFalse(!empty($game_info), ERROR_BIZ_GAME_NOT_EXIST, "gameid");

        if($robot != 1) {
            $live       = new DAOLive();
            Interceptor::ensureNotFalse(!empty($live -> getLiveInfo($liveid)), ERROR_BIZ_LIVE_NOT_EXIST, "liveid");
        }
        if($round_info['amount']>0&&$robot==1) { return true;//已有人抢庄，机器人抢庄失效
        }

        $user_info      = User::getUserInfo($userid);
        Interceptor::ensureNotFalse(!empty($user_info), ERROR_LOGINUSER_NOT_EXIST);

        $account        = new DAOAccount($userid);
        $balance        = $account ->getBalance(Account::CURRENCY_COIN);
        if($robot != 1) {
            Interceptor::ensureNotFalse($balance>=$amount, ERROR_BIZ_STAR_BALANCE_DUE, 'amount');
        }else{
            if($balance<$amount) { return true;
            }
        }

        $extends     = json_decode($game_info['extends'], true);
        Interceptor::ensureNotFalse(!empty($extends), ERROR_BIZ_GAME_HORSERACING_AMOUNT_NOT_EXIST, 'amount');
        if($extends['banker_times']&&$extends['banker_base']) {
            foreach($extends['banker_times'] as $v){
                $amounts[]    = $v*$extends['banker_base'];
            }
        }

        Interceptor::ensureNotFalse(in_array($amount, $amounts), ERROR_BIZ_GAME_HORSERACING_AMOUNT_NOT_EXIST, 'amount');

        $blanker        = new DAOHorseracingBankerStar();
        $round_info     = $round->getNewestInfo(true);//添加锁
        if($robot != 1) {
            Interceptor::ensureNotFalse($round_info['amount']<$amount, ERROR_BIZ_GAME_HORSERACING_AMOUNT_MIN, 'amount');
        }else{
            if($round_info['amount']>=$amount) { return true;
            }
        }


        //扣款与退款处理
        $round ->startTrans();
        try{
            $account_extends        = array(
                'uid'       => $round_info['bankerid'],
                'name'      => '跑马',
                'type'      => '星光抢庄',
                'amount'    => $round_info['amount'],
                'roundid'   => $round_info['id']
            );
            if($round_info['amount']>0) {
                //退款
                Interceptor::ensureNotFalse(Account::decrease(Account::ACTIVE_ACCOUNT1900, Account::TRADE_TYPE_GAME_RUN, $round_info['orderid'], $round_info['amount'], Account::CURRENCY_COIN, "跑马星光抢庄退款[uid:{$round_info['bankerid']};金额:{$round_info['amount']}]", $extends),  ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
                Interceptor::ensureNotFalse(Account::increase($round_info['bankerid'], Account::TRADE_TYPE_GAME_RUN, $round_info['orderid'], $round_info['amount'],  Account::CURRENCY_COIN, "跑马星光抢庄退款[uid:{$round_info['bankerid']};金额:{$round_info['amount']}]", $account_extends), ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
                $account_banker        = new DAOAccount($round_info['bankerid']);
                $old_balance    = $account_banker ->getBalance(Account::CURRENCY_COIN);
            }
            //扣款
            $account_extends['uid']     = $userid;
            $account_extends['amount']  = $amount;
            Interceptor::ensureNotFalse(Account::decrease($userid, Account::TRADE_TYPE_GAME_RUN, $round_info['orderid'], $amount, Account::CURRENCY_COIN, "跑马星光抢庄扣款[uid:{$userid};金额:{$amount}]", $account_extends),  ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            Interceptor::ensureNotFalse(Account::increase(Account::ACTIVE_ACCOUNT1900, Account::TRADE_TYPE_GAME_RUN, $round_info['orderid'], $amount, Account::CURRENCY_COIN, "跑马星光抢庄扣款[uid:{$userid};金额:{$amount}]", $extends), ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            $round -> commit();
        }catch (Exception $e){
            $round ->rollback();
            throw $e;
        }

        //业务
        $blanker -> startTrans();
        try{
            $blanker ->updateBlanker(1, $round_info['bankerid'], $round_info['roundid']);
            if($round   -> updateBanker($userid, $amount, $round_info['roundid'])) {
                $blanker -> insertBlanker($userid, $round_info['roundid'], $liveid, $amount, date("Y-m-d H:i:s", time()), $round_info['orderid']);
            }else{
                $round_info     = $round->getNewestInfo();
                Interceptor::ensureNotFalse(Account::decrease(Account::ACTIVE_ACCOUNT1900, Account::TRADE_TYPE_GAME_RUN, $round_info['orderid'], $round_info['amount'], Account::CURRENCY_COIN, "跑马星光抢庄退款[uid:{$round_info['bankerid']};金额:{$round_info['amount']}]", $extends),  ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
                Interceptor::ensureNotFalse(Account::increase($userid, Account::TRADE_TYPE_GAME_RUN, $round_info['orderid'], $amount,  Account::CURRENCY_COIN, "跑马星光抢庄退款[uid:{$round_info['bankerid']};金额:{$round_info['amount']}]", $account_extends), ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
                if($robot != 1) {
                    Interceptor::ensureNotFalse($round_info['amount']<$amount, ERROR_BIZ_GAME_HORSERACING_AMOUNT_MIN, 'amount');
                }else{
                    if($round_info['amount']>=$amount) { return true;
                    }
                }
            }
            $blanker -> insertBlanker($userid, $round_info['roundid'], $liveid, $amount, date("Y-m-d H:i:s", time()), $round_info['orderid']);
            $round   -> updateBanker($userid, $amount, $round_info['roundid']);
            $balance    = $account ->getBalance(Account::CURRENCY_COIN);
            $blanker -> commit();
        }catch (Exception $e){
            $log    = array(
                'bankid'    => $userid,//本次抢庄id
                'bankAmount'=> $amount,//本次抢庄金额
                'roundid'   => $round_info['roundid'],//场次
                'oldId'     => $round_info['bankerid'],//上次庄主
                'orderid'   => $round_info['orderid'],//订单id
                'type'      => '抢庄星光',
                'oldAmount' => $round_info['amount']//上次庄主金额
            );
            Logger::log("banker_message", 'bankinfo ', $log);
            $blanker -> rollback();
            throw new BizException(ERROR_BIZ_GAME_HORSERACING_BLANKER_FALSE, $e->getMessage());
        }
        //修改场次中的抢庄缓存
        $cache          = Cache::getInstance("REDIS_CONF_CACHE");
        $infos          = json_decode($cache ->get('horseracing_round_'.$gameid), true);
        $infos['bankerid']  = $userid;
        $infos['amount']    = $amount;
        $cache -> set('horseracing_round_'.$gameid, json_encode($infos));
        //发送消息
        if($round_info['amount']>0) {
            $extends    = json_decode($round_info['extends'], true);
            $bankerInfo     = array(
                'uid'      => $round_info['bankerid'],
                'virtual_room_id'   => (string)$extends['virtual_room_id'],
                'balance' => $old_balance
            );
            if($round_info['bankerid']!=$userid) {
                HorseracingEngineStar::sendMsg($round_info['bankerid'], HorseracingEngineStar::MESSAGE_TYPE_PRIVATE, "抢庄星光退款", $bankerInfo, HorseracingEngineStar::MESSAGE_TAG_BANKER_REFUND);
            }
        }
        $round_info     = $round->getNewestInfo(true);//添加锁
        $userinfo       = User::getUserInfo($round_info['bankerid']);

        $extends    = json_decode($round_info['extends'], true);
        $bankerInfo = array(
            'bankerid'      => $round_info['bankerid'],
            'amount'        => $round_info['amount'],
            'roundid'       => $round_info['roundid'],
            'virtual_room_id' => (string)$extends['virtual_room_id'],
            'nickname'      => $userinfo['nickname'],
            'avatar'        => $userinfo['avatar'],
            'balance'       => $balance
        );
        HorseracingEngineStar::sendMsg($extends['virtual_room_id'], HorseracingEngineStar::MESSAGE_TYPE_ROOM, "抢庄星光", $bankerInfo, HorseracingEngineStar::MESSAGE_TAG_BANKER);
        return array('balance'=>$balance);
    }
    //星光押注
    public static function starStake($gameid,$amount,$liveid,$userid,$trackno)
    {
        //检测参数
        $time       = time();
        $round      = new DAOHorseracingRoundStar();
        $round_info = $round -> getNewestInfo();
        $extend     = json_decode($round_info['extends'], true);
        $stime      = strtotime($extend['timeline_option']['start_time']) + $extend['timeline_option']['banker_time_span'] + $extend['timeline_option']['banker_to_stake_span'];
        $etime      = $stime + $extend['timeline_option']['stake_time_span'] + $extend['timeline_option']['stake_to_run_span']-2;
        Interceptor::ensureNotFalse(($stime<=$time&&$time<=$etime), ERROR_BIZ_GAME_HORSERACING_TRACKNO_TIME_OUT, '');
        Interceptor::ensureNotFalse(($round_info['status'] == 2), ERROR_BIZ_GAME_HORSERACING_TRACKNO_TIME_OUT, '');
        Interceptor::ensureNotFalse($userid != $round_info['bankerid'], ERROR_BIZ_GAME_NOT_BANKER);
        Interceptor::ensureNotFalse($round_info['bankerid']>0, ERROR_SYS_DB_SQL);

        $game       = new DAOGame();
        //$game_info  = $game->getGameInfo($gameid);
        $game_info  = $game->getGameByType(DAOGame::TYPE_HORSERACING_STAR);
        Interceptor::ensureNotFalse($gameid==5, ERROR_BIZ_GAME_NOT_EXIST, "gameid");
        Interceptor::ensureNotFalse(!empty($game_info), ERROR_BIZ_GAME_NOT_EXIST, "gameid");

        $live       = new DAOLive();
        Interceptor::ensureNotFalse(!empty($live -> getLiveInfo($liveid)), ERROR_BIZ_LIVE_NOT_EXIST, "liveid");

        $user_info      = User::getUserInfo($userid);
        Interceptor::ensureNotFalse(!empty($user_info), ERROR_LOGINUSER_NOT_EXIST);

        $account        = new DAOAccount($userid);
        $balance        = $account ->getBalance(Account::CURRENCY_COIN);
        Interceptor::ensureNotFalse($balance>=$amount, ERROR_BIZ_STAR_BALANCE_DUE, 'amount');
        $extends     = json_decode($game_info['extends'], true);
        Interceptor::ensureNotFalse(!empty($extends), ERROR_BIZ_GAME_HORSERACING_AMOUNT_STAKE_NOT_EXIST, 'amount');
        Interceptor::ensureNotFalse(in_array($amount, $extends['stake_amount']), ERROR_BIZ_GAME_HORSERACING_AMOUNT_STAKE_NOT_EXIST, 'amount');
        Interceptor::ensureNotFalse(in_array($trackno, self::TRACKNOS), ERROR_BIZ_GAME_HORSERACING_TRACKNO_NOT_EXIST, 'trackno');

        //处理并发
        $round_info     = $round->getNewestInfo();
        $total          = Counter::get(Counter::COUNTER_TYPE_STAKE_MONEY, $trackno."_xingguang_".$round_info['roundid']);
        Interceptor::ensureNotFalse(($total+$amount)<=$round_info['amount'], ERROR_BIZ_GAME_HORSERACING_TRACKNO_FULL, 'trackno');
        Counter::increase(Counter::COUNTER_TYPE_STAKE_MONEY, $trackno."_xingguang_".$round_info['roundid'], $amount);
        $new_total      = Counter::get(Counter::COUNTER_TYPE_STAKE_MONEY, $trackno."_xingguang_".$round_info['roundid']);
        if($new_total>$round_info['amount']) {
            Counter::decrease(Counter::COUNTER_TYPE_STAKE_MONEY, $trackno."_xingguang_".$round_info['roundid'], $amount);
            Interceptor::ensureNotFalse($new_total<=$round_info['amount'], ERROR_BIZ_GAME_HORSERACING_TRACKNO_FULL, 'trackno');
        }

        //扣款
        $round ->startTrans();
        try{
            $account_extends        = array(
                'uid'       => $userid,
                'game'      => '跑马',
                'type'      => '星光押注',
                'amount'    => $amount,
                'roundid'   => $round_info['orderid']
            );
            //扣款
            Interceptor::ensureNotFalse(Account::decrease($userid, Account::TRADE_TYPE_GAME_RUN, $round_info['orderid'], $amount, Account::CURRENCY_COIN, "跑马星光押注扣款[uid:{$userid};金额:{$amount}]", $account_extends),  ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            Interceptor::ensureNotFalse(Account::increase(Account::ACTIVE_ACCOUNT1900, Account::TRADE_TYPE_GAME_RUN, $round_info['orderid'], $amount, Account::CURRENCY_COIN, "跑马星光押注扣款[uid:{$userid};金额:{$amount}]", $extend), ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            $round -> commit();
        }catch (Exception $e){
            $round ->rollback();
            throw $e;
        }
        //AccountInterface::minus($userid,Account::ACTIVE_ACCOUNT1900,Account::TRADE_TYPE_GAME_RUN,$amount,Account::CURRENCY_COIN,"押注扣款[uid:{$userid},金额:{$amount}]",$round_info,$round_info['orderid']);

        //业务
        try{
            $stake      = new DAOHorseracingStakeStar();
            $stake -> insertStake($round_info['roundid'], $userid, $liveid, $trackno, $amount, $round_info['orderid']);
        }catch (Exception $e){
            $log    = array(
                'type'      => '押注星光',
                'amount'    => $amount,
                'stakeId'   => $userid,
                'orderid'   => $round_info['orderid'],
                'roundid'   => $round_info['roundid']
            );
            Logger::log("banker_message", 'stakeinfo ', $log);
            throw new BizException(ERROR_BIZ_GAME_HORSERACING_BLANKER_FALSE, $e->getMessage());
        }

        //发送消息
        $stake              = new DAOHorseracingStakeStar();
        $taotal_peoples     = $stake ->getStakePeple($round_info['roundid']);
        if(!empty($taotal_peoples)) {
            $total_people   = $taotal_peoples['num'] + 1;
        }
        $bankerInfo = array(
            'roundid'               => $round_info['roundid'],
            'virtual_room_id'      => (string)$extend['virtual_room_id'],
            'total_people'         => $total_people>1?$total_people:1
        );
        HorseracingEngineStar::sendMsg($extend['virtual_room_id'], HorseracingEngineStar::MESSAGE_TYPE_ROOM, "押注星光", $bankerInfo, HorseracingEngineStar::MESSAGE_TAG_STAKE);
        //用户押注信息
        $bankerInfo     = array(
            'virtual_room_id'       => (string)$extend['virtual_room_id'],
            'uid'                    => $userid,
            'balance'               => $account ->getBalance(Account::CURRENCY_COIN),
            'trackno1'              => $stake -> getTracknoAmountNum($round_info['roundid'], $userid, 1),
            'trackno2'              => $stake -> getTracknoAmountNum($round_info['roundid'], $userid, 2),
        );

        return $bankerInfo;
    }
    //星光场top3与礼物
    public static function getStarGameResult($userid)
    {
        $user_info      = User::getUserInfo($userid);
        Interceptor::ensureNotFalse(!empty($user_info), ERROR_LOGINUSER_NOT_EXIST);

        $round          = new DAOHorseracingRoundStar();
        $round_info     = $round ->getNewestInfo();
        $cache          = Cache::getInstance("REDIS_CONF_CACHE");
        $top3_info      = $cache ->get('horseracing_top3_star_'.$round_info['roundid']);
        if($top3_info) { $top3_info      = json_decode($top3_info, true);
        }
        //获取打赏礼物
        $log            = new DAOHorseracingLogStar();
        $reward         = new DAORewardConfig();
        $stat           = new DAOHorseracingStakeStar();
        $gift           = new DAOGift();
        //用户输赢情况
        $win            = $stat->getTracknoAmountNum($round_info['roundid'], $userid, $round_info['winno']);
        $winamount      = !$win?0:$win;
        $lose_trackno   = $round_info['winno'] == 1?2:1;
        $lose           = $stat->getTracknoAmountNum($round_info['roundid'], $userid, $lose_trackno);
        $loseamount     = !$lose?0:$lose;
        $total_amount   = floor($winamount*0.9) - $loseamount;
        //$win_amount     = $log->getWinAmount($userid,$round_info['roundid']);
        //if($win_amount){
            //$total_amount   = array_sum(array_column($win_amount, 'num'));
            //星光游戏默认取0的礼物
            $gift_ids          = $reward ->getRewardInfos(0);
        if($gift_ids) {
            foreach ($gift_ids as $value){
                $gift_info  = $gift->getInfo($value['giftid']);
                $gifts[]    = array(
                    'giftid'    => $value['giftid'],
                    'giftname'  => $gift_info['name'],
                    'amount'    => $gift_info['price'],
                    'giftimg'   => Util::joinStaticDomain($gift_info['image']),
                    'type'      => $gift_info['type'],
                    'consume'   => $gift_info['consume']
                );
            }
        }
        //}

        return array('top'=>$top3_info,'amount'=>$total_amount,'gift'=>$gifts,'roundid'=>$round_info['roundid']);
    }

    /**
     * 获取跑马游戏的排序
     */
    public static function getRankingInfo($userid)
    {
        /*error_reporting(E_ALL);
        ini_set('display_errors', '1');*/
        //$week_info_key         = 'weekinfo'.$type;
        $week_info_key         = 'weekinfo';
        $is_open               = 1;

        /*if($type == 1){
            $stime       = mktime(0,0,0,date('m'),date('d')-date('w')+3-7,date('Y'));
            $etime       = mktime(23,59,59,date('m'),date('d')-date('w')+2,date('Y'));
        }else{
            $stime       = strtotime(date("Y-m-d 00:00:00",time() - (date("w")==0?7:date("w")-3)*24*3600));
            $etime       = time();
        }*/
        $stime      = strtotime('2017-7-17 00:00:00');
        $etime      = strtotime('2017-7-23 23:59:59');
        if(time()>$etime) { $is_open = 2;
        }
        if(time()<$stime) { $is_open = 0;
        }

        $live                       = new DAOLive();
        $horseracing_log            = new DAOHorseracingLog();
        $star_horseracing_log       = new DAOHorseracingLogStar();
        $cache                      = Cache::getInstance("REDIS_CONF_CACHE");

        $week_info_json             = $cache -> get($week_info_key);
        if($week_info_json) {
            return json_decode($week_info_json, true);
        }else{
            //获取用户top10
            $week_win_amount            = $horseracing_log -> getUserSumWinAmount($stime, $etime);
            //获取荷官top10
            $running_water          = $horseracing_log -> getLiveSumRunningWaters($stime, $etime);
            $star_running_water     = $star_horseracing_log -> getLiveSumRunningWaters($stime, $etime);
            $running_water          = array_merge($running_water, $star_running_water);
            $liveids=array_column($running_water, 'liveid');
            if($liveids) { $last_res=$live->getAll("select uid,liveid from live where liveid in (".implode(',', $liveids).")", "");
            }
            if($last_res) {
                foreach ($last_res as $v){
                    $live_info[$v['liveid']]    =$v['uid'];
                }
            }
            if($running_water) {
                $running_waters     = array();
                $uids               = array();
                foreach($running_water as $k=>$v){
                    $nums       = $running_waters[$live_info[$v['liveid']]]['num'];
                    $running_waters[$live_info[$v['liveid']]]  = $v;
                    $running_waters[$live_info[$v['liveid']]]['uid']       = $live_info[$v['liveid']];
                    if(in_array($live_info[$v['liveid']], $uids)) {
                        $running_waters[$live_info[$v['liveid']]]['num'] = $nums+floor($v['num'])+$v['people']*100;
                    }else{
                        $uids[]       = $live_info[$v['liveid']];
                        $running_waters[$live_info[$v['liveid']]]['num']  = floor($v['num'])+$v['people']*100;
                    }

                }
                usort(
                    $running_waters, function ($a,$b) {
                        $_a=$a['num'];
                        $_b=$b['num'];
                        if ($_a == $_b) { return 0;
                        }
                        return ($_a<$_b)?1:-1;
                    }
                );
            }
            $week_running_water = array_slice($running_waters, 0, 10);
            $week_info  = array(
                'is_open'       => $is_open,
               /* 'user_uid'       => '',
                'user_nickname' => '',
                'user_avatar'   => '',
                'live_uid'      => '',
                'live_nickname' => '',
                'live_avatar'   => '',*/
                'this_week_info' => array(
                    'user_info'     => !empty(self::getInfo($week_win_amount, $userid))?self::getInfo($week_win_amount, $userid):array(),
                    'live_info'     => !empty(self::getInfo($week_running_water, $userid))?self::getInfo($week_running_water, $userid):array()
                )
            );
            $cache -> set(json_encode($week_info), 60);
        }
        return $week_info;
    }
    public static function getInfo($this_week_running_water,$userid)
    {
        if($this_week_running_water) {
            foreach($this_week_running_water as $key=>$value){
                if(!$value['uid']) { continue;
                }
                $user_info      = User::getUserInfo($value['uid']);
                $feed           = new Feeds();
                $feed_info      = $feed->getActivingLiveUser((array)$value['uid']);
                $followed       = Follow::isFollowed($userid, $value['uid']);
                $live_info[$key]      = array(
                    "uid"             => $value['uid'],
                    "nickname"        => $user_info['nickname'],
                    "avatar"          => $user_info['avatar'],
                    "is_focus"        => $followed[$value['uid']]?'Y':'N',
                    "live_status"    => $feed_info[$value['uid']]?"Y":"N",
                    "amount"          => $value['num'],
                    "ranking"         =>$key+1
                );
            }
            //die();
        }
        return $live_info;
    }

}