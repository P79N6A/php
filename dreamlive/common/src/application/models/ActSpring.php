<?php
/*
// 0,30 12-19 * * * php /home/dream/codebase/service/src/application/process/crontab_activity_christmas.php 3
// 0,10,20,30,40,50 20-23,0 * * * php /home/dream/codebase/service/src/application/process/crontab_activity_christmas.php 3

10,20,30,40,50,59 12-17 * * * php /home/dream/codebase/service/src/application/process/crontab_activity_christmas.php 3
5,10,15,20,25,30,35,40,45,50,55,59 18-23,0 * * * php /home/dream/codebase/service/src/application/process/crontab_activity_christmas.php 3


* * * * * php /home/dream/codebase/service/src/application/process/crontab_activity_christmas.php 1
* * * * * php /home/dream/codebase/service/src/application/process/crontab_activity_christmas.php 2


 */
class ActSpring
{
    const UTYPE_ANCHOR=1;
    const UTYPE_RICH_MAN=2;

    const ACT_STATE_NOT_BEGIN=1;
    const ACT_STATE_IN_RUNNING=2;
    const ACT_STATE_IN_TMP_STOP=3;
    const ACT_STATE_OVER=4;

    const CRONTAB_TASK_TYPE_REALTIME=1;
    const CRONTAB_TASK_TYPE_TOTAL=2;
    const CRONTAB_TASK_TYPE_ACT=3;


    const TIME_SPAN_FREE='free';
    const TIME_SPAN_BUSY='busy';
    const TIME_SPAN_STOP='stop';

    public static function getInfo($uid,$stage=0)
    {
        if ($stage==0) {
            $c=self::getStageConfig();
            $stage=$c['stage'];
        }else{
            $cc=self::springActConfig();
            $allowStages=array_keys($cc);
            Interceptor::ensureNotFalse(in_array($stage, $allowStages), ERROR_CUSTOM, 'stage not in 0123');
        }

        $realtimeRank=self::getRealTimeRank($uid, $stage);
        $totalRank=self::getTotalRank($uid, $stage);
        $actState=self::getActState();
        $timeSpan=self::getTimeSpan();
        $timeSpan['stime']=strtotime($timeSpan['stime']);
        $timeSpan['etime']=strtotime($timeSpan['etime']);

        return array(
            'realtimeRank'=>$realtimeRank,
            'totalRank'=>$totalRank,
            'actState'=>$actState,
            'timeSpan'=>$timeSpan,
            'stage'=>$timeSpan['stage'],
        );
    }

    public static function statistics($task)
    {
        switch ($task){
        case self::CRONTAB_TASK_TYPE_REALTIME:
            self::genRealTimeRank();
            break;
        case self::CRONTAB_TASK_TYPE_TOTAL:
            self::genTotalRank();
            break;
        case self::CRONTAB_TASK_TYPE_ACT:
            self::runActTask();
            break;
        default:
            break;
        }
    }
    //=====================================================================
    //* * * * * php /home/dream/codebase/service/src/application/process/crontab_activity_christmas_realtime_rank.sh
    public static function genRealTimeRank()
    {
        $step=5;
        $end=56;
        $c=self::getStageConfig();
        $cache=Cache::getInstance("REDIS_CONF_CACHE");
        $key=$c['real_time_rank_key'];

        for($i=0;$i<$end;$i+=$step){
            $actState=self::getActState();
            $t=array('anchorRank'=>array(),'richManRank'=>array());
            if ($actState==self::ACT_STATE_IN_RUNNING) {
                $re=self::makeRank();
                //if (empty($re['anchorRank'])||empty($re['richManRank']))return;
                $t['anchorRank']=self::getExt($re['anchorRank']);
                $t['richManRank']=self::getExt($re['richManRank']);
            }
            $cache->set($key, json_encode($t));
            sleep($step-1);
        }
    }

