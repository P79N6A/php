<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/18
 * Time: 15:05
 */
class LottoPrize
{
    const RATE_UP_LIMIT=10000;
    const DAY_TO_SECONDS=24*60*60;
    const FACTOR=0.5;
    const MULTI=2;

    //加缓存
    public static function getPrizeList()
    {
        $daoPrize=new DAOLottoPrize();
        $re=$daoPrize->getPrizeList();
        foreach ($re as &$i){
            $i['icon']=Util::joinStaticDomain($i['icon']);
        }
        return $re;
    }

    //废弃
    public static function randomPrize($uid,$type,$liveid=0)
    {
        //判断游戏是否开启
        /*//检查是否直播间发起
        $daoLive=new DAOLive();
        Interceptor::ensureNotFalse($daoLive->isLiveRunning($liveid),ERROR_LIVE_IS_OVER,'liveid');
        //检查是否再三分钟以内*/
        $daoGame=new DAOGame();
        $game=$daoGame->getGameByType(DAOGame::TYPE_LOTTO);
        Interceptor::ensureNotEmpty($game, ERROR_PARAM_DATA_NOT_EXIST, 'lotto game not exists');

        $config=@json_decode($game['extends'], true);
        Interceptor::ensureNotFalse($config, ERROR_PARAM_DATA_NOT_EXIST, 'lotto game config is not exists');

        /*$timeLimit=$config['stay_time'];
        $daoLottoLog=new DAOLottoLog();
        Interceptor::ensureFalse($daoLottoLog->isInThreeMinitues($uid, $liveid,$timeLimit),ERROR_BIZ_LOTTO_GAME_LESS_TIME,'');*/

        /*return MyLock::lock(function () {
            $ps=func_get_args();
            list($uid,$liveid)=$ps[0];
            return self::lottoAlg($uid, $liveid);
        },[$uid,$liveid],"my_lotto_game_lock" );*/



        return self::lottoAlg($uid, $liveid);

    }

    public static function _lottoAlg()
    {
        $data=[];
        $prizeList=self::getPrizeList();

        $filterList=[];
        foreach ($prizeList as $i){//过滤中奖概率为零的
            if ($i['rate']==0) { continue;
            }
            $filterList[]=$i;
        }

        $sum=array_sum(array_column($prizeList, 'rate'));
        //$upLimit=self::RATE_UP_LIMIT;
        $upLimit=$sum*self::MULTI;
        $times=floor(($upLimit/$sum)*self::FACTOR);

        shuffle($filterList);

        $count=3;
        for($c=0;$c<$count;$c++){
            foreach ($filterList as $i){
                $rand=self::myRand(1, $upLimit);
                //$rand=$rand%$sum;
                $rate=$i['rate'];
                if ($rand<=$rate) {
                    $data= $i;
                    break;
                }else{
                    //$upLimit-=$rate*$times;
                    $upLimit-=$rate;
                }
            }
            if (!empty($data)) { break;
            }
        }


        if (empty($data)) {
            foreach ($prizeList as $i){
                if ($i['type']==DAOLottoPrize::TYPE_EMPTY) {
                    $data=$i;
                    break;
                }
            }
        }

        if (empty($data)) {
            $fpl=$filterList;
            usort(
                $fpl, function ($a,$b) {
                    if ($a['rate']==$b['rate']) {
                        return 0;
                    }
                    return $a['rate']<$b['rate']?1:-1;
                } 
            );
            $fpl4=array_slice($fpl, 0, 2);
            $index=array_rand($fpl4);
            $data=$fpl4[$index];
        }

        Interceptor::ensureNotEmpty($data, ERROR_PARAM_DATA_NOT_EXIST, "prize list is null");
        $data['status']=DAOLottoLog::LOG_STATUS_NO_GET;
        $data['i']=strval(Account::getOrderId());
        return $data;
    }

    public static function drawPrize($uid,$type,$liveid=0)
    {
        try{
            return self::_drawPrize($uid, $type, $liveid);
        }catch (Exception $e){
            ELog::e(array($e->getMessage(),$uid,$type), 'lotto_all');
            throw $e;
        }
    }

