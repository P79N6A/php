<?php
/**
 * mongoDB操作类
 * Class MongoDbClient
 */

class MongoDbClient
{
    private function connect($confArr = []) {
        try {
            $config = [
                'host'      => '127.0.0.1',
                'port'      => 27017,
                'db_name'   => 'test',
                'username'  => '',
                'password'  => '',
                'read_preference'   => '',
                'connect_timeout_ms'=> '',
                'socket_timeout_ms' => ''
            ];
            $confArr = $confArr ? $confArr : $config;
            $connStr = "mongodb://" . $confArr['host'] . ":" . $confArr['port'] . "/" . $confArr['db_name'];
            $options = array(
                'username' => $confArr['username'],
                'password' => $confArr['password'],
                'readPreference' => $confArr['read_preference'],
                'connectTimeoutMS' => intval($confArr['connect_timeout_ms']),
                'socketTimeoutMS' => intval($confArr['socket_timeout_ms']),
            );
            $mc = new MongoDB\Driver\Manager($connStr, $options);
            return $mc;
        } catch(Exception $e) {
            return false;
        }
    }

    public function find($query, $fields, $collection, $sort = array(), $limit = 0, $skip = 0) {
        $conn = $this->connect();
        if (empty($conn)) {
            return false;
        }
        try {
            $data = array();
            $options = array();
            if (!empty($query)) {
                $options['projection'] = array_fill_keys($fields, 1);
            }
            if (!empty($sort)) {
                $options['sort'] = $sort;
            }
            if (!empty($limit)) {
                $options['skip'] = $skip;
                $options['limit'] = $limit;
            }
            $mongoQuery = new \MongoDB\Driver\Query($query, $options);
            $readPreference = new \MongoDB\Driver\ReadPreference(\MongoDB\Driver\ReadPreference::RP_SECONDARY);
            $cursor = $conn->executeQuery($collection, $mongoQuery, $readPreference);
            foreach($cursor as $value) {
                $data[] = (array)$value;
            }
            return $data;
        } catch (Exception $e) {
            //记录错误日志
        }
        return false;
    }

    public function insert($addArr, $collection) {
        if (empty($addArr) || !is_array($addArr)) {
            return false;
        }
        $conn = $this->connect();
        if (empty($conn)) {
            return false;
        }
        try {
            $bulk = new \MongoDB\Driver\BulkWrite();
            $bulk->insert($addArr);
            $writeConcern = new \MongoDB\Driver\WriteConcern(\MongoDB\Driver\WriteConcern::MAJORITY, 6000);
            $result = $conn->executeBulkWrite($collection, $bulk, $writeConcern);
            if ($result->getInsertedCount()) {
                return true;
            }
        } catch (Exception $e) {
            //记录错误日志
        }
        return false;
    }

    public function delete($whereArr, $collection, $options = array()) {
        if (empty($whereArr)) {
            return false;
        }
        if (!isset($options['justOne'])) {
            $options = array(
                'justOne' => false,
            );
        }
        $conn = $this->connect();
        if (empty($conn)) {
            return false;
        }
        try {
            $bulk = new \MongoDB\Driver\BulkWrite();
            $bulk->delete($whereArr, $options);
            $writeConcern = new \MongoDB\Driver\WriteConcern(\MongoDB\Driver\WriteConcern::MAJORITY, 30000);
            $result = $conn->executeBulkWrite($collection, $bulk, $writeConcern);
            return true;
        } catch (Exception $e) {
            //记录错误日志
        }
        return false;
    }

    private function command($params, $dbName = 'test') {
        $conn = $this->connect();
        if (empty($conn)) {
            return false;
        }
        try {
            $cmd = new \MongoDB\Driver\Command($params);
            $result = $conn->executeCommand($dbName, $cmd);
            return $result;
        } catch (Exception $e) {
            //记录错误
        }
        return false;
    }

    public function count($query, $collection) {
        try {
            $cmd = array(
                'count' => $collection,
                'query' => $query,
            );
            $res = $this->command($cmd);
            $result = $res->toArray();
            return $result[0]->n;
        } catch (Exception $e) {
            //记录错误
        }
        return false;
    }

    public function distinct($key, $where, $collection) {
        try {
            $cmd = array(
                'distinct' => $collection,
                'key' => $key,
                'query' => $where,
            );
            $res = $this->command($cmd);
            $result = $res->toArray();
            return $result[0]->values;
        } catch (Exception $e) {
            //记录错误
        }
        return false;
    }

    public function aggregate($where, $group, $collection) {
        try {
            $cmd = array(
                'aggregate' => $collection,
                'pipeline' => array(
                    array(
                        '$match' => $where,
                    ),
                    array(
                        '$group' => $group,
                    ),
                ),
                'explain' => false,
            );
            $res = $this->command($cmd);
            if (!$res) {
                return false;
            }
            $result = $res->toArray();
            return $result[0]->total;
        } catch (Exception $e) {
            //记录错误
        }
        return false;
    }


}