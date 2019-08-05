<?php
class OAuth
{
 /* {{{ */
    const ERRNO_CLIENT_NOT_EXIST = 99998;

    const ERRNO_INVALID_TOKEN = 99999;

    public static function getClient($source, $token = "", $uid = "", $token_secret = "", $channel = "")
    { /* {{{ */
        static $clientObj = array();

        $key = $source . "-" . $uid . "-" . $channel . "-" . $token;

        if (! isset($clientObj[$key])) {
            $c = ucfirst($source) . 'Client';
            include_once dirname(__FILE__).DIRECTORY_SEPARATOR. "oauth_client". DIRECTORY_SEPARATOR. "$c.php";
            if (! class_exists($c)) {
                throw new OauthException("The Oauth Client you called is not exist!");
            }

            $clientObj[$key] = new $c($token, $uid, $token_secret, $channel);
        }

        return $clientObj[$key];
    }
 /* }}} */
    public static function getOauthUrl($source, $state = null)
    { /* {{{ */
        return self::getClient($source)->getOauthUrl($state);
    }
 /* }}} */
    public static function getAccessToken($source, $code)
    { /* {{{ */
        return self::getClient($source)->getAccessToken($code);
    }
 /* }}} */
    public static function getUserInfo($source, $token, $uid, $token_secret = "", $channel = "")
    { /* {{{ */
        return self::getClient($source, $token, $uid, $token_secret, $channel)->getUser();
    }
 /* }}} */
    public static function getFriends($source, $token, $uid, $start = 0)
    { /* {{{ */
        return self::getClient($source, $token, $uid)->getFriends($start);
    }
 /* }}} */
    public static function sendUserPassword($source, $token, $uid, $mobile, $pass)
    { /* {{{ */
        return self::getClient($source, $token, $uid)->sendPassword($mobile, $pass);
    } /* }}} */
    public static function getSessionKey($source, $token, $uid, $token_secret = "", $channel = "")
    { /* {{{ */
        return self::getClient($source, $token, $uid, $token_secret, $channel)->getSessionKey();
    }
 /* }}} */
}
 /* }}} */
class OAuthException extends Exception
{
}