    //0,10,20,30,40,50 * * * * php /home/dream/codebase/service/src/application/process/crontab_activity_christmas.php 2
    public static function genTotalRank()
    {
        $c=self::getStageConfig();
        if ($c['stage']==0) {
            $info=array(
                'anchorTotalRank'=>array(),
                'richmanTotalRank'=>array(),
            );
        }else{
            $daoActChristmasRankTotal=new DAOActChristmasRankTotal();
            $re=$daoActChristmasRankTotal->getTotalRank($c['total_rank_num'], $c['stage']);
            $info=array(
                'anchorTotalRank'=>self::getExt($re['anchorTotalRank']),
                'richmanTotalRank'=>self::getExt($re['richmanTotalRank']),
            );
        }
        $cache=Cache::getInstance("REDIS_CONF_CACHE");
        $key=$c['total_rank_key'];

        $cache->set($key, json_encode($info));
    }

    public static function makeRank()
    {
        $date=self::getTimeSpan();
        $stime=isset($date['stime'])?$date['stime']:"";
        $etime=isset($date['etime'])?$date['etime']:"";
        $info=array('stime'=>$stime,
            'etime'=>$etime,
            'anchorRank'=>null,
            'richManRank'=>null);
        if ($stime&&$etime) {
            $c=self::getStageConfig();

            $gifts = self::getActGifts();
            if (empty($gifts)) { return;
            }
            $daoGiftLog = new DAOGiftLog();

            $blackList=self::getBlackList();

            $anchorRank = $daoGiftLog->getGiftAmountTopN($gifts, $stime, $etime, "R", $c['real_time_rank_num'], $blackList);
            $richManRank = $daoGiftLog->getGiftAmountTopN($gifts, $stime, $etime, "S", $c['real_time_rank_num'], $blackList);
            $info['anchorRank']=$anchorRank;
            $info['richManRank']=$richManRank;
        }
        return $info;
    }

    public static function actBusinessLogic($info)
    {
        if (empty($info['anchorRank'])||empty($info['richManRank'])) { return;
        }
        $stime=$info['stime'];
        $etime=$info['etime'];
        $anchorRank=$info['anchorRank'];
        $richmanRank=$info['richManRank'];
        $c=self::getStageConfig();
        if (empty($c)) { return;
        }
        $actid=$c['stage'];
        $which=self::inWhichTime();
        $score=1;
        if ($which==self::TIME_SPAN_BUSY) {
            $score=2;
        }

        $daoActChristmas=new DAOActChristmas();
        $daoActChristmasLog=new DAOActChristmasLog();
        $daoActChristmasRank=new DAOActChristmasRank();
        $daoActChristmasRankTotal=new DAOActChristmasRankTotal();
        $daoActChristmas->startTrans();
        try{
            $roundid=$daoActChristmas->add($stime, $etime, $actid);
            if (!$roundid) { throw new Exception("add round fail");
            }
            //主播
            foreach ($anchorRank as $i){
                $daoActChristmasRank->add($roundid, $i['uid'], self::UTYPE_ANCHOR, $i['total'], $i['rank'], $actid);
            }
            $anchorTopOne=$anchorRank[0];
            $daoActChristmasRankTotal->setRank($anchorTopOne['uid'], self::UTYPE_ANCHOR, $actid, $score);
            $daoActChristmasLog->add($roundid, DAOActChristmasLog::TYPE_WISH, $anchorTopOne['uid'], self::UTYPE_ANCHOR, $actid);
            $daoActChristmasLog->add($roundid, DAOActChristmasLog::TYPE_RED_PACKET, $anchorTopOne['uid'], self::UTYPE_ANCHOR, $actid);
            //土豪
            foreach ($richmanRank as $i){
                $daoActChristmasRank->add($roundid, $i['uid'], self::UTYPE_RICH_MAN, $i['total'], $i['rank'], $actid);
            }
            $richmanTopOne=$richmanRank[0];
            $daoActChristmasRankTotal->setRank($richmanTopOne['uid'], self::UTYPE_RICH_MAN, $actid, $score);
            //$daoActChristmasLog->add($roundid,DAOActChristmasLog::TYPE_LOTTO_TICKET ,$richmanTopOne['uid'] ,self::UTYPE_RICH_MAN ,$actid);
            //$daoActChristmasLog->add($roundid,DAOActChristmasLog::TYPE_RIDE ,$richmanTopOne['uid'] ,self::UTYPE_RICH_MAN ,$actid);

            $daoActChristmas->commit();
        }catch (Exception $e){
            $daoActChristmas->rollback();
            throw $e;
        }
    }

