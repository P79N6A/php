<?php
class DAOUserPersist extends DAOProxy
{
    public function __construct()
    {
        $this->setDBConf("MYSQL_CONF_PASSPORT");
        $this->setTableName("user_persist");
    }
    
    public function addUserPersist($uid)
    {
        return $this->insert($this->getTableName(), array("uid"=>$uid, "addtime"=>date("Y-m-d H:i:s")));
    }
    
    public function setUsedPersist($uid)
    {
        return $this->update($this->getTableName(), "uid=?", array("used"=>"Y"), $uid);
    }
    
    public function isUserPersisted($uid)
    {
        $sql = "select count(*) from {$this->getTableName()} where uid=?";
        return $this->getOne($sql, $uid);
    }

    public function getUidByRealUid($realuid)
    {
        $sql = "select uid from {$this->getTableName()} where endtime>? and realuid=?";
        return $this->getOne($sql, array(date("Y-m-d H:i:s"), $realuid));
    }

    public function getRealUidByUid($uid)
    {
        $sql = "select realuid from {$this->getTableName()} where endtime>? and uid=?";
        return $this->getOne($sql, array(date("Y-m-d H:i:s"), $uid));
    }

    public function getEndtimeByRealUid($realuid, $uid)
    {
        $sql = "select uid from {$this->getTableName()} where uid=? and realuid=?";
        return $this->getOne($sql, array($uid, $realuid));
    }

    public function updateBind($uid, $realuid = 0, $endtime = '', $starttime = '')
    {
        $bindinfo['realuid'] = $realuid;

        if(!empty($starttime)) {
            $bindinfo['starttime'] = $starttime;
        }

        if(!empty($endtime)) {
            $bindinfo['endtime'] = $endtime;
        }
        $bindinfo['used'] = $realuid == 0? "N" : "Y";

        return $this->update($this->getTableName(), $bindinfo, "uid=?", $uid);
    }

    public function addUserPersistBulk($uids)
    {
        $total = count($uids);
        $addtime = date("Y-m-d H:i:s");
        $values = "";
        for ($i = 0; $i < $total; $i++) {
            if ($i == $total - 1) { //最后一次
                if ($i%3000 == 0) {
                    $values = "($uids[$i], '$addtime')";
                    $this->query("insert into `user_persist` (`uid`, `addtime`) values $values", array(), false);
                } else {
                    $values .= ",($uids[$i], '$addtime')";
                    $this->query("insert into `user_persist` (`uid`, `addtime`) values $values", array(), false);
                }
            }
            if ($i%3000 == 0) {
                if ($i == 0) {
                    $values = "($uids[$i], '$addtime')";
                } else {
                    $this->query("insert into `user_persist` (`uid`, `addtime`) values $values", array(), false);
                    $values = "($uids[$i], '$addtime')";
                }
            } else {
                $values .= ",($uids[$i], '$addtime')";
            }
        }

        return true;
    }
}
?>
