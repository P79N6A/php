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

    protected function getParam($name, $default = null, $filter = true)
    {
        $return = '';
        if(null !== ($value = $this->getRequest($name, null))) {
            $return = $value;
        } else if(null !== ($value = $this->getPost($name, null))) {
            $return = $value;
        } else if(null !== ($value = $this->getCookie($name, null))) {
            $return = $value;
        }

        if ($return && $filter) {
//            return XssFilter::filter($return);
        }

        return ($value==null && $default !==null ) ? $default : $return;
    }

    public function init()
    {
        Logger::log('request_api', '', [json_encode($_REQUEST)]);

        $channel  = trim(strip_tags($this->getParam("channel")));
        Context::add("channel", $channel);

        $userid  = (int) $this->getParam("userid");
        $wxProgramFormId  = $this->getParam("wx_program_form_id");
        if ($wxProgramFormId) {
            Context::add("wx_program_form_id", $wxProgramFormId);
        }
        $partner = $this->getParam("partner") ? trim(strip_tags($this->getParam("partner"))) : "client";

        Context::add("userid", $userid);
        $api_auth_conf = Context::getConfig("API_AUTH_CONFIG");
        $auth_conf = array();
        foreach ($api_auth_conf as $auth_uri => $value) {
            if (strcasecmp(Context::get("method"), $auth_uri) == 0) {
                $auth_conf = $value;
                break;
            }
        }
//        var_dump($api_auth_conf);die;

        if (! $auth_conf) {
//            return;
        }

        if (in_array(AUTH_CHECK_POST, $auth_conf)) {
            Interceptor::ensureNotFalse($_SERVER["REQUEST_METHOD"] == "POST", ERROR_SYS_NEEDPOST);
        }

        $token = trim($this->getCookie("token"));


        if(in_array(AUTH_CHECK_LOGIN, $auth_conf)) {
            Interceptor::ensureNotFalse(Session::isLogined($token), ERROR_USER_ERR_TOKEN);
            Context::set("userid", Session::getLoginId($token));
        }else if (!empty($token)) {
            $logined = Session::isLogined($token);
            if ($logined) {
                Context::set("userid", Session::getLoginId($token));
            }
        }

        if(in_array(AUTH_CHECK_SERVER, $auth_conf)) {
            Interceptor::ensureNotFalse(Util::isValidServer($partner), ERROR_API_NOT_ALLOWED);
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
