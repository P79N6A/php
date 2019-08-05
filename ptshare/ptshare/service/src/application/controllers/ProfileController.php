<?php
class ProfileController extends BaseController
{
    public function syncAction()
    {
        $uid = Context::get("userid");
        Interceptor::ensureNotFalse($uid > 0, ERROR_PARAM_INVALID_FORMAT, "userid");

        $profiles = trim($this->getParam("profiles"));
        Interceptor::ensureNotEmpty($profiles, ERROR_PARAM_IS_EMPTY, "profiles");

        $profile_info = json_decode($profiles, true);
        Interceptor::ensureNotFalse(is_array($profile_info), ERROR_PARAM_INVALID_FORMAT, 'profiles');

        $dao_profile = new DAOProfile($uid);
        $user_info = array();
        foreach ($profile_info as $k => $v) {
           if(array_key_exists($k, DAOProfile::$PROFILE_KEY)) {
                if($k == 'social'){
                    $v = json_encode($v);
                }
                $v = strip_tags($v);
                $dao_profile->modProfile(array(
                    "item" => $k,
                    "value" => trim($v)
                ));
            }
        }

        // 调用 任务接口
        $_profiles = $dao_profile->getUserProfiles();
        if($_profiles["height"] && $_profiles["weight"] && $_profiles["shoe_size"] && $_profiles["tag"]){
            try {
                Task::execute($uid, 7, 1);
            } catch (Exception $e) {}
        }

        $this->render();
    }

    public function getProfileAction()
    {
        $uid  = (int)$this->getParam("uid");
        $item = trim($this->getParam("item"));

        Interceptor::ensureNotEmpty($uid, ERROR_PARAM_IS_EMPTY, "uid");

        $dao_profile = new DAOProfile($uid);
        $_profiles = $dao_profile->getUserProfiles();

        $profiles = $item ? $_profiles[$item] : $_profiles;

        $this->render($item ? array($item => $profiles) : $profiles);
    }
}
