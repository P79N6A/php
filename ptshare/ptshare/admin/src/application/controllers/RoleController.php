<?php
class RoleController extends BaseController
{
    public function addAction()
    {
        $this->display("include/header.html", "admin/role_add.html", "include/footer.html");
    }

    public function addRoleAction()
    {
        $name = $this->getParam("name") ? trim(strip_tags($this->getParam("name"))) : "";

        $arr_error_list = array();

        if($name == "") {
            Util::jumpMsg("名称不能为空!", "/role/add");
        }

        $role = new Role();

        try {
            $roleid = $role->addRole($name);
        } catch(Exception $e) {
            Util::jumpMsg($e->getMessage(), "/role/");
        }

        Util::jumpMsg("添加成功!", "/role/");
    }

    public function indexAction()
    {
        $role = new Role();
        list($total, $role_list) = $role->getRoleList();

        $this->assign("role_list", $role_list);

        $this->display("include/header.html", "admin/role_list.html", "include/footer.html");
    }

    public function deleteAction()
    {
        $roleid = $this->getParam("roleid") ? intval($this->getParam("roleid")) : 0;

        Interceptor::ensureNotEmpty($roleid, ERROR_PARAM_IS_EMPTY, "roleid");

        $role = new Role();
        $role->delRole($roleid);

        Util::jumpMsg("删除成功!", "/role/");
    }
}
?>