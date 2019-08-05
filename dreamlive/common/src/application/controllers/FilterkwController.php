<?php
class FilterkwController extends BaseController
{
    /**
     * 新增关键字
     */
    public function setKeywordAction()
    {
        $keyword = $this->getParam('keyword') ? trim($this->getParam('keyword')) : '';
        $key = $this->_getKey();
        $cache = Cache::getInstance('REDIS_CONF_CACHE');

        //新增关键字
        if (($ret = $cache->sadd($key, $keyword)) !== false) {
            $json_string = array('error' => 0, 'msg'=>'操作成功');
        } else {
            $json_string = array('error' => 1, 'msg'=>'操作失败');
        }
        echo json_encode($json_string);

    }

    /**
     * 删除关键字
     */
    public function delKeywordAction()
    {
        $keyword = $this->getParam('keyword') ? trim($this->getParam('keyword')) : '';
        $key = $this->_getKey();
        
        $cache = Cache::getInstance('REDIS_CONF_CACHE');
        $ret = $cache->srem($key, $keyword);
        if ($ret) {
            $json_string = array('error' => 0, 'msg'=>'操作成功');
        } else {
            $json_string = array('error' => 1, 'msg'=>'操作失败');
        }

        echo json_encode($json_string);
    }

    /**
     * 获取关键字
     */
    public function getKeywordAction()
    {
        $cache = Cache::getInstance('REDIS_CONF_CACHE');
        $key = $this->_getKey();
        $key_list = $cache->get($key);
        echo json_encode($key_list);
    }

    private function _getKey()
    {
        return 'filter_keys_list';
    }
    
    /**
     * 新增封禁关键字
     */
    public function setForbiddenKeywordAction()
    {
        $keyword = $this->getParam('keyword') ? trim($this->getParam('keyword')) : '';
        $key = $this->_getForbiddenKey();
        $cache = Cache::getInstance('REDIS_CONF_CACHE');
        
        //新增关键字
        if (($ret = $cache->sadd($key, $keyword)) !== false) {
            $json_string = array('error' => 0, 'msg'=>'操作成功');
        } else {
            $json_string = array('error' => 1, 'msg'=>'操作失败');
        }
        echo json_encode($json_string);
        
    }
    
    /**
     * 删除封禁关键字
     */
    public function delForbiddenKeywordAction()
    {
        $keyword = $this->getParam('keyword') ? trim($this->getParam('keyword')) : '';
        $key = $this->_getForbiddenKey();
        
        $cache = Cache::getInstance('REDIS_CONF_CACHE');
        $ret = $cache->srem($key, $keyword);
        if ($ret) {
            $json_string = array('error' => 0, 'msg'=>'操作成功');
        } else {
            $json_string = array('error' => 1, 'msg'=>'操作失败');
        }
        
        echo json_encode($json_string);
    }
    
    private function _getForbiddenKey()
    {
        return 'filter_keys_forbidden_list';
    }
}
