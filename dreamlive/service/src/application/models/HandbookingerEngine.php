<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/10
 * Time: 10:26
 */
class HandbookingerEngine
{
    const SYSTEM_ACCOUNT=9000;
    const SYSTEM_JOURNAL=100;

    const MSG_TYPE_OPEN_ROOM=2100;
    const MSG_TYPE_GAME_START=2101;
    const MSG_TYPE_STAKE_START=2102;
    const MSG_TYPE_STAKE_END=2103;
    const MSG_TYPE_RUN_START=2104;
    const MSG_TYPE_GAME_OVER=2105;

    const MESSATE_EXPIRE=180;//单位秒

    const GAME_VIDEO_CREATE_URL="http://192.168.1.222/newRound?";

    const SETTLE_RATE=0.1;

    private $game=null;
    private $roomid=0;
    private $orderid=0;
    private $config=null;
    private $roundid=0;
    private $horses=null;
    private $timeline=null;
    private $failed=false;
    
    public function __construct()
    {
        $game=Game::getHandbookingerGame();
        if (!$game) { throw new Exception('game is null');
        }
        $this->roomid=$game['gameid'];

        // 初始化虚拟聊天室
        if (empty($game['chatroomid'])) {
            $this->roomid= Counter::increase(Counter::COUNTER_TYPE_FEEDS, "idgen");
            $result=Game::updateRoomid($game['gameid'], $this->roomid);
            if (!$result) { throw new Exception("update game roomid failed");
            }
            //创建跑马游戏虚拟聊天室
            $this->sendMsg(self::MSG_TYPE_OPEN_ROOM, 'open room');
        }else{
            $this->roomid= $game['chatroomid'];
        }
        $this->log('construct');
    }

    // 初始化
    public function init()
    {
        try{
            $game=Game::getHandbookingerGame();
            if (!$game) { throw new Exception('game is null');
            }
            if ($game['isshow']=="N") {
                // throw new Exception("game is down");
            }
            $this->game=$game;
            $this->orderid=Account::getOrderId();
            $ext=json_decode($game['extends'], true);
            $this->config=$ext?$ext:[];
            $this->roundid=0;
            $this->horses=[];
            $this->timeline=[];
            $this->log('init');
        }catch (Exception $e){
            self::err($e);
            $this->failed=true;
        }
    }

    // 开启一场比赛
    public function startGame()
    {
        sleep(30);
        if ($this->failed) { return;
        }
        try{
            $cfg=$this->config;
            $stake_list=isset($cfg['stake_list'])?$cfg['stake_list']:[1=>1,2=>1,5=>1];
            $stake_base=isset($cfg['stake_base'])?$cfg['stake_base']:100;
            $stake_time=isset($cfg['stake_time'])?$cfg['stake_time']:2*60;
            $run_time=isset($cfg['run_time'])?$cfg['run_time']:2*60;
            $now=time();
            $timeline=[
                'game_start'=>$now,
                'stake_start'=>$now+60,
                'stake_end'=>$now+$stake_time+60,
                'run_start'=>$now+$stake_time+60+10,
                'run_end'=>$now+$stake_time+70+$run_time
            ];
            $this->timeline=$timeline;
            $ext=['stake_list'=>$stake_list,'stake_base'=>$stake_base,'stake_rate'=>0.1,
                'stake_time'=>$stake_time,'run_time'=>$run_time, 'timeline'=>$timeline];
            Handbookinger::createNewRound($this->orderid, $ext);
            $round=Handbookinger::curRound();
            if (!$round||$round['status']!=DAOHandbookingerRound::STATUS_PREPARE) { throw new Exception("create new round failed");
            }
            $this->roundid=$round['roundid'];
            $this->sendMsg(self::MSG_TYPE_GAME_START, 'game start', []);
            $this->log(['roundid'=>$this->roundid]);
        }catch (Exception $e){
            $this->err($e);
            $this->failed=true;
        }

    }

