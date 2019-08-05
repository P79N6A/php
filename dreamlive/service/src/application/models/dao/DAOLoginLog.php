<?php
class DAOLoginLog extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PASSPORT");
        $this->setTableName("loginlog");
    }

    public function addLoginLog($uid, $ip, $deviceid, $platform, $version, $brand, $model, $network, $netspeed, $extend)
    {
        $log_info = array(
            "uid"=>$uid,
            "ip"=>$ip,
            "deviceid"=>$deviceid,
            "platform"=>$platform,
            "version"=>$version,
            "brand"=>$brand,
            "model"=>$model,
            "network"=>$network,
            "netspeed"=>$netspeed,
            "addtime"=>date("Y-m-d H:i:s"),
            "extend" => $extend
        );

        return $this->insert($this->getTableName() . "_" . date("Ym"), $log_info);
    }
}
?>
