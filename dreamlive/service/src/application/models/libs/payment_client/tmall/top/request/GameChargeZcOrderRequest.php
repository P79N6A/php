<?php
/**
 * TOP API: taobao.game.charge.zc.order request
 * 
 * @author auto create
 * @since  1.0, 2017.11.23
 */
class GameChargeZcOrderRequest
{

    /**
     * 
     * 充值卡商品编号
     **/
    private $cardId;
    
    /**
     * 
     * 充值卡数量
     **/
    private $cardNum;
    
    /**
     * 
     * 合作商编号
     **/
    private $coopId;
    
    /**
     * 
     * 被充值帐号或手机号码
     **/
    private $customer;
    
    /**
     * 
     * 充值游戏编号
     **/
    private $gameId;
    
    /**
     * 
     * 异同通知地址
     **/
    private $notifyUrl;
    
    /**
     * 
     * 游戏或手机区
     **/
    private $section1;
    
    /**
     * 
     * 二级分类，如游戏服
     **/
    private $section2;
    
    /**
     * 
     * 本次充值总金额，代表用户支付的金额
     **/
    private $sum;
    
    /**
     * 
     * 淘宝订单号
     **/
    private $tbOrderNo;
    
    /**
     * 
     * 商品信息快照
     **/
    private $tbOrderSnap;
    
    /**
     * 
     * 版本号
     **/
    private $version;
    
    private $apiParas = array();
    
    public function setCardId($cardId)
    {
        $this->cardId = $cardId;
        $this->apiParas["card_id"] = $cardId;
    }

    public function getCardId()
    {
        return $this->cardId;
    }

    public function setCardNum($cardNum)
    {
        $this->cardNum = $cardNum;
        $this->apiParas["card_num"] = $cardNum;
    }

    public function getCardNum()
    {
        return $this->cardNum;
    }

    public function setCoopId($coopId)
    {
        $this->coopId = $coopId;
        $this->apiParas["coop_id"] = $coopId;
    }

    public function getCoopId()
    {
        return $this->coopId;
    }

    public function setCustomer($customer)
    {
        $this->customer = $customer;
        $this->apiParas["customer"] = $customer;
    }

    public function getCustomer()
    {
        return $this->customer;
    }

    public function setGameId($gameId)
    {
        $this->gameId = $gameId;
        $this->apiParas["game_id"] = $gameId;
    }

    public function getGameId()
    {
        return $this->gameId;
    }

    public function setNotifyUrl($notifyUrl)
    {
        $this->notifyUrl = $notifyUrl;
        $this->apiParas["notify_url"] = $notifyUrl;
    }

    public function getNotifyUrl()
    {
        return $this->notifyUrl;
    }

    public function setSection1($section1)
    {
        $this->section1 = $section1;
        $this->apiParas["section1"] = $section1;
    }

    public function getSection1()
    {
        return $this->section1;
    }

    public function setSection2($section2)
    {
        $this->section2 = $section2;
        $this->apiParas["section2"] = $section2;
    }

    public function getSection2()
    {
        return $this->section2;
    }

    public function setSum($sum)
    {
        $this->sum = $sum;
        $this->apiParas["sum"] = $sum;
    }

    public function getSum()
    {
        return $this->sum;
    }

    public function setTbOrderNo($tbOrderNo)
    {
        $this->tbOrderNo = $tbOrderNo;
        $this->apiParas["tb_order_no"] = $tbOrderNo;
    }

    public function getTbOrderNo()
    {
        return $this->tbOrderNo;
    }

    public function setTbOrderSnap($tbOrderSnap)
    {
        $this->tbOrderSnap = $tbOrderSnap;
        $this->apiParas["tb_order_snap"] = $tbOrderSnap;
    }

    public function getTbOrderSnap()
    {
        return $this->tbOrderSnap;
    }

    public function setVersion($version)
    {
        $this->version = $version;
        $this->apiParas["version"] = $version;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function getApiMethodName()
    {
        return "taobao.game.charge.zc.order";
    }
    
    public function getApiParas()
    {
        return $this->apiParas;
    }
    
    public function check()
    {
        
        RequestCheckUtil::checkNotNull($this->cardId, "cardId");
        RequestCheckUtil::checkNotNull($this->cardNum, "cardNum");
        RequestCheckUtil::checkNotNull($this->coopId, "coopId");
        RequestCheckUtil::checkNotNull($this->customer, "customer");
        RequestCheckUtil::checkNotNull($this->notifyUrl, "notifyUrl");
        RequestCheckUtil::checkNotNull($this->sum, "sum");
        RequestCheckUtil::checkNotNull($this->tbOrderNo, "tbOrderNo");
        RequestCheckUtil::checkNotNull($this->tbOrderSnap, "tbOrderSnap");
        RequestCheckUtil::checkNotNull($this->version, "version");
    }
    
    public function putOtherTextParam($key, $value)
    {
        $this->apiParas[$key] = $value;
        $this->$key = $value;
    }
}
