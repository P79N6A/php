<?php
/**
 * ALIPAY API: alipay.commerce.cityfacilitator.voucher.confirm request
 *
 * @author auto create
 * @since  1.0, 2016-07-07 13:14:52
 */
class AlipayCommerceCityfacilitatorVoucherConfirmRequest
{

    /**
     * 
     * 钱包中地铁票购票，获得核销码，线下地铁自助购票机上凭核销码取票,购票确认
     **/
    private $bizContent;

    private $apiParas = array();
    private $terminalType;
    private $terminalInfo;
    private $prodCode;
    private $apiVersion="1.0";
    private $notifyUrl;
    private $returnUrl;
    private $needEncrypt=false;

    
    public function setBizContent($bizContent)
    {
        $this->bizContent = $bizContent;
        $this->apiParas["biz_content"] = $bizContent;
    }

    public function getBizContent()
    {
        return $this->bizContent;
    }

    public function getApiMethodName()
    {
        return "alipay.commerce.cityfacilitator.voucher.confirm";
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
