<?php
class DAOLive extends DAOProxy
{
    static $_fields = "liveid, uid, title, sn, partner, `point`, province, city, district, location, width, height, `status`, subtitle, extends, addtime, endtime, modtime, replayurl, replay, `virtual`,region,cover,stime,etime,record, pullurl,privacy,linktype,streamtype";

    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_LIVE");
        $this->setTableName("live");
    }

    private function _getFields()
    {
        return self::$_fields;
    }

    public function getUserActiveLives($uid)
    {
        $sql = "select * from {$this->getTableName()} where uid=? and status in (".Live::ACTIVING.",".Live::PAUSED.") and deleted='N'";

        return $this->getAll($sql, array($uid));
    }
    
    public function getUserLives($uid)
    {
        $sql = "select liveid,addtime,endtime from {$this->getTableName()} where uid=?  and deleted='N'";
    
        return $this->getAll($sql, array($uid));
    }
    
    public function getUserLivesCount($uid)
    {
        $sql = "select count(*) from {$this->getTableName()} where uid=?  and deleted='N'";
        
        return $this->getOne($sql, array($uid));
    }
    
    public function getLiveInfo($liveid)
    {
        $sql = "select {$this->_getFields()} from {$this->getTableName()} where liveid=? and deleted='N'";
        return $this->getRow($sql, $liveid);
    }

    public function getLiveInfoBySn($sn, $partner)
    {
        $sql = "select {$this->_getFields()} from {$this->getTableName()} where sn='{$sn}' and partner=? and deleted='N' order by liveid desc limit 1   ";
        return $this->getRow($sql, $partner);
    }

    public function addLive($liveid, $uid, $sn, $partner, $title, $width, $height, $point, $province, $city, $district, $location, $virtual, $extends = array(),$replay,$region, $cover,$record, $pullurl)
    {
        $province     = str_replace('市', '', $province);
        $city         = str_replace('市', '', $city);
        $district     = str_replace('市', '', $district);
        
        if (strpos($cover, 'http://') !== false) {
            $cover = str_replace(STATIC_DOMAIN_NAME, '', $cover);
            $cover = str_replace("http://static.dreamlive.tv", '', $cover);
            $cover = str_replace("http://static.dreamlive.com", '', $cover);
        }
        
        $live_info = array(
        "liveid"    => $liveid,
        "uid"        => $uid,
            "sn"        => $sn,
            "partner"   => $partner,
        "title"        => $title,
        "width"        => $width,
            "height"    => $height,
        "point"        => $point,
        "province"    => $province,
        "city"        => $city,
        "district"    => $district,
        "location"    => $location,
            "virtual"   => $virtual,
        "status"    => Live::ACTIVING,
        "extends"    => json_encode($extends),
        "addtime"    => date("Y-m-d H:i:s"),
        "modtime"    => date("Y-m-d H:i:s"),
            "replay"    => $replay,
            "region"    => $region,
            "cover"     => $cover,
            "record"    => $record,
        "pullurl"    => $pullurl
        );

        return $this->insert($this->getTableName(), $live_info);
    }

    public function delLive($liveid)
    {
        $sql = "update {$this->getTableName()} set deleted='Y',status=".Live::DELETED." where liveid=?";
        $this->live_log($liveid, 5);
        return $this->Execute($sql, $liveid);
    }

    public function getActiveLives()
    {
        $sql = "select liveid, uid from {$this->getTableName()} where status=? and addtime > ? and deleted='N'";
        return $this->getAll($sql, array(Live::ACTIVING, date("Y-m-d H:i:s", strtotime("-2 days"))));
    }

    public function getActiveAndPausedLives()
    {
        $sql = "select liveid, uid from {$this->getTableName()} where (status=? or status =?)  and deleted='N' and addtime < ?";
        return $this->getAll($sql, array(Live::ACTIVING, Live::PAUSED, date('Y-m-d H:i:s', (time()-60))));
    }

    /* newsfeed合并用 */
    public function getFollowersLives($followers)
    {
        $sql = "select {$this->_getFields()} from {$this->getTableName()} where uid in (" . implode(",", $followers) . ") and status=?";
        return $this->getAll($sql, Live::ACTIVING);
    }

    public function setLiveStatus($liveid, $status)
    {
        $info = array();
        $info["status"] = $status;

        if(in_array($status, array(Live::DELETED, Live::CLEANED, Live::FORBIDED, Live::REPEATED))) {
            $info["deleted"] = "Y";
        }

        if (!in_array($status, array(Live::ACTIVING, Live::PAUSED))) {
            $info["endtime"] = date("Y-m-d H:i:s");
        }
        $info["modtime"] = date("Y-m-d H:i:s");
        $this->live_log($liveid, $status);
        return $this->update($this->getTableName(), $info, "liveid=?", $liveid);
    }

    public function setLiveDeleteStatus($liveid, $status)
    {
        $info = array();
        $info["status"] = $status;
        
        if(in_array($status, array(Live::DELETED, Live::CLEANED, Live::FORBIDED, Live::REPEATED))) {
            $info["deleted"] = "Y";
        }
        
        $info["modtime"] = date("Y-m-d H:i:s");
        $this->live_log($liveid, $status);
        return $this->update($this->getTableName(), $info, "liveid=?", $liveid);
    }
    
    public function live_log($liveid, $status)
    {
        $sql = "insert into live_log (liveid,status,addtime,extends) values (?,?,?,?) ";
        if (empty($_REQUEST)) {
            $_REQUEST = debug_backtrace();
        }
        return $this->Execute($sql, array($liveid, $status, date("Y-m-d H:i:s"), json_encode($_REQUEST))) ? true : false;
    }

    public function setStream($liveid, $sn, $partner)
    {
        $info = array(
            "sn"=>$sn,
            "partner"=>$partner,
            "modtime"=>date("Y-m-d H:i:s")
        );

        return $this->update($this->getTableName(), $info, "liveid=?", $liveid);
    }

    public function setReplay($liveid, $replayurl,$partner,$stime,$etime)
    {
        $info = array(
        "replayurl"    => $replayurl,
        "modtime"    => date("Y-m-d H:i:s"),
                "stime"     => date("Y-m-d H:i:s", strtotime($stime)),
                "etime"     => date("Y-m-d H:i:s", strtotime($etime)),
        );
        return $this->update($this->getTableName(), $info, "liveid=? and partner=? and replay=? ", array('liveid'=>$liveid,'partner'=>$partner,'replay'=>'Y'));
    }

    /**
     * 用户是否在直播状态
     *
     * @param int $uid
     */
    public function isLive($uid)
    {
        $sql = "SELECT count(0) as cnt FROM ".$this->getTableName()." where uid=? and status=? ";
        $result = $this->getRow($sql, array('uid'=>$uid,'status'=>Live::ACTIVING));
        if(isset($result['cnt']) && $result['cnt']>0) {
            return true;
        }
        return false;
    }

    /**
     * 用户是否在直播、暂停状态
     *
     * @param unknown $uid
     */
    public function isLiveStatus($uid)
    {
        $sql = "SELECT count(0) as cnt FROM ".$this->getTableName()." where uid=? and status in (".Live::ACTIVING.",".Live::PAUSED.") ";
        $result = $this->getRow($sql, array('uid'=>$uid));
        if(isset($result['cnt']) && $result['cnt']>0) {
            return true;
        }
        return false;
    }

    /**
     * 直播间是否在直播、暂停状态
     *
     * @param unknown $livid
     */
    public function isLiveRunning($liveid)
    {
        $sql = "SELECT liveid FROM ".$this->getTableName()." where liveid=? and status in (".Live::ACTIVING.",".Live::PAUSED.") ";
        $result = $this->getRow($sql, array('liveid'=>$liveid));
        if(isset($result) && $result['liveid']>0) {
            return true;
        }
        return false;
    }
    /**
     * 通过liveid获取live
     */
    public function getLiveById($liveid)
    {
        return $this->getRow("select uid from ".$this->getTableName()." where liveid=?", ['liveid'=>$liveid]);
    }

    /**
     * 是否有数据
     *
     * @param unknown $uid
     */
    public function isExist($uid)
    {
        $sql = "SELECT count(0) as cnt FROM " . $this->getTableName() . " where uid=? ";
        $result = $this->getRow($sql, array('uid' => $uid));
        if (isset($result['cnt']) && $result['cnt'] > 0) {
            return true;
        }
        return false;
    }


    /**
     * 通过uid批量获取
     *
     * @param array $uids
     */
    public function getLiveInfoByUids($uids)
    {
        $sql = "select {$this->_getFields()} from {$this->getTableName()} where uid in (" . implode(",", $uids) . ") and status=?";
        return $this->getAll($sql, Live::ACTIVING);
    }

    public function setCover($liveid, $cover)
    {
        if (strpos($cover, 'http://') !== false) {
            $cover = str_replace(STATIC_DOMAIN_NAME, '', $cover);
            $cover = str_replace("http://static.dreamlive.tv", '', $cover);
            $cover = str_replace("http://static.dreamlive.com", '', $cover);
        }
        
        $sql = "update {$this->getTableName()} set cover=? where liveid=?";
        return $this->Execute($sql, array($cover, $liveid));
    }
    
    
    public function setLiveInfo($liveid, $cover, $title)
    {
        if (strpos($cover, 'http://') !== false) {
            $cover = str_replace(STATIC_DOMAIN_NAME, '', $cover);
            $cover = str_replace("http://static.dreamlive.tv", '', $cover);
            $cover = str_replace("http://static.dreamlive.com", '', $cover);
        }
        
        $sql = "update {$this->getTableName()} set cover=?, title = ? where liveid=?";
        return $this->Execute($sql, array($cover, $title, $liveid));
    }
    
    /**
     * 设置私密直播
     *
     * @param int    $liveid
     * @param string $privacy
     */
    public function setPrivacy($liveid, $privacy)
    {
        $sql = "update {$this->getTableName()} set privacy='".$privacy."' where liveid=?";
        return $this->Execute($sql, array('liveid'=>$liveid));
    }
    
    /**
     * 设置sn
     *
     * @param int    $liveid
     * @param string $sn
     * @param string $partner
     */
    public function setPartnerSn($liveid, $sn, $partner)
    {
        $sql = "update {$this->getTableName()} set sn='".$sn."',partner='".$partner."'  where liveid=?";
        return $this->Execute($sql, array('liveid'=>$liveid));
    }
    
    /**
     * 获取私密直播列表
     *
     * @param int  $uid
     * @param date $startime
     * @param date $endtime
     */
    public function getListByPrivacy($uid,$startime,$endtime)
    {
        $sql = " select liveid, privacy from {$this->getTableName()} where 
                 uid=?  and (
                     (addtime<='".$startime."' and endtime>='".$startime."') or
                     (addtime<='".$startime."' and endtime='0000-00-00 00:00:00') or
                     (addtime>='".$startime."' and endtime<'".$endtime."') or
                     (addtime>='".$startime."' and endtime>='".$endtime."') or
                     (addtime>='".$startime."' and endtime>='0000-00-00 00:00:00') or
                     (addtime<='".$startime."' and endtime>='".$endtime."') or
                     (addtime<='".$startime."' and endtime='0000-00-00 00:00:00')
                 ) and privacy!='' and deleted='N' ";
        return $this->getAll($sql, array('uid'=>$uid));
    }
    
    /**
     * 用户是否在直播、暂停状态
     *
     * @param unknown $uid
     */
    public function isLiveAuthorityStatus($sn,$partner)
    {
        $sql = "SELECT count(0) as cnt FROM ".$this->getTableName()." where sn=? and partner=? and status in (".Live::ACTIVING.",".Live::PAUSED.") ";
        $result = $this->getRow($sql, array('sn'=>$sn,'partner'=>$partner));
        if(isset($result['cnt']) && $result['cnt']>0) {
            return true;
        }
        return false;
    }

    /**
     * 查询直播列表
     *
     * @param int  $uid
     * @param date $startime
     * @param date $endtime
     * @param int  $num
     * @param int  $offset
     */
    public function getUserLiveList($uid, $startime, $endtime, $num, $offset)
    {
        $where = "";
        if ($startime) {
            $where .= " and addtime>='" . $startime . "' ";
        }
        if ($endtime) {
            $where .= " and addtime<='" . $endtime . "' ";
        }
        if ($offset > 0) {
            $where .= " and liveid<" . $offset . " ";
        }
        $sql = "SELECT liveid, uid, addtime, endtime FROM " . $this->getTableName() . " WHERE uid=? and deleted='N' ";
        $sql .= $where;
        $sql .= "ORDER BY liveid desc LIMIT " . $num;
        return $this->getAll($sql, array('uid' => $uid));
    }
    
    /**
     * 获取直播次数
     *
     * @param int  $uid
     * @param date $startime
     * @param date $endtime
     */
    public function getUserLiveTotal($uid, $startime, $endtime)
    {
        $where = "";
        if ($startime) {
            $where .= " and addtime>='" . $startime . "' ";
        }
        if ($endtime) {
            $where .= " and addtime<'" . $endtime . "' ";
        }
        $sql = "SELECT count(*) as cnt FROM " . $this->getTableName() . " WHERE uid=? and deleted='N' ";
        $sql .= $where;
        return $this->getOne($sql, array('uid' => $uid));
    }
    
    /**
     * 获取直播总时长
     *
     * @param int $uid
     */
    public function getUserLiveLong($uid, $startime, $endtime)
    {
        $where = "";
        if ($startime) {
            $where .= " and addtime>='" . $startime . "' ";
        }
        if ($endtime) {
            $where .= " and addtime<'" . $endtime . "' ";
        }
        $sql = " SELECT sum(UNIX_TIMESTAMP(endtime)-UNIX_TIMESTAMP(addtime)) FROM live WHERE uid=? and status!=1 and deleted='N' ";
        $sql .= $where;
        return $this->getOne($sql, array($uid));
    }

    public function getEarliestLive($uid)
    {
        $sql = "SELECT * FROM {$this->getTableName()} WHERE uid=? ORDER BY liveid LIMIT 1";

        return $this->getRow($sql, $uid);
    }

    /**
     * 
     * 修改连麦类型
     *
     * @param int $liveid            
     * @param int $linktype            
     */
    public function setUserLiveLink($liveid, $linktype, $streamtype)
    {
        $condition = ' liveid=? ';
        $params = array(
            'liveid' => $liveid
        );
        $option = array(
            'linktype'   => $linktype,
            'streamtype' => $streamtype
        );
        return $this->update($this->getTableName(), $option, $condition, $params);
    }
}
?>
