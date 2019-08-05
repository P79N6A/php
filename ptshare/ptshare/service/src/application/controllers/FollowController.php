<?php
class FollowController extends BaseController
{
    const FOLLOWING_LIMIT = 3000;

    public function getFollowingsAction()
    {
        $uid    = Context::get("userid");
        $offset = (int) $this->getParam("offset", 0);
        $num    = (int) $this->getParam("num", 20);

        Interceptor::ensureFalse($num > 200, ERROR_PARAM_INVALID_FORMAT, "num");

        $users = $fids = array();
        $more  = false;
        $list  = Follow::getUserFollowings($uid, $offset, 200);

        if ($list) {
            foreach ($list as $k => $v) {
                $fids[] = $v["uid"];
            }
            $userinfos = User::getFollowUserInfos($fids);

            foreach ($list as $k => $v) {
                $user_info = $userinfos[$v["uid"]];

                if (! $user_info) {
                    continue;
                }
                $user_info["followed"] = true;

                $users[] = $user_info;
                if (count($users) >= $num) {
                    break;
                }
            }
            $offset = $offset + $k + 1;
            $more = true;
        }

        $this->render(array(
            "users"                 => $users,
            "offset"                => $offset,
            "more"                  => $more,
        ));
    }

    public function getFollowersAction()
    {
        $uid = Context::get("userid");
        $offset = (int) $this->getParam("offset", 0);
        $num = (int) $this->getParam("num", 200);

        Interceptor::ensureFalse($num > 200, ERROR_PARAM_INVALID_FORMAT, "num");

        $users = $fids = array();
        $more  = false;

        $list = Follow::getUserFollowers($uid, $offset, $num);

        if ($list) {
            foreach ($list as $k => $v) {
                $fids[] = $v["uid"];
            }

            $followed = Follow::isFollowed($uid, $fids);
            $userinfos = User::getFollowUserInfos($fids);

            foreach ($list as $k => $v) {
                if ($v["id"]) {
                    $new_offset = $v["id"];
                }

                $user_info = $userinfos[$v["uid"]];
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

        $this->render(array(
            "users"                 => $users,
            "offset"                => $offset,
            "more"                  => $more,
        ));
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
        $more  = false;

        $list = Follow::getUserFollowings($uid, $offset, 200);

        if ($list) {
            foreach ($list as $k => $v) {
                $fids[] = $v["uid"];
            }

            $followed = Follow::isFollowed($loginid, $fids);
            $userinfos = User::getFollowUserInfos($fids);

            foreach ($list as $k => $v) {

                $user_info = $userinfos[$v["uid"]];
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

        $this->render(array(
            "users"                 => $users,
            "offset"                => $offset,
            "more"                  => $more,
        ));
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
        $more  = false;

        $list = Follow::getUserFollowers($uid, $offset, 200);

        if ($list) {
            foreach ($list as $k => $v) {
                $fids[] = $v["uid"];
            }

            $followed = Follow::isFollowed($loginid, $fids);
            $userinfos = User::getFollowUserInfos($fids);

            foreach ($list as $k => $v) {
                if ($v["id"]) {
                    $new_offset = $v["id"];
                }

                $user_info = $userinfos[$v["uid"]];
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

        $this->render(array(
            "users"                 => $users,
            "offset"                => $offset,
            "more"                  => $more,
        ));
    }

    public function addAction()
    {
        $loginid = Context::get("userid");
        $uid = (int) $this->getParam("uid", 0);

        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "uid");

        $userinfo = User::getUserInfo($uid);
        Interceptor::ensureNotEmpty($userinfo, ERROR_USER_NOT_EXIST);

        $counters = Counter::get(Counter::COUNTER_TYPE_FOLLOWINGS, $loginid);
        $followings = (int) $counters;

        Interceptor::ensureNotFalse($followings < self::FOLLOWING_LIMIT, ERROR_FOLLOW_TOO_MUCH);

        $followed = Follow::isFollowed($loginid, $uid);
        if($followed[$uid]){
            $this->render();
        }

        if ($loginid != $uid) {

            $followed = Follow::addFollow($loginid, $uid);
            
            if (! $followed[$uid]) {
                $this->render();
            }
        }

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

    public function isFollowedAction()
    {
        $uid = Context::get("userid");
        $fids = trim($this->getParam("fids"), ",");

        Interceptor::ensureNotFalse(preg_match("/^(\d+,?)+$/", $fids) != 0, ERROR_PARAM_INVALID_FORMAT, "fids($fids)");

        if($uid == 0){
            $followed = [];
            $list = explode(",", $fids);
            foreach($list as $fid){
                $followed[$fid] = false;
            }
            
            $this->render(array(
                "users" => $followed
            ));
        }

        $followed = array();

        if (strcmp($uid, $fids) == 0) {
            $followed = array(
                $uid => true
            );
        } else {
            $followed = Follow::isFollowed($uid, explode(",", $fids));
        }

        $this->render(array(
            "users" => $followed
        ));
    }
}
?>
