<?php
/**
 * TOP API: taobao.game.charge.zc.updatesupplierorder request
 * 
 * @author auto create
 * @since  1.0, 2015.08.06
 */
class GameChargeZcUpdatesupplierorderRequest
{

    /**
     * 
     * 合作商标识
     **/
    private $coopId;
    
    /**
     * 
     * 合作商的订单号
     **/
    private $coopOrderNo;
    
    /**
     * 
     * 商品信息快照
     **/
    private $coopOrderSnap;
    
    /**
     * 
     * 合作商的订单状态
     **/
    private $coopOrderStatus;
    
    /**
     * 
     * 充值成功时间
     **/
    private $coopOrderSuccessTime;
    
    /**
     * 
     * 失败代码
     **/
    private $failedCode;
    
    /**
     * 
     * 失败原因
     **/
    private $failedReason;
    
    /**
     * 
     * 淘宝订单号
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

    public function setCoopOrderNo($coopOrderNo)
    {
        $this->coopOrderNo = $coopOrderNo;
        $this->apiParas["coop_order_no"] = $coopOrderNo;
    }

    public function getCoopOrderNo()
    {
        return $this->coopOrderNo;
    }

    public function setCoopOrderSnap($coopOrderSnap)
    {
        $this->coopOrderSnap = $coopOrderSnap;
        $this->apiParas["coop_order_snap"] = $coopOrderSnap;
    }

    public function getCoopOrderSnap()
    {
        return $this->coopOrderSnap;
    }

    public function setCoopOrderStatus($coopOrderStatus)
    {
        $this->coopOrderStatus = $coopOrderStatus;
        $this->apiParas["coop_order_status"] = $coopOrderStatus;
    }

    public function getCoopOrderStatus()
    {
        return $this->coopOrderStatus;
    }

    public function setCoopOrderSuccessTime($coopOrderSuccessTime)
    {
        $this->coopOrderSuccessTime = $coopOrderSuccessTime;
        $this->apiParas["coop_order_success_time"] = $coopOrderSuccessTime;
    }

    public function getCoopOrderSuccessTime()
    {
        return $this->coopOrderSuccessTime;
    }

    public function setFailedCode($failedCode)
    {
        $this->failedCode = $failedCode;
        $this->apiParas["failed_code"] = $failedCode;
    }

    public function getFailedCode()
    {
        return $this->failedCode;
    }

    public function setFailedReason($failedReason)
    {
        $this->failedReason = $failedReason;
        $this->apiParas["failed_reason"] = $failedReason;
    }

    public function getFailedReason()
    {
        return $this->failedReason;
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
        return "taobao.game.charge.zc.updatesupplierorder";
    }
    
    public function getApiParas()
    {
        return $this->apiParas;
    }
    
    public function check()
    {
        
        RequestCheckUtil::checkNotNull($this->coopId, "coopId");
        RequestCheckUtil::checkNotNull($this->coopOrderNo, "coopOrderNo");
        RequestCheckUtil::checkNotNull($this->coopOrderStatus, "coopOrderStatus");
        RequestCheckUtil::checkNotNull($this->tbOrderNo, "tbOrderNo");
        RequestCheckUtil::checkNotNull($this->version, "version");
    }
    
    public function putOtherTextParam($key, $value)
    {
        $this->apiParas[$key] = $value;
        $this->$key = $value;
    }
}
