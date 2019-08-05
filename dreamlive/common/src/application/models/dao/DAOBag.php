<?php
class DAOBag extends DAOProxy
{
    const BAG_SOURCE_GIFT=1;//来自礼物
    const BAG_SOURCE_STORE=2;//来自商城

    const BAG_TYPE_NUM=1;//数量叠加
    const BAG_TYPE_EXP=2;//有效期叠加

    const BAG_STATUS_ON='ON';
    const BAG_SATTUS_OFF='OFF';

    const BAG_CATEID_BEAUTY=1;//美颜
    const BAG_CATEID_FILTER=2;//滤镜
    const BAG_CATEID_FACEU=3;//FACEU
    const BAG_CATEID_RIDE=4;//座驾
    const BAG_CATEID_GUARD=5;//守护
    const BAG_CATEID_BIG_HORN=6;//大喇叭
    const BAG_CATEID_GIFT=7;//礼物
    const BAG_CATEID_SMALL_HORN=8;//小喇叭
    const BAG_CATEID_FREE_LOTTO_TICKET=9;//免费转盘抽奖券
    const BAG_CATEID_ANCHOR_LEVEL_TOKEN=10;//主播段位标识


    const BAG_TAG_GIFT=1;//礼物
    const BAG_TAG_PROP=2;//道具
    const BAG_TAG_PRIVILEGE=3;//特权

