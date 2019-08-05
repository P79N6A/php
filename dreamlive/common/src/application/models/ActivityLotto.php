<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/1
 * Time: 10:29
 */

/**
 * Class ActivityLotto
 * 抽奖模块配置
 *
 * stime//开始时间
 * etime://结束时间
 *
 * prize:[
 *      list:[
 *          {leve:1//奖励等级,prob:２//概率}
 *      ]
 *      base:10000//抽奖最大基数
 * ]
 */
class ActivityLotto
{
    const DRAW_TRADE_TYPE=80;
    const DRAW_SYS_ACCOUNT=1000;

    public static function userDraw($ctl,$activityid,$moduleid,$uid,$isFree=false)
    {
        $activityinfo=Activity::getActivityInfo($activityid);
        Interceptor::ensureNotEmpty($activityinfo, ERROR_PARAM_DATA_NOT_EXIST, ' activity not exists');
        $userinfo=User::getUserInfo($uid);
        Interceptor::ensureNotEmpty($userinfo, ERROR_PARAM_DATA_NOT_EXIST, ' user not exists');
        $moduleinfo=$moduleinfo=Activity::getModuleInfoById($activityinfo, $moduleid);
        Interceptor::ensureNotEmpty($moduleinfo, ERROR_PARAM_DATA_NOT_EXIST, ' module not exists');

        $data=self::beforeDraw($ctl, $activityinfo, $userinfo, $moduleinfo);

        $ext=@json_decode($moduleinfo['extends'], true);
        Interceptor::ensureNotFalse($ext, ERROR_CUSTOM, 'config exception');

        if ($isFree) {
            $account = new Account();
            $dao_gift_log = new DAOGiftLog();
            try {
                $dao_gift_log->startTrans();

                $diamond = $account->getBalance($uid, ACCOUNT::CURRENCY_DIAMOND);
                Interceptor::ensureNotEmpty($diamond, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
                Interceptor::ensureNotFalse($diamond>=$data['num'], ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);
                Interceptor::ensureNotFalse($data['num']>0, ERROR_BIZ_PAYMENT_DIAMOND_BALANCE_DUE);

                $orderid = $account->getOrderId();

                $tradeType=self::DRAW_TRADE_TYPE;
                $sysAccount=self::DRAW_SYS_ACCOUNT;
                $account->decrease($uid, $tradeType, $orderid, $data['num'], ACCOUNT::CURRENCY_DIAMOND, "用户：".$uid."抽奖扣钻".$data['num'], []);
                $account->increase($sysAccount, $tradeType, $orderid, $data['num'], ACCOUNT::CURRENCY_DIAMOND, "用户：".$uid."抽奖扣钻".$data['num'], []);

                $dao_gift_log->commit();
            } catch (Exception $e) {
                $dao_gift_log->rollback();
                throw $e;
            }

        }

        $level=self::userDrawAlg($ext);

        $dao_lotto=new DAOActivityLotto();
        $lottoid=$dao_lotto->add($activityid, $moduleinfo['roundid'], $moduleid, $uid, $level, DAOActivityLotto::LOTTO_STATUS_NOT_RECEIVE);

        return self::afterDraw($ctl, $activityinfo, $userinfo, $moduleinfo, $lottoid);
    }

    public static function chargeUserDraw($ctl,$activityid,$moduleid,$uid)
    {
        self::userDraw($ctl, $activityid, $moduleid, $uid, true);
    }

    public static function freeUserDraw($ctl,$activityid,$moduleid,$uid)
    {
        self::userDraw($ctl, $activityid, $moduleid, $uid, false);
    }

    //制定id范围，然后随机生成id，如果存在选出来
    public static function guestDraw($ctl,$activityid,$uid)
    {

    }

    public static function rollDraw($ctl,$activityid,$moduleid)
    {

    }

    private static function beforeDraw($ctl,$activityinfo,$userinfo,$moduleinfo)
    {
        $data=[];
        $config=ActivityModule::getModuleConfig($moduleinfo['extends']);
        //检查报名时间
        $now=time();
        if (isset($config['stime'])) {
            Interceptor::ensureNotFalse($now>=strtotime($config['stime']), ERROR_CUSTOM, 'not begin');
        }
        if (isset($config['etime'])) {
            Interceptor::ensureNotFalse($now<=strtotime($config['etime']), ERROR_CUSTOM, 'be over');
        }

        $num=$ctl->getParam('num', 0);
        $data['num']=$num;

        if (!empty($moduleinfo['scripts'])) {
            eval($moduleinfo['scripts']);
        }
        return $data;
    }
    private static function afterDraw($ctl,$activityinfo,$userinfo,$moduleinfo,$lottoid)
    {
        $data=[];
        $dao_lotto=new DAOActivityLotto();
        $data['lottoid']=$lottoid;
        $data['result']=$dao_lotto->getLottoById($lottoid);
        return $data;
    }
    private static function userDrawAlg(array $config)
    {
        $data=0;
        Interceptor::ensureNotEmpty($config, ERROR_CUSTOM, 'config is null');
        Interceptor::ensureNotEmpty(isset($config['prize']), ERROR_CUSTOM, 'prize is empty');

        $prize=$config['prize'];
        $prize_list=$prize['list'];
        $upLimit=$prize['base'];

        foreach ($prize_list as $i){
            $rand=self::myRand(1, $upLimit);
            if ($rand<=$i['prob']) {
                $data= $i['level'];
                break;
            }else{
                $upLimit-=$config['prob'];
            }
        }

        return $data;
    }
    private static function guestDrawAlg()
    {

    }

    private static function myRand($min,$max)
    {
        srand((double)microtime()*1000000);
        return rand($min, $max);
    }
}