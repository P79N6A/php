<?php
/*
 * #act_christmas
// 0,30 12-19 * * * php /home/dream/codebase/service/src/application/process/crontab_activity_christmas.php 3
0,10,20,30,40,50 12-19 * * * php /home/dream/codebase/service/src/application/process/crontab_activity_christmas.php 3
// 0,10,20,30,40,50 20-23,0-4 * * * php /home/dream/codebase/service/src/application/process/crontab_activity_christmas.php 3
0,5,10,15,20,25,30,35,40,45,50,55 20-23,0-4 * * * php /home/dream/codebase/service/src/application/process/crontab_activity_christmas.php 3

* * * * * sh /home/dream/codebase/service/src/application/process/crontab_activity_christmas_realtime_rank.sh
// 0,10,20,30,40,50 * * * * php /home/dream/codebase/service/src/application/process/crontab_activity_christmas.php 2
// 0,5,10,15,20,25,30,35,40,45,50,55 * * * * php /home/dream/codebase/service/src/application/process/crontab_activity_christmas.php 2
* * * * * php /home/dream/codebase/service/src/application/process/crontab_activity_christmas.php 2

 */
class ActChristmas
{
    //todo 问松山，
    const UTYPE_ANCHOR=1;
    const UTYPE_RICH_MAN=2;

    const ACT_STATE_NOT_BEGIN=1;
    const ACT_STATE_IN_RUNNING=2;
    const ACT_STATE_IN_TMP_STOP=3;
    const ACT_STATE_OVER=4;

    const ACT_STIME="2017-12-21 20:00:00";//2017-12-23 00:00:00
    const ACT_ETIME="2018-01-01 23:59:59";

    const ACT_FREE_TIME_START="12:00:00";//12:00:00
    const ACT_FREE_TIME_END="20:00:00";
    const ACT_FREE_STAT_FREQUENCY=30;//分钟 //30

    const ACT_BUSY_TIME_START="20:00:00";
    const ACT_BUSY_TIME_END="04:00:00";//第二天
    const ACT_BUSY_STAT_FREQUENCY=10;//分钟 //5

    const ACT_STOP_TIME_START="04:00:00";
    const ACT_STOP_TIME_END="12:00:00";//12:00:00

    const ACT_CHRISTMAS_MAN=11111111;//?  12181933

    const ACT_WISH_BAG_GIFT_ID=3768;//?
    const ACT_AVG_RED_PACKET_AMOUNT=3;

    const ACT_CHRISTMAS_REALTIME_RANK_KEY="act_christmas_realtime_rank";
    const ACT_CHRISTMAS_TOTAL_RANK_KEY="act_christmas_total_rank";

    const CRONTAB_TASK_TYPE_REALTIME=1;
    const CRONTAB_TASK_TYPE_TOTAL=2;
    const CRONTAB_TASK_TYPE_ACT=3;

    const REALTIME_RANK_NUM=3;
    const TOTAL_RANK_NUM=3;

