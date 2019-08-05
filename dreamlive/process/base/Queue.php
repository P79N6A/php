<?php

class QueueException extends Exception
{

    const CODE_PRODUCT_NOT_SUPPORT = 1000;

    const CODE_QUEUE_NAME_INVALID = 1001;

    const CODE_SERVER_NOT_CONFIGURATION = 1002;

    public function __construct($message, $code = 0)
    {
        /* {{{ */
        $message = "Queue Exception[$code]:$message ($code)";
        parent::__construct($message, $code);
    } /* }}} */
}

/**
 * 注:
 * 优先队列和延迟队列由于锁的原因并发消费意义不大,最好使用一个消费转移到普通队列后再并行消费
 */
class Queue
{

    private $_product = "";

    private $_config = array();

    public function __construct($product)
    {
        /* {{{ */
        $this->_product = $product;
        $this->_config = $GLOBALS["QUEUE_CONF"];
    }
    /* }}} */
    private function getRedis($name)
    {
        /* {{{ */
        static $redises;
        
        if (! isset($this->_config[$this->_product]) || empty($this->_config[$this->_product])) {
            throw new QueueException("product not support", QueueException::CODE_PRODUCT_NOT_SUPPORT);
        }
        
        if (! isset($this->_config[$this->_product]["queue"][$name])) {
            throw new QueueException("queue name not support", QueueException::CODE_QUEUE_NAME_INVALID);
        }
        
        try {
            $serverid = $this->_config[$this->_product]["queue"][$name]["server"];
            
            $config = $this->_config[$this->_product]["servers"][$serverid];
            if (! $config || ! is_array($config) || empty($config)) {
                throw new QueueException("server not configuration", QueueException::CODE_SERVER_NOT_CONFIGURATION);
            }
            
            if (count($config) > 1) {
                $config_key = array_rand($config);
            } else {
                $config_key = 0;
            }
            
            if (isset($redises[$serverid . "_" . $config_key]) && $redises[$serverid . "_" . $config_key] instanceof Redis && $redises[$serverid . "_" . $config_key]->ping() == "+PONG") {
                return $redises[$serverid . "_" . $config_key];
            }
        } catch (Exception $e) {
            ;
        }
        
        $this_config = $config[$config_key];
        
        $redises[$serverid . "_" . $config_key] = new Redis();
        $redises[$serverid . "_" . $config_key]->connect($this_config["host"], $this_config["port"], $this_config["timeout"]);
        $redises[$serverid . "_" . $config_key]->auth($this_config["password"]);
        
        return $redises[$serverid . "_" . $config_key];
    }
    /* }}} */
    public function isAlive()
    {
        /* {{{ */
        foreach ($this->_config[$this->_product]["servers"] as $config_arr) {
            foreach ($config_arr as $config) {
                $redis = new Redis();
                $redis->connect($config["host"], $config["port"], $config["timeout"]);
                $redis->auth($config["password"]);
                
                if ($redis->ping() != "+PONG") {
                    return false;
                }
                
                $redis->close();
            }
        }
        
        return true;
    }
    /* }}} */
    public function getTraceId($name)
    {
        /* {{{ */
        $arr = gettimeofday();
        $utime = $arr['sec'] * 1000000 + $arr['usec'];
        
        return (crc32(gethostname()) & 0x7FFFFFF | 0x8000000) . ($utime & 0x7FFFFFF | 0x8000000);
    }
    /* }}} */
    public function getKey($name)
    {
        /* {{{ */
        return $this->_product . "_" . $name;
    }
    /* }}} */
    public function getType($name)
    {
        /* {{{ */
        return $this->_config[$this->_product]["queue"][$name]["type"];
    }
    /* }}} */
    public static function getInstance($product)
    {
        /* {{{ */
        static $queue;
        
        if ($queue == null || ! ($queue instanceof Queue)) {
            $queue = new Queue($product);
        }
        
        return $queue;
    }
    /* }}} */
    public function addRouteQueue($name, $data, $rank)
    {
        /* {{{ */
        $queues = $this->_config[$this->_product]["queue"][$name]["queues"];
        
        foreach ($queues as $name) {
            switch ($this->getType($name)) {
            case QUEUE_TYPE_NORMAL:
                $this->addNormalQueue($name, $data);
                break;
            case QUEUE_TYPE_PRIORITY:
                $this->addPriorityQueue($name, $data, $rank);
                break;
            case QUEUE_TYPE_DELAY:
                $this->addDelayQueue($name, $data, $rank);
                break;
            }
        }
        
        return true;
    }
    /* }}} */
    public function addPriorityQueue($name, $data, $rank)
    {
        /* {{{ */
        $redis = $this->getRedis($name);
        $key = $this->getKey($name);
        return $redis->zadd($key, $rank, $data);
    }
    /* }}} */
    public function getPriorityQueue($name)
    {
        /* {{{ */
        $redis = $this->getRedis($name);
        $key = $this->getKey($name);
        
        $lock = "lock_queue_$name";
        if ($redis->get($lock)) {
            return false;
        }
        
        $list = $redis->zRange($key, 0, 1);
        $data = array_shift($list);
        
        $redis->zDelete($key, $data);
        $redis->delete($lock);
        
        return $data;
    }
    /* }}} */
    public function addDelayQueue($name, $data, $rank)
    {
        /* {{{ */
        if (! $rank) {
            $message = unserialize($data);
            $rank = time();
            $message["rank"] = $rank;
            $data = serialize($message);
        }
        
        return $this->addPriorityQueue($name, $data, $rank);
    }
    /* }}} */
    public function getDelayQueue($name)
    {
        /* {{{ */
        $data = $this->getPriorityQueue($name);
        $message = unserialize($data);
        
        if (isset($message["rank"]) && $message["rank"] > time()) {
            $this->addPriorityQueue($name, $data, $message["rank"]);
            return false;
        }
        
        return $data;
    }
    /* }}} */
    public function addNormalQueue($name, $data)
    {
        /* {{{ */
        $redis = $this->getRedis($name);
        $key = $this->getKey($name);
        
        return $redis->lPush($key, $data);
    }
    /* }}} */
    public function getNormalQueue($name)
    {
        /* {{{ */
        $redis = $this->getRedis($name);
        $key = $this->getKey($name);
        $data = $redis->rpop($key);
        
        return $data;
    }
    /* }}} */
    public function addRescueQueue($name, $data)
    {
        /* {{{ */
        $redis = $this->getRedis($name);
        $key = $this->getKey($name);
        
        $bak_key = $key . "_bak" . date("ymd");
        $redis->lPush($bak_key, serialize($data));
        $redis->expire($bak_key, 172800);
        
        return true;
    }
    /* }}} */
    public function addQueue($name, $params, $rank = 0)
    {
        /* {{{ */
        $traceid = $this->getTraceId($name);
        
        $message = array(
            "traceid" => $traceid,
            "addtime" => time(),
            "retry" => 0,
            "rank" => $rank,
            "params" => $params
        );
        $data = serialize($message);
        
        switch ($this->getType($name)) {
        case QUEUE_TYPE_NORMAL:
            $ret = $this->addNormalQueue($name, $data);
            break;
        case QUEUE_TYPE_PRIORITY:
            $ret = $this->addPriorityQueue($name, $data, $rank);
            break;
        case QUEUE_TYPE_DELAY:
            $ret = $this->addDelayQueue($name, $data, $rank);
            break;
        case QUEUE_TYPE_ROUTE:
            $ret = $this->addRouteQueue($name, $data, $rank);
            break;
        default:
            break;
        }
        
        return $ret ? $traceid : 0;
    }
    /* }}} */
    public function getQueue($name)
    {
        /* {{{ */
        switch ($this->getType($name)) {
        case QUEUE_TYPE_NORMAL:
            $data = $this->getNormalQueue($name);
            break;
        case QUEUE_TYPE_PRIORITY:
            $data = $this->getPriorityQueue($name);
            break;
        case QUEUE_TYPE_DELAY:
            $data = $this->getDelayQueue($name);
            break;
        default:
            break;
        }
        
        if ($data) {
            return unserialize($data);
        }
        
        return false;
    }
    /* }}} */
    private function _resetQueue($name, $params)
    {
        /* {{{ */
        $type = $this->getType($name);
        $params["retry"] ++;
        $params["resetime"] = time();
        $data = serialize($params);
        
        switch ($type) {
        case QUEUE_TYPE_NORMAL:
            return $this->addNormalQueue($name, $data);
                break;
        case QUEUE_TYPE_PRIORITY:
            return $this->addPriorityQueue($name, $data, $params["rank"]);
                break;
        case QUEUE_TYPE_DELAY:
            return $this->addDelayQueue($name, $data, $params["rank"]);
                break;
        default:
            break;
        }
        
        return false;
    }
    /* }}} */
    public function rescueQueue($name, $date)
    {
        /* {{{ */
        $serverid = $this->_config[$this->_product]["queue"][$name]["server"];
        $config_arr = $this->_config[$this->_product]["servers"][$serverid];
        
        $key = $this->getKey($name);
        $bak_key = $key . "_bak" . $date;
        
        foreach ($config_arr as $config) {
            $redis = new Redis();
            $redis->connect($config["host"], $config["port"], $config["timeout"]);
            $redis->auth($config["password"]);
            
            print "Host: {$config["host"]} Port: {$config["port"]} \n";
            
            while (true) {
                $data = $redis->rpop($bak_key);
                if (! $data) {
                    break;
                }
                $data = unserialize($data);
                
                print "Data: " . var_export($data, true) . " \n";
                
                $this->_resetQueue($name, $data);
            }
            
            $redis->close();
        }
    }
    /* }}} */
    public function getLength($name)
    {
        /* {{{ */
        $redis = $this->getRedis($name);
        
        $key = $this->getKey($name);
        
        switch ($this->getType($name)) {
        case QUEUE_TYPE_NORMAL:
            $len = $redis->lSize($key);
            break;
        case QUEUE_TYPE_PRIORITY:
        case QUEUE_TYPE_DELAY:
            $len = $redis->zCard($key);
            break;
        }
        
        return $len ? $len : 0;
    } /* }}} */
}
// $queue = Queue::getInstance("weimi");
/*
 * //normal queue
 * $traceid = $queue->addQueue("test_normal", array("rand"=>rand(0, 1000)));
 * var_dump($traceid);
 *
 * $record = $queue->getQueue("test_normal");
 * print_r($record);
 */

/*
 * //priority queue
 * $traceid = $queue->addQueue("test_priority", array("rand"=>rand(0, 1000)), 10000);
 * var_dump($traceid);
 * $traceid = $queue->addQueue("test_priority", array("rand"=>rand(0, 1000)), 10001);
 * var_dump($traceid);
 * print "\n";
 * var_dump($traceid);
 *
 * $record = $queue->getQueue("test_priority");
 * var_dump($record);
 */

/*
 * //delay queue
 * $traceid = $queue->addQueue("test_delay", array("rand"=>1000));
 * var_dump($traceid);
 * //$traceid = $queue->addQueue("test_delay", array("rand"=>1001), time() + 30);
 * var_dump($traceid);
 *
 * $record = $queue->getQueue("test_delay");
 * print_r($record);
 */

/*
 * //route queue
 * $traceid = $queue->addQueue("test_route", array("rand"=>8888), 9999);
 * var_dump($traceid);
 */
?>