    //0,30 12-19 * * * php /home/dream/codebase/service/src/application/process/crontab_activity_christmas.php 3
    //0,10,20,30,40,50 20-23,0-4 * * * php /home/dream/codebase/service/src/application/process/crontab_activity_christmas.php 3
    public static function runActTask()
    {
        try{
            $actState=self::getActState();
            if ($actState==self::ACT_STATE_IN_RUNNING) {
                self::mySleep();
                $re=self::makeRank();
                if (empty($re)) { return;
                }
                if (empty($re['anchorRank'])||empty($re['richManRank'])) { return;
                }
                $topOneAnchor=$re['anchorRank'][0];
                $topOneRichman=$re['richManRank'][0];
                if (!$topOneAnchor||!$topOneRichman) { return;
                }
                $anchorid=$topOneAnchor['uid'];
                $richmanid=$topOneRichman['uid'];
                self::actBusinessLogic($re);
                //不在直播状态，
                if ($anchorid) {//主播端
                    $liveid=self::getCurLiveIdByAnchorid($topOneAnchor['uid']);
                    if ($liveid) {
                        //进入直播间
                        self::joinRoom($anchorid, $liveid);
                        //送福袋
                        self::sendWishBag($anchorid, $liveid);
                        //发红包
                        self::sendRedPacket($liveid);
                        //发跑道消息
                        self::sendTrackMsg($richmanid, $anchorid);
                    }
                }

            }
        }catch (Exception $e){
            self::log(__FUNCTION__, array(), $e);
        }
    }

    private static function mySleep()
    {
        $now=time();
        $t=date("i:s", $now);
        list($i,$s)=explode(":", $t);
        $i=intval($i);
        $s=intval($s);
        if ($i==59&&$s<58) {
            sleep(abs(60-$s-1));
        }
    }

    public static function getCurLiveIdByAnchorid($anchorid)
    {
        $daoLive=new DAOLive();
        $r=$daoLive->getUserActiveLives($anchorid);
        if (isset($r[0])) {
            return $r[0]['liveid'];
        }
        return 0;
    }
    public static function getTimeSpan()
    {
        $now=time();
        $today=date("Y-m-d", $now);

        $c=self::springActConfig();
        $cfg=self::getStageConfig();

        $stime=$etime="";
        $state=self::getActState();
        if ($state==self::ACT_STATE_NOT_BEGIN||$state==self::ACT_STATE_OVER) {
            $stime= $c[1]['stime'];
            $etime= $c[3]['etime'];
        }elseif ($state==self::ACT_STATE_IN_TMP_STOP) {
            list($stime,$etime)=explode('-', $cfg['stop_time'][0]);
            $stime=$today." ".$stime;
            $etime=$today." ".$etime;
        }else{//runing time
            $m=date("i", $now);
            $m=intval($m);
            $s=date("s", $now);
            $s=intval($s);

            $freq=$cfg['free_freq'];
            $isInterval=false;

            $which=self::inWhichTime();
            if ($which==self::TIME_SPAN_FREE) {
                $freq=$cfg['free_freq'];
            }elseif ($which==self::TIME_SPAN_BUSY) {
                $freq=$cfg['busy_freq'];
            }

            $t_m=intval($m/$freq);
            $isInterval=($m%$freq)==0&&$s<=2?true:false;
            $stime=date("Y-m-d H", $now).":".($t_m==0?"00":$t_m*$freq).":00";
            $stime=$isInterval?date("Y-m-d H:i:s", (strtotime($stime)-$freq*60)):$stime;
            $etime=date("Y-m-d H:i:s", strtotime($stime)+$freq*60);
        }

        return array(
            'stime'=>$stime,
            'etime'=>$etime,
            'now'=>$now,
            'stage'=>isset($cfg['stage'])?$cfg['stage']:0,
        );

    }

