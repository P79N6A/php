<?php
class DAONewsFeeds extends DAOProxy
{
    public function __construct($receiver)
    {
        $this->setDBConf("MYSQL_CONF_LIVE");
        $this->setShardId($receiver);
        $this->setTableName("newsfeeds");
    }

    public function getNewsFeeds($uid, $relateid, $num)
    {
        $sql = "select feedid, receiver, relateid, creator, author, score, type, repost, creatime from {$this->getTableName()} where receiver=?";
        $sql .= " and relateid < ? limit $num";

        return $this->getAll($sql, array($uid, $relateid));
    }

    public function addNewsFeeds($receiver, $type, $relateid, $author)
    {
        $info = array(
            "receiver"  => $receiver,
            "relateid"  => $relateid,
            "author"    => $author,
            "type"      => $type,
            "addtime"   => date("Y-m-d H:i:s")
        );
        return $this->replace($this->getTableName(), $info);
    }

    //拉数据
    public function receive($followings, $uid, $relateid)
    {
        $sql = "insert into {$this->getTableName()} (receiver, relateid, author, type, creatime, addtime) select $uid, relateid, uid, type, addtime, addtime from feeds where relateid > ? and uid in (" . implode(",", $followings) . ") ";

        return $this->Execute($sql, $relateid);
    }
    
    public function cancel($author)
    {
        $sql = "delete from {$this->getTableName()} where receiver=? and author=?";
        return $this->Execute(
            $sql, array(
            $this->getShardId(),
            $author
            )
        );
    }
    public function delNewsFeeds($relateid)
    {
        $sql = "delete from {$this->getTableName()} where receiver=? and relateid=?";
        return $this->Execute(
            $sql, array(
            $this->getShardId(),
            $relateid
            )
        );
    }

    public function clean($uid, $feedids)
    {
        $sql = "delete from {$this->getTableName()} where receiver=? and feedid in (" . implode(",", $feedids) . ") ";
        return $this->Execute($sql, $uid);
    }
    
    public function getMaxRelateid($uid)
    {
        return $this->getOne("select MAX(relateid) as relateid from {$this->getTableName()} where receiver = ? limit 1", $uid);
    }

    /**
     * 
     * 获取最大的feedid
     *
     * @param  int $receiver            
     * @return int
     */
    public function getMaxNewsFeedid($receiver)
    {
        $sql = "select MAX(feedid) as feedid from " . $this->getTableName() . " where receiver=?";
        return $this->getOne($sql, array('receiver' => $receiver));
    }

    /**
     * 
     * 获取newsFeeds列表
     *
     * @param  int $receiver            
     * @param  int $feedid            
     * @param  int $num            
     * @return array
     */
    public function getNewsFeedsList($receiver, $offset, $num, $type = false)
    {   
        if($type) {
            $where = " and type = 1 ";
        }else{
            $where = " and type in (2,4) ";
        }
        $sql = "select feedid, receiver, relateid, creator, author, score, type, repost, creatime, addtime
                from " . $this->getTableName() . " 
                where receiver=? and addtime < ? ";
        $sql .= $where;
        $sql .= " order by addtime DESC  limit " . $num ;
        return $this->getAll($sql, array('receiver' => $receiver,'addtime' => empty($offset) ? date("Y-m-d H:i:s", time()+86400) : date("Y-m-d H:i:s", $offset)));
    }

    /**
     * 
     * 批量插入数据
     *
     * @param string $str            
     */
    public function addNewsFeedsBatch($str)
    {
        $sql = "replace into " . $this->getTableName() . " (receiver, relateid, author, type, creatime, addtime) values " . $str;
        return $this->Execute($sql);
    }
    

    /**
     * 
     * 分类获取MaxRelateid
     *
     * @param int $uid            
     * @param int $type            
     */
    public function getMaxRelateidByType($receiver, $author, $type)
    {
        $sql = "select MAX(relateid) as relateid from " . $this->getTableName() . " where receiver = ? and author=? and type=? limit 1";
        return $this->getOne($sql, array('receiver' => $receiver,'author'=>$author,'type' => $type));
    }
    
    public function getMaxRelateidByRelateidAuthor($receiver,$author)
    {
        $sql = "select MAX(relateid) as relateid from {$this->getTableName()} where receiver = ? and author=? limit 1";
        return $this->getOne($sql, array('receiver' => $receiver,'author'=>$author));
    }

    /**
     * 
     * 删除NewsFeeds
     *
     * @param int $relateid            
     * @param int $type            
     */
    public function delNewsFeedsByRelateidType($i,$relateid, $type)
    {
        $sql = "delete from newsfeeds_" .$i. " where relateid=? and type=?";
        return  $this->Execute($sql, array('relateid' => $relateid,'type' => $type));
    }
    
    
}
?>