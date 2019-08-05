<?php
class DAOUploadPart extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_UPLOAD");
        $this->setTableName("uploadpart");
    }

    public function addPart($uploadid, $partnumber, $hash, $etag)
    {
        $sql = "INSERT INTO {$this->getTableName()} (uploadid,partnumber,hash,addtime,etag) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE  hash=?, addtime=?, etag=?";
        
        return $this->Execute($sql, array($uploadid, $partnumber, $hash, date("Y-m-d H:i:s"),$etag?$etag:"", $hash, date("Y-m-d H:i:s"),$etag?$etag:""), false);

    }

    public function getAllPartHash($uploadid)
    {
        $sql = "select hash from ".$this->getTableName()." where uploadid=? order by partnumber ";
        return $this->getAll($sql, $uploadid);
    }
}
?>