    public static function getActState()
    {
        //$cfg=self::springActConfig(); 注意
        $cfg=self::getStageConfig();
        if (empty($cfg)) { return 0;
        }
        $now=time();
        $stime=strtotime($cfg['stime']);
        $etime=strtotime($cfg['etime']);
        if ($now>=$stime&&$now<=$etime) {
            $which=self::inWhichTime();
            if ($which==self::TIME_SPAN_STOP) {
                return self::ACT_STATE_IN_TMP_STOP;
            }else{
                return self::ACT_STATE_IN_RUNNING;
            }
        }else if ($now<$stime) {
            return self::ACT_STATE_NOT_BEGIN;
        }else if ($now>$etime) {
            return self::ACT_STATE_OVER;
        }else{
            return 0;//未知
        }
    }

    public static function getActGifts()
    {
        //return array(3869,3862,3863,3864,3865,3866,3868,3859,3870,3867,3872,3871,);
        $c=self::getStageConfig();
        if (!empty($c)) { return $c['gifts'];
        }
        return array();
    }

    public static function getRealTimeRank($loginUid,$stage=0)
    {
        $result=array(
            "anchorRank"=>[],
            "richManRank"=>[],
        );
        $c=self::getStageConfig();
        if ($c['stage']!=$stage) {
            return $result;
        }
        $state=self::getActState();
        if ($state==self::ACT_STATE_IN_TMP_STOP||$state==self::ACT_STATE_NOT_BEGIN||$state==self::ACT_STATE_OVER||empty($c)) {
            return $result;
        }
        $cache=Cache::getInstance("REDIS_CONF_CACHE");
        $key=$c['real_time_rank_key'];
        $r=$cache->get($key);
        if (!$r) { return $result;
        }
        $re=json_decode($r, true);
        $re['anchorRank']=self::getLiveStatus($re['anchorRank'], $loginUid);
        $re['richManRank']=self::getLiveStatus($re['richManRank'], $loginUid);
        return $re;
    }

    public static function getTotalRank($loginUid,$stage=0)
    {
        $cc=self::springActConfig();
        $c=$stage?$cc[$stage]:self::getStageConfig();

        if (!empty($c)) {
            $cache=Cache::getInstance("REDIS_CONF_CACHE");
            $key=$c['total_rank_key'];
            $re=$cache->get($key);
            if (!$re) { return array(
                'anchorTotalRank'=>array(),
                'richmanTotalRank'=>array(),
            );
            }
            $re=json_decode($re, true);
            $re['anchorTotalRank']=self::getLiveStatus($re['anchorTotalRank'], $loginUid);
            $re['richmanTotalRank']=self::getLiveStatus($re['richmanTotalRank'], $loginUid);
            return $re;
        }
        return array(
            'anchorTotalRank'=>array(),
            'richmanTotalRank'=>array(),
        );
    }

    public static function getBlackList()
    {
        return array();
    }

    public static function joinRoom($anchorid,$liveid)
    {
        try{
            $c=self::getStageConfig();
            if (empty($c)) { throw new Exception("config is null");
            }
            $live = new Live();
            $liveinfo = $live->getLiveInfo($liveid);
            if($liveinfo) {
                $privacy = Privacy::getPrivacyInfoByLiveInfo($liveinfo['privacy']);
                Messenger::sendLiveJoinMessage($liveid, $c['ambassador'], '加入了直播间', Messenger::MESSAGE_TYPE_CHATROOM_JOIN, 0, $privacy);
            }
        }catch (Exception $e){
            self::log(__FUNCTION__, array('anchorid'=>$anchorid), $e);
        }
    }

