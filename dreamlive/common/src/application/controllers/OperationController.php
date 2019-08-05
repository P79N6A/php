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
    
    
}