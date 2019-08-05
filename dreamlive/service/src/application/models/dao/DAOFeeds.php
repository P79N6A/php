<?php
class DAOFeeds extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_LIVE");
        $this->setTableName("feeds");
    }

    public function delFeeds($uid, $relateid, $type)
    {
        $sql = "delete from {$this->getTableName()} where uid=? and relateid=? and type=?";
        return $this->Execute($sql, array($uid, $relateid, $type)) ? true : false;
    }

    public function addFeeds($author, $relateid, $type)
    {
        $info = array(
        "uid"        => $author,
        "relateid"     => $relateid,
        "type"        => $type,
        "addtime"    => date("Y-m-d H:i:s"),
        );
        $this->replace($this->getTableName(), $info);
    }

    public function getFeedInfo($relateid)
    {    
        //$sql = "select uid, relateid, type, watches, replays, praises, replies, reposts, addtime from {$this->getTableName()} where relateid=?";
        $sql = "select uid, relateid, type, addtime from {$this->getTableName()} where relateid=?";
        return $this->getRow($sql, $relateid);
    }
    
    public function exists($relateid)
    {
        $sql = "select count(*) from {$this->getTableName()} where relateid=?";
        return $this->getOne($sql, $relateid) ? true : false;
    }
    
    public function modLiveFeedType($liveid, $type) 
    {
        $sql = "update {$this->getTableName()} set type=$type where relateid=?";
        return $this->execute($sql, $liveid);
    }

    public function getMultiFeedsInfo($relateids)
    {
        $placeholder = implode(',', array_fill(0, count($relateids), '?'));
        $sql = "select uid, relateid, type, addtime from {$this->getTableName()} where relateid in ({$placeholder})";
        return $this->getAll($sql, $relateids);
    }

    public function getUserFeedNum($uid)
    {
        $sql = "select count(*) from {$this->getTableName()} where uid=?";
        return $this->getOne($sql, $uid);
    }

    public function getMultiUserFeeds($uids, $offset, $num = 10)
    {
        $params =  $uids;
        $fields = implode(',', array_fill(0, count($uids), '?'));
        $sql = "select * from feeds force index (idx_uid_type) where uid in ({$fields}) and type in (2, 3, 4)";

        if($offset) {
            $sql .= " and feedid<?";
            $params[] = $offset;
        }
        $sql .= " order by feedid desc limit $num";

        return $this->getAll($sql, $params);
    }
    
    public function getActivingLiveByUserId(array $uids)
    {
        $params =  $uids;
        $fields = implode(',', array_fill(0, count($uids), '?'));
        $sql = "select uid,relateid from {$this->getTableName()} where  type=? and uid in ({$fields}) ";
        array_unshift($params, Feeds::FEEDS_LIVE);
         
        return $this->getAll($sql, $params);
    }
    
    public function hasActivingLive($uid)
    {
        $sql = "select count(*) from {$this->getTableName()} where  type=? and uid =? ";
        return intval($this->getOne($sql, array(Feeds::FEEDS_LIVE, $uid))) > 0;
    }
}
?>
