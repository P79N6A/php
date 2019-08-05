<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/20
 * Time: 11:27
 */
class TeamPk
{
    const TEAM_RED=1;
    const TEAM_BLUE=2;

    const ACT_STATE_UNBEGIN=1;
    const ACT_STATE_RUNNING=2;
    const ACT_STATE_END=3;

    const ACT_RED_BLUE_PK_KEY='act_red_blue_pk';

    const ACT_UNREGISTER=1;
    const ACT_REGISTER=2;

    public static function register($uid,$group)
    {
        Interceptor::ensureNotFalse(($group == self::TEAM_RED||$group == self::TEAM_BLUE), ERROR_CUSTOM, '只能加入红蓝两队');
        Interceptor::ensureNotFalse($uid>0, ERROR_CUSTOM, '用户id不能空');
        //$daoLive = new DAOLive();
        //$re=$daoLive->isLiveRunning($uid);

        $daoTeamPk=new DAOTeamPk();
        return $daoTeamPk->add($uid, $group);
    }

    public static function getRank($anchorid,$uid)
    {
        $default=array(
            'anchors'=>array('red'=>array(),'blue'=>array(),'redScore'=>0,'blueScore'=>0),
            'richs'=>array('red'=>array(),'blue'=>array(),'redScore'=>0,'blueScore'=>0),
            'me'=>array(),
        );
        $cache=Cache::getInstance("REDIS_CONF_CACHE");
        $key=self::ACT_RED_BLUE_PK_KEY;
        $data=$cache->get($key);
        $data=json_decode($data, true);
        if (!$data) {
            $data=$default;
        }
        $data['me']=new stdClass();
        if ($anchorid!=0/*&&$uid!=0&&$anchorid==$uid*/) {
            $data['me']=self::getCurAnchor($anchorid);
        }
        return $data;
    }

    //没分钟一次
    public static function makeRank()
    {
        $state=self::getState();
        if ($state!=self::ACT_STATE_RUNNING) { return;
        }
        $data=self::makeData();
        $data['anchors']['red']=self::getExt($data['anchors']['red']);
        $data['anchors']['blue']=self::getExt($data['anchors']['blue']);
        $data['richs']['red']=self::getExt($data['richs']['red']);
        $data['richs']['blue']=self::getExt($data['richs']['blue']);

        $cache=Cache::getInstance("REDIS_CONF_CACHE");
        $key=self::ACT_RED_BLUE_PK_KEY;
        $cache->set($key, json_encode($data));
    }

    public static function makeData()
    {
        $c=self::config();
        $stime=$c['stime'];
        $etime=$c['etime'];
        $red=$c['gifts']['red'];
        $blue=$c['gifts']['blue'];

        $redAnchors=array();
        $blueAnchors=array();
        $redAnchorsScore=0;
        $blueAnchorsScore=0;

        $redRichs=array();
        $blueRichs=array();
        $redRichsScore=0;
        $blueRichsScore=0;

        $anchors=self::getAnchors();

        $daoGiftLog=new DAOGiftLog();
        if (!empty($anchors[self::TEAM_RED])) {
            $ruids=array_column($anchors[self::TEAM_RED], 'uid');
            $redAnchors=$daoGiftLog->getGiftActTopN($red, $stime, $etime, 'R', $ruids);
            $redt=$daoGiftLog->getScore($red, $stime, $etime, $ruids);
            if ($redt) { $redAnchorsScore=$redt['total'];
            }
        }
        if (!empty($anchors[self::TEAM_BLUE])) {
            $buids=array_column($anchors[self::TEAM_BLUE], 'uid');
            $blueAnchors=$daoGiftLog->getGiftActTopN($blue, $stime, $etime, 'R', $buids);
            $bluet=$daoGiftLog->getScore($blue, $stime, $etime, $buids);
            if ($bluet) { $blueAnchorsScore=$bluet['total'];
            }
        }

        $redR=$daoGiftLog->getGiftActTopN($red, $stime, $etime, 'S');
        if ($redR) { $redRichs=$redR;
        }
        $redRt=$daoGiftLog->getScore($red, $stime, $etime);
        if ($redRt) { $redRichsScore=$redRt['total'];
        }

        $blueR=$daoGiftLog->getGiftActTopN($blue, $stime, $etime, 'S');
        if ($blueR) { $blueRichs=$blueR;
        }
        $blueRt=$daoGiftLog->getScore($blue, $stime, $etime);
        if ($blueRt) { $blueRichsScore=$blueRt['total'];
        }

        return array(
            'anchors'=>array('red'=>$redAnchors,'blue'=>$blueAnchors,'redScore'=>$redAnchorsScore,'blueScore'=>$blueAnchorsScore),
            'richs'=>array('red'=>$redRichs,'blue'=>$blueRichs,'redScore'=>$redRichsScore,'blueScore'=>$blueRichsScore),
        );
    }

    public static function getAnchors()
    {
        $daoTeamPk=new DAOTeamPk();
        $anchors=$daoTeamPk->getList();
        $re=array();
        foreach ($anchors as $i){
            $re[$i['group']][]=$i;
        }
        return $re;
    }

    public static function getCurAnchor($anchorid)
    {
        $result=array('state'=>self::ACT_UNREGISTER,'group'=>0,'uid'=>$anchorid,'rank'=>'50+','total'=>0);
        $daoTeamPk=new DAOTeamPk();
        $re=$daoTeamPk->getAnchorState($anchorid);
        if($re) {
            $result['state']=self::ACT_REGISTER;
            $result['group']=$re['group'];

            $c=self::config();
            $giftid=$re['group']==self::TEAM_RED?$c['gifts']['red']:$c['gifts']['blue'];
            $anchors=self::getAnchors();
            $ruids=array_column($anchors[$re['group']], 'uid');
            $daoGiftLog=new DAOGiftLog();
            $redAnchors=$daoGiftLog->getGiftActTopN($giftid, $c['stime'], $c['etime'], 'R', $ruids, 50);

            foreach ($redAnchors as $i){
                if ($i['uid']==$anchorid) {
                    $result['rank']=$i['rank'];
                    $result['total']=$i['total'];
                    break;
                }
            }

        }
        $tmp=self::getExt(array($result));
        if ($tmp) {
            $result=$tmp[0];
        }
        return $result;
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

    public static function getState()
    {
        $now=time();
        $c=self::config();
        $stime=strtotime($c['stime']);
        $etime=strtotime($c['etime']);
        if ($now<$stime) {
            return self::ACT_STATE_UNBEGIN;
        }elseif ($now>=$stime&&$now<=$etime) {
            return self::ACT_STATE_RUNNING;
        }elseif ($now>$etime) {
            return self::ACT_STATE_END;
        }else{
            return self::ACT_STATE_END;
        }

    }
    public static function config()
    {
        return array(
            'stime'=>'2018-03-26 00:00:00',
            'etime'=>'2018-04-01 23:59:59',
            'gifts'=>array('red'=>3895,'blue'=>3896),
        );
    }
}