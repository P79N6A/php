<?php
class RedisLock
{
    private $retryDelay=200;
    private $retryCount=3;
    private $clockDriftFactor = 0.01;

    private $name="";
    private $expire=10000;

    private $redis=null;

    function __construct($name="",$expire=10000)
    {
        $this->name=!empty($name)?md5($name):md5(microtime());
        $this->expire=!empty($expire)&&$expire>0?$expire:10000;
        $this->redis=Cache::getInstance("REDIS_CONF_CACHE");
        if (!$this->redis) { throw new Exception("redis is null");
        }
    }

    public static function syn(callable $fun,array $params=[])
    {
        $ins=new RedisLock();
        $token=$ins->lock();
        if ($token) {
            $fun($params);
            $ins->_unlock($token);
        }
    }

    public function lock()
    {
        while (true){
            $lock=$this->_lock($this->name, $this->expire);
            if ($lock) { return $lock ;
            }
        }
    }

    private function _lock($resource, $ttl)
    {
        $token = uniqid();
        $retry = $this->retryCount;
        do {
            $startTime = microtime(true) * 1000;
            $setResult=$this->lockInstance($this->redis, $resource, $token, $ttl);

            $drift = ($ttl * $this->clockDriftFactor) + 2;
            $validityTime = $ttl - (microtime(true) * 1000 - $startTime) - $drift;
            if ($setResult && $validityTime > 0) {
                return $token;
            } else {
                $this->unlockInstance($this->redis, $resource, $token);
            }
            $delay = mt_rand(floor($this->retryDelay / 2), $this->retryDelay);
            usleep($delay * 1000);
            $retry--;
        } while ($retry > 0);
        return false;
    }

    public function unlock($token)
    {
        $this->_unlock(['resource'=>$this->name,'token'=>$token]);
    }

    private function _unlock(array $lock)
    {
        $resource = $lock['resource'];
        $token    = $lock['token'];
        $this->unlockInstance($this->redis, $resource, $token);
    }

    private function lockInstance($instance, $resource, $token, $ttl)
    {
        return $instance->set($resource, $token, ['NX', 'PX' => $ttl]);
    }
    private function unlockInstance($instance, $resource, $token)
    {
        $script = '
            if redis.call("GET", KEYS[1]) == ARGV[1] then
                return redis.call("DEL", KEYS[1])
            else
                return 0
            end
        ';
        return $instance->eval($script, [$resource, $token], 1);
    }
}

/*#example
RedisLock::syn(function($params){
    #do something
},['a'=>1]);*/