<?php
class DAOUserForms extends DAOProxy
{
	public function __construct()
	{
		$this->setDBConf("MYSQL_CONF_PASSPORT");
		$this->setTableName("user_forms");
	}
	
	public function addForm($userid, $formid)
	{
		$now = time();
		$seven = $now + 3600*24*7;
		$arr_info = [];
		$arr_info["uid"] = $userid;
		$arr_info["formid"] = $formid;
		$arr_info["endtime"] = date("Y-m-d H:i:s", $seven);
		$arr_info["addtime"] = date("Y-m-d H:i:s", $now);
		
		return $this->insert($this->getTableName(), $arr_info);
	}
	
}