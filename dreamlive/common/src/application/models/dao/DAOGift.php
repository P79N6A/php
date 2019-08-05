<?php

class DAOGift extends DAOProxy
{
    const GIFT_CONSUME_DIAMOND = "DIAMOND";
    const GIFT_CONSUME_COIN = "COIN";//星光

    const GIFT_TYPE_COMMON=1;//普通礼物
    const GIFT_TYPE_DBCLICK=2;//连击礼物
    const GIFT_TYPE_DYNAMIC=3;//动态礼物
    const GIFT_TYPE_FACEU=4;//faceu礼物

    const GIFT_TAG_NEW = 1;//最新
    const GIFT_TAG_HOT = 2;//热门
    const GIFT_TAG_CLASSIC = 3;//经典
    const GIFT_TAG_INTERACTION = 4;//活动（Faceu） 
    const GIFT_TAG_NOBILITY = 5;//贵族
    const GIFT_TAG_EFFECTS = 6;//特效
    const GIFT_TAG_EXCLUSIVE = 7;//专属
    const GIFT_TAG_BAG=8;//背包礼物

    const GIFT_TAG_LIST = [
        //['name'=>"最新",'sort'=>1,'id'=>self::GIFT_TAG_NEW],
        ['name'=>"热门",'sort'=>2,'id'=>self::GIFT_TAG_HOT],//默认
        //['name'=>"经典",'sort'=>3,'id'=>self::GIFT_TAG_CLASSIC],
        //['name'=>"活动",'sort'=>4,'id'=>self::GIFT_TAG_INTERACTION] ,//faceu
        ['name'=>"贵族",'sort'=>5,'id'=>self::GIFT_TAG_NOBILITY],
        //['name'=>"特效",'sort'=>6,'id'=>self::GIFT_TAG_EFFECTS],
        //['name'=>"专属",'sort'=>7,'id'=>self::GIFT_TAG_EXCLUSIVE],
    ];

    const REGION_LIST = ['china', 'abroad', ''];

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PAYMENT");
        $this->setTableName("gift");
    }

    public function getInfo($giftid)
    {
        $sql = "select * from " . $this->getTableName() . " where giftid=?";
        return $this->getRow($sql, $giftid);
    }

    public function getList($status)
    {
        $sql = "select * from " . $this->getTableName() . " where status=? order by score asc";
        $result=$this->getAll($sql, $status);
        return $result;
    }

    public function addGift($name, $image, $label, $type, $price, $ticket, $consume, $score, $status, $url, $zip_md5, $extends, $tag, $region)
    {
        $info = array(
            "name" => $name,
            "image" => $image,
            "label" => $label,
            "type" => $type,
            "tag" => $tag,
            "region" => in_array($region, self::REGION_LIST) ? $region : '',
            "price" => $price,
            "ticket" => $ticket,
            "consume" => $consume,
            "score" => $score,
            "status" => $status,
            "url" => $url,
            "zip_md5" => $zip_md5,
            "extends" => $extends,
            "addtime" => date("Y-m-d H:i:s"),
            "modtime" => date("Y-m-d H:i:s"),

        );

        return $this->insert($this->getTableName(), $info);
    }

    public function setGift($giftid, $name, $image, $label, $type, $price, $ticket, $consume, $score, $status, $url, $zip_md5, $extends, $tag, $region)
    {
        $info = array();

        if ($name) {
            $info["name"] = $name;
        }

        if ($image) {
            $info["image"] = $image;
        }

        if ($label) {
            $info["label"] = $label;
        }

        if ($type) {
            $info["type"] = $type;
        }

        if ($price) {
            $info["price"] = $price;
        }

        if ($ticket) {
            $info["ticket"] = $ticket;
        }

        if ($consume) {
            $info["consume"] = $consume;
        }
        if ($tag) {
            $info["tag"] = $tag;
        }

        if ($score) {
            $info["score"] = $score;
        }

        if ($status) {
            $info["status"] = $status;
        }

        if ($url) {
            $info["url"] = $url;
            $info["zip_md5"] = $zip_md5;
        }

        if ($extends) {
            $info["extends"] = $extends;
        }
        if (!is_null($region) && in_array($region, self::REGION_LIST)) {
            $info["region"] = $region;
        }

        $info["modtime"] = date("Y-m-d H:i:s");

        return $this->update($this->getTableName(), $info, "giftid=?", $giftid);
    }
}
