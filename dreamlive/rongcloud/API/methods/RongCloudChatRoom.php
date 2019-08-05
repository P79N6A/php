<?php
class RongCloudChatRoom
{

    private $SendRequest;
    
    public function __construct($SendRequest)
    {
               $this->SendRequest = $SendRequest;
    }

    
    /**
     * 创建聊天室方法 
     * 
     * @param chatRoomInfo:id:要创建的聊天室的id；name:要创建的聊天室的name。（必传）
     *
     * @return $json
     **/
    public function create($chatRoomInfo)
    {
        if (empty($chatRoomInfo)) {
            throw new Exception('Paramer "chatRoomInfo" is required');
        }


        $params = array();

        foreach ($chatRoomInfo as $key => $value) {
            $params['chatroom[' . $key . ']'] = $value;
        }

        $ret = $this->SendRequest->curl('/chatroom/create.json', $params, 'urlencoded', 'im', 'POST');
        if(empty($ret)) {
            throw new Exception('bad request');
        }
        return $ret;
    }
    
       /**
        * 向应用中的所有聊天室发送一条消息，单条消息最大 128k
        *
        * @param fromUserId：发送人用户 Id。（必传）
        * @param objectName：消息类型，`参考融云消息类型表.消息标志`；可自定义消息类型，长度不超过 32 个字符，您在自定义消息时需要注意，不要以 "RC:" 开头，以避免与融云系统内置消息的 ObjectName 重名。（必传）
        * @param content：发送消息内容，`参考融云消息类型表.示例说明`；如果 objectName 为自定义消息类型，该参数可自定义格式。（必传）
        *
        * @return $json
        **/
    public function broadcast($fromUserId, $objectName, $content)
    {
        if (empty($fromUserId)) {
            throw new Exception('Paramer "fromUserId" is required');
        }
                
        if (empty($objectName)) {
            throw new Exception('Paramer "$objectName" is required');
        }
            
        if (empty($content)) {
            throw new Exception('Paramer "$content" is required');
        }
       
        $params = array();
       
        $params = array (
        'fromUserId' => $fromUserId,
        'objectName' => $objectName,
        'content' => $content,
                   
        );
       
        $ret = $this->SendRequest->curl('/message/chatroom/broadcast.json', $params, 'urlencoded', 'im', 'POST');
        if(empty($ret)) {
            throw new Exception('bad request');
        }
        return $ret;
    }
       
    /**
     * 加入聊天室方法 
     * 
     * @param userId:要加入聊天室的用户 Id，可提交多个，最多不超过 50 个。（必传）
     * @param chatroomId:要加入的聊天室 Id。（必传）
     *
     * @return $json
     **/
    public function join($userId, $chatroomId)
    {
        if (empty($userId)) {
            throw new Exception('Paramer "userId" is required');
        }
                
        if (empty($chatroomId)) {
            throw new Exception('Paramer "chatroomId" is required');
        }
                
    
         $params = array (
         'userId' => $userId,
         'chatroomId' => $chatroomId
         );
            
         $ret = $this->SendRequest->curl('/chatroom/join.json', $params, 'urlencoded', 'im', 'POST');
         if(empty($ret)) {
             throw new Exception('bad request');
         }
         return $ret;
    }
    
        /**
         * 查询聊天室信息方法 
         * 
         * @param chatroomId:要查询的聊天室id（必传）
         *
         * @return $json
         **/
    public function query($chatroomId)
    {
        if (empty($chatroomId)) {
            throw new Exception('Paramer "chatroomId" is required');
        }
                
    
         $params = array (
         'chatroomId' => $chatroomId
         );
            
         $ret = $this->SendRequest->curl('/chatroom/query.json', $params, 'urlencoded', 'im', 'POST');
         if(empty($ret)) {
             throw new Exception('bad request');
         }
         return $ret;
    }
    
