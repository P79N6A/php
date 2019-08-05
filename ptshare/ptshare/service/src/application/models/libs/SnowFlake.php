<?php
class SnowFlake
{
    public static function nextId(){
        $hostname = gethostname();
        $workerid = preg_replace("/[^\d]+(\d+)/i", "\\1", $hostname);
        ini_set("snowflake.node", $workerid);
        return snowflake_nextid(); 
    }
}
?>