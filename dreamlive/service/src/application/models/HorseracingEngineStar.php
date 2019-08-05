<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/6
 * Time: 9:52
 */
class HorseracingEngineStar
{
    const BEFORE_BANKER_TIME=2;//提前时间4
    const BANKER_TIME_SPAN=10;//抢庄时间
    const BANKER_TIME_SPAN_ROBOT=8;//机器人开抢时间
    const BANKER_TO_STAKE_SPAN=2;//抢庄结束到开始押注时间
    const STAKE_TIME_SPAN=20;//押注时间
    const STAKE_TO_RUN_SPAN=1;//押注到跑马
    const RUN_PREPARE_SPAN=3;//预备跑
    const RUN_TIME_SPAN=6;//跑马时间10
    const RESULT_WINNER_SPAN=2;//winner时间3
    const RESULT_REWARD_TOP3_SPAN=4;//榜单打赏时间5
    const NEXT_ROUND_TIME=1;//下一局开始时间


    const SYSTEM_DIVIDE_ACCOUNT=Account::ACTIVE_ACCOUNT3000;//游戏系统分红账户（钻)
    const SYSTEM_TMP_ACCOUNT=Account::ACTIVE_ACCOUNT1900;//游戏中间临时账户
    //const SYSTEM_ROBOT_ACCOUNT=1007;//机器借钱账户

    const SYSTEM_CURRENCY=4;//币种
    const SYSTEM_JOURNAL_TYPE=Star::TYPE_HORSERACING;//帐变类型TRADE_TYPE_GAME_RUN

    const MESSAGE_TYPE_PRIVATE=1;//私信
    const MESSAGE_TYPE_ROOM=2;//聊天室消息

    const MESSATE_EXPIRE=180;//单位秒

    const MESSAGE_TAG_START_GAME=3000;//开始游戏消息,推给虚拟聊天室
    const MESSAGE_TAG_RUN_HORSE=3001;//开始跑马消息，推给虚拟聊天室
    const MESSAGE_TAG_GAME_OVER=3002;//游戏结束消息，三押注最多，三贡献最多，推给直播间
    const MESSAGE_TAG_SETTLE_TO_STAKE=3003;//结算推给押注或坐庄者的私信
    const MESSAGE_TAG_SETTLE_MAX_TO_LIVE=3004;//结算推给直播间的赢钱最多用户信息
    const MESSAGE_TAG_BANKER=3005;//抢庄消息
    const MESSAGE_TAG_SWITCH_GAME=3006;//直播间中途推送消息，使端上切换入口
    const MESSAGE_TAG_OPEN_ROOM=3007;//开启虚拟聊天室
    const MESSAGE_TAG_BANKER_REFUND=3008;//抢庄被顶退钱消息
    const MESSAGE_TAG_STAKE=3009;//押注消息

    const HUGE_AMOUNT_WIN_THREHOLD=300;//1000星钻

    const RT_LOG="runhorse.log";
    static $MSG_LOG="msg.log";


    const ROBOT_BANKER_MIN_RATE=1;//机器人抢庄最低倍率



    private $gameid=0;
    private $roundid=0;
    private $orderid=0;
    private $chatroom=0;
    private $debug=false;
    private $gameConfig=[];
    private $game=null;
    private $winno=0;
    private $split=[];

    public function __construct($debug=false)
    {
        $this->debug=$debug;
        //创建跑马游戏虚拟聊天室
        $this->getVirtualRoomId();
    }


    /**
     * 初始化一局游戏
     */
    public function init()
    {
        $game_dao=new DAOGame();
        $game=$game_dao->getGameById($this->gameid);
        if (!$game) { throw new Exception("get game failed");
        }
        //根据游戏是否显示，判断是否进行下一局
        if ($game['isshow']=="N") {
            throw new Exception("game is down", 1111);
        }

        $this->gameConfig=$game['extends']?json_decode($game['extends'], true):[];
        $this->game=$game;

        $this->roundid=0;
        $this->orderid=0;
        $this->winno=0;
        $this->split=[];

        $this->w(">>>>", "GAMESTART", 1, true);
        $this->w(__METHOD__, $game);
    }

    /**
     * 获取时间轴
     */
    public function getTimeline()
    {
        //时间轴
        $start_time=date('Y-m-d H:i:s', time()+self::BEFORE_BANKER_TIME);
        return $timeline_option=[
            'start_time'=>$start_time,
            'banker_time_span_robot'=>self::BANKER_TIME_SPAN_ROBOT,
            'banker_time_span'=>self::BANKER_TIME_SPAN,
            'banker_to_stake_span'=>self::BANKER_TO_STAKE_SPAN,
            'stake_time_span'=>self::STAKE_TIME_SPAN,
            'stake_to_run_span'=>self::STAKE_TO_RUN_SPAN,
            'run_prepare_span'=>self::RUN_PREPARE_SPAN,
            'run_time_span'=>self::RUN_TIME_SPAN,
            'result_winner_span'=>self::RESULT_WINNER_SPAN,
            'result_reward_top3_span'=>self::RESULT_REWARD_TOP3_SPAN,
        ];
    }