    public static function sendWishBag($anchorid,$liveid)
    {
        try{
            $c=self::getStageConfig();
            if (empty($c)) { throw new Exception("config is null");
            }
            $sender=$c['ambassador'];
            $receiver=$anchorid;
            $giftid=$c['bagGift'];
            $num=1;
            $doublehit=1;
            $giftUniTag=strval(time());
            $daoBag=new DAOBag();
            $wishBag=$daoBag->getChristmasWishBag($sender, $giftid);
            if (!$wishBag) { throw new Exception("Christmas's wish bag is empty!");
            }
            $_REQUEST['bagid']=$wishBag['id'];
            $_REQUEST['num']=1;
            Bag::useBagGift($wishBag['id'], $sender, $receiver, $giftid, $num, $liveid, $doublehit, $giftUniTag);
            $name=isset($c['bagGiftName'])?$c['bagGiftName']:"幸运福袋";
            $content="恭喜您获得新春即时主播榜第一，吉祥旺财送给你一个{$name}，房间内见者有份粉丝红包一个";
            //$content="恭喜您获得圣诞即时主播榜第一，圣诞老人送给你一个幸运福袋，房间内见者有份粉丝红包一个";
            Messenger::sendCollectLog(Messenger::MESSAGE_TYPE_BROADCAST_SOME, $anchorid, '系统消息', $content, 0);
        }catch (Exception $e){
            self::log(
                __FUNCTION__,
                array('sender'=>$sender,
                    'receiver'=>$receiver,
                    'giftid'=>$giftid,
                    'liveid'=>$liveid,
                ), $e 
            );
        }
    }

    public static function sendRedPacket($liveid)
    {
        try{
            $roomPeopleNum=self::getRoomPeopleNum($liveid);
            if (!$roomPeopleNum) { throw new Exception("room people num is zero!");
            }

            $packet = new Packet();
            $packetid = (int) $packet->getLivePacket($liveid);
            if ($packetid) { throw  new Exception("the last red packet is not over!");
            }

            $dao_live = new DAOLive();
            if (!$dao_live->isLiveRunning($liveid)) { throw new Exception("live is not active!");
            }

            //$which=self::inWhichTime();
            //$config=self::springActConfig();
            /* $avgAmount=self::ACT_DEFAULT_AVG_RED_PACKET_AMOUNT;
            if ($which==self::TIME_SPAN_FREE){
                $avgAmount=self::ACT_FREE_AVG_RED_PACKET_AMOUNT;
            }elseif ($which==self::ACT_BUSY_AVG_RED_PACKET_AMOUNT){
                $avgAmount=self::ACT_BUSY_AVG_RED_PACKET_AMOUNT;
            }else{
                $avgAmount=self::ACT_FREE_AVG_RED_PACKET_AMOUNT;
            }*/
            $c=self::getStageConfig();
            if (empty($c)) { throw new Exception("config is null");
            }
            $avgAmount=isset($c['red_bag_avg'])&&$c['red_bag_avg']>0?$c['red_bag_avg']:5;
            $amount = $roomPeopleNum*$avgAmount;
            if ($amount > $c['red_bag_amount']) {
                $amount=$c['red_bag_amount'];
            }
            $remark = '恭喜发财，大吉大利。';
            $num=$roomPeopleNum;
            if ($num > $c['red_bag_max']) {
                $num=$c['red_bag_max'];
            }
            $threshold = intval($roomPeopleNum/2);
            $threshold=self::getUpTen($threshold);
            $uid=$c['ambassador'];

            //Interceptor::ensureNotFalse($amount >= 50, ERROR_PARAM_INVALID_FORMAT, 'amount');
            Interceptor::ensureNotFalse($amount >= $num, ERROR_BIZ_PACKET_SEND_AMOUNT_NOT_ENOUGH, 'num');

            Context::set('userid', $uid);

            $packetid=$packet->sendSharePacket($amount, $num, $remark, $liveid, $threshold);
            if (!$packetid) { throw new Exception("send red packet failed! ");
            }
        }catch (Exception $e){
            self::log(__FUNCTION__, get_defined_vars(), $e);
        }
    }

