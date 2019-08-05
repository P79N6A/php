<?php

class DAOUserFeeds extends DAOProxy
{

    public function __construct($uid)
    {
        $this->setDBConf("MYSQL_CONF_LIVE");
        $this->setShardId($uid);
        $this->setTableName("userfeeds");
    }

    public function delUserFeeds($relateid)
    {
        $sql = "delete from {$this->getTableName()} where relateid=?";
        return $this->Execute($sql, $relateid) ? true : false;
    }

    public function addUserFeeds($uid, $type, $relateid,$addtime)
    {
        $info = array(
            "uid"       => $uid,
            "relateid"  => $relateid,
            "type"      => $type,
            "addtime"   => $addtime
        );
        
        $this->replace($this->getTableName(), $info);
    }

    public function getUserFeeds($uid, $num, $feedid)
    {
        $sql = "select feedid, uid, relateid, type, addtime from {$this->getTableName()} where uid=? and feedid<? order by addtime desc limit {$num}";
        
        return $this->getAll($sql, array($uid, $feedid));
    }
    
    public function clean($uid, $feedids)
    {
        $sql = "delete from {$this->getTableName()} where uid=? and feedid in (" . implode(",", $feedids) . ") ";
        return $this->Execute($sql, $uid);
    }
    
    public function getUserFeedsTotal($uid)
    {
        $sql = "select count(*) from {$this->getTableName()} where uid=?";
        return $this->getOne($sql, $uid);
    }

    public function modLiveFeedType($liveid, $type, $score)
    {
        $sql = "update {$this->getTableName()} set type=$type, score=$score where relateid=?";
        return $this->execute($sql, $liveid);
    }

    /**
     * 
     * 删除UserFeeds
     *
     * @param int $relateid            
     * @param int $type            
     */
    public function delUserFeedsByRelateidType($relateid, $type)
    {
        $sql = "delete from {$this->getTableName()} where relateid=? and type=?";
        return $this->Execute($sql, array('relateid' => $relateid,'type' => $type));
    }
    
    /**
     * 插入数据
     *
     * @param string $str
     */
    public function replaceUserFeeds($uid,$relateid,$type,$addtime)
    {
        $info = array(
            "uid"       => $uid,
            "relateid"  => $relateid,
            "type"      => $type,
            "addtime"   => $addtime
        );
        $this->replace($this->getTableName(), $info);
    }
    
    /**
     * 获取最大的relateid
     *
     * @param int $uid
     */
    public function getMaxRelateid()
    {
        $sql = "select MAX(relateid) as relateid from {$this->getTableName()} where uid = ? limit 1";
        return $this->getOne($sql, self::getShardId());
    }
    
    /**
     * 获取所有数据
     *
     * @param int $uid
     * @param int $relateid
     */
    public function getUserFeedsList($uid,$relateid)
    {
        $sql = "select uid, relateid, type,addtime from " . $this->getTableName() . " where uid =? and  relateid>? ";
        return $this->getAll($sql, array('uid'=>$uid,'relateid' => $relateid));
    }
    
}