    /**
     * 获取虚拟聊天室id
     */
    private function getVirtualRoomId()
    {
        $game_dao=new DAOGame();
        $game=$game_dao->getGameByType(DAOGame::TYPE_HORSERACING_STAR);
        if (!$game) { throw new Exception("game is not existed");
        }
        $this->gameid=$game['gameid'];

        if (empty($game['chatroomid'])) {
            $this->chatroom= Counter::increase(Counter::COUNTER_TYPE_FEEDS, "idgen");
            $result=$game_dao->updateBaseInfo($this->gameid, $game['name'], $game['icon'], $game['type'], $game['isshow'], $this->chatroom);
            if (!$result) { throw new Exception("update game extends failed");
            }
            //创建跑马游戏虚拟聊天室
            $this->sendMsg($this->chatroom, self::MESSAGE_TYPE_ROOM, 'txt', [], self::MESSAGE_TAG_OPEN_ROOM);
        }else{
            $this->chatroom= $game['chatroomid'];
        }
    }

    /**
     * orderid
     */
    public function getOrderId()
    {
        $account=new Account();
        $this->orderid=$account->getOrderId();
        if (!$this->orderid) { throw new Exception("get orderid failed");
        }
    }

    /**
     * 游戏开始
     */
    public function startGame()
    {
        //检查是否有人直播间开启游戏，否则空转
        /* $gamelive_dao=new DAOGameLive();
         $gamelive=$gamelive_dao->getAllLiveInGame($this->game['gameid']);
         if (!$gamelive)throw new Exception("no live start game");*/

        $extends=$this->gameConfig;
        //庄家配置
        $banker_option=[
            'banker_base'=>$extends['banker_base'],
            'banker_times'=>$extends['banker_times']
        ];
        //押注者配置
        $stake_option=[
            'stake_amount'=>$extends['stake_amount'],
        ];
        //时间轴
        $timeline_option=$this->getTimeline();
        $start_time=$timeline_option['start_time'];
        $mod_time='0000-00-00 00:00:00';
        $end_time='0000-00-00 00:00:00';
        //虚拟聊天室id
        $virtualRoomId=$this->chatroom;
        $options=[
            'banker_option'=>$banker_option,
            'stake_option'=>$stake_option,
            'timeline_option'=>$timeline_option,
            'virtual_room_id'=>$virtualRoomId,
        ];

        //插入场次记录
        $this->getOrderId();
        $round_dao=new DAOHorseracingRoundStar();
        $result=$round_dao->addRound(0, 0, 0, DAOHorseracingRoundStar::GAME_STATUS_BANKER, $this->orderid, $options, $mod_time, $start_time, $end_time);
        if(!$result) { throw new Exception("insert round failed");
        }
        $round=$round_dao->getNewestInfo();
        if (!$round) { throw new Exception("cant find the current round");
        }
        $this->roundid=$round['roundid'];
        $this->setCache();
        $this->w(__METHOD__, ['options'=>$options,'roundid'=>$this->roundid]);
    }


    /**
     * 开始抢庄
     * 如果没有抢庄，则等待下一局开始
     */
    public function startBanker()
    {
        /*//获取所有直播间
        $gamelive_dao=new DAOGameLive();
        $gamelive=$gamelive_dao->getAllLiveInGame($this->game['gameid']);
        if (!$gamelive)throw new Exception("no live start game");*/

        //发送开始游戏信息(开启游戏并且在直播)
        $round_dao=new DAOHorseracingRoundStar();
        $round=$round_dao->getRoundById($this->roundid);
        if (!$round) { throw new Exception(" round may be not set");
        }
        //$live=new DAOLive();
        $options=json_decode($round['extends'], true);

        $text="game start";
        $options['virtual_room_id']=strval($options['virtual_room_id']);
        $options['roundid']=$this->roundid;
        $timeline= $options['timeline_option'];
        $start_time=strtotime($timeline['start_time']);
        $options['timeline_option']['start_time']=$start_time;
        $options['timeline_option']['stake_time']=$start_time+$timeline['banker_time_span']+$timeline['banker_to_stake_span'];
        $options['timeline_option']['run_time']=$start_time+$timeline['banker_time_span']+$timeline['banker_to_stake_span']+$timeline['stake_time_span']+$timeline['stake_to_run_span'];

        $this->sendMsg($this->chatroom, self::MESSAGE_TYPE_ROOM, $text, $options, self::MESSAGE_TAG_START_GAME);

        $this->w(__METHOD__, $options);
    }

    /**
     * 机器人抢庄
     */
    public function robotBanker()
    {
        $cfg=$this->gameConfig;
        $one_time=!empty($cfg['banker_times'])?$cfg['banker_times'][0]:self::ROBOT_BANKER_MIN_RATE;
        $amount=$cfg['banker_base']*$one_time;
        $game=$this->game;
        $gameid=$game['gameid'];
        GameRobotsStar::robotsBanker($gameid, $amount);
        $this->w(__METHOD__, 'robot banker');
    }

