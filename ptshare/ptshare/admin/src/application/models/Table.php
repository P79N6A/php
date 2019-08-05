<?php
class Table
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

        if (in_array($_SERVER["SERVER_ADDR"], array("192.168.1.201", "192.168.1.202")) && isset($_REQUEST["debug"])) {
            self::$connects[$key]->setDebug(true);
		}

		self::$connects[$key]->setWaitTimeOut(60);
		self::$connects[$key]->setAutoReconnect(true);

		ini_set('mysql.connect_timeout', 60);
		ini_set('default_socket_timeout', 60);

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

    public function addRecord($info)
    {
        return $this->insert($this->getTableName(), $info);
    }

    public function resetRecord($info)
    {
        return $this->replace($this->getTableName(), $info);
    }

    public function setRecord($relateid, $info)
    {
        return $this->update($this->getTableName(), $info, "{$this->getPrimary()}=?", $relateid);
    }

    public function getRecord($relateid)
    {
        $sql = "select * from {$this->getTableName()} where {$this->getPrimary()}=?";
        return $this->getRow($sql, $relateid);
    }

    public function delRecord($relateid)
    {
        $sql = "delete from {$this->getTableName()} where {$this->getPrimary()}=?";
        return $this->Execute($sql, $relateid);
    }

    public function getAllRecords($start = 0, $num = 0, $order = "")
    {
        return $this->getRecords("", "", $start, $num, $order);
    }

    public function getRecords($condition = "", $params = array(), $start = 0, $num = 0, $order = "")
    {
        $sql = "select * from {$this->getTableName()}";
        $sql.= $condition != "" ? " where " . $condition : "";
        $sql.= " order by " . ($order != "" ? $order : $this->getPrimary() . " desc");
        $sql.= $num > 0 ? " limit $start, $num" : "";

        $lists = $this->getAll($sql, $params);
        $total = $this->getCount($condition,$params);

        return array($total, $lists);
    }

    public function exists($relateid)
    {
        $sql = "select count(*) from {$this->getTableName()} where {$this->getPrimary()}=?";
        return $this->getOne($sql, $relateid) ? true : false;
    }

    public function getCount($condition = "", $params = array())
    {
        $sql = "select count({$this->getPrimary()}) from {$this->getTableName()}";
        $sql.= $condition != "" ? " where " . $condition : "";
        return (int)$this->getOne($sql, $params);
    }

    public function setPrimary($field = "id")
    {
        $this->primary = $field;
    }

    public function getPrimary()
    {
        return $this->primary;
    }
}
?>
