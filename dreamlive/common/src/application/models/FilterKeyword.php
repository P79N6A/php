<?php
class FilterKeyword
{
    const SHIELD     = 1;//屏蔽
    const REPLACE     = 2;//替换
    const NORMAL    = 0;//没替换也没有屏蔽
    
    /**
     * 检验是否包含屏蔽词
     */ 
    public static function check_shield($content_old, $plus, $is_record = true)
    {
        $status = true;
        //return $status;
        try{
            //解析Json内容
            $obj = @json_decode($content_old);
            $content = isset($obj->content) ? $obj->content : $content_old;
            
            if (isset($obj->content) && $obj->contentType != 0) {
                return $status;
            }
            
            $cache = Cache::getInstance('REDIS_CONF_CACHE');
            if (($ret = $cache->smembers('filter_keys_list')) !== false) {
                foreach ($ret as $k) {
                    $keys = explode('#', $k);
                    $keyword = !empty($keys[0]) ? trim($keys[0]) : ''; //关键字
                    $type = !empty($keys[1]) ? intval($keys[1]) : 0; //类型
                    
                    if ($type == self::REPLACE) {
                        continue;
                    }
                    //var_dump($keyword);
                    //var_dump($content);
                    
                    if (empty($keyword)) {
                        continue;
                    }
                    
                    $bool = @preg_match("/$keyword/iu", $content);
                    //var_dump($bool);exit;
                    if ($bool) {

                        //如果包含屏蔽词写入数据库
                        $liveid = isset($plus['liveid']) ? $plus['liveid']  : 0;
                        $sender = isset($plus['sender']) ? $plus['sender'] : 0;
                        $receiver = isset($plus['receiver']) ? $plus['receiver'] : 0;
                        $replace_keyword = $keyword;
                        if ($is_record) {
                            //$dao_filter = new DAOFilter();
                            if (is_object($obj)) { //私信
                                $dao = new DAOMessage($receiver);
                                $dao->addMessage($sender, $receiver, $type, $content_old, $replace_keyword);
                                //$dao_filter->addFilter($sender, $receiver, self::SHIELD, $content, $replace_keyword, 0, 'MESSAGE');
                            } else { //群聊
                                $dao = new DAOChat($liveid);
                                $dao->addMessage($liveid, $sender, $type, $content, $replace_keyword);
                                //$dao_filter->addFilter($sender, 0, self::SHIELD, $content, $replace_keyword, $liveid, 'CHAT');
                            }
                                
                        } else {
                            $dao_filter = new DAOFilter();
                                
                            $dao_filter->addFilter($sender, 0, self::SHIELD, $content, $replace_keyword, $liveid, 'PROP');
                        }
                        $status = false;
                        break;
                    }   
                     
                }
            }            
        }catch(exception $e){
            //print_r($e);
        }
        
        return $status;
    }
    
    /**
     * 内容替换
     */ 
    public static function content_replace($content_old, &$replace_keyword=array())
    {
        try{
            //解析Json内容
            $obj = @json_decode($content_old);
            $content = isset($obj->content) ? $obj->content : $content_old;
            
            if (isset($obj->content) && $obj->contentType != 0) {
                return $content_old;
            }
            
            $cache = Cache::getInstance('REDIS_CONF_CACHE');
            if (($ret = $cache->smembers('filter_keys_list')) !== false) {
                
                foreach($ret as $k) {
                    $keys = explode('#', $k);
                    $keyword = !empty($keys[0]) ? trim($keys[0]) : ''; //关键字
                    $type = !empty($keys[1]) ? intval($keys[1]) : 0; //类型
                    
                    if ($type == self::SHIELD) {
                        continue;
                    }
                    
                    //如果内容包含关键字替换
                    $pos = stripos($content, $keyword);
                    if($pos !== false  && self::REPLACE == $type) {
                        $replace_keyword[$pos] = $keyword;
                        $content = str_replace($keyword, '***', $content);
                    }  
                }
                
                if(is_array($replace_keyword)) {
                    ksort($replace_keyword);
                }
            } 
            
            //重组内容结构
            if (!empty($replace_keyword)) {
                if(is_object($obj)) {
                    $content_old = str_replace($obj->content, $content, $content_old);
                    
                } else {
                    $content_old = $content;
                }
            }
            
            
       
        }catch(exception $e){
            
        }
        
        return $content_old;
    }
    
    
    /**
     * 关键词包含就替换成***
     *
     * @param  string $content
     * @return unknown|mixed|unknown
     */
    static public function keyword_replace($content)
    {
        $replace_keyword = array();
        
        try{
            $cache = Cache::getInstance('REDIS_CONF_CACHE');
            if (($ret = $cache->smembers('filter_keys_list')) !== false) {
                
                foreach($ret as $k) {
                    $keys = explode('#', $k);
                    $keyword = !empty($keys[0]) ? trim($keys[0]) : ''; //关键字
                    $type = !empty($keys[1]) ? intval($keys[1]) : 0; //类型
                    
                    //如果内容包含关键字替换
                    $pos = strrpos($content, $keyword);
                    if($pos !== false) {
                        $replace_keyword[$pos] = $keyword;
                        $content = str_replace($keyword, '***', $content);
                    }
                }
                
                if(is_array($replace_keyword)) {
                    ksort($replace_keyword);
                }
            }
            
        }catch(exception $e){
            
        }
        
        return array('content' => $content, 'replace_word' => $replace_keyword);
    }
    
    /**
     * true代表有屏蔽词 false代表没有
     *
     * @param  string $content
     * @return boolean
     */
    static public function keywordFilterFromCache($content)
    {
        include_once 'antispam_client/policy/Policy.php';
        $content = Policy::format($content);
        
        $cache = Cache::getInstance('REDIS_CONF_CACHE');
        if (($ret = $cache->smembers('filter_keys_list')) !== false) {
            foreach ($ret as $k) {
                $keys = explode('#', $k);
                $keyword = !empty($keys[0]) ? trim($keys[0]) : ''; //关键字
                $type = !empty($keys[1]) ? intval($keys[1]) : 0; //类型
                
                if (empty($keyword)) {
                    continue;
                }
                
                $bool = @preg_match("/$keyword/iu", $content);
                
                if(!$bool) {
                    $numbers = Policy::getNumbers($content);
                    $bool    = @preg_match("/$keyword/iu", $numbers);
                }
                
                if(!$bool) {
                    $characters = Policy::getCharacters($content);
                    $bool    = @preg_match("/$keyword/iu", $characters);
                }
                if ($bool) {
                    return true;
                }
                
            }
        }
        
        return false;
    }
}
?>
