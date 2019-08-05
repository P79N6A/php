<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/6
 * Time: 14:56
 */

/**
 * Class ActivityPromotion
 *
 * 晋级开始时间：stime
 * relation:[support_module=>mid]
 */
class ActivityPromotion
{
    public static function promotion($activityid,$moduleid,$ctl)
    {
        $activityinfo=Activity::getActivityInfo($activityid);
        Interceptor::ensureNotEmpty($activityinfo, ERROR_PARAM_DATA_NOT_EXIST, ' activity not exists');

        $moduleinfo=Activity::getModuleInfoById($activityinfo, $moduleid);
        Interceptor::ensureNotEmpty($moduleinfo, ERROR_PARAM_DATA_NOT_EXIST, ' module not exists');

        $data=self::beforePromotion($activityid, $moduleid, $ctl);


        $config=ActivityModule::getModuleConfig($moduleinfo['extends']);

        $result=0;
        $daopromotion=new DAOActivityPromotion();
        $userRank=ActivityRank::getUidRankOfActivity($data['uid'], $config['relation']["support_module"], true);
        if (!$userRank) { $userRank=['rank'=>0,'score'=>0];
        }
        $result=$daopromotion->add($activityid, $moduleinfo['roundid'], $moduleid, $data['uid'], $data['type'], $userRank['rank'], $userRank['score'], $jury = $data['jury'], $juryid = $data['juryid']);
        /*  if ($data['type']==DAOActivityPromotion::PROMOTION_TYPE_AUTO){

            $promotions=ActivityRank::getPromotion($activityid, $moduleid, $rankname,$topn);
            $index=0;
            foreach ($promotions as $k=>$v){
                $index++;
                $result[]=$daopromotion->add($activityid,$moduleinfo['roundid'] , $moduleid,$k ,$data['type'] ,$index ,$v,$jury='',$juryid=0 );
            }
        }elseif ($data['type']==DAOActivityPromotion::RPOMOTION_TYPE_JURY){
            $userRank=ActivityRank::getRankByUid($data['uid'], $activityid, $moduleid);
            if (!$userRank)$userRank=['rank'=>0,'score'=>0];
            $result[]=$daopromotion->add($activityid,$moduleinfo['roundid'] , $moduleid,$data['uid'] ,$data['type'] ,$userRank['rank'] ,$userRank['score'],$jury=$data['jury'],$juryid=$data['juryid'] );
        }*/

        return self::afterPromotion($activityid, $moduleid, $ctl, $result);
    }
    

    private static function beforePromotion($activityid,$moduleid,$ctl)
    {
        $data=[];
        $uid=$ctl->getParam('uid', 0);
        Interceptor::ensureNotFalse($uid>0, ERROR_PARAM_INVALID_FORMAT, "uid is null");
        $data['uid']=$uid;

        $type=$ctl->getParam('type', 1);
        Interceptor::ensureNotNull($type, ERROR_PARAM_INVALID_FORMAT, "type is null");
        $data['type']=$type;

        $jury=$ctl->getParam('jury', '');
        $data['jury']=$jury;
        $juryid=$ctl->getParam('juryid', 0);
        $data['juryid']=$juryid;

        $moduleinfo=ActivityModule::getModuleById($moduleid);
        $config=ActivityModule::getModuleConfig($moduleinfo['extends']);
        $now=time();
        if (isset($config['stime'])) {
            Interceptor::ensureNotFalse($now>strtotime($config['stime']), ERROR_CUSTOM, "support is not over ,promotion is not begin");
        }

        return $data;
    }

    private static function afterPromotion($activityid,$moduleid,$ctl,$result)
    {
        return $result;
    }
}