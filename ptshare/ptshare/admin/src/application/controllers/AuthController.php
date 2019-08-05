<?php
class AuthController extends BaseController
{
    public function addAction()
    {
        $auth = new Auth();
        $module_list = $auth->getModuleList();

        $this->assign("module_list", $module_list);

		$this->display("include/header.html", "auth/auth_add.html", "include/footer.html");
    }

    public function addAuthAction()
    {
        $pid  = $this->getParam("pid")   ? intval($this->getParam("pid"))            : 0;
        $code = $this->getParam("code") ? trim(strip_tags($this->getParam("code"))) : '';
        $type = $this->getParam("type") ? intval($this->getParam("type"))           : 1;
        $name = $this->getParam("name") ? trim(strip_tags($this->getParam("name"))) : '';

        Interceptor::ensureNotEmpty($code, ERROR_PARAM_IS_EMPTY, "code");
        Interceptor::ensureNotEmpty($name, ERROR_PARAM_IS_EMPTY, "name");

        try {
            $auth = new Auth();
            $auth->addAuth($pid, $type, $code, $name);

            Util::jumpMsg("权限添加成功", "/auth/");
        } catch (Exception $e) {
            Util::showError("权限添加失败");
        }
    }

    public function modifyAction()
    {
        $authid = $this->getParam("authid") ? intval($this->getParam("authid")): 0;

        Interceptor::ensureNotEmpty($authid, ERROR_PARAM_IS_EMPTY, "authid");

        $auth       = new Auth();
        $auth_info  = $auth->getAuthInfo($authid);

        $this->assign("auth_info", $auth_info);

        $this->display("include/header.html", "auth/auth_modify.html", "include/footer.html");
    }

    public function updateAction()
    {
        $authid    = $this->getParam("authid")? intval($this->getParam("authid")): 0;
        $name      = $this->getParam("name")? trim(strip_tags($this->getParam("name"))): '';

        Interceptor::ensureNotEmpty($authid, ERROR_PARAM_IS_EMPTY, "authid");
        Interceptor::ensureNotEmpty($name, ERROR_PARAM_IS_EMPTY, "name");

        try {
            $auth = new Auth();
            $auth->setAuth($authid,$name);

            Util::jumpMsg("权限修改成功", "/auth/");
        } catch (Exception $e) {
            Util::showError("权限修改失败");
        }
    }

    public function delAuthAction()
    {
        $authid    = $this->getParam("authid")? intval($this->getParam("authid")): 0;

        Interceptor::ensureNotEmpty($authid, ERROR_PARAM_IS_EMPTY, "authid");

        try {
            $auth = new Auth();
            $auth->delAuth($authid);

            Util::jumpMsg("权限删除成功", "/auth/");
        } catch (Exception $e) {
            Util::showError("权限删除失败");
        }
    }

    public function indexAction()
    {
        $auth = new Auth();
        $arr_auth_list = $auth->getAuthList();

        $this->assign("auth_list", $arr_auth_list);

		$this->display("include/header.html", "auth/auth_list.html", "include/footer.html");
    }

    public function assignAction()
    {
        $roleid = $this->getParam("roleid") ? intval($this->getParam("roleid")) : 0;

        $role = new Role();
        list($total, $role_list) = $role->getRoleList();

        $auth = new Auth();
        $module_list = $auth->getModuleList();

        $role = new Role();
        $role_info = $role->getRoleInfo($roleid);

        $auth_list = array();
        foreach($module_list as $key=>$module_info) {
            $auth_list[$key]["module"] = $module_info;
            $auth_list[$key]["authes"] = $auth->getAuthListByModuleId($module_info["authid"]);
        }

        $this->assign("roleid", $roleid);
        $this->assign("auth_code", explode(",", $role_info["auth"]));
        $this->assign("role_list", $role_list);
        $this->assign("auth_list", $auth_list);

        $this->display("include/header.html", "auth/auth_assign.html", "include/footer.html");
    }

    public function saveAction()
    {
        $roleid  = $this->getParam("roleid")  ? intval($this->getParam("roleid")) : 0;
        $authids = $this->getParam("authids") ? $this->getParam("authids")        : array();

        Interceptor::ensureNotEmpty($roleid, ERROR_PARAM_IS_EMPTY, "roleid");

        $auth = new Auth();
        $auth_list = $auth->getAuthList();

        $codes = array();
        foreach($auth_list as $auth_info) {
            if(in_array($auth_info["authid"], $authids)) {
                $codes[] = $auth_info["code"];
            }
        }

        try {
            $role = new Role();
            $role->setAuth($roleid, implode(",", $codes));
        } catch(Exception $e) {
            Util::showError("权限设置失败!");
        }

        Util::jumpMsg("权限设置成功!", "/auth/assign?roleid=$roleid");
    }
}
?>