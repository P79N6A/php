<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/12
 * Time: 17:50
 */
class ActivityRound
{
    public static function addRound($activityid,$name,$round,$startime,$endtime,array $extends=array())
    {
        $daoActivityRound=new DAOActivityRound();
        $id=$daoActivityRound->add($activityid, $name, $round, $startime, $endtime, $extends);
        Activity::reloadCache();
        return $id;
    }

    public static function modRound($roundid,$name,$round,$startime,$endtime,array $extends=array())
    {
        $daoActivityRound=new DAOActivityRound();
        $re=$daoActivityRound->mod($roundid, $name, $round, $startime, $endtime, $extends);
        Activity::reloadCache();
        return $re;
    }

    public static function delRound($roundid)
    {
        $daoRound=new DAOActivityRound();
        $daoRound->starTrans();
        try{
            $daoModule=new DAOActivityModule();
            $modules=$daoModule->getModuleByRoundId($roundid);
            foreach ($modules as $i){
                $daoModule->del($i['moduleid']);
            }
            $daoRound->del($roundid);
            $daoRound->commit();
        }catch (Exception $e){
            $daoRound->rollback();
            throw $e;
        }
        Activity::reloadCache();
    }
}