    /**
     * 开始押注
     * 如果没有一个人押注，那么，该局游戏，会等到结束，但是不分账。
     */
    public function startStake()
    {
        $round_dao=new DAOHorseracingRoundStar();
        //判断是否有人抢庄
        $round=$round_dao->getRoundById($this->roundid);
        if(!$round) { throw new Exception("no round record");
        }
        if (!$round['bankerid']) { throw new Exception("no one banker");
        }
        $result=$round_dao->updateRound($this->roundid, '', '', '', DAOHorseracingRoundStar::GAME_STATUS_STAKE, '', [], '');
        if(!$result) { throw new Exception("update stake status failed");
        }
        $this->setCache();
        $this->w(__METHOD__, 'stake');
    }

    /**
     * 计算并下发获胜赛道信息，客户端开始跑马，服务端开始结账
     */
    public function startRun()
    {
        //计算获胜赛道
        $winno=$this->getWinTrackNo();
        if (!$winno) { throw new Exception("get win track no failed");
        }
        $this->winno=$winno;
        //计算赛道速度
        $base_speed=rand(10, 20);
        $factor=2;
        $one_speed=$winno==DAOHorseracingRoundStar::TRACK_ONE?$base_speed+$factor:$base_speed-$factor;
        $two_speed=$winno==DAOHorseracingRoundStar::TRACK_TWO?$base_speed+$factor:$base_speed-$factor;
        //设置跑马中状态和赛道号
        $round_dao=new DAOHorseracingRoundStar();
        $round_info=$round_dao->getRoundById($this->roundid);
        if(!$round_info) { throw new Exception("round is not exist");
        }
        $extends=json_decode($round_info['extends'], true);
        $extends['track_option']=[
            'one_speed'=>$one_speed,
            'two_speed'=>$two_speed,
        ];
        $result=$round_dao->updateRound($this->roundid, '', '', $winno, DAOHorseracingRoundStar::GAME_STATUS_RUN, '', $extends, '');
        if (!$result) { throw new Exception('update round winno status failed');
        }
        $this->setCache();

        //下发跑马消息
        $options=[
            'roundid'=>$this->roundid,
            'virtual_room_id'=>strval($this->chatroom),
            'winno'=>$winno,
            'one_speed'=>$one_speed,
            'two_speed'=>$two_speed,
        ];
        $text='run';
        $this->sendMsg($this->chatroom, self::MESSAGE_TYPE_ROOM, $text, $options, self::MESSAGE_TAG_RUN_HORSE);
        $this->w(__METHOD__, 'start ');
    }


    //分账
    public function split()
    {
        //0 判断是否有押注者，如果没有，则不分账，退回抢庄者钻,等待游戏结束，开始下一局

        //1 假设，庄家和买家的钱都到了一个临时账户，被锁定（如果是多个，就增加了复杂度），合计为(N)
        //2 假设，所有押注的买家，都要被分账，无论输赢，但是不包括庄家(S)

        //3 先分系统，(N-S) x 5% = R1

        //4 再分主播，如果主播房间没有人押注，只有人抢庄，则不参与分账。如果有人押注，那么获取所有押注者金额（M1,M2,M3,...）,循环所有有押注的直播间主播（P）个，逐个分账
        // （M1 x 5%）+ (M2 x 5%) + ... + (Mp x 5%) = R2


        //5 然后是押注获胜者（Q）个，假设，押注（K1，K2,K3,k4,...） ，则应分得：(2 x K1 ) x (1 - 5% -5%) + (2 x K2) x (1 - 5% -5%) + ...+ (2 x Kq) x (1 - 5% -5%) =R3，
        //6 押注失败者，则失去所有筹码
        //7 最后，分给坐庄者，N - R1 -R2 -R3

        // 资金流向，from ---> to

        $data['system']=$this->systemSplit();
        $data['anchor']=$this->anchorSplit();
        $data['winer']=$this->stakeSplit();
        $data['banker']=$this->bankerSplit();

        return $data;
    }

    /**
     * 系统分账
     */
    private function systemSplit()
    {
        //计算押注总金额
        $stake_dao=new DAOHorseracingStakeStar();
        $stake_total=$stake_dao->getStakeTotal($this->roundid);
        if (!$stake_total) { throw new Exception("split system failed");
        }

        //$amount=$stake_total['num']*DAOHorseracingRoundStar::GAME_SYSTEM_DIVIDE_REATE;
        $amount=$this->hsystem($stake_total['num']);
        return  [
            'from_uid'=>self::SYSTEM_TMP_ACCOUNT,//系统临时账户,扣减
            'to_uid'=>self::SYSTEM_DIVIDE_ACCOUNT,//增加
            'amount'=>$amount,//金额
            'type'=>DAOHorseracingLogStar::TYPE_SYSTEM,
        ];
    }