    // 押注
    public function stakeStart()
    {
        sleep(abs($this->timeline['stake_start']-time()));
        if ($this->failed) { return;
        }
        try{
            $round=Handbookinger::idRound($this->roundid);
            if ($round['status']!=DAOHandbookingerRound::STATUS_PREPARE) { throw new Exception("start stake failed");
            }
            Handbookinger::updateRound($round['roundid'], DAOHandbookingerRound::STATUS_STAKE);
            $this->sendMsg(self::MSG_TYPE_STAKE_START, 'stake start', []);
            $this->log('stakeStart');
        }catch (Exception $e){
            $this->err($e);
            $this->failed=true;
        }

    }
    public function stakeEnd()
    {
        sleep(abs($this->timeline['stake_end']-time()));
        if ($this->failed) { return;
        }
        try{
            $round=Handbookinger::idRound($this->roundid);
            if ($round['status']!=DAOHandbookingerRound::STATUS_STAKE) { throw new Exception("stake end failed");
            }

            $this->getWinHorse();
            $this->createGameVedio();

            Handbookinger::updateRound($round['roundid'], DAOHandbookingerRound::STATUS_VIDEO);
            $this->sendMsg(self::MSG_TYPE_STAKE_END, 'stake end', []);
            $this->log('stakeEnd');
        }catch (Exception $e){
            $this->err($e);
            $this->failed=true;
        }

    }
    // 准备
    // 跑马
    public function runStart()
    {
        if ($this->failed) { return;
        }
        sleep(abs($this->timeline['run_start']-time()));

        try{
            $round=Handbookinger::idRound($this->roundid);
            if ($round['status']!=DAOHandbookingerRound::STATUS_VIDEO) { throw new Exception("start run failed");
            }
            Handbookinger::updateRound($round['roundid'], DAOHandbookingerRound::STATUS_RUN);
            $this->sendMsg(self::MSG_TYPE_RUN_START, 'run', []);
            $this->log('runStart');
        }catch (Exception $e){
            $this->err($e);
            $this->failed=true;
        }

    }

    // 比赛结束，并带有比赛结果消息
    public function runEnd()
    {
        if ($this->failed) { return;
        }
        sleep(abs($this->timeline['run_end']-time()));
        try{
            $round=Handbookinger::idRound($this->roundid);
            if ($round['status']!=DAOHandbookingerRound::STATUS_RUN) { throw new Exception("settle failed");
            }
            $this->settle();
            Handbookinger::updateRound($round['roundid'], DAOHandbookingerRound::STATUS_SETTLE);
            sleep(30);
            $this->sendMsg(self::MSG_TYPE_GAME_OVER, 'run end', []);// 可以带着排行榜5
            $this->log('runEnd');
        }catch (Exception $e){
            $this->err($e);
            $this->failed=true;
        }

    }

