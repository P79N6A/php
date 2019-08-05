<?php
class DAOLottoPrize extends DAOProxy
{
    const TYPE_STAR=1;//星光
    const TYPE_DIAMOND=2;//星钻
    const TYPE_BIG_HORN=3;//大喇叭
    const TYPE_SMALL_HORN=4;//小喇叭
    const TYPE_RIDE=5;//座驾
    const TYPE_BAG_GIFT=6;//背包礼物
    const TYPE_ENTITY=7;//实物
    const TYPE_EMPTY=8;//空奖

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_GAME");
        $this->setTableName("lotto_prize");
    }

    public function getPrizeList()
    {
        return $this->getAll("select * from ".$this->getTableName()." order by weight limit 8");
    }

    public function add($id,$name,$num,$icon,$upLimit,$rate,$relateid,$type,$weight,$notice='')
    {
        $now=date("Y-m-d H:i:s");
        /*$d=[
        'id'=>$id,
        'name'=>$name,
        'num'=>$num,
        'icon'=>$icon,
        'upLimit'=>$upLimit,
        'rate'=>$rate,
        'relateid'=>$relateid,
        'type'=>$type,
        'weight'=>$weight,
        'modtime'=>$now,
        'addtime'=>$now,
        'notice'=>$notice,
        ];*/
        $r=[
        $id,$name,$num,$icon,$upLimit,$rate,$relateid,$type,$weight,$now,$now,$notice
        ];
        $sql="replace into ".$this->getTableName()." (prizeid,name,num,icon,upLimit,rate,relateid,type,weight,modtime,addtime,notice) values(?,?,?,?,?,?,?,?,?,?,?,?)";
        return $this->execute($sql, $r, false);
        //return $this->insert($this->getTableName(),$d);
    }

    public function mod($prizeid,$name,$num,$icon,$upLimit,$rate,$relateid,$type,$weight,$notice='')
    {
        $d=[];
        if ($name) {
            $d['name']=$name;
        }
        if ($num) {
            $d['num']=$num;
        }

        if ($icon) {
            $d['icon']=$icon;
        }

        if ($upLimit) {
            $d['upLimit']=$upLimit;
        }
        if ($rate) {
            $d['rate']=$rate;
        }
        if ($relateid) {
            $d['relateid']=$relateid;
        }
        if ($type) {
            $d['type']=$type;
        }
        if ($weight) {
            $d['weight']=$weight;
        }
        if ($notice) {
            $d['notice']=$notice;
        }

        return $this->update($this->getTableName(), $d, 'prizeid=?', ['prizeid'=>$prizeid]);
    }

    public function del($prizeid)
    {
        return $this->delete($this->getTaleName(), 'prizeid=?', ['prizeid'=>$prizeid]);
    }

    public function truncatePrize()
    {
        return $this->execute("truncate table ".$this->getTableName());
    }

    public static function getUnitByType($type=0)
    {
        $map=array(
        self::TYPE_STAR=>'个',
        self::TYPE_DIAMOND=>'个',
        self::TYPE_BIG_HORN=>'个',
        self::TYPE_SMALL_HORN=>'个',
        self::TYPE_RIDE=>'天',
        self::TYPE_BAG_GIFT=>'个',
        self::TYPE_ENTITY=>'个',
        self::TYPE_EMPTY=>'个',
        );
        if ($type) { return isset($map[$type])?isset($map[$type]):'个';
        }
        return $map;
    }
}
?>
