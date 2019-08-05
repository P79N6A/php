<?php
class Forbidden
{
    const FORBIDDEN_USER_KEY = 'forbidden_user_';

    public static function addForbidden($relateid, $expire, $reason = "", $liveid = 0)
    {
        /* {{{封禁 */

        $dao_forbidden = new DAOForbidden();
        $dao_forbidden->addForbidden($relateid, time() + $expire, $reason);

        if ($liveid) {
            $userinfo = User::getUserInfo($relateid);
            $sender = empty(Context::get('userid')) ? Messenger::MESSAGE_SYSTERM_BRODCAST_USER : Context::get('userid');
            Messenger::sendForbiden($liveid, $sender, $userinfo['nickname']."被封禁了", $relateid, $userinfo['nickname'], $userinfo['avatar'], $reason, $expire, $userinfo['level']);
            Messenger::sendUserFobidden($relateid, "您被封禁了", $reason, $expire);
        } else {
            //$userinfo = User::getUserInfo($relateid);
            Messenger::sendUserFobidden($relateid, "您被封禁了", $reason, $expire);
        }

        $cache = Cache::getInstance("REDIS_CONF_FORBIDDEN");
        return $cache->setex(self::FORBIDDEN_USER_KEY . $relateid, $expire, $relateid);
    }/* }}} */
    public static function unForbidden($relateid)
    {
        /* {{{ */

        $dao_forbidden = new DAOForbidden();
        $dao_forbidden->unForbidden($relateid);

        $cache = Cache::getInstance("REDIS_CONF_FORBIDDEN");
        return $cache->delete(self::FORBIDDEN_USER_KEY . $relateid);
    }/* }}} */
    public static function isForbidden($userid)
    {
        /* {{{ */
        $result = false;

        $cache = Cache::getInstance("REDIS_CONF_FORBIDDEN");

        try {
            $key = self::FORBIDDEN_USER_KEY . $userid;
            $result = $cache->ttl($key) > 0;
        } catch (Exception $e) {
            Logger::log(
                'forbidden_err', 'isforbidden_user::failure', array(
                'key' => $key,
                "errmsg" => $e->getMessage()
                )
            );

            $dao_forbidden = new DAOForbidden();
            $forbidden_info = $dao_forbidden->getForbidden($userid);
            $result = $forbidden_info && time() < $forbidden_info['expire'];
        }

        return $result;
    }/* }}} */
    public static function isForbiddenUsers($uids)
    {
        /* {{{ */
        if (! $uids) {
            return array();
        }

        $forbidden_info = array();
        $keys = array();

        $uids = is_string($uids) ? array($uids) : $uids;

        foreach ($uids as $uid) {
            $keys[] = self::FORBIDDEN_USER_KEY . $uid;
        }

        try {
            $cache = Cache::getInstance("REDIS_CONF_FORBIDDEN");

            $results = $cache->mget($keys);

            foreach ($results as $row) {
                if ($row) {
                    $forbidden_info[] = $row;
                }
            }
        } catch (Exception $e) {
            Logger::log(
                'forbidden_err', 'isForbiddenUsers:failure', array(
                'key' => implode(",", $keys),
                "errmsg" => $e->getMessage()
                )
            );

            $dao_forbidden = new DAOForbidden();
            $forbidden_infos = $dao_forbidden->getForbiddenLists($uids);

            foreach ($forbidden_infos as $info) {
                if (time() < $info['expire']) {
                    $forbidden_info[] = $info['relateid'];
                }
            }
        }

        return $forbidden_info;
    }/* }}} */
}
?>
