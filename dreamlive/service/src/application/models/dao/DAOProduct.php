<?php
class DAOProduct extends DAOProxy
{
    const CATEID_BEAUTY=1;//"美颜";
    const CATEID_EFFECT=2;//"滤镜";
    const CATEID_FACEU=3;//"FaceU";
    const CATEID_RIDE=4;//座驾
    const CATEID_GUARD=5;//守护
    const CATEID_BIG_HORN=6;//大喇叭
    const CATEID_SMALL_HORN=8;//小喇叭
    const CATEID_FREE_LOTTO_TICKET=9;//免费转盘抽奖券
    const CATEID_ANCHOR_LEVEL_TOKEN=10;//主播段位标识

    const TYPE_GOLD=1;//金守护
    const TYPE_SILVER=2;//银守护

    const TAG_HOT=1;//热门
    const TAG_NEW=2;//最新
    const TAG_CLASSIC=3;//经典
    const TAG_FACEU=4;//
    const TAG_NOBLE=5;//贵族
    const TAG_EFFECT=6;//特效

    const DELETED_YES="Y";
    const DELETED_NO="N";

    const ONLINE_YES="Y";
    const ONLINE_NO="N";

    const UNIT_SECOND="S";//秒
    const UNIT_DAY="D";//天
    const UNIT_MONTH="M";//月
    const UNIT_YEAR="Y";//年
    const UNIT_NUM="N";//个

    const PRODUCT_TYPE_EXPIRE=1;//有效期型
    const PRODUCT_TYPE_NUM=2;//数量型



    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("product");
    }

    public function getOneByProductId($productid)
    {
        $sql = "select * from " . $this->getTableName() . " where productid=? limit 1";
        $one = $this->getRow($sql, [intval($productid)]);

        return $one ?  : [];
    }

    public function getListWithCateid($cateid)
    {
        $sql = "select " . $this->_getSelectFields() . " from " . $this->getTableName();
        $cateid = intval($cateid);
        if($cateid > 0 ) {
            $sql .= " where cateid=?";
        }
        $sql .= " order by weight asc";
        $list = $this->getAll($sql, [$cateid]);

        return $list;
    }

    private function _getSelectFields()
    {
        /*{{{*/
        return "productid, name, image, cateid, tag, price, expire, currency, online, mark, extends";
    }/*}}}*/

    public function addProduct($name,$image,$cateid,$type,$tag,$price,$expire,$unit,$currency,$online,$deleted,$mark,$weight,$remark,$extends=array())
    {
        $now=date("Y-m-d H:i:s");
        $data=array(
        'name'=>$name,
        "image"=>$image,
        "cateid"=>$cateid,
        "type"=>$type,
        "tag"=>$tag,
        "price"=>$price,
        "expire"=>$expire,
        "unit"=>$unit,
        "currency"=>$currency,
        "online"=>$online,
        "deleted"=>$deleted,
        "mark"=>$mark,//角标
        "weight"=>$weight,
        "remark"=>$remark,
        "extends"=>json_encode($extends),
        "addtime"=>$now,
        "modtime"=>$now,
        );

        return $this->insert($this->getTableName(), $data);
    }

    public function modifyProduct($productid,$name,$image,$cateid,$type,$tag,$price,$expire,$unit,$currency,$online,$deleted,$mark,$weight,$remark,$extends=array())
    {
        $data=array();
        if ($name) {
            $data['name']=$name;
        }
        if($image) {
            $data['image']=$image;
        }
        if ($cateid) {
            $data['cateid']=$cateid;
        }
        if ($type) {
            $data['type']=$type;
        }
        if ($tag) {
            $data['tag']=$tag;
        }
        if($price!=''&&$price>=0) {
            $data['price']=$price;
        }
        if($expire) {
            $data['expire']=$expire;
        }
        if($unit) {
            $data['unit']=$unit;
        }
        if ($currency) {
            $data['currency']=$currency;
        }
        if ($online) {
            $data['online']=$online;
        }
        if ($deleted) {
            $data['deleted']=$deleted;
        }
        if ($mark) {
            $data['mark']=$mark;
        }
        if ($weight) {
            $data['weight']=$weight;
        }
        if ($remark) {
            $data['remark']=$remark?$remark:"";
        }
        if (!empty($extends)) {
            $data['extends']=json_encode($extends);
        }
        $data['modtime']=date("Y-m-d H:i:s");
        return $this->update($this->getTableName(), $data, "productid=?", $productid);
    }

    public function getProductByCateids(array $cateids)
    {
        $sql = "select * from " . $this->getTableName();
        if(!empty($cateids) ) {
            $sql .= " where cateid in (".implode(',', $cateids).")";
        }
        $sql .= " order by weight asc";
        return $this->getAll($sql, '');
    }

    public function getOnlyOneByCateid($cateid)
    {
        return $this->getRow("select * from ".$this->getTableName()." where cateid=? and deleted='N' order by productid desc limit 1", array('cateid'=>$cateid));
    }
}
?>

