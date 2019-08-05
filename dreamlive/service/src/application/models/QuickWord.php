<?php
class QuickWord
{
    
    
    public static function getTotal($uid)
    {
        $dao_quick = new DAOQuickWord();
        
        return $dao_quick->getTotal($uid);
    }
    
    public function addWord($uid, $content)
    {
        $dao_quick = new DAOQuickWord();
        
        return $dao_quick->add($uid, $content);
    }
    
    public function getList($uid)
    {
        $cache     = new Cache("REDIS_CONF_CACHE");
        
        $key = "live_quick_word_" . $uid;
        
        $string = $cache->get($key);
        
        if (!empty($string)) {
            $content = json_decode($string, true);
            
            return $content;
        }
        
        $dao_quick = new DAOQuickWord();
        
        $data = $dao_quick->getList($uid);
        
        if (!empty($data)) {
            $re_data = [];
            
            foreach ($data as $item) {
                $item['is_can_edit'] = 1;
                if ($item['status'] != 'UNPASS') {
                    $re_data[] = $item;
                }
            }
            
            $cache->add($key, json_encode($re_data), 100);
        }
        
        return $re_data;
    }
}