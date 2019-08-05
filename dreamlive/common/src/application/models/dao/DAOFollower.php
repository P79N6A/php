<?php
class DAOFollower extends DAOProxy
{
    public function __construct($shardid = 0)
    {
        /* {{{ */
        $this->setDBConf("MYSQL_CONF_PASSPORT");
        $this->setShardId($shardid);
        $this->setTableName("follower");
    }
    /* }}} */
    public function addFollower($fid)
    {
        /* {{{ */
        $arr_info["uid"] = $this->getShardId();
        $arr_info["fid"] = $fid;
        $arr_info["addtime"] = date("Y-m-d H:i:s");
        return $this->insert($this->getTableName(), $arr_info);
    }
    /* }}} */
    public function getFollowers($offset = null, $num = null, $noticed_only = false)
    {
        /* {{{ */
        $limit = is_numeric($num) ? " order by id desc limit $num " : "";
        $sql = "select a.id, a.uid, a.fid, a.notice, a.addtime from " . $this->getTableName() . " as a inner join(select id from " . $this->getTableName() . " where uid=? " . ($noticed_only ? " and notice='Y'" : "") . ($offset ? " and id < $offset" : "") . $limit . ") as b on a.id=b.id";
        
        Logger::log("live_start_distribute", "sql", array("liveid"=>$sql,"offset"=> $offset,"num"=> $num, "line"=>__LINE__));
        
        
        return $this->getAll($sql, $this->getShardId());
    }
    /* }}} */
    public function exists($fid)
    {
        /* {{{ */
        $sql = "select count(0) from {$this->getTableName()} where uid=? and fid=?";
        return (int) $this->getOne(
            $sql, array(
            $this->getShardId(),
            $fid
            )
        ) > 0;
    }
    /* }}} */
    public function isFollower($fids)
    {
        /* {{{ */
        $follower = array();

        $sql = "select fid from {$this->getTableName()} where uid=? and fid in (" . implode(",", $fids) . ")";
        $list = $this->getAll(
            $sql, array(
            $this->getShardId()
            )
        );
        foreach ($list as $v) {
            $follower[$v["fid"]] = true;
        }

        return $follower;
    }
    /* }}} */
    public function delFollower($fid)
    {
        /* {{{ */
        $sql = "delete from {$this->getTableName()} where uid=? and fid=?";
        return $this->execute(
            $sql, array(
            $this->getShardId(),
            $fid
            )
        );
    }
    /* }}} */
    public function modFollower($fid, $arr_info)
    {
        /* {{{ */
        return $this->update(
            $this->getTableName(), $arr_info, "uid=? and fid=?", array(
            $this->getShardId(),
            $fid
            )
        );
    }
    /* }}} */
    public function countFollowers()
    {
        /* {{{ */
        $sql = "select count(0) from {$this->getTableName()} where uid=? and notice =? ";
        return (int) $this->getOne(
            $sql, array(
            $this->getShardId(), 'Y'
            )
        );
    }
    /* }}} */
    private function _getFields()
    {
        /* {{{ */
        return "id, uid, fid, notice, addtime";
    } /* }}} */
    
    public function getFollowersList($offset,$num)
    {
        $sql = "SELECT * FROM " . $this->getTableName()." AS t1
                JOIN (SELECT id as id1 FROM " . $this->getTableName()." WHERE uid=".$this->getShardId()." ORDER BY id DESC LIMIT ".$offset.",1) AS t2
                WHERE t1.id <= t2.id1 and t1.uid=".$this->getShardId()."   ORDER BY t1.id desc LIMIT ".$num; 
        return $this->getAll($sql, $this->getShardId());
    }
    
    
    public function getUserFollowers($offset, $num)
    {
        $sql = "SELECT * FROM ". $this->getTableName()." AS t1 WHERE uid =? LIMIT {$offset}, {$num}";
        
        return $this->getAll($sql, array($this->getShardId()));
    }
    
}