    public static function getUpTen($num)
    {
        $numStr=strval($num);
        $numArr=str_split($numStr);
        $oneField=$numArr[count($numArr)-1];
        $base=$num-$oneField;
        if ($oneField>0) { $base+=10;
        }
        return $base;
    }

    public static function getRoomPeopleNum($liveid)
    {
        $key = "dreamlive_live_user_real_num_".$liveid;
        $cache=Cache::getInstance("REDIS_CONF_CACHE");
        $r=$cache->get($key);
        $r=json_decode($r, true);
        $r=!$r?[]:$r;
        if (isset($r['num'])) { return intval($r['num']);
        }
        return 0;
    }

    public static function sendTrackMsg($uid,$anchorid)
    {
        try{
            $user=User::getUserInfo($uid);
            $anchor=User::getUserInfo($anchorid);
            if (!$user||!$anchorid) { return;
            }
            //$msg="圣诞消息：恭喜 {$user['nickname']}支持主播{$anchor['nickname']}({$anchorid})获得圣诞老人降临，送出圣诞福袋，Ta房间还有红包可以抢,大家快去哦~";
            $msg="恭喜主播{$user['nickname']}({$anchorid})获得吉祥旺财送出的福袋，还有红包可以抢大家快去哦~";
            $img="";
            Track::showTrackDefault($msg, $img);
        }catch (Exception $e){
            self::log(__FUNCTION__, get_defined_vars(), $e);
        }
    }

    public static function log($f,$msg,$e=null)
    {
        $d=array(
            'fun'=>$f,
            'msg'=>$msg,
            'e'=>$e,
            'time'=>date("Y-m-d H:i:s"),
        );

        try{
            $f="/tmp/act_spring.log";
            if (!file_exists($f)) {
                touch($f);
            }
            $info="\n\n".json_encode($d);
            $info.="\n\n";
            file_put_contents($f, $info, FILE_APPEND);
        }catch (Exception $e){

        }

    }

    public static function getExt($info)
    {
        foreach ($info as &$i){
            $userInfo=User::getUserInfo($i['uid']);
            if (empty($userInfo)) { continue;
            }
            $i['avatar']=$userInfo?$userInfo['avatar']:"";
            $i['nickname']=$userInfo?$userInfo['nickname']:"";
            $i['gender']=$userInfo?$userInfo['gender']:"M";
            //$i['icon']=$userInfo?self::getIcon($userInfo['medal']):null;
            $i['level']=$userInfo?$userInfo['level']:0;
            $i['num']=$i['total'];
            $i['king']=0;
            $i['vip']=0;
            foreach ($userInfo['medal'] as $j){
                if ($j['kind']==UserMedal::KIND_KING) {
                    $i['king']=intval($j['medal']);
                    continue;
                }elseif ($j['kind']==UserMedal::KIND_VIP) {
                    $i['vip']=intval($j['medal']);
                    continue;
                }
            }
        }
        return $info;
    }

    public static function getLiveStatus($info,$loginUid=0)
    {
        $daoLive=new DAOLive();
        foreach ($info as &$i){
            $s=$daoLive->isLiveStatus($i['uid']);
            $i['live']=$s;
            $f=Follow::isFollowed($loginUid, $i['uid']);
            $i['isFollow']=$f[$i['uid']];
        }
        return $info;
    }

