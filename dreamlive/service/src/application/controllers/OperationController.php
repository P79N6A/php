<?php

class OperationController extends  BaseController
{
    
    /**
     * 添加运营uid
     */
    public function addAction()
    {
        $uids = trim($this->getParam("uids"));
        
        Interceptor::ensureNotEmpty($uids, ERROR_PARAM_IS_EMPTY, "uids");
        
        $op = new Operation();
        $op->addRedis($uids);
        
        $this->render();
    }
    
    
    /**
     * 删除运营uid
     */
    public function deleteAction()
    {
        $del_uid= intval($this->getParam("uid", 0));
        Interceptor::ensureNotFalse($del_uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        
        $op = new Operation();
        $op->delRedis($del_uid);
        
        $this->render();
    }
    
    /**
     * 后台人员添加禁言
     */
    public function addSilenceAction()
    {
        $liveid  = $this->getParam("liveid")  ? intval($this->getParam('liveid'))            : 0;
        $userid  = $this->getParam("userid")  ? intval($this->getParam('userid'))            : 0;
        
        if ($liveid == 1111) {
            $cache = new Cache("REDIS_CONF_CACHE");
            $key = "room_slience_{$liveid}";
            $cache->sAdd($key, $userid);
            $cache->expire($key, 86400000);//1000天
            $dao_patroller_log = new DAOPatrollerLog();
            
            $dao_patroller_log->addPatrollerLog('8888', $userid, 'N', 'SILENCE', $liveid);
            $result = true;
        } else {
            $chat = new Chat();
            $result = $chat->addSilence($liveid, $userid);
        }
        
        $this->render($result);
    }
    
    /**
     * 后台人员解除禁言
     */
    public function delSilenceAction()
    {
        $liveid  = $this->getParam("liveid")  ? intval($this->getParam('liveid'))            : 0;
        $userid  = $this->getParam("userid")  ? intval($this->getParam('userid'))            : 0;
        
        
        if ($liveid == 1111) {
            $cache = new Cache("REDIS_CONF_CACHE");
            $key = "room_slience_{$liveid}";
            $cache->sRemove($key, $userid);
            $cache->expire($key, 86400);
            $dao_patroller_log = new DAOPatrollerLog();
            
            $dao_patroller_log->addPatrollerLog('8888', $userid, 'N', 'UNSILENCE', $liveid);
        } else {
            $chat = new Chat();
            $chat->delSilence($liveid, $userid);
        }
        
        $this->render();
    }
    
    
}