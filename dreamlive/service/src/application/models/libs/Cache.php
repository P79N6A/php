<?php
class Cache
{
    private static $connects;

    private $shardid;

    private $configs;

    private $master = false;

    public static function getInstance($name, $shardid = 0)
    {
        static $instance;
        
        if (! isset($instance[$name]) || ! ($instance[$name] instanceof Cache)) {
            $instance[$name] = new Cache($name, $shardid);
        }
        
        return $instance[$name];
    }

    public function __construct($name, $shardid = 0)
    {
        /* {{{ */
        $this->shardid = $shardid;
        $this->configs = Context::getConfig($name);
    }
    /* }}} */
    public function setShardId($shardid)
    {
        $this->shardid = $shardid;
    }

    protected function getConfig($force_master = false)
    {
        $configs = $this->configs;
        
        // Write-Back hash, Write-Through range
        if (isset($configs["sharding"]) && ! empty($configs["sharding"]) && in_array(
            $configs["sharding"]["type"], array(
            "hash",
            "range"
            )
        ) && $this->shardid
        ) {
            switch ($configs["sharding"]["type"]) {
            case "hash":
                $group = $this->shardid % count($configs["hosts"]);
                break;
            case "range":
                $group = 0;
                foreach ($configs["sharding"]["range"] as $range) {
                    if ($this->shardid >= $range["min"] && $this->shardid < $range["max"]) {
                        $group = $range["group"];
                    }
                }
                break;
            default:
                break;
            }
        } else {
            $group = 0;
        }
        
        // 每个group构成一个cell
        $cell = $configs["hosts"][$group];
        $slave = $cell["slave"][rand(0, count($cell["slave"]) - 1)];
        $master = $cell["master"];
        
        return $force_master ? $master : $slave;
    }

    public function setMaster($force_master = false)
    {
        $this->master = $force_master;
    }

    public function add($key, $value, $seconds = 0)
    {
        if (! $this->setnx($key, $value)) {
            return false;
        }
        
        if ($seconds > 0) {
            $this->expire($key, $seconds);
        }
        
        return true;
    }

    public function delete($key)
    {
        $this->del($key);
    }

    public function __call($name, $arguments)
    {
        $write_command = array("set","setex","setnx","del","incr","decr","hset","hmset","hdel","hexpire","zadd","zdelete","sAdd","sRemove","expire");
        
        if ($this->master || in_array($name, $write_command)) {
            $config = $this->getConfig(true);
        } else {
            $config = $this->getConfig(false);
        }

        $key = md5(serialize($config));

        try {
            if (isset(self::$connects[$key]) && self::$connects[$key] instanceof Redis && self::$connects[$key]->ping() == "+PONG") {
                $redis = self::$connects[$key];
            } else {
                $redis = new Redis();
                $redis->connect($config["host"], $config["port"], $config['timeout'] ? $config['timeout'] : 3);
                $redis->auth($config["password"]);
                self::$connects[$key] = $redis;
            }
        } catch (RedisException $e) {
            Logger::warning($e->getMessage());
            throw new BizException(ERROR_SYS_REDIS);
        }
        
        try {
            return call_user_func_array(array($redis, $name), $arguments);
        } catch (RedisException $e) {
            Logger::warning($e->getMessage());
            throw new BizException(ERROR_SYS_REDIS);
        }
    }
}
?>
