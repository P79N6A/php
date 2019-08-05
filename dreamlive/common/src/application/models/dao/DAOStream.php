<?php
class DAOStream extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_DREAM_REPORT");
        $this->setTableName("stream");
    }
    
    public function addPoint($uid, $liveid, $bps, $fps, $plr, $direct, $relayip, $userip, $lng, $lat, $location)
    {
        $info = array(
            "uid"=>$uid,
            "liveid"=>$liveid,
            "bps"=>$bps,
            "fps"=>$fps,
            "plr"=>$plr,
            "direct"=>$direct,
            "relayip"=>$relayip,
            "userip"=>$userip,
            "lng"=>$lng,
            "lat"=>$lat,
            "location"=>$location,
            "addtime"=>date("Y-m-d H:i:s")
        );
        
        //return $this->insert($this->getTableName(), $info);
    }

    public function getLastSomePointByUser($uid,$num=5,$direct='PUSH')
    {
        return $this->getAll("select * from ".$this->getTableName()." where uid=? and direct=? order by addtime desc limit ".$num, array('uid'=>$uid,'direct'=>$direct));
    }
}
?>
