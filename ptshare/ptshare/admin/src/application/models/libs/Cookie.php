<?php
class Cookie
{
    const ENCRYPTKEY = "#42!Wec(LK1g@fsh";
    const COOKIE_P3P = false;
    const COOKIE_DOMAIN = "admin." . COOKIE_NAME;
    const COOKIE_PATH   = "/";

    private static $_cookies = array();

    private static function getKey($name)
    {/*{{{*/
        return substr(md5($_SERVER["HTTP_USER_AGENT"] . self::ENCRYPTKEY), 8, 5) . '_' . $name;
    }/*}}}*/

    public static function set($name, $value = "", $expires = 0)
    {/*{{{*/
        self::$_cookies[$name] = $value;

        $expiresat = $expires ? (time() + $expires) : 0;

        $new_value = (is_array($value) || is_object($value)) ? '_ser_' . serialize($value) : $value;
        $new_value = Crypt::encrypt($new_value, self::ENCRYPTKEY);

        $domain = null;
        if(self::COOKIE_DOMAIN != '') {
            $domain = self::COOKIE_DOMAIN;
        }

        if(self::COOKIE_P3P) {
            header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
        }

        $k = self::getKey($name);
        $_COOKIE[$k] = $new_value;//当前页面生效，不用等刷新
        setcookie($k, $new_value, $expiresat, self::COOKIE_PATH, $domain, $_SERVER['SERVER_PORT'] == '443' ? 1 : 0);
    }/*}}}*/

    public static function get($name)
    {/*{{{*/
        if(isset(self::$_cookies[$name])) {
            return self::$_cookies[$name];
        }

        $k = self::getKey($name);

        if (isset($_COOKIE[$k])) {
            $val = Crypt::decrypt($_COOKIE[$k], self::ENCRYPTKEY);

            if(substr($val, 0, 5) == '_ser_') {
                $val = unserialize(substr($val, 5));
            }

            self::$_cookies[$name] = $val;
            return $val;
        }

        return null;
    }/*}}}*/

    public static function exists($name)
    {/*{{{*/
        return isset($_COOKIE[self::getKey($name)]);
    }/*}}}*/

    public static function delete($name)
    {/*{{{*/
        $domain = null;
        if(self::COOKIE_DOMAIN != '') {
            $domain = self::COOKIE_DOMAIN;
        }

        $k = self::getKey($name);
        unset($_COOKIE[$k]);//当前页面生效，不用等刷新
        unset(self::$_cookies[$name]);
        setcookie($k, '', time() - 3600, self::COOKIE_PATH, $domain, $_SERVER['SERVER_PORT'] == '443' ? 1 : 0);
    }/*}}}*/
}
