<?php

//连接mongo
$manager = new \MongoDB\Driver\Manager("mongodb://127.0.0.1:27017/apps");
var_dump($manager);
//添加mongo数据
$bulk = new \MongoDB\Driver\BulkWrite;
$bulk->insert(['x' => 1, 'name'=>'dadavm', 'url' => 'http://www.dadavm.cn']);
$bulk->insert(['x' => 2, 'name'=>'Google', 'url' => 'http://www.google.com']);
$bulk->insert(['x' => 3, 'name'=>'taobao', 'url' => 'http://www.taobao.com']);
$manager->executeBulkWrite('apps.test', $bulk);
var_dump("添加成功!\n");
//删除
$bulk = new \MongoDB\Driver\BulkWrite;
$bulk->delete(['x' => 1], ['limit' => 1]);   // limit 为 1 时，删除第一条匹配数据
$bulk->delete(['x' => 2], ['limit' => 0]);   // limit 为 0 时，删除所有匹配数据
$writeConcern = new \MongoDB\Driver\WriteConcern(\MongoDB\Driver\WriteConcern::MAJORITY, 1000);
$result = $manager->executeBulkWrite('apps.test', $bulk, $writeConcern);
//修改
$bulk = new \MongoDB\Driver\BulkWrite;
$bulk->update(
    ['x' => 2],
    ['$set' => ['name' => '简书', 'url' => 'jianshu.runoob.com']],
    ['multi' => false, 'upsert' => false]
);
$writeConcern = new \MongoDB\Driver\WriteConcern(\MongoDB\Driver\WriteConcern::MAJORITY, 1000);
$result = $manager->executeBulkWrite('h5maker.test', $bulk, $writeConcern);
//查询
// 查询数据
$filter = ['x' => ['$gt' => 1]];
$options = [
    'projection' => ['_id' => 0],
    'sort' => ['x' => -1],
];
$query = new \MongoDB\Driver\Query($filter, $options);
$cursor = $manager->executeQuery('apps.test', $query);

foreach ($cursor as $document) {
    echo '<pre>';
    print_r($document);
}
exit;