    //只有在运行时候，返回数据，其它时间，数据为空 todo
    public static function getInfo($uid)
    {
        $realtimeRank=self::getRealTimeRank($uid);
        $totalRank=self::getTotalRank($uid);
        $actState=self::getActState();
        $timeSpan=self::getTimeSpan();
        $timeSpan['stime']=strtotime($timeSpan['stime']);
        $timeSpan['etime']=strtotime($timeSpan['etime']);

        return array(
            'realtimeRank'=>$realtimeRank,
            'totalRank'=>$totalRank,
            'actState'=>$actState,
            'timeSpan'=>$timeSpan,
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
        for($i=0;$i<$end;$i+=$step){
            //$actState=self::getActState();
            //if ($actState!=self::ACT_STATE_IN_RUNNING)return;
            $cache=Cache::getInstance("REDIS_CONF_CACHE");
            $key=self::ACT_CHRISTMAS_REALTIME_RANK_KEY;
            $re=self::makeRank();
            //if (empty($re['anchorRank'])||empty($re['richManRank']))return;
            $t=array();
            $t['anchorRank']=self::getExt($re['anchorRank']);
            $t['richManRank']=self::getExt($re['richManRank']);
            $cache->set($key, json_encode($t));

            sleep($step-1);
        }
    }

    //0,10,20,30,40,50 * * * * php /home/dream/codebase/service/src/application/process/crontab_activity_christmas.php 2
    public static function genTotalRank()
    {
        $actState=self::getActState();
        if ($actState!=self::ACT_STATE_IN_RUNNING) { return;
        }
        $cache=Cache::getInstance("REDIS_CONF_CACHE");
        $key=self::ACT_CHRISTMAS_TOTAL_RANK_KEY;
        $daoActChristmasRankTotal=new DAOActChristmasRankTotal();
        $re=$daoActChristmasRankTotal->getTotalRank(self::TOTAL_RANK_NUM);
        $info=array(
            'anchorTotalRank'=>self::getExt($re['anchorTotalRank']),
            'richmanTotalRank'=>self::getExt($re['richmanTotalRank']),
        );
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
            $gifts = self::getActGifts();
            if (empty($gifts)) { return;
            }
            $daoGiftLog = new DAOGiftLog();

            $blackList=self::getBlackList();

            $anchorRank = $daoGiftLog->getGiftAmountTopN($gifts, $stime, $etime, "R", self::REALTIME_RANK_NUM, $blackList);
            $richManRank = $daoGiftLog->getGiftAmountTopN($gifts, $stime, $etime, "S", self::REALTIME_RANK_NUM, $blackList);
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

        $daoActChristmas=new DAOActChristmas();
        $daoActChristmasLog=new DAOActChristmasLog();
        $daoActChristmasRank=new DAOActChristmasRank();
        $daoActChristmasRankTotal=new DAOActChristmasRankTotal();
        $daoActChristmas->startTrans();
        try{
            $roundid=$daoActChristmas->add($stime, $etime);
            if (!$roundid) { throw new Exception("add round fail");
            }
            //主播
            foreach ($anchorRank as $i){
                $daoActChristmasRank->add($roundid, $i['uid'], self::UTYPE_ANCHOR, $i['total'], $i['rank']);
            }
            $anchorTopOne=$anchorRank[0];
            $daoActChristmasRankTotal->setRank($anchorTopOne['uid'], self::UTYPE_ANCHOR);
            $daoActChristmasLog->add($roundid, DAOActChristmasLog::TYPE_WISH, $anchorTopOne['uid'], self::UTYPE_ANCHOR);
            $daoActChristmasLog->add($roundid, DAOActChristmasLog::TYPE_RED_PACKET, $anchorTopOne['uid'], self::UTYPE_ANCHOR);
            //土豪
            foreach ($richmanRank as $i){
                $daoActChristmasRank->add($roundid, $i['uid'], self::UTYPE_RICH_MAN, $i['total'], $i['rank']);
            }
            $richmanTopOne=$richmanRank[0];
            $daoActChristmasRankTotal->setRank($richmanTopOne['uid'], self::UTYPE_RICH_MAN);
            $daoActChristmasLog->add($roundid, DAOActChristmasLog::TYPE_LOTTO_TICKET, $richmanTopOne['uid'], self::UTYPE_RICH_MAN);
            $daoActChristmasLog->add($roundid, DAOActChristmasLog::TYPE_RIDE, $richmanTopOne['uid'], self::UTYPE_RICH_MAN);

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
                $sender=self::ACT_CHRISTMAS_MAN;
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

                if ($richmanid) {
                    //送奖券
                    self::sendLottoTicket($richmanid);
                    //送座驾
                    self::sendRide($richmanid);
                }

            }
        }catch (Exception $e){
            self::log(__FUNCTION__, array(), $e);
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
        $tomorrow=date("Y-m-d", $now+24*60*60);
        $yesterday=date("Y-m-d", $now-24*60*60);
        $hour=date("H", $now);
        $hour=intval($hour);

        $fstart=strtotime($today." ".self::ACT_FREE_TIME_START);
        $fend=strtotime($today." ".self::ACT_FREE_TIME_END);

        if ($hour>=0&&$hour<4) {
            $bstart=strtotime($yesterday." ".self::ACT_BUSY_TIME_START);
            $bend=strtotime($today." ".self::ACT_BUSY_TIME_END);
        }else{
            $bstart=strtotime($today." ".self::ACT_BUSY_TIME_START);
            $bend=strtotime($tomorrow." ".self::ACT_BUSY_TIME_END);
        }
        

        $stime=$etime="";

        $state=self::getActState();
        if ($state==self::ACT_STATE_NOT_BEGIN) {
            $stime= self::ACT_STIME;
            $etime= self::ACT_ETIME;
        }elseif($state==self::ACT_STATE_OVER) {
            $stime= self::ACT_STIME;
            $etime= self::ACT_ETIME;
        }elseif ($state==self::ACT_STATE_IN_TMP_STOP) {
            $stime=$today." ".self::ACT_STOP_TIME_START;
            $etime=$today." ".self::ACT_STOP_TIME_END;
        }else{//runing time
            $h=date("H", $now);
            $m=date("i", $now);
            $m=intval($m);
            $s=date("s", $now);
            $s=intval($s);
            $freq=self::ACT_BUSY_STAT_FREQUENCY;
            $isInterval=false;
            if ($now>=$fstart&&$now<$fend) {//30分钟一次
                $freq=self::ACT_FREE_STAT_FREQUENCY;
            }elseif ($now>=$bstart&&$now<$bend) {//10分钟一次
                $freq=self::ACT_BUSY_STAT_FREQUENCY;
            }else{//休息时间
                $freq=self::ACT_BUSY_STAT_FREQUENCY;
            }
            $t_m=intval($m/$freq);
            $isInterval=($m%$freq)==0&&$s<=2?true:false;
            $head=$now>strtotime($today." 23:59:59")?$tomorrow:$today;
            $stime=$head." ".$h.":".($t_m==0?"00":$t_m*$freq).":00";
            $stime=$isInterval?date("Y-m-d H:i:s", (strtotime($stime)-$freq*60)):$stime;
            $etime=date("Y-m-d H:i:s", strtotime($stime)+$freq*60);
        }

        return array(
            'stime'=>$stime,
            'etime'=>$etime,
            'now'=>$now,
        );
    }

    public static function getActState()
    {
        $now=time();
        $stime=strtotime(self::ACT_STIME);
        $etime=strtotime(self::ACT_ETIME);
        if ($now>=$stime&&$now<=$etime) {
            $today=date("Y-m-d", $now);
            $sstime=strtotime($today." ".self::ACT_STOP_TIME_START);
            $setime=strtotime($today." ".self::ACT_STOP_TIME_END);
            if ($now>=$sstime&&$now<=$setime) {
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
        return array(3861,3862,3863,3864,3865,3866,3750,3859,);
        //return array(3970,3971,3972,3973,3974,3975);
    }

    public static function getRealTimeRank($loginUid)
    {
        $state=self::getActState();
        if ($state==self::ACT_STATE_IN_TMP_STOP||$state==self::ACT_STATE_NOT_BEGIN||$state==self::ACT_STATE_OVER) {
            return array(
                "anchorRank"=>[],
                "richManRank"=>[],
            );
        }
        $cache=Cache::getInstance("REDIS_CONF_CACHE");
        $key=self::ACT_CHRISTMAS_REALTIME_RANK_KEY;
        $r=$cache->get($key);
        if (!$r) { return array();
        }
        $re=json_decode($r, true);
        $re['anchorRank']=self::getLiveStatus($re['anchorRank'], $loginUid);
        $re['richManRank']=self::getLiveStatus($re['richManRank'], $loginUid);
        return $re;
    }

    public static function getTotalRank($loginUid)
    {
        $state=self::getActState();
        if ($state==self::ACT_STATE_NOT_BEGIN||$state==self::ACT_STATE_OVER) {
            return array(
                "anchorTotalRank"=>[],
                "richmanTotalRank"=>[],
            );
        }
        $cache=Cache::getInstance("REDIS_CONF_CACHE");
        $key=self::ACT_CHRISTMAS_TOTAL_RANK_KEY;
        $re=$cache->get($key);
        if (!$re) { return array();
        }
        $re=json_decode($re, true);
        $re['anchorTotalRank']=self::getLiveStatus($re['anchorTotalRank'], $loginUid);
        $re['richmanTotalRank']=self::getLiveStatus($re['richmanTotalRank'], $loginUid);
        return $re;
    }

    public static function getBlackList()
    {
        return array();
    }

    public static function joinRoom($anchorid,$liveid)
    {
        try{
            $live = new Live();
            $liveinfo = $live->getLiveInfo($liveid);
            if($liveinfo) {
                $privacy = Privacy::getPrivacyInfoByLiveInfo($liveinfo['privacy']);
                Messenger::sendLiveJoinMessage($liveid, self::ACT_CHRISTMAS_MAN, '加入了直播间', Messenger::MESSAGE_TYPE_CHATROOM_JOIN, 0, $privacy);
            }
        }catch (Exception $e){
            self::log(__FUNCTION__, array('anchorid'=>$anchorid), $e);
        }
    }

    public static function sendWishBag($anchorid,$liveid)
    {
        try{
            $sender=self::ACT_CHRISTMAS_MAN;
            $receiver=$anchorid;
            $giftid=self::ACT_WISH_BAG_GIFT_ID;
            $num=1;
            $doublehit=1;
            $giftUniTag=strval(time());
            $daoBag=new DAOBag();
            $wishBag=$daoBag->getChristmasWishBag($sender, self::ACT_WISH_BAG_GIFT_ID);
            if (!$wishBag) { throw new Exception("Christmas's wish bag is empty!");
            }
            $_REQUEST['bagid']=$wishBag['id'];
            $_REQUEST['num']=1;
            Bag::useBagGift($wishBag['id'], $sender, $receiver, $giftid, $num, $liveid, $doublehit, $giftUniTag);
            $content="恭喜您获得圣诞即时主播榜第一，圣诞老人送给你一个幸运福袋，房间内见者有份粉丝红包一个";
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

            $amount = $roomPeopleNum*self::ACT_AVG_RED_PACKET_AMOUNT;
            $remark = '恭喜发财，大吉大利。';
            $num=$roomPeopleNum;
            $threshold = intval($roomPeopleNum/2);
            $threshold=self::getUpTen($threshold);
            $uid=self::ACT_CHRISTMAS_MAN;

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
    public static function sendRedPacketPK($liveid, $amount)
    {
        try{
            $roomPeopleNum=self::getRoomPeopleNum($liveid);
            if (!$roomPeopleNum) { throw new Exception("room people num is zero!");
            }
            if($amount<$roomPeopleNum) { throw new Exception("金额小于房间人数!");
            }

            $packet = new Packet();
            $packetid = (int) $packet->getLivePacket($liveid);
            if ($packetid) { throw  new Exception("the last red packet is not over!");
            }

            $dao_live = new DAOLive();
            if (!$dao_live->isLiveRunning($liveid)) { throw new Exception("live is not active!");
            }

            //$amount = $roomPeopleNum*self::ACT_AVG_RED_PACKET_AMOUNT;
            $remark = '恭喜发财，大吉大利。';
            $num=$roomPeopleNum;
            $threshold = intval($roomPeopleNum/2);
            $threshold=self::getUpTen($threshold);
            $uid=self::ACT_CHRISTMAS_MAN;

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
            $msg="圣诞消息：恭喜 {$user['nickname']}支持主播{$anchor['nickname']}({$anchorid})获得圣诞老人降临，送出圣诞福袋，Ta房间还有红包可以抢,大家快去哦~";
            $img="";
            Track::showTrackDefault($msg, $img);
        }catch (Exception $e){
            self::log(__FUNCTION__, get_defined_vars(), $e);
        }
    }

    public static function sendLottoTicket($uid)
    {
        try{
            $ticketProduct=Product::getOnlyOneProductByCateid(DAOProduct::CATEID_FREE_LOTTO_TICKET);
            if (!$ticketProduct) { return;
            }
            $num=1;
            $msg="恭喜您获得圣诞即时土豪榜第一，圣诞老人送给你一张幸运抽奖券，快去体验吧";
            Bag::putFreeLottoTicket($uid, $ticketProduct['productid'], $num, $msg);
        }catch (Exception $e){
            self::log(__FUNCTION__, get_defined_vars(), $e);
        }
    }

    public static function sendRide($uid)
    {
        try{
            $daoActChristmasRankTotal=new DAOActChristmasRankTotal();
            $level=$daoActChristmasRankTotal->getRichManRideLevel($uid);
            if (!$level) { return;
            }
            $rides=self::getFourLevelRides();
            if (!isset($rides[$level])) { return;
            }
            //如果已经送过1级的，则不送了
            if (Bag::hasTheRide($uid, $rides[$level])) { return;
            }
            $product=Product::getOne($rides[$level]);
            if (!$product) { return;
            }
            $expire=10*24*60*60;
            $notice="恭喜您获得圣诞即时土豪榜第一，圣诞老人送给你一个圣诞进场特效，快去体验吧 ";
            Bag::putRide($uid, $product['productid'], $expire, $notice);
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
            $f="/tmp/act_christmas.log";
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

    public static function getTimer($seconds,$inRun=true)
    {
        if ($inRun) {
            $h=intval($seconds/(60*60));
            $left=$seconds%(60*60);
            $m=intval($left%60);
            $s=$seconds-$h*60*60-$m*60;
            return array(
                'h'=>$h,
                'm'=>$m,
                's'=>$s,
            );
        }else{
            $d=intval($seconds/(24*60*60));
            $left=$seconds%(24*60*60);
            $h=intval($left/(60*60));
            $left=$left%(60*60);
            $m=intval($left/60);
            $s=$seconds-$d*24*60*60-$h*60*60-$m*60;
            return array(
                'd'=>$d,
                'h'=>$h,
                'm'=>$m,
                's'=>$s,
            );
        }
    }

    public static function getFourLevelRides()
    {
        return array(
            1=>67,
            2=>68,
            3=>69,
            4=>70,
        );
    }

}