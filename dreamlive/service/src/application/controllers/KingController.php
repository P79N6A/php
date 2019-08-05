<?php
class KingController extends BaseController
{

    public function getListAction()
    {
        $uid = Context::get("userid");
        Interceptor::ensureNotEmpty($uid,    ERROR_PARAM_IS_EMPTY, "uid");


        //| 得到本月的列表
        $king = new King();
        $list = $king->getList($uid);

        //| 得到级别
        $level = $king->getTodayLevel($uid);
        if($level) {
            $arr = array($level);
        }
        if(is_array($list)) {
            foreach ($list  as $key => $value){
                $arr[] = $value['level'];
            }
        }
        if(is_array($arr)) {
            $ret = array_count_values($arr);
        }
        

        //级别计算, 本级+上级的所有合 $result
        $result = array();
        $s = 0;
        for($i=1; $i<9; $i++){
            $s += $ret[$i];
            $result[$i] = $s;
        }

        $config = new Config();
        $ranges = $config->getConfig('china', "ladder_of_money", "ios", '1.0.0.0');
        $ranges = json_decode($ranges['value'], true);

        $tags = array();
        foreach ($ranges as $key =>$value) {
            if($value['max_name']) {
                $tags[] = $value['max_name'];
            }
        }


        $this->render(array('data'=>$result, 'tags'=>$tags));
    }

    public function getListByUidAction()
    {
        $uid = intval($this->getParam("uid"));
        Interceptor::ensureNotEmpty($uid,    ERROR_PARAM_IS_EMPTY, "uid");


        //| 得到本月的列表
        $king = new King();
        $list = $king->getList($uid);

        //| 得到级别
        $level = $king->getTodayLevel($uid);
        if($level) {
            $arr = array($level);
        }
        if(is_array($list)) {
            foreach ($list  as $key => $value){
                $arr[] = $value['level'];
            }
        }
        if(is_array($arr)) {
            $ret = array_count_values($arr);
        }
        

        //级别计算, 本级+上级的所有合 $result
        $result = array();
        $s = 0;
        for($i=1; $i<9; $i++){
            $s += $ret[$i];
            $result[$i] = $s;
        }

        $config = new Config();
        $ranges = $config->getConfig('china', "ladder_of_money", "ios", '1.0.0.0');
        $ranges = json_decode($ranges['value'], true);

        $tags = array();
        foreach ($ranges as $key =>$value) {
            if($value['max_name']) {
                $tags[] = $value['max_name'];
            }
        }


        $this->render(array('data'=>$result, 'tags'=>$tags));
    }
    
    public function getListVisitorAction()
    {
        $config = new Config();
        $ranges = $config->getConfig('china', "ladder_of_money", "ios", '1.0.0.0');
        $ranges = json_decode($ranges['value'], true);

        $tags = array();
        foreach ($ranges as $key =>$value) {
            if($value['max_name']) {
                $tags[] = $value['max_name'];
            }
        }

        $this->render(array('data'=>array(), 'tags'=>$tags));
    }

    public function getMedalListAction()
    {
        $config = new Config();
        $ranges = $config->getConfig('china', "ladder_of_money", "ios", '1.0.0.0');
        $ranges = json_decode($ranges['value'], true);

        $tags = array();
        foreach ($ranges as $key =>$value) {
            if($value['max_name']) {
                $tags[] = $value['max_name'];
            }
        }

        
        $cache = Cache::getInstance('REDIS_CONF_CACHE');
        $key  = "king_medal";
        $data = json_decode($cache->get($key), true);
        asort($data);

        if(is_array($data)) {
            foreach($data as $key => $value){
                $user_info = User::getUserInfo($key);
                $nickname[$key] = $user_info['nickname'];
            }
        }

        $this->render(array('data'=>$data, 'tags'=>$tags, 'nickname'=>$nickname));
    }

   
}
