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
        $channel  = trim(strip_tags($this->getParam("channel")));
        Context::add("channel", $channel);
        
        $userid  = (int) $this->getParam("userid");

        Context::add("userid", $userid);
        
        
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

    protected function fetch($filename)
    {
        ob_start();
        extract($this->variables);
        require_once(VIEW_PATH . "/" . $filename);
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    protected function display()
    {
        foreach (func_get_args() as $key => $val) {
            print($this->fetch($val));
        }
        exit();
    }

    public function assign($name, $value = "")
    {
        if (is_array($name)) {
            foreach ($name as $key => $val) {
                $this->variables[$key] = $val;
            }
        } else {
            $this->variables[$name] = is_object($value) ? $value->fetch() : $value;
        }
    }
}
?>