    // 结算
    public function settle()
    {
        $rate=isset($this->config['stake_rate'])?$this->config['stake_rate']:0;
        $t=[];
        $horses=$this->horses;
        $win=$horses[0];
        $cfg=$this->config;
        $base=isset($cfg['stake_base'])?$cfg['stake_base']:100;
        $list=!empty($cfg['stake_list'])?$cfg['stake_list']:[1=>1,2=>1,5=>1];
        $stakes=Handbookinger::allStake($this->roundid);
        $account_dao=new DAOAccount(self::SYSTEM_ACCOUNT);
        $account_dao->startTrans();
        try{
            foreach ($stakes as $j){
                // if($j['amount']==0)continue;
                $t[]=$j;
                $t['result_amount']=0;
                $t['status']="Y";
                if ($j['trackno']!=$win) { continue;
                }
                $times=intval($j['amount']/$base);
                $afterAmount=intval($j['amount']*$rate);
                $amount=isset($list[$times])?intval($list[$times]*$afterAmount+$afterAmount):intval(2*$afterAmount);

                $account_model=new Account();
                $diamond = $account_model->getBalance(self::SYSTEM_ACCOUNT, Account::CURRENCY_DIAMOND);
                Interceptor::ensureNotEmpty($diamond, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
                Interceptor::ensureNotFalse($diamond>=$amount, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

                $orderid = $this->orderid;
                $extends=$j;
                //$extends=json_encode($extends);
                Interceptor::ensureNotFalse($account_model->decrease(self::SYSTEM_ACCOUNT, self::SYSTEM_JOURNAL, $orderid, $amount,  Account::CURRENCY_DIAMOND, "handbookinger 扣减临时账户[".self::SYSTEM_ACCOUNT."]={$amount}钻", $extends),  ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
                Interceptor::ensureNotFalse($account_model->increase($j['uid'], self::SYSTEM_JOURNAL, $orderid, $amount,  Account::CURRENCY_DIAMOND, "handbookinger 增加账户[{$j['uid']}]={$amount}钻", $extends), ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
                $t['result_amount']=$amount;

            }
            $account_dao->commit();
            $this->settleLog($t);
        }catch (Exception $e){
            $account_dao->rollback();
            throw $e;
        }
        $this->log($stakes);
    }
    public function settleLog($d)
    {
        $dao=new DAOHandbookingerLog();
        $dao->startTrans();
        try{
            foreach ($d as $i){
                $dao->record($this->roundid, $i['orderid'], $i['uid'], $i['amount'], $i['result_amount'], $i['trackno'], $i['status'], []);
            }
            $dao->commit();
        }catch (Exception $e){
            $dao->rollback();
            throw $e;
        }
        $this->log('settleLog');
    }

    // 计算获胜赛马
    public function getWinHorse()
    {
        $result=[];
        $horses=Handbookinger::HORSES;
        $stakeInfos=Handbookinger::leastHorseStake($this->roundid);
        $n=count($stakeInfos);
        if ($n<=1) {
            shuffle($horses);
            $result=$horses;
        }
        if ($n>=2) {
            $first=$stakeInfos[0]['trackno'];
            $tmp=array_filter(
                $horses, function ($v) use ($first) {
                    return $v==$first?false:true;
                }
            );
            shuffle($tmp);
            array_unshift($tmp, $first);
            $result=$tmp;
        }
        // return $result;
        $this->horses=$result;
        $this->log(['result'=>$result]);
    }

    // 生成赛马视频并上传的CDN
    public function createGameVedio()
    {
        $now=time();
        $params=[
            'id'=>$now,
            'series'=>$this->roundid,
            'playtime'=>date('Y-m-d H:i:s', $now),
            //'date'=>date('Y-m-d H:i:s',$now),
            //'starttime'=>10.0,
            //'length'=>500.0,
            //'init'=>implode(':',Handbookinger::HORSES ),
            'result'=>implode(' ', $this->horses),
            //'state'=>'ready',
        ];
        // 发起请求更新赛马server的文件
        $result=Util::myCurl(self::GAME_VIDEO_CREATE_URL, [], $params);
        $this->log($result);
        if (!$result) { throw new Exception("create video fail");
        }
        $re=json_decode($result, true);
        if (!$re) { throw new Exception("create video fail ,json");
        }
        $re=$re[0];
        if ($re['errcode']!=0||empty($re['data']['url'])) { throw new  Exception("create video fail,errcode");
        }
        //sleep(3*60);
        // 如果成功了回调，并修改round，url字段
        // 再sleep一段时间后，循环查询数据库的该字段，循环超过3次，任务失败。
        /*$times=3;
        do{
            sleep(1*60);
            #do
            $round=Handbookinger::idRound($this->roundid);
            if ($round['status']==DAOHandbookingerRound::STATUS_VIDEO&&$round['url']!=''){
                break;
            }
            $times--;
        }while($times>0);*/
        Handbookinger::updateRound($this->roundid, 0, 0, $re['data']['url']);
        Handbookinger::updateExtRound($this->roundid, ['game_params'=>$params,]);
        $this->log($re);
    }

    // 消息分发
    public  function sendMsg($type,$text='',array $extends=array())
    {
        $extends['roundid']=$this->roomid;
        $extends['roomid']=$this->roomid;
        $wrappers = array(
            "userid" => self::SYSTEM_ACCOUNT,
            "type"=>$type,
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

        $result=$rongcloud_client->sendChatRoomMessage($this->roomid, self::SYSTEM_ACCOUNT, json_encode($wrapper), 'RC:TxtMsg');
        if (!$result) { $result=$rongcloud_client->sendChatRoomMessage($this->roomid, self::SYSTEM_ACCOUNT, json_encode($wrapper), 'RC:TxtMsg');
        }

        $log_name="/tmp/handbookinger-msg".date('Ymd').".log";
        if (!file_exists($log_name)) {
            touch($log_name);
            // chmod($log_name, 0666);
        }
        $log_str="\nMSG".":::".$result.":::".json_encode($wrappers) ."\n\n";
        file_put_contents($log_name, $log_str, FILE_APPEND);

    }

    public function log($msg)
    {
        $d=[
            'time'=>date('Y/m/d/H:i:s'),
            'msg'=>$msg,
            'p'
        ];
        try{
            $trace=debug_backtrace();
            $d['fun']=$trace[1]['function'];
            $d['line']=$trace[1]['line'];

            $f='/tmp/handbookinger'.date('Ymd').'.log';
            if (!file_exists($f)) {
                touch($f);
            }
            $str="\n".json_encode($d)."\n\n";
            file_put_contents($f, $str, FILE_APPEND);
        }catch (Exception $e){
            throw $e;
        }

    }

    public function err($e)
    {
        $d=[
            'time'=>date("Y/m/d/H:i:s"),
            'e'=>$e,
            'type'=>'err'
        ];

        $d=json_encode($d);
        $d="\n\n".$d."\n\n";
        print_r($d);
    }
}