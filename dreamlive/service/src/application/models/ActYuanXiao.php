<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/2
 * Time: 17:28
 */
class ActYuanXiao
{
    const ACT_YUAN_XIAO_KEY='act_yuan_xiao';
    const ACT_STATUS_NOT_BEGIN=1;
    const ACT_STATUS_RUN=2;
    const ACT_STATUS_OVER=3;

    public static function config()
    {
        return array(
            'stime'=>'2018-03-02 19:30:00',
            'etime'=>'2018-03-07 23:59:59',
            'gifts'=>array(3795,3891,3780),
        );
    }

    public static function getRank()
    {
        $cache=Cache::getInstance("REDIS_CONF_CACHE");
        $info=$cache->get(self::ACT_YUAN_XIAO_KEY);
        $info=json_decode($info, true);
        if (!$info) { $info=array();
        }
        return $info;
    }

    public static function genRank()
    {
        $st=self::status();
        $c=self::config();
        if ($st==self::ACT_STATUS_RUN) {
            $gifts=$c['gifts'];
            if (!empty($gifts)) {
                $daoGiftLog = new DAOGiftLog();
                $re=$daoGiftLog->getGiftAmountTopN($gifts, $c['stime'], $c['etime'], 'R', 5);
                if (!$re) { $re=array();
                }
                $re=self::getExt($re);
                $cache=Cache::getInstance("REDIS_CONF_CACHE");
                $cache->set(self::ACT_YUAN_XIAO_KEY, json_encode($re));
            }
        }
    }

    public static function status()
    {
        $now=time();
        $c=self::config();
        $stime=strtotime($c['stime']);
        $etime=strtotime($c['etime']);
        $st=0;
        if ($now<$stime) {
            $st=self::ACT_STATUS_NOT_BEGIN;
        }elseif ($now>=$stime&&$now<=$etime) {
            $st=self::ACT_STATUS_RUN;
        }else{
            $st=self::ACT_STATUS_OVER;
        }
        return $st;
    }

    private static function getExt($info)
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
            //if ($i['rank']>50)$i['rank']='50+';
            /* foreach ($userInfo['medal'] as $j){
                 if ($j['kind']==UserMedal::KIND_KING){
                     $i['king']=intval($j['medal']);
                     continue;
                 }elseif ($j['kind']==UserMedal::KIND_VIP){
                     $i['vip']=intval($j['medal']);
                     continue;
                 }
             }*/
        }
        return $info;
    }
}