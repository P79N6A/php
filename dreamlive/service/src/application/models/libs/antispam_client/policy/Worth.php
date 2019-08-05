<?php
class Worth extends Policy
{
    public function isDirty($content)
    {
        $options  = $this->getOptions();

        $ratio = isset($options["ratio"]) ? $options["ratio"] : 0.5;

        $content = Policy::replace_emoji($content);//过滤emoj
        if(strlen($content) < 12) {
            return false;
        }
        
        //return false;
        $words = array_unique($this->getWords($content));
        $worth = strlen(implode("", $words)) / strlen($content);
        $total = count($words);

        //无意义的字符 如:adlfjaksldfjlkadsf
        if($total == 1 && $worth == 1) {
            return true;
        }

        if($worth < $ratio) {
            return true;
        }

        return false;
    }
}
?>