<?php
class ForbiddenMsg
{
    const FORBIDDEN_USER_KEY = 'forbidden_msg_user_';

    public static function addForbidden($relateid, $expire)
    {
        /* {{{封禁私信 */

        $cache = Cache::getInstance("REDIS_CONF_FORBIDDEN");

        return $cache->setex(self::FORBIDDEN_USER_KEY . $relateid, $expire, $relateid);
    }/* }}} */
    public static function unForbidden($relateid)
    {
        /* {{{ */
        $cache = Cache::getInstance("REDIS_CONF_FORBIDDEN");
        return $cache->delete(self::FORBIDDEN_USER_KEY . $relateid);
    }/* }}} */
    public static function isForbidden($userid)
    {
        /* {{{ */
        $result = false;

        $cache = Cache::getInstance("REDIS_CONF_FORBIDDEN");

        $key = self::FORBIDDEN_USER_KEY . $userid;
        $result = $cache->ttl($key) > 0;

        return $result;
    }/* }}} */
}
?>
