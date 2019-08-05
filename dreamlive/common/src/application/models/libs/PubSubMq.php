<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/9
 * Time: 19:37
 */
class PubSubMq
{
    const CACHE_REDIS_CONFIG_NAME="REDIS_CONF_CACHE";
    private $redis=null;
    private $name="";

    private function getConfig()
    {
        $config=Context::getConfig(self::CACHE_REDIS_CONFIG_NAME);
        if (!isset($config['hosts'][0])) { throw new Exception("redis config is null");
        }
        $hosts=$config['hosts'][0];
        if (!$hosts) { throw new Exception("redis config is null");
        }
        $master=$hosts['master'];
        return [$master['host'],$master['port'],$master['timeout'],$master['password']];
    }

    public function __construct($name)
    {
        $this->name=$name;
        if (!$this->redis) {
            list($host, $port, $timeout,$auth) = $this->getConfig();
            $redis = new \Redis();
            $redis->pconnect($host, $port, $timeout);
            $redis->auth($auth);
            $redis->setOption(Redis::OPT_READ_TIMEOUT, -1);
            // $this->redis=Cache::getInstance("REDIS_CONF_CACHE");
            $this->redis=$redis;
        }
    }
    private static function getInstance()
    {

    }
    private function _publish(array $msg)
    {
        $this->redis->publish($this->name, json_encode($msg));
    }

    //fun $redis $chan $msg
    private function _subscribe($fun)
    {
        $this->redis->subscribe(
            array($this->name), function ($redis,$chan,$msg) use ($fun) {
                if ($chan==$this->name) {
                    $params=@json_decode($msg, true);
                    $fun($params);
                }
            }
        );
    }

    public static function publish($chan,array $msg)
    {
        $slf=new PubSubMq($chan);
        return $slf->_publish($msg);
    }

    public static function subscribe($chan,$fun)
    {
        $slf=new PubSubMq($chan);
        return $slf->_subscribe($fun);
    }

}