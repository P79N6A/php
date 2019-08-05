<?php
class FollowController extends BaseController
{
    const FOLLOWING_LIMIT = 3000;

    public function getFollowingsAction()
    {
        $uid = Context::get("userid");
        $offset = (int) $this->getParam("offset", 0);
        $num = (int) $this->getParam("num", 20);

        Interceptor::ensureFalse($num > 200, ERROR_PARAM_INVALID_FORMAT, "num");

        $users = $fids = array();
        $more = $isforbidden = false;
        $list = Follow::getUserFollowings($uid, $offset, 200);

        if ($list) {
            foreach ($list as $k => $v) {
                $fids[] = $v["uid"];
            }
            $forbidden_users = Forbidden::isForbiddenUsers($fids);
            foreach ($list as $k => $v) {
                if (in_array($v["uid"], $forbidden_users)) {
                    $isforbidden = true;
                    continue;
                }
                $user = new User();
                $user_info = $user->getUserInfo($v["uid"]);
                if (! $user_info) {
                    continue;
                }
                $user_info["followed"] = true;
                $user_info["blocked"] = false;
                $user_info["notice"] = ! ! $v["notice"];
                $users[] = $user_info;
                if (count($users) >= $num) {
                    break;
                }
            }
            $offset = $offset + $k + 1;
            $more = true;
        }

        $is_remind_forbidden = $isforbidden;
        $this->render(
            array(
            "users"                 => $users,
            "offset"                => $offset,
            "more"                  => $more,
            "is_remind_forbidden"   => $is_remind_forbidden
            )
        );
    }

    public function getFollowersAction()
    {
        $uid = Context::get("userid");
        $offset = (int) $this->getParam("offset", 0);
        $num = (int) $this->getParam("num", 20);

        Interceptor::ensureFalse($num > 200, ERROR_PARAM_INVALID_FORMAT, "num");

        $users = $fids = array();
        $more = $isforbidden = false;

        $list = Follow::getUserFollowers($uid, $offset, 200);

        if ($list) {
            foreach ($list as $k => $v) {
                $fids[] = $v["uid"];
            }

            $forbidden_users = Forbidden::isForbiddenUsers($fids);
            
            $followed = Follow::isFollowed($uid, $fids);

            foreach ($list as $k => $v) {
                if ($v["id"]) {
                    $new_offset = $v["id"];
                }

                if (in_array($v["uid"], $forbidden_users)) {
                    $isforbidden = true;
                    continue;
                }

                $user = new User();
                $user_info = $user->getUserInfo($v["uid"]);
                if (! $user_info) {
                    continue;
                }

                $user_info["followed"] = ! ! $followed[$v["uid"]];
                $users[] = $user_info;

                if (count($users) >= $num) {
                    break;
                }
            }

            $offset = $new_offset ? $new_offset : $offset + $k + 1;
            $more = true;
        }

        $counter = Counter::get(Counter::COUNTER_TYPE_FOLLOWERS, $uid);

        $is_remind_forbidden = $isforbidden || $counter >= 200;

        $this->render(
            array(
            "users"                 => $users,
            "offset"                => $offset,
            "more"                  => $more,
            "is_remind_forbidden"   => $is_remind_forbidden
            )
        );
    }

    public function getFriendsAction()
    {
        $uid = Context::get("userid");
        $offset = (int) $this->getParam("offset", 0);
        $num = (int) $this->getParam("num", 20);

        Interceptor::ensureFalse($num > 3000, ERROR_PARAM_INVALID_FORMAT, "num");

        $users = $fids = array();
        $more = $isforbidden = false;

        $list = Follow::getUserFriends($uid, $offset, $num);

        if ($list) {
            foreach ($list as $k => $v) {
                $fids[] = $v["uid"];
            }

            $forbidden_users = Forbidden::isForbiddenUsers($fids);

            foreach ($list as $k => $v) {
                $user_info = User::getUserInfo($v["uid"]);
                if (! $user_info || in_array($user_info["uid"], $forbidden_users)) {
                    $isforbidden = true;
                    continue;
                }

                $users[] = $user_info;
            }

            $offset = $offset + $k + 1;
            $more = $k == ($num - 1);
        }

        $is_remind_forbidden = $isforbidden;

        $this->render(
            array(
            "users"                 => $users,
            "offset"                => $offset,
            "more"                  => $more,
            "is_remind_forbidden"   => $is_remind_forbidden
            )
        );
    }

    
    public function isFollowedAction()
    {
        $uid = Context::get("userid");
        $fids = trim($this->getParam("fids"), ",");

        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        Interceptor::ensureNotFalse(preg_match("/^(\d+,?)+$/", $fids) != 0, ERROR_PARAM_INVALID_FORMAT, "fids($fids)");

        $followed = array();

        if (strcmp($uid, $fids) == 0) {
            $followed = array(
                $uid => true
            );
        } else {
            $followed = Follow::isFollowed($uid, explode(",", $fids));
        }

        $this->render(
            array(
            "users" => $followed
            )
        );
    }