    /**
     * 主播分账
     */
    private function anchorSplit()
    {
        //获取所有有人押注的主播
        $stake_dao=new DAOHorseracingStakeStar();
        $stake=$stake_dao->getAllLiveOfRound($this->roundid);

        $anchor=[];
        $live_dao=new DAOLive();
        foreach ($stake as $i){
            $info=$live_dao->getLiveById($i['liveid']);
            if ($info) {
                //$anchor_split=$i['num']*DAOHorseracingRoundStar::GAME_ANCHOR_DIVIDE_RATE;
                $anchor_split=$this->hanchor($i['num']);
                $anchor[]=[
                    'from_uid'=>self::SYSTEM_TMP_ACCOUNT,
                    'to_uid'=>$info['uid'],
                    'amount'=>$anchor_split,
                    'liveid'=>$i['liveid'],
                    'type'=>DAOHorseracingLogStar::TYPE_ANCHOR,
                ];
            }else{
                //没有直播则不分
            }
        }
        return $anchor;
    }

    /**
     * 押注获胜者分账
     */
    private function stakeSplit()
    {
        //买家（押注者获胜者）
        /*  $stake=[];
          $round_dao=new DAOHorseracingRound();
          $round_info=$round_dao->getRoundById($this->roundid);
          if (!$round_info)throw new Exception('round not exists');
          $winno=$round_info['winno'];
          if (!$winno)throw new Exception("win track is not set up");*/
        $stake=[];
        $stake_dao=new DAOHorseracingStakeStar();
        $stake_info=$stake_dao->getAllStakeOfWin($this->roundid, $this->winno);

        foreach ($stake_info as $i){
            //$stake_split=$i['amount']*(1-DAOHorseracingRoundStar::GAME_ANCHOR_DIVIDE_RATE-DAOHorseracingRoundStar::GAME_SYSTEM_DIVIDE_REATE)+$i['amount'];
            $stake_split=$this->hstake($i['amount']);
            $stake[]=[
                'from_uid'=>self::SYSTEM_TMP_ACCOUNT,
                'to_uid'=>$i['uid'],
                'amount'=>$stake_split,
                'liveid'=>$i['liveid'],
                'orginal_amount'=>$i['amount'],
                'type'=>DAOHorseracingLogStar::TYPE_STAKE,
            ];
        }
        return $stake;
    }

    /**
     * 庄家分账
     */
    private function bankerSplit()
    {
        //抢庄金额
        $round_dao=new DAOHorseracingRoundStar();
        $round_info=$round_dao->getRoundById($this->roundid);
        if (!$round_info) { throw new Exception('round not exists');
        }
        if(!$round_info['amount']) { throw new Exception('the round have not banker');
        }
        $banker_amount=$round_info['amount'];

        //押注总金额
        $stake_dao=new DAOHorseracingStakeStar();
        $stake_info=$stake_dao->getStakeTotal($this->roundid);
        if(!$stake_info) { throw new Exception('get stake failed');
        }
        $stake_total_amount=$stake_info['num'];

        //系统分成
        $system_split=$this->systemSplit();
        $system_split_amount=$system_split['amount'];

        //主播分成
        $anchor_split_total=0;
        $anchor_split=$this->anchorSplit();
        foreach ($anchor_split as $i){
            $anchor_split_total+=$i['amount'];
        }

        //押注获胜者分成
        $stake_split_total=0;
        $stake_split=$this->stakeSplit();
        foreach ($stake_split as $i){
            $stake_split_total+=$i['amount'];
        }

        //$banker_split=$banker_amount + $stake_total_amount - $system_split_amount - $anchor_split_total - $stake_split_total;
        $banker_split=$this->hbanker($banker_amount, $stake_total_amount, $system_split_amount, $anchor_split_total, $stake_split_total);
        if($banker_split<0) { throw new Exception("the banker amount is not enough");
        }

        $banker_dao=new DAOHorseracingBankerStar();
        $banker_info=$banker_dao->getLiveOfBanker($this->roundid, $round_info['bankerid']);
        if (!$banker_info) { throw new Exception("get banker failed");
        }

        return[
            'from_uid'=>self::SYSTEM_TMP_ACCOUNT,
            'to_uid'=>$round_info['bankerid'],
            'amount'=>$banker_split,
            'liveid'=>$banker_info['liveid'],
            'orginal_amount'=>$round_info['amount'],
            'type'=>DAOHorseracingLogStar::TYPE_BANKER,
        ];
    }


