<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/20
 * Time: 15:28
 */
class MyLock
{
    const CACHE_REDIS_CONFIG_NAME="REDIS_CONF_CACHE";
    const TIMEOUT=3;
    const LOCK_NAME="my_lotto_game_lock";
    const TTL=1000;

    private static $servers=[];

    private static function getCacheConfig()
    {
        $config=Context::getConfig(self::CACHE_REDIS_CONFIG_NAME);
        if (!isset($config['hosts'][0])) { throw new Exception("redis config is null");
        }
        $hosts=$config['hosts'][0];
        $master=$hosts['master'];
        self::$servers[]=[$master['host'],$master['port'],self::TIMEOUT,$master['password']];
        /* foreach ($hosts['slave'] as $i){
            self::$servers[]=[$i['host'],$i['port'],self::TIMEOUT,$i['password']];
        }*/
    }
    public static function lock($fun,array $params,$lockName,$ttl=1000)
    {
        self::getCacheConfig();
        $re=[];
        $redLock=new RedLock(self::$servers);
        $lock=$redLock->lock($lockName, $ttl);
        if ($lock) {
            if (is_callable($fun)) {
                $re= $fun($params);
            }
            $redLock->unlock($lock);
        }else{
            throw  new Exception("sys is busy !");
        }
        return $re;
    }
}

class RedLock
{
    private $retryDelay;
    private $retryCount;
    private $clockDriftFactor = 0.01;
    private $quorum;
    private $servers = array();
    private $instances = array();
    function __construct(array $servers, $retryDelay = 200, $retryCount = 3)
    {
        $this->servers = $servers;
        $this->retryDelay = $retryDelay;
        $this->retryCount = $retryCount;
        $this->quorum  = min(count($servers), (count($servers) / 2 + 1));
    }
    public function lock($resource, $ttl)
    {
        $this->initInstances();
        $token = uniqid();
        $retry = $this->retryCount;
        do {
            $n = 0;
            $startTime = microtime(true) * 1000;
            foreach ($this->instances as $instance) {
                if ($this->lockInstance($instance, $resource, $token, $ttl)) {
                    $n++;
                }
            }
            // Add 2 milliseconds to the drift to account for Redis expires
            // precision, which is 1 millisecond, plus 1 millisecond min drift
            // for small TTLs.
            $drift = ($ttl * $this->clockDriftFactor) + 2;
            $validityTime = $ttl - (microtime(true) * 1000 - $startTime) - $drift;
            if ($n >= $this->quorum && $validityTime > 0) {
                return [
                    'validity' => $validityTime,
                    'resource' => $resource,
                    'token'    => $token,
                ];
            } else {
                foreach ($this->instances as $instance) {
                    $this->unlockInstance($instance, $resource, $token);
                }
            }
            // Wait a random delay before to retry
            $delay = mt_rand(floor($this->retryDelay / 2), $this->retryDelay);
            usleep($delay * 1000);
            $retry--;
        } while ($retry > 0);
        return false;
    }
    public function unlock(array $lock)
    {
        $this->initInstances();
        $resource = $lock['resource'];
        $token    = $lock['token'];
        foreach ($this->instances as $instance) {
            $this->unlockInstance($instance, $resource, $token);
        }
    }
    private function initInstances()
    {
        if (empty($this->instances)) {
            foreach ($this->servers as $server) {
                list($host, $port, $timeout,$auth) = $server;
                $redis = new \Redis();
                $redis->connect($host, $port, $timeout);
                $redis->auth($auth);
                $this->instances[] = $redis;
            }
        }
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