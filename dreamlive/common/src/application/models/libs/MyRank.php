<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/9
 * Time: 19:38
 */
class MyRank
{

    const RANK_ORDER_MIN2MAX=1;//分数从小到大
    const RANK_ORDER_MAX2MIN=2;//分数从大到小

    private $name='';
    private $redis=null;

    public function __construct($name)
    {
        $this->name=$name;
        if (!$this->redis) {
            $this->redis=Cache::getInstance("REDIS_CONF_CACHE");
            //$this->redis->setOption(Redis::OPT_READ_TIMEOUT,-1);
        }
    }

    public function modScore($member,$score)
    {
        return $this->redis->zIncrBy($this->name, $score, $member);
    }

    public function addMember($member,$score=0)
    {
        return $this->redis->zAdd($this->name, $score, $member);
    }

    public function remMember($member)
    {
        return $this->redis->zRem($this->name, $member);
    }

    public function setExpire($expire)
    {
        $this->redis->expire($this->name, $expire);
    }

    public function hasRank()
    {
        return $this->redis->exists($this->name);
    }

    public function delRank()
    {
        return $this->redis->del($this->name);
    }

    public function totalMemberCount()
    {
        return $this->redis->zCard($this->name);
    }

    public function scoreScaleMemberCount($min,$max)
    {
        return $this->redis->zCount($this->name, $min, $max);
    }

    public function getMemberScore($member)
    {
        return $this->redis->zScore($this->name, $member);
    }

    public function getMemberRankByOrder($member,$order=2)
    {
        if ($order==self::RANK_ORDER_MIN2MAX) {
            $re=$this->redis->zRank($this->name, $member);
            return $re+1;
        }elseif ($order==self::RANK_ORDER_MAX2MIN) {
            $re=$this->redis->zRevRank($this->name, $member);
            return $re+1;
        }
    }

    public function getRankByIndex($startIndex,$endIndex,$order=2)
    {
        if ($order==self::RANK_ORDER_MIN2MAX) {
            return $this->redis->zRange($this->name, $startIndex, $endIndex, true);
        }elseif ($order==self::RANK_ORDER_MAX2MIN) {
            return $this->redis->zRevRange($this->name, $startIndex, $endIndex, true);
        }
    }


    public function getRankByScore($startScore,$endScore,$order=1,$offset=0,$count=0)
    {
        $options=['withscores'=>true,];
        if ($offset&&$count) {
            $options['limit']=array($offset,$count);
        }

        if ($order==self::RANK_ORDER_MIN2MAX) {
            return $this->redis->zRangeByScore($this->name, $startScore, $endScore, $options);
        }elseif ($order==self::RANK_ORDER_MAX2MIN) {
            return $this->redis->zRevRangeByScore($this->name, $startScore, $endScore, $options);
        }
    }

    public function makeDayRankName()
    {
        return $this->name.":".date("Y-m-d");
    }

    public function makeWeekRankName()
    {
        return $this->name.":".date("Y-m-d-w");
    }

    public function getRankJson()
    {
        return json_encode($this->getRankByIndex(0, -1));
    }
}