    public function isFriendAction()
    {
        $uid = Context::get("userid");
        $fids = trim($this->getParam("fids"), ",");

        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");
        Interceptor::ensureNotFalse(preg_match('/^(\d+,?)+$/', $fids) != 0, ERROR_PARAM_INVALID_FORMAT, "fids($fids)");

        $friends = array();
        $friends = Follow::isFriend($uid, explode(",", $fids));

        $this->render(
            array(
            "friends" => $friends
            )
        );
    }

    public function getUserFollowingsAction()
    {
        $loginid = Context::get("userid");
        $uid = (int) $this->getParam("uid", 0);
        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");

        $offset = (int) $this->getParam("offset", 0);
        $num = (int) $this->getParam("num", 20);

        Interceptor::ensureFalse($num > 200, ERROR_PARAM_INVALID_FORMAT, "num");

        $users = $fids = array();
        $more = $isforbidden = false;

        $list = Follow::getUserFollowings($uid, $offset, 200);

        if ($list) {
            foreach ($list as $k => $v) {
                $fids[] = $v["uid"];
            }

            $forbidden_users = Forbidden::isForbiddenUsers($fids);

            $followed = Follow::isFollowed($loginid, $fids);

            foreach ($list as $k => $v) {
                if (in_array($v["uid"], $forbidden_users)) {
                    $isforbidden = true;
                    continue;
                }

                $user = new User();
                $user_info = $user->getUserInfo($v["uid"]);
                if (! $user_info) {
                    continue;
                }

                $user_info["followed"] = ! ! $followed[$v["uid"]];
                $users[] = $user_info;

                if (count($users) >= $num) {
                    break;
                }
            }

            $offset = $offset + $k + 1;
            $more = true;
        }

        $is_remind_forbidden = $isforbidden;
        $this->render(
            array(
            "users"                 => $users,
            "offset"                => $offset,
            "more"                  => $more,
            "is_remind_forbidden"   => $is_remind_forbidden
            )
        );
    }

    public function getUserFollowersAction()
    {
        $loginid = Context::get("userid");
        $uid = (int) $this->getParam("uid", 0);
        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");

        $offset = (int) $this->getParam("offset", 0);
        $num = (int) $this->getParam("num", 20);

        Interceptor::ensureFalse($num > 200, ERROR_PARAM_INVALID_FORMAT, "num");

        $users = $fids = array();
        $more = $isforbidden = false;

        $list = Follow::getUserFollowers($uid, $offset, 200);

        if ($list) {
            foreach ($list as $k => $v) {
                $fids[] = $v["uid"];
            }

            $forbidden_users = Forbidden::isForbiddenUsers($fids);

            $followed = Follow::isFollowed($loginid, $fids);

            foreach ($list as $k => $v) {
                if ($v["id"]) {
                    $new_offset = $v["id"];
                }

                if (in_array($v["uid"], $forbidden_users)) {
                    $isforbidden = true;
                    continue;
                }

                $user = new User();
                $user_info = $user->getUserInfo($v["uid"]);
                if (! $user_info) {
                    continue;
                }

                $user_info["followed"] = ! ! $followed[$v["uid"]];
                $users[] = $user_info;

                if (count($users) >= $num) {
                    break;
                }
            }

            $offset = $new_offset ? $new_offset : $offset + $k + 1;
            $more = true;
        }

        $counter = Counter::get(Counter::COUNTER_TYPE_FOLLOWERS, $uid);

        $is_remind_forbidden = $isforbidden || $counter >= 200;
        $this->render(
            array(
            "users"                 => $users,
            "offset"                => $offset,
            "more"                  => $more,
            "is_remind_forbidden"   => $is_remind_forbidden
            )
        );
    }

