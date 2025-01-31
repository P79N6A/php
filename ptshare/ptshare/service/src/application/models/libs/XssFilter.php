<?php
/**
 * xss_filter
 *
 * @author mario.brandt
 * @copyright Copyright (c) 2013 - 2017
 * @access public
 */
class XssFilter {
    /**
     * @var bool $allow_http_value
     * @access private
     */
    private static $allow_http_value = true;
    /**
     * @var string $input
     * @access private
     */
    private static $input;
    /**
     * @var array $preg_patterns
     * @access private
     */
    private static $preg_patterns = array(
        // Fix &entity\n
        '!(&#0+[0-9]+)!' => '$1;',
        '/(&#*\w+)[\x00-\x20]+;/u' => '$1;>',
        '/(&#x*[0-9A-F]+);*/iu' => '$1;',
        //any attribute starting with "on" or xml name space
        '#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu' => '$1>',
        //javascript: and VB script: protocols
        '#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu' => '$1=$2nojavascript...',
        '#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu' => '$1=$2novbscript...',
        '#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u' => '$1=$2nomozbinding...',
        // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
        '#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i' => '$1>',
        '#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu' => '$1>',
        // namespace elements
        '#</*\w+:\w[^>]*+>#i' => '',
        //unwanted tags
        '#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i' => ''
    );
    /**
     * @var array
     */
    private static $normal_patterns = array(
//        '\'' => '&apos;',
//        '"' => '&quot;',
        '&' => '&amp;',
        '<' => '&lt;',
        '>' => '&gt;',
        //possible SQL injection remove from string with there is no '
        'SELECT * FROM' => '',
        'SELECT(' => '',
        'SLEEP(' => '',
        'AND (' => '',
        ' AND' => '',
        '(CASE' => ''
    );
    /**
     * xss_filter::filter_it()
     *
     * @access public
     * @param string $input
     * @return string
     */
    public static function filter($input){
        self::$input = html_entity_decode($input, ENT_NOQUOTES, 'UTF-8');
        self::normal_replace();
        self::do_grep();
        return self::$input;
    }
    /**
     * xss_filter::allow_http()
     *
     * @access public
     */
    protected static function allow_http(){
        self::$allow_http_value = true;
    }
    /**
     * xss_filter::disallow_http()
     *
     * @access public
     */
    protected static function disallow_http(){
        self::$allow_http_value = false;
    }
    /**
     * xss_filter::remove_get_parameters()
     *
     * @access public
     * @param $url string
     * @return string
     */
    protected static function remove_get_parameters($url){
        return preg_replace('/\?.*/', '', $url);
    }
    /**
     * xss_filter::normal_replace()
     *
     * @access private
     */
    private static function normal_replace(){
        self::$input = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), self::$input);
        if(self::$allow_http_value === false){
            self::$input = str_replace(array('&', '%', 'script', 'http', 'localhost'), array('', '', '', '', ''), self::$input);
        }
        else
        {
            self::$input = str_replace(array('&', '%', 'script', 'localhost', '../'), array('', '', '', '', ''), self::$input);
        }

        foreach(self::$normal_patterns as $pattern => $replacement){
            self::$input = str_replace($pattern,$replacement,self::$input);
        }
    }
    /**
     * xss_filter::do_grep()
     *
     * @access private
     */
    private static function do_grep(){
        foreach(self::$preg_patterns as $pattern => $replacement){
            self::$input = preg_replace($pattern,$replacement,self::$input);
        }
    }
}