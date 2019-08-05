<?php
/**
 * TOP API: taobao.game.charge.zc.cancel request
 * 
 * @author auto create
 * @since  1.0, 2015.09.24
 */
class GameChargeZcCancelRequest
{

    /**
     * 
     * 商家编号
     **/
    private $coopId;
    
    /**
     * 
     * 淘宝的订单号
     **/
    private $tbOrderNo;
    
    /**
     * 
     * 接口版本
     **/
    private $version;
    
    private $apiParas = array();
    
    public function setCoopId($coopId)
    {
        $this->coopId = $coopId;
        $this->apiParas["coop_id"] = $coopId;
    }

    public function getCoopId()
    {
        return $this->coopId;
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
        return "taobao.game.charge.zc.cancel";
    }
    
    public function getApiParas()
    {
        return $this->apiParas;
    }
    
    public function check()
    {
        
        RequestCheckUtil::checkNotNull($this->coopId, "coopId");
        RequestCheckUtil::checkNotNull($this->tbOrderNo, "tbOrderNo");
        RequestCheckUtil::checkNotNull($this->version, "version");
    }
    
    public function putOtherTextParam($key, $value)
    {
        $this->apiParas[$key] = $value;
        $this->$key = $value;
    }
}
