<?php
class Repeat extends Policy
{
    public function isDirty($content)
    {
        $options  = $this->getOptions();

        $min       = isset($options["min"])          ? $options["min"]    : 10;
        $max       = isset($options["max"])          ? $options["max"]    : PHP_INT_MAX;
        $interval  = isset($options["interval"])  ? $options["interval"]  : 60;
        $frequency = isset($options["frequency"]) ? $options["frequency"] : 5;

        if(strlen($content) <= $min || strlen($content) >= $max ) {
            return false;
        }

        $cache = $this->getCache();

        $key = $this->getType()."_".md5($content);

        $keywords = Policy::getKeywords();
        $length = mb_strlen($content);
        
        /**
         * 便捷语
         */
        $config = new Config();
        $results = $config->getConfig("china", "public_chat", "server", '1000000000000');
        $public_chat_array = json_decode($results['value'], true);
        $md5_array = [];
        foreach ($public_chat_array as $val) {
            $md5_array[] = md5($val['content']);
        }
        
        $md5content = md5($content);
        
        if (in_array($md5content, $md5_array)) {
            return false;
        }
        
        if ($length < 3) {
            return false;
        }
        
        $current = time();
        $times   = 0;
        if(false !== ($value = $cache->get($key))) {
            $value = json_decode($value, true);
            $current = $value["current"];
            $times   = $value["times"];

            if((time() - $current) < $interval && $times >= $frequency) {
                //                 foreach($keywords as $keyword) {
                //                     if(strpos($content, $keyword)  !== false && $length > 10) {
                //                         return true;
                //                     }
                //                 }
                
                return true;
                //return false;
            }
        }

        $cache->setex($key, $interval, json_encode(array("current"=>$current, "times"=>$times + 1)));

        return false;
    }
}
?>
