<?php
class RongCloudClient
{
    private $_client = null;
    private $_appkey = "y745wfm8yobxv";
    private $_secret = "X5PbEVRXjThiD";

    public function __construct()
    {
        include_once 'rongcloud_client/API/rongcloud.php';
        $config = Context::getConfig("RONGCLOUD_CONF");
        $this->_client = new RongCloud($config['appkey'], $config['secret']);
    }

    public function getToken($userid, $username, $avatar)
    {
        try {
            $result = $this->_client->user()->getToken($userid, $username, $avatar);
            
            $result = json_decode($result, true);
            
            if($result["code"] == 200) {
                return $result["token"];
            }
        } catch (Exception $e) {
            throw new BizException($e->getCode(), $e->getMessage());
        }
        
        return false;
    }

    public function refresh($userid, $username, $avatar)
    {
        try{
            $result = $this->_client->user()->refresh($userid, $username, $avatar);
            $result = json_decode($result, true);
    
            return $result["code"] == 200 ? true : false;
        } catch (Exception $e) {
            throw new BizException($e->getCode(), $e->getMessage());
        }
    }
    
    public function sendPrivateMessage($receiver, $sender, $content, $pushContent = '', $type = 0)
    {
        //内置类型RC:TxtMsg
        //自定义类型TxtMsg
        try {
            if (in_array($type, array(200))) {
                $con = json_decode($content, true);
                $avatar = $con['content']['extra']['avatar'];
                $avatar = str_replace("http://", "https://", $avatar);
                
                if (strpos($avatar, 'static.dreamlive.com') !== false || strpos($avatar, 'static.dreamlive.tv') !== false || strpos($avatar, 'static.dreamlive.cn') !== false) {
                    $pathinfo = pathinfo($avatar);
                    
                    $new_avatar = $pathinfo['dirname'].'/'. $pathinfo['filename'] . '_324-324.' . $pathinfo['extension'];
                } else {
                    $new_avatar = $avatar;
                }
                Logger::log("private_msg_err", "newavatar", array('newavatar' => $new_avatar));
                $result = $this->_client->message()->publishPrivate($sender, $receiver, "TxtMsg", $content, $pushContent, $new_avatar, '', 0, 0, 0);
            } else {
                
                if (in_array($type, array(400))) {
                    $con = json_decode($content, true);
                    $pushmessage = json_decode($con['content']['text'], true);
                    Logger::log("msg", 'message ', array("receiver" => $receiver, "sender" => $sender, "content" => $content, "pushContent" => $pushContent,"c" => $con['content']));
                    
                    $content_type = $pushmessage['contentType'];
                    
                    
                    $result = $this->_client->message()->publishPrivate($sender, $receiver, "TxtMsg", $content,  ($content_type == 0) ? $pushmessage['content'] : '', '', '', 0, 0, 0);
                } else {
                    $result = $this->_client->message()->publishPrivate($sender, $receiver, "TxtMsg", $content, '', '', '', 0, 0, 0);
                }
                
            }
            Logger::log(
                "msg_err", "private msg err", [
                "method"=>__METHOD__,
                "result"=>$result,
                "receiver"=>$receiver,
                "sender"=>$sender,
                "content"=>$content,
                "type"=>$type,
                ]
            );
            $result = json_decode($result, true);

            
            return $result["code"] == 200 ? true : false;
        } catch (Exception $e) {
            Logger::log("private_msg_err", "private msg err", json_decode(json_encode($e), true));
            throw new BizException($e->getCode(), $e->getMessage());
        }
    }
    
    public function sendBigNotice($receiver, $sender, $content, $avatar = '', $body = '')
    {
        try {
            $avatar = str_replace("http://", "https://", $avatar);
            Logger::log("private_msg_err_bignotice", "newavatar", array('image' => $avatar, "text" => $content, "receiver" => $receiver));
            $result = $this->_client->message()->publishPrivate($sender, $receiver, "TxtMsg", $body, $content, $avatar, '', 0, 0, 0);
            
            $result = json_decode($result, true);
            
            return $result["code"] == 200 ? true : false;
        } catch (Exception $e) {
            Logger::log("private_msg_err_bignotice", "private msg err", json_decode(json_encode($e), true));
            throw new BizException($e->getCode(), $e->getMessage());
        }
    }
    
