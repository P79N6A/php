<?php
require './../vendor/autoload.php';
use GuzzleHttp\Ring\Client\MockHandler;
use Elasticsearch\ClientBuilder;

// The connection class requires 'body' to be a file stream handle
// Depending on what kind of request you do, you may need to set more values here
$handler = new MockHandler([
    'status' => 200,
    'transfer_stats' => [
        'total_time' => 100
    ],
	//'body' => fopen('somefile.json')
]);
$builder = ClientBuilder::create();
$builder->setHosts(['172.18.130.48:9200']);
//$builder->setHandler($handler);
$client = $builder->build();


$params = [
    'index' => 'mapi_laputa_humans',
    'type' => 'laputa_humans',
    'id' => 2914
];

$response = $client->get($params);
print_r($response);