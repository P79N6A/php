<?php
class Interceptor
{
    public static function ensureNull($result, $errno, $args = array())
    {
        if (! is_null($result)) {
            throw new BizException($errno, $args);
        }
        
        return $result;
    }

    public static function ensureNotNull($result, $errno, $args = array())
    {
        if (is_null($result)) {
            throw new BizException($errno, $args);
        }
        
        return $result;
    }

    public static function ensureNotEmpty($result, $errno, $args = array())
    {
        if (empty($result)) {
            throw new BizException($errno, $args);
        }
        
        return $result;
    }

    public static function ensureEmpty($result, $errno, $args = array())
    {
        if (! empty($result)) {
            throw new BizException($errno, $args);
        }
        
        return $result;
    }

    public static function ensureNotFalse($result, $errno, $args = array())
    {
        if ($result === false) {
            throw new BizException($errno, $args);
        }
        
        return $result;
    }

    public static function ensureFalse($result, $errno, $args = array())
    {
        if ($result !== false) {
            throw new BizException($errno, $args);
        }
        
        return $result;
    }

    public static function ensureJson($string, $errno, $args = array())
    {
        json_decode($string, true);
        if (!json_last_error() == JSON_ERROR_NONE) {
            throw new BizException($errno, $args);
        }
        return $string;
    }

}

class BizException extends Exception
{
    public function __construct($errno, $args = array())
    {
        $args  = is_array($args) ? $args : array($args);
        $error = empty($args) ? Util::getError($errno) : vsprintf(Util::getError($errno), $args);

        throw new Exception($error, $errno);
    }
}
?>