       /**
        * 查询聊天室内用户方法 
        * 
        * @param chatroomId:要查询的聊天室 ID。（必传）
        * @param count:要获取的聊天室成员数，上限为 500 ，超过 500 时最多返回 500 个成员。（必传）
        * @param order:加入聊天室的先后顺序， 1 为加入时间正序， 2 为加入时间倒序。（必传）
        *
        * @return $json
        **/
    public function queryUser($chatroomId, $count, $order)
    {
        if (empty($chatroomId)) {
            throw new Exception('Paramer "chatroomId" is required');
        }
                
        if (empty($count)) {
            throw new Exception('Paramer "count" is required');
        }
                
        if (empty($order)) {
            throw new Exception('Paramer "order" is required');
        }
                
    
         $params = array (
         'chatroomId' => $chatroomId,
         'count' => $count,
         'order' => $order
         );
            
         $ret = $this->SendRequest->curl('/chatroom/user/query.json', $params, 'urlencoded', 'im', 'POST');
         if(empty($ret)) {
             throw new Exception('bad request');
         }
         return $ret;
    }
    
        /**
         * 聊天室消息停止分发方法（可实现控制对聊天室中消息是否进行分发，停止分发后聊天室中用户发送的消息，融云服务端不会再将消息发送给聊天室中其他用户。） 
         * 
         * @param chatroomId:聊天室 Id。（必传）
         *
         * @return $json
         **/
    public function stopDistributionMessage($chatroomId)
    {
        if (empty($chatroomId)) {
            throw new Exception('Paramer "chatroomId" is required');
        }
                
    
         $params = array (
         'chatroomId' => $chatroomId
         );
            
         $ret = $this->SendRequest->curl('/chatroom/message/stopDistribution.json', $params, 'urlencoded', 'im', 'POST');
         if(empty($ret)) {
             throw new Exception('bad request');
         }
         return $ret;
            
    }
    
        /**
         * 聊天室消息恢复分发方法 
         * 
         * @param chatroomId:聊天室 Id。（必传）
         *
         * @return $json
         **/
    public function resumeDistributionMessage($chatroomId)
    {
        if (empty($chatroomId)) {
            throw new Exception('Paramer "chatroomId" is required');
        }
                
    
         $params = array (
         'chatroomId' => $chatroomId
         );
            
         $ret = $this->SendRequest->curl('/chatroom/message/resumeDistribution.json', $params, 'urlencoded', 'im', 'POST');
         if(empty($ret)) {
             throw new Exception('bad request');
         }
         return $ret;
    }
    
        /**
         * 添加禁言聊天室成员方法（在 App 中如果不想让某一用户在聊天室中发言时，可将此用户在聊天室中禁言，被禁言用户可以接收查看聊天室中用户聊天信息，但不能发送消息.） 
         * 
         * @param userId:用户 Id。（必传）
         * @param chatroomId:聊天室 Id。（必传）
         * @param minute:禁言时长，以分钟为单位，最大值为43200分钟。（必传）
         *
         * @return $json
         **/
    public function addGagUser($userId, $chatroomId, $minute)
    {
        if (empty($userId)) {
            throw new Exception('Paramer "userId" is required');
        }
                
        if (empty($chatroomId)) {
            throw new Exception('Paramer "chatroomId" is required');
        }
                
        if (empty($minute)) {
            throw new Exception('Paramer "minute" is required');
        }
                
    
         $params = array (
         'userId' => $userId,
         'chatroomId' => $chatroomId,
         'minute' => $minute
         );
            
         $ret = $this->SendRequest->curl('/chatroom/user/gag/add.json', $params, 'urlencoded', 'im', 'POST');
         if(empty($ret)) {
             throw new Exception('bad request');
         }
         return $ret;
    }
    
        /**
         * 查询被禁言聊天室成员方法 
         * 
         * @param chatroomId:聊天室 Id。（必传）
         *
         * @return $json
         **/
    public function ListGagUser($chatroomId)
    {
        if (empty($chatroomId)) {
            throw new Exception('Paramer "chatroomId" is required');
        }
                
    
         $params = array (
         'chatroomId' => $chatroomId
         );
            
         $ret = $this->SendRequest->curl('/chatroom/user/gag/list.json', $params, 'urlencoded', 'im', 'POST');
         if(empty($ret)) {
             throw new Exception('bad request');
         }
         return $ret;
    }
    
