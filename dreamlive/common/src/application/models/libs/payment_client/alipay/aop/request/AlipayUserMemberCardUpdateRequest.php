<?php
/**
 * ALIPAY API: alipay.user.member.card.update request
 *
 * @author auto create
 * @since  1.0, 2015-09-23 14:14:10
 */
class AlipayUserMemberCardUpdateRequest
{

    /**
     * 
     * 商户会员卡余额
     **/
    private $balance;
    
    /**
     * 
     * 会员卡卡号
     **/
    private $bizCardNo;
    
    /**
     * 
     * 发卡商户信息，json格式。
   
      注意：
     **/
    private $cardMerchantInfo;
    
    /**
     * 
     * 扩展参数，json格式。
     **/
    private $extInfo;
    
    /**
     * 
     * 商户会员卡号。 
     **/
    private $externalCardNo;
    
    /**
     * 
     * ALIPAY：支付宝
     **/
    private $issuerType;
    
    /**
     * 
     * 商户会员卡会员等级
     **/
    private $level;
    
    /**
     * 
     * 时间戳参数-orrur_time（精确至毫秒），标识业务发生的时间
     **/
    private $orrurTime;
    
    /**
     * 
     * 商户会员卡积分
     **/
    private $point;
    
    /**
     * 
     * 请求来源。
     **/
    private $requestFrom;

    private $apiParas = array();
    private $terminalType;
    private $terminalInfo;
    private $prodCode;
    private $apiVersion="1.0";
    private $notifyUrl;
    private $returnUrl;
    private $needEncrypt=false;

    
    public function setBalance($balance)
    {
        $this->balance = $balance;
        $this->apiParas["balance"] = $balance;
    }

    public function getBalance()
    {
        return $this->balance;
    }

    public function setBizCardNo($bizCardNo)
    {
        $this->bizCardNo = $bizCardNo;
        $this->apiParas["biz_card_no"] = $bizCardNo;
    }

    public function getBizCardNo()
    {
        return $this->bizCardNo;
    }

    public function setCardMerchantInfo($cardMerchantInfo)
    {
        $this->cardMerchantInfo = $cardMerchantInfo;
        $this->apiParas["card_merchant_info"] = $cardMerchantInfo;
    }

    public function getCardMerchantInfo()
    {
        return $this->cardMerchantInfo;
    }

    public function setExtInfo($extInfo)
    {
        $this->extInfo = $extInfo;
        $this->apiParas["ext_info"] = $extInfo;
    }

    public function getExtInfo()
    {
        return $this->extInfo;
    }

    public function setExternalCardNo($externalCardNo)
    {
        $this->externalCardNo = $externalCardNo;
        $this->apiParas["external_card_no"] = $externalCardNo;
    }

    public function getExternalCardNo()
    {
        return $this->externalCardNo;
    }

    public function setIssuerType($issuerType)
    {
        $this->issuerType = $issuerType;
        $this->apiParas["issuer_type"] = $issuerType;
    }

    public function getIssuerType()
    {
        return $this->issuerType;
    }

    public function setLevel($level)
    {
        $this->level = $level;
        $this->apiParas["level"] = $level;
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function setOrrurTime($orrurTime)
    {
        $this->orrurTime = $orrurTime;
        $this->apiParas["orrur_time"] = $orrurTime;
    }

    public function getOrrurTime()
    {
        return $this->orrurTime;
    }

    public function setPoint($point)
    {
        $this->point = $point;
        $this->apiParas["point"] = $point;
    }

    public function getPoint()
    {
        return $this->point;
    }

    public function setRequestFrom($requestFrom)
    {
        $this->requestFrom = $requestFrom;
        $this->apiParas["request_from"] = $requestFrom;
    }

    public function getRequestFrom()
    {
        return $this->requestFrom;
    }

    public function getApiMethodName()
    {
        return "alipay.user.member.card.update";
    }

    public function setNotifyUrl($notifyUrl)
    {
        $this->notifyUrl=$notifyUrl;
    }

    public function getNotifyUrl()
    {
        return $this->notifyUrl;
    }

    public function setReturnUrl($returnUrl)
    {
        $this->returnUrl=$returnUrl;
    }

    public function getReturnUrl()
    {
        return $this->returnUrl;
    }

    public function getApiParas()
    {
        return $this->apiParas;
    }

    public function getTerminalType()
    {
        return $this->terminalType;
    }

    public function setTerminalType($terminalType)
    {
        $this->terminalType = $terminalType;
    }

    public function getTerminalInfo()
    {
        return $this->terminalInfo;
    }

    public function setTerminalInfo($terminalInfo)
    {
        $this->terminalInfo = $terminalInfo;
    }

    public function getProdCode()
    {
        return $this->prodCode;
    }

    public function setProdCode($prodCode)
    {
        $this->prodCode = $prodCode;
    }

    public function setApiVersion($apiVersion)
    {
        $this->apiVersion=$apiVersion;
    }

    public function getApiVersion()
    {
        return $this->apiVersion;
    }

    public function setNeedEncrypt($needEncrypt)
    {

        $this->needEncrypt=$needEncrypt;

    }

    public function getNeedEncrypt()
    {
        return $this->needEncrypt;
    }

}