    //内置类型RC:TxtMsg
    //自定义类型TxtMsg
    public function sendChatRoomMessage($liveid, $sender, $content, $objectName = 'RC:TxtMsg')
    {
        try{
            $result = $this->_client->message()->publishChatroom($sender, array($liveid), $objectName, $content);

            Logger::log(
                "private_msg_err", "private msg err", [
                "method"=>__METHOD__,
                "result"=>$result,
                "receiver"=>$liveid,
                "sender"=>$sender,
                "content"=>$content,
                ]
            );
            $result = json_decode($result, true);

            
            return $result["code"] == 200 ? true : false;
        } catch (Exception $e) {
            Logger::log("msg_err", "sendChatRoomMessage msg err", json_decode(json_encode($e), true));
            throw new BizException($e->getCode(), $e->getMessage());
        }
    }

    public function addGagUser($liveid, $userid, $minute = 180)
    {
        try {
            $result = $this->_client->chatroom()->addGagUser($userid, $liveid, $minute);
            $result = json_decode($result, true);
            
            return $result["code"] == 200 ? true : false;
        } catch (Exception $e) {
            Logger::log("msg_err", "addGagUser msg err", json_decode(json_encode($e), true));
            throw new BizException($e->getCode(), $e->getMessage());
        }
        
    }

    public function delGagUser($liveid, $userid)
    {
        try {
            $result = $this->_client->chatroom()->rollbackGagUser($userid, $liveid);
            $result = json_decode($result, true);
            
            return $result["code"] == 200 ? true : false;
        } catch (Exception $e) {
            Logger::log("msg_err", "delGagUser msg err", json_decode(json_encode($e), true));
            throw new BizException($e->getCode(), $e->getMessage());
        }
        
    }

    public function addKickUser($liveid, $userid, $minute = 180)
    {
        try {
            $result = $this->_client->chatroom()->addBlockUser($userid, $liveid, $minute);
            $result = json_decode($result, true);
            
            return $result["code"] == 200 ? true : false;
        } catch (Exception $e) {
            Logger::log("msg_err", "addKickUser msg err", json_decode(json_encode($e), true));
            throw new BizException($e->getCode(), $e->getMessage());
        }
        
    }
    
    public function queryUsers($liveid, $count, $order)
    {
        try {
            
            $result = $this->_client->chatroom()->queryUser($liveid, $count, $order);
            $result = json_decode($result, true);
        
            return $result;
        } catch (Exception $e) {
            Logger::log("msg_err", "queryUsers msg err", json_decode(json_encode($e), true));
            throw new BizException($e->getCode(), $e->getMessage());
        }
    }
    
    public function sendBroadcast($fromUserId, $content, $pushContent = '', $pushData = '', $os = '')
    {
        try {
            
            $result = $this->_client->message()->broadcast($fromUserId,  'RC:TxtMsg', $content, $pushContent, $pushData, $os);
            $result = json_decode($result, true);
    
            return $result["code"] == 200 ? true : false;
        } catch (Exception $e) {
            Logger::log("msg_err", "sendBroadcast msg err", json_decode(json_encode($e), true));
            throw new BizException($e->getCode(), $e->getMessage());
        }
    }
    
    public function push($fromUserId, $content, $extra = array())
    {
        
        try {
            
        
            $result = $this->_client->message()->push($fromUserId,  'RC:TxtMsg', $content, $extra);
            $result = json_decode($result, true);
            
            return $result["code"] == 200 ? true : false;
        } catch (Exception $e) {
            Logger::log("msg_err", "push msg err", json_decode(json_encode($e), true));
            throw new BizException($e->getCode(), $e->getMessage());
        }
    }

    public function sendSystemPublish($fromUserId, $toUserId, $content, $pushContent = '', $pushData = '', $isPersisted = 1, $isCounted = 1)
    {
        try {
            $result = $this->_client->message()->PublishSystem($fromUserId, $toUserId,  'TxtMsg', $content, $pushContent, $pushData, $isPersisted, $isCounted);
            $result = json_decode($result, true);
    
            return $result["code"] == 200 ? true : false;
        }  catch (Exception $e) {
            Logger::log("msg_err", "sendSystemPublish msg err", json_decode(json_encode($e), true));
            throw new BizException($e->getCode(), $e->getMessage());
        }
    }
    
