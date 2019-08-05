<?php

class T
{
    public static function cleanUnLiveStatus()
    {
        //清理热门bucket非直播状态集合元素
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $name="cache_bucket_element_content_china_live_hot";
        $keys=$cache->zRange($name, 0, -1);
        foreach($keys as $i){
            $m=explode("_", $i);
            if($m[0]==1) {
                $dao=new DAOLive();
                $r=$dao->isLiveRunning($m[1]);
                if(!$r) {
                    $cache->zRem($name, $i);
                }
            }

        }
    }

    public static function cleanUnLiveType()
    {
        //清理热门bucket非直播类型的集合元素
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $name="cache_bucket_element_content_china_live_hot";
        $keys=$cache->zRange($name, 0, -1);
        foreach($keys as $i){
            $t=explode("_", $i);
            if($t[0]==4||$t[0]==3||$t[0]==2) {
                $cache->zRem("cache_bucket_element_content_china_live_hot", $i);
            }
        }
    }

    public static function cleanJsonValue()
    {
        //清理热门bucket元素val为json串的集合元素
        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $name="cache_bucket_element_content_china_live_hot";
        $keys=$cache->zRange($name, 0, -1);
        foreach($keys as $i){
            $s=json_decode($i, true);
            if($s&&is_array($s)) {
                $cache->zRem("cache_bucket_element_content_china_live_hot", $i);
            }
        }
    }

    //tool("images/fa68179a7d6e4b00d4b25c436b2ac4d1");
    public  static  function tool($uri)
    {
        $t=array("_100-100.jpg","_200-200.jpg","_324-324.jpg","_800-800.jpg",".jpg");
        $r=array();
        foreach($t as $i){
            $r[$i]=md5($uri.$i);
        }
        var_dump($r);
    }


    public static  function actRepair($json,$preRemark='')
    {
        $t=$json;
        $data=json_decode($t, true);
        if ($data) {
            foreach ($data as $i){
                try{
                    AccountInterface::wuyiAct($i['uid'], $i['reward_amount'], $preRemark);
                }catch (Exception $e){
                    print_r($i);
                    throw $e;
                }
            }
        }

    }
}