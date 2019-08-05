<?php
class Role extends Table
{
	public function __construct()
	{
	    $this->setDBConf("MYSQL_CONF_ADMIN");
	    $this->setTableName("role");
	    $this->setPrimary("roleid");
	}

	public function addRole($name)
	{
		$info = array(
        "name"=>$name,
        "addtime"=>date("Y-m-d H:i:s"),
		"modtime"=>date("Y-m-d H:i:s")
		);

        return $this->addRecord($info);
    }

	public function delRole($roleid)
	{
        $this->delRecord($roleid);
    }

    public function setAuth($roleid, $codes)
    {
        $this->setRecord($roleid, array('auth'=>$codes));
    }

    public function getAuth($roleid)
    {
    	$sql = "select auth from {$this->getTableName()} where roleid=?";

    	return explode(",", $this->getOne($sql, $roleid));
    }

    public function getRoleList()
    {
		return $this->getRecords();
	}

    public function getRoleInfo($roleid)
    {
        return $this->getRecord($roleid);
    }

    public function modifyRole($roleid,$name,$codes)
    {
    	return $this->setRecord($roleid,array("name"=>$name,"auth"=>$codes));
    }
}
?>
