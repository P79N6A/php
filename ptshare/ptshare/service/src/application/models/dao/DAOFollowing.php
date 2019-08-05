<?php
class DAOFollowing extends DAOProxy
{

    public function __construct($userid = 0)
    {
        $this->setDBConf("MYSQL_CONF_PASSPORT");
        $this->setShardId($userid);
        $this->setTableName("following");
    }

    public function addFollowing($fid, $friend = false)
    {
        $arr_info["uid"] = $this->getShardId();
        $arr_info["fid"] = $fid;
        $arr_info["friend"] = $friend ? "Y" : "N";
        $arr_info["addtime"] = date("Y-m-d H:i:s");
        return $this->insert($this->getTableName(), $arr_info);
    }

    public function getFollowingInfo($fid)
    {
        $sql = "select " . $this->_getFields() . " from " . $this->getTableName() . " where uid=? and fid=?";

        return $this->getRow($sql, array(
            $this->getShardId(),
            $fid
        ));
    }

    public function getFollowings($start = null, $num = null, $noticed_only = false, $forceMaster = false)
    {
        $limit = (is_numeric($start) && is_numeric($num)) ? " order by id desc limit $start , $num " : "";
        $sql = "select " . $this->_getFields() . " from " . $this->getTableName() . " where uid=? " . ($noticed_only ? " and notice='Y'" : "") . $limit;

        return $this->getAll($sql, $this->getShardId());
    }

    public function getFriends($start = null, $num = null)
    {
        $limit = (is_numeric($start) && is_numeric($num)) ? " order by id desc limit $start , $num " : "";
        $sql = "select " . $this->_getFields() . " from " . $this->getTableName() . " where uid=? and friend='Y'" . $limit;

        return $this->getAll($sql, $this->getShardId());
    }

    public function exists($fid)
    {
        $sql = "select count(*) from {$this->getTableName()} where uid=? and fid=?";
        return (int) $this->getOne($sql, array(
            $this->getShardId(),
            $fid
        )) > 0;
    }

    public function isFriend($fids)
    {
        $sql = "select fid from {$this->getTableName()} where uid=? and fid in (" . implode(",", $fids) . ") and friend='Y'";
        $list = $this->getAll($sql, array(
            $this->getShardId()
        ));
        foreach ($list as $v) {
            $friend[$v["fid"]] = true;
        }

        return $friend;
    }

    public function isFollowed($fids)
    {
        $followed = array();

        $sql = "select fid from {$this->getTableName()} where uid=? and fid in (" . implode(",", $fids) . ")";
        $list = $this->getAll($sql, array(
            $this->getShardId()
        ));

        foreach ($list as $v) {
            $followed[$v["fid"]] = true;
        }

        return $followed;
    }

    public function delFollowing($fid)
    {
        $sql = "delete from {$this->getTableName()} where uid=? and fid=?";
        return $this->execute($sql, array(
            $this->getShardId(),
            $fid
        ));
    }

    public function modFollowing($fid, $arr_info)
    {
        return $this->update($this->getTableName(), $arr_info, "uid=? and fid=?", array(
            $this->getShardId(),
            $fid
        ));
    }

    public function countFollowings()
    {
        $sql = "select count(0) from {$this->getTableName()} where uid=?";
        return (int) $this->getOne($sql, array(
            $this->getShardId()
        ));
    }

    private function _getFields()
    {
        return "uid, fid, notice, friend, addtime";
    }
}
