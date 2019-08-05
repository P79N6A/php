<?php
class AdminController extends BaseController
{
    public function addAction()
    {
        $role = new Role();
        list($total, $roles) = $role->getRoleList();

        $this->assign('role_list', $roles);

        $this->display("include/header.html", "admin/admin_add.html", "include/footer.html");
    }

    public function insertAction()
    {
        $username  = $this->getParam("username")   ? trim(strip_tags($this->getParam("username"))) : '';
        $roleids   = $this->getParam("roleids", array());
        $password  = $this->getParam("password")   ? trim(strip_tags($this->getParam("password"))) : '';
        $realname  = $this->getParam("realname")   ? trim(strip_tags($this->getParam("realname"))) : '';
        $mobile    = $this->getParam("mobile")     ? trim(strip_tags($this->getParam("mobile")))   : '';
        $super     = $this->getParam("super")      ? trim(strip_tags($this->getParam("super")))    : 'N';

        Interceptor::ensureNotEmpty($username, ERROR_PARAM_IS_EMPTY, "username");
        Interceptor::ensureNotEmpty($password, ERROR_PARAM_IS_EMPTY, "password");
        Interceptor::ensureNotEmpty($realname, ERROR_PARAM_IS_EMPTY, "realname");
        Interceptor::ensureNotFalse(in_array($super,  array('Y', 'N')), ERROR_PARAM_IS_EMPTY, "super");

        $admin = new Admin();

        if($admin->exists($username)) {
            Util::showError("用户已存在");
        }

        try {
            $admin->addAdmin($username, $password ,$realname, $mobile, $super,  $roleids);
        } catch(Exception $e) {
            Util::jumpMsg("添加失败!", "/admin/add/");
        }

        Util::jumpMsg("添加成功!", "/admin/");
    }

    public function modifyAction()
    {
        $adminid    = intval($this->getParam("adminid"));

        Interceptor::ensureNotFalse($adminid > 0, ERROR_PARAM_IS_EMPTY);

        $admin = new Admin();
        $admin_info = $admin->getAdminInfo($adminid);

        $role = new Role();
        list($total, $role_list) = $role->getRoleList();

        $this->assign(array("admin_info"=>$admin_info, "role_list"=>$role_list));

        $this->display("include/header.html", "admin/admin_edit.html", "include/footer.html");
    }

    public function updateAction()
    {
        $roleids   = $this->getParam("roleids")  ? $this->getParam("roleids") : array();
        $super     = $this->getParam("super")    ? trim(strip_tags($this->getParam("super")))    : 'N';
        $realname  = $this->getParam("realname") ? trim(strip_tags($this->getParam("realname"))) : '';
        $adminid   = $this->getParam("adminid")  ? intval($this->getParam('adminid'))            : 0;

        Interceptor::ensureNotEmpty($adminid, ERROR_PARAM_IS_EMPTY, "adminid");
        Interceptor::ensureNotEmpty($realname, ERROR_PARAM_IS_EMPTY, "realname");

        try {
            $admin = new Admin();
            $admin->setAdmin($adminid, $super, $roleids, $realname);
        } catch (Exception $e) {
            Util::showError("修改失败");
        }

        Util::jumpMsg("修改成功!", "/admin/");
    }

    public function deleteAction()
    {
        $adminid   = $this->getParam("adminid")    ? trim(strip_tags($this->getParam("adminid")))             : '';

        Interceptor::ensureNotFalse($adminid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "adminid");

        try {
            $admin = new Admin();
            $admin->delete($adminid);
        } catch (Exception $e) {
            Util::showError("停用失败");
        }

        Util::jumpMsg("停用成功!", "/admin/");
    }

    public function restoreAction()
    {
        $adminid   = $this->getParam("adminid")    ? trim(strip_tags($this->getParam("adminid")))             : '';

        Interceptor::ensureNotFalse($adminid > 0, ERROR_PARAM_NOT_SMALL_ZERO, "adminid");

        try {
            $admin = new Admin();
            $admin->restore($adminid);
        } catch (Exception $e) {
            Util::showError("恢复失败");
        }

        Util::jumpMsg("恢复成功!", "/admin/");
    }

    public function indexAction()
    {
        $admin = new Admin();
        list($total, $admin_list) = $admin->getAdminList();

        $this->assign("admin_list", $admin_list);

        $role = new Role();
        list($total, $role_list) = $role->getRoleList();

        $role_hash = array();
        foreach($role_list as $role_info) {
            $role_hash[$role_info['roleid']] = $role_info['name'];
        }

        $this->assign("role_list", $role_hash);

        $this->display("include/header.html", "admin/admin_list.html", "include/footer.html");
    }

    public function resetAction()
    {
        $adminid = $this->getParam("adminid") ? intval($this->getParam("adminid")) : 0;

        $admin = new Admin();
        $admin->reset($adminid);

        $this->render();
    }

    public function profileAction()
    {
        $this->display("include/header.html", "admin/profile.html", "include/footer.html");
    }

    public function passwordAction()
    {
        $this->display("include/header.html", "admin/password.html", "include/footer.html");
    }

    public function changeAction()
    {
        $password = $this->getParam("password") ? $this->getParam("password") : "";

        Interceptor::ensureNotEmpty($password, ERROR_PARAM_IS_EMPTY);

        try {
            $admin = new Admin();
            $admin->change(Context::get("adminid"), $password);
        } catch(Exception $e) {
            Util::jumpMsg("密码修改失败!", "/admin/password/");
        }

        Util::jumpMsg("密码修改成功!", "/admin/password/");
    }
}
?>