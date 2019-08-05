<?php
/**
 * ALIPAY API: alipay.open.public.life.account.create request
 *
 * @author auto create
 * @since  1.0, 2016-12-08 12:04:26
 */
class AlipayOpenPublicLifeAccountCreateRequest
{

    /**
     * 
     * 背景图片，需上传图片原始二进制流，此图片显示在支付宝客户端生活号主页上方背景图位置，后缀是jpg或者jpeg，图片大小限制1mb
     **/
    private $background;
    
    /**
     * 
     * 生活号二级分类id，请按照以下分类进行填写，非以下分类账号请联系相应人员添加类别
     **/
    private $catagoryId;
    
    /**
     * 
     * 联系人邮箱，可以是调用者的联系人邮箱
     **/
    private $contactEmail;
    
    /**
     * 
     * 联系人电话，可以是调用者的联系人电话
     **/
    private $contactTel;
    
    /**
     * 
     * 生活号简介，此内容显示在支付宝客户端生活号主页简介区块
     **/
    private $content;
    
    /**
     * 
     * 客服电话，可以是电话号码，手机号码，400电话
     **/
    private $customerTel;
    
    /**
     * 
     * 生活号名称，该名称会显示在支付宝客户端生活号主页上方
     **/
    private $lifeName;
    
    /**
     * 
     * logo图片，需上传图片原始二进制流，此图片显示在支付宝客户端生活号主页上方位置，后缀是jpg或者jpeg，图片大小限制1mb
     **/
    private $logo;
    
    /**
     * 
     * 支付宝用户id，由支付宝同学提供用户id，为该生活号对应pid
     **/
    private $userId;

    private $apiParas = array();
    private $terminalType;
    private $terminalInfo;
    private $prodCode;
    private $apiVersion="1.0";
    private $notifyUrl;
    private $returnUrl;
    private $needEncrypt=false;

    
    public function setBackground($background)
    {
        $this->background = $background;
        $this->apiParas["background"] = $background;
    }

    public function getBackground()
    {
        return $this->background;
    }

    public function setCatagoryId($catagoryId)
    {
        $this->catagoryId = $catagoryId;
        $this->apiParas["catagory_id"] = $catagoryId;
    }

    public function getCatagoryId()
    {
        return $this->catagoryId;
    }

    public function setContactEmail($contactEmail)
    {
        $this->contactEmail = $contactEmail;
        $this->apiParas["contact_email"] = $contactEmail;
    }

    public function getContactEmail()
    {
        return $this->contactEmail;
    }

    public function setContactTel($contactTel)
    {
        $this->contactTel = $contactTel;
        $this->apiParas["contact_tel"] = $contactTel;
    }

    public function getContactTel()
    {
        return $this->contactTel;
    }

    public function setContent($content)
    {
        $this->content = $content;
        $this->apiParas["content"] = $content;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setCustomerTel($customerTel)
    {
        $this->customerTel = $customerTel;
        $this->apiParas["customer_tel"] = $customerTel;
    }

    public function getCustomerTel()
    {
        return $this->customerTel;
    }

    public function setLifeName($lifeName)
    {
        $this->lifeName = $lifeName;
        $this->apiParas["life_name"] = $lifeName;
    }

    public function getLifeName()
    {
        return $this->lifeName;
    }

    public function setLogo($logo)
    {
        $this->logo = $logo;
        $this->apiParas["logo"] = $logo;
    }

    public function getLogo()
    {
        return $this->logo;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
        $this->apiParas["user_id"] = $userId;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getApiMethodName()
    {
        return "alipay.open.public.life.account.create";
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