    const UPDATE_OP_ADD="ADD";
    const UPDATE_OP_SUB="SUB";

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("bag");
    }

    //根据uid得到有效坐骑
    public function getUsedRide($uid)
    {
        return $this->getRow(
            "select * from ".$this->getTableName()." where uid=? and cateid=? and status=? and  UNIX_TIMESTAMP(expiretime)>UNIX_TIMESTAMP('".date('Y-m-d H:i:s')."') order by addtime desc limit 1",
            ['uid'=>$uid,'cateid'=>self::BAG_CATEID_RIDE,'status'=>self::BAG_STATUS_ON]
        );
    }
    //获取背包喇叭数
    public function getUserHorn($uid)
    {
        return $this->getAll("select * from ".$this->getTableName()." where uid=? and cateid in (".self::BAG_CATEID_BIG_HORN.",".self::BAG_CATEID_SMALL_HORN.")", ['uid'=>$uid]);
    }
    //获取背包免费抽奖券
    public function getUserFreeLottoTicket($uid)
    {
        return $this->getRow("select * from ".$this->getTableName()." where uid=? and cateid=? and num>0 order by id desc limit 1", ['uid'=>$uid,'cateid'=>self::BAG_CATEID_FREE_LOTTO_TICKET]);
    }

    //获取圣诞老人福袋
    public function getChristmasWishBag($uid,$relateid)
    {
        return $this->getRow("select * from ".$this->getTableName()." where uid=? and cateid =? and relateid=? and num>0 order by id desc limit 1", ['uid'=>$uid,'cateid'=>self::BAG_CATEID_GIFT,'relateid'=>$relateid]);
    }

    //用户是否拥有某个坐骑
    public function getUsedRideByRelateid($uid,$relateid=0)
    {
        return $this->getRow(
            "select * from ".$this->getTableName()." where uid=? and cateid=? and relateid=? and  UNIX_TIMESTAMP(expiretime)>UNIX_TIMESTAMP('".date('Y-m-d H:i:s')."') order by addtime desc limit 1",
            ['uid'=>$uid,'cateid'=>self::BAG_CATEID_RIDE,'relateid'=>$relateid]
        );
    }

    public function getListByUid($uid)
    {
        $sql = "select " . $this->_getSelectFields() . " from " . $this->getTableName();
        $uid = intval($uid);
        if($uid > 0 ) {
            $sql .= " where uid=?";
        }
        $list = $this->getAll($sql, [$uid]);

        return $list;
    }

    public function getUserBagByProductid($uid, $productid)
    {
        $sql = "select " . $this->_getSelectFields() . " from " . $this->getTableName() . " where uid=? and productid=?";
        $list = $this->getAll($sql, [$uid, $productid]);

        return $list;
    }

    public function insert_drop($orderid, $uid, $productid, $product_expire, $expiretime)
    {
        $sql = "INSERT INTO {$this->getTableName()} (orderid,uid,productid,product_expire,expiretime,addtime) VALUES (?,?,?,?,?,?)";
        return $this->Execute($sql, array($orderid, $uid, $productid, $product_expire, $expiretime, date("Y-m-d H:i:s")), false);
    }

    public function add($uid,$cateid,$relateid,$expire,$status,$num,$source,$tag=0,$type=0)
    {
        $hasRide=self::getUsedRide($uid);
        if (empty($hasRide)) {
            $status=self::BAG_STATUS_ON;
        }
        if (!$tag) {
            $tag=self::getTagByCateid($cateid);
        }
        if (!$type) {
            $type=self::getTypeByCateid($cateid);
        }
        $now=date("Y-m-d H:i:s");
        if (self::BAG_TYPE_NUM==$type) {
            $item=$this->getRow("select * from ".$this->getTableName()." where uid=? and cateid=? and relateid=?", ['uid'=>$uid,'cateid'=>$cateid,'relateid'=>$relateid,]);
            if ($item) {
                $d=[
                'num'=>$item['num']+$num,
                'modtime'=>$now,
                ];
                return $this->update($this->getTableName(), $d, 'id=?', ['id'=>$item['id']]);
            }
        }elseif (self::BAG_TYPE_EXP==$type) {
            $item=$this->getRow("select * from ".$this->getTableName()." where uid=? and cateid=? and relateid=? and UNIX_TIMESTAMP(expiretime)>UNIX_TIMESTAMP('".$now."') order by addtime desc limit 1", ['uid'=>$uid,'cateid'=>$cateid,'relateid'=>$relateid]);
            if ($item) {
                $d=[
                'expiretime'=>date("Y-m-d H:i:s", strtotime($item['expiretime'])+$num*$expire),
                'modtime'=>$now,
                ];
                return $this->update($this->getTableName(), $d, 'id=?', ['id'=>$item['id']]);
            }
        }

        $d=[
        'uid'=>$uid,
        'cateid'=>$cateid,
        'relateid'=>$relateid,
        'expire'=>$expire,
        'tag'=>$tag,
        'status'=>$status,
        'num'=>$type==self::BAG_TYPE_EXP?1:$num,
        'addtime'=>$now,
        'modtime'=>$now,
        'source'=>$source,
        'type'=>$type,
        'expiretime'=>$type==self::BAG_TYPE_NUM?'0000-00-00 00:00:00':(date("Y-m-d H:i:s", strtotime($now)+$num*$expire)),
        ];
        return $this->insert($this->getTableName(), $d);
    }

    private function _getSelectFields()
    {
        /*{{{*/
        return "id, uid, addtime, expiretime, productid, product_expire";
    }/*}}}*/

    //分类获取物品列表
    public function getUserBagByCateid($uid,$cateid=0)
    {
        $where='';
        if ($cateid) {
            $where=" and cateid=".$cateid;
        }
        return $this->getAll("select * from ".$this->getTableName()." where uid=? ".$where, ['uid'=>$uid]);
    }

    //多分类获取物品列表
    public function getUserBagByCateids($uid,array $cateids=array())
    {
        $where='';
        if (!empty($cateids)) {
            $where=" and cateid in (".implode(',', $cateids).") ";
        }
        return $this->getAll("select * from ".$this->getTableName()." where uid=? ".$where, ['uid'=>$uid]);
    }

    public function getBagByStatus($uid,$cateid=0,$status="OFF")
    {
        $where='';
        if ($cateid) {
            $where=" and cateid=".$cateid;
        }
        if ($status) {
            $where .=" and status='".$status."' " ;
        }
        return $this->getAll("select * from ".$this->getTableName()." where uid=? ".$where, ['uid'=>$uid]);
    }

    //用id获取
    public function getItemById($bagid)
    {
        return $this->getRow("select * from ".$this->getTableName()." where id=?", ['id'=>$bagid]);
    }


    //更新状态
    public function updateStatusById($bagid,$status)
    {
        $r=[
        'status'=>$status,
        ];
        return $this->update($this->getTableName(), $r, 'id=?', ['id'=>$bagid]);
    }

    public static function getTagByCateid($cateid)
    { 
        $tag=self::BAG_TAG_PROP;
        switch ($cateid){
        case self::BAG_CATEID_BEAUTY:
        case self::BAG_CATEID_FACEU:
        case self::BAG_CATEID_FILTER:
        case self::BAG_CATEID_RIDE:
        case self::BAG_CATEID_BIG_HORN:
        case self::BAG_CATEID_SMALL_HORN:
            $tag=self::BAG_TAG_PROP;
            break;
        case self::BAG_CATEID_GIFT:
            $tag=self::BAG_TAG_GIFT;
            break;
        case self::BAG_CATEID_GUARD:
            $tag=self::BAG_TAG_PRIVILEGE;
            break;
        default:
            $tag=self::BAG_TAG_PROP;
            break;
        }
        return $tag;
    }

    public static function getTypeByCateid($cateid)
    {
        $type=self::BAG_TYPE_NUM;
        switch ($cateid){
        case self::BAG_CATEID_BEAUTY:
        case self::BAG_CATEID_FACEU:
        case self::BAG_CATEID_FILTER:
        case self::BAG_CATEID_RIDE:
        case self::BAG_CATEID_GUARD:
        case self::BAG_CATEID_ANCHOR_LEVEL_TOKEN:
            $type=self::BAG_TYPE_EXP;
            break;
        case self::BAG_CATEID_GIFT:
        case self::BAG_CATEID_BIG_HORN:
        case self::BAG_CATEID_SMALL_HORN:
            $type=self::BAG_TYPE_NUM;
            break;
        default:
            $type=self::BAG_TYPE_NUM;
            break;
        }
        return $type;
    }

    public static function getTagMap()
    {
        return [

        ];
    }
    public static function getTypeMap()
    {
        return [

        ];
    }

    public static function getCateids()
    {
        return [
        self::BAG_CATEID_BEAUTY,//美颜
        self::BAG_CATEID_FILTER,//滤镜
        self::BAG_CATEID_FACEU,//FACEU
        self::BAG_CATEID_RIDE,//座驾
        self::BAG_CATEID_GUARD,//守护
        self::BAG_CATEID_BIG_HORN,//喇叭
        self::BAG_CATEID_SMALL_HORN,//喇叭
        self::BAG_CATEID_GIFT,//礼物,
        self::BAG_CATEID_FREE_LOTTO_TICKET,//抽奖券,
        self::BAG_CATEID_ANCHOR_LEVEL_TOKEN,
        ];
    }

    public function updateBag($bagid,$tag,$status,$num,$expiretime='')
    {
        $d=[];
        if ($tag) {
            $d['tag']=$tag;
        }
        if ($status) {
            $d['status']=$status;
        }
        if ($num>=0) {
            $d['num']=$num;
        }
        if ($expiretime) {
            $d['expiretime']=$expiretime;
        }

        $d['modtime']=date("Y-m-d H:i:s");
        return $this->update($this->getTableName(), $d, 'id=?', ['id'=>$bagid]);
    }

    public function updateBagNum($bagid,$num,$op="SUB")
    {
        if ($op==self::UPDATE_OP_SUB) {
            $update="update ".$this->getTableName()." set num=num-".$num." , modtime='".date("Y-m-d H:i:s")."' where id=? and num>=?";
            return $this->execute($update, array($bagid,$num));
        }elseif ($op==self::UPDATE_OP_ADD) {
            $update="update ".$this->getTableName()." set num=num+".$num." , modtime='".date("Y-m-d H:i:s")."' where id=?";
            return $this->execute($update, array($bagid));
        }

    }

    public function clear($uid)
    {
        return $this->delete($this->getTableName(), "uid=? and (num=0 or UNIX_TIMESTAMP(expiretime)<UNIX_TIMESTAMP('".date('Y-m-d H:i:s')."'))", array('uid'=>$uid));
    }

    public function getBagByUid($uid)
    {
        return $this->getAll("select * from ".$this->getTableName()." where uid=? limit 10000", array('uid'=>$uid));
    }
}
?>
