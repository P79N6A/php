<?php

class DAOErrorLog extends DAOProxy
{

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PASSPORT");
        $this->setTableName("errorlog");
    }

    public function addLog($uid = null, $mobile = null, $errno = "", $extend = array())
    {
        $info["uid"] = intval($uid);
        $info["mobile"] = strval($mobile);
        $info["ip"] = strval(Util::getIP());
        $info["deviceid"] = strval(Context::get("deviceid"));
        $info["platform"] = strval(Context::get("platform"));
        $info["version"] = strval(Context::get("version"));
        $info["netspeed"] = strval($_REQUEST["netspeed"]);
        $info["network"] = strval($_REQUEST["network"]);
        $info["model"] = strval($_REQUEST["model"]);
        $info["channel"] = strval($_REQUEST["channel"]);
        $info["errno"] = strval($errno);
        $info["extend"] = $extend ? json_encode($extend) : "";
        $info["addtime"] = date("Y-m-d H:i:s");
        
        return $this->insert($this->getTable(), $info);
    }

    public function getTable()
    {
        return $this->getTableName() . "_" . date("Ym");
    }
}
