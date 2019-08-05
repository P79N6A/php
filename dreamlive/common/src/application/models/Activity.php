<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/31
 * Time: 14:26
 */

/**
 * Class Activity
 * 配置：
 */
class Activity
{
    const CURRENT_ACTIVITY_LIST_CACHE_KEY='model_current_activity_list';
    const HISTORY_ACTIVITY_LIST_CACHE_KEY='model_history_activity_list';

    public static function getActivityInfo($activityid)
    {
        $result=[];
        $result=self::getCurrentActivityInfo($activityid);
        if (empty($result)) {
            $dao_activity=new DAOActivity();
            $result=$dao_activity->getActivityById($activityid);
            if (!empty($result)) {
                $dao_round=new DAOActivityRound();
                $dao_module=new DAOActivityModule();
                $result['round_list']=$dao_round->getRoundByActivityId($result['activityid']);
                $result['module_list']=$dao_module->getModuleByActivityId($result['activityid']);
            }

        }

        return $result;
    }


    public static function getCurrentActivityListByUid($uid)
    {
        $activityList=self::getCurrentActivityList();
        foreach ($activityList as &$i){
            if (!self::chkAnchorTakeActivity($uid, $i['activityid'])) { continue;
            }
            $memberRank=[];
            foreach ($i['module_list'] as $j){
                if ($j['type']!=DAOActivityModule::MODULE_TYPE_SUPPORT) { continue;
                }
                $memberRank=ActivityRank::getUidRankOfActivity($uid, $j['moduleid']);
                break;
            }
            $i['member_rank']=$memberRank;
            if (!$memberRank['rank']&&!$memberRank['score']) {
                $i['member_rank']=null;
            }

        }
        return $activityList;
    }

    //判断主播是否参加本次活动
    public static function chkAnchorTakeActivity($anchorId,$activityid)
    {
        //需要报名，检查是否报名
        //不需要报名，检查是否有礼物，检查是否有投票
        return true;
    }

    //修改模块和轮次也要更新此缓存
    public static function getCurrentActivityList()
    {
        /*$cache = Cache::getInstance("REDIS_CONF_CACHE");
        $activity_list = $cache->get(self::CURRENT_ACTIVITY_LIST_CACHE_KEY);
        $activity_list=@json_decode($activity_list,true);
        if (!$activity_list){
            $dao_activity=new DAOActivity();
            $activity_list=$dao_activity->getCurrentActivityList();
            $dao_round=new DAOActivityRound();
            $dao_module=new DAOActivityModule();
            foreach ($activity_list as &$i){
                $i['round_list']=$dao_round->getRoundByActivityId($i['activityid']);
                $i['module_list']=$dao_module->getModuleByActivityId($i['activityid']);
            }
            if (!$activity_list)$activity_list=[];
            $cache->set(self::CURRENT_ACTIVITY_LIST_CACHE_KEY,json_encode($activity_list) );
        }*/
        $dao_activity=new DAOActivity();
        $activity_list=$dao_activity->getCurrentActivityList();
        $dao_round=new DAOActivityRound();
        $dao_module=new DAOActivityModule();
        foreach ($activity_list as &$i){
            $i['round_list']=$dao_round->getRoundByActivityId($i['activityid']);
            $i['module_list']=$dao_module->getModuleByActivityId($i['activityid']);
        }
        if (!$activity_list) { $activity_list=[];
        }

        //再次过滤掉已经过期的
        //最好把已经过期的活动，设置一个结束状态。
        return $activity_list;
    }

    //刷新缓存
    public static function reloadCache()
    {
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $dao_activity=new DAOActivity();
        $activity_list=$dao_activity->getCurrentActivityList();
        $dao_round=new DAOActivityRound();
        $dao_module=new DAOActivityModule();
        foreach ($activity_list as &$i){
            $i['round_list']=$dao_round->getRoundByActivityId($i['activityid']);
            $i['module_list']=$dao_module->getModuleByActivityId($i['activityid']);
        }
        if (!$activity_list) { $activity_list=[];
        }
        $cache->set(self::CURRENT_ACTIVITY_LIST_CACHE_KEY, json_encode($activity_list));
    }


    public static function getCurrentActivityInfo($activityid)
    {
        $info=[];
        $list=self::getCurrentActivityList();
        foreach ($list as $i){
            if ($i['activityid']==$activityid) {
                $info=$i;
                break;
            }
        }
        return $info;
    }

    public static function getModuleInfoByType($activityinfo,$moduletype)
    {
        $modulelist=$activityinfo['module_list'];
        foreach ($modulelist as $i){
            if ($i['type']==$moduletype) {
                return $i;
            }
        }
        return null;
    }

    public static function getModuleInfoById($activityinfo,$moduleid)
    {
        $modulelist=$activityinfo['module_list'];
        foreach ($modulelist as $i){
            if ($i['moduleid']==$moduleid) {
                return $i;
            }
        }
        return null;
    }


    //检查活动是否有报名
    public static function hasOneModuleByType($modulelist,$roundid,$type)
    {
        foreach ($modulelist as $i){
            if ($i['type']==$type&&$i['roundid']==$roundid) {
                return true;
            }
        }
        return false;
    }

    public static function addActivity($type,$name,$icon,$url,$vote,$online,$startime,$endtime,$remark,array $extends=array())
    {
        $daoActivity=new DAOActivity();
        $id=$daoActivity->add($type, $name, $icon, $url, $vote, $online, $startime, $endtime, $remark, $extends);
        self::reloadCache();
        return $id;
    }
    public static function modActivity($activityid,$name,$icon,$url,$vote,$online,$startime,$endtime,$remark,$extends)
    {
        $daoActivity=new DAOActivity();
        $re=$daoActivity->mod($activityid, $name, $icon, $url, $vote, $online, $startime, $endtime, $remark, $extends);
        self::reloadCache();
        return $re;
    }

    public static function delActivity($activityid)
    {
        $daoActivity=new DAOActivity();
        $daoActivity->starTrans();
        try{
            $daoRound=new DAOActivityRound();
            $rounds=$daoRound->getRoundByActivityId($activityid);
            $daoModule=new DAOActivityModule();
            foreach ($rounds as $i){
                $modules=$daoModule->getModuleByRoundId($i['roundid']);
                foreach ($modules as $j){
                    $daoModule->del($j['moduleid']);
                }
                $daoRound->del($i['roundid']);
            }
            $daoActivity->del($activityid);
            $daoActivity->commit();
        }catch (Exception $e){
            $daoActivity->rollback();
            throw $e;
        }
        self::reloadCache();
    }
}