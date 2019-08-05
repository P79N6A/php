<?php
class UserPersist
{
    const QUEUE_USERID_GENERATE = "queue:userid:generate"; //UID
    const QUEUE_USERID_MASTER   = "queue:userid:master"; //正常UID队列,优先从该队列中取号
    const QUEUE_USERID_BACKUP   = "queue:userid:backup"; //正常UID备用队列,主队列不可用从备用队列取号

    public function getUserId()
    {
        try {
            $cache = Cache::getInstance("REDIS_CONF_USER");

            $uid = $cache->lPop(self::QUEUE_USERID_MASTER);
            if(!$uid){
                throw new BizException(ERROR_QUEUE_UID_MASTER_ISNULL);
            }
        } catch(Exception $e) {
            Logger::log("user_persist_get_error", "uid worker master genUid error", array("errno" => $e->getCode(), "errmsg" => $e->getMessage()));
            $cache  = Cache::getInstance("REDIS_CONF_USER");
            $uid = $cache->lPop(self::QUEUE_USERID_BACKUP);
            if(!$uid){
                throw new BizException(ERROR_QUEUE_UID_MASTER_ISNULL);
            }
        }

        return $uid;
    }

    public function generate()
    {
        $cache = Cache::getInstance("REDIS_CONF_USER");
        $number = 1000000;

        if($cache->lSize(self::QUEUE_USERID_MASTER) < $number) {
            for($i = 0; $i < $number; $i++) {
                $userid = $cache->incrBy(self::QUEUE_USERID_GENERATE, 1);

                $cache->rPush(self::QUEUE_USERID_MASTER, $userid);
            }
        }

        $cache = Cache::getInstance("REDIS_CONF_USER");
        $total = $cache->lSize(self::QUEUE_USERID_BACKUP);
        if($total < $number) {
            for($i = 0; $i < $number - $total; $i++) {
                $userid = $cache->incrBy(self::QUEUE_USERID_GENERATE, 1);

                $cache->rPush(self::QUEUE_USERID_BACKUP, $userid);
            }
        }

        return true;
    }
}
?>
