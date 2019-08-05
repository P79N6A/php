<?php
class DAOProxy
{
    private static $connects;

    protected $table = "";

    protected $shardid = 0;

    protected $config = array();

    public function __construct($sharid = 0)
    {
        $this->setShardId($sharid);
    }

    public function __call($name, $arguments)
    {
        $config = $this->_getShardConf();

        Interceptor::ensureNotEmpty($config, ERROR_PARAM_IS_EMPTY, "db config");

        $key = md5(serialize($config));

        if (! self::$connects[$key]) {
            self::$connects[$key] = MySQL::getInstance($config);
        }


        if (in_array($_SERVER["SERVER_ADDR"], array("172.21.0.6", "172.21.0.8")) && isset($_REQUEST["debug"])) {
            self::$connects[$key]->setDebug(true);
		}
		
        try {
            return call_user_func_array(array(self::$connects[$key], $name), $arguments);
        } catch (MySQLException $e) {
            Logger::warning($e->getMessage());
            throw $e;
            throw new BizException(ERROR_SYS_DB_SQL);
        }
    }

    public static function getInstance($shardid = 0)
    {
        static $ins;
        if (! isset($ins[$shardid])) {
            $ins[$shardid] = new static($shardid);
        }

        return $ins[$shardid];
    }

    protected function setShardId($shardid)
    {
        $this->shardid = $shardid;
    }

    protected function getShardId()
    {
        return $this->shardid;
    }

    protected function setTableName($table)
    {
        $this->table = $table;
    }

    protected function getTableName()
    {
        return $this->_getShard() == 1 ? $this->table : $this->table . "_" . $this->_getShardingIndex();
    }

    protected function setDBConf($name)
    {
        $config = Context::getConfig($name);
        $this->config = $config;
    }

    private function _getShardConf()
    {
        $range = $this->_getShardingRange();
        $index = $this->_getShardingIndex();
        $type = $this->_getShardingType();

        if ($type == "hash") {
            foreach ($range as $record) {
                if ($index >= $record["min"] && $index <= $record["max"]) {
                    return $this->config["hosts"][$record["confid"]];
                }
            }
        } else
            if ($type == "range") {
                return $this->config["hosts"][$range[$index]["confid"]];
            } else
                if ($type == "custom") {
                    foreach ($range as $record) {
                        if ($index >= $record["min"] && $index <= $record["max"]) {
                            return $this->config["hosts"][$record["confid"]];
                        }
                    }
                } else {
                    Interceptor::ensureNotFalse(isset($this->config["hosts"]) && ! empty($this->config["hosts"]), ERROR_PARAM_INVALID_FORMAT, "db config table:" . $this->getTableName());

                    return current(array_values($this->config["hosts"]));
                }

        return false;
    }

    private function _getShardingType()
    {
        if (false !== ($sharding = $this->_getShardingConf())) {
            return $sharding["type"];
        }

        return false;
    }

    private function _getShardingConf()
    {
        if (isset($this->config["sharding"]) && isset($this->config["sharding"][$this->table])) {
            return $this->config["sharding"][$this->table];
        }

        return false;
    }

    private function _getShard()
    {
        if (false !== ($sharding = $this->_getShardingConf())) {
            if ($sharding["type"] == "hash") {
                return $sharding["shard"];
            } else
                if ($sharding["type"] == "range") {
                    return 10000;
                } else
                    if ($sharding["type"] == "custom") {
                        return 10000;
                    }
        }

        return 1;
    }

    private function _getShardingIndex()
    {
        if (false !== ($sharding = $this->_getShardingConf())) {
            if ($sharding["type"] == "hash") {
                return abs(substr($this->shardid, - 3)) % $this->_getShard();
            } else
                if ($sharding["type"] == "range") {
                    foreach ($sharding["range"] as $index => $range) {
                        if ($this->shardid > $range["min"] && $this->shardid <= $range["max"]) {
                            return $index;
                        }
                    }
                } else
                    if ($sharding["type"] == "custom") {
                        $callback = $sharding["callback"];
                        return $callback($this->shardid, $sharding["range"]);
                    }
        }

        return - 1;
    }

    private function _getShardingRange()
    {
        if (false !== ($sharding = $this->_getShardingConf())) {
            return $sharding["range"];
        }

        return false;
    }
}
?>