        /**
         * 移除禁言聊天室成员方法 
         * 
         * @param userId:用户 Id。（必传）
         * @param chatroomId:聊天室Id。（必传）
         *
         * @return $json
         **/
    public function rollbackGagUser($userId, $chatroomId)
    {
        if (empty($userId)) {
            throw new Exception('Paramer "userId" is required');
        }
                
        if (empty($chatroomId)) {
            throw new Exception('Paramer "chatroomId" is required');
        }
                
    
         $params = array (
         'userId' => $userId,
         'chatroomId' => $chatroomId
         );
            
         $ret = $this->SendRequest->curl('/chatroom/user/gag/rollback.json', $params, 'urlencoded', 'im', 'POST');
         if(empty($ret)) {
             throw new Exception('bad request');
         }
         return $ret;
    }
    
        /**
         * 添加封禁聊天室成员方法 
         * 
         * @param userId:用户 Id。（必传）
         * @param chatroomId:聊天室 Id。（必传）
         * @param minute:封禁时长，以分钟为单位，最大值为43200分钟。（必传）
         *
         * @return $json
         **/
    public function addBlockUser($userId, $chatroomId, $minute)
    {
        if (empty($userId)) {
            throw new Exception('Paramer "userId" is required');
        }
                
        if (empty($chatroomId)) {
            throw new Exception('Paramer "chatroomId" is required');
        }
                
        if (empty($minute)) {
            throw new Exception('Paramer "minute" is required');
        }
                
    
         $params = array (
         'userId' => $userId,
         'chatroomId' => $chatroomId,
         'minute' => $minute
         );
            
         $ret = $this->SendRequest->curl('/chatroom/user/block/add.json', $params, 'urlencoded', 'im', 'POST');
         if(empty($ret)) {
             throw new Exception('bad request');
         }
         return $ret;
    }
    
        /**
         * 查询被封禁聊天室成员方法 
         * 
         * @param chatroomId:聊天室 Id。（必传）
         *
         * @return $json
         **/
    public function getListBlockUser($chatroomId)
    {
        if (empty($chatroomId)) {
            throw new Exception('Paramer "chatroomId" is required');
        }
                
    
         $params = array (
         'chatroomId' => $chatroomId
         );
            
         $ret = $this->SendRequest->curl('/chatroom/user/block/list.json', $params, 'urlencoded', 'im', 'POST');
         if(empty($ret)) {
             throw new Exception('bad request');
         }
         return $ret;
    }
    
        /**
         * 移除封禁聊天室成员方法 
         * 
         * @param userId:用户 Id。（必传）
         * @param chatroomId:聊天室 Id。（必传）
         *
         * @return $json
         **/
    public function rollbackBlockUser($userId, $chatroomId)
    {
        if (empty($userId)) {
            throw new Exception('Paramer "userId" is required');
        }
                
        if (empty($chatroomId)) {
            throw new Exception('Paramer "chatroomId" is required');
        }
                
    
         $params = array (
         'userId' => $userId,
         'chatroomId' => $chatroomId
         );
            
         $ret = $this->SendRequest->curl('/chatroom/user/block/rollback.json', $params, 'urlencoded', 'im', 'POST');
         if(empty($ret)) {
             throw new Exception('bad request');
         }
         return $ret;
    }
    
    /**
     * 添加聊天室消息优先级方法 
     * 
     * @param objectName:低优先级的消息类型，每次最多提交 5 个，设置的消息类型最多不超过 20 个。（必传）
     *
     * @return $json
     **/
    public function addPriority($objectName)
    {
        if (empty($objectName)) {
            throw new Exception('Paramer "objectName" is required');
        }
                
    
        $params = array (
         'objectName' => $objectName
        );
            
        $ret = $this->SendRequest->curl('/chatroom/message/priority/add.json', $params, 'urlencoded', 'im', 'POST');
        if(empty($ret)) {
            throw new Exception('bad request');
        }
        return $ret;
    }
    
       /**
        * 移除聊天室消息优先级方法
        *
        * @param objectName:低优先级的消息类型，每次最多提交 5 个，设置的消息类型最多不超过 20 个。（必传）
        *
        * @return $json
        **/
    public function removePriority($objectName)
    {
        if (empty($objectName)) {
            throw new Exception('Paramer "objectName" is required');
        }
       
       
        $params = array (
        'objectName' => $objectName
        );
       
        $ret = $this->SendRequest->curl('/chatroom/message/priority/remove.json', $params, 'urlencoded', 'im', 'POST');
        if(empty($ret)) {
            throw new Exception('bad request');
        }
        return $ret;
    }
       