    private static function _drawPrize($uid,$type,$liveid)
    {
        $game=Game::getLottoGame();
        Interceptor::ensureNotEmpty($game, ERROR_PARAM_DATA_NOT_EXIST, 'game is null');
        $config=@json_decode($game['extends'], true);
        Interceptor::ensureNotFalse($game['isshow']=='Y', ERROR_CUSTOM, 'sorry , lotto game is offline !');

        $data=[];
        $amount=0;
        $id=0;
        if ($type==DAOLottoLog::LOG_TYPE_FREE) {
            Interceptor::ensureNotFalse(LottoLog::isFree($uid), ERROR_CUSTOM, 'free chance used !');
            if (Bag::hasFreeLottoTicket($uid)) {
                Bag::useFreeLottoTicket($uid, 1);
            }
            $data[]=self::_lottoAlg();
        }elseif ($type==DAOLottoLog::LOG_TYPE_SINGLE) {
            $data[]=self::_lottoAlg();
            Interceptor::ensureNotFalse($config['single_amount']>0, ERROR_CUSTOM, 'single_amount config is null');
            $singleAmount=$config['single_amount'];
            $amount=$singleAmount;
            AccountInterface::lottoGamePayment($uid, $singleAmount, "[$uid]单次抽奖，消费[$singleAmount]", $data);
        }elseif ($type==DAOLottoLog::LOG_TYPE_MUTI) {
            $mutiCount=$config['muti_count'];
            $mutiAmount=$config['muti_amount'];
            $amount=$mutiAmount;
            for($i=0;$i<$mutiCount;$i++){
                $data[]=self::_lottoAlg();
            }
            AccountInterface::lottoGamePayment($uid, $mutiAmount, "[$uid]单次抽奖，消费[$mutiAmount]", $data);
        }else{
            throw new Exception("lotto type is unexcepted !");
        }

        $id=LottoLog::addLog($uid, $type, $amount, $liveid, $data);
        if (!$id) { throw new Exception('add log failed!');
        }

        self::asynProcess($uid, $data, $id);
        return $data;
    }

    private static function getRemarkByMode($mode,&$data)
    {
        if (empty($data)) { return;
        }
        $remark="";
        if ($mode==DAOLottoLog::LOG_TYPE_FREE||$mode==DAOLottoLog::LOG_TYPE_SINGLE) {
            switch ($data[0]['type']){
            case DAOLottoPrize::TYPE_BAG_GIFT:
            case DAOLottoPrize::TYPE_BIG_HORN:
            case DAOLottoPrize::TYPE_SMALL_HORN:
                break;
            case DAOLottoPrize::TYPE_DIAMOND:
            case DAOLottoPrize::TYPE_STAR:
                break;
            case DAOLottoPrize::TYPE_RIDE:
                break;
            case DAOLottoPrize::TYPE_ENTITY:
                break;
            case DAOLottoPrize::TYPE_EMPTY:
                break;
            default:
                break;
            }
            $data[0]['remark']=$remark;
        }

    }

    private static function asynProcess($uid,$data,$id)
    {
        if (!$id) { return ;
        }
        foreach ($data as $i){
            ////放背包异步
            self::putInBag($uid, $i, $id);
            //如果notice不空发消息
            if ($i['notice']) {
                Track::showTrackLotto($uid, $i);
            }
        }
    }

    //废弃，但是保留
    private static function lottoAlg($uid,$liveid)
    {
        $data=[];
        $daoPrize=new DAOLottoPrize();
        $prizeList=$daoPrize->getPrizeList();

        $filterList=[];
        $daoLog=new DAOLottoLog();
        foreach ($prizeList as $i){//过滤中奖概率为零的，过率超过周上限的
            if (bccomp($i['rate'], '0', 2)==0) { continue;
            }
            /*if ($i['upLimit']){
                if ($daoLog->isUptoWeekLimit($i['prizeid'], $i['upLimit']))continue;
            }*/
            $filterList[]=$i;
        }

        $upLimit=array_sum(array_column($prizeList, 'rate'));
        $upLimit=floor($upLimit*100);

        shuffle($filterList);

        foreach ($filterList as $i){
            $rand=self::myRand(1, $upLimit);
            $rate=floor($i['rate']*100);
            if ($rand<=$rate) {
                $data= $i;
                break;
            }else{
                $upLimit-=$rate;
            }
        }

        if (empty($data)) {
            foreach ($prizeList as $i){
                if ($i['type']==DAOLottoPrize::TYPE_EMPTY) {
                    $data=$i;
                    break;
                }
            }
        }
        Interceptor::ensureNotEmpty($data, ERROR_PARAM_DATA_NOT_EXIST, "prize list is null");
        //加log
        $status=$data['type']==DAOLottoPrize::TYPE_ENTITY?DAOLottoLog::LOG_STATUS_NO_GET:DAOLottoLog::LOG_STATUS_GET;
        $daoLog->add($uid, $data['prizeid'], $status, $liveid);

        ////放背包异步
        self::putInBag($uid, $data);

        $data['remark']="恭喜你获得了".$data['name']." 到背包查看一下吧！";
        if ($data['type']==DAOLottoPrize::TYPE_EMPTY) {
            $data['remark']='谢谢惠顾！';
        }

        //如果notice不空发消息
        if ($data['notice']) {
            $sysAcount=Account::ACTIVE_ACCOUNT1300;
            $userInfo=User::getUserInfo($uid);
            $content=str_replace("{user}", $userInfo['nickname'], $data['notice']);
            Messenger::sendChatroomBroadcast($sysAcount, Messenger::MESSAGE_TYPE_BROADCAST_HORN_ALL, $content, []);
        }
        return $data;
    }

