<?php
class Admin extends Table
{
	public function __construct()
	{
		$this->setDBConf("MYSQL_CONF_ADMIN");
		$this->setTableName("admin");
		$this->setPrimary("adminid");
	}

	public static function isLogined()
	{
	    return Cookie::get("admin") ? true : false;
	}

    public function addAdmin($username, $password ,$realname, $mobile, $super, $roleids)
    {
        $info = array(
            "username"      => $username,
            "password"  => md5($password),
            "realname"  => $realname,
            "mobile"    => $mobile,
            "super"     => $super,
            "roleid"     => implode(",",$roleids),
            "addtime"   => date("Y-m-d H:i:s"),
            "modtime"   => date("Y-m-d H:i:s")
        );
        return $this->addRecord($info);
    }

    public function exists($username)
    {
        return $this->getCount("username=?", $username) ? true : false;
    }

    public function delete($adminid)
    {
        return $this->setRecord($adminid, array('active'=>'N'));
    }

    public function restore($adminid)
    {
        return $this->setRecord($adminid, array('active'=>'Y'));
    }

    public function getAdminList()
    {
        return $this->getRecords();
    }

    public function getAdminInfo($adminid)
    {
        return $this->getRecord($adminid);
    }

	public function getAdminByName($username)
	{
		$sql = "select * from {$this->table} where username=?";
		return $this->getRow($sql, $username);
	}

    public function getUserName($adminid)
    {
        $row= $this->getRow("select username from {$this->table} where adminid=?", $adminid);
        return $row['name'];
    }

    public function setAdmin($adminid, $super, $roleids, $realname)
    {
        $info = array(
            "super"=>$super,
            "roleid"=>implode(",", $roleids),
            "realname"=>$realname
        );

        return $this->setRecord($adminid, $info);
    }

	public function reset($adminid)
    {
        return $this->setRecord($adminid, array('password' => md5("123456"), "modtime"=>date("Y-m-d H:i:s")));
    }

    public function getActiveAdmin()
    {
        $sql = "SELECT * FROM {$this->getTableName()} WHERE active=?";

        return $this->getAll($sql, 'Y');
    }

    public function change($adminid, $password)
    {
        return $this->setRecord($adminid, array("password"=>md5($password)));
    }

    public static function isAuthed($adminid, $auth_code)
    {
        $admin = new self();
        $admin_info = $admin->getAdminInfo($adminid);

        $roleids = explode(",", $admin_info["roleid"]);

        $role = new Role();
        foreach($roleids as $roleid) {
            $role_info = $role->getRoleInfo($roleid);

            if(in_array($auth_code, explode(",", $role_info["auth"]))) {
                return true;
            }
        }

        return false;
    }
}
?>