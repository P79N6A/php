<?php

class DAOVideo extends DAOProxy
{

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_LIVE");
        $this->setTableName("video");
    }

    public function add($videoid, $uid, $mp4, $duration, $content, $cover, $width, $height, $point, $province, $city, $district, $location, $extends,$iscapture)
    {
        $info = array(
            "videoid" => $videoid,
            "uid" => $uid,
            "mp4" => $mp4,
            "duration" => $duration,
            "cover" => $cover,
            "content" => $content,
            "width" => $width,
            "height" => $height,
            "point" => $point,
            "province" => $province,
            "city" => $city,
            "district" => $district,
            "location" => $location,
            "extends" => $extends,
            "addtime" => date("Y-m-d H:i:s"),
            "iscapture" => $iscapture,
        );

        return $this->insert($this->getTableName(), $info);
    }

    public function getVideoInfo($videoid)
    {
        $sql = "select * from {$this->getTableName()} where videoid=? and deleted='N'";
        return $this->getRow($sql, $videoid);
    }
    
    public function getAllVideo()
    {
        $sql = "select videoid from {$this->getTableName()} where deleted = ? ";
        return $this->getAll($sql, array('N'));
    }
    
    public function delVideo($videoid)
    {
        $sql = "update {$this->getTableName()} set deleted='Y' where videoid=?";
        return $this->Execute($sql, $videoid);
    }

    public function setCover($videoid, $cover)
    {
        $sql = "update {$this->getTableName()} set cover=? where videoid=?";
        return $this->Execute($sql, array($cover, $videoid));
    }
}
