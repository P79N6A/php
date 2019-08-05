<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/31
 * Time: 17:20
 */
class ActivityModule
{
    public static function getModuleById($moduleid)
    {
        $daoActivityModule=new DAOActivityModule();
        return $daoActivityModule->getModuleById($moduleid);
    }

    public static function getModuleConfig($ext,$key='')
    {
        $_ext=@json_decode($ext, true);
        if (!$_ext) { $_ext=[];
        }
        if ($key) {
            if (isset($_ext[$key])) {
                return $_ext[$key];
            }else{
                return null;
            }
        }
        return $_ext;
    }

    public static function addModule($activityid,$roundid,$name,$type,$scripts,array $extends=array())
    {
        $daoActivityModule=new DAOActivityModule();
        $id=$daoActivityModule->add($activityid, $roundid, $name, $type, $scripts, $extends);
        Activity::reloadCache();
        return $id;
    }

    public static function modModule($moduleid,$name,$scripts,array $extends=array())
    {
        $daoActivityModule=new DAOActivityModule();
        $re=$daoActivityModule->mod($moduleid, $name, $scripts, $extends);
        Activity::reloadCache();
        return $re;
    }

    public static function delModule($moduleid)
    {
        $daoActivityModule=new DAOActivityModule();
        $re=$daoActivityModule->del($moduleid);
        Activity::reloadCache();
        return $re;
    }
}