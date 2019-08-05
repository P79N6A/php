<?php
class Auth extends Table
{
	public function __construct()
	{
	    $this->setDBConf("MYSQL_CONF_ADMIN");
	    $this->setTableName("auth");
	    $this->setPrimary("authid");
	}

    public function addAuth($pid, $type, $code, $name)
    {
        $info = array(
            "pid"=>$pid,
            "type"=>$type,
            "code"=>$code,
            "name"=>$name,
            "addtime"=>date("Y-m-d H:i:s"),
            "modtime"=>date("Y-m-d H:i:s")
        );

        return $this->addRecord($info);
    }

    public function delAuth($authid)
    {
        return $this->delRecord($authid);
    }

    public function getAuthList()
    {
        $sql = "select * from {$this->getTableName()}";

        return $this->getAll($sql);
    }

    public function getModuleList()
    {
        $sql = "select * from {$this->getTableName()}";
        $sql .=  " where type=?";
        
        return $this->getAll($sql, 1);
    }
    
    public function getAuthListByModuleId($moduleid)
    {
        $sql = "select * from {$this->getTableName()} where pid=?";
        
        return $this->getAll($sql, $moduleid);
    }

    public function getAuthInfo($authid)
    {
        return $this->getRecord($authid);
    }

    public function setAuth($authid, $name)
    {
    	return $this->setRecord($authid, array("name"=>$name));
    }
}
?>