<?php
class ReadRedisController extends BaseController
{
    // 日志收集
    public function getAction()
    {
        $uid           = trim(strip_tags($this->getParam("uid")));
        $brand         = trim(strip_tags($this->getParam('brand')));
        $logurl        = trim(strip_tags($this->getParam('logurl')));
        $version    = trim(strip_tags($this->getParam('version')));
        $userid     = intval(Context::get('userid'));
    
        $dao_live = new DAOLive();
        
        $alllive = $dao_live->getUserLives($uid);
        
        $array = array();
        foreach ($alllive as $liveinfo ) {
            $watches = Counter::get(Counter::COUNTER_TYPE_LIVE_WATCHES, $liveinfo['liveid']);
            if (!empty($watches)) {
                $liveinfo['watches'] = $watches;
                
                $array[] = $liveinfo;
            }
        }
    
        $this->render($array);
    }
}

?>