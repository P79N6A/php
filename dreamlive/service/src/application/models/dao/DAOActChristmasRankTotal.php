<?php
class DAOActChristmasRankTotal extends DAOProxy
{
    public static $rideLevelMap=array(
    1=>1,//1级1次
    2=>10,//2级10次
    3=>30,//3级30次
    4=>50,//4级50次
    );
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_ACTIVITY_JAVA");
        $this->setTableName("activity_christmas_rank_total");
    }

    public function setRank($uid,$utype,$actid=0,$score=1)
    {
        $d=array();
        $r=$this->getRow("select * from ".$this->getTableName()." where uid=? and utype=? and actid=? limit 1", array('uid'=>$uid,'utype'=>$utype,'actid'=>$actid));
        if ($r) {
            $d=array(
            'modtime'=>date("Y-m-d H:i:s"),
            'num'=>$r['num']+$score,
            );
            return $this->update($this->getTableName(), $d, 'id=?', array('id'=>$r['id']));
        }else{
            $now=date("Y-m-d H:i:s");
            $d=array(
            'uid'=>$uid,
            'utype'=>$utype,
            'num'=>$score,
            'modtime'=>$now,
            'addtime'=>$now,
            'actid'=>$actid,
            );
            return $this->insert($this->getTableName(), $d);
        }
    }
    //获取土豪座驾等级
    public function getRichManRideLevel($uid)
    {
        $num=0;
        $r=$this->getRow("select * from ".$this->getTableName()." where uid=? and utype=? and actid=0 limit 1", array('uid'=>$uid,'utype'=>ActChristmas::UTYPE_RICH_MAN));
        if ($r) { $num=$r['num'];
        }
        if ($num<0) { $num=0;
        }
        if ($num==0) { return 0;
        }
        $map=self::$rideLevelMap;
        if ($num>=$map[1]&&$num<$map[2]) {
            return 1;
        }
        if ($num>=$map[2]&&$num<$map[3]) {
            return 2;
        }
        if ($num>=$map[3]&&$num<$map[4]) {
            return 3;
        }
        if ($num>=$map[4]) {
            return 4;
        }
        return 0;
    }


    //获取土豪座驾等级
    public function getRichManRide($uid,$config,$actid=0)
    {
        
        $num=0;
        $r=$this->getRow("select * from ".$this->getTableName()." where uid=? and utype=? and actid=? limit 1", array('uid'=>$uid,'utype'=>ActChristmas::UTYPE_RICH_MAN,'actid'=>$actid));
        if ($r) { $num=$r['num'];
        }
        if ($num<0) { $num=0;
        }
        if ($num==0) { return 0;
        }
        $re=0;
        foreach ($config as $level=>$v){
            if ($num>=$v['num']) {
                $re= $v['id'];
            }
        }
        return $re;
    }
    
    public function getTotalRank($lines=2,$actid=0)
    {
        $anchorTotalRank=$this->getAll("select uid,num as total ,addtime from ".$this->getTableName()." where utype=? and actid=? order by total desc,addtime asc limit ".$lines, array('utype'=>ActChristmas::UTYPE_ANCHOR,'actid'=>$actid));
        $richmanTotalRank=$this->getAll("select uid,num as total ,addtime from ".$this->getTableName()." where utype=? and actid=? order by total desc ,addtime asc limit ".$lines, array('utype'=>ActChristmas::UTYPE_RICH_MAN,'actid'=>$actid));
        array_walk(
            $anchorTotalRank, function (&$v,$k) {
                $v['rank']=$k+1;
            } 
        );
        array_walk(
            $richmanTotalRank, function (&$v,$k) {
                $v['rank']=$k+1;
            } 
        );
        return array(
        'anchorTotalRank'=>$anchorTotalRank,
        'richmanTotalRank'=>$richmanTotalRank,
        );
    }
}
?>