    public function sendChatroomBrodcast($fromUserId, $content) 
    {
        try {
            $result = $this->_client->chatroom()->broadcast($fromUserId, 'RC:TxtMsg', $content);
            $result = json_decode($result, true);
            
            return $result["code"] == 200 ? true : false;
        }  catch (Exception $e) {
            Logger::log("msg_err", "sendChatroomBrodcast msg err", json_decode(json_encode($e), true));
            throw new BizException($e->getCode(), $e->getMessage());
        }
    }
    
    public function addPriority($objectName)
    {
        try {
            $result = $this->_client->chatroom()->addPriority($objectName);
            $result = json_decode($result, true);
             
            return $result["code"] == 200 ? true : false;
        }  catch (Exception $e) {
            Logger::log("msg_err", "addPriority msg err", json_decode(json_encode($e), true));
            throw new BizException($e->getCode(), $e->getMessage());
        }
    }
    
    public function queryMessgaeHistory($datetime)
    {
        try {
            $result = $this->_client->Message()->getHistory($datetime);
            $result = json_decode($result, true);
            
            return $result["code"] == 200 ? $result : false;
        }  catch (Exception $e) {
            Logger::log("msg_err", "queryHistory msg err", json_decode(json_encode($e), true));
            throw new BizException($e->getCode(), $e->getMessage());
        }
    }
    
    public function removePriority($objectName)
    {
        try {
            $result = $this->_client->chatroom()->removePriority($objectName);
            $result = json_decode($result, true);
             
            return $result["code"] == 200 ? true : false;
        }  catch (Exception $e) {
            Logger::log("msg_err", "removePriority msg err", json_decode(json_encode($e), true));
            throw new BizException($e->getCode(), $e->getMessage());
        }
    }
    
    public function queryPriority()
    {
        try {
            $result = $this->_client->chatroom()->queryPriority();
            $result = json_decode($result, true);
            
            return $result;
        } catch (Exception $e) {
            Logger::log("msg_err", "queryPriority msg err", json_decode(json_encode($e), true));
            throw new BizException($e->getCode(), $e->getMessage());
        }
    }
    
    public function queryChatroom($chatroomId)
    {
        try {
            $result = $this->_client->chatroom()->query($chatroomId);
            $result = json_decode($result, true);
             
            return $result["code"] == 200 ? $result : false;
        }  catch (Exception $e) {
            Logger::log("msg_err", "queryChatroom msg err", json_decode(json_encode($e), true));
            throw new BizException($e->getCode(), $e->getMessage());
        }
    }
    
    public function queryChatroomUserExist($chatroomId, $userId)
    {
        try {
            $result = $this->_client->chatroom()->userExist($chatroomId, $userId);
            $result = json_decode($result, true);
             
            return $result["code"] == 200 ? $result : false;
        }  catch (Exception $e) {
            Logger::log("msg_err", "queryChatroom msg err", json_decode(json_encode($e), true));
            throw new BizException($e->getCode(), $e->getMessage());
        }
    }
    
    public function addChatroomWhiteList($chatroomId, $userId)
    {
        try {
            $result = $this->_client->chatroom()->addChatroomWhite($chatroomId, $userId);
            $result = json_decode($result, true);
            return $result["code"] == 200 ? $result : false;
        }  catch (Exception $e) {
            Logger::log("msg_err", "queryChatroom msg err", json_decode(json_encode($e), true));
            throw new BizException($e->getCode(), $e->getMessage());
        }
    }
    
    public function batchQueryChatroomUserExist($chatroomId, $userId)
    {
        try {
            $result = $this->_client->chatroom()->usersExist($chatroomId, $userId);
            $result = json_decode($result, true);
            
            return $result["code"] == 200 ? $result : false;
        }  catch (Exception $e) {
            Logger::log("msg_err", "queryUsersChatroom msg err", json_decode(json_encode($e), true));
            throw new BizException($e->getCode(), $e->getMessage());
        }
    }
}
?>
