<?php
class BaseController
{
	//当前登陆人信息
	protected $admin_info = array();
	protected $adminid = 0;
	private $authid;
    protected $variables = array();

    public function __construct()
    {
        $this->prepare();
    }

    public function prepare()
    {
        if(in_array($_SERVER["REQUEST_URI"], array("/login/", "/login/login/"))) {
            return;
        }

        if(!Admin::isLogined()) {
            header("Location: /login/");
            exit;
        }

        $admin_info = json_decode(Cookie::get('admin'), true);

        $role    = new Role();
        $roleids = explode(",", $admin_info['roleid']);
        foreach($roleids as $roleid){
            $authids = array_merge($authids, $role->getAuth($roleid));
        }

        $admin_info["roleids"] = $roleids;
        $admin_info["authids"] = $authids;

        $this->assign("admin_info", $admin_info);

        Context::set("admin_info", $admin_info);
        Context::set("adminid", $admin_info["adminid"]);
        $this->adminid = $admin_info['adminid'];

        $this->admin_info = $admin_info;
    }

    public function setAuthId($authid)
    {
    	$this->authid[] = intval($authid);
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

    protected function mutipage($total, $curpage, $num, $args = '', $url = '')
    {
        if ($args != '') {
            $args = '&'.$args;
        }
        $args .= "&num=$num";
        $url .= false !== strpos($url, '?') ? '&' : '?';

        $total_page = ceil($total / $num);

        if ($total_page == 0) {
            $total_page = 1;
            $curpage = 1;
        }

        if ($total_page == 1) {
            return '';
        }
        $output = null;

        $output .= ' <ul class="spage pagination no-margin">';

        if ($curpage > 1) {
            $output .= '<li><a href='.$url.'page=1'.$args.' title="首页">首页</a></li>';
            $output .= '<li><a href='.$url.'page='.($curpage - 1).$args.' title="上一页">上一页</a></li>';
        }

        $start = floor($curpage / 10) * 10;
        $end = $start + 9;
        if ($start < 1) {
            $start = 1;
        }

        if ($end > $total_page) {
            $end = $total_page;
        }

        $output .= ' ';
        for ($i = $start; $i <= $end; ++$i) {
            $output .= ($i != 1 && $i % 10) ? ' ' : '';

            if ($curpage == $i) {
                $output .= '<li class="active"><a href="#" onclick="return false;" class="this-page">'.$i.'</a></li>';    //输出当前页数
            } else {
                $output .= '<li><a href="'.$url.'page='.($i).$args.'">'.$i.'</a></li>';    //输出页数
            }
        }

        if ($curpage < $total_page) {
            $output .= '<li><a href='.$url.'page='.($curpage + 1).$args.' title="下一页">下一页</a></li>';
            $output .= '<li><a href='.$url.'page='.$total_page.$args.' title="尾页">尾页</a></li>';
        }

        $output .= '</ul>';
        return $output;
    }

    public function isPost()
    {
        return strtoupper($_SERVER['REQUEST_METHOD']) == 'POST';
    }

    public function isAjax()
    {
        return ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') || ("Y" == $this->getParam("json_call"));
    }

    public function success($message, $data = array())
    {
        if ($this->isAjax()) {
            $this->render($data, 0, $message);
        } else {
            $this->assign('message', $message);
            $this->assign('data', $data);
            $this->display('include/success.html');
        }
    }

    public function error($message, $code = 1)
    {
        if ($this->isAjax()) {
            $this->render(array(), $message, $code);
        } else {
            $this->assign('message', $message);
            $this->display('include/error.html');
        }

    }

    public function render($data = array(), $errno = 0, $errmsg = "")
    {
        header('Content-Type: application/json; charset=UTF-8');
        header("Server: nginx/1.2.3");

        $result = array(
            "errno" => $errno,
            "errmsg" => $errmsg,
            "time" => time(),
        );


        if (!empty($data)) {
            $result['md5'] = md5(json_encode($data));
            $result['data'] = $data;
        }

        $content = json_encode($result);

        print $content;
        exit;
    }
}
?>