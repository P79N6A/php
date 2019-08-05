<?php
class Cache
{
    private static $connects;

    private $config;

    public static function getInstance($name)
    {
        static $instance;

        if (! isset($instance[$name]) || ! ($instance[$name] instanceof Cache)) {
            $instance[$name] = new Cache($name);
        }

        return $instance[$name];
    }

    public function __construct($name)
    {
        $this->config = Context::getConfig($name);
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

    public function __call($command, $arguments)
    {
        $config = $this->config;

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
            var_dump($e->getMessage());
            Logger::warning($e->getMessage());
            throw new BizException(ERROR_SYS_REDIS);
        }

        try {
            return call_user_func_array(array($redis, $command), $arguments);
        } catch (RedisException $e) {
            var_dump($e->getMessage());
            Logger::warning($e->getMessage());
            throw new BizException(ERROR_SYS_REDIS);
        }
    }
}
?>