    public static function putInBag($uid,$data,$id=0)
    {
        self::inBagLog($uid, $data, $id);
        try{
            if ($data['type']==DAOLottoPrize::TYPE_STAR) {
                AccountInterface::lottoGameSendMoney($uid, $data['num'], Account::CURRENCY_COIN, '大转盘游戏('.$id.'-'.$data['i'].')送【'.$uid.'】'.$data['amount'].'星光');
            }elseif ($data['type']==DAOLottoPrize::TYPE_DIAMOND) {
                AccountInterface::lottoGameSendMoney($uid, $data['num'], Account::CURRENCY_DIAMOND, '大转盘游戏('.$id.'-'.$data['i'].')送【'.$uid.'】'.$data['amount'].'星钻');
            }elseif ($data['type']==DAOLottoPrize::TYPE_BAG_GIFT) {
                Bag::putGift($uid, $data['relateid'], $data['num']);
            }elseif ($data['type']==DAOLottoPrize::TYPE_RIDE) {
                $product=new Product();
                $product->sendRideByLotto($uid, $data['relateid'], $data['num']*self::DAY_TO_SECONDS);
            }elseif ($data['type']==DAOLottoPrize::TYPE_BIG_HORN||$data['type']==DAOLottoPrize::TYPE_SMALL_HORN) {
                $product=new Product();
                $product->sendHornByLotto($uid, $data['relateid'], $data['num']);
            }
            if ($id) {
                LottoLog::getConfirm($id, $data['i']);
            }
        }catch (Exception $e){
            ELog::e(array($id,$uid,$data,$e->getMessage()), 'lotto_inbag');
            //throw  $e;
        }
    }

    public static function editPrize($data)
    {
        $d=@json_decode($data, true);
        Interceptor::ensureNotFalse($d, ERROR_PARAM_INVALID_FORMAT, 'data');
        Interceptor::ensureNotFalse(!empty($d), ERROR_PARAM_INVALID_FORMAT, 'data is null');
        $daoPrize=new DAOLottoPrize();
        $daoPrize->startTrans();
        try{
            //$daoPrize->truncatePrize();
            $index=0;
            foreach ($d as $i){
                $index++;
                $daoPrize->add($index, $i['name'], $i['num'], $i['icon'], 0, $i['rate'], $i['relateid'], $i['type'], $i['weight'], $i['notice']);
            }
            $daoPrize->commit();
        }catch (Exception $e){
            $daoPrize->rollback();
            Interceptor::ensureNotFalse(is_null($e), ERROR_CUSTOM, json_encode($e));
        }
    }

    private static function myRand($min,$max)
    {
        srand((double)microtime()*1000000);
        return rand($min, $max);
    }

    //补发奖品
    private static function reissue($uid,$update=false)
    {
        //背包礼物
        //抽奖的+系统发放的+自己购买的=背包余额+背包消耗
        //抽奖记录
        $daoLottoLog=new DAOLottoLog();
        $r=$daoLottoLog->getAll("select * from lotto_log where uid=? ", array('uid'=>$uid));
        $rt=array();
        foreach ($r as $i){
            $ext=@json_decode($i['extends'], true);
            if ($ext) {
                foreach ($ext as $j){
                    if($j['type']==6) {
                        $rt[$j['relateid']]=isset($rt[$j['relateid']])?($rt[$j['relateid']]+$j['num']):$j['num'];
                    }
                }
            }
        }
        //背包剩余
        $daoBag=new DAOBag();
        $br=$daoBag->getAll("select * from bag where uid=? and cateid=7", array('uid'=>$uid));
        $brt=array();
        foreach ($br as $i){
            $brt[$i['relateid']]=$i['num'];
        }
        //背包消耗
        $giftid=array_keys($rt);
        $str=implode(',', $giftid);//exit($str);
        $bu=new DAOBagUsed();
        $bur=$bu->getAll("select relateid,sum(num) as n from bag_used where uid=? and relateid in(".$str.") GROUP BY relateid ", array('uid'=>$uid));

        $burt=array();
        foreach($bur as $i){
            $burt[$i['relateid']]=$i['n'];
        }

        //补发;
        $result=array();
        foreach($rt as $k=>$v){
            $v-=isset($burt[$k])?$burt[$k]:0;
            $v-=isset($brt[$k])?$brt[$k]:0;
            $result[$k]=$v;
        }
        //print_r($rt);

        if ($update) {
            foreach($result as $k=>$v){
                if ($v<=0) { continue;
                }
                Bag::putGift($uid, $k, $v);
            }
        }else{
            var_dump($rt, $brt, $burt);
        }

    }

    //put in bag 日志
    private static function inBagLog($uid,$data,$id)
    {
        try{
            Logger::log("lotto_prize_log", "LottoPrize.PutInBag", array($uid,$data,$id));
        }catch (Exception $e){

        }
    }
}