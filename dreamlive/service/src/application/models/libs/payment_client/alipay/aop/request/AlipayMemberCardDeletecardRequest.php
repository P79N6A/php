<?php
/**
 * ALIPAY API: alipay.member.card.deletecard request
 *
 * @author auto create
 * @since  1.0, 2014-12-01 11:13:25
 */
class AlipayMemberCardDeletecardRequest
{

    /**
     * 
     * 商户端删卡业务流水号
     **/
    private $bizSerialNo;
    
    /**
     * 
     * 发卡商户信息，json格式。
     **/
    private $cardMerchantInfo;
    
    /**
     * 
     * 删卡扩展参数，json格式。
     **/
    private $extInfo;
    
    /**
     * 
     * 商户会员卡号
     **/
    private $externalCardNo;
    
    /**
     * 
     * CANCEL：销户
     **/
    private $reasonCode;
    
    /**
     * 
     * 请求来源
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

    
    public function setBizSerialNo($bizSerialNo)
    {
        $this->bizSerialNo = $bizSerialNo;
        $this->apiParas["biz_serial_no"] = $bizSerialNo;
    }

    public function getBizSerialNo()
    {
        return $this->bizSerialNo;
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

    public function setReasonCode($reasonCode)
    {
        $this->reasonCode = $reasonCode;
        $this->apiParas["reason_code"] = $reasonCode;
    }

    public function getReasonCode()
    {
        return $this->reasonCode;
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
        return "alipay.member.card.deletecard";
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