    /**
     * 结账
     */
    public function settle()
    {

        $this->split=$this->split();
        $split= $this->split;
        $this->w("TTTTT", $split);
        unset($split['anchor']);//主播暂不分账
        $data=[];
        foreach ($split as $k => $v){
            if ($k=='system'||$k=='banker') {
                $data[]=$v;
            }else{
                foreach ($v as $i){
                    $data[]=$i;
                }
            }
        }
        $d=[];
        foreach ($data as $i){
            if (!isset($d[$i['to_uid']])) {
                $d[$i['to_uid']]=$i;
            }else{
                $d[$i['to_uid']]['amount']+=$i['amount'];
            }
        }
        $account_tmp_dao=new DAOAccount(self::SYSTEM_TMP_ACCOUNT);
        $account_tmp_dao->startTrans();
        try{
            foreach ($d as $j){
                if($j['amount']==0) { continue;
                }
                $account_model=new Account();
                $diamond = $account_model->getBalance(self::SYSTEM_TMP_ACCOUNT, self::SYSTEM_CURRENCY);
                Interceptor::ensureNotEmpty($diamond, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
                Interceptor::ensureNotFalse($diamond>=$j['amount'], ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

                $orderid = $this->orderid;
                $extends=array_merge(
                    $j, array(
                    'roundid'=>$this->roundid,
                    'winno'=>$this->winno,
                    'orderid'=>$this->orderid,
                    )
                );
                //$extends=json_encode($extends);

                Interceptor::ensureNotFalse($account_model->decrease($j['from_uid'], self::SYSTEM_JOURNAL_TYPE, $orderid, $j['amount'], self::SYSTEM_CURRENCY, "run horse 扣减临时账户[{$j['from_uid']}]={$j['amount']}星光", $extends),  ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
                Interceptor::ensureNotFalse($account_model->increase($j['to_uid'], self::SYSTEM_JOURNAL_TYPE, $orderid, $j['amount'], self::SYSTEM_CURRENCY, "run horse 增加账户[{$j['to_uid']}]={$j['amount']}星光", $extends), ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
            }
            $account_tmp_dao->commit();
            //$this->orderid=$orderid;
            $this->getGlobalTop3();
        }catch (Exception $e){
            $account_tmp_dao->rollback();
            throw $e;
        }
        $this->w(__METHOD__, $split);
    }

    /**
     * 获取全局top3榜单
     */
    private function getGlobalTop3()
    {
        $stake_dao=new DAOHorseracingStakeStar();
        $stake=$stake_dao->getGlobalTop3($this->roundid, $this->winno);
        $user=new User();
        foreach ($stake as &$i){
            $user_info=$user->getUserInfo($i['uid']);
            $i['nickname']=$user_info['nickname']?$user_info['nickname']:"";
            $i['avatar']=$user_info['avatar']?$user_info['avatar']:"";
            //$i['win_amount']=round($i['num']*(1-DAOHorseracingRoundStar::GAME_ANCHOR_DIVIDE_RATE-DAOHorseracingRoundStar::GAME_SYSTEM_DIVIDE_REATE));
            $i['win_amount']=$this->hstake($i['num'], false);

        }
        $this->w("DDDDDDDDDD::", $stake);
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $key="horseracing_top3_star_".$this->roundid;
        $expire=90;
        $cache->set($key, json_encode($stake));
        $cache->expire($key, $expire);
    }

    /**
     * 结束游戏
     */
    public function gameOver()
    {
        $endtime=date("Y-m-d H:i:s");
        $round_dao=new DAOHorseracingRoundStar();
        $result=$round_dao->updateRound($this->roundid, '', '', '', DAOHorseracingRoundStar::GAME_STATUS_DIVIDED, $this->orderid, [], $endtime);
        if (!$result) { throw new Exception('update round failed');
        }
        $this->setCache();
        //向每一个用户发送游戏输赢及分账私信（押注者和庄家）
        $this->settleMsgToUser();
        //大额获利显示房间消息
        $this->settleMsgToAllLiveOutOfMax();
        //发送分账消息盈利前三
        $this->settleMsgForTop3();
        //记录分账明细
        $this->recordSettle();

        $this->w(__METHOD__, 'over');
        $this->w("<<<<", "GAMEOVER", 1, true);

    }

    /**
     * 判断是否有人押注
     */
    public function checkStakeNum()
    {
        $stake_dao=new DAOHorseracingStakeStar();
        $stake=$stake_dao->getStakeTotal($this->roundid);
        if (!$stake||$stake['num']<=0) { return false;
        }
        return true;
    }

    /**
     * 游戏分账，向每一个参与游戏的用户（押注者，和庄家）推私信
     * 后期可以合并成一条虚拟聊天室消息
     */
    private function settleMsgToUser()
    {
        $stake_dao=new DAOHorseracingStakeStar();
        $winno=$this->winno;

        $stake=$stake_dao->getAllStakerOfRound($this->roundid);
        $data=[];
        foreach ($stake as $i){
            if (!isset($data[$i['uid']])) {
                $data[$i['uid']]=$i;
                //$data[$i['uid']]['win_amount']=$i['trackno']==$winno?$i['amount']+$i['amount']*(1-DAOHorseracingRoundStar::GAME_SYSTEM_DIVIDE_REATE-DAOHorseracingRoundStar::GAME_ANCHOR_DIVIDE_RATE):0;
                $data[$i['uid']]['win_amount']=$i['trackno']==$winno?$this->hstake($i['amount']):0;
                continue;
            }
            $data[$i['uid']]['amount']+=$i['amount'];
            if ($i['trackno']==$winno) {
                //$data[$i['uid']]['win_amount']+=$i['amount']+$i['amount']*(1-DAOHorseracingRoundStar::GAME_SYSTEM_DIVIDE_REATE-DAOHorseracingRoundStar::GAME_ANCHOR_DIVIDE_RATE);
                $data[$i['uid']]['win_amount']+=$this->hstake($i['amount']);
            }
        }
        $this->w("BBBBBBB", $data);

        //坐庄者
        $split=$this->split;
        $banker=$split['banker'];
        $this->w(__METHOD__, $banker);
        if ($banker) {
            $data[$banker['to_uid']]=[
                'uid'=>$banker['to_uid'],
                'amount'=>$banker['orginal_amount'],
                'win_amount'=>$banker['amount'],
            ];
        }

        if ($data) {
            foreach ($data as $k=>$v){
                $diamond=Account::getBalance($k, Account::CURRENCY_COIN);
                $options=[
                    'uid'=>$k,
                    'roundid'=>$this->roundid,
                    'amount'=>$v['amount'],
                    'win_amount'=>$v['win_amount'],
                    'virtual_room_id'=>strval($this->chatroom),
                    'balance'=>isset($diamond)?intval($diamond):0,
                ];
                $this->sendMsg($k, self::MESSAGE_TYPE_PRIVATE, 'stake private msg', $options, self::MESSAGE_TAG_SETTLE_TO_STAKE);
            }
        }

    }

    /**
     * 全站显示赢钱超过1000的用户
     */
    public function settleMsgToAllLiveOutOfMax()
    {
        $stake_dao=new DAOHorseracingStakeStar();
        $largeAmount=self::HUGE_AMOUNT_WIN_THREHOLD;
        if ($this->gameConfig['large_amount']) { $largeAmount=$this->gameConfig['large_amount'];// todo
        }
        $largeAmount=$largeAmount/(1-DAOHorseracingRoundStar::GAME_SYSTEM_DIVIDE_REATE-DAOHorseracingRoundStar::GAME_ANCHOR_DIVIDE_RATE);
        $largeAmount=ceil($largeAmount);
        $stake=$stake_dao->getStakeAmountMax($this->roundid, $this->winno, $largeAmount);
        if($stake) {
            $user=new User();
            foreach ($stake as $i){
                $user_info=$user->getUserInfo($i['uid']);
                if ($user_info) {
                    $options=[
                        'liveid'=>strval($i['liveid']),
                        'gamename'=>$this->game['name'],
                        'roundid'=>$this->roundid,
                        'uid'=>$i['uid'],
                        'nickname'=>$user_info['nickname']?$user_info['nickname']:"",
                        'amount'=>$i['num'],
                        //'win_amount'=>floor($i['num']*(1-DAOHorseracingRoundStar::GAME_SYSTEM_DIVIDE_REATE-DAOHorseracingRoundStar::GAME_ANCHOR_DIVIDE_RATE)),
                        'win_amount'=>$this->hstake($i['num'], false),
                        'virtual_room_id'=>$this->chatroom,
                    ];
                    $this->sendMsg($i['liveid'], self::MESSAGE_TYPE_ROOM, 'max', $options, self::MESSAGE_TAG_SETTLE_MAX_TO_LIVE);
                }

            }
        }
    }


    /**
     * 获取直播间盈利前三发送消息,推送给主播
     */
    private function settleMsgForTop3()
    {
        $stake_dao=new DAOHorseracingStakeStar();
        $stake=$stake_dao->getAllLiveOfRound($this->roundid);
        $live=[];
        if($stake) {
            $user=new User();
            $live_dao=new DAOLive();
            foreach ($stake as $i){
                $contribute3=$stake_dao->getWinTop3($this->roundid, $i['liveid'], $this->winno);
                if (!$contribute3) { continue;
                }
                foreach ($contribute3 as &$n){
                    $user_contribute=$user->getUserInfo($n['uid']);
                    $n['nickname']=$user_contribute['nickname']?$user_contribute['nickname']:"";
                    //$n['win_amount']=round($n['num']*(1-DAOHorseracingRoundStar::GAME_ANCHOR_DIVIDE_RATE-DAOHorseracingRoundStar::GAME_SYSTEM_DIVIDE_REATE));
                    $n['win_amount']=$this->hstake($n['num'], false);
                }
                $live_info=$live_dao->getLiveById($i['liveid']);
                if ($live_info) {
                    $live[$i['liveid']] = [
                        'anchorid'=>$live_info['uid'],
                        'contribution_top_3' => $contribute3,
                    ];
                }
            }
        }

        foreach ($live as $k=>&$v){
            $ext=[
                'liveid'=>strval($k),
                'roundid'=>$this->roundid,
                'virtual_room_id'=>$this->chatroom,
                'contribution_top_3'=>$v['contribution_top_3'],
            ];
            $this->sendMsg($v['anchorid'], self::MESSAGE_TYPE_PRIVATE, 'result', $ext, self::MESSAGE_TAG_GAME_OVER);
        }
        //return $live;
    }

    /**
     * 记录分账详情
     */
    public function recordSettle()
    {
        //向horseracing_log插入结账信息
        $orderid=$this->orderid;
        $roundid=$this->roundid;
        $loseno=$this->winno==DAOHorseracingRoundStar::TRACK_ONE?DAOHorseracingRoundStar::TRACK_TWO:DAOHorseracingRoundStar::TRACK_ONE;
        //失败者
        $stake_dao=new DAOHorseracingStakeStar();
        $stake_loser=$stake_dao->getAllStakeOfWin($roundid, $loseno);

        $log_dao=new DAOHorseracingLogStar();
        $log_dao->startTrans();
        try{
            //loser
            foreach ($stake_loser as $i){
                $log_dao->addLog($roundid, $i['uid'], $orderid, DAOHorseracingLogStar::TYPE_STAKE, $i['liveid'], $i['amount'], 0, $i['trackno']);
            }

            $split=$this->split;
            //system
            $system=$split['system'];
            $log_dao->addLog($roundid, self::SYSTEM_DIVIDE_ACCOUNT, $orderid, DAOHorseracingLogStar::TYPE_SYSTEM, 0, 0, $system['amount']);
            //anchor
            $anchors=$split['anchor'];
            foreach ($anchors as $i){
                $log_dao->addLog($roundid, $i['to_uid'], $orderid, DAOHorseracingLogStar::TYPE_ANCHOR, $i['liveid'], 0, $i['amount'], 0, "N");
            }
            //winer
            $winer=$split['winer'];
            foreach ($winer as $i){
                $log_dao->addLog($roundid, $i['to_uid'], $orderid, DAOHorseracingLogStar::TYPE_STAKE, $i['liveid'], $i['orginal_amount'], $i['amount'], $this->winno);
            }
            //banker
            $banker=$split['banker'];
            $log_dao->addLog($roundid, $banker['to_uid'], $orderid, DAOHorseracingLogStar::TYPE_BANKER, $banker['liveid'], $banker['orginal_amount'], $banker['amount']);

            $log_dao->commit();
        }catch (Exception $e){
            $log_dao->rollback();
            throw  $e;
        }
    }

    //计算获胜赛道算法2
    private function getWinTrackNo()
    {
        $extends=$this->gameConfig;

        //获取庄家信息（是否是运营账号或机器人坐庄）
        $round_dao=new DAOHorseracingRoundStar();
        $round=$round_dao->getRoundById($this->roundid);
        if (empty($round)) { throw new Exception("get round failed");
        }
        $banker=$round['bankerid'];
        //押注最多的胜率
        $odds=[];
        if ($this->getOperateAccount($banker)) {//运营账号庄
            $odds=[
                'min'=>$extends['machine_odds_min'],
                'max'=>$extends['machine_odds_max']
            ];
        }else{
            $odds=[
                'min'=>$extends['user_odds_min'],
                'max'=>$extends['user_odds_max']
            ];
        }

        //获取押注最多的赛道
        $stake_dao=new DAOHorseracingStakeStar();
        $stake_info=$stake_dao->getMostAmountTrack($this->roundid);
        //if (empty($stake_info))throw new Exception('get most amount track failed');
        $most_amount_track=!empty($stake_info)?$stake_info['trackno']:rand(1, 2);

        if (!intval($odds['min'])||!intval($odds['max'])) { throw new Exception('min max must be int');
        }
        if($odds['min']<=0||$odds['min']>100||$odds['max']<=0||$odds['max']>100) { throw new Exception('min max must be between 0 and 100');
        }

        $rate=rand($odds['min'], $odds['max']);
        $keys=range(1, 100);
        $init_track=$most_amount_track==DAOHorseracingRoundStar::TRACK_ONE?DAOHorseracingRoundStar::TRACK_TWO:DAOHorseracingRoundStar::TRACK_ONE;
        $pad_track=$init_track==DAOHorseracingRoundStar::TRACK_ONE?DAOHorseracingRoundStar::TRACK_TWO:DAOHorseracingRoundStar::TRACK_ONE;
        $values=array_fill(0, 100, $init_track);
        $game=array_combine($keys, $values);
        for ($i=1;$i<=$rate;$i++){
            $game[$i]=$pad_track;
        }
        $game_keys=array_keys($game);
        $game_values=array_values($game);
        shuffle($game_values);
        $game_shuffle=array_combine($game_keys, $game_values);
        $index=array_rand($game_shuffle, 1);
        return $game_shuffle[$index];
    }


    //获取运营账号
    private function getOperateAccount($bankerid)
    {
        $operation_dao=new DAOGameOperationStar();
        $operation=$operation_dao->isRunAccount($bankerid);
        if (!$operation) { return false;
        }
        return true;
    }


    public static function sendMsg($to,$type,$text,array $extends,$tag)
    {
        $wrappers = array(
            "userid" => $to,
            "type"=>$tag,
            "text" => $text,
            "time" => time(),
            "expire" => self::MESSATE_EXPIRE,
            "extra" => $extends
        );

        $wrapper = array();
        $traceid = md5(serialize($wrappers));

        $wrappers["traceid"] = $traceid;
        $wrapper['content'] = $wrappers;


        include_once 'message_client/RongCloudClient.php';
        $rongcloud_client = new RongCloudClient();
        $result=null;
        if ($type==self::MESSAGE_TYPE_PRIVATE) {
            $result=$rongcloud_client->sendPrivateMessage($to, self::SYSTEM_TMP_ACCOUNT, json_encode($wrapper), $text, $type);
            if(!$result) { $result=$rongcloud_client->sendPrivateMessage($to, self::SYSTEM_TMP_ACCOUNT, json_encode($wrapper), $text, $type);
            }
        }elseif ($type==self::MESSAGE_TYPE_ROOM) {
            $result=$rongcloud_client->sendChatRoomMessage($to, self::SYSTEM_TMP_ACCOUNT, json_encode($wrapper), 'RC:TxtMsg');
            if (!$result) { $result=$rongcloud_client->sendChatRoomMessage($to, self::SYSTEM_TMP_ACCOUNT, json_encode($wrapper), 'RC:TxtMsg');
            }
        }
        
        ChatMsgCount::count($to, $tag);
        
        $trace=debug_backtrace();
        if (isset($trace[1]['class'])&&$trace[1]['class']!=__CLASS__) { return;
        }

        $log_name=static::getLogDir().self::$MSG_LOG.".".date("Ymd");
        if (!file_exists($log_name)) {
            touch($log_name);
            chmod($log_name, 0666);
        }
        // $log_name=__DIR__.self::$MSG_LOG.".".date("Ymd");
        $log_str="\n\n".__METHOD__.":::".$result.":::".json_encode(get_defined_vars()) ."\n\n";
        file_put_contents($log_name, $log_str, FILE_APPEND);

    }

    /**
     * 运行时日志
     */
    public function w($tag,$msg,$level=1,$line=false)
    {
        if (!$line) {
            $content=json_encode(
                [
                'TAG'=>$tag,
                'MSG'=>$msg,
                'LEVEL'=>$level,
                'DATE'=>date("Y-m-d H:i:s"),
                ]
            );
        }else{
            $content="\n\n\n====================================";
            $content.=$tag.":::".$msg.":::".$tag;
            $content.="===================================\n\n\n";
        }


        $log_name=static::getLogDir().self::RT_LOG.".".date('Ymd');
        if (!file_exists($log_name)) {
            touch($log_name);
            chmod($log_name, 0666);
        }
        file_put_contents($log_name, $content."\n\n", FILE_APPEND);
        //print_r($log_name);exit;

    }

    //系统分账帮助函数
    private function hsystem($a)
    {
        return floor(bcmul($a, DAOHorseracingRoundStar::GAME_SYSTEM_DIVIDE_REATE, 2));
    }

    //主播分账帮助函数
    private function hanchor($a)
    {
        return floor(bcmul($a, DAOHorseracingRoundStar::GAME_ANCHOR_DIVIDE_RATE, 2));
    }
    //压住分账帮助函数
    private function hstake($a,$f=true)
    {
        $r=bcsub(1, DAOHorseracingRoundStar::GAME_ANCHOR_DIVIDE_RATE, 2);
        $r=bcsub($r, DAOHorseracingRoundStar::GAME_SYSTEM_DIVIDE_REATE, 2);
        $r=bcmul($r, $a, 2);
        if ($f==true) {
            $r=bcadd($r, $a, 2);
        }
        return floor($r);
    }
    //庄家分账帮助函数
    private function hbanker($banker,$stake_total,$system,$anchor,$stake_win)
    {
        $r=bcadd($banker, $stake_total, 2);
        $r=bcsub($r, $system, 2);
        $r=bcsub($r, $anchor, 2);
        $r=bcsub($r, $stake_win, 2);
        return floor($r);
    }

    //写缓存
    public  function setCache()
    {
        try{
            $pref_key='horseracing_round_';
            $cache = Cache::getInstance("REDIS_CONF_CACHE");
            if (!$this->roundid) { throw new Exception("roundid null");
            }
            if (!$this->gameid) { throw new Exception("gameid null");
            }
            $key=$pref_key.$this->gameid;
            $round_dao=new DAOHorseracingRoundStar();
            $round=$round_dao->getRoundById($this->roundid);
            if (!$round) { throw new Exception("round null");
            }
            if (!$cache->set($key, json_encode($round), 300)) { throw new Exception("set cache fail");
            }
        }catch (Exception $e){
            $this->w(__METHOD__, $e);
        }

    }

    //获取log路径
    public static function getLogDir()
    {
        return '/home/dream/codebase/service/src/application/process/game/horseracingstar/log/';
    }
}