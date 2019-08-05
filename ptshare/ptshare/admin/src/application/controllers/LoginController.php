<?php
class LoginController extends BaseController
{
    public function loginAction()
    {
        $username  = $this->getParam("username") ? trim(strip_tags($this->getParam("username"))) : '';
        $password  = $this->getParam("password") ? trim(strip_tags($this->getParam("password"))) : '';

        Interceptor::ensureNotEmpty($username, ERROR_PARAM_IS_EMPTY, "username");
        Interceptor::ensureNotEmpty($password, ERROR_PARAM_IS_EMPTY, "password");

        $admin = Admin::getInstance();
        $admin_info = $admin->getAdminByName($username);

        if(!is_array($admin_info) || intval($admin_info['adminid']) <= 0){
            Util::jumpMsg("登陆失败!", "/login/");
        }

        if ($password != $admin_info["password"]) {
            Util::jumpMsg("密码错误!", "/login/");
        }

        Cookie::set('admin', json_encode($admin_info), 86400);

        Util::jumpMsg("您已成功登陆!", "/index/");
    }

	public function indexAction()
	{
        $this->display("index/login.html");
    }

    public function logoutAction()
    {
        Cookie::delete("admin");

        header("Location: /login/");
        exit;
    }
}
?>
