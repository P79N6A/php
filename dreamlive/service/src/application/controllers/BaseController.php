<?php
class BaseController
{
    public function __construct()
    {
        Consume::start();
        $this->init();
    }

    protected function getRequest($name = null, $default = null)
    {
        if (null === $name) {
            return $_GET;
        }

        return (isset($_GET[$name])) ?  $_GET[$name] : $default;
    }

    protected function getPost($name, $default = null)
    {
        if (null === $name) {
            return $_POST;
        }

        return (isset($_POST[$name])) ?  $_POST[$name] : $default;
    }

    protected function getCookie($name, $default = null)
    {
        return isset($_COOKIE[$name]) && !empty($_COOKIE[$name]) ? $_COOKIE[$name] : $default;
    }

    protected function getParam($name, $default = null)
    {
        if(null !== ($value = $this->getRequest($name, null))) {
            return $value;
        } else if(null !== ($value = $this->getPost($name, null))) {
            return $value;
        } else if(null !== ($value = $this->getCookie($name, null))) {
            return $value;
        }

        return ($value==null && $default !==null ) ? $default : $value;
    }

    public function init()
    {
        $deviceid = trim(strip_tags($this->getParam("deviceid")));
        $userid   = (int) $this->getParam("userid");
        $platform = trim(strip_tags($this->getParam("platform", "android")));
        $version  = trim($this->getParam("version"));
        $network  = trim(strip_tags($this->getParam("network")));
        $netspeed = trim(strip_tags($this->getParam("netspeed")));
        $rand     = trim(strip_tags($this->getParam("rand")));
        $time     = trim(strip_tags($this->getParam("time")));
        $channel  = trim(strip_tags($this->getParam("channel")));
        $armour   = trim(strip_tags($this->getParam("armour")));
        $lng = trim(strip_tags($this->getParam('lng')));
        $lat = trim(strip_tags($this->getParam('lat')));
        $app_name = trim(strip_tags($this->getParam('app_name')));

        Context::add("userid", $userid);
        Context::add("deviceid", $deviceid);
        Context::add("version", $version);
        Context::add("user_agent", $_SERVER["HTTP_USER_AGENT"]);
        Context::add("platform", $platform);
        Context::add("brand", trim(strip_tags($this->getParam("brand"))));
        Context::add("model", trim(strip_tags($this->getParam("model"))));
        Context::add("network", $network);
        Context::add("netspeed", $netspeed);
        //Context::add("region", trim(strip_tags($this->getParam("region", "china"))));
        Context::add("region", Region::REGION_CHINA);
        Context::add("channel", $channel);
        Context::add("armour", $armour);
        Context::add("lng", $lng);
        Context::add("lat", $lat);
        Context::add("app_name", $app_name);

        Interceptor::ensureNotFalse(in_array($platform, array("android", "ios", "server", "obs", "ws", "cdn","miniapp")), ERROR_PARAM_PLATFORM_INVALID);
        $guid = $this->getParam("guid");
        // 调试接口暂时将验证注释
        if (in_array($platform, array("ios", "android", "obs","miniapp"))) {
            Interceptor::ensureNotFalse(Util::isValidClient($userid, $deviceid, $platform, $network, $version, $rand, $netspeed, $time, $guid), ERROR_PARAM_SIGN_INVALID);
            Interceptor::ensureNotFalse(Util::checkFlood($guid), ERROR_PARAM_FLOOD_REQUEST);
        }elseif ($platform == 'cdn') {
            Interceptor::ensureNotFalse(Util::isValidCdn($rand, $time, $guid), ERROR_PARAM_SIGN_INVALID);
        } else {
            Interceptor::ensureNotFalse(Util::isValidServer($rand, $time, $guid), ERROR_PARAM_SIGN_INVALID);
        }

        $api_auth_conf = Context::getConfig("API_AUTH_CONFIG");

        $auth_conf = array();
        foreach ($api_auth_conf as $auth_uri => $value) {
            if (strcasecmp(Context::get("method"), $auth_uri) == 0) {
                $auth_conf = $value;
                break;
            }
        }
        if (! $auth_conf) {
            return;
        }

        if (in_array(AUTH_CHECK_POST, $auth_conf)) {
            Interceptor::ensureNotFalse($_SERVER["REQUEST_METHOD"] == "POST", ERROR_SYS_NEEDPOST);
        }

        if(in_array(AUTH_CHECK_LOGIN, $auth_conf)) {
            $token = trim($this->getCookie("token"));
            Interceptor::ensureNotFalse(Session::isLogined($token), ERROR_USER_ERR_TOKEN);

            Context::set("userid", Session::getLoginId($token));
        }

        if (in_array(AUTH_CHECK_FORBID, $auth_conf)) {
            $forbiddened = Forbidden::isForbidden(Context::get("userid"));

            if ($forbiddened) {
                $dao_forbidden  = new DAOForbidden();
                $forbidden_info = $dao_forbidden->getForbidden(Context::get("userid"));

                $reason = $forbidden_info["reason"];
                $expire = date("Y年m月d日", $forbidden_info["expire"]);
                $userinfo = User::getUserInfo(Context::get("userid"));
            
                Interceptor::ensureNotFalse(false, ERROR_USER_ERR_BLACK, [$userinfo['nickname'], Context::get("userid"), $expire]);
            }
        }
    }

    public function render($data = array())
    {
        header('Content-Type: application/json; charset=utf-8');
        header("Server: nginx/1.2.3");

        $result = array(
            "errno" => OK,
            "errmsg" => Util::getError(OK),
            "consume" => Consume::getTime(),
            "time" => Util::getTime(false),
            //"domain"=>MAIN_DOMAIN_NAME,
        );

        if (! empty($data)) {
            $result['md5'] = md5(json_encode($data));
            $result['data'] = $data;
        }

        $content = json_encode($result);

        Logger::notice($content, array(), $result["errno"]);

        print $content;
        exit();
    }
}
?>
