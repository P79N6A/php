<?php
class WeekStarTopn
{

    //主播id
    public static function getWeekStar($uid=0)
    {
        if (!$uid) { $uid=0;
        }
        $key_the_week="new_week_star_gift_top_3:the_week";
        $key_last_week="new_week_star_gift_top_3:last_week";
        $cache = Cache::getInstance("REDIS_CONF_CACHE");

        $the_week=$cache->get($key_the_week);
        $the_week=json_decode($the_week, true);
        $the_week=self::getHeader($the_week, $uid);

        $last_week=$cache->get($key_last_week);
        $last_week=json_decode($last_week, true);
        $last_week=self::getHeader($last_week, $uid);

        return [
            'the_week'=>$the_week,
            'last_week'=>$last_week,
        ];
    }

    private static function statStarWeekByWeek($week)
    {
        $result=[];
        $weekStarDao=new DAOWeekStar();
        $weekStar=$weekStarDao->getGiftByWeek($week);
        if ($weekStar) {
            $giftLogDao=new DAOGiftLog();
            $gifts=DAOWeekStar::parseGift($weekStar['gifts']);
            foreach ($gifts as $i){
                $result[$i]['receive']=$giftLogDao->getReceiveGiftNumTopN($i, $weekStar['week_start'], $weekStar['week_end']);
                $result[$i]['send']=$giftLogDao->getSendGiftNumTopN($i, $weekStar['week_start'], $weekStar['week_end']);
            }
        }
        $weekStar['ext']=$result;
        return $weekStar;
    }

    //1分钟
    public static function theWeek()
    {
        $now=time();
        $week=date("Y", $now).'-'.date("W", $now);
        $key="new_week_star_gift_top_3:the_week";
        $info=self::statStarWeekByWeek($week);
        if ($info) {
            $re=self::getExt($info);
            $cache = Cache::getInstance("REDIS_CONF_CACHE");
            $cache->set($key, json_encode($re));
        }
    }

    //每周一早上1点统计上一周的数据
    public static function lastWeek($updateCacheOnly=false)
    {
        $now=time();
        $time=$now-7*24*3600;
        $today=date("Y-m-d H:i:s");
        $week=date("Y", $time).'-'.date("W", $time);
        $key="new_week_star_gift_top_3:last_week";
        $info=self::statStarWeekByWeek($week);
        if ($info) {
            $re=self::getExt($info);
            $cache = Cache::getInstance("REDIS_CONF_CACHE");
            $cache->set($key, json_encode($re));
            if ($updateCacheOnly) { return;
            }
            $daoWeekStarTopn=new DAOWeekStarTopn();
            $daoWeekStarTopn->startTrans();
            try{
                foreach ($info['ext'] as $id=>$v){
                    foreach ($v['receive'] as $i){
                        $daoWeekStarTopn->add($week, $info['week_start'], $info['week_end'], $i['uid'], $id, $i['total'], $today, DAOWeekStarTopn::DIRECT_RECEIVE);
                    }
                    foreach ($v['send'] as $i){
                        $daoWeekStarTopn->add($week, $info['week_start'], $info['week_end'], $i['uid'], $id, $i['total'], $today, DAOWeekStarTopn::DIRECT_SEND);
                    }
                }

                $daoWeekStarTopn->commit();
            }catch (Exception $e){
                $daoWeekStarTopn->rollback();
            }

        }
    }

    private static function getExt($info)
    {
        $giftDao=new DAOGift();
        $user=new User();
        foreach ($info['ext'] as $id=>&$v){
            $giftInfo=$giftDao->getInfo($id);
            if (empty($giftInfo)) { continue;
            }
            $v['gift']=[
                'giftid'=>$id,
                'image'=>Util::joinStaticDomain($giftInfo['image']),
                'name'=>$giftInfo['name'],
            ];
            foreach ($v['receive'] as &$i){
                $userInfo=$user->getUserInfo($i['uid']);
                if (empty($userInfo)) { continue;
                }
                $i['avatar']=$userInfo?$userInfo['avatar']:"";
                $i['nickname']=$userInfo?$userInfo['nickname']:"";
                $i['gender']=$userInfo?$userInfo['gender']:"M";
                $i['icon']=$userInfo?self::getIcon($userInfo['medal']):null;
                $i['level']=$userInfo?$userInfo['level']:0;
                $i['num']=$i['total'];
                $i['direct']=DAOWeekStarTopn::DIRECT_RECEIVE;
            }
            foreach ($v['send'] as &$i){
                $userInfo=$user->getUserInfo($i['uid']);
                if (empty($userInfo)) { continue;
                }
                $i['avatar']=$userInfo?$userInfo['avatar']:"";
                $i['nickname']=$userInfo?$userInfo['nickname']:"";
                $i['gender']=$userInfo?$userInfo['gender']:"M";
                $i['icon']=$userInfo?self::getIcon($userInfo['medal']):null;
                $i['level']=$userInfo?$userInfo['level']:0;
                $i['num']=$i['total'];
                $i['direct']=DAOWeekStarTopn::DIRECT_SEND;
            }

        }
        return $info;
    }

    private static  function getIcon($medal)
    {
        if (is_array($medal)) {
            $map=array(
                'yellow'=>11,
                'blue'=>12,
                'purple'=>13,
                'red'=>14
            );
            foreach ($medal as $i){
                if ($i['kind']=='tuhao') {
                    if ($i['medal']) {
                        return $i['medal'];
                    }

                }
            }

            foreach ($medal as $i){
                if ($i['kind']=='v') {
                    if (isset($i['medal'])&&isset($map[$i['medal']])) {
                        return $map[$i['medal']];
                    }

                }
            }

        }
        return null;
    }

    public static function getHeader($info,$uid)
    {
        $header=[
            'u'=>null,
            'r'=>[],
        ];
        if ($uid) {
            $user=new User();
            $userInfo=$user->getUserInfo($uid);
            $u['avatar']=$userInfo?$userInfo['avatar']:"";
            $u['nickname']=$userInfo?$userInfo['nickname']:"";
            $u['gender']=$userInfo?$userInfo['gender']:"M";
            $u['icon']=$userInfo?self::getIcon($userInfo['medal']):null;
            $u['level']=$userInfo?$userInfo['level']:0;
            $header['u']=$u;

            $gifts=DAOWeekStar::parseGift($info['gifts']);
            $giftDao=new DAOGift();
            $giftLogDao=new DAOGiftLog();
            $r=[];
            foreach ($gifts as $i){
                $giftInfo=$giftDao->getInfo($i);
                $t=$giftLogDao->getGiftRankByUid($uid, $i, $info['week_start'], $info['week_end']);
                $r[$i]=array(
                    'giftid'=>$i,
                    'name'=>$giftInfo['name'],
                    'image'=>Util::joinStaticDomain($giftInfo['image']),
                    'uid'=>$uid,
                    'total'=>isset($t['total'])?$t['total']:0,
                    'rank'=>isset($t['rank'])&&$t['rank']<=100?$t['rank']:"100+",
                );
            }

            foreach ($info['ext'] as $i){
                foreach ($i['receive'] as $j){
                    if ($j['uid']==$uid) {
                        $r[$i]['rank']=$j['rank'];
                        $r[$i]['total']=$j['total'];
                    }
                }
            }
            $header['r']=array_values($r);

        }
        $info['header']=$header;
        return $info;
    }
}