    public static function inWhichTime()
    {
        $v=self::getStageConfig();
        if (empty($v)) { return self::TIME_SPAN_STOP;
        }

        $r=self::getState($v['busy_time'], self::TIME_SPAN_BUSY);
        if (!empty($r)) { return $r;
        }
        $r=self::getState($v['free_time'], self::TIME_SPAN_FREE);
        if (!empty($r)) { return $r;
        }
        $r=self::getState($v['stop_time'], self::TIME_SPAN_STOP);
        if (!empty($r)) { return $r;
        }

        return self::TIME_SPAN_STOP;
    }

    private static function getState($times,$s)
    {
        $now=time();
        $today=date("Y-m-d", $now);
        foreach ($times as $i){
            list($st,$et)=explode('-', $i);
            $st=strtotime($today.' '.$st);
            $et=strtotime($today.' '.$et);
            if ($now>=$st&&$now<=$et) { return $s;
            }
        }
        return '';
    }

    public static function springActConfig()
    {
        /*return array(
            1=>array(
                'stage'=>1,
                'name'=>'带你回家',
                'stime'=>'2018-02-05 12:00:00',
                'etime'=>'2018-02-10 02:00:20',
                'gifts'=>array(3843,3895),
                'bagGift'=>3976,
                'bagGiftName'=>'大福袋1',
                'busy_time'=>array('00:00:00-02:00:20','20:00:00-23:59:59'),
                'free_time'=>array('12:00:00-19:59:59'),
                'stop_time'=>array('02:00:21-11:59:59'),
                'total_rank_num'=>3,
                'real_time_rank_num'=>3,
                'busy_freq'=>10,
                'free_freq'=>30,
                'total_busy_score'=>2,
                'total_free_score'=>1,
                'red_bag_avg'=>10,
                'red_bag_max'=>50,//最多50个包
                'red_bag_amount'=>1000,//最多1000钻
                'ambassador'=>22222222,
                //'ambassador'=>23081180,
                'total_rank_key'=>'spring_act_total_rank_1',
                'real_time_rank_key'=>'spring_act_real_time_rank_1',
            ),
            2=>array(
                'stage'=>2,
                'name'=>'全程热恋',
                'stime'=>'2018-02-10 12:00:00',
                'etime'=>'2018-02-14 02:00:20',
                'gifts'=>array(3843,3895),
                'bagGift'=>3976,
                'bagGiftName'=>'大福袋2',
                'busy_time'=>array('00:00:00-02:00:20','20:00:00-23:59:59'),
                'free_time'=>array('12:00:00-19:59:59'),
                'stop_time'=>array('02:00:21-11:59:59'),
                'total_rank_num'=>3,
                'real_time_rank_num'=>3,
                'busy_freq'=>10,
                'free_freq'=>30,
                'total_busy_score'=>2,
                'total_free_score'=>1,
                'red_bag_avg'=>10,
                'red_bag_max'=>50,//最多50个包
                'red_bag_amount'=>1000,//最多1000钻
                'ambassador'=>22222222,
                //'ambassador'=>23081180,
                'total_rank_key'=>'spring_act_total_rank_2',
                'real_time_rank_key'=>'spring_act_real_time_rank_2',
            ),
            3=>array(
                'stage'=>3,
                'name'=>'家的温暖',
                'stime'=>'2018-02-14 12:00:00',
                'etime'=>'2018-02-23 00:00:20',
                'gifts'=>array(3843,3895),
                'bagGift'=>3976,
                'bagGiftName'=>'大福袋',
                'busy_time'=>array('00:00:00-02:00:20','20:00:00-23:59:59'),
                'free_time'=>array('12:00:00-19:59:59'),
                'stop_time'=>array('02:00:21-11:59:59'),
                'total_rank_num'=>3,
                'real_time_rank_num'=>3,
                'busy_freq'=>10,
                'free_freq'=>30,
                'total_busy_score'=>2,
                'total_free_score'=>1,
                'red_bag_avg'=>10,
                'red_bag_max'=>50,//最多50个包
                'red_bag_amount'=>1000,//最多1000钻
                'ambassador'=>22222222,
                //'ambassador'=>23081180,
                'total_rank_key'=>'spring_act_total_rank_3',
                'real_time_rank_key'=>'spring_act_real_time_rank_3',
            ),
        );*/
        return array(
            1=>array(
                'stage'=>1,
                'name'=>'带你回家',
                'stime'=>'2018-02-07 19:00:00',
                //'etime'=>'2018-02-10 02:00:20',
                'etime'=>'2018-02-10 11:59:59',
                'gifts'=>array(3894,3809,3892),
                'bagGift'=>3768,
                'bagGiftName'=>'吉祥福袋',
                'busy_time'=>array('00:00:00-02:00:20','20:00:00-23:59:59'),
                'free_time'=>array('12:00:00-19:59:59'),
                'stop_time'=>array('02:00:21-11:59:59'),
                'total_rank_num'=>3,
                'real_time_rank_num'=>3,
                'busy_freq'=>10,
                'free_freq'=>30,
                'total_busy_score'=>2,
                'total_free_score'=>1,
                'red_bag_avg'=>10,
                'red_bag_max'=>50,//最多50个包
                'red_bag_amount'=>1000,//最多1000钻
                'ambassador'=>23081180,
                'total_rank_key'=>'spring_act_total_rank_1',
                'real_time_rank_key'=>'spring_act_real_time_rank_1',
            ),
            2=>array(
                'stage'=>2,
                'name'=>'全程热恋',
                'stime'=>'2018-02-10 12:00:00',
                //'etime'=>'2018-02-13 02:00:20',
                'etime'=>'2018-02-13 11:59:59',
                'gifts'=>array(3824,3747,3889),
                'bagGift'=>3783,
                'bagGiftName'=>'爱的永恒',
                'busy_time'=>array('00:00:00-02:00:20','20:00:00-23:59:59'),
                'free_time'=>array('12:00:00-19:59:59'),
                'stop_time'=>array('02:00:21-11:59:59'),
                'total_rank_num'=>3,
                'real_time_rank_num'=>3,
                'busy_freq'=>10,
                'free_freq'=>30,
                'total_busy_score'=>2,
                'total_free_score'=>1,
                'red_bag_avg'=>10,
                'red_bag_max'=>50,//最多50个包
                'red_bag_amount'=>1000,//最多1000钻
                'ambassador'=>23081180,
                'total_rank_key'=>'spring_act_total_rank_2',
                'real_time_rank_key'=>'spring_act_real_time_rank_2',
            ),
            3=>array(
                'stage'=>3,
                'name'=>'家的温暖',
                'stime'=>'2018-02-13 12:00:00',
                'etime'=>'2018-02-24 00:00:20',
                'gifts'=>array(3893,3868,3891),
                'bagGift'=>3872,
                'bagGiftName'=>'恭喜发财',
                'busy_time'=>array('00:00:00-02:00:20','20:00:00-23:59:59'),
                'free_time'=>array('12:00:00-19:59:59'),
                'stop_time'=>array('02:00:21-11:59:59'),
                'total_rank_num'=>3,
                'real_time_rank_num'=>3,
                'busy_freq'=>10,
                'free_freq'=>30,
                'total_busy_score'=>2,
                'total_free_score'=>1,
                'red_bag_avg'=>10,
                'red_bag_max'=>50,//最多50个包
                'red_bag_amount'=>1000,//最多1000钻
                'ambassador'=>23081180,
                'total_rank_key'=>'spring_act_total_rank_3',
                'real_time_rank_key'=>'spring_act_real_time_rank_3',
            ),
        );
    }

    private static function getStageConfig()
    {
        $now=time();
        $config=self::springActConfig();
        foreach ($config as $k=>$v) {
            $stime = strtotime($v['stime']);
            $etime = strtotime($v['etime']);
            if ($now >= $stime && $now <= $etime) {
                return $v;
            }
        }
        return array();
    }

    public static function mp($arg)
    {
        var_dump($arg);exit;
    }
}