<?php

class ForbiddenKeyword
{
    
    /**
     * 检验是否包含封禁词汇，广告词汇
     *
     * @param  string $content
     * @return |boolean 返回true代表包含 ，false代表不包含
     */
    public static function forbiddenCheck($content)
    {
        $status = false;
        try{
            include_once 'antispam_client/policy/Policy.php';
            $content = Policy::format($content);
            
            $content = Policy::replace_emoji($content);
            
            $cache = Cache::getInstance('REDIS_CONF_CACHE');
            if (($ret = $cache->smembers('filter_keys_forbidden_list')) !== false) {
                foreach ($ret as $k) {
                    $keys         = explode('#', $k);
                    $keyword     = !empty($keys[0]) ? trim($keys[0]) : ''; //关键字
                    
                    
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
                        $status = true;
                        break;
                    }
                    
                }
            } else {
                Logger::log("keyword_forbidden_log", "cache_error1", array("content" => $content));
            }
        }catch(exception $e){
            
            Logger::log("keyword_forbidden_log", "cache_error2", array("content" => $content));
        }
        
        return $status;
    }
}