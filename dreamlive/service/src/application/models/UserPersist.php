<?php
class UserPersist
{
    const QUEUE_USERID_MASTER = "queue:userid:master"; //正常UID队列,优先从该队列中取号
    const QUEUE_USERID_BACKUP = "queue:userid:backup"; //正常UID备用队列,主队列不可用从备用队列取号

    public static $_pattern = array(
        '/[1-9]*(5201314|5203344|5211314|5213344|13143344|33441314)[\d]*/',
        '/[1-9]*(520|521|1314|3344)$/',
        '/([\d])\1{2,}/',
        '/([\d])\1{1,}([\d])\2{1,}/',
        '/(\d)((?!\1)\d)\1\2\1\2\1\2/',
        '/(?:(?:0(?=1)|1(?=2)|2(?=3)|3(?=4)|4(?=5)|5(?=6)|6(?=7)|7(?=8)|8(?=9)){2}|(?:9(?=8)|8(?=7)|7(?=6)|6(?=5)|5(?=4)|4(?=3)|3(?=2)|2(?=1)|1(?=0)){2})\d/',
        '/((19|20)[0-9]{2}(((0[13578]|1[02])(0[1-9]|[12][0-9]|3[01]))|((0[469]|11)(0[1-9]|[12][0-9]|30))|(02(0[1-9]|[1][0-9]|2[0-8]))))|(((19|20)(0[48]|[2468][048]|[13579][26])|(([2][048])00))0229)/',
    );

    public $_uidList = array();

    /**
     * 判定靓号规则
     *
     * @return true 靓号 false 非靓号
     */
    public static function checkUidGood($uid)
    {
        foreach (self::$_pattern as $pattern) {
            if (preg_match($pattern, $uid)) {
                return true;
            }
        }

        return false;
    }

    public function addPersist($uid)
    {
        $dao_user_persist = new DAOUserPersist();
        $dao_user_persist->addUserPersist($uid);

        try {
            $cache = Cache::getInstance("REDIS_CONF_USER");
            $cache->lRemove(self::QUEUE_USERID_MASTER, $uid, 0);
        } catch(Exception $e) {
            Logger::log("uid_worker_err", "uid worker master addUidGood error", array("uid" => $uid, "errno" => $e->getCode(), "errmsg" => $e->getMessage()));
        }

        try {
            $cache  = Cache::getInstance("REDIS_CONF_FOLLOW");
            $cache->lRemove(self::QUEUE_USERID_BACKUP, $uid, 0);
        } catch(Exception $e) {
            Logger::log("uid_worker_err", "uid worker backup addUidGood error", array("uid" => $uid, "errno" => $e->getCode(), "errmsg" => $e->getMessage()));
        }

        return true;
    }

    private function addPersistBulk()
    {
        if(count($this->_uidList)) {
            $dao_user_persist = new DAOUserPersist();
            $dao_user_persist->addUserPersistBulk($this->_uidList);
        }
        return true;
    }
    public function getUserId()
    {
        try {
            $cache = Cache::getInstance("REDIS_CONF_USER");
            $uid = $cache->lPop(self::QUEUE_USERID_MASTER);
            if(!$uid) {
                throw new BizException(ERROR_BIZ_PASSPORT_QUEUE_UID_MASTER_ISNULL);
            }
        } catch(Exception $e) {
            Logger::log("user_persist_get_error", "uid worker master genUid error", array("errno" => $e->getCode(), "errmsg" => $e->getMessage()));
            $cache  = Cache::getInstance("REDIS_CONF_FOLLOW");
            $uid = $cache->lPop(self::QUEUE_USERID_BACKUP);
        }

        return $uid;
    }

    public function generate()
    {
        $cache = Cache::getInstance("REDIS_CONF_USER");
        $total = $cache->lSize(self::QUEUE_USERID_MASTER);
        if($total < 2000000) {
            for($i = 0; $i < 2000000 - $total; $i++) {
                $userid = Counter::increase(Counter::COUNTER_TYPE_PASSPORT_USERID, "generate");

                if(self::checkUidGood($userid)) {
                    $this->_uidList[] = $userid;
                    $i--;
                }else{
                    $cache->rPush(self::QUEUE_USERID_MASTER, $userid);
                }
            }
            $this->addPersistBulk();
        }

        $cache = Cache::getInstance("REDIS_CONF_FOLLOW");
        $total = $cache->lSize(self::QUEUE_USERID_BACKUP);
        if($total < 2000000) {
            for($i = 0; $i < 2000000 - $total; $i++) {
                $userid = Counter::increase(Counter::COUNTER_TYPE_PASSPORT_USERID, "generate");

                if(self::checkUidGood($userid)) {
                    $this->_uidList[] = $userid;
                    $i--;
                }else{
                    $cache->rPush(self::QUEUE_USERID_BACKUP, $userid);
                }

            }
            $this->addPersistBulk();
        }

        return true;
    }

    public static function getUidByRealUid($realUid)
    {
        $dao_user_persist = new DAOUserPersist();
        return $dao_user_persist->getUidByRealUid($realUid);
    }

    public static function getRealUidByUid($uid)
    {
        $dao_user_persist = new DAOUserPersist();
        return (int)$dao_user_persist->getRealUidByUid($uid);
    }

    public static function bind($uid, $realuid, $endtime, $starttime)
    {
        $dao_user_persist = new DAOUserPersist();
        return $dao_user_persist->updateBind($uid, $realuid, $endtime, $starttime);
    }

    public static function unBind($uid)
    {
        $dao_user_persist = new DAOUserPersist();
        return $dao_user_persist->updateBind($uid);
    }

    public static function setBindEndtime($uid, $realuid, $endtime)
    {
        $dao_user_persist = new DAOUserPersist();
        return $dao_user_persist->updateBind($uid, $realuid, $endtime);
    }

}
?>
