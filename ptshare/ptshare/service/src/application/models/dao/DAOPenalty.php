<?php
class DAOPenalty extends DAOProxy
{
    /**
     * 构造方法
     */
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_MALL");
        $this->setTableName("penalty");
    }

    public function add($uid, $type, $relateid, $remark, $grape)
    {
    	$penalty_info = array(
    			"uid"		=> $uid,
    			"type"	    => $type,
    			"relateid"	=> $relateid,
    			"remark"	=> $remark,
    			"grape"		=> $grape,
    			"addtime"	=> date("Y-m-d H:i:s")
    	);
    	return $this->insert($this->getTableName(), $penalty_info);
    }
}