    public function addAction()
    {
        $loginid = Context::get("userid");
        $uid = (int) $this->getParam("uid", 0);

        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");

        $liveid = (int) $this->getParam("liveid", 0);

        Interceptor::ensureFalse(Restrict::add("/follow/add", $loginid), ERROR_PARAM_REQUEST_RESTRICT);

        $counters = Counter::get(Counter::COUNTER_TYPE_FOLLOWINGS, $loginid);
        $followings = (int) $counters;

        Interceptor::ensureNotFalse($followings < self::FOLLOWING_LIMIT, ERROR_FOLLOW_TOO_MUCH);

        $followed = Follow::isFollowed($loginid, $uid);
        if($followed[$uid]) {
            Interceptor::ensureNotEmpty($liveid, ERROR_FOLLOW_FOLLOWED);
            $this->render();
        }

        $user = new User();

        if ($loginid != $uid) {
            $following_userinfo = $user->getUserInfo($uid);
            Interceptor::ensureNotFalse($following_userinfo && ! Forbidden::isForbidden($uid), ERROR_USER_NOT_EXIST);

            $dao_blocked = new DAOBlocked();
            Interceptor::ensureFalse($dao_blocked->exists($uid, $loginid), ERROR_USER_BLOCKED);

            $followed = Follow::addFollow($loginid, $uid, $liveid);
            
            //注释newsfeeds by yangqing
            //require_once "process_client/ProcessClient.php";
            // worker 拉取我关注人的feeds，插入我的newsfeed表
            //$bool = ProcessClient::getInstance("dream")->addTask("followings_increase_newsfeeds", array("uid" => $loginid,"type"=>'follow',"fid" => $uid));
            
            if (! $followed[$uid]) {
                $this->render();
            }
        }

        $this->render();
    }

    public function multiAddAction()
    {
        /* {{{ 批量关注一群人 userid代表我, uids代表一群人 */
        $loginid = Context::get("userid");
        $uids = trim($this->getParam("uids"), ",");
        Interceptor::ensureNotFalse(preg_match("/^(\d+,?)+$/", $uids) != 0 && substr_count($uids, ",") < 20, ERROR_PARAM_INVALID_FORMAT, "uids($uids)");

        Interceptor::ensureFalse(Restrict::add("/follow/multiAdd", $loginid), ERROR_PARAM_REQUEST_RESTRICT);

        $list = explode(",", $uids);

        try {
            $counters = Counter::get(Counter::COUNTER_TYPE_FOLLOWINGS, $loginid);
            $followings = (int) $counters;
        } catch (Exception $e) {
            $followings = 0;
        }

        Interceptor::ensureNotFalse($followings < self::FOLLOWING_LIMIT, ERROR_FOLLOW_TOO_MUCH);

        $user = new User();
        $userinfo = $user->getUserInfo($loginid);
        $filter_list = array();

        $forbidden_users = Forbidden::isForbiddenUsers($list);

        foreach ($list as $uid) {
            if ($loginid != $uid) {
                $following_userinfo = $user->getUserInfo($uid);

                if (! $following_userinfo || in_array($following_userinfo["uid"], $forbidden_users)) {
                    continue;
                }

                $filter_list[] = $uid;
            }
        }

        $followed = Follow::addFollow($loginid, $filter_list, "multi");
        $this->render();
    }

    public function cancelAction()
    {
        $loginid = Context::get("userid");
        $uid = (int) $this->getParam("uid", 0);
        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");

        Follow::cancelFollow($loginid, $uid);

        $this->render();
    }

    public function setOptionNoticeAction()
    {
        /*{{{*/
        $loginid = Context::get("userid");
        $fid     = (int)$this->getParam("fid");
        $notice = trim($this->getParam("notice", "N"));

        Interceptor::ensureNotFalse($fid > 0, ERROR_PARAM_INVALID_FORMAT, "fid");
        Interceptor::ensureNotFalse(in_array($notice, array("Y", "N")), ERROR_PARAM_INVALID_FORMAT, "notice");

        Follow::setOptionNotice($loginid, $fid, $notice);

        $this->render();
    }/*}}}*/

    public function cancelReportAction()
    {
        $followed = (int) $this->getParam("followed", 0);
        $follower = (int) $this->getParam("follower", 0);
        Interceptor::ensureNotFalse($follower > 0, ERROR_PARAM_INVALID_FORMAT, "follower");
        Interceptor::ensureNotFalse($followed > 0, ERROR_PARAM_INVALID_FORMAT, "followed");

        Follow::cancelFollow($follower, $followed);

        $this->render();
    }
}
?>
