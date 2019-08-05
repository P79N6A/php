<?php

/**
 * Class MongodbClient
 * mongo操作类
 *如果需要自己也可以改成单例模式
 */
class MongoClientDemo{

    protected $mongodb;
    protected $dbname;
    protected $collection;
    protected $bulk;
    protected $writeConcern;
    public function __construct($config)
    {
        if (!$config['dbname'] || !$config['collection']) {
            # code...
            exit('参数错误');
        }
        $this->mongodb = new \MongoDB\Driver\Manager("mongodb://localhost:27017");
        $this->dbname = $config['dbname'];
        $this->collection = $config['collection'];
        $this->bulk = new \MongoDB\Driver\BulkWrite();
        $this->writeConcern   = new \MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 100);
    }

    /**
     * @param array $where
     * @param array $option
     * @return string
     * @throws \MongoDB\Driver\Exception\Exception
     */
    public function query($where = [], $option = [])
    {
        $query = new MongoDB\Driver\Query($where,$option);
        $result = $this->mongodb->executeQuery("$this->dbname.$this->collection", $query);
        $data = [];
        if ($result) {
            # code...
            foreach ($result as $key => $value) {
                # code...
                array_push($data, $value);
            }
        }

        return json_encode($data);
    }

    /**
     * @param array $where
     * @return int
     * @throws \MongoDB\Driver\Exception\Exception
     */
    public function getCount($where = [])
    {
        $command = new MongoDB\Driver\Command(['count' => $this->collection,'query'=>$where]);
        $result = $this->mongodb->executeCommand($this->dbname,$command);
        $res = $result->toArray();
        $cnt = 0;
        if ($res) {
            # code...
            $cnt = $res[0]->n;
        }

        return $cnt;
    }

    /**
     * @param array $where
     * @param int $page
     * @param int $limit
     * @return string
     * @throws \MongoDB\Driver\Exception\Exception
     */
    public function page($where = [], $page = 1, $limit = 10)
    {

        $count = $this->getCount($where);
        $data['count'] = $count;
        $endpage = ceil($count/$limit);
        if ($page>$endpage) {
            # code...
            $page = $endpage;
        }elseif ($page <1) {
            $page = 1;
        }
        $skip = ($page-1)*$limit;
        $options = [
            'skip'=>$skip,
            'limit'    => $limit
        ];
        $data['data'] = $this->query($where,$options);
        $data['page'] = $endpage;
        return json_encode($data);
    }

    /**
     * @param array $where
     * @param array $update
     * @param bool $upsert
     * @return int|null
     *
     */
    public function update($where = [], $update = [], $upsert =  false)
    {
        $this->bulk->update($where,['$set' => $update], ['multi' => true, 'upsert' => $upsert]);
        $result = $this->mongodb->executeBulkWrite("$this->dbname.$this->collection", $this->bulk, $this->writeConcern);
        return $result->getModifiedCount();
    }

    /**
     * @param array $data
     * @return mixed
     *
     */
    public function insert($data=[])
    {
        $result = $this->bulk->insert($data);
        return $result->getInsertedCount();
    }

    /**
     * @param array $where
     * @param int $limit
     * @return mixed
     *
     */
    public function delete($where=[],$limit=1)
    {
        $result = $this->bulk->delete($where,['limit'=>$limit]);
        return $result->getDeletedCount();
    }

}
//实例化调用
$action = $_GET['action']?:exit('参数错误');
$page = $_GET['page']?:1;
$dbname = "test";
$where = json_decode($_GET['where'],true)?:[];
$limit = $_GET['limit']?:'10';
$data = json_decode($_GET['data'],true)?:[];
$option = json_decode($_GET['option'],true)?:[];
$collection = $_GET['collection'];
$mongodb = new MongoClientDemo(['dbname' => $dbname,'collection' => $collection]);

if ($action=='getCount') {
    # code...
    $data = $mongodb->getCount($where);
}elseif($action=='insert')
{
    $data = $mongodb->insert($data);
}
elseif($action=='update')
{
    $data = $mongodb->update($where, $data);
}
elseif($action=='delete')
{
    $data = $mongodb->delete($where);
}
elseif($action=='query')
{
    $data = $mongodb->query($where, $option);
}elseif($action=='page')
{
    $data = $mongodb->page($where, $page, $limit);
}

echo $data;
