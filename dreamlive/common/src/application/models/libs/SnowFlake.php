<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/5
 * Time: 13:33
 */
class SnowFlake
{
    public static function nextId()
    {
        $hostname = gethostname();
        $workerid = preg_replace("/[^\d]+(\d+)/i", "\\1", $hostname);
        ini_set("snowflake.node", $workerid);
        return snowflake_nextid(); 
    }
}