       /**
        * 添加聊天室消息优先级方法
        *
        * @param objectName:低优先级的消息类型，每次最多提交 5 个，设置的消息类型最多不超过 20 个。（必传）
        *
        * @return $json
        **/
    public function queryPriority()
    {
        $ret = $this->SendRequest->curl('/chatroom/message/priority/query.json', array(), 'urlencoded', 'im', 'POST');
        if(empty($ret)) {
            throw new Exception('bad request');
        }
        return $ret;
    }
       
       
       
    /**
     * 销毁聊天室方法 
     * 
     * @param chatroomId:要销毁的聊天室 Id。（必传）
     *
     * @return $json
     **/
    public function destroy($chatroomId)
    {
        if (empty($chatroomId)) {
            throw new Exception('Paramer "chatroomId" is required');
        }
                
    
        $params = array (
         'chatroomId' => $chatroomId
        );
            
        $ret = $this->SendRequest->curl('/chatroom/destroy.json', $params, 'urlencoded', 'im', 'POST');
        if(empty($ret)) {
            throw new Exception('bad request');
        }
        return $ret;
    }
    
    /**
     * 添加聊天室白名单成员方法 
     * 
     * @param chatroomId:聊天室中用户 Id，可提交多个，聊天室中白名单用户最多不超过 5 个。（必传）
     * @param userId:聊天室 Id。（必传）
     *
     * @return $json
     **/
    public function addWhiteListUser($chatroomId, $userId)
    {
        if (empty($chatroomId)) {
            throw new Exception('Paramer "chatroomId" is required');
        }
                
        if (empty($userId)) {
            throw new Exception('Paramer "userId" is required');
        }
                
    
        $params = array (
         'chatroomId' => $chatroomId,
         'userId' => $userId
        );
            
        $ret = $this->SendRequest->curl('/chatroom/user/whitelist/add.json', $params, 'urlencoded', 'im', 'POST');
        if(empty($ret)) {
            throw new Exception('bad request');
        }
        return $ret;
    }
    
    
    /**
     * 查询用户是否在聊天室方法
     *
     * @param  int $chatroomId
     * @param  int $userId
     * @throws Exception
     */
    public function userExist($chatroomId, $userId)
    {
        if (empty($chatroomId)) {
            throw new Exception('Paramer "chatroomId" is required');
        }
    
        if (empty($userId)) {
            throw new Exception('Paramer "userid" is required');
        }
   
        $params = array (
            'chatroomId' => $chatroomId,
            'userId' => $userId
        );
    
        $ret = $this->SendRequest->curl('/chatroom/user/exist.json', $params, 'urlencoded', 'im', 'POST');
        if(empty($ret)) {
            throw new Exception('bad request');
        }
        return $ret;
    }
    
    /**
     * 批量查询用户是否在聊天室方法
     *
     * @param  int $chatroomId
     * @param  int $userId     用户
     *                         Id，提供多个本参数可以实现查询多人是否在直播间，上限为
     *                         1000 人。（必传）
     * @throws Exception
     */
    public function usersExist($chatroomId, $userId)
    {
        if (empty($chatroomId)) {
            throw new Exception('Paramer "chatroomId" is required');
        }
            
        if (empty($userId)) {
            throw new Exception('Paramer "userid" is required');
        }
                
        $params = array (
                        'chatroomId' => $chatroomId,
                        'userId' => $userId
        );
                
        $ret = $this->SendRequest->curl('/chatroom/users/exist.json', $params, 'urlencoded', 'im', 'POST');
        if(empty($ret)) {
                    throw new Exception('bad request');
        }
                    return $ret;
    }
    
    /**
     * 给聊天室添加白名单
     *
     * @param  int $chatroomId
     * @param  int $userId
     * @throws Exception
     */
    public function addChatroomWhite($chatroomId, $userId)
    {
        if (empty($chatroomId)) {
            throw new Exception('Paramer "chatroomId" is required');
        }
            
        if (empty($userId)) {
            throw new Exception('Paramer "userid" is required');
        }
                
        $params = array (
                        'chatroomId' => $chatroomId,
                        'userId' => $userId
        );
                
        $ret = $this->SendRequest->curl('/chatroom/user/whitelist/add.json', $params, 'urlencoded', 'im', 'POST');
        if(empty($ret)) {
                    throw new Exception('bad request');
        }
                    return $ret;
    }
}
?>
