<?php
class Reply
{
    const LOADING_FORWARD = 0;

    const LOADING_BACKWARD = 1;

    public function addReply($pid, $qid, $content, $type)
    {
        $uid = Context::get("userid");

        $floor = Counter::increase(Counter::COUNTER_TYPE_REPLY_FLOOR, $pid);

        $dao_reply = new DAOReply($pid);
        $rid = $dao_reply->addReply($pid, $qid, $floor, $uid, $content);

        Counter::increase(Counter::COUNTER_TYPE_REPLIES, $pid);

        $reply_info = Reply::getReplyInfo($pid, $rid);

        return $reply_info;
    }

    public static function getReplyInfo($pid, $rid)
    {
        $dao_reply = new DAOReply($pid);
        $reply_info = $dao_reply->getReplyInfo($rid);

        return empty($reply_info) ? array() : self::getFormatReply($reply_info);
    }

    public static function getUserInfo($uid)
    {
        static $users;

        if (!isset($users[$uid]) || empty($users[$uid])) {
            $user_info = User::getUserInfo(Context::get("userid"));

            $users[$uid] = array(
                "uid"         => $uid,
                "nickname"     => $user_info["nickname"],
                "avatar"     => $user_info["avatar"],
                "verified"     => $user_info["verified"],
                "verifiedinfo" => $user_info["verifiedinfo"]
            );
        }

        return $users[$uid];
    }

    public static function getFormatReply($reply_info)
    {
        $formated_reply_info = array(
            "rid"         => $reply_info["rid"],
            "content"     => $reply_info["content"],
            "floor"     => $reply_info["floor"],
            "addtime"     => $reply_info["addtime"]
        );

        if ($reply_info["qid"]) {
            $dao_reply = new DAOReply($reply_info["pid"]);
            $quote_info = $dao_reply->getReplyInfo($reply_info["qid"]);

            if (empty($quote_info)) {
                $formated_reply_info["quote"] = array(
                    "deleted" => true
                );
            } else {
                $formated_reply_info["quote"] = array(
                    "deleted"     => false,
                    "content"     => $quote_info["content"],
                    "floor"     => $quote_info["floor"],
                    "user"         => self::getUserInfo($quote_info["uid"])
                );
            }
        }

        $formated_reply_info["user"] = self::getUserInfo($reply_info["uid"]);

        return $formated_reply_info;
    }

    public static function getReplies($pid, $offset, $num, $direct = 1)
    {
        $max_floor = Counter::get(Counter::COUNTER_TYPE_REPLY_FLOOR, $pid);

        if (! $direct) {
            $offset = $offset > $max_floor ? $max_floor : $offset;
            $offset = $offset > $num ? $offset - $num : 0;
        }

        $dao_reply = new DAOReply($pid);
        $reply_list = $dao_reply->getReplies($pid, $offset, $num);

        $users = array();
        foreach ($reply_list as $reply_info) {
            $users[] = $reply_info["uid"];
        }

        $forbbidden_users = array();

        if (!empty($users)) {
            $forbbidden_users = array_values(Forbidden::isForbiddenUsers($users));
        }

        $replies = array();
        foreach ($reply_list as $reply_info) {
            if (!in_array($reply_info["uid"], $forbbidden_users)) {
                $replies[] = self::getFormatReply($reply_info);
            }
        }

        $total = $dao_reply->getTotal($pid);

        return array(
            $total,
            $replies
        );
    }

    public function report($pid, $rid)
    {
        $uid = Context::get("userid");
        $user_info = User::getUserInfo(Context::get("userid"));

        Interceptor::ensureNotFalse(! empty($user_info), ERROR_USER_NOT_EXIST);

        $cache = Cache::getInstance("REDIS_CONF_CACHE");
        $key = "report_reply_" . $pid . "_" . $rid . "_" . $uid;

        if (!$cache->get($key)) {
            $reply_info = $this->getReplyInfo($pid, $rid);
            Interceptor::ensureNotFalse(! empty($reply_info), ERROR_BIZ_REPLY_NOT_EXISTS, $rid);

            // todo 通知后台
            $bool = $cache->set($key, true, 24 * 60 * 60);
        }
    }

    public function delReply($pid, $rid)
    {
        $dao_reply = new DAOReply($pid);
        $reply_info = $dao_reply->getReplyInfo($rid);

        Interceptor::ensureNotFalse((Context::get("userid") == $reply_info['uid']), ERROR_BIZ_REPLY_NOT_SELF);

        Counter::decrease(Counter::COUNTER_TYPE_LIVE_REPLIES, $pid);

        return $dao_reply->delReply($rid);
    }

    public static function addAdminProcess($rid, $uid, $pid, $qid, $content, $type)
    {
        $reply_info = array(
            "rid"             => $rid,
            "relateid"         => $pid,
            "quoteid"         => $qid,
            "uid"             => $uid,
            "content"         => $content,
            "reply_time"     => date("Y-m-d H:i:s"),
            "type"             => $type
        );

        include_once 'process_client/ProcessClient.php';
        ProcessClient::getInstance("dream")->addTask(Context::getConfig("FRONT2ADMIN_PROCESS_REPLY_SYNC_DATA"), $reply_info);
    }
}
?>
