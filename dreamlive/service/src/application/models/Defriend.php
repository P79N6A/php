<?php
class Defriend
{
    const DEFRIEND_USER_KEY = 'defriend_user_';

    public static function addDefriend($uid, $expire)
    {
        /* {{{封禁 */

        $dao_defriend = new DAODefriend();
        $dao_defriend->addDefriend($uid, time() + $expire);

        $cache = Cache::getInstance("REDIS_CONF_FORBIDDEN");
        return $cache->setex(self::DEFRIEND_USER_KEY . $uid, $expire, $uid);
    }/* }}} */
    public static function unDefriend($uid)
    {
        /* {{{ */

        $dao_defriend = new DAODefriend();
        $dao_defriend->unDefriend($uid);

        $cache = Cache::getInstance("REDIS_CONF_FORBIDDEN");
        return $cache->delete(self::DEFRIEND_USER_KEY . $uid);
    }/* }}} */
    public static function isDefriend($userid)
    {
        /* {{{ */
        $result = false;

        $cache = Cache::getInstance("REDIS_CONF_FORBIDDEN");

        try {
            $key = self::DEFRIEND_USER_KEY . $userid;
            $result = $cache->ttl($key) > 0;
        } catch (Exception $e) {
            $dao_defriend = new DAODefriend();
            $defriend_info = $dao_defriend->getDefriendByUid($userid);
            $result = $defriend_info && time() < $defriend_info['expire'];
        }

        return $result;
    }/* }}} */

    public static function isDefriendUsers($uids)
    {
        /* {{{ */
        if (! $uids) {
            return array();
        }

        $defriend_info = array();
        $keys = array();

        $uids = is_string($uids) ? array($uids) : $uids;

        foreach ($uids as $uid) {
            $keys[] = self::DEFRIEND_USER_KEY . $uid;
        }

        try {
            $cache = Cache::getInstance("REDIS_CONF_FORBIDDEN");

            $results = $cache->mget($keys);

            foreach ($results as $row) {
                if ($row) {
                    $defriend_info[] = $row;
                }
            }
        } catch (Exception $e) {
            $dao_defriend = new DAODefriend();
            $defriend_infos = $dao_defriend->getDefriendByUids($uids);

            foreach ($defriend_infos as $info) {
                if (time() < $info['expire']) {
                    $defriend_info[] = $info['uid'];
                }
            }
        }

        return $forbidden_info;
    }/* }}} */
}
?>
