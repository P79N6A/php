<?php
class DAOUploadTask extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_UPLOAD");
        $this->setTableName("uploadtask");
    }

    public function addTask($uid, $uploadid, $filename, $hash, $filesize, $netspeed, $network)
    {
        $list = array(
            "uid"=>$uid,
            "uploadid"=>$uploadid,
            "filename"=>$filename,
            "hash"=>$hash,
            "filesize"=>$filesize,
            "netspeed"=>$netspeed,
            "network"=>$network,
            "addtime"=>date("Y-m-d H:i:s"),
        );
        
        return $this->insert($this->getTableName(), $list);
    }
    public function completeTask($uploadid)
    {
        $list = array(
            "completetime" => date("Y-m-d H:i:s"),
        );

        return $this->update($this->getTableName(), $list, "uploadid=?", $uploadid);
    }
}
?>