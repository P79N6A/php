<?php
/**
 * TOP API: taobao.game.charge.zc.audit request
 * 
 * @author auto create
 * @since  1.0, 2015.08.06
 */
class GameChargeZcAuditRequest
{

    /**
     * 
     * 商家id值
     **/
    private $coopId;
    
    /**
     * 
     * 对账确定时间段
     **/
    private $flag;
    
    /**
     * 
     * 淘宝订单号
     **/
    private $tbOrderNos;
    
    /**
     * 
     * 版本
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

    public function setFlag($flag)
    {
        $this->flag = $flag;
        $this->apiParas["flag"] = $flag;
    }

    public function getFlag()
    {
        return $this->flag;
    }

    public function setTbOrderNos($tbOrderNos)
    {
        $this->tbOrderNos = $tbOrderNos;
        $this->apiParas["tb_order_nos"] = $tbOrderNos;
    }

    public function getTbOrderNos()
    {
        return $this->tbOrderNos;
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
        return "taobao.game.charge.zc.audit";
    }
    
    public function getApiParas()
    {
        return $this->apiParas;
    }
    
    public function check()
    {
        
        RequestCheckUtil::checkNotNull($this->coopId, "coopId");
        RequestCheckUtil::checkNotNull($this->tbOrderNos, "tbOrderNos");
    }
    
    public function putOtherTextParam($key, $value)
    {
        $this->apiParas[$key] = $value;
        $this->$key = $value;
    }
}
