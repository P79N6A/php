<?php
/**
 * 融云 Server API PHP 客户端
 * create by kitName
 * create datetime : 2016-09-05 
 * 
 * v2.0.1
 */
require 'SendRequest.php';
require 'methods/RongCloudUser.php';
require 'methods/RongCloudMessage.php';
require 'methods/RongCloudChatRoom.php';
require 'methods/RongCloudPush.php';
require 'methods/RongCloudSMS.php';
    
class RongCloud
{
    /**
     * 参数初始化
     *
     * @param $appKey
     * @param $appSecret
     * @param string    $format
     */
    public function __construct($appKey, $appSecret, $format = 'json')
    {
        $this->SendRequest = new SendRequest($appKey, $appSecret, $format);
    }
    
    public function User()
    {
        $User = new RongCloudUser($this->SendRequest);
        return $User;
    }
    
    public function Message()
    {
        $Message = new RongCloudMessage($this->SendRequest);
        return $Message;
    }
       
    public function ChatRoom()
    {
        $Chatroom = new RongCloudChatRoom($this->SendRequest);
        return $Chatroom;
    }
    
    public function Push()
    {
        $Push = new RongCloudPush($this->SendRequest);
        return $Push;
    }
    
    public function SMS()
    {
        $SMS = new RongCloudSMS($this->SendRequest);
        return $SMS;
    }
    
    public function Group()
    {
        $Group = new RongCloudGroup($this->SendRequest);
        return $Group;
    }
}
