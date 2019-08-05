<?php
class ProfileController extends BaseController
{
    public function syncAction()
    {
        /* {{{ */
        $uid = Context::get("userid");
        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "userid");

        $profiles = trim($this->getParam("profiles"));
        Interceptor::ensureNotEmpty($profiles, ERROR_PARAM_IS_EMPTY, "profiles");

        $profile_info = json_decode($profiles, true);
        Interceptor::ensureNotFalse(is_array($profile_info), ERROR_PARAM_INVALID_FORMAT, 'profiles');

        include_once 'process_client/ProcessClient.php';

        $dao_profile = new DAOProfile($uid);
        $user_info = array();
        foreach ($profile_info as $k => $v) {
            if (in_array(
                $k, array(
                "avatar",
                "signature",
                "nickname",
                "gender",
                "location",
                "birth"
                )
            )
            ) {
                $user_info[$k] = trim(strip_tags($v));
                unset($profile_info[$k]);
            } elseif (array_key_exists($k, DAOProfile::$PROFILE_KEY)) {
                $dao_profile->modProfile(
                    array(
                    "item" => $k,
                    "value" => trim($v)
                    )
                );
                if($k == 'option_city_hidden') {
                    $redis = Cache::getInstance("REDIS_CONF_USER");
                    if($v == "Y") {
                        $redis->sAdd("dreamlive_users_city_hidden", $uid);
                    }elseif($v == "N") {
                        $redis->sRem("dreamlive_users_city_hidden", $uid);
                    }
                }
            }
        }

        if ($user_info) {
            $user_info = array_diff_assoc($user_info, User::getUserInfo($uid));

            if ($user_info) {
                // 不允许纯数字
                if (isset($user_info["nickname"])) {
                    Interceptor::ensureFalse(mb_strwidth($user_info["nickname"], "utf8") > 16, ERROR_USER_NAME_TOOLONG, $user_info["nickname"]);
                    Interceptor::ensureFalse(mb_strwidth($user_info["nickname"], "utf8") < 4, ERROR_USER_NAME_SHORT, $user_info["nickname"]);
                    Interceptor::ensureFalse(empty($user_info["nickname"]) || is_numeric($user_info["nickname"]) || preg_match("/(" . RULE_DIRTY_WORDS . "|" . RULE_PROTECT_WORDS . ")/i", $user_info['nickname']), ERROR_USER_NAME_DIRTY, $user_info["nickname"]);
                    Interceptor::ensureFalse(FilterKeyword::keywordFilterFromCache($user_info["nickname"]), ERROR_USER_NAME_DIRTY, $user_info["nickname"]);
                    
                    $dao_user = new DAOUser();
                    Interceptor::ensureFalse($dao_user->exists($user_info["nickname"]), ERROR_USER_NAME_EXISTS, $user_info["nickname"]);
                }
                if (isset($user_info["signature"])) {
                    Interceptor::ensureFalse(FilterKeyword::keywordFilterFromCache($user_info["signature"]), ERROR_USER_SIGNATURE_DIRTY, $user_info["signature"]);
                }
                $user = new User();
                $user->setUserInfo($uid, $user_info['nickname'], $user_info['signature'], $user_info['avatar'], $user_info['gender'], $user_info['location'], $user_info['birth']);
            }
        }

        $this->render();
    }/*}}}*/

    public function getProfileAction()
    {
        /*{{{*/
        $uid  = (int)$this->getParam("uid");
        $item = trim($this->getParam("item"));

        Interceptor::ensureNotEmpty($uid, ERROR_PARAM_IS_EMPTY, "uid");

        $dao_profile = new DAOProfile($uid);
        $_profiles = $dao_profile->getUserProfiles();

        $profiles = $item ? $_profiles[$item] : $_profiles;

        $this->render($item ? array($item => $profiles) : $profiles);
    }/*}}}*/
}
