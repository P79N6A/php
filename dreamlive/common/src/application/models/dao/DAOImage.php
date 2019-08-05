<?php
class DAOImage extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_LIVE");
        $this->setTableName("image");
    }

    public function add($imageid, $uid, $content, $url, $width, $height, $point, $province, $city, $district, $location, $extends,$iscapture)
    {
        $info = array(
            "imageid" => $imageid,
            "uid" => $uid,
            "content" => $content,
            "url" => $url,
            "width" => $width,
            "height" => $height,
            "point" => $point,
            "province" => $province,
            "city" => $city,
            "district" => $district,
            "location" => $location,
            "extends" => $extends,
            "addtime" => date("Y-m-d H:i:s"),
            "iscapture"=>$iscapture
        );
        
        return $this->insert($this->getTableName(), $info);
    }

    public function delImage($imageid)
    {
        $sql = "update {$this->getTableName()} set deleted='Y' where imageid=?";
        return $this->Execute($sql, $imageid);
    }

    public function getImageInfo($imageid)
    {
        $sql = "select * from {$this->getTableName()} where imageid=? and deleted='N'";
        return $this->getRow($sql, $imageid);
    }
}
?>