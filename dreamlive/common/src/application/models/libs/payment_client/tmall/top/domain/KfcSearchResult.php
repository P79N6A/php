<?php

/**
 * KFC 关键词过滤匹配结果
 *
 * @author auto create
 */
class KfcSearchResult
{
    
    /**
     * 
     * 过滤后的文本：
     **/
    public $content;
    
    /**
     * 
     * 匹配到的关键词的等级，值为null，或为A、B、C、D。
     **/
    public $level;
    
    /**
     * 
     * 是否匹配到关键词,匹配到则为true.
     **/
    public $matched;    
}
?>