<?php
class TopicController extends BaseController
{
    //添加话题
    public function addAction()
    {
        $name     = $this->getParam("name")     ? trim(strip_tags($this->getParam("name")))   : "";
        $region   = $this->getParam("region")   ? trim(strip_tags($this->getParam("region"))) : "";
        $relateid = $this->getParam("relateid") ? intval($this->getParam("relateid"))         : "";
        $type     = $this->getParam("type")     ? intval($this->getParam("type"))             : "";
        Interceptor::ensureNotEmpty($name, ERROR_PARAM_IS_EMPTY, "name");
        Interceptor::ensureNotEmpty($relateid, ERROR_PARAM_IS_EMPTY, "relateid");
        Interceptor::ensureNotEmpty($name, ERROR_PARAM_IS_EMPTY, "type");
        
        
        Topic::addTopic($name, $region, $relateid, $type);
        $this->render();
    }
    
    //删除话题
    public function delAction()
    {
        $name     = $this->getParam("name")     ? trim(strip_tags($this->getParam("name")))   : "";
        $region   = $this->getParam("region")   ? trim(strip_tags($this->getParam("region"))) : "";
        $relateid = $this->getParam("relateid") ? intval($this->getParam("relateid"))         : "";
        $type     = $this->getParam("type")     ? intval($this->getParam("type"))             : "";
        Interceptor::ensureNotEmpty($name, ERROR_PARAM_IS_EMPTY, "name");
        Interceptor::ensureNotEmpty($name, ERROR_PARAM_IS_EMPTY, "type");
        
        $result = Topic::delTopic($name, $region, $relateid, $type);
        $this->render($result);
    }
    
    //清除话题
    public function cleanAction()
    {
        $name     = $this->getParam("name")     ? trim(strip_tags($this->getParam("name")))   : "";
        $region   = $this->getParam("region")   ? trim(strip_tags($this->getParam("region"))) : "";
        Interceptor::ensureNotEmpty($name, ERROR_PARAM_IS_EMPTY, "name");
    
        $result = Topic::cleanTopic($name, $region);
        $this->render($result);
    }
    
}
