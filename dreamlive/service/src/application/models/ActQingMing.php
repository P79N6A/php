<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/2
 * Time: 17:28
 */
class ActQingMing
{
    //const ACT_YUAN_XIAO_KEY='act_qing_ming';
    //const ACT_YUAN_XIAO_KEY='act_20180517_520';
    const ACT_YUAN_XIAO_KEY='act_20180530_61';
    const ACT_STATUS_NOT_BEGIN=1;
    const ACT_STATUS_RUN=2;
    const ACT_STATUS_OVER=3;

    public static function config()
    {
        return array(
            'stime'=>'2018-05-31 00:00:00',
            'etime'=>'2018-06-03 23:59:59',
            'gifts'=>array(3903,),
        );
    }

    public static function getRank($uid=0)
    {
        $r=array('anchorRank'=>array(),'richRank'=>array(),'me'=>new stdClass());
        $cache=Cache::getInstance("REDIS_CONF_CACHE");
        $info=$cache->get(self::ACT_YUAN_XIAO_KEY);
        $info=json_decode($info, true);

        if ($info) {
            $r['anchorRank']=self::getLiveStatus($info['anchorRank']);
            $r['richRank']=self::getLiveStatus($info['richRank']);
        }
        if ($uid) {
            $r['me']=self::getCurAnchor($info['anchorRank'], $uid);
        }
        $r['status']=self::status();
        return $r;
    }


    public static function getCurAnchor($anchorRank,$uid)
    {
        $result=array('uid'=>$uid,'rank'=>'50+','total'=>0);
        $flag=false;

        foreach ($anchorRank as $i){
            if ($i['uid']==$uid) {
                //$result['rank']=$i['rank'];
                //$result['total']=$i['total'];
                $result=$i;
                $flag=true;
                break;
            }
        }
        if (!$flag) {
            $tmp=self::getExt(array($result));
            if ($tmp) {
                $result=$tmp[0];
            }
        }

        return $result;
    }

    public static function getLiveStatus($info)
    {
        $daoLive=new DAOLive();
        foreach ($info as &$i){
            $s=$daoLive->isLiveStatus($i['uid']);
            $i['live']=$s;
        }
        return $info;
    }

    public static function genRank()
    {
        $st=self::status();
        $c=self::config();
        $result=array(
            'anchorRank'=>array(),
            'richRank'=>array(),
        );
        if ($st==self::ACT_STATUS_RUN) {
            $gifts=$c['gifts'];
            if (!empty($gifts)) {

                $cache = Cache::getInstance("REDIS_CONF_CACHE");
                $big_liver = $cache->get("big_liver_keys");
                $big_live_list = $big_liver?explode(',', $big_liver):array();

                $daoGiftLog = new DAOGiftLog();

                $anchorRank = $daoGiftLog->getGiftAmountTopN($gifts, $c['stime'], $c['etime'], "R", 50, $big_live_list);
                if (empty($anchorRank)) { $anchorRank=array();
                }
                $result['anchorRank']=self::getExt($anchorRank);

                $richManRank = $daoGiftLog->getGiftAmountTopN($gifts, $c['stime'], $c['etime'], "S", 50);
                if (empty($richManRank)) { $richManRank=array();
                }
                $result['richRank']=self::getExt($richManRank);
                $cache=Cache::getInstance("REDIS_CONF_CACHE");
                $cache->set(self::ACT_YUAN_XIAO_KEY, json_encode($result));
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
}