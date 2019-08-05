<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/16
 * Time: 16:27
 */
class ELog
{

    public static function e(array  $data,$key)
    {
        $daoElog=new DAOELog();
        return $daoElog->add($key, $data);
    }
}